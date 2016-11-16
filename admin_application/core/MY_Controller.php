<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * 让ci继承自己的类库 
 * ######################################
 * 这个类里面写权限代码
 *###################################
 */

class MY_Controller extends CI_Controller{
	public $username = '' ;//登录的用户名
	public $admin_id = '' ;
	public $group_name = '' ;//所在的群组
	public $role_id = '' ;
	function MY_Controller(){    
                                
		parent::__construct() ;                
		$this->load->library('session','sessionopt');
		$this->load->model('M_common');
		$this->load->model('M_57sy_common_sysconfig','sysconfig');	
		$this->load->model('M_admin_user_session','sessionopt');	
		$this->load->helper('cookie');
		
		//print_r($this->session->all_userdata());
		//$this->input->get("session");
		//echo print_r($this->input->get(),true);
		//$data = decode_data($this->input->get_post("cookie")); //获取cookie数据
		//$data = decode_data($this->input->cookie("admin_auth")); //获取cookie数据
		//echo "aaa=".print_r($data,true);
		//file_put_contents("e:aa.txt","aaaa=".print_r($data,true));
		//print_r($data);
		//die();
		//echo "aaa=".admin_id();
		//die();
		//print_r($data);
		//file_put_contents("e:aa.txt","fu=".print_r($this->input->get(),true));
		$session_id = $this->input->get_post("session_id");
		//file_put_contents("e:aa.txt","fgggu=".print_r($this->input->get(),true));
		$sess_id = empty($session_id)?"":$this->input->get_post("session_id");
		$data = $this->parent_getsession($sess_id);
		//file_put_contents("e:aa.txt","fu=".print_r($data,true));
		$this->check_is_login($data);
		$timeout = 15 * 60;//15分钟超时
		$this->check_login_timeout($timeout);//检查是否超时登录
		$isadmin = false ;//判断是不是超级管理员
		if(isset($data['isadmin']) && $data['isadmin']){	
			$isadmin = true ;	
		}	
		$this->role_id = $data["gid"];		
		if(!$isadmin){//普通的用户进行验证权限
			$this->permition() ;
		}		
	}
	
