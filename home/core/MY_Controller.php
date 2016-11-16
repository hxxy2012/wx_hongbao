<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * 让ci继承自己的类库 
 * ######################################
 * 这个类里面写权限代码
 *###################################
 */

/**
 * 走出去添加
 * Class MY_Controller
 * @property CI_Router router
 */
class MY_Controller extends CI_Controller{
	var $webconfig  = "";
	function MY_Controller(){
		parent::__construct() ;	
		$this->load->model('M_57sy_common_sysconfig','sysconfig');	
		$this->load->model('M_website_category','webcate');//栏目模型
		$this->load->model('M_common_category_data','cd');
		$this->load->model('M_common');
		$this->load->model('M_zx_user_session','sessionopt');			
		$this->load->library('session');
		$this->load->helper('cookie');
		$this->load->model('M_57sy_extra_ad','ad');//广告模型
		$this->webconfig = $this->parent_sysconfig();
		$timeout = 60 * 60;//15分钟超时
		$this->check_login_timeout($timeout);//检查是否超时登录
        $this->parent_chksession();//检查登录
        $this->check_permissions();//检查权限
	}
	//判断是否超时登录，通过ci_session session找到数据库的最后活动时间，判断是否超时
	function check_login_timeout($timeout) {		        
		$nowTime =  time();//当前时间
		$sid   	 =  $this->input->cookie("zx_sid");
		$sid 	 =  !empty($sid)?$sid:$this->input->get_post("session_id");//为了兼容flash上传（上传后cookie获取不到）
		// echo $sid;
		$domain= 'http://'.$_SERVER['SERVER_NAME'].($_SERVER["SERVER_PORT"]!=80?(':'.$_SERVER["SERVER_PORT"]):"");//域名加端口
		$url   = $domain.'/index.php/login/index';//跳转到登录页面跳转到前台
		// echo $url;exit;
		if (!empty($sid)) {//存在sid，sessionid进行超时登录判断
			$sql   =  "select * from zx_user_session where session_id='$sid'";
			$data  =  $this->M_common->query_one($sql);
			if (count($data)>0&&$data['last_activity1']&&
				(($nowTime - $timeout) > $data['last_activity1'])) {//如果存在最后活动时间,判断是否超时,超时
				$this->parent_delsession($sid);//删除session
				echo "<script>top.location.href='".$url."?overtime=1&rnd=1';</script>";exit;
				// showmessage("超时登录","$url",3,0,'',1);exit;				
			} else {//不超时更新last_activity1字段
				$model = array('last_activity1' => $nowTime);
				$where = array('session_id' => $sid);
				$this->M_common->update_data2('zx_user_session', $model, $where);
			}
		} else {//超时登录
			// $this->parent_delsession($sid);//删除session
			echo "<script>top.location.href='".$url."?overtime=1&rnd=1';</script>";exit;
			// showmessage("超时登录","$url",3,0,'',1);exit;
		}
        
		
	}
	function parent_sysconfig(){
		$list = $this->sysconfig->getlist();
		$arr = array();
		foreach($list as $v ){
			$arr[$v["varname"]]=$v["value"];
		}
		return $arr;
	}
	//底部政府网站部门以及友情链接等
	function parent_getfinfo() {
		$arr = array();
		$arr["zfwz"] = $this->ad->getlist("ad_type='23' and status='1'","id asc");
        $arr["zgbm"] = $this->ad->getlist("ad_type='24' and status='1'","id asc");
        $arr["link1"] = $this->ad->getlist("ad_type='4' and status='1'","id asc");
        $arr["link2"] = $this->ad->getlist("ad_type='22' and status='1'","id asc");
        //获取菜单信息
        $catOneInfo = $this->webcate->GetModelList();//获取一级菜单
        foreach ($catOneInfo as $key => $value) {//循环一级菜单获取其下级菜单
        	$catOneInfo[$key]['nexcate'] = $this->webcate->GetSubList($value['id']);
        }
        $arr['menu'] = $catOneInfo;
        return $arr;
	}	
	function parent_getsession($cookie_val=""){
		//$array = $this->session->all_userdata();
		//$get = $this->input->get();
		//if(!empty($get["session"])){
		//	$sid = 	$get["session"];		
		//}
		//else{	
		$sid = $this->input->cookie("zx_sid");
		//}
		//$array = unserialize($array);
		//die("sid=".$sid);

		if($sid=="" && $cookie_val==""){			
			return "";
		}
		else{
			if($cookie_val!=""){
				$sid = $cookie_val;
			}
			$array = $this->sessionopt->GetModel($sid);

			//print_r($array);
			//die("aaa");
			if(!empty($array["user_data"])){
				$array = unserialize($array["user_data"]);
				$array["session_id"] = $sid;
			}
			else{
				$array = "";
				//清空cookie
				//$this->input->
				delete_cookie("zx_sid");
			}
		}
		return is_array($array) && count($array)>0?$array:"";
	}
	
