<?php
//普通用户找回密码页面
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
@ini_set( "display_errors" ,"On");
class Swj_recover_password extends MY_ControllerLogout{	

	function Swj_recover_password(){
		parent::__construct();
		
		$this->load->model('M_user','user');
		$this->load->model("M_swj_reg_tel_code","tel_code");	
		$this->load->model('m_common_sms','sms');	
		
	}
	function index(){
		// header("location:".site_url("Swj_recover_password/step1"));	
		$get = $this->input->get();			
		$ls = !isset($get["ls"])?site_url("home/index"):$get["ls"];
		$data["ls"] = $ls;
		$this->load->view(__TEMPLET_FOLDER__."/recoverpassword/index",$data);
	}
	//通过发送密码短信到手机号进行找回密码
	function getPwdByPhone() {
		$post = $this->input->post();
		$config = $this->parent_sysconfig();
		$phone = $post['mysql_tel'];//手机号码
		$uInfo = $this->chktel_ht($phone);//获取该手机号的用户信息
		if (count($uInfo) <= 0) {//判断发送过来手机号是否存在系统
			// showmessage("手机号不存在","Swj_recover_password/index",3,0);exit;
			$this->parent_showmessage(
					0
					,"手机号不存在",
					site_url("Swj_recover_password/index"),
					3,
					'showmessage_logout');
			exit();
		}
		$uid     =  $uInfo['uid'];//用户id
		//修改新的密码
		$pwd     =  mt_rand(100000, 999999);//获取随机的密码
		$data    =  array('passwd' => md5($pwd));
		$where   =  array('uid' => $uid);//条件
		$result  =  $this->M_common->update_data2('57sy_common_user', $data, $where);//更新数据
		if ($result['affect_num'] > 0) {//修改成功,发送密码短信到该手机中
			$slname  =  $this->getSLName($uInfo['username']);//获取带有隐藏（*）的账号
			$msg     =  '用户:'.$slname.'您好,'.$config["site_fullname"].'重置后的密码为：'.$pwd.',请您及时登录系统进行修改密码。';
			$this->sms->send_message( $msg, "手机找回密码","-1","-1", 
				array(array("id"=>$uid,"type"=>1,"phone"=>$phone))
				, "Swj_recover_password/index");
			//更新用户表的editpwd字段
			$data    =  array('editpwd' => '1');
			$where   =  array('uid' => $uid);//条件
			$result  =  $this->M_common->update_data2('57sy_common_user', $data, $where);//更新数据
			// showmessage("新的密码已经发到您填写的手机中，请您及时登录系统修改密码","home/login",3,1);
			$this->parent_showmessage(
					1
					,"新的密码已经发到您填写的手机中，请您及时登录系统修改密码",
					site_url("home/login"),
					3,
					'showmessage_logout');
			exit();
		} else {
			$this->parent_showmessage(
					0
					,"找回失败，请刷新重试",
					site_url("Swj_recover_password/index"),
					3,
					'showmessage_logout');
			exit();
			// showmessage("找回失败，请刷新重试","Swj_recover_password/index",3,0);
		}
		
	}
	//获取带有隐藏（*）的账号
	private function getSLName($username) {
		$slname = "";
		$head_str = substr($username, 0, stripos($username, '@'));
		// $late_str = substr($username, stripos($username, '@'));
		$head_length = strlen($head_str);//账号@前面的长度
		if ($head_length < 0) {//截取不到，返回原来数据
			return $username;
		}
		switch ($head_length) {
			case 0:case 1://长度为0,1
				$slname = $username;
				break;
			case 2:case 3:
				$slname = '*'.substr($username, 1);//从1位置开始截取，组合字符串
				break;
			case 4:case 5:
				$slname = '**'.substr($username, 2);//从2位置开始截取，组合字符串
				break;
			default:
				$slname = '****'.substr($username, 4);//从4位置开始截取，组合字符串
				break;
		}
		return $slname;
	}
	//邮箱找回密码
	function getPwdByEmail() {
		$post     =   $this->input->post();
		$config   =   $this->parent_sysconfig();
		$email    =   $post['mysql_email'];//邮箱
		$uInfo    =   $this->M_common->query_one("select uid from 57sy_common_user where isdel=0 and username='$email'");
		if (count($uInfo) <= 0) {//判断邮箱是否存在，不存在则提示
			// showmessage("您填写的邮箱不存在","Swj_recover_password/index",3,0);
			$this->parent_showmessage(
					0
					,"您填写的邮箱不存在",
					site_url("Swj_recover_password/index"),
					3,
					'showmessage_logout');
			exit();
		}
		//重置密码
		$uid     =  $uInfo['uid'];//用户id
		$pwd     =  mt_rand(100000, 999999);//获取随机的密码
		$data    =  array('passwd' => md5($pwd));
		$where   =  array('uid' => $uid);//条件
		$result  =  $this->M_common->update_data2('57sy_common_user', $data, $where);//更新数据
		if ($result['affect_num'] > 0) {//将密码通过邮件发送到传过来的邮箱中
			$slname  =  $this->getSLName($email);//获取带有隐藏（*）的账号
			$msg     =  '用户：'.$slname.'，您好，'.$config["site_fullname"].'重置后的密码为：'.$pwd.'，请您及时登录系统进行修改密码(首次接收本单位邮件有可能存放在垃圾箱中注意查收并标为可信邮箱地址)';
			//发送邮件
			sendmail_help($email, "邮箱找回密码-中山市电子商务信息管理系统",$msg);
			//更新用户表的editpwd字段
			$data    =  array('editpwd' => '1');
			$where   =  array('uid' => $uid);//条件
			$result  =  $this->M_common->update_data2('57sy_common_user', $data, $where);//更新数据
			// showmessage("新的密码已经发到您填写的邮箱中，请您及时登录系统修改密码","home/login",3,1);
			$this->parent_showmessage(
					1
					,"<span style='font-size:14px;'>新的密码已经发到您填写的邮箱中，请您及时登录系统修改密码，<br/>注意：首次接收本单位邮件有可能存放在垃圾箱中，查收时可以标为可信邮箱地址。</span>",
					site_url("home/login"),
					20,
					'showmessage_logout');
			exit();
		} else {
			// showmessage("找回失败，请刷新重试","Swj_recover_password/index",3,0);
			$this->parent_showmessage(
					0
					,"找回失败，请刷新重试",
					site_url("Swj_recover_password/index"),
					3,
					'showmessage_logout');
			exit();
		}
	}






