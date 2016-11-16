<?php
class M_swj_register_dsqy_tmp extends M_common {
	private $table = "swj_register_dsqy_tmp";
	private $tablename = "电商企业资料变动审核表";
	function M_swj_register_dsqy(){
		parent::__construct();	
		
	}


	
	function count($where){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where $where");				
	}
	

	
	
	
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	
	function GetModelFromUserID($userid){
		$sql = "select * from ".$this->table." where userid=$userid  order by id desc";
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
	
	function GetInfoList($pageindex,$pagesize,$search,$orderby){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where 1=1 ';
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
			$orderby_str = " order by id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t1 left join 57sy_common_user t2 on t1.userid=t2.uid {$where}";
	
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT t1.*,t2.username,t3.name as companyname FROM ".$this->table." t1 left join 57sy_common_user t2 on t1.userid=t2.uid
 		
		{$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}	
	
	


}