	function parent_delsession($sid){
		$this->sessionopt->del("session_id='".$sid."'");
	}
	
	function parent_setsession($usermodel){
		if($usermodel["usertype"]>0){
			$usertype = $this->cd->GetModel($usermodel["usertype"]);
			$usertype = $usertype["name"];
		}
		else{
			$usertype = "";
		}
		$array = array(
				"userid"=>$usermodel["uid"],				
				"username"=>$usermodel["username"],
				"tel"=>$usermodel["tel"],				
				"last_login"=>$usermodel["lastlogin"],
				"usertype"=>$usermodel["usertype"],
				"usertype_name"=>$usertype,
				"realname"=>$usermodel["realname"],
				"yuming"=>$usermodel["yuming"],
				"email"=>$usermodel["email"],
				"editpwd"=>$usermodel["editpwd"],
                "checkstatus"=>$usermodel["checkstatus"],//审核状态0:未审,1:已审,99:审核不通过
				"wxid"=>empty($usermodel["openid"])?"":$usermodel["openid"]
		);
		
		$this->session->set_userdata($array);
		$sess = $this->session->all_userdata();
		//保存COOKE用于单点登录
		$cookie = array(
				'name'   => 'sid',
				'value'  => $sess["session_id"],
				'expire' => time()+(60*60*5),
				'domain' => ''.$this->webconfig["cfg_cookie_domain"],
				'path'   => '/',
				'prefix' => 'zx_',
				'secure' => FALSE,
				'httponly'=>TRUE
		);		
		$this->input->set_cookie($cookie);
		return $array;
	}	

	//是否成功，提示信息，返回链接(空不返回)，多少秒返回
	function parent_showmessage($isok,$message,$url,$miao,$view='showmessage'){
	    /*
		$data = array(
			"isok"=>$isok,
			"title"=>($isok==1?"操作成功":"操作失败"),
			"message"=>$message,
			"url"=>$url,
			"miao"=>$miao,
			"istop"=>($view=="showmessage_logout")				
		);				
		$this->load->view(__TEMPLET_FOLDER__."/".$view,$data);
	    */
        $gourl = "isok=".$isok."&msg=".$message."&url=".urlencode($url)."&view=".$view."&miao=".$miao;
        $gourl = site_url("showmsg/msg")."?".$gourl;
        header("location:".$gourl);
	}
	
	//是否成功，提示信息，返回链接(空不返回)，多少秒返回
	function parent_showmessage_wx($isok,$message,$url,$miao){
		
		$data = array(
				"isok"=>$isok,				
				"pagetitle"=>($isok==1?"操作成功":"操作失败"),
				"message"=>$message,
				"url"=>$url,
				"backurl"=>$url,
				"miao"=>$miao,
				"config"=>$this->parent_sysconfig()		
		);		
		$this->load->view(__TEMPLET_FOLDER_WX__."/showmessage",$data);			
	}	
	
	

	//成功的就返回会话
	function parent_chklogin(){		
		$sess = $this->parent_getsession();
		//服务号用		
		if(empty($sess["userid"])){
			$this->load->library('weixin');
			$url = $this->weixin->getwxurl(site_url("home/login"));			
			$ch = curl_init($url);
			//设置选项，包括URL
			//curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			//curl_setopt($ch, CURLOPT_HEADER, 0);
			//执行并获取HTML文档内容
			$output = curl_exec($ch);
			//释放curl句柄
			curl_close($ch);
			$sess = $this->parent_getsession();
			return $sess;
		}
		else{
			return $sess;
		}
		
		//订阅号用
		//return $sess;
		//订阅号结束
		
	}
	
