<?php
class M_common_category_data extends M_common {
	private $table = "57sy_common_category_data";
	function M_common_category_data(){
		parent::__construct();
	}
	
	function GetModel($id){
		return  $this->M_common->query_one("select * from ".$this->table." where id=$id");
	}
	function GetModelList($pid=0){
		return $this->M_common->querylist("select * from ".$this->table." where pid=$pid order by disorder asc");
	}
	function GetModelList_orderby($typeid,$pid=0,$orderby='disorder asc'){
		return $this->M_common->querylist("select * from ".$this->table." where status=1 and typeid=$typeid and pid=$pid order by $orderby");
	}	
	function Update($model){
		return $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
	}
	function Insert($model){
		return $this->M_common->insert_one($this->table,$model);
	}	
	
	
	function GetUserType(){
		return $this->M_common->querylist("select * from ".$this->table." where typeid=9 order by disorder asc");
	}
	
	function GetListFromTypeidAndPid($typeid,$pid){
		return $this->M_common->querylist("select * from ".$this->table." where typeid=$typeid and pid='$pid' order by disorder asc");
	}
	
	function GetList2($where){
		return $this->M_common->querylist("select * from ".$this->table." where 
		$where order by disorder asc");
	}
	
	//根据子级返回父级
	function GetParentFromSub($subid){
		$list = $this->M_common->querylist("select * from ".$this->table." where 
		id in($subid) order by disorder asc");		
		$pid = array();
		if(count($list)>0){
			foreach($list as $v){
				$pid[] = $v["pid"];
			}
		}
		if(count($pid)>0){		
			$pid = array_unique($pid);
			return implode(",",$pid);
		}
		else{
			return "";
		}
	}

    /**
     * 获得服务类型列表
     * @return mixed
     */
    function GetServerType(){
        return $this->M_common->querylist("select * from " . $this->table . " where typeid=7 order by disorder asc");
    }
}
?>