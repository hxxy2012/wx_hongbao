<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Fenzitiku extends MY_Controller{
	function Fenzitiku(){
		parent::__construct();		
		$this->load->model("M_zzb_jjfz_dati","dati");
		$this->load->model("M_zzb_jjfz_dati_item","dati_item");
	}
	
	


	function index(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
		
		$search_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title",true))))) ;

		$search = array();
		
		$search_val = array(
				"search_title"=>""
		);
		if(!empty($search_title)){
			$search["title"]= " LIKE '%{$typename}%'";
			$search_val["search_title"] = $typename;
		}
		
		$data = array();
		$orderby["orderby"] = "asc";
		$orderby["id"] = "desc";
		$data = $this->dati->GetInfoList($pageindex,10,$search,$orderby);
		$data["isadd"] = $this->permition_for("fenzitiku","add");
		$data["isdel"] = $this->permition_for("fenzitiku","del");
		$data["search_val"] = $search_val;

		$this->load->view(__TEMPLET_FOLDER__."/fenzitiku/index",$data);		
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
			$guid = $post["guid"];
			$bigitem = $post["bigitem"];
			$title = $post["title"];
			$content = $post["content"];
			$beizhu = $post["beizhu"];
			$isshow = $post["isshow"];
			
			//主表，主题项
			$model=array(					
				"title"=>$title,
				"orderby"=>50,
				"pid"=>"0",
				"content"=>$content,
				"beizhu"=>$beizhu,
				"isshow"=>$isshow,	
				"guid"=>$guid,	
				"isdel"=>"0",
				"create_common_system_userid"=>admin_id(),
				"create_date"=>date("Y-m-d h:i:s")				
			);
			$pid = $this->dati->add($model);
			/*
			echo "pid=".$pid;
			echo "<br/>";
			echo "bigitem=".$bigitem;
			die();
			*/
			//主表，答题标题项
			if($pid>0 && $bigitem!=""){
				//echo $bigitem;
				$arr = json_decode("[$bigitem]",true);
				//print_r($arr);
				//die();
				for($i=0;$i<count($arr);$i++){
					$model = array(
						"title"=>$arr[$i]["dati"],
						"seltype"=>$arr[$i]["seltype"],
						"pid"=>$pid,							
						"isshow"=>"1",
						"isdel"=>"0",
						"guid"=>$guid,
						"content"=>'',
						"beizhu"=>'',							
						"create_common_system_userid"=>admin_id(),
						"create_date"=>date("Y-m-d h:i:s")															
					);
	
					$dati_id = $this->dati->add($model);

					if($dati_id>0 && is_array($arr[$i]["subitem"])){
						$subitem = $arr[$i]["subitem"];
						//插入答题选择项
						for($j=0;$j<count($subitem);$j++){
							$model_subitem = array(
								"zzb_jjfz_dati_id"=>$dati_id,
								"title"=>$subitem[$j]["title"],
								"guid"=>$guid,
								"isdel"=>"0",
								"orderby"=>"50",
								"content"=>'',
								"beizhu"=>'',									
								"istrue"=>$subitem[$j]["istrue"],
								"create_common_system_userid"=>admin_id(),
								"create_date"=>date("Y-m-d h:i:s")	
							);
							$this->dati_item->add($model_subitem);
						}						
					}				
				}
			}
			
			echo "<script>
			parent.tip_show('新增成功',1,1000);
			setTimeout(\"window.location.href='".site_url("fenzitiku/index")."';\",1000);
			</script>";
			//header("Location:".site_url("dangzuzhi/index"));
			exit();			
		}
		else{
			$get = $this->input->get();
			$data = array();
			$data["backurl"] = empty($get["backurl"])?site_url("fenzitiku/index"):$get["backurl"];			
			$this->load->view(__TEMPLET_FOLDER__."/fenzitiku/add",$data);
		}
		
	}
	
	function getitem(){
		$get = $this->input->get();
		$id = empty($get["id"])?"0":$get["id"];
		$list = $this->dati->getlist("pid=".$id." and isdel=0");
		if(count($list)>0){
			//将TITLE改为DATI
			for($i=0;$i<count($list);$i++){
				$list[$i]["dati"] = $list[$i]["title"];
				unset($list[$i]["title"]);
			}
			//去掉头尾[]
			$list = json_encode($list);
			$list = substr($list,1,strlen($list)-2);
		}
		else{
			$list = "";
		}
		echo $list;//htmlspecialchars($list);
	}
	
	function edit(){
		$post = $this->input->post();
		//print_r($post);
		//echo $post["id"];
		//die();	
		if(is_array($post)){
			$model = $this->dati->GetModel($post["id"]);			
			$model["title"]=$post["title"];
			$model["orderby"]=50;				
			$model["beizhu"]=$post["beizhu"];
			$model["isshow"]=$post["isshow"];					
			$model["update_common_system_userid"]=admin_id();
			$model["update_date"]=date("Y-m-d h:i:s");				
			
			//print_r($model);
			//die();
			$this->dati->update($model);
			$backurl = empty($post["backurl"])?base_url()."admin.php/fenzitiku/index.shtml":$post["backurl"];
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
			$data["backurl"] = empty($get["backurl"])?site_url("fenzitiku/index"):$get["backurl"];			
			$model = $this->dati->GetModel($get["id"]);
			$data["model"] = $model;
			$arr = $this->dati->getlist("isdel=0 and pid=".$model["id"]);
			
			if(count($arr)>0){
				for($i=0;$i<count($arr);$i++){
					$arr[$i]["dati"] = $arr[$i]["title"];
					$arr[$i]["subitem"] = array();
					$arr_item = $this->dati_item->getlist("isdel=0 and zzb_jjfz_dati_id=".$arr[$i]["id"]);
					if(count($arr_item)>0){				
						$arr[$i]["subitem"] = $arr_item;
					} 
				}
				//去掉最外层的[]				
				$data["bigitem"] = substr(json_encode($arr),1,strlen(json_encode($arr))-2);				
			}
			else{
				//去掉最外层的[]
				$data["bigitem"] = "";
			}			
			$this->load->view(__TEMPLET_FOLDER__."/fenzitiku/edit",$data);
		}		
	}
	
	function chktitle(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from  zzb_jjfz_dati where isdel=0 and title='$title'".($id>0?" and id<>$id":""));
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

	function chktitle_item(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		if($id<0){
			$pid = abs($id);
		}
		else{
			$model = $this->dati->GetModel($id);
			$pid = $model["pid"]; 
		}
		$model = $this->M_common->query_one("select * from  zzb_jjfz_dati where pid=".$pid." and isdel=0 and title='$title'".($id>0?" and id<>$id":""));
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
				$this->dati->del($v);
			}
		}				
	}

	function additem(){
		$data = array();
		$post = $this->input->post();
		//print_r($post);
		//echo $post["id"];
		//die();
		if(is_array($post)){
			//print_r($post);
			//die();
			$title = $post["title"];
			$seltype = strval($post["seltype"]);
			$subitem = $post["subitem"];
			//转成数组 true转为数组
			$subitem = json_decode($subitem,true);
			//组合JSON，送回主窗口
			$arr["dati"] = $title;
			$arr["seltype"] = $seltype;
			$arr["subitem"] = $subitem;
			$json = json_encode($arr);			
			echo "<script>";
			echo "parent.getitem('$json',".$post["i"].");";
			echo "parent.parent.tip_show('".($post["i"]>=0?"题目修改成功":"题目新增成功")."',2,1000);";
			echo "setTimeout('parent.dialog2.close();',1000);";
			echo "</script>";
		}
		else{	
			$get = $this->input->get();
			$item = empty($get["item"])?"":$get["item"];
			$i = empty($get["i"])?0:$get["i"];
			$arr = array("dati"=>"","seltype"=>"0","subitem"=>"");
			if($item!=""){
				$arr = json_decode($item,true);
			}		
			//echo $item;
			//print_r($arr);
			$data["item"] = $arr;
			$data["i"] = $i;	
			$this->load->view(__TEMPLET_FOLDER__."/fenzitiku/additem",$data);
		}
	}
	
	function edititem(){
		$data = array();
		$post = $this->input->post();
		//print_r($post);
		//echo $post["id"];
		//die();
		if(is_array($post)){
			//print_r($post);
			//die();
			$id = $post["id"];//少于0就是插入且为PID
			if($id>0){
				$model = $this->dati->GetModel($id);
				$model["update_common_system_userid"] = admin_id();
				$model["update_date"] = date("Y-m-d H:i:s");				
			}
			else{
				$model["create_common_system_userid"] = admin_id();
				$model["create_date"] = date("Y-m-d H:i:s");
				$model["pid"] = abs($id);
			}			
			$title = $post["title"];
			$seltype = strval($post["seltype"]);
			$subitem = $post["subitem"];
			$model["title"] = $title;			
			$model["seltype"] = $seltype;
			if($id>0){
			$this->dati->update($model);
			}
			else{
				$id = $this->dati->add($model);
			}
						
			//转成数组 true转为数组
			$subitem = json_decode($subitem,true);
			
			for($i=0;$i<count($subitem);$i++){
				if(isset($subitem[$i]["id"]) && $subitem[$i]["id"]>0){
					//更新
					$subitem[$i]["update_common_system_userid"] = admin_id();
					$subitem[$i]["update_date"] = date("Y-m-d H:i:s");
					$this->dati_item->update($subitem[$i]);
				}
				else{
					//插入
					$subitem[$i]["zzb_jjfz_dati_id"] = $id;
					$subitem[$i]["create_common_system_userid"] = admin_id();
					$subitem[$i]["create_date"] = date("Y-m-d H:i:s");
					$this->dati_item->add($subitem[$i]);
				}
			}
						
			echo "<script>";
			echo "parent.getlist();";
			echo "parent.parent.tip_show('".($id>0?"题目修改成功":"题目新增成功")."',2,1000);";
			echo "setTimeout('parent.dialog2.close();',1000);";
			echo "</script>";
		}
		else{
		$get = $this->input->get();
		$id = empty($get["id"])?"0":$get["id"];
		
		$arr = array();//"id"=>"0","dati"=>"","seltype"=>"0","subitem"=>"");
		$model = array();
		if($id>0){
			$model = $this->dati->GetModel($id);
			$item = $this->dati_item->getlist("isdel=0 and zzb_jjfz_dati_id=".$model["id"]);
			$arr = $item;//json_decode($item,true);
		}
			//echo $item;
			//print_r($arr);
			$data["id"] = $id;			
			$data["model"] = $model;
			$data["subitem"] = $arr;			
			$this->load->view(__TEMPLET_FOLDER__."/fenzitiku/edititem",$data);
		}
	}

	function delsubitem(){
		$get = $this->input->get();
		$id = empty($get["id"])?"0":$get["id"];
		$this->dati_item->del($id);		
	}
	function delitem(){
		$get = $this->input->get();
		$id = empty($get["id"])?"0":$get["id"];
		$this->dati->del($id);
	}

	function history(){
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$get=$this->input->get();
			
		
		$search_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title",true))))) ;
		
		$search = array();
		
		$search_val = array(
				"search_title"=>""
		);
		if(!empty($search_title)){
			$search["title"]= " LIKE '%{$typename}%'";
			$search_val["search_title"] = $typename;
		}
		
		$data = array();
		$orderby["orderby"] = "asc";
		$orderby["id"] = "desc";
		$data = $this->dati->GetInfoList($pageindex,10,$search,$orderby);
		$data["isadd"] = $this->permition_for("fenzitiku","add");
		$data["isdel"] = $this->permition_for("fenzitiku","del");
		$data["search_val"] = $search_val;
		
		$this->load->view(__TEMPLET_FOLDER__."/fenzitiku/history",$data);		
	}
}
?>