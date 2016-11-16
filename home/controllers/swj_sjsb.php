<?php
/*
 *数据上报控制器
 *author 林科 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class Swj_sjsb extends MY_Controller{
	private $table_ ; //表的前缀
	private $status;
	function Swj_sjsb(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_common_sms');//发送短信公共模型
		$this->load->model('M_common_category_data','ccd'); 
		$this->load->library('session');
		$this->table_ =table_pre('real_data');
		$this->table = 'swj_sjsb_category';//数据上报类目表
		$this->status = array(0=>'进行中',1=>'未开始',2=>'已过期',3=>'已申报');
	}
	//列表页面
	function index(){

		// var_dump(decode_data());exit;
		// var_dump($_COOKIE);exit;
		$userInfo = $this->parent_getsession();//获取登录信息
		// var_dump($userInfo);exit;
		if (!$userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$action = $this->input->get_post("action");	
		$action_array = array("show","ajax_data");
		$action = !in_array($action,$action_array)?'show':$action ;
		if ($action == 'show') {
			//查询所有的log表数据，生成select
			$this->load->view(__TEMPLET_FOLDER__."/admin/sjlm_list");
		} elseif($action == 'ajax_data') {
			$this->ajax_data();
		}
		
	}
	//ajax get data
	private function ajax_data() {

		$result = array('resultinfo' => array('list' => '', 'errmsg' => ''), 'resultcode' => '99999');
		$userInfo = $this->parent_getsession();//获取登录信息
		if (!$userInfo) {
			echo json_encode($result);
			exit();
		}
		$userid = $userInfo['userid'];//用户id
		$this->load->library("common_page");
		$page = intval($this->input->get_post("page"));
		if($page <=0 ){
			$page = 1 ;
		}
		$per_page = 20;//每一页显示的数量
		$limit = ($page-1)*$per_page;
		$limit.=",{$per_page}";
		$where = ' where 1= 1 and isdel=0 and isshow=1 ';//查询条件
		$name = daddslashes(html_escape(strip_tags($this->input->get_post("lmname"))));//类目名称
		$tablename = $this->table;//数据上报类目表

		if(!empty($name)){//查询类目名称
			$where .= " AND `name` LIKE '%{$name}%'";
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM {$tablename} {$where} ";
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_log = "SELECT * FROM {$tablename} {$where} order by id desc limit  {$limit}";	

		$list = $this->M_common->querylist($sql_log);
		$nowTime = date('Y-m-d H:i:s', time());
		foreach ($list as $key => $value) {//处理数据输出
			$id = $value['id'];//id
			$sb_stime = $value['sb_stime'];//上报开始时间
			$sb_etime = $value['sb_etime'];//上报结束时间
			$flag = 0;
			if ($this->check_ysb($id, $userid)) {//已申报
				$flag = 3;
			} else if ($nowTime > $sb_etime) {//已结束
				$flag = 2;
			} else if ($nowTime > $sb_stime) {//进行中
				$flag = 0;
			} else {//未开始
				$flag = 1;
			}

			$list[$key]['status'] = $this->status[$flag];//状态
			$list[$key]['timepart'] = date("Y-m-d H:i",strtotime($sb_stime)).' 至 '. date("Y-m-d H:i",strtotime($sb_etime));
		}
		echo result_to_towf_new($list, 1, '成功', $page_string) ;
	}
	
	//申报类目
	function shenbao(){
		$action = $this->input->get_post("action");		
		$action_array = array("shenbao", "doshenbao");
		$action = !in_array($action,$action_array)?'show':$action;	
		if($action == 'show') {//显示申报页面
			$id = $this->input->get("id");//类目id
			$data = array('id'=>$id);
			$data['all_jyms'] = $this->getccd('11', '45129');//系统中所有的交易模式
			$data['all_platform'] = $this->getccd('11', '45142');//系统中所有的电商平台
			$this->load->view(__TEMPLET_FOLDER__."/admin/sjlm_shenbao", $data);		
		} elseif($action == 'doshenbao'){
			$this->doshenbao();
		}
	}
	//处理申报类目
	private function doshenbao(){

		// $name = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("name")))));//name
		// $beizhu = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("content")))));//beizhu
		// $beizhu = $this->input->get_post("content");
		$userInfo = $this->parent_getsession();//获取登录信息
		if (!$userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$createid = $userInfo['userid'];
		$id = verify_id($this->input->get_post("id"));
		if ($this->check_ysb($id, $createid)) {//判断该类目是否已申报，是返回true否则false
			showmessage("您已经申报了该类目","Swj_sjsb/index",3,0);
			exit();
		}
		if (!$this->check_lming($id)) {//判断该类目是否正在进行，是返回true否则false
			showmessage("该类目不在申报时间段内，请选择正在进行中的类目进行申报","Swj_sjsb/index",3,0);
			exit();
		}
		$company = $this->input->get_post("company");//公司单位	
		$platform = $this->input->get_post("platform");//电商平台
		$platform_str = implode(',', $platform);//以逗号分割
		$jiaoyimodel = $this->input->get_post("jiaoyimodel");//交易模式
		$jiaoyimodel_str = implode(',', $jiaoyimodel);//以逗号分割
		$jiaoyimodel_other = $this->input->get_post("jiaoyimodel_other");//其他交易模式
		$year = $this->input->get_post("year");//年份
		$jyl_11_11 = $this->input->get_post("jyl_11_11");//11月11日交易量
		$jye_11_11 = $this->input->get_post("jye_11_11");//11月11日交易额
		$jyl_11_12 = $this->input->get_post("jyl_11_12");//11月12日交易量
		$jye_11_12 = $this->input->get_post("jye_11_12");//11月12日交易额
		$jyl_11_13 = $this->input->get_post("jyl_11_13");//11月13日交易量
		$jye_11_13 = $this->input->get_post("jye_11_13");//11月13日交易额

		// var_dump($userInfo);exit;
		$nowTime = date("Y-m-d H:i:s",time());//当前时间
		//添加的数据
		$data = array(
			'company'=>$company,
			'cid'=>$id,
			'platform'=>$platform_str,
			'jiaoyimodel'=>$jiaoyimodel_str,
			'jiaoyimodel_other'=>$jiaoyimodel_other,
			'year'=>$year,
			'jyl_11_11'=>$jyl_11_11,
			'jye_11_11'=>$jye_11_11,
			'jyl_11_12'=>$jyl_11_12,
			'jye_11_12'=>$jye_11_12,
			'jyl_11_13'=>$jyl_11_13,
			'jye_11_13'=>$jye_11_13,
			'createid'=>$createid,
			'createtime'=>$nowTime,
			'updateid'=>$createid,
			'updatetime'=>$nowTime
		);
		$array = $this->M_common->insert_one("swj_sjsb_beian_s11",$data);
		if($array['affect_num']>=1){
			$lmInfo = $this->M_common->query_one("select * from `swj_sjsb_category` where id='$id'");
			$content = '用户'.$userInfo['username'].'申报了'.$lmInfo['name'].',请您及时审核';
			$this->M_common_sms->sendSmsToSystem('数据申报', $userInfo['userid'], 
				$content, "Swj_sjsb/index");
			showmessage("申报成功","Swj_sjsb/index",3,1);
			exit();
		}else{
			// write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"添加类目为{$name}失败");
			showmessage("申报失败","Swj_sjsb/index",3,0);
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
	//判断该类目是否正在进行$id:类目id，是返回true否则false
	private function check_lming($id) {

		$nowTime = date('Y-m-d H:i:s', time());//当前时间
		$sql = "select sb_stime,sb_etime from `swj_sjsb_category` where `id`='$id'";
		$data = $this->M_common->query_one($sql);//查找数据
		if ($nowTime > $data['sb_stime'] && $nowTime < $data['sb_etime']) {//正在进行的上报
			return true;
		} else {
			return false;
		}
	}
	//判断该类目是否已申报$id:类目id，是返回true否则false
	private function check_ysb($id, $createid) {

		$sql = "select id from `swj_sjsb_beian_s11` where `cid`='$id' and isdel='0' and `createid`='$createid'";
		$query = $this->db->query($sql);
		if ($query->num_rows()) {//有数据
			return true;
		} else {
			return false;	
		}
		
	}
	//获取系统模型
	private function getccd($typeid,$pid){
		return $this->ccd->GetListFromTypeidAndPid($typeid,$pid);
	}
}