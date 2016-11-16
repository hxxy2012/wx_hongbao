<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Fenshu_leibie extends MY_Controller{
	function Fenshu_leibie(){
		parent::__construct();
		$this->load->model("M_zzb_dangzuzhi","zzb_dzz");
		$this->load->model("M_zzb_dangyuan_fenshu","fenshu");
		$this->load->model("M_zzb_fenshu_leibie","fenshu_leibie");
	}
	
	


	function index(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
		
		$typename = daddslashes(html_escape(strip_tags(trim($this->input->get_post("typename",true))))) ;

		$search = array();
		
		$search_val = array(
				"typename"=>""
		);
		if(!empty($typename)){
			$search["typename"]= " LIKE '%{$typename}%'";
			$search_val["typename"] = $typename;
		}
		
		$data = array();
		$orderby["orderby"] = "asc";
		$orderby["id"] = "asc";
		$data = $this->fenshu_leibie->GetInfoList($pageindex,10,$search,$orderby);
		$data["isadd"] = $this->permition_for("fenshu_leibie","add");
		$data["isdel"] = $this->permition_for("fenshu_leibie","del");
		$data["search_val"] = $search_val;

		$this->load->view(__TEMPLET_FOLDER__."/views_fenshu_leibie",$data);		
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
				"typename"=>$post["typename"],
				"orderby"=>50,
				"issystem"=>$post["issystem"],
				"beizhu"=>$post["beizhu"],
				"fenshu"=>$post["fenshu"],					
				"create_common_system_userid"=>admin_id(),
				"createdate"=>date("Y-m-d h:i:s")				
			);
			$this->fenshu_leibie->add($model);
			echo "<script>
			parent.tip_show('新增成功',1,1000);
			setTimeout(\"window.location.href='".site_url("fenshu_leibie/index")."';\",1000);
			</script>";
			//header("Location:".site_url("dangzuzhi/index"));
			exit();			
		}
		else{
			$get = $this->input->get();
			$data = array();
			$data["backurl"] = empty($get["backurl"])?site_url("fenshu_leibie/index"):$get["backurl"];			
			$this->load->view(__TEMPLET_FOLDER__."/views_fenshu_leibie_add",$data);
		}
		
	}
	
	
	
	function edit(){
		$post = $this->input->post();
		//print_r($post);
		//echo $post["id"];
		//die();	
		if(is_array($post)){
			$model = $this->fenshu_leibie->GetModel($post["id"]);			
			$model["typename"]=$post["typename"];
			$model["orderby"]=50;				
			$model["beizhu"]=$post["beizhu"];
			$model["fenshu"]=$post["fenshu"];					
			$model["update_common_system_userid"]=admin_id();
			$model["updatedate"]=date("Y-m-d h:i:s");				
			
			//print_r($model);
			//die();
			$this->fenshu_leibie->update($model);
			$backurl = empty($post["backurl"])?base_url()."admin.php/fenshu_leibie/index.shtml":$post["backurl"];
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
			$data["backurl"] = empty($get["backurl"])?site_url("fenshu_leibie/index"):$get["backurl"];			
			$model = $this->fenshu_leibie->GetModel($get["id"]);
			$data["model"] = $model;
			$this->load->view(__TEMPLET_FOLDER__."/views_fenshu_leibie_edit",$data);
		}		
	}
	
	function chktitle(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from zzb_fenshu_leibie where isdel=0 and typename='$title'".($id>0?" and id<>$id":""));
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
				$this->fenshu_leibie->del($v);
			}
		}				
	}		
}
?>