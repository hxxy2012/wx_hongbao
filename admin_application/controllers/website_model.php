<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
/*
 * 模型管理
 * 2015-1-28
 */
class Website_model extends MY_Controller{
	
	function Website_model(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_website_common_model');
		$this->load->library('MyText');
		$this->role_cache_path =  config_item("role_cache");
	}
	
	function index(){
		$this->load->library("common_page");
		$page = $this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		
		$pagesize = 10; //每一页显示的数量
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where pid=0 ';
		$orderby = " order by id asc";
		$sql_count = "SELECT COUNT(*) AS tt FROM website_common_model {$where}";
		
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT * FROM website_common_model {$where} " . $orderby . " limit  {$limit}";
		$list = $this->M_common->querylist($sql);
		$data = array(
			"pager"=>$page_string,
			"list"=>$list	
		);
		$this->load->view(__TEMPLET_FOLDER__."/website/model/index",$data);
	}
	
	function chkmodelname(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from website_common_model where title='$title'".($id>0?" and id<>$id":""));		
		if(count($model)>0){			
			$data = array("result"=>"0","msg"=>"名字重复");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}
	function add(){
		$post = $this->input->post();
		//die(count($post));
		$data = $post;		
		if(is_array($post)){			
			if($post["id"]>0){
				//更新
				$data["pid"] = "0";
				$result = $this->M_common->update_data2("website_common_model", $data, array("id" => $post["id"]));
			}		
			else{
				//新增
				$data["pid"] = "0";
				$this->M_website_common_model->CreateTable($post["tablename"]);												
				$result = $this->M_common->insert_one("website_common_model",$data);
			}
			write_action_log(
			$result['sql'], 
			$this->uri->uri_string(), 
			login_name(), 
			get_client_ip(), 
			1, 
			"模型：" . $post["title"] . "保存成功");
			//showmessage("修改任务失败", "mokuai/edit?id=$id", 3, 0);
			//header("Location:" . site_url("website_model/index"));
			echo "<script>
					parent.tip_show('保存成功',1,500);
					//取自网址的ID
					top.topManager.reloadPage('133');
					top.topManager.closePage();
				  </script>";
			exit();			
		}
		else{
			$get = $this->input->get();
			$data = array();
			$data["info"] = array("id"=>"","tablename"=>"","title"=>"","content"=>"");
			if(!empty($get["id"])){
				$data["info"] = $this->M_common->query_one("select * from website_common_model where id=".$get["id"]);			
			}			
		}
		$this->load->view(__TEMPLET_FOLDER__."/website/model/add",$data);
	}
	
	//添加字段
	function edit(){
		$data = array();
		$info["title"] = "";
		$info["id"] = "";
		$info["pid"] = "";
		$info["field"] = "";
		$info["field_comment"]="";
		$info["fieldtype"] = "";
		$info["cell_width"] = "120";
		$info["orderby"] = "50";
		$info["inline"] = "";
		$info["field_value"] = "";
		$info["listpage"] = "";
		$info["contentpage"] = "";
		$info["content"] = "";
		$info["tablename"]="";
		$info["isrequired"]="0";
		
			
		
		$get=$this->input->get();
		$parent = array();
		if($get["pid"]>0){			
			$info["pid"] = $get["pid"];
			$parent = $this->M_website_common_model->GetModel($get["pid"]);
			$data["parent"] = $parent;
		}
		else{
			//关闭
			echo "<script>
					parent.tip_show('没有表格',1,500);
					top.topManager.closePage();
				  </script>";			
			exit();
		}
		
		$post = $this->input->post();		
		
		if(is_array($post)){			
					
			if($post["id"]>0){
				
				$info = $this->M_website_common_model->GetModel($post["id"]);
				$info["field"] = $post["field"];
				$info["id"] = $post["id"];
				$info["title"] = $post["title"];
				$info["pid"] =  $post["pid"];
				$info["field_comment"]=$post["field_comment"];
				$info["fieldtype"] = $post["fieldtype"];
				$info["cell_width"] = $post["cell_width"];
				$info["orderby"] = $post["orderby"];
				$info["inline"] = $post["inline"];
				$info["field_value"] = $post["field_value"];
				$info["listpage"] = $post["listpage"];
				$info["contentpage"] = $post["contentpage"];
				$info["isrequired"] = empty($post["isrequired"])?"0":"1";
				$info["content"] = "";
				$info["tablename"]=$parent["tablename"];
								
				$result = $this->M_website_common_model->Update($info);
				write_action_log(
				$result['sql'],
				$this->uri->uri_string(),
				login_name(),
				get_client_ip(),
				1,
				"更新字段：" . $post["title"] . "保存成功");				
			}
			else{	
				$info["title"] = $post["title"];
				$info["field"] = $post["field"];
				$info["pid"] =  $post["pid"];
				$info["field_comment"]=$post["field_comment"];
				$info["fieldtype"] = $post["fieldtype"];
				$info["cell_width"] = $post["cell_width"];
				$info["orderby"] = $post["orderby"];
				$info["inline"] = $post["inline"];
				$info["field_value"] = $post["field_value"];
				$info["listpage"] = $post["listpage"];
				$info["contentpage"] = $post["contentpage"];
				$info["isrequired"] = empty($post["isrequired"])?"0":"1";
				$info["content"] = "";
				$info["tablename"]=$parent["tablename"];
											
				$result = $this->M_website_common_model->Insert($info);
				write_action_log(
				$result['sql'],
				$this->uri->uri_string(),
				login_name(),
				get_client_ip(),
				1,
				"添加字段：" . $post["title"] . "保存成功");	
				//操作数据库
				$datatype = "";
				$constraint = 250;
				switch ($info["fieldtype"]){
					case "6":
						//批量上传图片
						$datatype="TEXT";
						$constraint="9999";
						
						break;
					case "7":
						$datatype="TEXT";
						$constraint="9999";
						break;
					case "8":
						$datatype="TEXT";
						$constraint="9999";						
						break;
					case "9":
						$datatype="INT";
						$constraint="11";						
						break;
					case "10":
						$datatype="INT";
						$constraint="11";
						break;						
					default:
					$datatype = "VARCHAR";	
					$constraint = 250;
					break;
				}
				$field = array(					
					$info["field"] => array(
					'type' => $datatype,
					'constraint' => $constraint,
					'null'=>true
					)																																										
				);
				$this->M_website_common_model->CreateCols(
						"website_model_".$info["tablename"],
						$field
						);
			}	
			echo "<script>
					parent.tip_show('保存成功',1,500);
					top.topManager.closePage();			
				  </script>";
			exit();			
		}
		$data["info"] = $info;
		$this->load->view(__TEMPLET_FOLDER__."/website/model/edit",$data);		
	}
	//添加字段
	function addmodel(){
		$data = array();
		
		$this->load->view(__TEMPLET_FOLDER__."/website/model/addmodel",$data);
	}	
	//检查字段是否重复
	function chkfield(){
		$get=$this->input->get();
		$field = trim($get["param"]);
		$pid = trim($get["pid"]);
		
		//每个表都有固定字段
		$field_arr = array("aid","category_id","category_id2","category_id3");
		
		if(in_array($field,$field_arr)){
			$data = array("result"=>"0","msg"=>"字段重复");
			echo json_encode($data);
			exit();
		}
		
		$count = 0;
		if($field!=""){
			$count = $this->M_website_common_model->GetFieldCount($pid,$field);
		}
		if($count>0){
			$data = array("result"=>"0","msg"=>"字段重复");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();		
	}
	//创建表单  父表ID
	function commonForm(){
		$get = $this->input->get();
		$id = $get["id"];
		$rows = $this->M_website_common_model->GetSubList($id);
		$html = "";		
		for($i=0;$i<count($rows);$i++){
			switch ($rows[$i]["fieldtype"]){
				case 1:					
					$html.=$this->mytext->Create($rows[$i]['id']);
					break;
				default:
					
					break;
			}			
		}
		$data["field"] = $html;
		$this->load->view(__TEMPLET_FOLDER__."/website/model/common_form",$data);
	}
	
}
?>