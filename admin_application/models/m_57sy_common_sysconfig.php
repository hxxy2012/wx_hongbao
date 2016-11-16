<?php
/*

 */
class M_57sy_common_sysconfig extends M_common {
	private $table = "57sy_common_sysconfig";
	function M_57sy_common_sysconfig(){			
		parent::__construct();		

	}
	
	function GetConfig($key){
		
		$model = $this->M_common->query_one("select value from ".$this->table." where varname='$key'");
		if(!is_array($model)){
			return "";
		}
		else{
			return $model["value"];
		}
	}
	
	
	function getlist(){	
		$sql = "select varname,value from ".$this->table;
		return $this->M_common->querylist($sql);		
	}	
		
	

}