	//判断是否超时登录，通过swj_admin session找到数据库的最后活动时间，判断是否超时
	function check_login_timeout($timeout) {
		
		$nowTime =  time();//当前时间
		$sid   	 =  $this->input->cookie("swj_admin");
		$sid 	 =  !empty($sid)?$sid:$this->input->get_post("session_id");//为了兼容flash上传（上传后cookie获取不到）
		// echo $sid;
		$domain  =  'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"];//域名加端口
		$url     =  $domain.'/gl.php/login/index';//跳转到登录页面跳转到前台
		// echo $url;exit;
		if (!empty($sid)) {//存在sid，sessionid进行超时登录判断
			$sql   =  "select * from admin_user_session where session_id='$sid'";
			$data  =  $this->M_common->query_one($sql);
			
			if (count($data)>0&&$data['last_activity1']&&
				(($nowTime - $timeout) > $data['last_activity1'])) {//如果存在最后活动时间,判断是否超时,超时
				$this->parent_delsession($sid);//删除session
				echo "<script>top.location.href='".$url."?overtime=1&rnd=1';</script>";exit;
				// showmessage("超时登录","$url",3,0,'',1,"top");exit;			
			} else {//不超时更新last_activity1字段
				$model = array('last_activity1' => $nowTime);
				$where = array('session_id' => $sid);
				$this->M_common->update_data2('admin_user_session', $model, $where);
			}
		} else {//超时登录
			// $this->parent_delsession($sid);//删除session
			echo "<script>top.location.href='".$url."?overtime=1&rnd=1';</script>";exit;
			// showmessage("超时登录","$url",3,0,'',1,"top");exit;
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
	
	function parent_getsession($session_id = ""){
		//$array = $this->session->all_userdata();
		//$get = $this->input->get();
		//if(!empty($get["session"])){
		//	$sid = 	$get["session"];		
		//}
		//else{
		$sid = $this->input->cookie("swj_admin");
		//}
		//$array = unserialize($array);
		//die("sid=".$sid);
		
		if($sid=="" && $session_id==""){			
			return "";
		}
		else{
			if($session_id!=""){
				$sid = $session_id;	
			}
			$array = $this->sessionopt->GetModel($sid);
			//print_r($array);
			//die("aaa");
			if(!empty($array["user_data"])){
				$array = unserialize($array["user_data"]);
				$array["session_id"] = $sid;
				if(isset($array["group_name"])){
					$this->group_name = $array["group_name"];	
				}
			}
			else{
				$array = "";
				//清空cookie
				//$this->input->
				delete_cookie("swj_admin");
			}
		}
		return is_array($array) && count($array)>0?$array:"";
	}
	
	function parent_delsession($sid){
		$this->sessionopt->del("session_id='".$sid."'");
	}
	
	function parent_setsession($usermodel){
		
		$gid = intval($usermodel['gid']);
		$group_name = '' ;
		$sql_role = "SELECT rolename FROM 57sy_common_role where id = '{$gid}' limit 1 ";
		$role_info = $this->M_common->query_one($sql_role);		
		$group_name = ($usermodel['super_admin'] == 1 )?'超级管理员':(isset($role_info['rolename'])?$role_info['rolename']:'');		
		
		
		$array = array(
				"admin_id"=>$usermodel["id"],				
				"username"=>$usermodel["username"],
				"tel"=>$usermodel["tel"],				
				"email"=>$usermodel["email"],
				"isadmin"=>$usermodel["super_admin"]==1,
				"gid"=>$usermodel["gid"],
				"group_name"=>$group_name
		);
		
		$this->session->set_userdata($array);
		$sess = $this->session->all_userdata();
		//保存COOKE用于单点登录
		$cookie = array(
				'name'   => 'admin',
				'value'  => $sess["session_id"],
				'expire' => time()+(60*60*5),
				'domain' => '.'.$this->webconfig["cfg_cookie_domain"],
				'path'   => '/',
				'prefix' => 'swj_',
				'secure' => FALSE
		);		
		$this->input->set_cookie($cookie);
		return $array;
	}	
	
	
	
	//检查是否登录了 
	private function check_is_login($data = '' ){	
		if(isset($data['username'])){
			$this->username = $data['username'];
		}
		$this->table_ =table_pre("real_data"); //设置表前缀
		$this->admin_id = admin_id() ; //当前登陆的用户的uid
		$this->group_name = group_name() ;//所在的群组
		$this->role_id = role_id() ; 
		
		if(empty($this->username) || $this->username == "" || intval($this->admin_id) <= 0 ){
				if($this->input->get_post("showpage") != "" ){ //这个地方是为了判断 ，ajax请求，但是显示的是一个提示页面
					//show_error("you don't have permition to Access this page,please Contact  &nbsp;Email:{$this->config->item('web_admin_email')}",'403','forbidden');			
					echo "对不起登陆超时，或者你还没登陆";
					die();
				}			
				//如果没有登录
				if(isset($_GET['inajax']) || $this->is_ajax()){
					echo result_to_towf_new('',$this->config->item('no_permition'),"你的密码已经过期,重新登录",null);
					die();
				}
				//$this->load->library('session');				
				//file_put_contents("e:aa.txt","aaaa=".print_r($this->session->all_userdata(),true));
				//解决360极速模式及火孤批量上传时提示密码过期问题
				//die("aaa=".print_r($data));
				if(empty($data['admin_id'])){
					//showmessage("密码已经过期",'login/index',3,0);
					//echo site_url("login/index");
					//header("Location:".site_url("login/index"));
					die("<script>top.location.href='".site_url("login/index")."';</script>");
				}
				
			}		
	}
	//验证是否有访问的权限
	private function permition(){
		$last_permition = array();
		$permition =array();
		$permition_admin = array() ;
		$role_cache = $this->config->item('role_cache');

		if(file_exists($role_cache."/cache_role_{$this->role_id}.inc.php")){
			require_once  $role_cache."/cache_role_{$this->role_id}.inc.php" ;
			$permition = $role_array ;	
			
		}
		//print_r($role_cache."/cache_role_{$this->role_id}.inc.php" );
		//print_r($permition);
		//die();
		if(file_exists($role_cache."/cache_admin_{$this->admin_id}.inc.php")){		
			require_once $role_cache."/cache_admin_{$this->admin_id}.inc.php" ; 
			$permition_admin = $admin_perm_array ;	
	
		}

		$last_permition = array();
		if($permition && $permition_admin){
			
			$last_permition = array_merge_recursive($permition,$permition_admin); 
		}elseif(!$permition && $permition_admin){
			$last_permition = $permition_admin ;
			
		}elseif($permition && !$permition_admin){
			$last_permition = $permition ;
			
		}
		
		$no_need_perm = $this->config->item('no_need_perm') ;
		
		if($no_need_perm && $last_permition){
			$last_permition = array_merge_recursive($last_permition,$no_need_perm); ;
		}
		if($last_permition){
			$last_permition = array_unique($last_permition);
		}


		$url_array = $this->uri->segment_array() ;
		//print_r($url_array);
		$new_url = '';
		if(isset($url_array[1])){
			$new_url.=$url_array[1]."/";
		}
		if(isset($url_array[2])){
			$new_url.=$url_array[2]."/";
		}
		if(isset($url_array[3])){
			$new_url.=$url_array[3]."/";
		}
		if(isset($url_array[4])){
			$new_url.=$url_array[4]."/";
		}
		//判断当前的访问地址的最后一位是不是有/
		if(substr($new_url,-1) == "/"){
			$new_url = substr($new_url,0,-1);
		}
		$last_permition = $this->delete_str_($last_permition);
        $new_url = strtolower($new_url);
		foreach($last_permition as $k=>$v){
            $last_permition[$k] = strtolower($v);
        }
		//print_r($last_permition);
		//echo "<br/>".$new_url;
		
		if(!in_array($new_url, $last_permition)){
			if($this->input->get_post("showpage") != "" ){ //这个地方是为了判断 ，ajax请求，但是显示的是一个错误页面
				//show_error("you don't have permition to Access this page,please Contact  &nbsp;Email:{$this->config->item('web_admin_email')}",'403','forbidden');			
				echo "对不起没权限执行此操作，请联系管理员：{$this->config->item('web_admin_email')}";
				die();
			}			
			if(isset($_GET['inajax']) || $this->is_ajax()){
					echo result_to_towf_new('',$this->config->item('no_permition'),"你没有权限进行此操作，请联系管理员。",null);
			}else{
			    header('content-type:text/html;charset=utf-8');
				show_error("你无权限访问页面，请与管理员联系。",403,'forbidden');
			}
				die();
		}	
	}
	
	
	
	//验证是否有访问的权限，用于检查按钮权限
	//控制器，方法名，
	public function permition_for($control,$method){
		$data2 = $this->parent_getsession();//decode_data($this->input->get_post("cookie"));
		if(isset($data2['isadmin']) && $data2['isadmin']){
			return true;
		}
        $control = strtolower($control);
        $method = strtolower($method);

		$last_permition = array();
		$permition =array();
		$permition_admin = array() ;
		$role_cache = $this->config->item('role_cache');
	
		if(file_exists($role_cache."/cache_role_{$this->role_id}.inc.php")){
			$path = $role_cache."/cache_role_{$this->role_id}.inc.php";
			$path = str_replace("//","/",$path);
			require $path;
			//include '$path';
			//echo $path;
			$permition = $role_array ;
		}
		if(file_exists($role_cache."/cache_admin_{$this->admin_id}.inc.php")){
			require $role_cache."/cache_admin_{$this->admin_id}.inc.php" ;
			$permition_admin = $admin_perm_array ;
		}
		$last_permition = array();
		if($permition && $permition_admin){
			$last_permition = array_merge_recursive($permition,$permition_admin);
		}elseif(!$permition && $permition_admin){
			$last_permition = $permition_admin ;
		}elseif($permition && !$permition_admin){
			$last_permition = $permition ;
		}
	
		$no_need_perm = $this->config->item('no_need_perm') ;
	
		if($no_need_perm && $last_permition){
			$last_permition = array_merge_recursive($last_permition,$no_need_perm); ;
		}
		if($last_permition){
			$last_permition = array_unique($last_permition);
		}
		//$url_array = $this->uri->segment_array() ;
	    $url_array[1] = $control;
	    $url_array[2] = $method;
	    
		$new_url = '';
		if(isset($url_array[1])){
			$new_url.=$url_array[1]."/";
		}
		if(isset($url_array[2])){
			$new_url.=$url_array[2]."/";
		}
		if(isset($url_array[3])){
			$new_url.=$url_array[3]."/";
		}
		if(isset($url_array[4])){
			$new_url.=$url_array[4]."/";
		}
		//判断当前的访问地址的最后一位是不是有/
		if(substr($new_url,-1) == "/"){
			$new_url = substr($new_url,0,-1);
		}
		$new_url = strtolower($new_url);
		$last_permition = $this->delete_str_($last_permition);
		//print_r($last_permition);
		//echo "<br/>".$new_url;
        //转为小写，防止大小写区分
        foreach($last_permition as $k=>$v){
            $last_permition[$k] = strtolower($v);
        }

		if(!in_array($new_url, $last_permition)){
			if($this->input->get_post("showpage") != "" ){ //这个地方是为了判断 ，ajax请求，但是显示的是一个错误页面
				//show_error("you don't have permition to Access this page,please Contact  &nbsp;Email:{$this->config->item('web_admin_email')}",'403','forbidden');
				//echo "对不起没权限执行此操作，请联系管理员：{$this->config->item('web_admin_email')}";
				return false;
				//die();
			}
	
				//die();
			return false;
		}
		return true;
			}	
	
	
	
	private function is_ajax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true ;
		}else{
			echo false ;
		}
	}
	/*
	*遍历数组 ， 并且去掉每个值后面的/
	*@params $array 
	*@return array
	*/
	private function delete_str_($array = array() ){
		
		$data = array();
		if(is_array($array) && $array){
		/*
			for($j = 0 ;$j <count($array) ; $j++){
				$url = isset($array[$j])?$array[$j]:'' ;
				
				if(substr($url,-1) == '/'){				
					$url= substr($url,0,-1) ;
				}
				$data[] = $url ;
				//echo $array[$i]."<br/>";
			}
		*/	
			foreach($array as $v){
				$url = $v;
				if(substr($url,-1) == '/'){
					$url= substr($url,0,-1) ;
				}
				$data[] = $url ;
			}
		}
		
		return $data ;
	}
	//用于返回表单提交的MYSQL开头的变量，用于快速写入数据库
	function getmysqlmodel(){
		$post  = $this->input->post();
		$model = array();
		foreach($post as $k=>$v){
			if(stripos($k,"mysql_")!==false){
				$model[substr($k,6,strlen($k)-6)]=$v;
			}
		}
		return $model;	
	}
}

