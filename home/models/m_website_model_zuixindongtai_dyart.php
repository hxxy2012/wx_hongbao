<?php
class M_website_model_zuixindongtai_dyart extends M_common {
	private $table = "website_model_zuixindongtai_dyart";
	private $tablename = "动态对联文章一对多";
	function M_website_model_zuixindongtai_dyart(){
		parent::__construct();	
		
	}


	
	function count($where){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where $where");				
	}
	

	function getlist($where){
		$sql = "select * from ".$this->table." where $where";
		return $this->M_common->querylist($sql);
	}	
	
	
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	
	function add($model){
		$arr = $this->M_common->insert_one($this->table,$model);
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"添加".$this->tablename."：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));
		return $arr['insert_id'];	
	}	

	function update($model){	 
		$arr = $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"更新".$this->tablename."：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));						
	}
	
	function del($id){
		$sql = "delete from ".$this->table." where id=".$id;
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		"删除栏目：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));
		return $num;				
	}	
	
	function del2($where){
		$sql = "delete from ".$this->table." where ".$where;
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		"删除栏目：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));
		return $num;
	}	
	
	

	
	


}