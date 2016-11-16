<?php
/*
 *申报项目列表控制器
 *author 林科 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class Swj_project extends MY_Controller{
	private $table_ ; //表的前缀
	private $status;
	private $userInfo;//登录用户信息
	private $table;//项目表
	function Swj_project(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_common_sms');//发送短信公共模型
		$this->load->model('M_common_category_data','ccd'); 
		$this->load->model('M_swj_project_template', 'mswpt');//项目模板选择模型
		$this->load->model('M_swj_info_hdba','msih');//活动备案模型
		$this->load->model('M_swj_fujian','fujian');//附件模型
		$this->load->model('M_swj_register_xiehuiorjigou', 'msrx');//协会或结构模型
		$this->load->library('session');
		$this->table_ =table_pre('real_data');
		$this->userInfo = $this->parent_getsession();//获取登录信息
		$this->table = 'swj_project';//申报项目表
		$this->status = array(0=>'未开始',1=>'进行中',2=>'已过期',3=>'已申报');
	}
	//列表页面
	function index(){
		
		// var_dump($userInfo);exit;
		if (!$this->userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$action = $this->input->get_post("action");	
		$action_array = array("show","ajax_data");
		$action = !in_array($action,$action_array)?'show':$action ;
		$status  =  $this->input->get_post('status');//状态，1进行中或者其他过期等状态
		$status  =  !empty($status)?$status:1;//默认为显示进行中的申报项目
		if ($action == 'show') {
			$data = array('status' => $status);
			$this->load->view(__TEMPLET_FOLDER__."/project/project_list", $data);
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
		$userid = $this->userInfo['userid'];//用户id
		$this->load->library("common_page");
		$page = intval($this->input->get_post("page"));
		if($page <=0 ){
			$page = 1 ;
		}
		$per_page = 20;//每一页显示的数量
		$limit = ($page-1)*$per_page;
		$limit.=",{$per_page}";

		$nowTime =  time();//当前时间
		$where 	 =  ' where 1=1 and sp.isdel="0" and sp.isshow="1" ';//查询条件
		$status  =  $this->input->get_post('status');//状态，1进行中或者其他过期等状态
		if ($status!=='') {//不为空的时候刷选哪个状态的项目，否则显示全部
			switch ($status) {
				case 0://未开始的项目
					$where .= " and sp.starttime>'$nowTime' ";
					break;
				case 1://进行中的项目
					$where .= " and sp.starttime<'$nowTime' and sp.endtime>'$nowTime' 
							  and sp.id not in(select project_id from swj_project_shenbao 
							  where userid='$userid' and checkstatus!='99' and isdel='0') ";
					break;
				case 2://往期的项目
					$where .= " and sp.endtime<'$nowTime' ";
					break;
				case 3://已申报
					$where .= " and sp.id in(select project_id from swj_project_shenbao 
								where userid='$userid' and checkstatus!='99' and isdel='0') ";
					break;
				default://默认显示进行中的项目
					$where .= " and sp.starttime<'$nowTime' and sp.endtime>'$nowTime' ";
					break;
			}
		}
		$name = daddslashes(html_escape(strip_tags($this->input->get_post("projectname"))));//项目名称
		if(!empty($name)){//查询项目名称
			$where .= " AND sp.`title` LIKE '%{$name}%'";
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM {$this->table} sp {$where} ";
		// file_put_contents('F:/aaa.txt', $sql_count);
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_log = "SELECT sp.* FROM {$this->table} sp {$where} order by sp.id desc limit  {$limit}";	
		// file_put_contents('F:/aaa.txt', $sql_log);
		$list = $this->M_common->querylist($sql_log);
		foreach ($list as $key => $value) {//处理数据输出
			$id        = $value['id'];//id
			$starttime = $value['starttime'];//项目申报开始时间
			$endtime   = $value['endtime'];//项目申报结束时间
			$flag = 0;//默认为未开始
			if ($this->check_ysb($id, $userid)) {//是已申报
				$flag = 3;
			} else if ($nowTime > $endtime) {//为已结束
				$flag = 2;
			} else if ($nowTime > $starttime) {//进行中
				$flag = 1;
			} else {//未开始
				$flag = 0;
			}
			//由于使用json_encode是中文出现null的情况所以转码
			$list[$key]['status'] = $this->status[$flag];//状态
			$list[$key]['timepart'] = date("Y-m-d H:i", $starttime).' 至 '. date("Y-m-d H:i", $endtime);
			// $list[$key]['timepart'] = iconv('gb2312','utf-8',$list[$key]['timepart']);
		}
		/*$json_str = result_to_towf_new($list, 1, '成功', $page_string);
		file_put_contents('f:/aaa.txt', $json_str);*/
		echo result_to_towf_new($list, 1, '成功', $page_string);
	}
	
	//申报项目
	function shenbao(){
		$action = $this->input->get_post("action");		
		$action_array = array("shenbao", "shenbao_step");
		$action = !in_array($action,$action_array)?'show':$action;	
		if($action == 'show') {//显示申报页面(项目基本信息)
			$id   =  $this->input->get_post("id");//项目id
			$sql  =  "select * from swj_project where id='$id'";
			$data =  $this->M_common->query_one($sql);
			$data['timepart'] = date("Y-m-d H:i", $data['starttime']).' 至 '. date("Y-m-d H:i", $data['endtime']);
			$data["sess"] = $this->userInfo;
			$data['template'] = $this->mswpt->GetModelFromProjectAndUtype($id, $this->userInfo['usertype']);
			$this->load->view(__TEMPLET_FOLDER__."/project/project_shenbao", $data);		
		} elseif($action == 'shenbao_step'){//填写初审资料
			$this->shenbao_step();
		}
	}
	//申报项目填写基本资料
	function shenbao_step(){
		$action   =  $this->input->get_post("action");
		$tplguid  =  $this->input->get_post("template");//选择的模板guid
		$action_array = array("shenbao_step", "doshenbao");
		$action   =  !in_array($action,$action_array)?'shenbao_step':$action;	
		if($action == 'shenbao_step') {//显示填写信息页面
			$id   =  $this->input->get_post("id");//项目id
			
			
			$template = $this->mswpt->getTplByGuid($tplguid);//通过模板guid获取模板名称
			$table    = $this->mswpt->getTplByGuid($tplguid,1);//通过模板guid获取附表名称
			$data     = $this->getCmnInfo($id, $table);//通过项目id，附表名称获取该模板的数据
			$data     = $this->handleData($data, $tplguid);//通过模板guid处理不同的数据
			$model    = $this->getOutputFj($id);//通过项目id和用户id判断是否存在申报表中
			$data     = array_merge($data, $model);//合并两个数组
			$data["sess"] =  $this->userInfo;
			$data["id"]   =  $id;//项目id
			$data["tplguid"] = $tplguid;//申报项目的模板guid
			$this->load->view(__TEMPLET_FOLDER__."/project/$template", $data);		
		} elseif($action == 'doshenbao'){
			$this->doshenbao();
		}
	}
	//附表信息的获取,$pid项目id,返回一维数组
	private function getCmnInfo($pid, $table) {
		$data    =  array();
		$userid  =  $this->userInfo['userid'];//用户id
		$sql     =  "select id from swj_project_shenbao where 
					project_id='$pid' and userid='$userid' ";
		$model_sb=  $this->M_common->query_one($sql);//获取申报id
		if(count($model_sb) > 0) {//存在申报信息，通过申报id查找附表电商园区的信息
			$project_shenbao_id = $model_sb['id'];//申报id
			$sql =  "select * from $table where 
					project_shenbao_id='$project_shenbao_id'";
			$data=  $this->M_common->query_one($sql);
		}
		return $data;
	}
	//通过申报主表的一条数组处理主表中的附件信息包括json附件
	private function getOutputFj($pid) {
		$userid = $this->userInfo['userid'];
		$sql    = "select * from swj_project_shenbao where project_id='$pid' and userid='$userid'
				   and isdel='0'";
		$model  = $this->M_common->query_one($sql);
		if (count($model) > 0) {
			$model['fujian_shenqing'] = $this->getFile($model['fujian_shenqing']);
			$model['fujian_zuzhi'] = $this->getFile($model['fujian_zuzhi']);
			$model['fujian_yingye'] = $this->getFile($model['fujian_yingye']);
			$model['fujian_wanshui'] = $this->getFile($model['fujian_wanshui']);
			$model['fujian_caiwu'] = $this->getFile($model['fujian_caiwu']);
			$model['fujian_shenqingbaogao'] = $this->getFile($model['fujian_shenqingbaogao']);
			$model['ny_chenghao_fj'] = $this->getFile($model['ny_chenghao_fj']);
			$fujian_arr = json_decode($model['fujian_json'], true);
			if (is_array($fujian_arr)) {
				foreach ($fujian_arr as $key => $value) {
					$field = $value['field'];
					$model[$field] = $this->getFile($value['fjid']);
				}
			}
		}
		return $model;
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
	//处理申报项目(将数据插入到主表中，相对的将数据插入到正确的附表)
	private function doshenbao(){

		if (!$this->userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$createid = $this->userInfo['userid'];
		$id = verify_id($this->input->get_post("id"));//项目id
		$tplguid = $this->input->get_post("tplguid");//模板guid
		if ($this->check_ysb($id, $createid)) {//判断该项目是否已申报，是返回true否则false
			showmessage("您已经申报了该项目","Swj_project/index",3,0);
			exit();
		}
		$tInfo = $this->mswpt->GetModelFromGuid($tplguid);//通过模板guid获取模板信息
		if (count($tInfo) <= 0) {
			showmessage("请选择一个模板进行申报","Swj_project/shenbao?id=".$id,3,0);
			exit();
		}
		if (!$this->check_pjing($id)) {//判断该项目是否正在进行，是返回true否则false
			showmessage("该项目不在申报时间段内，请选择正在进行中的项目进行申报","Swj_project/index",3,0);
			exit();
		}
		//主表的附件信息
		$fujian_shenqing       = @implode(',', $this->input->get_post("fujian_shenqing")) + 0;//申请附件表
		$fujian_zuzhi          = @implode(',', $this->input->get_post("fujian_zuzhi")) + 0;//企业组织机构
		$fujian_yingye         = @implode(',', $this->input->get_post("fujian_yingye")) + 0;//营业执照
		$fujian_wanshui        = @implode(',', $this->input->get_post("fujian_wanshui")) + 0;//企业完税
		$fujian_caiwu          = @implode(',', $this->input->get_post("fujian_caiwu")) + 0;//企业年度财务
		$fujian_shenqingbaogao = @implode(',', $this->input->get_post("fujian_shenqingbaogao")) + 0;//申请报告
		$ny_chenghao_fj        = @implode(',', $this->input->get_post("ny_chenghao_fj")) + 0;//年度所获的称号


		$checkstatus = $this->input->get_post("checkstatus");//审核状态

		$checkstatus = $checkstatus=='99'?'99':'0';//前台提交只有临时保存跟未审核两种状态

		$nowTime = time();//当前时间
		$template_title = $tInfo['title'];//模板名称
		//添加到申报主表中
		$data = array(
			'project_id'=>$id,
			'template_guid'=>$tplguid,
			'userid'=>$createid,
			'checkstatus'=>$checkstatus,
			'template_title'=>$template_title,
			'update_time'=>$nowTime,
			'fujian_shenqing'=>$fujian_shenqing,
			'fujian_zuzhi'=>$fujian_zuzhi,
			'fujian_yingye'=>$fujian_yingye,
			'fujian_wanshui'=>$fujian_wanshui,
			'fujian_caiwu'=>$fujian_caiwu,
			'fujian_shenqingbaogao'=>$fujian_shenqingbaogao,
			'ny_chenghao_fj'=>$ny_chenghao_fj,
			'isdel'=>'0'
		);
		//判断userid跟project_id是否存在申报表中，如果存在则更新否则插入
		$sql    = "select id from swj_project_shenbao 
				   where userid='$createid' and project_id='$id'";
		$array  =  $this->M_common->query_one($sql);
		if (count($array)) {//存在更新数据
			$array['affect_num'] = 1;
			$project_shenbao_id = $array['id'];//申报id
			$this->M_common->update_data2('swj_project_shenbao', $data, array('id' => $project_shenbao_id));
		} else {//插入到数据库中
			$data['create_time'] = $nowTime;//申请时间
			$array = $this->M_common->insert_one("swj_project_shenbao",$data);
			$project_shenbao_id = $array['insert_id'];//插入申报表成功返回的id
		}
		$fjArr = array($fujian_shenqing,$fujian_zuzhi,$fujian_yingye,$fujian_wanshui
					,$fujian_caiwu,$fujian_shenqingbaogao,$ny_chenghao_fj);
		$this->fujian->update_beizhu($fjArr);//更新附件表的备注
		if($array['affect_num']>=1){//插入主表成功，将数据插入到附表中
			
			$template = $this->mswpt->getTplByGuid($tplguid);//通过模板guid获取模板名称，作为处理附表的方法名
			$functionFb = 'do' . $template;//组合成处理方法
			$this->$functionFb($project_shenbao_id);//$project_shenbao_id主表申报id
			if ($checkstatus == '0') {//是提交审核操作的发送提醒短信
				$pInfo   = $this->M_common->query_one("select * from `swj_project` where id='$id'");
				$content = '(项目申报)用户'.$this->userInfo['username'].'申报了项目:'.$pInfo['title'].',请您及时审核';
				$this->M_common_sms->sendSmsToSystem('项目申报', $this->userInfo['userid'], 
					$content, "Swj_project/index");
				showmessage("申报成功", "Swj_project/index", 3, 1);
			} else {//为临时保存的
				showmessage("保存成功", "Swj_project/shenbao_step?id=$id&template=$tplguid&rnd=1", 3, 1);
			}
			exit();
		}else{
			showmessage("申报失败，请刷新重试","Swj_project/index", 3, 0);
			exit();
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
		//更新附表
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
	//获取系统模型
	private function getccd($typeid,$pid){
		return $this->ccd->GetListFromTypeidAndPid($typeid,$pid);
	}
}