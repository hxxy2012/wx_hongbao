<?php
/*

 */
class M_admin_user_session extends M_common {
	private $table = "admin_user_session";
	private $tablename = "后台用户会话表";
	
	function M_zx_user_session(){			
		parent::__construct();	
		
	}
	

	function count($where){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where $where");
	}
		
	function getlist($where){
		$sql = "SELECT * FROM ".$this->table." where $where and isdel=0";		
		return $this->M_common->querylist($sql);
	}
	
	
	function GetModel($sessionid){
		// echo "select * from ".$this->table." where session_id='$sessionid'";exit;
		$model = $this->M_common->query_one("select * from ".$this->table." where session_id='$sessionid'");
		if(!is_array($model)){
			return "";
		}
		else{
			return $model;
		}		
	}
	
	
	function add($model){
		$arr = $this->M_common->insert_one($this->table,$model);
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		"前台",
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"添加".$this->tablename."：". ($arr['affect_num']>=1?"成功":"失败"));
		return $arr['insert_id'];
	}
	
	function update($model){
		$arr = $this->M_common->update_data2($this->table,$model,array("guid"=>$model["guid"]));
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		"前台",
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"更新".$this->tablename."：".($arr['affect_num']>=1?"成功":"失败"));
	}	
	
	function del($where){
		$sql = "delete from ".$this->table." where ".$where;
		$num = $this->M_common->del_data($sql);		
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		"前台",
		get_client_ip(),
		($num>=1?1:0),
		"删除".$this->tablename."：".($num>=1?"成功":"失败"));
	}	
	
	
	

}