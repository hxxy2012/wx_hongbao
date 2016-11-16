<?php
class M_website_model_dangyuandati extends M_common {
	private $table = "website_model_dangyuandati";
	private $tablename = "党员答题";
	function M_zzb_dy_dati_history(){
		parent::__construct();	
		
	}


	
	function count($where){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where $where");				
	}
	

	
	
	function get_zzb_dy_dati_id($where){
		$sql = "select * from ".$this->table." where $where";
		$list = $this->M_common->querylist($sql);
		$zzb_dy_dati_id="";
		if(is_array($list)){
			foreach($list as $v){
				if($zzb_dy_dati_id==""){
					$zzb_dy_dati_id = $v["zzb_dy_dati_id"];
				}
				else{
					$zzb_dy_dati_id .=",".$v["zzb_dy_dati_id"];
				}
			}
		}
		return $zzb_dy_dati_id;
	} 

	function getlist($where){
		$sql = "select * from ".$this->table." where $where";
		return $this->M_common->querylist($sql);
	}	
	
	
	function GetModel($id){
		$sql = "select * from ".$this->table." where aid=$id";
		return  $this->M_common->query_one($sql);
	}
	
	
	
	function del($id){
		$sql = "delete from ".$this->table." where aid in($id)";
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		"删除".$this->tablename."：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));				
	}

	function del2($where){
		$sql = "delete from ".$this->table." where $where";
		//file_put_contents("./aa.html",$sql);
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		"删除".$this->tablename."：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));
	}	
	
	
	
	


	
	


}