	//用于返回表单提交的MYSQL开头的变量，用于快速写入数据库
	function getmysqlmodel(){
		$post  = $this->input->post();
		$model = array();
		if(is_array($post)){
			foreach($post as $k=>$v){
				if(stripos($k,"mysql_")!==false){
					$model[substr($k,6,strlen($k)-6)]=$v;
				}
			}
		}
		return $model;
	}
	
	function parent_chksession(){
		$sess = $this->parent_getsession();
		if(!isset($sess["userid"])){
			//是否成功，提示信息，返回链接(空不返回)，多少秒返回
			$this->parent_showmessage(0,"超时登录",site_url("login/index"),3,'showmessage_logout');
		}
	}

    /**
     * [走出去]审核不通过时检查权限，只允许修改资料
     */
	function check_permissions(){
        $sess = $this->parent_getsession();
        //审核不通过
        if ($sess['checkstatus']==99) {

            $method = $this->router->fetch_method();
            $controller= $this->router->class;

            $allow_controllers = array("admin","zcq_user");
            //$allow_methods = array("index","""editinfo");

            if (!in_array($controller,$allow_controllers)) {
                //控制器不在访问列表
                $this->parent_showmessage(0, "无法使用功能!<br>用户当前资料状态为不通过，请重新修改资料！", site_url("adminx/zcq_user/editinfo"), 10, 'showmessage_logout');
            }else{

            }
        }
    }
}



//未登录时使用
class MY_ControllerLogout extends CI_Controller{
	var $webconfig  = "";
	function MY_ControllerLogout(){
		parent::__construct() ;	
		$this->load->model('M_57sy_common_sysconfig','sysconfig');
		$this->load->model('M_website_category','webcate');//栏目模型	
		$this->load->model('M_common_category_data','cd');
		$this->load->model('M_zx_user_session','sessionopt');
		$this->load->model('M_57sy_extra_ad','ad');//广告模型			
		$this->load->library('session');
		$this->load->helper('cookie');
		$this->webconfig = $this->parent_sysconfig();
		
	}
	
	function parent_sysconfig(){
		$list = $this->sysconfig->getlist();
		$arr = array();
		foreach($list as $v ){
			$arr[$v["varname"]]=$v["value"];
		}
		return $arr;
	}
	//底部政府网站部门以及友情链接等
	function parent_getfinfo() {
		$arr = array();
        $sql_common = " status='1' and  ( end_date > unix_timestamp(now() ) or end_date=0 ) ";
        //政府网站
		$arr["zfwz"] = $this->ad->getlist("ad_type='23' and {$sql_common}","id asc");
        //主管部门
        $arr["zgbm"] = $this->ad->getlist("ad_type='24' and {$sql_common}","id asc");
        //友情链接1
        $arr["link1"] = $this->ad->getlist("ad_type='4' and {$sql_common}","id asc");
        //友情链接2
        $arr["link2"] = $this->ad->getlist("ad_type='22' and {$sql_common}","id asc");
        //获取菜单信息
        $catOneInfo = $this->webcate->GetModelList();//获取一级菜单
        foreach ($catOneInfo as $key => $value) {//循环一级菜单获取其下级菜单
        	$catOneInfo[$key]['nexcate'] = $this->webcate->GetSubList($value['id']);
        }
        $arr['menu'] = $catOneInfo;
        return $arr;
	}	
	//获取登录跟注册的左边菜单以及菜单为最新动态
	function parent_getlrmenu() {

		$model = array();
		$pid = '38';//父级菜单为最新动态
		$model = $this->webcate->GetModel($pid);
		$model['nexcate'] = $this->webcate->GetSubList($pid);//获取其下级菜单
		return $model;
	}
	function parent_getsession(){
		//$array = $this->session->all_userdata();
		//$get = $this->input->get();
		//if(!empty($get["session"])){
		//	$sid = 	$get["session"];		
		//}
		//else{
		$sid = $this->input->cookie("zx_sid");
		//}
		//$array = unserialize($array);
		//die("sid=".$sid);
		
		if($sid==""){			
			return "";
		}
		else{
			$array = $this->sessionopt->GetModel($sid);
			//print_r($array);
			//die("aaa");
			if(!empty($array["user_data"])){
				$array = unserialize($array["user_data"]);
				$array["session_id"] = $sid;
			}
			else{
				$array = "";
				//清空cookie
				//$this->input->
				delete_cookie("zx_sid");
			}
		}
		return is_array($array) && count($array)>0?$array:"";
	}
	
