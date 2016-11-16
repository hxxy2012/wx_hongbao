<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Zzb_dy_dati_history extends MY_Controller{
	function Zzb_dy_dati_history(){
		parent::__construct();
		$this->load->model("M_zzb_dy_dati_history","ddfz_history");
		$this->load->model("M_zzb_dangzuzhi_guanli","guanli");
		$this->load->model("M_website_model_zuixindongtai_dyart","dyart");
	}
	
	function index(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
		
		$username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username",true))))) ;
		$title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("title",true))))) ;
		$dzz_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("dzz_title",true))))) ;
		
		
		$search = array();
		//判断是二级管理员，如果非超管就需要取他当前党组织
		if(!is_super_admin()){
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
			if($tmp_dzz_id!=""){
				$search["t2.zzb_dangzuzhi_id"]=" in($tmp_dzz_id)";
			}
			else{
				$search["t2.zzb_dangzuzhi_id"]="=-1";//无权查看
			}
		}
		$search_val = array(
				"title"=>"",
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
				
			$search["t3.title"]= " LIKE '%{$dzz_title}%'";
			$search_val["dzz_title"] = $dzz_title;
		}
		if(!empty($title)){
		
			$search["t1.title"]= " LIKE '%{$title}%'";
			$search_val["title"] = $title;
		}		
		
		//print_r($search_val);
		//die();
		$data = array();
		$orderby = array();
		$pagesize = 10;
		$data = $this->ddfz_history->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$data["search_val"] = $search_val;		
		$data["isdel"] = is_super_admin(); //$this->permition_for("zzb_dy_dati_history","del");		
		$pm = ($pageindex-1)*$pagesize;		
		//print_r($data);
		$this->load->view(__TEMPLET_FOLDER__."/dangyuantiku/history",$data);		
	}
		
	
	function tongji(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
	
		$username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username",true))))) ;
		$search_yearmonth = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_yearmonth",true))))) ;
		$dzz_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("dzz_title",true))))) ;
	
	
		$search = array();
		//判断是二级管理员，如果非超管就需要取他当前党组织
		if(!is_super_admin()){
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
				"dzz_title"=>"",
				"search_yearmonth"=>""				
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
		if(!empty($search_yearmonth)){
			$search["create_date"]= " date_format('".$search_yearmonth."-01','%Y-%m')=date_format(t1.create_date,'%Y-%m') ";
			$search_val["search_yearmonth"] = $search_yearmonth;
		}
	
		//print_r($search_val);
		//die();
		$data = array();
	
		$pagesize = 10;
		$data = $this->ddfz_history->GetPaiMing($pageindex,$pagesize,$search);
		$data["search_val"] = $search_val;
		$pm = ($pageindex-1)*$pagesize;
		foreach ($data["list"] as $k=>$v){
			$data["list"][$k]['paiming'] = ++$pm;
		}
		//print_r($data);
		$this->load->view(__TEMPLET_FOLDER__."/dangyuantiku/tongji",$data);
	}	
	
	
	function del(){
		$get = $this->input->get();
		$idlist = $get["idlist"];
		$isdel = false;
		if(!is_super_admin()){
			if($this->permition_for('zzb_dy_dati_history','del')){
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
				$this->ddfz_history->del($v);
			}
		}
	}
	/*
	党员文章删除后，没有及时删除动态对应的党员文章ID，导致统计不准(动态已答/应答不准)
	
	*/
	function chk_website_model_zuixindongtai_dyart(){
		$this->dyart->delnull();
	}
}
?>