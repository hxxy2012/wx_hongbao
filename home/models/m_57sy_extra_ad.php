<?php
class M_57sy_extra_ad extends M_common {
	private $table = "57sy_extra_ad";
	private $table_name = "广告";
	function M_57sy_extra_ad(){
		parent::__construct();	
		
	}


	
	function count($where){
		$row = $this->M_common->query_one("select count(*) as dd from ".$this->table." where $where");
		return $row["dd"];				
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
		"添加".$this->table_name."：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));
	}	

	function update($model){	 
		$arr = $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"更新".$this->table_name."：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));						
	}
	
	function del($id){
		$this->M_common->del_data("delete from ".$this->table." where id=$id");
		write_action_log(
		"delete from ".$this->table." where id=$id",
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		1,
		"删除".$this->table_name."：".login_name()."");				
	}
	
	function del_where($where){
		$this->M_common->del_data("delete from ".$this->table." where $where");
		write_action_log(
		"delete from ".$this->table." where $where",
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		1,
		"删除".$this->table_name."：".login_name()."");				
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
			if($k=="linkman"){
				$where .= " and ( t1.".$k." like '%".$v."%' or t2.username like '%".$v."%' or t3.name like '%".$v."%') ";
			}	
			else{		
				$where .= " and ".$k."".$v;
			}
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
		$sql = "SELECT t1.*,t2.username FROM ".$this->table." t1 left join 57sy_common_user t2 on t1.userid=t2.uid {$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}
	
	
	function getlist($where,$orderby="id asc",$count=9999){
		return $this->M_common->querylist(
				"select * from ".$this->table." where $where order by ".$orderby." limit ".$count
		);
	}	
	


}