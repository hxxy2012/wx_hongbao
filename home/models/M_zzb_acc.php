<?php
/*

 */
class M_zzb_acc extends M_common {
	private $table = "zzb_acc";
	private $tablename = "微信凭据";
	
	function M_zzb_acc(){			
		parent::__construct();	
		
	}
	
	function GetModel($acc){
	
		$model = $this->M_common->query_one("select * from ".$this->table." where acc='$acc'");
		if(!is_array($model)){
			return "";
		}
		else{
			return $model["value"];
		}		
	}
	//取最近一条
	function GetModel2(){
	
		$model = $this->M_common->query_one("select * from ".$this->table." order by createtime desc");
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
		$arr = $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
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