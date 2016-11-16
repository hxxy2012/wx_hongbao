<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
@ini_set( "display_errors" ,"On");
class Forget extends MY_Controller{	
	var $email_config = array();
	var $cust_email = "www_car_cc@126.com";
	function Forget(){
		parent::__construct();
		$this->load->library('session');
		$this->load->model('M_user','user');
		$this->load->model('M_zx_forget','fg');
		$this->load->library('email');
		
		$this->email_config['protocol'] = 'smtp';
		//$this->email_config['customer_email'] = 'www_car_cc@126.com';
		$this->email_config['smtp_host'] = 'smtp.126.com';
		$this->email_config['smtp_user'] = 'www_car_cc';
		$this->email_config['smtp_pass'] = '19820830';
		$this->email_config['smtp_port'] = '25';
		$this->email_config['charset'] = 'utf-8';
		$this->email_config['wordwrap'] = TRUE;
		$this->email_config['mailtype'] = 'html';

		
		

		
		
	}
	
	
	


	function index(){
		$config = $this->parent_sysconfig();
		$data = array();
		$data["config"] = $config;
		
		


		
		
		



		
		$this->load->view(__TEMPLET_FOLDER__."/forget",$data);
	}
	
	
	function index_wx(){
		
		$config = $this->parent_sysconfig();
		$data = array();
		$data["config"] = $config;
		
		
		$data["pagetitle"] = "忘记密码";
		$data["backurl"] = site_url("home_wx/login");
		
		
		
		
		
		
		
		
		$this->load->view(__TEMPLET_FOLDER_WX__."/home/forget",$data);		
	}
	
	//发送电邮
	function sendmail_wx(){
		$post = $this->input->post();
		$email = $post["email"];
		if($email==""){
			$this->parent_showmessage_wx(
					0
					,"没有邮箱",
					site_url("forget/index_wx"),
					999);
		}
		else{
			//查找用户表，如果存在邮箱，生成GUID，发送电邮，
			$user = $this->user->GetUserList("email='$email' and isdel=0");
			if(count($user)>0){
				$user = $user[0];
				//判断有效期内是否有申请
				if($this->fg->count("email='$email' and youxiaoqi>".time()." and isuse='0'")==0){
					$model = array(
							"email"=>$user["email"],
							"userid"=>$user["uid"],
							"guid"=>md5(create_guid()),
							"youxiaoqi"=>(time()+60*60*24),
							"create_date"=>date("Y-m-d H:i:s"),
							"isuse"=>"0"
					);
						
					//发电邮
					$this->email->initialize($this->email_config);
					$this->email->from($this->cust_email, '管理员');
					$this->email->to($email);
					$this->email->subject('找回密码-直销之家');
					$body = "点击以下链接修改密码24小时有效<br/>";
					$body.= site_url("forget/edit")."?guid=".$model["guid"];
					$body.="<b><br/>请及时修改密码</b>";
					$this->email->message($body);
					$this->email->set_alt_message('This is the alternative message');
					//$this->email->attach('application\controllers\1.jpeg');           //相对于index.php的路径
					//改用这个函数
					sendmail_help($email, "找回密码-直销之家",$body);
					$this->fg->add($model);
					$this->parent_showmessage(
							1
							,"发送成功，请去邮箱查收。",
							site_url("forget/index_wx"),
							999);
					/*暂时停用
					 if ( ! $this->email->send())
					 {
	
					 $this->parent_showmessage(
					 0
					 ,"发送失败。",
					 site_url("forget/index"),
					 3);
					 }
					 else{
					 $this->fg->add($model);
					 $this->parent_showmessage(
					 1
					 ,"发送成功，请去邮箱查收。",
					 site_url("forget/index"),
					 999);
	
					 }
					*/
						
				}
				else{
					$this->parent_showmessage_wx(
							0
							,"已发送过，24小时内不能再发送",
							site_url("forget/index_wx"),
							999);
				}
			}
			else{
				$this->parent_showmessage_wx(
						0
						,"找不到会员",
						site_url("forget/index_wx"),
						999);
			}
		}
	}	
	
	

