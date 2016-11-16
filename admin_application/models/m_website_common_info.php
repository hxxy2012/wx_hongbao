<?php
class M_website_common_info extends M_common {
	private $table = "website_common_info";
	function M_website_category(){
		parent::__construct();	
	}
	
	function GetModel($id){				
		return  $this->M_common->query_one("select * from ".$this->table." where id=$id");	
	}
	function GetList($where){
		return $this->M_common->querylist(
				 "select * from ".$this->table." where $where order by id asc"
				);
	}
	function GetList2($where,$orderby="id desc"){
		return $this->M_common->querylist(
				"select * from ".$this->table." where $where order by ".$orderby
		);
	}	
	//读副表
	function GetTwoModel($id,$tablename){
		//echo "id=$id".",table=".$tablename."<br/>";
		return $this->M_common->query_one("select * from website_model_".$tablename." where aid=$id");
	}
	function GetModelList(){
		return $this->M_common->querylist("select * from ".$this->table." where pid=0 order by id asc");
	}
	function Update($model){
		return $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
	}
	function Insert($model){
		return $this->M_common->insert_one($this->table,$model);
	}

	//插入模型
	function InsertModel($model,$tablename){
		return $this->M_common->insert_one("website_model_".$tablename,$model);
	}
	//更新模型
	function UpdateModel($model,$tablename){
		return $this->M_common->update_data2("website_model_".$tablename,$model,array("aid"=>$model["aid"]));
	}	

	function Del($where){
		return $this->M_common->del_data("delete from ".$this->table." where ".$where);
	}
	
	//删除副表
	function DelTwo($where,$tablename){
		return $this->M_common->del_data("delete from website_model_".$tablename." where ".$where);
	}	
	
	function GetFields($tablename=NULL){
		$fields = $this->M_common->get_fields($tablename==NULL?$this->table:"website_model_".$tablename);
		$main_model = array();
		foreach($fields as $v){
			$main_model[$v] = "";
		}
		return $main_model;
	}
	
	function count($where){
		$sql = "select count(1) as dd from ".$this->table." where ".$where;		
		return $this->M_common->query_count($sql);
	}
	
	function update_sql($sql){
		return $this->M_common->update_data($sql);
	}


	
	
}