	function parent_delsession($sid){
		$this->sessionopt->del("session_id='".$sid."'");
	}
	
	function parent_setsession($usermodel){
		if($usermodel["usertype"]>0){
			$usertype = $this->cd->GetModel($usermodel["usertype"]);
			$usertype = $usertype["name"];
		}
		else{
			$usertype = "";
		}
		$array = array(
				"userid"=>$usermodel["uid"],				
				"username"=>$usermodel["username"],
				"tel"=>$usermodel["tel"],				
				"last_login"=>$usermodel["lastlogin"],
				"usertype"=>$usermodel["usertype"],
				"usertype_name"=>$usertype,
				"realname"=>$usermodel["realname"],
				"yuming"=>$usermodel["yuming"],
				"email"=>$usermodel["email"],
				"editpwd"=>$usermodel["editpwd"],
            "checkstatus"=>$usermodel["checkstatus"],//审核状态0:未审,1:已审,99:审核不通过
            "wxid"=>empty($usermodel["openid"])?"":$usermodel["openid"]
		);
		
		$this->session->set_userdata($array);
		$sess = $this->session->all_userdata();
		//保存COOKE用于单点登录
		$cookie = array(
				'name'   => 'sid',
				'value'  => $sess["session_id"],
				'expire' => time()+(60*60*5),
				'domain' => '.'.$this->webconfig["cfg_cookie_domain"],
				'path'   => '/',
				'prefix' => 'zx_',
				'secure' => FALSE,
				'httponly'=>TRUE
		);		
		$this->input->set_cookie($cookie);
		return $array;
	}	

	//是否成功，提示信息，返回链接(空不返回)，多少秒返回
	function parent_showmessage($isok,$message,$url,$miao,$view='showmessage'){
	    /*
		$data = array(
			"isok"=>$isok,
			"title"=>($isok==1?"操作成功":"操作失败"),
			"message"=>$message,
			"url"=>$url,
			"miao"=>$miao,
            "sess"=>$this->parent_getsession(),
			"istop"=>($view=="showmessage_logout")					
		);
		$this->load->view(__TEMPLET_FOLDER__."/".$view,$data);
	    */
        $gourl = "isok=".$isok."&msg=".$message."&url=".urlencode($url)."&view=".$view."&miao=".$miao;
        $gourl = site_url("showmsg/msg")."?".$gourl;
        header("location:".$gourl);
	}
	
	//是否成功，提示信息，返回链接(空不返回)，多少秒返回
	function parent_showmessage_wx($isok,$message,$url,$miao){
		
		$data = array(
				"isok"=>$isok,				
				"pagetitle"=>($isok==1?"操作成功":"操作失败"),
				"message"=>$message,
				"url"=>$url,
				"backurl"=>$url,
				"miao"=>$miao,
				"config"=>$this->parent_sysconfig()		
		);		
		$this->load->view(__TEMPLET_FOLDER_WX__."/showmessage",$data);			
	}	
	
	

	//成功的就返回会话
	function parent_chklogin(){		
		$sess = $this->parent_getsession();
		//服务号用		
		if(empty($sess["userid"])){
			$this->load->library('weixin');
			$url = $this->weixin->getwxurl(site_url("home/login"));			
			$ch = curl_init($url);
			//设置选项，包括URL
			//curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			//curl_setopt($ch, CURLOPT_HEADER, 0);
			//执行并获取HTML文档内容
			$output = curl_exec($ch);
			//释放curl句柄
			curl_close($ch);
			$sess = $this->parent_getsession();
			return $sess;
		}
		else{
			return $sess;
		}
		
		//订阅号用
		//return $sess;
		//订阅号结束
		
	}
	
	//用于返回表单提交的MYSQL开头的变量，用于快速写入数据库
	function getmysqlmodel(){
		$post  = $this->input->post();
		$model = array();
		if(is_array($post)){
			foreach($post as $k=>$v){
				if(stripos($k,"mysql_")!==false){
					$model[substr($k,6,strlen($k)-6)]=$v;
				}
			}
		}
		return $model;
	}
	
	function parent_chksession(){
		$sess = $this->parent_getsession();
		if(!isset($sess["userid"])){
			//是否成功，提示信息，返回链接(空不返回)，多少秒返回
			$this->parent_showmessage(0,"超时登录",site_url("home/login"),3,'showmessage_logout');
		}
	}
	
}