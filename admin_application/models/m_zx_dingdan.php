<?php
class M_zx_dingdan extends M_common {
	private $table = "zx_dingdan";
	private $tablename = "订单";
	function M_zx_dingdan(){
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
		$sql = "select * from ".$this->table." where guid='$id'";
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
		$arr = $this->M_common->update_data2($this->table,$model,array("guid"=>$model["guid"]));
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"更新".$this->tablename."：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));						
	}
	//id:订单号
	function del($id){
		$list = $this->getlist("dingdan_hao='".$id."'");
		foreach($list as $v){
			$model = $this->GetModel($v["guid"]);
			$model["isdel"] = "1";		
			$arr = $this->update($model);
			write_action_log(
			$arr['sql'],
			$this->uri->uri_string(),
			login_name(),
			get_client_ip(),
			($arr['affect_num']>=1?1:0),
			"删除".$this->tablename."：".login_name()."|管理员ID=" . ($arr['affect_num']>=1?"成功":"失败"));				
		}
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
			$where .= " and ".$k."".$v;
		}
		$orderby_str = "";
		if(is_array($orderby)){
			$i=0;
			foreach($orderby as $k=>$v){
				if($i++==0){
					$orderby_str =$k." ".$v;
				}
				else{
					$orderby_str .= ",".$k." ".$v;
				}
				

			}
			if($i>0){
				$orderby_str = " order by ".$orderby_str;
			}
		}
		else{
			$orderby_str = " order by create_time desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." {$where} group by dingdan_hao";
	
		//$total = $this->M_common->query_count($sql_count);
		$total = $this->M_common->querylist($sql_count);
		$total = count($total);
				
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT *,count(guid) as dd FROM ".$this->table." {$where} group by dingdan_hao" . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		for($i=0;$i<count($list);$i++){
			$list[$i]["username"] = "";
			if($list[$i]["userid"]>0){
				$usermodel = $this->M_common->query_one("select * from 57sy_common_user where uid=".$list[$i]["userid"]);
				if(count($usermodel)>0){
					$list[$i]["username"] = ($usermodel["realname"]==""?$usermodel["username"]:$usermodel["realname"]);
				}
			}
		}
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}

	
	


}