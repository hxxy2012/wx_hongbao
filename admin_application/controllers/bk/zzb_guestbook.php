<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Zzb_guestbook extends MY_Controller{
	function Zzb_guestbook(){
		parent::__construct();
		$this->load->model("M_zzb_guestbook","guestbook");
		$this->load->model("M_common_system_user","system_user");
		$this->load->model("M_zzb_dangzuzhi_guanli","guanli");
	}
	
	function index(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
		
		$other = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_other",true))))) ;
		$title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title",true))))) ;
		$dzz_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("dzz_title",true))))) ;
		
		
		$search = array();
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
				$search["t2.zzb_dangzuzhi_id"]=" in($tmp_dzz_id)";
			}
			else{
				$search["t2.zzb_dangzuzhi_id"]="=-1";//无权查看
			}
		}
		$search_val = array(
				"search_title"=>"",
				"username"=>"",
				"condition"=>"1",
				"dzz_title"=>"",
				"search_other"=>""
				
		);

		if(!empty($dzz_title)){
				
			$search["t3.title"]= " LIKE '%{$dzz_title}%'";
			$search_val["dzz_title"] = $dzz_title;
		}
		if(!empty($title)){
		
			$search["t1.title"]= $title;
			$search_val["search_title"] = $title;
		}

		if(!empty($other)){
		
			$search["t1.other"]= $other;
			$search_val["search_other"] = $other;
		}		
			
		
		//print_r($search_val);
		//die();
		$data = array();
		$orderby = array();
		$pagesize = 10;
		$data = $this->guestbook->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$data["search_val"] = $search_val;		
		$data["isdel"] = $this->permition_for("zzb_guestbook","del");		
		$pm = ($pageindex-1)*$pagesize;		
		//print_r($data);
		$this->load->view(__TEMPLET_FOLDER__."/guestbook/index",$data);		
	}
		
	
	function check(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
	
		$other = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_other",true))))) ;
		$title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title",true))))) ;
		$dzz_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("dzz_title",true))))) ;
	
	
		$search = array("checkstatus"=>"0");
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
				$search["t2.zzb_dangzuzhi_id"]=" in($tmp_dzz_id)";
			}
			else{
				$search["t2.zzb_dangzuzhi_id"]="=-1";//无权查看
			}
		}
		$search_val = array(
				"search_title"=>"",
				"username"=>"",
				"condition"=>"1",
				"dzz_title"=>"",
				"search_other"=>""
	
		);
	
		if(!empty($dzz_title)){
	
			$search["t3.title"]= " LIKE '%{$dzz_title}%'";
			$search_val["dzz_title"] = $dzz_title;
		}
		if(!empty($title)){
	
			$search["t1.title"]= $title;
			$search_val["search_title"] = $title;
		}
	
		if(!empty($other)){
	
			$search["t1.other"]= $other;
			$search_val["search_other"] = $other;
		}
			
	
		//print_r($search_val);
		//die();
		$data = array();
		$orderby = array();
		$pagesize = 10;
		$data = $this->guestbook->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$data["search_val"] = $search_val;
		$data["isdel"] = $this->permition_for("zzb_guestbook","del");
		$pm = ($pageindex-1)*$pagesize;
		//print_r($data);
		$this->load->view(__TEMPLET_FOLDER__."/guestbook/check",$data);
	}	
	
	function del(){
		$get = $this->input->get();
		$idlist = $get["idlist"];
		$isdel = false;
		if(!is_super_admin()){
			if($this->permition_for('zzb_guestbook','del')){
				$isdel = true;
			}
		}
		else{
			$isdel = true;
		}
		//只有超管才能删除
		if($idlist!="" && $isdel){	
			//echo "$idlist";
			//die();		
			$arr = explode(",",$idlist);
			foreach($arr as $v){
				$this->guestbook->del($v);
			}
		}
	}
	
	function docheck(){
		$get = $this->input->get();
		$idlist = $get["idlist"];
		$isdel = false;
		if(!is_super_admin()){
			if($this->permition_for('zzb_guestbook','docheck')){
				$isdel = true;
			}
		}
		else{
			$isdel = true;
		}		
		if($idlist!="" && $isdel){
			//echo "$idlist";
			//die();
			$arr = explode(",",$idlist);
			foreach($arr as $v){
				$model = $this->guestbook->GetModel($v);
				$model["checkstatus"] = "80";
						if(is_super_admin()){
							$model["content_admin"] = "";							
							$model["content_admin_date"] = date("Y-m-d H:i:s");
							$model["common_system_userid"] = admin_id();
											
						}
						else{
							$model["content_admin2"] = "";						
							$model["content_admin2_date"] = date("Y-m-d H:i:s");
							$model["common_system_userid2"] = admin_id();											
						}
				$this->guestbook->update($model);
			}
		}
	}	
	
	function edit(){
		$post = $this->input->post();
	
		if(is_array($post)){
			$model = $this->guestbook->GetModel($post["id"]);
			$model["title"]=$post["title"];
			$model["content"]=$post["content"];
			$old_checkstatus = $model["checkstatus"];
			$model["checkstatus"]=$post["checkstatus"];			
			//判断回复人是超管还是二级
			if(is_super_admin()){
				$model["content_admin"] = $post["content2"];
				//if($model["checkstatus"]=="80" && $old_checkstatus!=$model["checkstatus"]){
				if($model["checkstatus"]=="80"){
					$model["content_admin_date"] = date("Y-m-d H:i:s");
					$model["common_system_userid"] = admin_id();
				}				
			}
			else{
				$model["content_admin2"] = $post["content2"];
				//if($model["checkstatus"]=="80"  && $old_checkstatus!=$model["checkstatus"]){
				if($model["checkstatus"]=="80"){
					$model["content_admin2_date"] = date("Y-m-d H:i:s");
					$model["common_system_userid2"] = admin_id();
				}				
			}
			$this->guestbook->update($model);
			$backurl = empty($post["backurl"])?base_url()."admin.php/zzb_guestbook/index.shtml":$post["backurl"];
			echo "<script>
			parent.tip_show('修改成功',1,1000);
			var url = \"$backurl\";
			parent.flushpage(url);
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";
			//header("Location:".site_url("dangzuzhi/index"));
			exit();
		}
		else{
			$get = $this->input->get();
			if(!is_numeric($get["id"])){
			die("err");
			}
			$get = $this->input->get();
			$data = array();
			$data["backurl"] = empty($get["backurl"])?site_url("zzb_guestbook/index"):$get["backurl"];
			$model = $this->guestbook->GetModel($get["id"]);
			$data["model"] = $model;
			$admin_user = "";
			$admin_user2 = "";
			if($model["common_system_userid"]>0){
				$admin_user = $this->system_user->GetModel($model["common_system_userid"]);
			}			
			if($model["common_system_userid2"]>0){
				$admin_user2 = $this->system_user->GetModel($model["common_system_userid2"]);
			}
			$data["admin_user"] = $admin_user;
			$data["admin_user2"] = $admin_user2;
			
			$this->load->view(__TEMPLET_FOLDER__."/guestbook/edit",$data);
		}
	}	
	
	
}
?>