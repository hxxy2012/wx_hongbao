<?php
class M_zzb_dangzuzhi extends M_common {
	private $table = "zzb_dangzuzhi";
	function M_zzb_dangzuzhi(){
		parent::__construct();	
		
	}


	
	function Count($adminid,$dzzid){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table);				
	}
	
	//查看党组织下，有无党员
	function dzz_count($dzzid){
		$sql = "select * from zzb_dangzuzhi where id in($dzzid) or pid in($dzzid)";
		$list = $this->M_common->querylist($sql);
		$ids = "";
		foreach($list as $v){
			if($ids==""){
				$ids = $v["id"];
			}
			else{
				$ids .=",".$v["id"];
			}			
		}
		if($ids!=""){
			$sql = "select count(*) as dd from 57sy_common_user where isdel=0 and zzb_dangzuzhi_id in($ids)";
			return $this->M_common->query_count($sql);
		}
		else{
			return "0";
		}
	}
	
	//参数：父ID
	function GetList($pid){
		$sql = "select * from ".$this->table." where isdel=0 and pid=$pid
		order by orderby asc
		";		
		return $this->M_common->querylist($sql);
	}	
	
	//任意取党组织
	function getlist2($where){
		$sql = "select * from ".$this->table." where isdel=0 and ".$where." order by orderby asc
		";
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
		"添加党组织：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));
	}	

	function update($model){	 
		$arr = $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"更新党组织：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));						
	}
	
	function del($id){
		$model = $this->GetModel($id);
		$model["isdel"] = "1";
		$model["del_common_system_userid"]=admin_id();
		$model["del_date"]=date("Y-m-d H:i:s");
		$arr = $this->update($model);
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"删除成功：更新党组织：".login_name()."|管理员ID=" . ($arr['affect_num']>=1?"成功":"失败"));				
	}	
	
	function GetInfoList($pageindex,$pagesize,$search,$orderby){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where isdel=0 ';
		foreach($search as $k=>$v){			
			$where .= " and ".$k."=".$v;
		}
		$orderby_str = "";
		if(is_array($orderby)){
			$i=0;
			foreach($orderby as $k=>$v){
				$orderby_str .= "$k $v";
				if($i++>0){
					$orderby_str .=",";
				}
			}
			if($i>0){
				$orderby_str = " order by ".$orderby_str;
			}
		}
		else{
			$orderby_str = " order by orderby asc,id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM zzb_dangzuzhi {$where}";
	
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT * FROM zzb_dangzuzhi {$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		for($i=0;$i<count($list);$i++){
			$sql = "select * from zzb_dangzuzhi where isdel=0 and pid=".$list[$i]["id"];
			$list[$i]["subcount"] = $this->M_common->query_count($sql);
		}
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}	



}