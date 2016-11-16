<?php
/*
 *活动备案控制器
 *author 林科 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

class Swj_hdba extends MY_Controller{
	public $table_ ; //表的前缀
	public $table;//表名
	public $userInfo;//用户登录信息
	public $upload_path;
	public $upload_save_url;
	public $upload_path_sys;
	public $audit_array = array(
			0  => '未审核',
			10 => '初审中',
			20 => '已查看',
			30 => '初审不通过',
			40 => '终审中',
			50 => '终审通过',
			60 => '终审不通过'
		);//审核状态
	function Swj_hdba(){
		parent::__construct();
		$this->load->model('M_common_sms');//发送短信公共模型
		$this->load->model('M_common');
		$this->load->model('M_common_category_data','ccd'); 
		$this->load->model('M_swj_info_hdba','msih');//活动备案模型 
		$this->load->model('M_swj_fujian','sf');//附件模型
		$this->table_ =table_pre('real_data');
		$this->table = "swj_activity_beian";
		$post = $this->input->get();
		$this->userInfo = $this->parent_getsession(isset($post["session_id"])?$post["session_id"]:"");//获取登录信息
		if (!$this->userInfo) {
			showmessage("请先登录","home/login",3,0);
			exit();
		}
		$this->load->model('M_common','',false , array('type'=>'real_data'));				

		$this->upload_path = __ROOT__."/data/upload/editor/"; // 编辑器上传的文件保存的位置
		$this->upload_save_url = "/data/upload/editor/"; //编辑器上传图片的访问的路径		
		// echo $this->upload_save_url;exit;
		$this->upload_path_sys = "data/upload/editor/";//保存字段用的

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
			$this->load->view(__TEMPLET_FOLDER__."/admin/hdba_list");
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
		$userid = $this->userInfo['userid'];//用户id
		$where = " where 1= 1 and sab.isdel=0 and sab.createid='$userid' ";//查询条件
		// $lmname  = daddslashes(html_escape(strip_tags($this->input->get_post("lmname"))));//类目名称
		// $badw = daddslashes(html_escape(strip_tags($this->input->get_post("badw"))));//备案单位
		/*if ($this->input->get_post("audit_status")||$this->input->get_post("audit_status")==='0') {
			$audit_status = $this->input->get_post("audit_status");//审核状态
		}*/
		$tablename = $this->table;//活动备案活动备案表
		/*$file = fopen("f:/aa.txt","w");//打开文件准备写入
        fwrite($file, $where);//写入
        fclose($file);//关闭*/

		// if(!empty($lmname)){//查询活动备案名称
		// 	$where.=" AND sac.`name` LIKE '%{$lmname}%'";
		// }
		// if(!empty($badw)){//查询活动备案名称
		// 	$where.=" AND sab.`company` LIKE '%{$badw}%'";
		// }
		$leftjoin  = " left join swj_activity_category sac on sac.id=sab.cid ";//连接备案表
		$sql_count = "SELECT COUNT(*) AS tt FROM {$tablename} sab {$leftjoin} {$where} ";
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_log = "SELECT sab.*,sac.name FROM {$tablename} sab {$leftjoin} {$where} order by id desc limit  {$limit}";	

		// $file = fopen("f:/aa.txt","w");//打开文件准备写入
  //       fwrite($file, $sql_log);//写入
  //       fclose($file);//关闭
		$list = $this->M_common->querylist($sql_log);
		foreach ($list as $key => $value) {//将审核状态显示为中文
			$temp = $value['audit_status'];//审核状态
			if ($temp) {//审核状态不为空
				$temp_status = $this->audit_array[$temp];
			} else {
				$temp_status = '未审核';
			}
			$list[$key]['audit_status'] = $temp_status;
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
	//新增备案信息
	function add() {
		$id = $this->input->get("id");//活动备案id	
		// echo $id;exit;
		$action = $this->input->get_post("action");//操作
		// echo $id;exit;
		$action_array = array("add", "doadd");
		$action = !in_array($action,$action_array)?'add':$action;	
		if($action == 'add') {//显示添加页面
			$data = array();//输出添加的数据
			$sql = "select id,name,latest from `swj_activity_category` where isshow=1 and isdel=0";
			$query = $this->db->query($sql);
			$data = array('data' => $query->result_array($sql));//查找数据
			// var_dump($data);exit;
			$data['all_hdtype'] = $this->getccd('11', '45146');//备案活动类型
			$data["sess"] = $this->userInfo;
			$this->load->view(__TEMPLET_FOLDER__."/admin/hdba_add", $data);		
		} elseif($action == 'doadd'){//处理审核
			$this->doadd();
		}
	}
	//处理新增活动备案
	function doadd() {

		$cid = $this->input->get_post("act_lm");//活动类目id
		$plan_stime = $this->input->get_post("stime");//活动预期开始时间
		$plan_etime = $this->input->get_post("etime");//活动预期结束时间
		/*if (!$this->chklmvalid($cid)) {//判断类目是否能进行备案，可以返回true，不能返回false
			showmessage("申请失败，请刷新重试","swj_hdba/index",3,0);
			exit();
		}*/
		//判断类目是否能在该时段进行备案，可以返回true，不能返回false
		/*if (!$this->chktimevalid($cid, $plan_stime, $plan_etime)) {
			showmessage("申请失败，活动开始时间距离当前时间超过该类目最迟的备案时间","swj_hdba/index",3,0);
			exit();
		}*/
		$xchb_arr = $this->input->get_post('xchb');//宣传海报
		$fjid = @implode(',', $xchb_arr);//附件id以逗号分隔
		// echo $fjid;exit;
		$actname = $this->input->get_post("actname");//活动类目id
		$type_arr = $this->input->get_post("type");//活动类型
		$type_other = $this->input->get_post("type_other");//其他活动类型
		$type = implode(',', $type_arr);//以逗号分割
		$nature = $this->input->get_post("nature");//活动性质
		$isfree = $this->input->get_post("isfree");//活动是否收费
		$plan_place = $this->input->get_post("plan_place");//活动场地
		$description = $this->input->get_post("description");//活动简介
		$plan_join_num = $this->input->get_post("plan_join_num");//预期参与人数
		$xcwz = $this->input->get_post("xcwz");//宣传网址
		$plan_price = $this->input->get_post("plan_price");//活动总预算
		$company = $this->input->get_post("company");//活动举办单位
		$plan_beizhu = $this->input->get_post("beizhu");//备注
		$nowTime = date("Y-m-d H:i:s",time());//当前时间
		$createid = $this->userInfo['userid'];
		//组合插入的数据
		$data = array(
				'cid'=>$cid,
				'actname'=>$actname,
				'type'=>$type,
				'type_other' =>$type_other,
				'nature'=>$nature,
				'isfree'=>$isfree,
				'plan_stime'=>$plan_stime,
				'plan_etime'=>$plan_etime,
				'plan_place'=>$plan_place,
				'description'=>$description,
				'plan_join_num'=>$plan_join_num,
				'xchb'=>$fjid,
				'xcwz'=>$xcwz,
				'plan_price'=>$plan_price,
				'company'=>$company,
				'plan_beizhu'=>$plan_beizhu,
				'createid'=>$createid,
				'createtime'=>$nowTime,
				'updateid'=>$createid,
				'updatetime'=>$nowTime
			);
		// $array = $this->M_common->update_data2("{$this->table}", $data, $where);
		$array = $this->M_common->insert_one("{$this->table}", $data);
		if($array['affect_num']>=1){
			$lmInfo = $this->M_common->query_one("select * from `swj_activity_category` where id='$cid'");
			$content = '用户'.$this->userInfo['username'].'申报了'.$lmInfo['name'].',请您及时审核';
			$this->M_common_sms->sendSmsToSystem('数据备案', $this->userInfo['userid'], 
				$content, "swj_hdba/index");
			//将附件表中上传的文件的备注信息（临时）去掉
			$this->sf->update_beizhu($xchb_arr);
			showmessage("申请成功，请耐心等待","swj_hdba/index",3,1);
			exit();
		}else{
			showmessage("申请失败，请刷新重试","swj_hdba/index",3,0);
			exit();
		}
	}
	//编辑活动备案
	function edit(){
		$id = $this->input->get("id");//活动备案id	
		// echo $id;exit;
		$action = $this->input->get_post("action");//操作
		// echo $id;exit;
		$action_array = array("edit", "doedit");
		$action = !in_array($action,$action_array)?'edit':$action;	
		if($action == 'edit') {//显示编辑页面
			$data = array();//输出编辑的数据
			//编辑的备案信息
			$leftjoin  = " left join swj_activity_category sac on sac.id=sab.cid ";//连接备案表
			$sql = "select sab.*,sac.name from `{$this->table}` sab {$leftjoin} where sab.`id`='$id' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			$index = $data['audit_status'];//审核状态
			//备案类目
			$sql = "select id,name,latest from `swj_activity_category` where isshow=1 and isdel=0";
			$query = $this->db->query($sql);
			$data['balm'] = $query->result_array($sql);//备案类目
			$data['audit'] = $this->audit_array[$index];
			$data['type_arr'] = explode(",", $data['type']);//将活动类型以逗号分隔
			$data['file'] = $this->getFile($data['xchb']);//活动宣传海报、文件
			// var_dump($data);exit;
			$data['all_hdtype'] = $this->getccd('11', '45146');//备案活动类型
			$data["sess"] = $this->userInfo;
			$this->load->view(__TEMPLET_FOLDER__."/admin/hdba_edit", $data);		
		} elseif($action == 'doedit'){//处理审核
			$this->doedit();
		}
	}
	//编辑活动备案处理
	function doedit() {
		
		$id = verify_id($this->input->get_post("id"));//活动备案id
		$audit_status = $this->input->get_post("audit_status");//之前的备案状态
		$bianji_flag = 1;//代表编辑的是未审核
		if ($audit_status == 30) {//如果编辑不通过时，将其状态改为初审中
			$audit_status = 10;//代表编辑的是审核不通过
			$bianji_flag = 2;//
		} else {//改为未审核
			$audit_status = 0;
		}
		$cid = $this->input->get_post("act_lm");//活动类目id
		$plan_stime = $this->input->get_post("stime");//活动预期开始时间
		$plan_etime = $this->input->get_post("etime");//活动预期结束时间
		/*if (!$this->chklmvalid($cid)) {//判断类目是否能进行备案，可以返回true，不能返回false
			showmessage("申请失败，请刷新重试","swj_hdba/index",3,0);
			exit();
		}*/
		//判断类目是否能在该时段进行备案，可以返回true，不能返回false
		/*if (!$this->chktimevalid($cid, $plan_stime, $plan_etime)) {
			showmessage("申请失败，活动开始时间距离当前时间超过该类目最迟的备案时间","swj_hdba/index",3,0);
			exit();
		}*/
		$xchb_arr = $this->input->get_post('xchb');//宣传海报
		$fjid = @implode(',', $xchb_arr);//以逗号分割
		//更新的数据
		$actname = $this->input->get_post("actname");//活动类目id
		$type_arr = $this->input->get_post("type");//活动类型
		$type_other = $this->input->get_post("type_other");//其他活动类型
		$type = implode(',', $type_arr);//以逗号分割
		$nature = $this->input->get_post("nature");//活动性质
		$isfree = $this->input->get_post("isfree");//活动是否收费
		$plan_place = $this->input->get_post("plan_place");//活动场地
		$description = $this->input->get_post("description");//活动简介
		$plan_join_num = $this->input->get_post("plan_join_num");//预期参与人数
		$xcwz = $this->input->get_post("xcwz");//宣传网址
		$plan_price = $this->input->get_post("plan_price");//活动总预算
		$company = $this->input->get_post("company");//活动举办单位
		$plan_beizhu = $this->input->get_post("plan_beizhu");//备注
		$nowTime = date("Y-m-d H:i:s",time());//当前时间
		$createid = $this->userInfo['userid'];
		//组合插入的数据
		$data = array(
				'cid'=>$cid,
				'actname'=>$actname,
				'type'=>$type,
				'type_other'=>$type_other,
				'nature'=>$nature,
				'isfree'=>$isfree,
				'plan_stime'=>$plan_stime,
				'plan_etime'=>$plan_etime,
				'plan_place'=>$plan_place,
				'description'=>$description,
				'plan_join_num'=>$plan_join_num,
				'xchb'=>$fjid,
				'xcwz'=>$xcwz,
				'plan_price'=>$plan_price,
				'company'=>$company,
				'plan_beizhu'=>$plan_beizhu,
				'audit_status'=>$audit_status,
				'updateid'=>$createid,
				'updatetime'=>$nowTime
			);
		$where = array('id' => $id);
		$array = $this->M_common->update_data2("{$this->table}", $data, $where);
		if($array['affect_num']>=1){
			$hdInfo = $this->M_common->query_one("select * from `swj_activity_beian` where id='$id'");
			$content = '用户'.$this->userInfo['username'].'编辑了活动信息：'.$hdInfo['actname'].',请您及时审核';
			$this->M_common_sms->sendSmsToSystem('数据备案', $this->userInfo['userid'], 
				$content, "swj_hdba/index");
			$this->sf->update_beizhu($xchb_arr);
			if ($bianji_flag == 2) {//编辑为初审不通过的返回到列表页面
				showmessage("编辑成功","swj_hdba/index",3,1);
			} else {//返回到编辑页面
				showmessage("编辑成功","swj_hdba/edit?id=".$id,3,1);
			}
			exit();
		}else{
			showmessage("编辑失败，请刷新重试","swj_hdba/edit?id=".$id,3,0);
			exit();
		}
	}

	//补充活动备案
	function sup(){
		$id = $this->input->get("id");//活动备案id	
		// echo $id;exit;
		$action = $this->input->get_post("action");//操作
		// echo $id;exit;
		$action_array = array("sup", "dosup");
		$action = !in_array($action,$action_array)?'sup':$action;	
		if($action == 'sup') {//显示编辑页面
			$data = array();//输出编辑的数据
			//编辑的备案信息
			$leftjoin  = " left join swj_activity_category sac on sac.id=sab.cid ";//连接备案表
			$sql = "select sab.*,sac.name from `{$this->table}` sab {$leftjoin} where sab.`id`='$id' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			$index = $data['audit_status'];//审核状态
			//备案类目
			$sql = "select id,name,latest from `swj_activity_category` where isshow=1 and isdel=0";
			$query = $this->db->query($sql);
			$data['balm'] = $query->result_array($sql);//备案类目
			$data['audit'] = $this->audit_array[$index];
			// var_dump($data);exit;
			$data['type_arr'] = explode(",", $data['type']);//将活动类型以逗号分隔
			$data['file_second_qdb'] = $this->getFile($data['second_actqd']);//活动签到表
			$data['file_second_jy'] = $this->getFile($data['second_jy']);//活动宣传海报、文件
			$data['file_second_kz_hdfp'] = $this->getFile($data['second_kz_hdfp']);//活动宣传海报、文件
			// var_dump($data);exit;
			$data["sess"] = $this->userInfo;
			$this->load->view(__TEMPLET_FOLDER__."/admin/hdba_sup", $data);		
		} elseif($action == 'dosup'){//处理审核
			$this->dosup();
		}
	}
	//补充活动备案处理
	function dosup() {
		
		$id = verify_id($this->input->get_post("id"));//活动备案id
		$audit_status = $this->input->get_post("audit_status");//之前的备案状态
		/*$bianji_flag = 1;//代表编辑的是未审核
		if ($audit_status == 60) {//如果编辑不通过时，将其状态改为初审中
			$audit_status = 40;//终审中
			$bianji_flag = 2;//代表编辑的是审核不通过
		} else {//改为未审核
			$audit_status = 20;
		}*/
		$second_stime = $this->input->get_post("stime");//活动实际开始时间
		$second_etime = $this->input->get_post("etime");//活动实际结束时间

		//更新的数据
		$second_join_num = $this->input->get_post("second_join_num");//实际参与人数
		$second_place = $this->input->get_post("second_place");//活动实际场地
		
		$second_pricekz = $this->input->get_post("second_pricekz");//活动实际开支
		$second_teacherkz = $this->input->get_post("second_teacherkz");//活动教师开支
		$second_wykz = $this->input->get_post("second_wykz");//活动文印开支
		$second_xckz = $this->input->get_post("second_xckz");//活动宣传开支
		$second_otherkz = $this->input->get_post("second_otherkz");//活动其他杂项开支
		$second_kzbeizhu = $this->input->get_post("second_kzbeizhu");//其他开支备注
		$second_beizhu = $this->input->get_post("second_beizhu");//备注
		$nowTime = date("Y-m-d H:i:s",time());//当前时间
		$themejyid = $this->input->get_post('themejyid');//主题图附件id
		$createid = $this->userInfo['userid'];

		//附件
		$file_second_qdb = $this->input->get_post("file_second_qdb");//活动签到表
		$file_second_jy = $this->input->get_post("file_second_jy");//活动剪影
		$file_second_kz_hdfp = $this->input->get_post("file_second_kz_hdfp");//合同或发票

		$second_actqd = @implode(',', $file_second_qdb);//打散为字符串
		$second_jy = @implode(',', $file_second_jy);//打散为字符串
		$second_kz_hdfp = @implode(',', $file_second_kz_hdfp);//打散为字符串
		//组合插入的数据
		$data = array(
				'second_stime'=>$second_stime,
				'second_etime'=>$second_etime,
				'second_join_num'=>$second_join_num,
				'second_place'=>$second_place,
				'second_pricekz'=>$second_pricekz,
				'second_teacherkz'=>$second_teacherkz,
				'second_wykz'=>$second_wykz,
				'second_xckz'=>$second_xckz,
				'second_otherkz'=>$second_otherkz,
				'second_kzbeizhu'=>$second_kzbeizhu,
				'second_beizhu'=>$second_beizhu,
				'updateid'=>$createid,
				'updatetime'=>$nowTime,
				'audit_status'=>40,
				'themejyid'=>$themejyid,
				'second_actqd'=>$second_actqd,
				'second_jy'=>$second_jy,
				'second_kz_hdfp'=>$second_kz_hdfp
			);
		$where = array('id' => $id);
		$array = $this->M_common->update_data2("{$this->table}", $data, $where);
		if($array['affect_num']>=1){
			$hdInfo = $this->M_common->query_one("select * from `swj_activity_beian` where id='$id'");
			$content = '用户'.$this->userInfo['username'].'补充了活动信息：'.$hdInfo['actname'].',请您及时审核';
			$this->M_common_sms->sendSmsToSystem('数据备案', $this->userInfo['userid'], 
				$content, "swj_hdba/index");
			//去掉上传文件时的临时
			$merge = @array_merge($file_second_qdb, $file_second_jy, $file_second_kz_hdfp);
			$this->sf->update_beizhu($merge);
			showmessage("补充成功","swj_hdba/index",3,1);
			// if ($bianji_flag == 2) {//编辑为终审不通过的返回到列表页面
			// 	showmessage("补充成功","swj_hdba/index",3,1);
			// } else {//返回到编辑页面
			// 	showmessage("补充成功","swj_hdba/sup?id=".$id,3,1);
			// }
			exit();
		}else{
			showmessage("补充失败，请刷新重试","swj_hdba/sup?id=".$id,3,0);
			exit();
		}
	}
	//删除活动备案,将isdel字段改为1
	function del() {
		$ids = $this->input->get("id");//活动备案id	
		// $data = array('isdel' => 1);
		// $where = array('id' => $id);
		// $array = $this->M_common->update_data2("{$this->table}", $data, $where);
		$sql = "update `{$this->table}` set isdel='1' where id in ($ids)";
		if($this->db->query($sql)){
			showmessage("删除备案成功","swj_hdba/index",1,1);
			exit();
		}else{
			showmessage("删除备案失败","swj_hdba/index",1,0);
			exit();
		}
	}
	
	//查看活动备案
	function look(){
		$id = $this->input->get("id");//活动备案id	
		$action = $this->input->get_post("action");//操作
		// echo $id;exit;
		$action_array = array("look", "dolook");
		$action = !in_array($action,$action_array)?'look':$action;	
		if($action == 'look') {//显示编辑页面
			$data = array();//输出编辑的数据
			$leftjoin  = " left join swj_activity_category sac on sac.id=sab.cid ";//连接备案表
			$sql = "select sab.*,sac.name from `{$this->table}` sab {$leftjoin} where sab.`id`='$id' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			$index = $data['audit_status'];//审核状态
			$data['audit'] = $this->audit_array[$index];
			$data['type_arr'] = explode(",", $data['type']);//将活动类型以逗号分隔
			$data['file'] = $this->getFile($data['xchb']);//活动宣传海报、文件
			$data['file_second_qdb'] = $this->getFile($data['second_actqd']);//活动签到表
			$data['file_second_jy'] = $this->getFile($data['second_jy']);//活动宣传海报、文件
			$data['file_second_kz_hdfp'] = $this->getFile($data['second_kz_hdfp']);//活动宣传海报、文件
			$data['all_hdtype'] = $this->getccd('11', '45146');//备案活动类型
			// var_dump($data);exit;
			$data["sess"] = $this->userInfo;
			$this->load->view(__TEMPLET_FOLDER__."/admin/hdba_look", $data);		
		} elseif($action == 'dolook'){//处理审核
			$this->dolook();
		}
	}
	//删除活动备案的附件
	function delfujian() {
		$id = $this->input->get("id");//活动备案id	
		$fjid = $this->input->get("fjid");//附件id	
		$field = $this->input->get('field');//删除附件的字段名称
		$bak_method = $this->input->get('bak_method');//返回的方法
		$sql = "select `$field` from `swj_activity_beian` where `id`='$id'";
		$data = $this->M_common->query_one($sql);//查找数据
		// var_dump($data);exit;
		$xchb_arr = explode(',', $data[$field]);//转成数组
		$key = array_search($fjid, $xchb_arr);
		if ($key !== false) {//如果能找到，删除该附件id
			array_splice($xchb_arr, $key, 1);
			// echo $id;
			// var_dump($xchb_arr);
		}
		// exit;
		$xchb_str = implode(',', $xchb_arr);//为字符串
		// echo $xchb_str;exit;
		$data = array($field => $xchb_str);
		$where = array('id' => $id);
		$array = $this->M_common->update_data2("{$this->table}", $data, $where);
		if ($array['affect_num'] >= 1) {//更新成功,删除附件表
			$this->delFile($fjid);
			showmessage("删除成功","swj_hdba/$bak_method?id=".$id,1,1);
		} else {
			showmessage("删除失败，请刷新重试","swj_hdba/$bak_method?id=".$id,1,0);
		}
	}

	//获取图片的信息,$str,附件表id，以逗号分隔.返回该附件的数组
	private function getFile($str) {

		$arr = explode(',', $str);
		$rst = array();//返回的数组
		foreach ($arr as $key => $value) {
			$sql = "select * from `swj_fujian` where `id`='$value' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			if ($data) {//存存数据
				$rst[$value] = $data;
			}
		}
		return $rst;
	}
	//删除附件，$fjid:附件id
	private function delFile($fjid) {
		//查找附件表的附件路径
		$sql = "select `filesrc` from `swj_fujian` where `id`='$fjid'";
		$data = $this->M_common->query_one($sql);//查找数据
		$filesrc = __ROOT__.'/'.$data['filesrc'];//附件路径
		@unlink($filesrc);//删除
		$sql = "delete from `swj_fujian` where `id`='$fjid'";
		$this->db->query($sql);
	}
	//插件(kindeditor)上传文件
	function upload(){
		// echo $this->sysconfig->test33();exit;
		//包含kindeditor的上传文件
		$save_path =$this->upload_path ; // 编辑器上传的文件保存的位置
		$save_url = $this->upload_save_url; //访问的路径
		include_once __ROOT__.'/'.APPPATH."libraries/JSON.php" ;
		include_once __ROOT__.'/'.APPPATH."libraries/upload_json.php" ;
		
	}	

	//多文件上传,新增活动备案时候用到
	public function uploadify() {
		//设置上传目录
		$path = 'data/upload/act_record/';
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}
		$year = date('Y', time());//文件按年份存储
		$path .= $year.'/';
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}

		if (!empty($_FILES)) {
			
			//得到上传的临时文件流
			$tempFile = $_FILES['Filedata']['tmp_name'];
			
			//允许的文件后缀
			$fileTypes = array('jpg','jpeg','gif','png','doc','pdf','rar','xls','xlsx'); 
			
			//得到文件原名
			$fileName = iconv("UTF-8","GB2312",$_FILES["Filedata"]["name"]);
			// $fileParts = pathinfo($_FILES['Filedata']['name']);
			$type = substr($fileName, strripos($fileName, '.'));//截取.后面的类型
			$fileName  = time().mt_rand(1,1000).$type;
			$fileParts = pathinfo($fileName);

			//接受动态传值
			$files=$_POST['typeCode'];
			$path .= date('Ymd', time()).'/';
			//最后保存服务器地址
			if(!is_dir($path))
			   mkdir($path);
			if (move_uploaded_file($tempFile, $path.$fileName)){
				echo $path.$fileName;
			}else{
				echo -1;
			}
		}
	}
	//多文件上传(sup补充页面的上传)，直接上传不需要提交。补充时候用到(编辑也有)
	public function uploadify2() {
		//设置上传目录
		$path = 'data/upload/act_record/';
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}
		$year = date('Y', time());//文件按年份存储
		$path .= $year.'/';
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}

		if (!empty($_FILES)) {
			
			//得到上传的临时文件流
			$tempFile = $_FILES['Filedata']['tmp_name'];
			
			//允许的文件后缀
			$fileTypes = array('jpg','jpeg','gif','png','doc','pdf','rar','xls','xlsx'); 
			
			//得到文件原名
			$fileName = iconv("UTF-8","GB2312",$_FILES["Filedata"]["name"]);
			// $fileParts = pathinfo($_FILES['Filedata']['name']);
			$type = substr($fileName, strripos($fileName, '.'));//截取.后面的类型
			$fileName  = time().mt_rand(1,1000).$type;
			$fileParts = pathinfo($fileName);

			//接受动态传值
			$typeCode = $_POST['typeCode'];
			$path .= date('Ymd', time()).'/';
			//最后保存服务器地址
			if(!is_dir($path))
			   mkdir($path);
			if (move_uploaded_file($tempFile, $path.$fileName)){
				$fjid = $this->insertFile($path.$fileName);//插入到附件表中,返回id
				$this->updateSabFj($typeCode, $fjid);//更新sab的附件信息
				echo $path.$fileName.'$_$'.$fjid;
			}else{
				echo -1;
			}
		}
	}
	//插入到附件表中,返回插入的id
	private function insertFile($file) {
		$data = array('title' => '活动备案', 'filesrc' => $file, 'userid' => $this->userInfo['userid']);
		$array = $this->M_common->insert_one('swj_fujian', $data);
		return $array['insert_id'];
	}
	//更新附件信息 $typeCode：存储字段和备案id, $fjid，附件id
	private function updateSabFj($typeCode, $fjid) {
		$post_arr = explode(',', $typeCode);
		$field = $post_arr[0];//要操作的字段
		$id = $post_arr[1];//活动备案id
		$sql = "select `$field` from `swj_activity_beian` where `id`='$id'";
		$data = $this->M_common->query_one($sql);//查找数据
		$data_in = array();//更新的数据
		if ($data[$field]) {//该字段存在值,组合为新的字符串
			$field_arr = explode(',', $data[$field]);//转成数组
			$field_arr = array_merge($field_arr, array(0=>$fjid));//合并数组
			$field_str = implode(',', $field_arr);//以逗号分隔
		} else {//之前没有值，直接赋值刚上传的附件id
			$field_str = $fjid;
			$data_in['themejyid'] = $fjid;//默认主题图
		}
		$data_in["$field"] = $field_str;
		$where = array('id' => $id);
		$array = $this->M_common->update_data2("swj_activity_beian", $data_in, $where);
		if ($array['affect_num'] >= 1) {//更新成功,删除附件表
			return true;
		} else {
			return false;
		}
	}
	//获取备案名称,返回json数组
	function getactname(){
		$get = $this->input->get();
		$key = empty($get["query"])?"":trim($get["query"]);
		$userid = $this->userInfo['userid'];//登录用户id
		$where = " where 1=1 and createid='$userid' and isdel='0' and actname like '%$key%' ";
		$list = $this->msih->GetList($where);
		$rows = "";
		foreach($list as $k=>$v){
			$rows[] = array("value"=>$v["id"],"label"=>$v["actname"]);	
		}
		echo json_encode($rows);
		exit();
	}
	//获取系统模型
	private function getccd($typeid,$pid){
		return $this->ccd->GetListFromTypeidAndPid($typeid,$pid);
	}
}