	//第一步手机验证身份
	function step1(){	
		$get = $this->input->get();			
		$ls = !isset($get["ls"])?site_url("home/index"):$get["ls"];
		$data["ls"] = $ls;
		$this->load->view(__TEMPLET_FOLDER__."/recoverpassword/step1",$data);
	}
	//第二步重置密码，如果发送过来的信息正确则显示重置密码界面
	function step2(){	
		$post = $this->input->post();
		if (!$this->chktelcode_ht($post)) {//手机验证码验证失效
			// showmessage("验证号不存在或已失效","Swj_recover_password/step1",3,0);
			$this->parent_showmessage(
					0
					,"验证号不存在或已失效",
					site_url("Swj_recover_password/step1"),
					3,
					'showmessage_logout');
			exit;
		}
		if (!$this->chkuserphone_ht($post)) {//用户名跟手机号不一致
			// showmessage("用户名跟手机号不一致","Swj_recover_password/step1",3,0);
			$this->parent_showmessage(
					0
					,"用户名跟手机号不一致",
					site_url("Swj_recover_password/step1"),
					3,
					'showmessage_logout');
			exit;
		}		
		$ls = !isset($post["ls"])?site_url("home/index"):$post["ls"];
		$post["ls"] = $ls;
		$this->load->view(__TEMPLET_FOLDER__."/recoverpassword/step2",$post);
	}
	//进行密码重置,首先判断传送过来的验证码是否失效，失效提示验证码已经失效
	//否则进行密码重置,重置成功后将验证码设置为已使用
	function step2_do() {
		$post = $this->input->post();
		// var_dump($post);exit;
		$tel  = $post['mysql_tel'];//手机号
		$code = $post['telcode'];//验证码
		$pwd  = $post['pwd'];//重置后的密码
		$mysql_username = $post['mysql_username'];//用户名
		if (!$this->chktelcode_ht($post, 360)) {//验证码过期或者不存在
			// showmessage("验证号不存在或已失效","Swj_recover_password/step1",3,0);
			$this->parent_showmessage(
					0
					,"验证号不存在或已失效",
					site_url("Swj_recover_password/step1"),
					3,
					'showmessage_logout');
			exit;
		}
		$data    =  array('passwd' => md5($pwd));
		$where   =  array('username' => $mysql_username, 'tel' => $tel);//条件
		$result  =  $this->M_common->update_data2('57sy_common_user', $data, $where);//更新数据
		if ($result['affect_num'] > 0) {
			//手机验证码状态改为已使用
			$data   =  array('isuse' => 2);
			$where  =  array('tel' => $tel, 'code' => $code);
			$this->M_common->update_data2('swj_reg_tel_code', $data, $where);//更新数据
			// showmessage("修改密码成功","home/login",3,1);
			$this->parent_showmessage(
					1
					,"验证号不存在或已失效",
					site_url("home/login"),
					3,
					'showmessage_logout');
		} else {
			// showmessage("修改密码失败","home/login",3,0);
			$this->parent_showmessage(
					1
					,"修改密码失败",
					site_url("home/login"),
					3,
					'showmessage_logout');
		}
	}
	//发送短信验证码到用户手机
	function send_tel_code(){
		$post = $this->input->post();
		if(!empty($post["tel"])){
			$tel = trim($post["tel"]);
			$config = $this->parent_sysconfig();
			$code = rand(100000,999999);			
			//检查是否已使用，一小时内未使用，就用回上一个
			$isrep = false;
			if($this->tel_code->count("tel='$tel' and isuse='1' and (UNIX_TIMESTAMP()-createtime)<120")>0){
				$model = $this->tel_code->GetModelFromTel($tel);
				$isrep = true;
			}
			$model["tel"] = $tel;
			if(!$isrep){
				$model["code"] = $code;
			}
			$model["createtime"] = time();
			$model["isuse"] = '1';
			$model["sendtype"] = '1';
			$msg = $config["site_fullname"]."手机验证码：".$model["code"].",请及时填写。";	
			if($isrep){
				$this->tel_code->update($model);				
			}
			else{
				$this->tel_code->add($model);
			}			
			$this->sms->send_message( $msg, "忘记密码验证","-1","-1", 
			array(array("id"=>"0","type"=>"0","phone"=>$tel))
			, "");
		}
	}

