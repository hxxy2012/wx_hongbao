<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Zzb_dengji extends MY_Controller{
	function Zzb_dengji(){
		parent::__construct();
		$this->load->model("M_zzb_dangzuzhi","zzb_dzz");
		$this->load->model("M_zzb_dangyuan_fenshu","fenshu");
		$this->load->model("M_zzb_fenshu_leibie","fenshu_leibie");
		$this->load->model("M_zzb_dengji","dengji");
	}
	
	


	function index(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();							
		$search = array();			
		$data = array();
		$orderby["orderby"] = "asc";
		$orderby["fenshu_start"] = "asc";
		$data = $this->dengji->GetInfoList($pageindex,20,$search,$orderby);
		$data["isadd"] = $this->permition_for("dengji","add");
		$data["isdel"] = $this->permition_for("dengji","del");
		
		foreach ($data["list"] as $k=>$v){
			$tmppng = "";
			for($j=0;$j<$data["list"][$k]['count'];$j++){
				$tmppng.= "<img src='/".$data["list"][$k]['pngpath']."'/>";				
			}
			$data["list"][$k]['ico'] = $tmppng;				
		}		
		$this->load->view(__TEMPLET_FOLDER__."/views_dengji",$data);		
	}
	function count(){
		$get = $this->input->get();
		$ids = $get["ids"];
		if($ids!=""){
			echo $this->fenshu_leibie->count("id in($ids) and isdel=0 and issystem=1");
		}
		else{
			echo "999";
		}		
	}
	function add(){
		$post = $this->input->post();
		if(is_array($post)){
			$model=array(					
				"dengji"=>$post["dengji"],
				"orderby"=>50,
				"count"=>$post["count"],
				"pngpath"=>$post["pngpath"],
				"fenshu_start"=>$post["fenshu_start"],
				"fenshu_end"=>$post["fenshu_end"],
				"create_common_system_userid"=>admin_id(),
				"createdate"=>date("Y-m-d h:i:s")				
			);
			$this->dengji->add($model);
			echo "<script>
			parent.tip_show('新增成功',1,1000);
			setTimeout(\"window.location.href='".site_url("zzb_dengji/index")."';\",1000);
			</script>";
			//header("Location:".site_url("dangzuzhi/index"));
			exit();			
		}
		else{
			$get = $this->input->get();
			$data = array();
			$data["backurl"] = empty($get["backurl"])?site_url("fenshu_leibie/index"):$get["backurl"];			
			$this->load->view(__TEMPLET_FOLDER__."/views_dengji_add",$data);
		}
		
	}
	
	
	
	function edit(){
		$post = $this->input->post();
	
		if(is_array($post)){
			$model = $this->dengji->GetModel($post["id"]);			
			$model["dengji"]=$post["dengji"];
			$model["orderby"]=50;				
			$model["count"]=$post["count"];
			$model["pngpath"]=$post["pngpath"];	
			$model["fenshu_start"] = $post["fenshu_start"];
			$model["fenshu_end"] = $post["fenshu_end"];
			
			$model["update_common_system_userid"]=admin_id();
			$model["updatedate"]=date("Y-m-d h:i:s");				
			
			//print_r($model);
			//die();
			$this->dengji->update($model);
			$backurl = empty($post["backurl"])?base_url()."admin.php/zzb_dengji/index.shtml":$post["backurl"];
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
			$data["backurl"] = empty($get["backurl"])?site_url("zzb_dengji/index"):$get["backurl"];			
			$model = $this->dengji->GetModel($get["id"]);
			$data["model"] = $model;
			$this->load->view(__TEMPLET_FOLDER__."/views_dengji_edit",$data);
		}		
	}
	
	function chktitle(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from zzb_dengji where isdel=0 and dengji='$title'".($id>0?" and id<>$id":""));
		if(count($model)>0){
			$data = array("result"=>"0","msg"=>"名称重复");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();		
	}

	

	
	function del(){
		$get = $this->input->get();
		$idlist = $get["idlist"];				
		if($idlist!=""){				
			$arr = explode(",",$idlist);
			foreach($arr as $v){				
				$this->dengji->del($v);
			}
		}				
	}		
}
?>