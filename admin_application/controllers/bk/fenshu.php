<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Fenshu extends MY_Controller{
	function Fenshu(){
		parent::__construct();
		$this->load->model("M_zzb_dangzuzhi","zzb_dzz");
		$this->load->model("M_zzb_dangyuan_fenshu","fenshu");
		$this->load->model("M_zzb_fenshu_leibie","fenshulb");
		$this->load->model('M_zzb_dangzuzhi_guanli','guanli');
		$this->load->model('M_user','user');
	}
	
	


	function paixing(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
		
		$username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username",true))))) ;
		$dzz_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("dzz_title",true))))) ;

		//改为不限党员或积极分子
		$search = array();//array("t2.usertype"=>"='45064'");
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
			"username"=>"",
			"condition"=>"1",
			"dzz_title"=>""							
		);
		if(!empty($username)){
			$condition = intval($this->input->get_post("condition"));
			$condition  = in_array($condition,array(1,2))?$condition:1;
			if($condition==1){
				//$search = array("username", " LIKE '%{$username}%'");
				$search["t2.username"]= " LIKE '%{$username}%'";
				$search_val["username"] = $username;
			}
			if($condition==2){
				//$search = array("username", " ='{$username}'");				
				$search["t2.username"]= "  ='{$username}'";
				$search_val["username"] = $username;
			}
			$search_val["condition"] = $condition;
		}
		if(!empty($dzz_title)){
			
				$search["dzz_title"]= " LIKE '%{$dzz_title}%'";
				$search_val["dzz_title"] = $dzz_title;			
		}

		//print_r($search_val);
		//die();	
		$data = array();
		
		$pagesize = 10;
		$data = $this->fenshu->GetPaiMing($pageindex,$pagesize,$search);
		$data["search_val"] = $search_val;
		$pm = ($pageindex-1)*$pagesize;
		foreach ($data["list"] as $k=>$v){
			$data["list"][$k]['paiming'] = ++$pm;						
		}
		//print_r($data);			
		$this->load->view(__TEMPLET_FOLDER__."/views_fenshu_paixing",$data);	
	}
	
	function add(){
		$post = $this->input->post();
		if(is_array($post)){
			$model=array(					
				"title"=>$post["title"],
				"pid"=>$post["pid"],
				"orderby"=>$post["orderby"],
				"create_common_system_userid"=>admin_id(),
				"create_date"=>date("Y-m-d h:i:s"),
				"addr"=>$post["addr"]
			);
			$this->zzb_dzz->add($model);
			echo "<script>
			parent.tip_show('添加成功',1,1000);
			setTimeout(\"window.location.href='".site_url("dangzuzhi/index")."?pid=".$post["pid"]."';\",1000);
			</script>";
			//header("Location:".site_url("dangzuzhi/index"));
			exit();			
		}
		else{
			$data = array();
			$dzz = $this->zzb_dzz->GetList(0);
			$data["dzz"] = $dzz;
			$this->load->view(__TEMPLET_FOLDER__."/views_dangzuzhi_add",$data);
		}
		
	}
	
	function edit(){
		if(!is_super_admin()){
			die("quanxian_err");		
		}
		$post = $this->input->post();
		if(is_array($post)){
			$model = $this->fenshu->GetModel($post["id"]);
			$model["beizhu"]=$post["beizhu"];
			$model["fenshu"]=$post["fenshu"];
			$model["update_common_system_userid"]=admin_id();
			$model["update_date"]=date("Y-m-d h:i:s");			

			$this->fenshu->update($model);
			$backurl = empty($post["backurl"])?base_url()."admin.php/fenshu/index.shtml":$post["backurl"];
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
			if(!is_super_admin()){
				die("quanxian_err");
				
			}
			else{
			$get = $this->input->get();
			if(!is_numeric($get["id"])){
				die("err");
			}
			
			$data = array();
					
			$model = $this->fenshu->GetModel($get["id"]);			
			$data["model"] = $model;
			$data["backurl"] =empty($get["backurl"])?"":$get["backurl"];			
			$this->load->view(__TEMPLET_FOLDER__."/views_fenshu_edit",$data);
			}
		}		
	}
	
	function chktitle(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from zzb_dangzuzhi where isdel=0 and title='$title'".($id>0?" and id<>$id":""));
		if(count($model)>0){
			$data = array("result"=>"0","msg"=>"党组织名称重复");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();		
	}
	function count(){

	}
	
	function leixing(){
		
	}
	
	
	function fslog(){
		$data = array();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
		$search = array();
		$search_val = array();
		$search["username"]="";
		$search["zzb_fenshu_leibie_id"] = "";
		$search_val["username"]="";
		$search_val["zzb_fenshu_leibie_id"] = "";
		
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
				//找出党组织下，所有用户ID
				$userid_tmp = $this->user->GetUserList("zzb_dangzuzhi_id in(".$tmp_dzz_id.")",null);
				if(is_array($userid_tmp)){
					$userid_arr = array();
					foreach($userid_tmp as $v){
						array_push($userid_arr,$v["uid"]);
					} 
					$search["create_common_userid"]=" in(".(implode(",", $userid_arr)).")";
				}
			}
			
		}		
		//$data["userfenshu"]=0;
		if(!empty($get["userid"])){
			if($get["userid"]>0){
				$search["create_common_userid"] = "=".trim($get["userid"]);								
			}
		}
		
		if(!empty($get["username"])){
			if($get["username"]!=""){
				$search["username"] = trim($get["username"]);
				$search_val["username"] = trim($get["username"]);
			}
		}
		if(!empty($get["zzb_fenshu_leibie_id"])){
			if($get["zzb_fenshu_leibie_id"]>0){
				$search["zzb_fenshu_leibie_id"] = "=".trim($get["zzb_fenshu_leibie_id"]);
				$search_val["zzb_fenshu_leibie_id"] = trim($get["zzb_fenshu_leibie_id"]);
			}
		}
		
		$orderby["id"] = "desc";		
		$data = $this->fenshu->GetInfoList($pageindex,20,$search,$orderby);		
		$data["isdel"] = is_super_admin();//$this->permition_for("fenshu","del");
		$data["isadd"] = is_super_admin();  //$this->permition_for("fenshu","add");
		$data["isedit"] = is_super_admin();//$this->permition_for("fenshu","add");
		$data["fenshu_leibie"] = $this->fenshulb->getlist("isdel=0","orderby asc,id asc");
		$data["search_val"] = $search_val;
		foreach ($data["list"] as $k=>$v){
			$tmppng = "";
//			for($j=0;$j<$data["list"][$k]['count'];$j++){

	//		}		
		}
		if(!empty($get["userid"])){
			if($get["userid"]>0){		
			$data["userfenshu"] = $this->fenshu->getuserfenshu($get["userid"]);
			}
		}
		$this->load->view(__TEMPLET_FOLDER__."/views_fenshu_log",$data);		
	}
	
	function del(){
		$get = $this->input->get();
		$idlist = $get["idlist"];				
		if($idlist!=""){				
			$arr = explode(",",$idlist);
			foreach($arr as $v){				
				$this->fenshu->del($v);
			}
		}				
	}		
}
?>