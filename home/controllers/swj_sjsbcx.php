<?php
/*
 *我的上报控制器
 *author 林科 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

class Swj_sjsbcx extends MY_Controller{
	private $table_ ; //表的前缀
	private $table;//表名
	private $audit_array = array(
			0  => '未审核',
			10 => '审核通过',
			20 => '数据有误'
		);//审核状态
	function swj_sjsbcx(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_common_sms');//发送短信公共模型
		$this->load->model('M_common_category_data','ccd'); 
		$this->table_ =table_pre('real_data');
		$this->table = "swj_sjsb_beian_s11";
	}
	//列表页面
	function index(){

		// var_dump(decode_data());exit;
		$action = $this->input->get_post("action");
		$action_array = array("show","ajax_data");
		$userInfo = $this->parent_getsession();//获取登录信息
		if (!$userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$action = !in_array($action,$action_array)?'show':$action ;
		if ($action == 'show') {
			//查询所有的log表数据，生成select
			$this->load->view(__TEMPLET_FOLDER__."/admin/sbcx_list");
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
		$where = ' where 1= 1 and sab.isdel=0 ';//查询条件
		$where.= "and sab.createid='$userid' ";
		// $lmname  = daddslashes(html_escape(strip_tags($this->input->get_post("lmname"))));//类目名称

		$tablename = $this->table;//上报表
		

		/*if(!empty($lmname)){//查询上报名称
			$where.=" AND sac.`name` LIKE '%{$lmname}%'";
		}*/
		$leftjoin  = " left join swj_sjsb_category ssc on ssc.id=sab.cid ";//连接备案表
		$sql_count = "SELECT COUNT(*) AS tt FROM {$tablename} sab {$leftjoin} {$where} ";
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_log = "SELECT sab.*,ssc.name,ssc.sh_stime,ssc.sh_etime FROM {$tablename} sab 
					{$leftjoin} {$where} order by id desc limit  {$limit}";	
		/*$file = fopen("f:/aa.txt","w");//打开文件准备写入
        fwrite($file, $sql_log);//写入
        fclose($file);//关闭*/
        $nowTime = date('Y-m-d H:i:s', time());
		$list = $this->M_common->querylist($sql_log);
		foreach ($list as $key => $value) {//将审核状态显示为中文
			$temp = $value['audit_status'];//审核状态
			$sh_stime = $value['sh_stime'];//审核开始时间
			$sh_etime = $value['sh_etime'];//审核结束时间
			if ($temp) {//审核状态不为空
				$temp_status = $this->audit_array[$temp];
			} else {
				$temp_status = '未审核';
			}
			//如果未审核状态且在审核阶段内，前台不能编辑上报信息
			if ($temp_status == '未审核'&&($nowTime>$sh_stime&&$nowTime<$sh_etime)) {
				$list[$key]['flag_edit'] = false;//不能编辑
			} else {
				$list[$key]['flag_edit'] = true;//能编辑
			}
			$list[$key]['status_format'] = $temp_status;
		}
		// $list['list'] = $list;
		// $list['audit_status'] = $audit_status;
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
	
	//查看上报详情
	function edit(){
		$id = $this->input->get("id");//上报id	
		$action = $this->input->get_post("action");//操作
		// echo $id;exit;
		$action_array = array("edit", "doedit");
		$action = !in_array($action,$action_array)?'edit':$action;	
		if($action == 'edit') {//显示编辑页面
			$data = array();//输出编辑的数据
			$leftjoin  = " left join swj_sjsb_category ssc on ssc.id=ssb.cid ";//连接备案表
			$sql = "select ssb.*,ssc.name from `{$this->table}` ssb {$leftjoin} where ssb.`id`='$id' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			@$index = $data['audit_status'];//审核状态
			@$data['audit'] = $this->audit_array[$index]?$this->audit_array[$index]:'未审核';//审核状态中文显示
			$data['platform_arr'] = explode(",", $data['platform']);//将电商平台打散为数组
			$data['jymodel_arr'] = explode(",", $data['jiaoyimodel']);//将交易模式打散为数组
			$data['all_jyms'] = $this->getccd('11', '45129');//系统中所有的交易模式
			$data['all_platform'] = $this->getccd('11', '45142');//系统中所有的电商平台
			// var_dump($data);exit;
			$this->load->view(__TEMPLET_FOLDER__."/admin/sbcx_edit", $data);		
		} elseif($action == 'doedit'){//处理审核
			$this->doedit();
		}
	}
	//处理上报
	function doedit() {
		
		// $beizhu = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("beizhu")))));//审核意见
		$id = verify_id($this->input->get_post("id"));
		$userInfo = $this->parent_getsession();//获取登录信息
		if (!$userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$audit_status = $this->input->get_post('audit_status');//申报状态
		$company = $this->input->get_post("company");//公司单位	
		$platform = $this->input->get_post("platform");//电商平台
		$platform_str = implode(',', $platform);//以逗号分割
		$jiaoyimodel = $this->input->get_post("jiaoyimodel");//交易模式
		$jiaoyimodel_str = implode(',', $jiaoyimodel);//以逗号分割
		$jiaoyimodel_other = $this->input->get_post("jiaoyimodel_other");//其他交易模式
		$year = $this->input->get_post("year");//年份
		// echo $year;exit;
		$jyl_11_11 = $this->input->get_post("jyl_11_11");//11月11日交易量
		$jye_11_11 = $this->input->get_post("jye_11_11");//11月11日交易额
		$jyl_11_12 = $this->input->get_post("jyl_11_12");//11月12日交易量
		$jye_11_12 = $this->input->get_post("jye_11_12");//11月12日交易额
		$jyl_11_13 = $this->input->get_post("jyl_11_13");//11月13日交易量
		$jye_11_13 = $this->input->get_post("jye_11_13");//11月13日交易额

		// var_dump($userInfo);exit;
		$createid = $userInfo['userid'];
		$nowTime = date("Y-m-d H:i:s",time());//当前时间
		//修改的数据
		$data = array(
			'company'=>$company,
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
			'audit_status'=>0,
			'updateid'=>$createid,
			'updatetime'=>$nowTime
		);
		$where = array('id' => $id);
		$array = $this->M_common->update_data2("swj_sjsb_beian_s11",$data,$where);
		if($array['affect_num']>=1){
			// write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),1,"添加类目为{$name}成功");
			if ($audit_status == 20) {//如果是编辑不通过时，发送短信给管理员
				$lmInfo = $this->M_common->query_one("select sjc.name from `swj_sjsb_beian_s11` ssb left join swj_sjsb_category sjc on ssb.cid=sjc.id where ssb.id='$id'");
				$content = '用户'.$userInfo['username'].'编辑了'.$lmInfo['name'].',请您及时审核';
				$this->M_common_sms->sendSmsToSystem('数据申报', $userInfo['userid'], 
					$content, "Swj_sjsb/index");
			}
			showmessage("修改成功","swj_sjsbcx/index",3,1);
			exit();
		}else{
			// write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"添加类目为{$name}失败");
			showmessage("修改失败","swj_sjsbcx/edit?id=".$id,3,0);
			exit();
		}
	}
	//删除上报,将isdel字段改为1
	function del() {
		$ids = $this->input->get("id");//上报id	
		// $data = array('isdel' => 1);
		// $where = array('id' => $id);
		// $array = $this->M_common->update_data2("{$this->table}", $data, $where);
		$sql = "update `{$this->table}` set isdel='1' where id in ($ids)";
		if($this->db->query($sql)){
			showmessage("删除备案成功","swj_sjsbcx/index",1,1);
			exit();
		}else{
			showmessage("删除备案失败","swj_sjsbcx/index",1,0);
			exit();
		}
	}
	
	//查看上报
	function look(){
		$userInfo = $this->parent_getsession();//获取登录信息
		if (!$userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$userid = $userInfo['userid'];//用户id
		$id = $this->input->get("id");//上报id	
		$action = $this->input->get_post("action");//操作
		// echo $id;exit;
		$action_array = array("look", "dolook");
		$action = !in_array($action,$action_array)?'look':$action;	
		if($action == 'look') {//显示编辑页面
			$data = array();//输出编辑的数据
			$leftjoin  = " left join swj_sjsb_category ssc on ssc.id=sab.cid ";//连接备案表
			$sql = "select sab.*,ssc.name from `{$this->table}` sab {$leftjoin} 
					where sab.`id`='$id' and sab.createid='$userid' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			if (!$data) {
				showmessage("数据出错，请刷新重试","home/login",3,0);
				exit();
			}
			$index = $data['audit_status'];//审核状态
			$data['audit'] = $this->audit_array[$index];
			$data['platform_arr'] = explode(',', $data['platform']);//交易平台
			$data['jymodel_arr'] = explode(',', $data['jiaoyimodel']);//交易模式
			$data['all_jyms'] = $this->getccd('11', '45129');//系统中所有的交易模式
			$data['all_platform'] = $this->getccd('11', '45142');//系统中所有的电商平台
			// var_dump($data);exit;
			$this->load->view(__TEMPLET_FOLDER__."/admin/sbcx_look", $data);		
		} elseif($action == 'dolook'){//处理审核
			$this->dolook();
		}
	}
	//获取图片的信息,$str,附件表id，以逗号分隔.返回该附件的数组
	function getFile($str) {

		$arr = explode(',', $str);
		$rst = array();//返回的数组
		foreach ($arr as $key => $value) {
			$sql = "select * from `swj_fujian` where `id`='$value' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			if ($data) {//存在数据
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