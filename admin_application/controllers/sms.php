<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Sms extends MY_Controller{
	function Sms(){
		parent::__construct();
		$this->load->model("M_sms","sms");
		$this->load->model("M_user","user");
		$this->load->model("M_common_system_user","system_user");
		$this->load->model("M_zzb_dangzuzhi_guanli","guanli");
		$this->load->model("M_57sy_common_sysconfig","sysconfig");
		$this->load->model('M_zzb_dangzuzhi','dzz');
	}
	
	


	function index(){
		$data = array();
		//判断是二级管理员，二级管理员只能看到自己发送的短信
		if(!is_super_admin()){
			$search["create_common_system_userid"] = admin_id();
		}		
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();	
		$username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username",true))))) ;

		$search = array();
		$search_val = array(
				"username"=>"",
				"condition"=>"1"
		);
		if(!empty($username)){
			$condition = intval($this->input->get_post("condition"));
			$condition  = in_array($condition,array(1,2))?$condition:1;
			if($condition==1){
				//$search = array("username", " LIKE '%{$username}%'");
				$search["username"]= " LIKE '%{$username}%'";
				$search_val["username"] = $username;
			}
			if($condition==2){
				//$search = array("username", " ='{$username}'");
				$search["username"]= "  ='{$username}'";
				$search_val["username"] = $username;
			}
			$search_val["condition"] = $condition;
		}
		$orderby = array("id"=>"desc");

		$data = $this->sms->GetInfoList($pageindex,100,$search,$orderby);
		$data["search_val"] = $search_val;
		$data["isdel"] = $this->permition_for("sms","del");			
						
		$this->load->view(__TEMPLET_FOLDER__."/views_sms",$data);	
	}
	
	function add(){
		$post = $this->input->post();
		if(is_array($post)){
			
			//手机号，11位数字
			$arr = explode(",",$post["tel"]);
			$sms = $post["sms"];
			$beizhu = $post["beizhu"];
			$realtel = "";
			foreach($arr as $v){
				if(preg_match('/^\d{11}$/',$v))
				{				
					$model=array(
							"sms"=>"【石岐党建】".$sms,
							"beizhu"=>$beizhu,
							"create_common_system_userid"=>admin_id(),
							"create_update"=>date("Y-m-d h:i:s"),
							"sendtime"=>date("Y-m-d h:i:s"),
							"issend"=>"1",
							"tel"=>$v
					);
					$this->sms->add($model);
					if($realtel==""){
						$realtel=$v;
					}
					else{
						$realtel.=",".$v;
					}
				}												
			}
			if($realtel!=""){
				//提交到接口方
				$return  = $this->sendsms($realtel,$sms);
				if($return!=""){						
					$data = array("err"=>"$return","tel"=>$post["tel"],"beizhu"=>$beizhu,"sms"=>$sms);
					$this->load->view(__TEMPLET_FOLDER__."/views_sms_add",$data);									
				}
				else{		
					echo "<script>
					parent.tip_show('号码已成功提交',1,10000);
					setTimeout(\"window.location.href='".site_url("sms/add")."';\",1000);
					</script>";
				}
				
			}
			else{
				/*
				echo "<script>
				parent.tip_show('提交失败，号码不是11位手机号',1,2000);
				//setTimeout(\"window.location.href='".site_url("sms/add")."';\",2000);
				</script>";*/
				$data = array("err"=>"提交失败，号码不是11位手机号","tel"=>$post["tel"],"beizhu"=>$beizhu,"sms"=>$sms);				
				$this->load->view(__TEMPLET_FOLDER__."/views_sms_add",$data);								
			}
			//header("Location:".site_url("dangzuzhi/index"));
			//exit();			
		}
		else{
			$data = array();
			$this->load->view(__TEMPLET_FOLDER__."/views_sms_add",$data);
		}
		
	}
	
	
	private function sendsms($tel,$content){
		/*
		 *
account 用户登陆名字 
password 用户登陆密码 
receivers 手机号码（多个号码用半角逗号分开） 
content 信息内容 (一般每70个字为1条短信，特殊情况除外，系统自动拆分) 
返回结果： 
如果发送成功(包括部分成功)，则返回：Success:流水号,成功号码数量
如果发送失败，则返回：Fail:错误描述
		 * 
		 */
		$account = $this->sysconfig->GetConfig("sms_name");
		$pwd = $this->sysconfig->GetConfig("sms_pwd");
		if($account == "" || $pwd == ""){
			return "还没有设置短信账号，请于系统管理>基本参数设置：短信账号和短信密码。";			
		}
		$url = "http://www.zs96000.com:1234/SMSSender.aspx";
		
		//分割提交
		$arr = explode(",",$tel);
		$arr = array_unique($arr);
		$pagesize = 1000;
		$all = count($arr);
		$tel_arr = array();
		$page=ceil($all/$pagesize); #计算总页面数
		$arr2 = array();
		for($i=1;$i<=$page;$i++){
			$start=($i-1)*$pagesize;
			$arr2[] = array_slice($arr,$start,$pagesize);
		}
		//print_r($arr2);
		//die();
		$err = array();
		$jj = 1;//出错手机批次
		foreach ($arr2 as $v){
			$tels = "";
			foreach($v as $v2){
				if($tels==""){
					$tels = $v2;
				}
				else{
					$tels.=",".$v2;
				}
			}
			
			if($tels!=""){
				$curl = curl_init();
				//设置抓取的url
				curl_setopt($curl, CURLOPT_URL, $url);
				//设置头文件的信息作为数据流输出
				curl_setopt($curl, CURLOPT_HEADER, 0);
				//设置获取的信息以文件流的形式返回，而不是直接输出。
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				//设置post方式提交
				curl_setopt($curl, CURLOPT_POST, 1);
				//设置post数据
				$post_data = array(
						"account" => $account,
						"password" => $pwd,
						"receivers"=>$tels,
						"content"=>$content
				);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
				//执行命令
				$data = curl_exec($curl);
				$err[] = $data;
				if( strtolower(substr($data,0,4))=="fail"){
					$content = $tels;
					//写入日志
					write_action_log(
					($jj++)."-批".$content,
					$this->uri->uri_string(),
					login_name(),
					get_client_ip(),
					0,
					"短信发送错误：".$data);
				}				
				//关闭URL请求
				curl_close($curl);							
			}
		
		
		}	
		if(count($err)>0){
			//取第一条错误 就行
			return $err[0].",发送不成功的号码在系统日志里查询。";
		}		
		return "";
		
			
		
	}
	
	function seluser(){

		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
		
		$username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username",true))))) ;
		$dzz_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("dzz_title",true))))) ;
		if(isset($get["status"])){
			$status = daddslashes(html_escape(strip_tags(trim($this->input->get_post("status",true))))) ;
		}
		else{
			$status = "-1";
		}
		$dzzid = empty($get["dzz_id"])?0:$get["dzz_id"];
		$dzzid2 = empty($get["dzz_id2"])?0:$get["dzz_id2"];
		$shenfen = empty($get["shenfen"])?"":trim($get["shenfen"]);		
		$search = array("usertype"=>" in(45064,45063) ");
		//判断是二级管理员，如果非超管就需要取他当前党组织
		if(!is_super_admin()){
			/*
			$dzz_list = $this->guanli->GetList(admin_id());
			$tmp_dzz_id = "";
			if(is_array($dzz_list)){
				for($i=0;$i<count($dzz_list);$i++){
					if($i==0){
						$tmp_dzz_id = $dzz_list[$i]["dangzuzhi_id"];
					}
					else{
						$tmp_dzz_id .=",".$dzz_list[$i]["dangzuzhi_id"];
					}
				}
			}
			*/
			$tmp_dzz_id = $this->guanli->GetGuanLiID(admin_id());
			if($tmp_dzz_id!=""){
				$search["zzb_dangzuzhi_id"]=" in($tmp_dzz_id)";
			}
			else{
				$search["zzb_dangzuzhi_id"]="=-1";//无权查看
			}
		}
		if($shenfen>0){
			$search["usertype"] = "=$shenfen";
		}
		$search_val = array(
				"username"=>"",
				"condition"=>"1",
				"status"=>$status,
				"dzz_title"=>"",
				"shenfen"=>$shenfen
		);
		if($dzzid>0){
			if($dzzid2>0){
				$search["zzb_dangzuzhi_id"]="=$dzzid2";
			}
			else{
				//查找组织下边所有党支部
				$dzz_list = $this->dzz->GetList($dzzid);
				$dzz_ids = $dzzid;
				foreach($dzz_list as $v){
					$dzz_ids.=",".$v["id"];
				}
				$search["zzb_dangzuzhi_id"]=" in($dzz_ids)";
			}
		}
		if(!empty($username)){
			$condition = intval($this->input->get_post("condition"));
			$condition  = in_array($condition,array(1,2))?$condition:1;
			if($condition==1){
				//$search = array("username", " LIKE '%{$username}%'");
				$search["username"]= " LIKE '%{$username}%'";
				$search_val["username"] = $username;
			}
			if($condition==2){
				//$search = array("username", " ='{$username}'");
				$search["username"]= "  ='{$username}'";
				$search_val["username"] = $username;
			}
			$search_val["condition"] = $condition;
		}
		if(!empty($dzz_title)){
				
			$search["dzz_title"]= " LIKE '%{$dzz_title}%'";
			$search_val["dzz_title"] = $dzz_title;
		}
		if($status>=0){
			$search['status'] = "='".strval($status)."'";
			$search_val["status"] = $status;
		}
		else{
			$search['status'] = "";
			$search_val["status"] = "-1";
		}
		//print_r($search_val);
		//die();
		$data = array();
		
		$orderby["uid"] = "desc";
		$data = $this->user->GetInfoList($pageindex,5,$search,$orderby);
		$data["isadd"] = $this->permition_for("user","add");
		$data["isdel"] = $this->permition_for("user","del");
		$data["search_val"] = $search_val;
		$data['dzz']=$this->dzz->GetList(0);
		$data['dzz2']=($dzzid2>0?$this->dzz->GetList($dzzid):array());
		$data['dzzid']=$dzzid;
		$data['dzzid2']=$dzzid2;
					
		
		foreach ($data["list"] as $k=>$v){
			$data["list"][$k]['status'] = ($v['status'] == 1 )?"开启":'<font color="red">禁止</font>';
			$data["list"][$k]['regdate'] = date("Y-m-d H:i:s",$v['regdate']);
			$data["list"][$k]['lastlogin'] = (date('Y',strtotime($v['lastlogin']))==1970 )?'<font color="green">-</font>':date("Y-m-d H:i:s",strtotime($v['lastlogin']));
				
		}
		
				
		
		$this->load->view(__TEMPLET_FOLDER__."/views_sms_seluser",$data);
	}
	
	function selall(){
		//如果是二级管理员，就选择党组下边的所有党员
		$where = "usertype=45064";
		if(!is_super_admin()){
			/*			
			$guanlilist  = $this->guanli->GetList(admin_id());
			//判断管理哪个党组织
			$dzz_id = "";
			foreach($guanlilist as $v){
				if($dzz_id==""){
					$dzz_id = $v["dangzuzhi_id"];
				
				}
				else{
					$dzz_id .= ",".$v["dangzuzhi_id"];
				}
			}
			*/
			$dzz_id = $this->guanli->GetGuanLiID(admin_id());
			if($dzz_id == "")
			{
				$where.=" and 1<>1";
			}
			else{
				$where .= " and zzb_dangzuzhi_id in($dzz_id)";
			}
			
		}
		$list = $this->user->GetUserList($where);
		$tel = "";
		foreach($list as $v){
			//11位数字
			if(preg_match('/^\d{11}$/',$v["tel"]))
			{				
				if($tel==""){
					$tel=$v["tel"];
				}
				else{
					$tel.=",".$v["tel"];
				}							
			}	
		}
		echo $tel;
		exit();
	}
		
	function del(){
		$get = $this->input->get();
		$idlist = $get["idlist"];				
		if($idlist!=""){				
			$arr = explode(",",$idlist);
			foreach($arr as $v){				
				$this->sms->del($v);
			}
		}				
	}		
}
?>