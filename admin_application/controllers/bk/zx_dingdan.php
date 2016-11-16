<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Zx_dingdan extends MY_Controller{
	function Zx_dingdan(){
		parent::__construct();
		$this->load->model("M_zx_dingdan","dingdan");
	}
	
	


	function index(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();							
		$search = array();			
		$data = array();
		$orderby["create_time"] = "desc";		
		$data = $this->dingdan->GetInfoList($pageindex,20,$search,$orderby);
		//$data["isadd"] = $this->permition_for("dengji","add");
		//$data["isdel"] = $this->permition_for("dengji","del");
		
		foreach ($data["list"] as $k=>$v){
			$tmppng = "";
			/*
			for($j=0;$j<$data["list"][$k]['count'];$j++){
				$tmppng.= "<img src='/".$data["list"][$k]['pngpath']."'/>";				
			}
			$data["list"][$k]['ico'] = $tmppng;				
			*/
		}		
		$this->load->view(__TEMPLET_FOLDER__."/zx/dingdan",$data);		
	}
	
	

	
	function del(){
		$get = $this->input->get();
		$idlist = $get["idlist"];				
		if($idlist!=""){				
			$arr = explode(",",$idlist);
			foreach($arr as $v){				
				$this->dingdan->del($v);
			}
		}				
	}
	
	function view(){
		$get = $this->input->get();
		$dingdan_hao  = $get["id"];//订单号ID
		$list = $this->dingdan->getlist("dingdan_hao='$dingdan_hao'");
		$data["list"] = $list;
		$this->load->view(__TEMPLET_FOLDER__."/zx/dingdan_view",$data);		
	}
}
?>