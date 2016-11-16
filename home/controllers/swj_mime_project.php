<?php
/*
 *我的申报控制器
 *author 林科 
 *id代表的项目id，sbid代表的是申报项目id
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class Swj_mime_project extends MY_Controller{
	private $table_ ;//表的前缀
	private $ckstext;//审核状态文字
	private $userInfo;//登录用户信息
	private $table;//项目表
	function Swj_mime_project(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_common_sms');//发送短信公共模型
		$this->load->model('M_common_category_data','ccd'); 
		$this->load->model('M_swj_project_template', 'mswpt');//项目模板选择模型
		$this->load->model('M_swj_info_hdba','msih');//活动备案模型
		$this->load->model('M_swj_register_xiehuiorjigou', 'msrx');//协会或结构模型
		$this->load->model('M_user','user');
		$this->load->model('M_swj_register_dsqy','dsqy');
		$this->load->model('M_swj_fujian','fujian');//附件模型
		$this->load->model('M_swj_register_xiehuiorjigou','xhjg');
		$this->load->library('session');
		$this->table_ =table_pre('real_data');
		$this->userInfo = $this->parent_getsession();//获取登录信息
		$this->table = 'swj_project_shenbao';//项目申报表
		$this->ckstext = array('0'=>'未审核','10'=>'初审通过'
						,'20'=>'评审完毕','30'=>'给予扶持'
						,'-10'=>'初审不通过','-30'=>'不给予扶持'
						,'99'=>'临时保存');
	}
	//列表页面
	function index(){
		
		// var_dump($userInfo);exit;
		if (!$this->userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$action  = $this->input->get_post("action");	
		$action_array = array("show","ajax_data");
		$action  = !in_array($action,$action_array)?'show':$action ;
/*		$status  =  $this->input->get_post('status');//状态，1进行中或者其他过期等状态
		$status  =  !empty($status)?$status:1;//默认为显示进行中的申报项目*/
		if ($action == 'show') {
			// $data = array('status' => $status);
			$this->load->view(__TEMPLET_FOLDER__."/project/mime_project");
		} elseif($action == 'ajax_data') {
			$this->ajax_data();
		}
		
	}
	//ajax get data
	private function ajax_data() {

		$result = array('resultinfo' => array('list' => '', 'errmsg' => ''), 'resultcode' => '99999');
		if (!$this->userInfo) {
			echo json_encode($result);
			exit();
		}
		$userid   = $this->userInfo['userid'];//用户id
		$this->load->library("common_page");
		$page = intval($this->input->get_post("page"));
		if($page <=0 ){
			$page = 1 ;
		}
		$per_page = 20;//每一页显示的数量
		$limit = ($page-1)*$per_page;
		$limit.=",{$per_page}";

		$nowTime =  time();//当前时间
		$where 	 =  " where 1=1 and sps.isdel='0' 
					and sps.userid='$userid' and sps.checkstatus!='99' ";//查询条件
		$leftjoin=  " left join swj_project sp on sp.id=sps.project_id ";
		$status  =  $this->input->get_post('status');//状态，审核未审核
		
		$name = daddslashes(html_escape(strip_tags($this->input->get_post("projectname"))));//项目名称
		if(!empty($name)){//查询项目名称
			$where .= " AND sp.`title` LIKE '%{$name}%'";
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM {$this->table} sps {$leftjoin} {$where} ";
		// file_put_contents('F:/aaa.txt', $sql_count);
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_log = "SELECT sps.*,sp.title,sp.starttime,sp.endtime,sp.check_starttime,sp.check_endtime FROM {$this->table} sps {$leftjoin} {$where} order by sps.id desc limit  {$limit}";	
		// file_put_contents('F:/aaa.txt', $sql_log);
		$list = $this->M_common->querylist($sql_log);
		foreach ($list as $key => $value) {//处理数据输出
			$id               =  $value['id'];//id
			$title 	          =  $value['title'];//项目名称
			$starttime  	  =  $value['starttime'];//项目申报开始时间
			$endtime 	  	  =  $value['endtime'];//项目申报结束时间
			$checkstatus      =  $value['checkstatus'];//审核状态
			$list[$key]['auditpart'] = false;//初始化项目在申报时间内
			if ($nowTime<$starttime || $nowTime>$endtime) {
				//项目不在申报时间段内
				$list[$key]['auditpart'] = true;
			}
			$list[$key]['ckstext'] = $this->ckstext[$checkstatus];//状态文字描述
			$list[$key]['create_time']   =  date('Y-m-d H:i', $value['create_time']);//申请时间
			// $list[$key]['timepart'] = iconv('gb2312','utf-8',$list[$key]['timepart']);
		}
		echo result_to_towf_new($list, 1, '成功', $page_string);
	}	

	//编辑项目申报信息
	function edit(){
		$id     = $this->input->get_post("id");//项目id
		$sbid   = $this->input->get_post("sbid");//项目申报id
		$action = $this->input->get_post("action");		
		$action_array = array("edit", "edit_step");
		$action = !in_array($action,$action_array)?'edit':$action;	
		//查询该申报信息，判断是否能修改
		$nowTime = time();//当前时间戳
		$sql  =  "select sps.*,sp.check_starttime,sp.check_endtime
				 ,sp.starttime,sp.endtime,sp.title,sp.content 
				 from swj_project_shenbao sps 
				 left join swj_project sp on sp.id=sps.project_id 
				 where sps.id='$sbid'";
		$data =  $this->M_common->query_one($sql);
		if (count($data) < 0) {
			showmessage("没有该申报数据，请刷新重试","Swj_mime_project/index",3,0);
			exit();
		} else if ($nowTime<$data['starttime']
			||$nowTime>$data['endtime']) {//当前时间不在申报时间断内,提示不能修改
			showmessage("该项目不在申报时间段内不能修改","Swj_mime_project/index",3,0);
			exit();
		}
		if($action == 'edit') {//显示申报页面(项目基本信息)
			$template_guid = $data['template_guid'];//模板guid
			$data["id"] = $id;//项目id
			$data["sbid"] = $sbid;//申报id
			$data['timepart'] = date("Y-m-d H:i", $data['starttime']).' 至 '. date("Y-m-d H:i", $data['endtime']);
			$data["sess"] = $this->userInfo;
			$data['template'] = $this->mswpt->GetModelFromProjectAndUtype($data['project_id'], $this->userInfo['usertype']);
			$this->load->view(__TEMPLET_FOLDER__."/project/mime_project_edit", $data);		
		} elseif($action == 'edit_step'){//进行修改
			$this->edit_step();
		}
	}
	//给予扶持后补充项目申报信息资料
	function sup(){
		$id     = $this->input->get_post("id");//项目id
		$sbid   = $this->input->get_post("sbid");//项目申报id
		$action = $this->input->get_post("action");		
		$action_array = array("sup", "dosup");
		$action = !in_array($action,$action_array)?'sup':$action;	
		//查询该申报信息，判断是否能修改
		$nowTime = time();//当前时间戳
		$sql  =  "select sps.checkstatus,sps.fuchi_ziliao  
				 from swj_project_shenbao sps 
				 where sps.id='$sbid'";

		$data =  $this->M_common->query_one($sql);
		if (count($data) < 0) {
			showmessage("没有该申报数据，请刷新重试","Swj_mime_project/index",3,0);
			exit();
		} else if ($data['checkstatus']!='30') {//只有给予扶持的才能补充资料
			showmessage("该申报还未给予扶持不能补充资料","Swj_mime_project/index",3,0);
			exit();
		}
		if($action == 'sup') {//显示申报页面(项目基本信息)
			$data["id"] = $id;//项目id
			$data["sbid"] = $sbid;//申报id
			$data["sess"] = $this->userInfo;
			$fuchi_ziliao = $data['fuchi_ziliao'];//扶持资料
			if (!empty($fuchi_ziliao)) {//如果不为空时，将json转为数组,获取附件数组
				$data['fuchi_ziliao_file'] = $this->fujian->getModelList($fuchi_ziliao);//扶持资料文件
			}
			$this->load->view(__TEMPLET_FOLDER__."/project/mime_project_sup", $data);		
		} elseif($action == 'dosup'){//进行修改
			$this->dosup();
		}

	}
	//处理补充资料
	private function dosup() {
		$id   =  $this->input->get_post("id");//项目id
		$sbid =  $this->input->get_post("sbid");//项目申报id
		$fuchi_ziliao = $this->input->get_post("fuchi_ziliao");//扶持资料文件id
		if (count($fuchi_ziliao) > 0) {//有数据进行更新
			//更新数据
			$data['fuchi_ziliao'] = implode(',', $fuchi_ziliao);//以逗号分割
			$this->M_common->update_data2('swj_project_shenbao', $data, array('id' => $sbid));
			
			$this->fujian->update_beizhu($fuchi_ziliao);//更新附件表的备注
			/*$pInfo   = $this->M_common->query_one("select * from `swj_project` where id='$id'");
			$content = '(项目申报)用户'.$this->userInfo['username'].'补充了项目:'.$pInfo['title'].'的申报信息';
			$this->M_common_sms->sendSmsToSystem('项目申报', $this->userInfo['userid'], 
				$content, "Swj_project/index");*/
		}
		showmessage("保存成功", "Swj_mime_project/sup?id=$id&sbid=$sbid&rnd=1", 3, 1);
	}
	//申报项目填写基本资料
	function edit_step(){
		$action   =  $this->input->get_post("action");
		$tplguid  =  $this->input->get_post("template");//选择的模板guid
		$action_array = array("edit_step", "doedit");
		$action   =  !in_array($action,$action_array)?'edit_step':$action;	
		$id   =  $this->input->get_post("id");//项目id
		$sbid =  $this->input->get_post("sbid");//项目申报id
		$sql  =  "select sps.*,sp.check_starttime,sp.check_endtime
				 ,sp.starttime,sp.endtime,sp.title,sp.content 
				 from swj_project_shenbao sps 
				 left join swj_project sp on sp.id=sps.project_id 
				 where sps.id='$sbid'";
		$data =  $this->M_common->query_one($sql);
		if (count($data) < 0) {
			showmessage("没有该申报数据，请刷新重试","Swj_mime_project/index",3,0);
			exit();
		}
		if($action == 'edit_step') {//显示填写信息页面
			 
			$template = $this->mswpt->getTplByGuid($tplguid);//通过模板guid获取模板名称
			$template = 'mime_' . $template;//组合成mime前缀的模板
			$table    = $this->mswpt->getTplByGuid($tplguid,1);//通过模板guid获取附表名称
			$data1    = $this->getCmnInfo($sbid, $table);//通过项目id，附表名称获取该模板的数据
			// var_dump($data1);exit;
			$data1    = $this->handleData($data1, $tplguid);//通过模板guid处理不同的数据
			$data     = array_merge($data, $data1);//合并两个数组
			$data     = $this->getOutputFj($data);//获取输出到模板的附件格式
			$data["sess"] =  $this->userInfo;
			$data["id"]   =  $id;//项目id
			$data["sbid"]   =  $sbid;//项目id
			$data["tplguid"] = $tplguid;//申报项目的模板guid

			$this->load->view(__TEMPLET_FOLDER__."/project/$template", $data);		
		} elseif($action == 'doedit'){
			$this->doedit();
		}
	}
	//查看申报信息
	function look() {
		$id   =  $this->input->get_post("id");//项目id
		$sbid =  $this->input->get_post("sbid") + 0;//项目申报id
		$sql  =  "select sps.*,sp.check_starttime,sp.check_endtime
				 ,sp.starttime,sp.endtime,sp.title,sp.content 
				 from swj_project_shenbao sps 
				 left join swj_project sp on sp.id=sps.project_id 
				 where sps.id='$sbid'";
		$data =  $this->M_common->query_one($sql);
		if (count($data) < 0) {
			showmessage("没有该申报数据，请刷新重试","Swj_mime_project/index",3,0);
			exit();
		} 
		$data['timepart'] = date("Y-m-d H:i", $data['starttime']).' 至 '. date("Y-m-d H:i", $data['endtime']);
		$data["sess"] = $this->userInfo;
		$tplguid  = $data['template_guid'];//模板guid
		$table    = $this->mswpt->getTplByGuid($tplguid,1);//通过模板guid获取附表名称
		$data1    = $this->getCmnInfo($sbid, $table);//通过项目id，附表名称获取该模板的数据
		$data1    = $this->handleData($data1, $tplguid);//通过模板guid处理不同的数据
		$data     = array_merge($data, $data1);//合并两个数组(主表跟附表的信息)
		$data     = $this->getOutputFj($data);//获取输出到模板的附件格式
		// var_dump($data);exit;
		$template = $this->mswpt->getTplByGuid($tplguid);//通过模板guid获取模板名称
		$template = 'mime_' . $template . '_look';//组合成查看申报信息模板
		$this->load->view(__TEMPLET_FOLDER__."/project/$template", $data);
	}
	//打印数据
 	function printData() {
		$id   =  $this->input->get_post("id");//项目id
		$sbid =  $this->input->get_post("sbid") + 0;//项目申报id
		$sql  =  "select sps.* from swj_project_shenbao sps 
				 where sps.id='$sbid'";
		$model =  $this->M_common->query_one($sql);//申报信息
		$project_id = $model['project_id'];
		$promodel = array();
		if ($project_id) {
			$prosql   = "select * from swj_project where id='$project_id'";
			$promodel = $this->M_common->query_one($prosql);//项目信息
		}
		$usermodel = $this->getdanwei($model["userid"]);
		$model["danwei"] = isset($usermodel["name"])?$usermodel["name"]:"";//单位
		$tplguid  = $model['template_guid'];//模板guid
		$table    = $this->mswpt->getTplByGuid($tplguid,1);//通过模板guid获取附表名称
		$fbmodel  = $this->getCmnInfo($sbid, $table);//通过项目id，附表名称获取该模板的数据
		$fbmodel  = $this->handleData($fbmodel, $tplguid);//通过模板guid处理不同的数据
		// $model    = $this->getOutputFj($model);//获取输出到模板的附件格式
		$data     = array('model' => $model, 'promodel' => $promodel,
					'fbmodel' => $fbmodel);
		$data["sess"] = $this->userInfo;
		$data["checkstatus"] = $this->ckstext;//状态数组
		$data["fujian"] = $this->getPrintFj($model);//处理输出的附件
		// var_dump($data);exit;
		$template = $this->mswpt->getTplByGuid($tplguid);//通过模板guid获取模板名称
		$template = 'mime_' . $template . '_print';//组合成查看申报信息模板
		$this->load->view(__TEMPLET_FOLDER__."/project/$template", $data);
	}
	//附表信息的获取,$pid项目id,返回一维数组
	private function getCmnInfo($project_shenbao_id, $table) {
		$data    =  array();
		$sql =  "select * from $table where 
				project_shenbao_id='$project_shenbao_id'";
		$data=  $this->M_common->query_one($sql);
		return $data;
	}
	//通过模板guid处理不同的数据,$data 输出到模板的数组
	private function handleData($data, $tplguid) {
		switch ($tplguid) {
			case '7A48A574-223F-44F4-A06D-47E86D422C09'://电子商务园区
				$data['all_yqqk'] = $this->getccd('12', '45164');//系统中园区产权情况数据
				break;
			case '28C5877E-AD9A-4F9A-A806-7B00763A0099'://电子商务平台
				break;
			case 'B048AAE0-536B-4D58-AA6E-D40B7AFC10B0'://电子商务应用项目
				$data['all_yyxmlx'] = $this->getccd('12', '45181');//电子商务应用项目类型数据
				break;
			case '447B3AE5-0007-4701-9969-5DC8566DC4AF'://电子商务服务项目
				$data['all_fwxmlx'] = $this->getccd('12', '45188');//电子商务服务项目类型数据
				break;
			case '3F1BF950-4201-4574-A09C-447605BCDD20'://电子商务行业协会或机构
				if (isset($data['huodong_id'])&&$data['huodong_id']) {
					$hdInfo = $this->msih->GetModel($data['huodong_id']);//通过活动id获取活动信息
					if (count($hdInfo) > 0) {
						$data['actname'] = $hdInfo['actname'];
					}
				}
				
				break;
			case 'F97C5BE0-C642-4C85-9481-D19FAC4132CD'://电子商务交易平台
				$data['product'] = '';//显示主营产品
				if (isset($data['protype'])) {
					$model_product = explode(',', $data['protype']);
					$model_product = array_filter($model_product);//去掉空元素
					$model_product = implode(',', $model_product);
					$product = $this->ccd->GetList2(" id in(".$model_product.")");	
					$data['product'] = $product;
				}
				$data['all_ptqk'] = $this->getccd('12', '45170');//系统中平台产权情况数据
				break;
			case 'BA038762-97D3-4EF7-8A17-B5BE0CB33252'://电子商务综合服务平台
				$data['all_ptfw'] = $this->getccd('12', '45173');//系统中平台提供的服务
				break;
			default://默认为电子商务园区
				break;
		}
		return $data;

	}
	//通过申报主表的一条数组处理主表中的附件信息包括json附件
	private function getOutputFj($model) {
		$model['fujian_shenqing'] = $this->getFile($model['fujian_shenqing']);
		$model['fujian_zuzhi'] = $this->getFile($model['fujian_zuzhi']);
		$model['fujian_yingye'] = $this->getFile($model['fujian_yingye']);
		$model['fujian_wanshui'] = $this->getFile($model['fujian_wanshui']);
		$model['fujian_caiwu'] = $this->getFile($model['fujian_caiwu']);
		$model['fujian_shenqingbaogao'] = $this->getFile($model['fujian_shenqingbaogao']);
		$model['ny_chenghao_fj'] = $this->getFile($model['ny_chenghao_fj']);
		$model['fuchi_ziliao_file'] = $this->getFile($model['fuchi_ziliao']);
		$fujian_arr = json_decode($model['fujian_json'], true);
		if (is_array($fujian_arr)) {
			foreach ($fujian_arr as $key => $value) {
				$field = $value['field'];
				$model[$field] = $this->getFile($value['fjid']);
			}
		}
		return $model;
	}
	//通过申报主表的一条数组处理主表中的附件信息包括json附件,返回附件model
	private function getPrintFj($model) {
		//读各种附件
		$fj["中山市电子商务专项资金申请表"] = $this->fujian->GetUrl($model["fujian_shenqing"]);
		$fj["组织机构代码证"] = $this->fujian->GetUrl($model["fujian_zuzhi"]);
		$fj["营业执照复印件"] = $this->fujian->GetUrl($model["fujian_yingye"]);
		$fj["企业完税证明复印件"] = $this->fujian->GetUrl($model["fujian_wanshui"]);
		$fj["企业年度财务报告"] = $this->fujian->GetUrl($model["fujian_caiwu"]);
		$fj["企业法定代表人签字并盖章的申请报告"] = $this->fujian->GetUrl($model["fujian_shenqingbaogao"]);
		$fj["当年所获电子商务评奖或称号"] = $this->fujian->GetUrl($model["ny_chenghao_fj"]);
		$json = $model["fujian_json"];
		if($json!=""){
			$json = json_decode($json,true);
			foreach($json as $v){
				$fj[$v["beizhu"]]=$v["picurl"];	
			}
		}
		return $fj;
	}
	//修改项目申报信息
	private function doedit(){

		if (!$this->userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$createid = $this->userInfo['userid'];
		$id = verify_id($this->input->get_post("id"));//项目id
		$tplguid = $this->input->get_post("tplguid");//模板guid

		$tInfo = $this->mswpt->GetModelFromGuid($tplguid);//通过模板guid获取模板信息
		if (count($tInfo) <= 0) {
			showmessage("请选择一个模板进行修改","Swj_mime_project/edit?id=$id&sbid=$project_shenbao_id",3,0);
			exit();
		}
		$checkstatus = $this->input->get_post("checkstatus");//审核状态

		//主表的附件信息
		$fujian_shenqing       = @implode(',', $this->input->get_post("fujian_shenqing")) + 0;//申请附件表
		$fujian_zuzhi          = @implode(',', $this->input->get_post("fujian_zuzhi")) + 0;//企业组织机构
		$fujian_yingye         = @implode(',', $this->input->get_post("fujian_yingye")) + 0;//营业执照
		$fujian_wanshui        = @implode(',', $this->input->get_post("fujian_wanshui")) + 0;//企业完税
		$fujian_caiwu          = @implode(',', $this->input->get_post("fujian_caiwu")) + 0;//企业年度财务
		$fujian_shenqingbaogao = @implode(',', $this->input->get_post("fujian_shenqingbaogao")) + 0;//申请报告
		$ny_chenghao_fj        = @implode(',', $this->input->get_post("ny_chenghao_fj")) + 0;//年度所获的称号
		$checkstatus = '0';//修改后的状态为未审核
		$nowTime = time();//当前时间
		$template_title = $tInfo['title'];//模板名称
		//更新到申报主表中
		$data = array(
			'project_id'=>$id,
			'template_guid'=>$tplguid,
			'userid'=>$createid,
			'template_title'=>$template_title,
			'update_time'=>$nowTime,
			'fujian_shenqing'=>$fujian_shenqing,
			'fujian_zuzhi'=>$fujian_zuzhi,
			'fujian_yingye'=>$fujian_yingye,
			'fujian_wanshui'=>$fujian_wanshui,
			'fujian_caiwu'=>$fujian_caiwu,
			'fujian_shenqingbaogao'=>$fujian_shenqingbaogao,
			'ny_chenghao_fj'=>$ny_chenghao_fj,
			'checkstatus'=>$checkstatus
		);
		//更新数据
		$project_shenbao_id = verify_id($this->input->get_post("sbid"));//项目id
		//判断该申报的审核状态，如果是初审通过的不能修改
		if (!$this->editAble($project_shenbao_id)) {
			showmessage("该申报已经审核通过不能修改", "Swj_mime_project/index", 3, 0);
		}
		$this->M_common->update_data2('swj_project_shenbao', $data, array('id' => $project_shenbao_id));
		$template = $this->mswpt->getTplByGuid($tplguid);//通过模板guid获取模板名称，作为处理附表的方法名
		$fjArr = array($fujian_shenqing,$fujian_zuzhi,$fujian_yingye,$fujian_wanshui
					,$fujian_caiwu,$fujian_shenqingbaogao,$ny_chenghao_fj);
		$this->fujian->update_beizhu($fjArr);//更新附件表的备注
		$functionFb = 'do' . $template;//组合成处理方法
		$this->$functionFb($project_shenbao_id);//$project_shenbao_id主表申报id
		if ($checkstatus == '0') {//是提交审核操作的发送提醒短信
			$pInfo   = $this->M_common->query_one("select * from `swj_project` where id='$id'");
			$content = '(项目申报)用户'.$this->userInfo['username'].'修改了项目:'.$pInfo['title'].'的申报信息,请您及时审核';
			$this->M_common_sms->sendSmsToSystem('项目申报', $this->userInfo['userid'], 
				$content, "Swj_project/index");
			showmessage("修改成功", "Swj_mime_project/index", 3, 1);
		} else {
			showmessage("修改成功", "Swj_mime_project/edit_step?id=$id&sbid=$project_shenbao_id&template=$tplguid&rnd=1", 3, 1);
		}
	}
	
	//插入到电商园区的附表中,$project_shenbao_id主表的申报id
	private function dodzswyq($project_shenbao_id) {

		$nowTime = time();
		$guid    = md5($nowTime);
		$fujian_arr = array();
		//更新主表园区个性化附件fujian_json{guid:xxxxx,picurl:xxxx.jpg,beizhu:‘某附件’}
		$fujian_jzzm = implode(',', $this->input->get_post("fujian_jzzm")) + 0;//园区土地及建筑物产权证明文件
		$fujian_qyht = implode(',', $this->input->get_post("fujian_qyht")) + 0;//园区相关企业名称和简介、合同
		$fujian_ptzm = implode(',', $this->input->get_post("fujian_ptzm")) + 0;//园区三年以上的电子商务发展目标和计划
		$fujian_otzm = implode(',', $this->input->get_post("fujian_otzm")) + 0;//园区建设和管理费用证明材料
		$fujian_zmcl = implode(',', $this->input->get_post("fujian_zmcl")) + 0;//运营主体每年开展活动证明材料
		$fjModel = $this->fujian->GetModel($fujian_jzzm);
		$fqModel = $this->fujian->GetModel($fujian_qyht);
		$fpModel = $this->fujian->GetModel($fujian_ptzm);
		$foModel = $this->fujian->GetModel($fujian_otzm);
		$fzModel = $this->fujian->GetModel($fujian_zmcl);
		if (count($fjModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_jzzm','guid' => md5($nowTime+1)
				,'picurl' => $fjModel['filesrc'],'fjid' => $fjModel['id']
				,'beizhu' => '园区土地及建筑物产权证明文件');
		}
		if (count($fqModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_qyht','guid' => md5($nowTime+2)
				,'picurl' => $fqModel['filesrc'],'fjid' => $fqModel['id']
				,'beizhu' => '园区相关企业名称和简介、合同');
		}
		if (count($fpModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_ptzm','guid' => md5($nowTime+3)
				,'picurl' => $fpModel['filesrc'],'fjid' => $fpModel['id']
				,'beizhu' => '园区三年以上的电子商务发展目标和计划');
		}
		if (count($foModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_otzm','guid' => md5($nowTime+4)
				,'picurl' => $foModel['filesrc'],'fjid' => $foModel['id']
				,'beizhu' => '园区建设和管理费用证明材料');
		}
		if (count($fzModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_zmcl','guid' => md5($nowTime+5)
				,'picurl' => $fzModel['filesrc'],'fjid' => $fzModel['id']
				,'beizhu' => '运营主体每年开展活动证明材料');
		}
		$fujian_json = json_encode($fujian_arr);
		//更新数据(主表)
		$data = array('fujian_json' => $fujian_json);
		$this->M_common->update_data2('swj_project_shenbao', $data, array('id'=>$project_shenbao_id));
		$fjArr = array($fujian_jzzm,$fujian_qyht,$fujian_ptzm,$fujian_otzm,$fujian_zmcl);
		$this->fujian->update_beizhu($fjArr);//更新附件表的备注
		//园区附表信息
		$yuanqu    =   $this->input->get_post("yuanqu");//园区名称	
		$touru     =   $this->input->get_post("touru");//投入
		$zizhu 	   =   $this->input->get_post("zizhu");//拟申请资助的金额
		$chanquan_id = $this->input->get_post("chanquan_id");//产权情况
		$chanquan_beizhu = $this->input->get_post("chanquan_beizhu");//其他产权备注
		$chanquan_year = $this->input->get_post("chanquan_year");//产权年限
		$jingying_mianji = $this->input->get_post("jingying_mianji");//园区经营面积
		$dianshang_mianji = $this->input->get_post("dianshang_mianji");//电商面积
		$qiyeshu = $this->input->get_post("qiyeshu");//进驻企业数
		$yingyong_qiyeshu = $this->input->get_post("yingyong_qiyeshu");//企业应用数
		$fuwu_qiyeshu = $this->input->get_post("fuwu_qiyeshu");//企业服务数
		$year_in = $this->input->get_post("year_in");//年度电子商务营业收入（万元）
		$year_in_bl = $this->input->get_post("year_in_bl");//年度电子商务营业收入占比（%）
		$year_out = $this->input->get_post("year_out");//年度企业纳税金额（万元）
		$data  =  array(
			'yuanqu'=>$yuanqu,
			'touru'=>$touru,
			'zizhu'=>$zizhu,
			'chanquan_id'=>$chanquan_id,
			'chanquan_beizhu'=>$chanquan_beizhu,
			'chanquan_year'=>$chanquan_year,
			'jingying_mianji'=>$jingying_mianji,
			'dianshang_mianji'=>$dianshang_mianji,
			'qiyeshu'=>$qiyeshu,
			'yingyong_qiyeshu'=>$yingyong_qiyeshu,
			'fuwu_qiyeshu'=>$fuwu_qiyeshu,
			'isdel'=>'0',
			'year_in'=>$year_in,
			'year_in_bl'=>$year_in_bl,
			'year_out'=>$year_out
		);
		//判断该申报id是否已经存在附表中，如果存在则更新否则插入
		$sql   =  "select id from swj_project_shenbao_yuanqu where project_shenbao_id='$project_shenbao_id'";
		$array  =  $this->M_common->query_one($sql);
		if (count($array) > 0) {//存在则更新数据库
			$id = $array['id'];
			$this->M_common->update_data2('swj_project_shenbao_yuanqu', $data, array('id'=>$id));
		} else {
			$data['project_shenbao_id'] = $project_shenbao_id;
			$array = $this->M_common->insert_one("swj_project_shenbao_yuanqu", $data);	
		}
	}

	//插入到电子商务交易平台的附表中,$project_shenbao_id主表的申报id
	private function dodzswjypt($project_shenbao_id) {

		$nowTime = time();
		$guid    = md5($nowTime);
		$fujian_arr = array();
		//更新主表园区个性化附件fujian_json{guid:xxxxx,picurl:xxxx.jpg,beizhu:‘某附件’}
		$fujian_mbjh = implode(',', $this->input->get_post("fujian_mbjh")) + 0;//平台三年以上的建设目标和工作计划
		$fujian_xkba = implode(',', $this->input->get_post("fujian_xkba")) + 0;//网站增值电信业务许可证或ICP备案证复印件
		$fujian_trzm = implode(',', $this->input->get_post("fujian_trzm")) + 0;//平台名称、链接、流量和交易额证明材料
		$fujian_lljy = implode(',', $this->input->get_post("fujian_lljy")) + 0;//平台每年建设和管理费用投入及交易手续费证明材料
		$fujian_fwht = implode(',', $this->input->get_post("fujian_fwht")) + 0;//平台运营技术团队证明材料
		$fujian_fysz = implode(',', $this->input->get_post("fujian_fysz")) + 0;//进驻的卖家名单及对应的网店网址证明材料
		$fujian_wspt = implode(',', $this->input->get_post("fujian_wspt")) + 0;//完善平台管理服务功能和组织活动的证明材料
		$fmsModel = $this->fujian->GetModel($fujian_mbjh);
		$fxsModel = $this->fujian->GetModel($fujian_xkba);
		$fhtModel = $this->fujian->GetModel($fujian_trzm);
		$fxmModel = $this->fujian->GetModel($fujian_lljy);
		$fscModel = $this->fujian->GetModel($fujian_fwht);
		$fywModel = $this->fujian->GetModel($fujian_fysz);
		$fwsModel = $this->fujian->GetModel($fujian_wspt);
		if (count($fmsModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_mbjh','guid' => md5($nowTime+1)
				,'picurl' => $fmsModel['filesrc'],'fjid' => $fmsModel['id']
				,'beizhu' => '平台三年以上的建设目标和工作计划');
		}
		if (count($fxsModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_xkba','guid' => md5($nowTime+2)
				,'picurl' => $fxsModel['filesrc'],'fjid' => $fxsModel['id']
				,'beizhu' => '网站增值电信业务许可证或ICP备案证复印件');
		}
		if (count($fhtModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_trzm','guid' => md5($nowTime+3)
				,'picurl' => $fhtModel['filesrc'],'fjid' => $fhtModel['id']
				,'beizhu' => '平台名称、链接、流量和交易额证明材料');
		}
		if (count($fxmModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_lljy','guid' => md5($nowTime+4)
				,'picurl' => $fxmModel['filesrc'],'fjid' => $fxmModel['id']
				,'beizhu' => '平台每年建设和管理费用投入及交易手续费证明材料');
		}
		if (count($fscModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_fwht','guid' => md5($nowTime+5)
				,'picurl' => $fscModel['filesrc'],'fjid' => $fscModel['id']
				,'beizhu' => '平台运营技术团队证明材料');
		}
		if (count($fywModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_fysz','guid' => md5($nowTime+6)
				,'picurl' => $fywModel['filesrc'],'fjid' => $fywModel['id']
				,'beizhu' => '进驻的卖家名单及对应的网店网址证明材料');
		}
		if (count($fwsModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_wspt','guid' => md5($nowTime+7)
				,'picurl' => $fwsModel['filesrc'],'fjid' => $fwsModel['id']
				,'beizhu' => '完善平台管理服务功能和组织活动的证明材料');
		}
		$fujian_json = json_encode($fujian_arr);
		//更新数据(主表)
		$data = array('fujian_json' => $fujian_json);
		$this->M_common->update_data2('swj_project_shenbao', $data, array('id'=>$project_shenbao_id));
		$fjArr = array($fujian_xkba,$fujian_trzm,$fujian_lljy,$fujian_fwht,$fujian_fysz,$fujian_mbjh,$fujian_wspt);
		$this->fujian->update_beizhu($fjArr);//更新附件表的备注
		//附表信息
		$title      =   $this->input->get_post("title");//交易平台名称	
		$url        =   $this->input->get_post("url");//交易平台网址
		$icp 	    =   $this->input->get_post("icp");//网站ICP备案证号
		$xukezheng  =   $this->input->get_post("xukezheng");//增值电信业务经营许可证号
		$protype    =   $this->input->get_post("protype");//平台销售的主要产品类型
		$chanquan   =   $this->input->get_post("chanquan");//平台产权情况
		$chanquan_other = $this->input->get_post("chanquan_other");//其他平台产权情况
		$uv         =   $this->input->get_post("uv");//平台日均浏览量(UV)
		$chengjiao  =   $this->input->get_post("chengjiao");//平台年度总成交额
		$shenqing   =   $this->input->get_post("shenqing");//拟申请金额
		$year_in    =   $this->input->get_post("year_in");//年度电子商务营业收入（万元）
		$year_in_bl =   $this->input->get_post("year_in_bl");//年度电子商务营业收入占比（%）
		$year_out   =   $this->input->get_post("year_out");//年度企业纳税金额（万元）
		$data  =  array(
			'title'=>$title,
			'url'=>$url,
			'icp'=>$icp,
			'xukezheng'=>$xukezheng,
			'protype'=>$protype,
			'chanquan'=>$chanquan,
			'chanquan_other'=>$chanquan_other,
			'uv'=>$uv,
			'chengjiao'=>$chengjiao,
			'shenqing'=>$shenqing,
			'isdel'=>'0',
			'year_in'=>$year_in,
			'year_in_bl'=>$year_in_bl,
			'year_out'=>$year_out
		);
		//判断该申报id是否已经存在附表中，如果存在则更新否则插入
		$sql   =  "select id from swj_project_shenbao_jiaoyi_pingtai where project_shenbao_id='$project_shenbao_id'";
		$array  =  $this->M_common->query_one($sql);
		if (count($array) > 0) {//存在则更新数据库
			$id = $array['id'];
			$this->M_common->update_data2('swj_project_shenbao_jiaoyi_pingtai', $data, array('id'=>$id));
		} else {
			$data['project_shenbao_id'] = $project_shenbao_id;
			$array = $this->M_common->insert_one("swj_project_shenbao_jiaoyi_pingtai", $data);	
		}
	}
	//插入到电子商务综合服务平台的附表中,$project_shenbao_id主表的申报id
	private function dodzswzhfwpt($project_shenbao_id) {
		$nowTime = time();
		$guid    = md5($nowTime);
		$fujian_arr = array();
		//更新主表园区个性化附件fujian_json{guid:xxxxx,picurl:xxxx.jpg,beizhu:‘某附件’}
		$fujian_xmqk = implode(',', $this->input->get_post("fujian_xmqk")) + 0;//项目基本情况介绍证明材料
		$fujian_yyxse = implode(',', $this->input->get_post("fujian_yyxse")) + 0;//运营销售额的后台数据截图
		$fujian_xmtr = implode(',', $this->input->get_post("fujian_xmtr")) + 0;//项目建设投入的证明材料
		$fujian_dlht = @implode(',', $this->input->get_post("fujian_dlht")) + 0;//与委托方的代理合同或协议
		// $fujian_fysz = implode(',', $this->input->get_post("fujian_fysz")) + 0;//费用收支证明
		$fxsModel = $this->fujian->GetModel($fujian_xmqk);
		$fhtModel = $this->fujian->GetModel($fujian_yyxse);
		$fxmModel = $this->fujian->GetModel($fujian_xmtr);
		$fscModel = $this->fujian->GetModel($fujian_dlht);
		// $fywModel = $this->fujian->GetModel($fujian_fysz);
		if (count($fxsModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_xmqk','guid' => md5($nowTime+1)
				,'picurl' => $fxsModel['filesrc'],'fjid' => $fxsModel['id']
				,'beizhu' => '项目基本情况介绍证明材料');
		}
		if (count($fhtModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_yyxse','guid' => md5($nowTime+2)
				,'picurl' => $fhtModel['filesrc'],'fjid' => $fhtModel['id']
				,'beizhu' => '运营销售额的后台数据截图');
		}
		if (count($fxmModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_xmtr','guid' => md5($nowTime+3)
				,'picurl' => $fxmModel['filesrc'],'fjid' => $fxmModel['id']
				,'beizhu' => '项目建设投入的证明材料');
		}
		if (count($fscModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_dlht','guid' => md5($nowTime+4)
				,'picurl' => $fscModel['filesrc'],'fjid' => $fscModel['id']
				,'beizhu' => '与委托方的代理合同或协议');
		}
		/*if (count($fywModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_fysz','guid' => md5($nowTime+5)
				,'picurl' => $fywModel['filesrc'],'fjid' => $fywModel['id']
				,'beizhu' => '费用收支证明');
		}*/
		$fujian_json = json_encode($fujian_arr);
		//更新数据(主表)
		$data = array('fujian_json' => $fujian_json);
		$this->M_common->update_data2('swj_project_shenbao', $data, array('id'=>$project_shenbao_id));
		$fjArr = array($fujian_xmqk,$fujian_yyxse,$fujian_xmtr,$fujian_dlht);
		$this->fujian->update_beizhu($fjArr);//更新附件表的备注
		//附表信息

		$title            =   $this->input->get_post("title");//交易平台名称	
		$fuwu_type        =   $this->input->get_post("fuwu_type");//交易平台网址
		$fuwu_type_str    =   implode(',', $fuwu_type);//分割为数组
		$fuwu_type_other  =   $this->input->get_post("fuwu_type_other");//网站ICP备案证号
		$qiyeshu          =   $this->input->get_post("qiyeshu");//增值电信业务经营许可证号
		$shouru           =   $this->input->get_post("shouru");//平台销售的主要产品类型
		$shenqing         =   $this->input->get_post("shenqing");//拟申请金额
		$year_in          =   $this->input->get_post("year_in");//年度电子商务营业收入（万元）
		$year_in_bl       =   $this->input->get_post("year_in_bl");//年度电子商务营业收入占比（%）
		$year_out         =   $this->input->get_post("year_out");//年度企业纳税金额（万元）
		$data  =  array(
			'title'=>$title,
			'fuwu_type'=>$fuwu_type_str,
			'fuwu_type_other'=>$fuwu_type_other,
			'qiyeshu'=>$qiyeshu,
			'shouru'=>$shouru,
			'shenqing'=>$shenqing,
			'isdel'=>'0',
			'year_in'=>$year_in,
			'year_in_bl'=>$year_in_bl,
			'year_out'=>$year_out
		);
		//判断该申报id是否已经存在附表中，如果存在则更新否则插入
		$sql   =  "select id from swj_project_shenbao_zonghe_fuwu where project_shenbao_id='$project_shenbao_id'";
		$array  =  $this->M_common->query_one($sql);
		if (count($array) > 0) {//存在则更新数据库
			$id = $array['id'];
			$this->M_common->update_data2('swj_project_shenbao_zonghe_fuwu', $data, array('id'=>$id));
		} else {
			$data['project_shenbao_id'] = $project_shenbao_id;
			$array = $this->M_common->insert_one("swj_project_shenbao_zonghe_fuwu", $data);	
		}
	}
	//插入到电子商务应用项目的附表中,$project_shenbao_id主表的申报id
	private function dodzswyyxm($project_shenbao_id) {

		$nowTime = time();
		$guid    = md5($nowTime);
		$fujian_arr = array();
		//更新主表园区个性化附件fujian_json{guid:xxxxx,picurl:xxxx.jpg,beizhu:‘某附件’}
		$fujian_xszm = implode(',', $this->input->get_post("fujian_xszm")) + 0;//第三方平台出具销售额证明
		$fujian_htxy = implode(',', $this->input->get_post("fujian_htxy")) + 0;//与委托方签订代理合同或协议
		$fujian_xmtr = implode(',', $this->input->get_post("fujian_xmtr")) + 0;//项目投入证明
		$fujian_scfg = implode(',', $this->input->get_post("fujian_scfg")) + 0;//市场覆盖证明
		$fujian_ywzz = implode(',', $this->input->get_post("fujian_ywzz")) + 0;//业务增长证明
		$fxsModel = $this->fujian->GetModel($fujian_xszm);
		$fhtModel = $this->fujian->GetModel($fujian_htxy);
		$fxmModel = $this->fujian->GetModel($fujian_xmtr);
		$fscModel = $this->fujian->GetModel($fujian_scfg);
		$fywModel = $this->fujian->GetModel($fujian_ywzz);
		if (count($fxsModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_xszm','guid' => md5($nowTime+1)
				,'picurl' => $fxsModel['filesrc'],'fjid' => $fxsModel['id']
				,'beizhu' => '第三方平台出具销售额证明');
		}
		if (count($fhtModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_htxy','guid' => md5($nowTime+2)
				,'picurl' => $fhtModel['filesrc'],'fjid' => $fhtModel['id']
				,'beizhu' => '与委托方签订代理合同或协议');
		}
		if (count($fxmModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_xmtr','guid' => md5($nowTime+3)
				,'picurl' => $fxmModel['filesrc'],'fjid' => $fxmModel['id']
				,'beizhu' => '项目投入证明');
		}
		if (count($fscModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_scfg','guid' => md5($nowTime+4)
				,'picurl' => $fscModel['filesrc'],'fjid' => $fscModel['id']
				,'beizhu' => '市场覆盖证明');
		}
		if (count($fywModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_ywzz','guid' => md5($nowTime+5)
				,'picurl' => $fywModel['filesrc'],'fjid' => $fywModel['id']
				,'beizhu' => '业务增长证明');
		}
		$fujian_json = json_encode($fujian_arr);
		//更新数据(主表)
		$data = array('fujian_json' => $fujian_json);
		$this->M_common->update_data2('swj_project_shenbao', $data, array('id'=>$project_shenbao_id));
		$fjArr = array($fujian_xszm,$fujian_htxy,$fujian_xmtr,$fujian_scfg,$fujian_ywzz);
		$this->fujian->update_beizhu($fjArr);//更新附件表的备注
		//附表信息

		$yingyong     =   $this->input->get_post("yingyong");//电子商务应用项目类型	
		$shenqing     =   $this->input->get_post("shenqing");//拟申请资助的金额
		$xiaoshou     =   $this->input->get_post("xiaoshou");//年网上销售额
		$url          =   $this->input->get_post("url");//网店网址
		$year_in      =   $this->input->get_post("year_in");//年度电子商务营业收入（万元）
		$year_in_bl   =   $this->input->get_post("year_in_bl");//年度电子商务营业收入占比（%）
		$year_out     =   $this->input->get_post("year_out");//年度企业纳税金额（万元）
		$qiyeshu      =   $this->input->get_post("qiyeshu");//代运营企业数量
		$o2o_addr     =   $this->input->get_post("o2o_addr");//线下体验店地址
		$o2o_ispay    =   $this->input->get_post("o2o_ispay");//网店是否有移动支付功能
		$o2o_bili     =   $this->input->get_post("o2o_bili");//网上销售额占年销售总额的比例
		$content      =   $this->input->get_post("content");//电子商务新技术应用项目的基本情况
		$data  =  array(
			'yingyong'=>$yingyong,
			'shenqing'=>$shenqing,
			'xiaoshou'=>$xiaoshou,
			'url'=>$url,
			'isdel'=>'0',
			'year_in'=>$year_in,
			'year_in_bl'=>$year_in_bl,
			'year_out'=>$year_out,
			'qiyeshu'=>$qiyeshu,
			'o2o_addr'=>$o2o_addr,
			'o2o_ispay'=>$o2o_ispay,
			'o2o_bili'=>$o2o_bili,
			'content'=>$content
		);
		//判断该申报id是否已经存在附表中，如果存在则更新否则插入
		$sql   =  "select id from swj_project_shenbao_yingyong_xiangmu where project_shenbao_id='$project_shenbao_id'";
		$array  =  $this->M_common->query_one($sql);
		if (count($array) > 0) {//存在则更新数据库
			$id = $array['id'];
			$this->M_common->update_data2('swj_project_shenbao_yingyong_xiangmu', $data, array('id'=>$id));
		} else {
			$data['project_shenbao_id'] = $project_shenbao_id;
			$array = $this->M_common->insert_one("swj_project_shenbao_yingyong_xiangmu", $data);	
		}
	}
	//插入到电子商务服务项目的附表中,$project_shenbao_id主表的申报id
	private function dodzswfwxm($project_shenbao_id) {

		$nowTime = time();
		$guid    = md5($nowTime);
		$fujian_arr = array();
		//更新服务项目个性化附件fujian_json{guid:xxxxx,picurl:xxxx.jpg,beizhu:‘某附件’}
		$fujian_xmqk = implode(',', $this->input->get_post("fujian_xmqk")) + 0;//项目基本情况介绍证明材料
		$fujian_htsf = implode(',', $this->input->get_post("fujian_htsf")) + 0;//服务合同、服务收费票据证明材料
		$fujian_xmtr = implode(',', $this->input->get_post("fujian_xmtr")) + 0;//项目建设投入的证明材料
		$fxqModel = $this->fujian->GetModel($fujian_xmqk);
		$fhModel = $this->fujian->GetModel($fujian_htsf);
		$fsmModel = $this->fujian->GetModel($fujian_xmtr);
		if (count($fxqModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_xmqk','guid' => md5($nowTime+1)
				,'picurl' => $fxqModel['filesrc'],'fjid' => $fxqModel['id']
				,'beizhu' => '项目基本情况介绍证明材料');
		}
		if (count($fhModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_htsf','guid' => md5($nowTime+2)
				,'picurl' => $fhModel['filesrc'],'fjid' => $fhModel['id']
				,'beizhu' => '服务合同、服务收费票据证明材料');
		}
		if (count($fsmModel) > 0) {//组合成附件数组
			$fujian_arr[] = array('field' => 'fujian_xmtr','guid' => md5($nowTime+2)
				,'picurl' => $fsmModel['filesrc'],'fjid' => $fsmModel['id']
				,'beizhu' => '项目建设投入的证明材料');
		}
		$fujian_json = json_encode($fujian_arr);
		//更新数据(主表)
		$data = array('fujian_json' => $fujian_json);
		$this->M_common->update_data2('swj_project_shenbao', $data, array('id'=>$project_shenbao_id));
		$fjArr = array($fujian_xmqk,$fujian_htsf,$fujian_xmtr);
		$this->fujian->update_beizhu($fjArr);//更新附件表的备注
		//附表信息
		$project_type  =   $this->input->get_post("project_type");//电子商务服务项目类型
		$project_str   =   implode(',', $project_type);//以逗号分割
		$other_type    =   $this->input->get_post("other_type");//其他项目类型
		$title         =   $this->input->get_post("title");//电子商务服务项目名称
		$qiyeshu       =   $this->input->get_post("qiyeshu");//服务企业数量
		$shouru        =   $this->input->get_post("shouru");//年营业收入
		$shenqing      =   $this->input->get_post("shenqing");//拟申请资助的金额
		$year_in       =   $this->input->get_post("year_in");//年度电子商务营业收入（万元）
		$year_in_bl    =   $this->input->get_post("year_in_bl");//年度电子商务营业收入占比（%）
		$year_out      =   $this->input->get_post("year_out");//年度企业纳税金额（万元）
		$data  =  array(
			'title'=>$title,
			'qiyeshu'=>$qiyeshu,
			'project_type'=>$project_str,
			'other_type'=>$other_type,
			'shouru'=>$shouru,
			'shenqing'=>$shenqing,
			'isdel'=>'0',
			'year_in'=>$year_in,
			'year_in_bl'=>$year_in_bl,
			'year_out'=>$year_out
		);
		//判断该申报id是否已经存在附表中，如果存在则更新否则插入
		$sql   =  "select id from swj_project_shenbao_fuwu_xiangmu where project_shenbao_id='$project_shenbao_id'";
		$array  =  $this->M_common->query_one($sql);
		if (count($array) > 0) {//存在则更新数据库
			$id = $array['id'];
			$this->M_common->update_data2('swj_project_shenbao_fuwu_xiangmu', $data, array('id'=>$id));
		} else {
			$data['project_shenbao_id'] = $project_shenbao_id;
			$array = $this->M_common->insert_one("swj_project_shenbao_fuwu_xiangmu", $data);	
		}
	}
	//插入到电子商务协会或机构的附表中,$project_shenbao_id主表的申报id
	private function dodzswhyxhhjg($project_shenbao_id) {

		$huodong_id      =   $this->input->get_post("huodong_id");//活动id
		$shenqing        =   $this->input->get_post("shenqing");//拟申请资助的金额
		$uInfo           =   $this->msrx->GetModelFromUserID($this->userInfo['userid']);//机构信息
		$jianjie         =   '';//机构或协会简介
		if (count($uInfo) > 0) {
			$jianjie     =   $uInfo['socialorinstitution_summary'];//机构或协会简介
		}
		$hdInfo          =   $this->msih->GetModel($huodong_id);//活动备案信息
		$huodong_jianjie =   '';//活动简介
		if (count($hdInfo) > 0) {
			$huodong_jianjie =   $hdInfo['description'];//活动简介
		}
		$data  =  array(
			'huodong_id'=>$huodong_id,
			'jianjie'=>$jianjie,
			'huodong_jianjie'=>$huodong_jianjie,
			'shenqing'=>$shenqing,
			'isdel'=>'0'
		);
		//判断该申报id是否已经存在附表中，如果存在则更新否则插入
		$sql   =  "select id from swj_project_shenbao_xiehui_jigou where project_shenbao_id='$project_shenbao_id'";
		$array  =  $this->M_common->query_one($sql);
		if (count($array) > 0) {//存在则更新数据库
			$id = $array['id'];
			$this->M_common->update_data2('swj_project_shenbao_xiehui_jigou', $data, array('id'=>$id));
		} else {
			$data['project_shenbao_id'] = $project_shenbao_id;
			$array = $this->M_common->insert_one("swj_project_shenbao_xiehui_jigou", $data);	
		}
	}
	//生成缓存
	private function make_cache(){
		if(!is_really_writable($this->admin_perm_path)){				
			exit("目录".$this->admin_perm_path."不可写");
		}
		
		if(!file_exists($this->admin_perm_path)){
			mkdir($this->admin_perm_path);
		}
		$configfile = $this->admin_perm_path."/cache_admin_{$this->admin_id}.inc.php";
		$fp = fopen($configfile,'w');
		flock($fp,3);
		fwrite($fp,"<"."?php\r\n");
    	fwrite($fp,"/*用户特殊的权限缓存*/\r\n");
    	fwrite($fp,"/*author wangjian*/\r\n");
    	fwrite($fp,"/*time 2014_03_01*/\r\n");
    	fwrite($fp,"\$admin_perm_array = array(\r\n");
		foreach($this->perm_array as $k=>$v){
			fwrite($fp,"'{$k}' => '{$v}',\r\n");
		}
		fwrite($fp,");\r\n");
		fwrite($fp,"?".">");
    	fclose($fp);
	}	
	//判断该项目是否正在进行$id:项目id，是返回true否则false
	private function check_pjing($id) {

		$nowTime  =  time();//当前时间戳
		$sql 	  =  "select starttime,endtime from `swj_project` where `id`='$id'";
		$data 	  =  $this->M_common->query_one($sql);//查找数据
		if ($nowTime > $data['starttime'] && $nowTime < $data['endtime']) {//为正在进行的上报
			return true;
		} else {
			return false;
		}
	}
	//判断该项目是否已申报$id:项目id，$userid用户id，是返回true否则false
	private function check_ysb($id, $userid) {

		$sql = "select id from `swj_project_shenbao` where 
				`project_id`='$id' and isdel='0' and `userid`='$userid' and checkstatus!='99'";
		$query = $this->db->query($sql);
		if ($query->num_rows()) {//有数据
			return true;
		} else {
			return false;	
		}
	}
	//获取附件的信息,$str,附件表id，以逗号分隔.返回该附件的数组
	private function getFile($str) {

		$arr = explode(',', $str);
		$rst = array();//返回的数组
		foreach ($arr as $key => $value) {
			$sql = "select * from `swj_fujian` where `id`='$value' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			if ($data) {//存数据
				$rst[$value] = $data;
			}
		}
		return $rst;
	}
	//判断是否可以修改，是返回true否则false
	private function editAble($project_shenbao_id) {

		$sql = "select * from swj_project_shenbao where id='$project_shenbao_id'";
		$data = $this->M_common->query_one($sql);
		if (count($data) > 0) {
			$checkstatus = $data['checkstatus'];
			if ($checkstatus!='0'&&$checkstatus!='-10') {//初审通过后不能修改
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	//通过用户id获取用户所在单位，返回单位model
	private function getdanwei($uid){
		$model = $this->user->GetModel($uid);
		if($model["usertype"]=="45063"){
			$model2 = $this->dsqy->GetModelFromUserID($uid);
			$model2["jianjie_"] = $model2["company_summary"];
		}
		if($model["usertype"]=="45064"){
			$model2 = $this->xhjg->GetModelFromUserID($uid);
			$model2["jianjie_"] = $model2["socialorinstitution_summary"];
		}
		$model = $model2;
		return $model;
	}
	/*//获取所有附表信息，返回数组,$project_shenbao_id申报主表id
	private function getAllFbInfo($data, $project_shenbao_id) {

		$data['dzswfwxm'] = $this->getOneFbInfo('swj_project_shenbao_fuwu_xiangmu', $project_shenbao_id);
		$data['dzswjypt'] = $this->getOneFbInfo('swj_project_shenbao_jiaoyi_pingtai', $project_shenbao_id);
		$data['dzswhyxhhjg'] = $this->getOneFbInfo('swj_project_shenbao_xiehui_jigou', $project_shenbao_id);
		$data['dzswyyxm'] = $this->getOneFbInfo('swj_project_shenbao_yingyong_xiangmu', $project_shenbao_id);
		$data['dzswyq'] = $this->getOneFbInfo('swj_project_shenbao_yuanqu', $project_shenbao_id);
		$data['dzswzhfwpt'] = $this->getOneFbInfo('swj_project_shenbao_zonghe_fuwu', $project_shenbao_id);
		$data['all_yqqk'] = $this->getccd('12', '45164');//系统中园区产权情况数据
		$data['all_yyxmlx'] = $this->getccd('12', '45181');//电子商务应用项目类型数据
		$data['all_fwxmlx'] = $this->getccd('12', '45188');//电子商务服务项目类型数据
		return $data;
	}
	//获取一个附表信息
	private function getOneFbInfo($table, $project_shenbao_id) {

		$sql = "select * from {$table} where project_shenbao_id='$project_shenbao_id' ";
		return $this->M_common->query_one($sql);
	}*/
	//获取系统模型
	private function getccd($typeid,$pid){
		return $this->ccd->GetListFromTypeidAndPid($typeid,$pid);
	}
}