class MY_Controller_logout extends CI_Controller{
	public $username = '' ;//登录的用户名
	public $table_ ; //表的前缀
	public $admin_id = '' ;
	public $group_name = '' ;//所在的群组
	public $role_id = '' ;
	function MY_Controller_logout(){
		parent::__construct() ;
		$this->load->model('M_57sy_common_sysconfig','sysconfig');	
		$this->load->library('session','sessionopt');
		$this->load->model('M_common');		
		$this->webconfig = $this->parent_sysconfig();	
	}
	/*
	function parent_getsession($session_id=""){
		$data = decode_data($this->input->get_post("cookie")); //获取cookie数据
		return $data;
	}
	*/
	
	
	function parent_sysconfig(){
		$list = $this->sysconfig->getlist();
		$arr = array();
		foreach($list as $v ){
			$arr[$v["varname"]]=$v["value"];
		}
		return $arr;
	}	
	
	function parent_getsession(){
		//$array = $this->session->all_userdata();
		//$get = $this->input->get();
		//if(!empty($get["session"])){
		//	$sid = 	$get["session"];		
		//}
		//else{
		$sid = $this->input->cookie("swj_admin");
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
				delete_cookie("swj_admin");
			}
		}
		return is_array($array) && count($array)>0?$array:"";
	}
	
	function parent_delsession($sid){
		$this->sessionopt->del("session_id='".$sid."'");
	}
	
	function parent_setsession($usermodel){
		
		$gid = intval($usermodel['gid']);
		$group_name = '' ;
		$sql_role = "SELECT rolename FROM 57sy_common_role where id = '{$gid}' limit 1 ";
		$role_info = $this->M_common->query_one($sql_role);		
		$group_name = ($usermodel['super_admin'] == 1 )?'超级管理员':(isset($role_info['rolename'])?$role_info['rolename']:'');		
		
		
		$array = array(
				"admin_id"=>$usermodel["id"],				
				"username"=>$usermodel["username"],
				"tel"=>$usermodel["tel"],				
				"email"=>$usermodel["email"],
				"isadmin"=>$usermodel["super_admin"]==1,
				"gid"=>$usermodel["gid"],
                "website_category"=>$usermodel["website_category"],
				"group_name"=>$group_name
		);
		//file_put_contents("e:aa.txt",print_r($array,true));
		$this->session->set_userdata($array);
		$sess = $this->session->all_userdata();
		//保存COOKE用于单点登录
		$cookie = array(
				'name'   => 'admin',
				'value'  => $sess["session_id"],
				'expire' => time()+(60*60*5),
				'domain' => '.'.$this->webconfig["cfg_cookie_domain"],
				'path'   => '/',
				'prefix' => 'swj_',
				'secure' => FALSE
		);		
		$this->input->set_cookie($cookie);
		return $array;
	}	
	
	
	
	//检查是否登录了 
	private function check_is_login($data = '' ){	
		if(isset($data['username'])){
			$this->username = $data['username'];
		}
		$this->table_ =table_pre("real_data"); //设置表前缀
		$this->admin_id = admin_id() ; //当前登陆的用户的uid
		$this->group_name = group_name() ;//所在的群组
		$this->role_id = role_id() ; 
		
		if(empty($this->username) || $this->username == "" || intval($this->admin_id) <= 0 ){
				if($this->input->get_post("showpage") != "" ){ //这个地方是为了判断 ，ajax请求，但是显示的是一个提示页面
					//show_error("you don't have permition to Access this page,please Contact  &nbsp;Email:{$this->config->item('web_admin_email')}",'403','forbidden');			
					echo "对不起登陆超时，或者你还没登陆";
					die();
				}			
				//如果没有登录
				if(isset($_GET['inajax']) || $this->is_ajax()){
					echo result_to_towf_new('',$this->config->item('no_permition'),"你的密码已经过期,重新登录",null);
					die();
				}
				//$this->load->library('session');				
				//file_put_contents("e:aa.txt","aaaa=".print_r($this->session->all_userdata(),true));
				//解决360极速模式及火孤批量上传时提示密码过期问题
				//die("aaa=".print_r($data));
				if(empty($data['admin_id'])){
					//showmessage("密码已经过期",'login/index',3,0);
					//echo site_url("login/index");
					//header("Location:".site_url("login/index"));
					die("<script>top.location.href='".site_url("login/index")."';</script>");
				}
			}		
	}
	//验证是否有访问的权限
	private function permition(){
		$last_permition = array();
		$permition =array();
		$permition_admin = array() ;
		$role_cache = $this->config->item('role_cache');

		if(file_exists($role_cache."/cache_role_{$this->role_id}.inc.php")){
			require_once  $role_cache."/cache_role_{$this->role_id}.inc.php" ;
			$permition = $role_array ;	
			
		}
		
		if(file_exists($role_cache."/cache_admin_{$this->admin_id}.inc.php")){		
			require_once $role_cache."/cache_admin_{$this->admin_id}.inc.php" ; 
			$permition_admin = $admin_perm_array ;	
					
		}
		$last_permition = array();
		if($permition && $permition_admin){
			
			$last_permition = array_merge_recursive($permition,$permition_admin); 
		}elseif(!$permition && $permition_admin){
			$last_permition = $permition_admin ;
			
		}elseif($permition && !$permition_admin){
			$last_permition = $permition ;
			
		}
		
		$no_need_perm = $this->config->item('no_need_perm') ;
		
		if($no_need_perm && $last_permition){
			$last_permition = array_merge_recursive($last_permition,$no_need_perm); ;
		}
		if($last_permition){
			$last_permition = array_unique($last_permition);
		}
		
		$url_array = $this->uri->segment_array() ;
		//print_r($url_array);
		$new_url = '';
		if(isset($url_array[1])){
			$new_url.=$url_array[1]."/";
		}
		if(isset($url_array[2])){
			$new_url.=$url_array[2]."/";
		}
		if(isset($url_array[3])){
			$new_url.=$url_array[3]."/";
		}
		if(isset($url_array[4])){
			$new_url.=$url_array[4]."/";
		}
		//判断当前的访问地址的最后一位是不是有/
		if(substr($new_url,-1) == "/"){
			$new_url = substr($new_url,0,-1);
		}
		$last_permition = $this->delete_str_($last_permition);
		
		//print_r($last_permition);
		//echo "<br/>".$new_url;
		
		if(!in_array($new_url, $last_permition)){
			if($this->input->get_post("showpage") != "" ){ //这个地方是为了判断 ，ajax请求，但是显示的是一个错误页面
				//show_error("you don't have permition to Access this page,please Contact  &nbsp;Email:{$this->config->item('web_admin_email')}",'403','forbidden');			
				echo "对不起没权限执行此操作，请联系管理员：{$this->config->item('web_admin_email')}";
				die();
			}			
			if(isset($_GET['inajax']) || $this->is_ajax()){
					echo result_to_towf_new('',$this->config->item('no_permition'),"你没有权限进行此操作，请联系管理员。",null);
			}else{
                                header('content-type:text/html;charset=utf-8');
				show_error("你无权限访问页面，请与管理员联系。",403,'forbidden');
			}
				die();
		}	
	}
	
	
	
	//验证是否有访问的权限，用于检查按钮权限
	//控制器，方法名，
	public function permition_for($control,$method){
		//$data2 = decode_data($this->input->get_post("cookie"));
		$data2 = $this->parent_getsession();
		if(isset($data2['isadmin']) && $data2['isadmin']){
			return true;
		}		
		
		$last_permition = array();
		$permition =array();
		$permition_admin = array() ;
		$role_cache = $this->config->item('role_cache');
	
		if(file_exists($role_cache."/cache_role_{$this->role_id}.inc.php")){
			$path = $role_cache."/cache_role_{$this->role_id}.inc.php";
			$path = str_replace("//","/",$path);
			require $path;
			//include '$path';
			//echo $path;
			$permition = $role_array ;
		}
		if(file_exists($role_cache."/cache_admin_{$this->admin_id}.inc.php")){
			require $role_cache."/cache_admin_{$this->admin_id}.inc.php" ;
			$permition_admin = $admin_perm_array ;
		}
		$last_permition = array();
		if($permition && $permition_admin){
			$last_permition = array_merge_recursive($permition,$permition_admin);
		}elseif(!$permition && $permition_admin){
			$last_permition = $permition_admin ;
		}elseif($permition && !$permition_admin){
			$last_permition = $permition ;
		}
	
		$no_need_perm = $this->config->item('no_need_perm') ;
	
		if($no_need_perm && $last_permition){
			$last_permition = array_merge_recursive($last_permition,$no_need_perm); ;
		}
		if($last_permition){
			$last_permition = array_unique($last_permition);
		}
		//$url_array = $this->uri->segment_array() ;
	    $url_array[1] = $control;
	    $url_array[2] = $method;
	    
		$new_url = '';
		if(isset($url_array[1])){
			$new_url.=$url_array[1]."/";
		}
		if(isset($url_array[2])){
			$new_url.=$url_array[2]."/";
		}
		if(isset($url_array[3])){
			$new_url.=$url_array[3]."/";
		}
		if(isset($url_array[4])){
			$new_url.=$url_array[4]."/";
		}
		//判断当前的访问地址的最后一位是不是有/
		if(substr($new_url,-1) == "/"){
			$new_url = substr($new_url,0,-1);
		}
		$last_permition = $this->delete_str_($last_permition);
		//print_r($last_permition);
		//echo "<br/>".$new_url;
		if(!in_array($new_url, $last_permition)){
			if($this->input->get_post("showpage") != "" ){ //这个地方是为了判断 ，ajax请求，但是显示的是一个错误页面
				//show_error("you don't have permition to Access this page,please Contact  &nbsp;Email:{$this->config->item('web_admin_email')}",'403','forbidden');
				//echo "对不起没权限执行此操作，请联系管理员：{$this->config->item('web_admin_email')}";
				return false;
				//die();
			}
	
				//die();
			return false;
		}
		return true;
			}	
	
	
	
	private function is_ajax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true ;
		}else{
			echo false ;
		}
	}
	/*
	*遍历数组 ， 并且去掉每个值后面的/
	*@params $array 
	*@return array
	*/
	private function delete_str_($array = array() ){
		
		$data = array();
		if(is_array($array) && $array){
		/*
			for($j = 0 ;$j <count($array) ; $j++){
				$url = isset($array[$j])?$array[$j]:'' ;
				
				if(substr($url,-1) == '/'){				
					$url= substr($url,0,-1) ;
				}
				$data[] = $url ;
				//echo $array[$i]."<br/>";
			}
		*/	
			foreach($array as $v){
				$url = $v;
				if(substr($url,-1) == '/'){
					$url= substr($url,0,-1) ;
				}
				$data[] = $url ;
			}
		}
		
		return $data ;
	}
	//用于返回表单提交的MYSQL开头的变量，用于快速写入数据库
	function getmysqlmodel(){
		$post  = $this->input->post();
		$model = array();
		foreach($post as $k=>$v){
			if(stripos($k,"mysql_")!==false){
				$model[substr($k,6,strlen($k)-6)]=$v;
			}
		}
		return $model;	
	}
}