	//发送电邮
	function sendmail(){
		$post = $this->input->post();
		$email = $post["email"];
		if($email==""){		
			$this->parent_showmessage(
					0
					,"没有邮箱",
					site_url("forget/index"),
					999);
		}
		else{
			//查找用户表，如果存在邮箱，生成GUID，发送电邮，
			$user = $this->user->GetUserList("email='$email' and isdel=0");
			if(count($user)>0){
				$user = $user[0];
				//判断有效期内是否有申请
				if($this->fg->count("email='$email' and youxiaoqi>".time()." and isuse='0'")==0){
					$model = array(
						"email"=>$user["email"],
						"userid"=>$user["uid"],
						"guid"=>md5(create_guid()),
						"youxiaoqi"=>(time()+60*60*24),
						"create_date"=>date("Y-m-d H:i:s"),
						"isuse"=>"0"
					);
									
					//发电邮 
					$this->email->initialize($this->email_config);     
			        $this->email->from($this->cust_email, '管理员');  
			        $this->email->to($email);  
			        $this->email->subject('找回密码-直销之家');
			        $body = "点击以下链接修改密码24小时有效<br/>";
			        $body.= site_url("forget/edit")."?guid=".$model["guid"];
			        $body.="<b><br/>请及时修改密码</b>";  
			        $this->email->message($body);
			        $this->email->set_alt_message('This is the alternative message');
			        //$this->email->attach('application\controllers\1.jpeg');           //相对于index.php的路径
			        //改用这个函数
			        sendmail_help($email, "找回密码-直销之家",$body);
			        $this->fg->add($model);
			        $this->parent_showmessage(
			        		1
			        		,"发送成功，请去邮箱查收。",
			        		site_url("forget/index"),
			        		999);			        
			        /*暂时停用
			        if ( ! $this->email->send())
			        {
			        	
			        	$this->parent_showmessage(
			        			0
			        			,"发送失败。",
			        			site_url("forget/index"),
			        			3);			        	
			        }			     
			        else{
			        	$this->fg->add($model);
			        	$this->parent_showmessage(
			        			1
			        			,"发送成功，请去邮箱查收。",
			        			site_url("forget/index"),
			        			999);			        	
			        	
			        } 
			        */   
  					
				}
				else{
					$this->parent_showmessage(
							0
							,"已发送过，24小时内不能再发送",
							site_url("forget/index"),
							999);					
				}
			}
			else{				
				$this->parent_showmessage(
						0
						,"找不到会员",
						site_url("forget/index"),
						999);										
			}
		}
	}
    //修改密码页
	function edit(){
		//清除所有SESSION
		$this->session->sess_destroy();
		$post = $this->input->post();
		
		
		if(is_array($post)){
			//保存	
			$pwd = $post["pwd"];
			$guid = $post["guid"];
			if($pwd=="" && $guid==""){
				$this->parent_showmessage(
						0
						,"资料为空",
						site_url("forget/index"),
						999);				
			}
			else{
				$forget = $this->fg->GetModel($guid);
				if($forget!=""){
					$pwd = md5($pwd);
					$model = $this->user->GetModel($forget["userid"]);
					$model["passwd"]=$pwd;
					$this->user->update($model);
					$forget["isuse"]=1;
					$this->fg->update($forget);
					$this->parent_showmessage(
							1
							,"修改密码成功，请登录。",
							site_url("home/login"),
							999);					
				}
				else{
					$this->parent_showmessage(
							0
							,"找不到会员",
							site_url("forget/index"),
							999);
				}
			}
		}
		else{
			$config = $this->parent_sysconfig();			
			
			$get = $this->input->get();
			$guid = $get["guid"];
			$model = $this->fg->GetModel($guid);
			$data = "";
			$data["config"] = $config;			
			if($model!=""){
				if($model["isuse"]=="1"){
					$this->parent_showmessage(
							0
							,"这条链接已使用，不能再修改",
							site_url("forget/index"),
							999);				
				}
				else{					
					$user = $this->user->GetModel($model["userid"]);
					$data["username"] = $user["username"];
					$data["guid"] = $model["guid"];
					$this->load->view(__TEMPLET_FOLDER__."/forget_edit",$data);
				}
			}
			else{
				$this->parent_showmessage(
						0
						,"没有记录",
						site_url("forget/index"),
						999);			
			}
		}
	}
	
}