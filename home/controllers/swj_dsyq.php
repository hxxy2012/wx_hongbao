<?php
/*
 *电商园区控制器
 *author 林科 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class Swj_dsyq extends MY_Controller{
	public $table_ ; //表的前缀
	public $table;//表名
	private $status;//审核状态
	private $template;//模板目录
	private $userInfo;//用户登录信息
	private $imgformat;//图片格式
	function Swj_dsyq(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_common_sms');//发送短信公共模型
		$this->load->model('M_common_category_data','ccd'); 
		$this->load->model('M_swj_fujian','sf');//附件模型
		$this->load->model('M_swj_dsyq');//电商园区模型
		$this->load->library('session');
		$this->userInfo = $this->parent_getsession(isset($post["session_id"])?$post["session_id"]:"");//获取登录信息
		if (!$this->userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$this->table_ =table_pre('real_data');
		$this->table = 'swj_dsyq';//电商园区列表
		$this->template = 'dsyq';
		$this->imgformat = array('jpg', 'png', 'gif', 'bmp');//根据文件后缀不同的方式显示,图片以图片的形式显示
		$this->status = array(10 => '未审核', 20 => '审核通过', 30 => '审核不通过');
	}
	//列表页面
	function index(){
		// var_dump($this->userInfo);exit;
		// var_dump(decode_data());exit;
		// var_dump($_COOKIE);exit;
		$action = $this->input->get_post("action");	
		$action_array = array("show","ajax_data");
		$action = !in_array($action,$action_array)?'show':$action ;
		if ($action == 'show') {
			//查询所有的log表数据，生成select
			$this->load->view(__TEMPLET_FOLDER__."/{$this->template}/dsyq_list");
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
		$where = ' where 1= 1 and isdel=0 ';//查询条件
		// $name = daddslashes(html_escape(strip_tags($this->input->get_post("lmname"))));//类目名称
		$tablename = $this->table;//电商园区列表

		$where .= " and uid='$userid' ";

		$sql_count = "SELECT COUNT(*) AS tt FROM {$tablename} {$where} ";
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_log = "SELECT * FROM {$tablename} {$where} order by id desc limit  {$limit}";	

		$list = $this->M_common->querylist($sql_log);

		foreach ($list as $key => $value) {//处理数据输出
			$uid = $value['uid'];//所属企业id
			$town_id = $value['town_id'];//所属镇区id
			$model_user = $this->M_common->query_one("select usertype from 57sy_common_user where uid='$uid'");
			$usertype = $model_user['usertype'];//公司类型
			switch ($usertype) {//根据usertype查找表名
				case 45063:
					$temp_table = 'swj_register_dsqy';
					break;
				case 45064:
					$temp_table = 'swj_register_xiehuiorjigou';
					break;
				default:
					$temp_table = '';
					break;
			}
			//查找公司名称
			if ($temp_table == '') {
				$list[$key]['qyname'] = '无企业';
			} else {
				$model_qy = $this->M_common->query_one("select name from $temp_table where userid='$uid'");
				$list[$key]['qyname'] = count($model_qy)>0?$model_qy['name']:'无企业';
			}
			//查找镇区名称
			if ($town_id) {
				$model_zq = $this->M_common->query_one("select name from 57sy_common_category_data where id='$town_id'");
				$list[$key]['zqname'] = count($model_zq)>0?$model_zq['name']:'无镇区';
			} else {
				$list[$key]['zqname'] = '无镇区';
			}
			$flag = $value['audit'];//审核
			$list[$key]['audit_status'] = $this->status[$flag];//状态
		}
		echo result_to_towf_new($list, 1, '成功', $page_string) ;
	}
	
	//添加电商园区
	function add(){
		$action = $this->input->get_post("action");		
		$action_array = array("add", "doadd");
		$action = !in_array($action,$action_array)?'show':$action;	
		if($action == 'show') {//显示添加页面
			$data["sess"] = $this->userInfo;
			$data['town'] = $this->getccd(6,3145,"id asc");;//系统中存在的镇区
			$this->load->view(__TEMPLET_FOLDER__."/{$this->template}/dsyq_add", $data);		
		} elseif($action == 'doadd'){
			$this->doadd();
		}
	}
	//处理申报类目
	private function doadd(){
		/*$post = $_POST;
		var_dump($post);exit;*/
		// $name = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("name")))));//name
		$createid = $this->userInfo['userid'];
		$name = $this->input->get_post("name");//园区名称	
		$town_id = $this->input->get_post("town_id");//镇区id
		$jymj = $this->input->get_post("jymj");//经营面积
		$jzqynum = $this->input->get_post("jzqynum");//进驻企业总数
		$cqcondition = $this->input->get_post("cqcondition");//产权情况
		$cq_year = $this->input->get_post("cq_year");//租赁年数
		$jzmj = $this->input->get_post("jzmj");//建筑面积
		$zdmj = $this->input->get_post("zdmj");//占地面积
		$dsqynum = $this->input->get_post("dsqynum");//电商企业数
		$haspolicy = $this->input->get_post("haspolicy");//当地扶持政策
		$chenghao = $this->input->get_post("chenghao");//所获称号
		$linkman_name = $this->input->get_post("linkman_name");//联系人姓名
		$linkman_phone = $this->input->get_post("linkman_phone");//联系人电话
		$linkman_work = $this->input->get_post("linkman_work");//联系人职务
		$yq_addr = $this->input->get_post("yq_addr");//园区地址
		$yq_website = $this->input->get_post("yq_website");//园区官方网站
		$yq_describe = htmlspecialchars($this->input->get_post("yq_describe"));//园区简介
		$yq_ziliao = $this->input->get_post("yq_ziliao");//园区资料
		$yq_jianying = $this->input->get_post("yq_jianying");//园区剪影
		$beizhu = htmlspecialchars($this->input->get_post("beizhu"));//备注

		$yq_ziliao_arr    =  @implode(',', $yq_ziliao);//组合以逗号分隔的字符串
		$yq_jianying_arr  =  @implode(',', $yq_jianying);//组合以逗号分隔的字符串
		// var_dump($userInfo);exit;
		$nowTime = date("Y-m-d H:i:s",time());//当前时间
		//添加的数据
		$data = array(
			'name'=>$name,
			'town_id'=>$town_id,
			'jymj'=>$jymj,
			'jzqynum'=>$jzqynum,
			'cqcondition'=>$cqcondition,
			'cq_year'=>$cq_year,
			'jzmj'=>$jzmj,
			'zdmj'=>$zdmj,
			'dsqynum'=>$dsqynum,
			'haspolicy'=>$haspolicy,
			'chenghao'=>$chenghao,
			'linkman_name'=>$linkman_name,
			'linkman_phone'=>$linkman_phone,
			'linkman_work'=>$linkman_work,
			'yq_addr'=>$yq_addr,
			'yq_website'=>$yq_website,
			'yq_describe'=>$yq_describe,
			'yq_ziliao'=>$yq_ziliao_arr,
			'yq_jianying'=>$yq_jianying_arr,
			'beizhu'=>$beizhu,
			'audit'=>10,
			'uid'=>$createid,
			'createtime'=>$nowTime,
			'updatetime'=>$nowTime
		);
		$array = $this->M_common->insert_one($this->table, $data);
		if($array['affect_num']>=1){
			$content = '用户'.$this->userInfo['username'].'添加了园区：'.$name.',请您及时审核';
			$this->M_common_sms->sendSmsToSystem('电商园区', $this->userInfo['userid'], 
				$content, "Swj_dsyq/index");
			//将附件表中上传的文件的备注信息（临时）去掉
			$arr_merge = @array_merge($yq_ziliao, $yq_jianying);
			$this->sf->update_beizhu($arr_merge);
			showmessage("添加成功","Swj_dsyq/index",3,1);
			exit();
		}else{
			// write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"添加类目为{$name}失败");
			showmessage("添加失败","Swj_dsyq/index",3,0);
			exit();
		}
	}
	
	//查询电商园区
	function look() {

		$id = $this->input->get('id') + 0;//电商园区id
		$data = $this->M_swj_dsyq->GetModel($id);
		if ($id <= 0 || count($data) <= 0) {
			showmessage("数据有误,请刷新重试","Swj_dsyq/index",3,0);exit;
		}
		$model_zq = $this->M_common->query_one("select name from 57sy_common_category_data where id='{$data['town_id']}'");
		$data['zqname'] = count($model_zq)>0?$model_zq['name']:'无镇区';
		$audit = $data['audit'];//审核状态
		$data['audit_status'] = $this->status[$audit];//审核状态文字显示
		$data['yq_ziliao_file'] = $this->sf->getModelList($data['yq_ziliao']);//园区资料文件
		$data['yq_jianying_file'] = $this->sf->getModelList($data['yq_jianying']);//园区剪影文件
		$data["sess"] = $this->userInfo;
		$data["imgformat"] = $this->imgformat;//对于图片的格式直接显示图片
		$this->load->view(__TEMPLET_FOLDER__."/{$this->template}/dsyq_look", $data);	
	}

	//删除园区
	function del() {
		$ids     =  $this->input->get('id') + 0;//电商园区id
		$id_arr  =  explode(',', $ids);//打散为字符串
		if ($ids < 0) {
			showmessage("数据有误,请刷新重试","Swj_dsyq/index",3,0);exit;
		}
		foreach ($id_arr as $key => $value) {
			$sql = "select id,name,audit from `{$this->table}` where id='$value'";
			$temp_info = $this->M_common->query_one($sql);
			if (count($temp_info) > 0&&$temp_info['audit']==20) {//审核通过不能删除
				showmessage("{$temp_info['name']}已经审核通过,不能删除","Swj_dsyq/index",3,0);exit;
			} else if (count($temp_info)<=0) {
				showmessage("数据有误,请刷新重试","Swj_dsyq/index",3,0);exit;
			}
		}
		$sql = "update `{$this->table}` set isdel='1' where id in ($ids)";
		if($this->db->query($sql)){
			showmessage("删除园区成功","Swj_dsyq/index",3,1);
			exit();
		}else{
			showmessage("删除园区失败","Swj_dsyq/index",3,0);
			exit();
		}
	}
	//修改电商园区
	function edit(){
		$id = $this->input->get('id') + 0;//电商园区id
		$action = $this->input->get_post("action");		
		$action_array = array("edit", "doedit");
		$action = !in_array($action,$action_array)?'show':$action;	
		if($action == 'show') {//显示修改页面
			$data = $this->M_swj_dsyq->GetModel($id);
			$audit = $data['audit'];//审核状态
			$data['audit_status'] = $this->status[$audit];//审核状态文字显示
			$data['yq_ziliao_file'] = $this->sf->getModelList($data['yq_ziliao']);//园区资料文件
			$data['yq_jianying_file'] = $this->sf->getModelList($data['yq_jianying']);//园区剪影文件
			$data["sess"] = $this->userInfo;
			$data["imgformat"] = $this->imgformat;//对于图片的格式直接显示图片
			$data['town'] = $this->getccd(6,3145,"id asc");//系统中存在的镇区
			$this->load->view(__TEMPLET_FOLDER__."/{$this->template}/dsyq_edit", $data);		
		} elseif($action == 'doedit'){
			$this->doedit();
		}
	}
	//处理申报类目
	private function doedit(){
		// $post = $_POST;
		// var_dump($post);exit;
		// $name = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("name")))));//name
		$id = $this->input->get_post("id");//id
		$createid = $this->userInfo['userid'];//登陆者id
		$name = $this->input->get_post("name");//园区名称	
		$town_id = $this->input->get_post("town_id");//镇区id
		$jymj = $this->input->get_post("jymj");//经营面积
		$jzqynum = $this->input->get_post("jzqynum");//进驻企业总数
		$cqcondition = $this->input->get_post("cqcondition");//产权情况
		$cq_year = $this->input->get_post("cq_year");//租赁年数
		$jzmj = $this->input->get_post("jzmj");//建筑面积
		$zdmj = $this->input->get_post("zdmj");//占地面积
		$dsqynum = $this->input->get_post("dsqynum");//电商企业数
		$haspolicy = $this->input->get_post("haspolicy");//当地扶持政策
		$chenghao = $this->input->get_post("chenghao");//所获称号
		$linkman_name = $this->input->get_post("linkman_name");//联系人姓名
		$linkman_phone = $this->input->get_post("linkman_phone");//联系人电话
		$linkman_work = $this->input->get_post("linkman_work");//联系人职务
		$yq_addr = $this->input->get_post("yq_addr");//园区地址
		$yq_website = $this->input->get_post("yq_website");//园区官方网站
		$yq_describe = htmlspecialchars($this->input->get_post("yq_describe"));//园区简介
		$yq_ziliao = $this->input->get_post("yq_ziliao");//园区资料
		$yq_jianying = $this->input->get_post("yq_jianying");//园区剪影
		$beizhu = htmlspecialchars($this->input->get_post("beizhu"));//备注

		$yq_ziliao_arr    =  @implode(',', $yq_ziliao);//组合以逗号分隔的字符串
		$yq_jianying_arr  =  @implode(',', $yq_jianying);//组合以逗号分隔的字符串
		// var_dump($userInfo);exit;
		$nowTime = date("Y-m-d H:i:s",time());//当前时间
		//添加的数据
		$data = array(
			'name'=>$name,
			'town_id'=>$town_id,
			'jymj'=>$jymj,
			'jzqynum'=>$jzqynum,
			'cqcondition'=>$cqcondition,
			'cq_year'=>$cq_year,
			'jzmj'=>$jzmj,
			'zdmj'=>$zdmj,
			'dsqynum'=>$dsqynum,
			'haspolicy'=>$haspolicy,
			'chenghao'=>$chenghao,
			'linkman_name'=>$linkman_name,
			'linkman_phone'=>$linkman_phone,
			'linkman_work'=>$linkman_work,
			'yq_addr'=>$yq_addr,
			'yq_website'=>$yq_website,
			'yq_describe'=>$yq_describe,
			'yq_ziliao'=>$yq_ziliao_arr,
			'yq_jianying'=>$yq_jianying_arr,
			'beizhu'=>$beizhu,
			'audit'=>10,
			'uid'=>$createid,
			'updatetime'=>$nowTime
		);
		$array = $this->M_common->update_data2($this->table, $data, array('id'=>$id));
		if($array['affect_num']>=1){
			$content = '用户'.$this->userInfo['username'].'修改了园区：'.$name.',请您及时审核';
			$this->M_common_sms->sendSmsToSystem('电商园区', $this->userInfo['userid'], 
				$content, "Swj_dsyq/index");
			//将附件表中上传的文件的备注信息（临时）去掉
			$arr_merge = @array_merge($yq_ziliao, $yq_jianying);
			$this->sf->update_beizhu($arr_merge);
			showmessage("修改成功","Swj_dsyq/index",3,1);
			exit();
		}else{
			// write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"添加类目为{$name}失败");
			showmessage("修改失败","Swj_dsyq/index",3,0);
			exit();
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
	//获取系统模型(镇区等信息)
	private function getccd($typeid,$pid){
		return $this->ccd->GetListFromTypeidAndPid($typeid,$pid);
	}
}