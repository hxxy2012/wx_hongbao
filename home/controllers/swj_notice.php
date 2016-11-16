<?php
/*
 *信息通知控制器
 *author 林科 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

class Swj_notice extends MY_Controller{
	public $table_ ; //表的前缀
	public $table;//表名
	public $userInfo;//用户登录信息
	function Swj_notice(){
		parent::__construct();
		$this->load->model('M_common');
		$this->table_ =table_pre('real_data');
		$this->table = "website_common_info";

		$this->userInfo = $this->parent_getsession();//获取登录信息
		if (!$this->userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$this->load->model('M_swj_info_pub','msip');//信息通知模型
		$this->load->model('M_common','',false , array('type'=>'real_data'));				

	}
	//列表页面
	function index(){
		// var_dump(decode_data());exit;
		$action = $this->input->get_post("action");
		$action_array = array("show","ajax_data");
		$action = !in_array($action,$action_array)?'show':$action ;
		if ($action == 'show') {
			//查询所有的log表数据，生成select
			// $data = array('caozuo' => $this->caozuo);//初始化搜索审核状态为未审核
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_notice_list");
		} elseif($action == 'ajax_data') {
			$this->ajax_data();
		} elseif($action == "upload" ){
			$this->upload() ;
		}	
		
	}
	//ajax get data
	private function ajax_data() {

		$this->load->library("common_page");
		$page = intval($this->input->get_post("page"));
		if($page <=0 ){
			$page = 1 ;
		}
		$per_page = 20;//每一页显示的数量
		$limit = ($page-1)*$per_page;
		$limit.=",{$per_page}";
		$where = " where 1=1 ";//查询条件
		$tablename = $this->table;//活动备案活动备案表
		// $leftjoin  = " left join swj_activity_category sac on sac.id=sab.cid ";//连接备案表
		$sql_count = "SELECT COUNT(*) AS tt FROM {$tablename} {$where} ";
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_log = "SELECT * FROM {$tablename} {$where} order by id desc limit  {$limit}";	

		$list = $this->M_common->querylist($sql_log);
		foreach ($list as $key => $value) {//将公开状态显示为中文
			$jibie = $value['jibie'];//公开状态
			if ($jibie == '0') {//审核状态不为空
				$list[$key]['jibie_str'] = '公开';
			} else {
				$list[$key]['jibie_str'] = '内部';
			}
			//截取标题
			$list[$key]['title'] = msubstr(stripslashes($value['title']), 0, 68, mb_strlen(stripslashes($value['title']), 'utf8'));
			$list[$key]['update_time'] = date('Y-m-d H:i:s', $value['update_time']);
		}
		// file_put_contents('f:/aaaa.txt', var_export($list, true));
		echo result_to_towf_new($list, 1, '成功', $page_string) ;
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

	//查看通知信息内容
	public function look() {
		$id = $this->input->get_post("id");//信息通知id
		$data = $this->msip->getInfo($id);//获取信息通知详细信息
		//限制没有登录的人员不能查询没有公开的信息
		// $sess = $this->parent_getsession();//$this->session->all_userdata();
		if (count($data) <= 0) {
			$this->parent_showmessage(
						0
						,"找不到该信息通知",
						site_url("Swj_notice/index"),
						3
						);
				exit();	
		}
		$get = $this->input->get();	
		$this->setInfoRead($this->userInfo['userid'], $id);//设置该用户已读该信息		
		$backurl = !isset($get["backurl"])?site_url("Swj_notice/index"):$get["backurl"];//返回url
		$data["backurl"] = $backurl;
		$this->load->view(__TEMPLET_FOLDER__."/admin/swj_notice_look", $data);
	}

	//设置该用户已读该信息,$uid用户id，$infoid信息id
	private function setInfoRead($uid, $infoid) {
		$sql        =   "select id from `swj_info_read` where uid='$uid' and infoid='$infoid'";
		$data       =   $this->M_common->query_one($sql);
		$createtime =   date('Y-m-d H:i:s', time());
		if (count($data) > 0) {//已读的修改最后阅读时间
			$where   =  array('uid' => $uid, 'infoid' => $infoid);
			$data    =  array('updatetime' => $createtime);
			$this->M_common->update_data2('swj_info_read', $data, $where);
		} else {//插入到已读表中
			$data    =  array('uid' => $uid, 'infoid' => $infoid, 
						'createtime' => $createtime, 'updatetime' => $createtime);
			$this->M_common->insert_one('swj_info_read', $data);
		}
	}
}