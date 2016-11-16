<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Dangzuzhi extends MY_Controller{
	function Dangzuzhi(){
		parent::__construct();
		$this->load->model("M_zzb_dangzuzhi","zzb_dzz");
	}
	
	


	function index(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();		
		//print_r($search_val);
		//die();
		$search = array();
		$dangzuzhi="";
		if( !empty($get["pid"]) ){
			$search["pid"] = $get["pid"];	
			$dangzuzhi=$this->zzb_dzz->GetModel($get["pid"]);
		}
		else{
			$search["pid"] = "0";			
		}
		
		$data = array();			
		$orderby = array();
		$data = $this->zzb_dzz->GetInfoList($pageindex,10,$search,$orderby);
		$data["search_val"] = array();
		$data["dangzuzhi"] = $dangzuzhi;				
		$this->load->view(__TEMPLET_FOLDER__."/views_dangzuzhi",$data);	
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
		$post = $this->input->post();
		if(is_array($post)){
			$model = $this->zzb_dzz->GetModel($post["id"]);
			$model=array(
					"id"=>$post["id"],
					"title"=>$post["title"],
					"pid"=>$post["pid"],
					"orderby"=>$post["orderby"],
					"update_common_system_userid"=>admin_id(),
					"update_date"=>date("Y-m-d h:i:s"),
					"addr"=>$post["addr"]
			);
			//print_r($model);
			//die();
			$this->zzb_dzz->update($model);
			echo "<script>
			parent.tip_show('修改成功',1,1000);
			var url = \"".base_url()."admin.php/dangzuzhi/index.shtml?pid=".$post["pid"]."\";
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
			$data = array();
			$dzz = $this->zzb_dzz->GetList(0);
			$data["dzz"] = $dzz;
			$model = $this->zzb_dzz->GetModel($get["id"]);
			$data["model"] = $model;
			$this->load->view(__TEMPLET_FOLDER__."/views_dangzuzhi_edit",$data);
		}		
	}
	
	function chktitle(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$pid = empty($get["pid"])?0:$get["pid"];
		$model = $this->M_common->query_one("select * from zzb_dangzuzhi where pid=".$pid." and isdel=0 and title='$title'".($id>0?" and id<>$id":""));
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
		$get = $this->input->get();		
		$dzzid = $get["dzzid"];
		if($dzzid!=""){
			echo $this->zzb_dzz->dzz_count($dzzid);
		}
		else{
			echo "999";
		}
	}
	function del(){
		$get = $this->input->get();
		$idlist = $get["idlist"];				
		if($idlist!=""){				
			$arr = explode(",",$idlist);
			foreach($arr as $v){				
				$this->zzb_dzz->del($v);
			}
		}				
	}		
}
?>