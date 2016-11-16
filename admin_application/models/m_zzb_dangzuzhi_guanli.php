<?php
class M_zzb_dangzuzhi_guanli extends M_common {
	private $table = "zzb_dangzuzhi_guanli";
	function M_user(){
		parent::__construct();	
		
	}


	//管理员ID，党组织ID
	function Count($adminid,$dzzid){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where dangzuzhi_id=$dzzid and common_system_userid=$adminid");				
	}

	//获得当前二级管理员所属的党组织
	//参数：管理员ID
	function GetList($adminid){
		$sql = "select * from ".$this->table." where common_system_userid=$adminid";		
		return $this->M_common->querylist($sql);
	}

	//获得当前二级管理员所属的党组织，并且连同下级支部的记录一起返回
	//参数：管理员ID
	function GetGuanLiID($adminid){
		$sql = "select * from ".$this->table." where common_system_userid=$adminid";
		$list = $this->M_common->querylist($sql);
		$guanid = "";		
		foreach($list as $v){
			if($guanid==""){
				$guanid=$v["dangzuzhi_id"];
			}
			else{
				$guanid.=",".$v["dangzuzhi_id"];
			}
		}
		if($guanid!=""){
			$sql = "select * from zzb_dangzuzhi where id in (".$guanid.")";
			$list = $this->M_common->querylist($sql);
			foreach($list as $v){
				if($v["pid"]>0){
					$guanid.=",".$v["pid"];
				}
			}
			
			$sql = "select * from zzb_dangzuzhi where pid in (".$guanid.") or id in (".$guanid.")";
			$guanid = "";
			$list = $this->M_common->querylist($sql);
			foreach($list as $v){
				if($guanid==""){
					$guanid=$v["id"];	
				}	
				else{			
					$guanid.=",".$v["id"];
				}				
			}
			$guanid = explode(",",$guanid);
			$guanid = array_unique($guanid);
			$guanid = implode(",",$guanid);			
		}
		//echo $guanid;
		return $guanid;
	}	
	
	function insert($model){
		//插入前要清空旧数据
		$this->del($model["common_system_userid"]);
		$arr = $this->M_common->insert_one($this->table,$model);
		$insert_id = 0;
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"添加二级管理员中间表：管理员ID" . $model["common_system_userid"] . ($arr['affect_num']>=1?"成功":"失败"));				
	}

	function del($sysid){
		$sql = "delete from ".$this->table." where common_system_userid=$sysid";
		return $this->M_common->del_data($sql);		
	}	

}