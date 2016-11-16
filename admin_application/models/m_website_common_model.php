<?php
class M_website_common_model extends M_common {
	private $table = "website_common_model";
	function M_website_common_model(){
		parent::__construct();	
	}
	
	function GetModel($id){		
		return  $this->M_common->query_one("select * from ".$this->table." where id=$id");	
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
	//取字段列表
	function GetSubList($id){		
		return $this->M_common->querylist(
				 "select * from ".$this->table." where pid=$id order by orderby asc"
				);
	}

	
	//检查字段时否存在,PID=数据表ID，返回数量
	function GetFieldCount($pid,$field){
		return $this->M_common->query_count("select * from ".$this->table." where pid=$pid and field='$field'");
	}	
	function CreateTable($tablename){		
		$fields = array(
				'aid' => array('type' => 'INT'),
				'category_id' => array('type' => 'INT'),
				'category_id2' => array('type' => 'INT'),
				'category_id3' => array('type' => 'INT')
		);
		$tablename = "website_model_".$tablename;				
		$this->M_common->create_table($tablename,$fields);
	}
		
	//不包含前辍
	function IsTable($tablename){
		$sql = "SHOW TABLES LIKE 'website_model_".$tablename."'";
		$this->M_common->query_one($sql);
	}
	
	function CreateCols($tablename,$field){		
		$this->M_common->create_cols($tablename,$field);
	}
	
	//通过字段名、表名(不含前辍)获取，字段属性
	function GetAttrFromField($field,$tablename){
		$sql = "SELECT * FROM `website_common_model` where field='$field' and tablename='$tablename'";			
		return $this->M_common->query_one($sql);
	}
	
	
}