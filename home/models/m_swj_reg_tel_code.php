<?php
class M_swj_reg_tel_code extends M_common {
	private $table = "swj_reg_tel_code";
	private $tablename = "注册时发送手机验证码表";
	function M_swj_reg_tel_code(){
		parent::__construct();	
		
	}


	
	function count($where){
		return $this->M_common->query_count("select * from ".$this->table." where $where");				
	}
	

	
	
	
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	
	function GetModelFromTel($tel){
		$sql = "select * from ".$this->table." where tel='$tel'";		
		return  $this->M_common->query_one($sql);
	}	
	
	function GetModelFromTelCode($tel,$code){
		$sql = "select * from ".$this->table." where tel='$tel' and `code`='$code' ";		
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
		$this->M_common->del_data("delete from ".$this->table." where id=$id");
		write_action_log(
		"delete from ".$this->table." where id=$id",
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		1,
		"删除".$this->tablename."：".login_name()."");				
	}	
	
	
	
	


}