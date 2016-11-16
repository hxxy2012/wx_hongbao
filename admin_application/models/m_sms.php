<?php
class M_sms extends M_common {
	private $table = "zzb_sms";
	function M_sms(){
		parent::__construct();	
		
	}


	
	function Count(){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table);				
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
		"添加短信：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));
	}	

	function update($model){	 
		$arr = $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"短信更新：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));						
	}
	
	function del($id){	
		$sql = "delete from ".$this->table." where id in($id)";
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		"短信删除成功：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));				
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
			if($k=="username"){
				$where .= " and t2.".$k.$v;
			}	
			else{	
				$where .= " and ".$k."=".$v;
			}
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
		$sql_count = "SELECT COUNT(*) AS tt FROM zzb_sms t1 left join 57sy_common_system_user t2 on t1.create_common_system_userid=t2.id {$where}";
	
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT t1.*,t2.username FROM 
		zzb_sms t1 left join 57sy_common_system_user t2 on t1.create_common_system_userid=t2.id
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