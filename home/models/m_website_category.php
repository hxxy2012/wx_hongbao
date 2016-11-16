<?php
class M_website_category extends M_common {
	private $table = "website_category";
	function M_website_category(){
		parent::__construct();	
	}
	
	function GetModel($id){				
		return  $this->M_common->query_one("select * from ".$this->table." where id=$id");	
	}
	function GetModelList(){
		return $this->M_common->querylist("select * from ".$this->table." where pid=0 order by orderby asc,id asc");
	}

	
	function GetList($where,$orderby,$limit=999999999){		
		return $this->M_common->querylist(
				"select * from ".$this->table." where $where order by $orderby limit $limit"
		);		
	}	
	function GetSubList($id){		
		return $this->M_common->querylist(
				 "select * from ".$this->table." where pid=$id order by orderby asc,id asc"
				);
	}
	function GetSubCount($id){
		return $this->M_common->query_count(
				"select id from ".$this->table." where pid=$id "
		);
	}
	//检查目录是否重复
	function GetAddrCount($id,$addr){
		if($id>0){
			return $this->M_common->query_count("select * from ".$this->table." where addr='$addr' and id<>$id");
		}
		else{
			return $this->M_common->query_count("select * from ".$this->table." where addr='$addr'");
		}
	}

	/*
	 * 
	 * 分页 
	 * pageindex 第一开始
	 * pagesize 分页数
	 * search 数组 search['title'] 标题搜索 search['typeid']栏目ID
	 * orderby 数组 orderby['id']='desc'， orderby['title']='desc'可以同时指定
	 */
	
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
			if(strtolower($k)=="title"){
				$where.= " and t1.".$k." like '%".$v."%' ";
			}
			else if(strtolower($k)=="category_id"){
				$where .= " and concat(',',t2.parent_path,',') like '%,$v,%'";
				$where .= " or t1.category_id=".$v."";
			}
			else{
				$where.= " and t1.".$k." ='".$v."' ";
			}
		}
		
		
		$orderby_str = "";
		if(is_array($orderby)){
			$i=0;
			foreach($orderby as $k=>$v){				
				$orderby_str .= "t1.$k $v";
				if($i++>0){
					$orderby_str .=",";
				}
			}
			if($i>0){
				$orderby_str = " order by ".$orderby_str;
			}
		}
		else{
			$orderby_str = " order by t1.id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM website_common_info t1 left join website_category t2 on t1.category_id=t2.id  {$where}";
		
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT t1.*,t3.username,t2.title as category_name FROM website_common_info t1 
		left join
		website_category t2 on t1.category_id=t2.id left join 
		57sy_common_system_user t3 on t1.create_user=t3.id {$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);					
		return $data;
	}
	

	
	
}