	//zx99生成验证码
	function code(){
		$this->load->library("code",array(
				'width'=>80,
				'height'=>35,
				'fontSize'=>20,
				'font'=>__ROOT__."/".APPPATH."/fonts/font.ttf"
		));
		$this->code->show();
		//echo $this->code->getCode();
	}
	//zx99 检查验证码
	function chkcode(){
		$get=$this->input->get();
		$code = $get["param"];
		$isok = false;
		@session_start();
		if(strtolower($_SESSION['code'])==strtolower($code)){
			$isok = true;
		}
		else{
			$isok = false;
		}
		if(!$isok){
			$data = array("result"=>"0","msg"=>"验证码不正确");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();		
	}
	
	//检查手机号是否存在
	function chktel(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select uid from 57sy_common_user where isdel=0 and tel='$title'".($id>0?" and uid<>$id":""));
		if(count($model)<=0){
			$data = array("result"=>"0","msg"=>"手机号码不存在");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}
	//检查手机号是否存在(后台)
	private function chktel_ht($phone){
		$model = $this->M_common->query_one("select uid,username from 57sy_common_user where isdel=0 and tel='$phone'");
		if(count($model)<=0){
			return array();
		}
		else{
			return $model;
		}
	}
	//检查用户名是否存在
	function chkusername(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select uid from 57sy_common_user where isdel=0 and username='$title'".($id>0?" and uid<>$id":""));
		if(count($model)<=0){
			$data = array("result"=>"0","msg"=>"用户名不存在");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}	
	//验证手机验证码
	function chktelcode(){
		$post=$this->input->post();
		$phone = $post["tel"];//手机号码
		$telcode = $post["telcode"];//手机验证码
				
		if($telcode=="" || $phone==""){
			$data = array("result"=>"0","msg"=>"验证号不存在或已失效");
			echo json_encode($data);
			exit();			
		}
		$model = $this->M_common->query_one("select * from swj_reg_tel_code where isuse='1' and tel='$phone' and code='".$telcode."' and (UNIX_TIMESTAMP()-createtime)<120");
		if(count($model)==0){
			$data = array("result"=>"0","msg"=>"验证号不存在或已失效");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}	
	//检查用户名跟手机号是否一致
	function chkuserphone(){
		$post=$this->input->post();
		$phone = $post["tel"];//手机号
		$username = $post["username"];//账号
		$model = $this->M_common->query_one("select uid from 57sy_common_user where isdel=0 and username='$username' and tel='$phone'");
		if(count($model)<=0){
			$data = array("result"=>"0","msg"=>"用户名跟手机号不一致");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}	
		//验证手机验证码(后台)$post:存储手机验证码跟手机号，$gqtime:手机验证码过期时间
	//,验证成功返回true，失败false
	function chktelcode_ht($post, $gqtime = 300){
		$code = $post["telcode"];//手机验证码
		$tel = $post["mysql_tel"];//手机号
				
		if($tel=="" || $code==""){
			return false;	
		}
		$model = $this->M_common->query_one("select * from swj_reg_tel_code where isuse='1' and tel='$tel' and code='".$code."' and (UNIX_TIMESTAMP()-createtime)<$gqtime");
		if(count($model)==0){
			return false;
		}
		return true;
	}	
	//检查用户名跟手机号是否一致（后台）,验证成功返回true，失败false
	function chkuserphone_ht($post){
		$phone = $post["mysql_tel"];//手机号
		$username = $post["mysql_username"];//账号
		$model = $this->M_common->query_one("select uid from 57sy_common_user where isdel=0 and username='$username' and tel='$phone'");
		if(count($model)<=0){
			return false;
		}
		return true;
	}		
}