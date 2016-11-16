<?php
class M_nav extends M_common {
	private $table = "57sy_common_admin_nav";
	private $tablename = "导航菜单";
	function M_nav(){
		
		parent::__construct();	
		
	}


	
	function Count($where){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where $where");				
	}
	
	
	
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	
	function dostatus($id,$status){
		$sql = "update ".$this->table." set status=$status where id in($id)";
		$this->M_common->update_data($sql);
	}
	
	
	function del($id){	
		$sql = "delete from ".$this->table." where id in($id)";
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		$this->tablename."删除成功：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));				
	}	
	
	



}