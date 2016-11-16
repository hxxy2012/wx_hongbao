<?php
//信息通知模型
class M_swj_info_pub extends M_common {
	private $table = "website_common_info";
	private $tablename = "信息通知表";
	function M_swj_info_pub(){
		parent::__construct();	
		
	}


	//计算条数
	function count($where){
		return $this->M_common->query_count("
		select count(*) as dd from ".$this->table." where $where ");				
	}
	

	
	
	//获取该id信息通知
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	//通过创建用户的id获取信息
	function GetModelFromUserID($userid){
		$sql = "select * from ".$this->table." where create_user=$userid";
		return  $this->M_common->query_one($sql);
	}	
	
	//添加
	function add($model){
		$arr = $this->M_common->insert_one($this->table,$model);
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"添加".$this->tablename."：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));
		return $arr["insert_id"];
	}	

	//更新
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
	//删除
	function del($id,$userid){
		$this->M_common->del_data("delete from ".$this->table." where id=$id and create_user=$userid");
		write_action_log(
		"delete from ".$this->table." where id=$id",
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		1,
		"删除".$this->tablename."：".login_name()."");				
	}	
	//通过信息通知id获取信息通知的详细信息
	function getInfo($id) {
		$where = " where t1.id='$id' ";//该id的信息
		$sql = "SELECT t1.*,t2.content FROM ".$this->table." t1 
				left join website_model_art t2 on t1.id=t2.aid {$where}";
		return  $this->M_common->query_one($sql);
	}
	//获取通知信息列表（分页）
	function GetInfoList($pageindex,$pagesize,$search,$orderby){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where 1=1 ';//可视权限为公开
		foreach($search as $k=>$v){//搜索条件
			$where .= " and t1.".$k."=".$v;
		}
		$orderby_str = "";
		if(is_array($orderby)) {
			$i=0;
			foreach($orderby as $k=>$v){
				if($i++==0){
					$orderby_str =$k." ".$v;
				}
				else{
					$orderby_str .= ",t1.".$k." ".$v;
				}
				
			}
			if($i>0){
				$orderby_str = " order by t1.".$orderby_str;
			}
		} else {
			$orderby_str = " order by t1.id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t1 {$where}";
	
		$total = $this->M_common->query_count($sql_count);
		// echo $total.','.$pagesize.','.$page;exit;
		$page_string = $this->common_page->page_string3($total, $pagesize, $page, 3);
		// $sql = "SELECT * FROM ".$this->table." {$where} " . $orderby_str . " limit  {$limit}";
		$sql = "SELECT t1.*,t2.content FROM ".$this->table." t1 
				left join website_model_art t2 on t1.id=t2.aid
				{$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}	
	
	//获取通知信息列表（首页显示限制跳数）$limit限制跳数 为0代表不限制，$search条件，$orderby排序
	function GetInfoLimit($limit,$search,$orderby){
		$this->load->library("common_page");
		$where = ' where 1=1 ';//可视权限为公开
		foreach($search as $k=>$v){//搜索条件
			$where .= " and t1.".$k."=".$v;
		}
		$orderby_str = "";
		if(is_array($orderby)) {//排序
			$i=0;
			foreach($orderby as $k=>$v){
				if($i++==0){
					$orderby_str =$k." ".$v;
				}
				else{
					$orderby_str .= ",t1.".$k." ".$v;
				}
				
			}
			if($i>0){
				$orderby_str = " order by t1.".$orderby_str;
			}
		} else {
			$orderby_str = " order by t1.id desc";//默认
		}
		//限制条数
		if ($limit <= 0) {
			$limit_str = '';
		} else {
			$limit_str = " limit {$limit}";
		}
		
		// $sql = "SELECT * FROM ".$this->table." {$where} " . $orderby_str . " limit  {$limit}";
		$sql = "SELECT t1.*,t2.content FROM ".$this->table." t1 
				left join website_model_art t2 on t1.id=t2.aid
				{$where} " . $orderby_str . " {$limit_str}";
		// echo $sql;
		$list = $this->M_common->querylist($sql);
		
		return $list;
	}	


}