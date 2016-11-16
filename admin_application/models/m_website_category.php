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
		return $this->M_common->querylist("select * from ".$this->table." where pid=0 order by id asc");
	}
	function Update($model){
		return $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
	}
	function Insert($model){
		return $this->M_common->insert_one($this->table,$model);
	}
	
	function GetList($where,$orderby){		
		return $this->M_common->querylist(
				"select * from ".$this->table." where $where order by $orderby"
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
				if(is_numeric($v)){
					$where.= " and (t1.id=".$v. " or t1.".$k." like '%".$v."%' )";
				}
				else{							
					$where.= " and t1.".$k." like '%".$v."%' ";
				}						
			}
			else if(strtolower($k)=="category_id"){
				$where .= " and (concat(',',t2.parent_path,',') like '%,$v,%'";
				$where .= " or t1.category_id=".$v.")";
			}
			else if(strtolower($k)=="category_id_in"){
			    $where .= " and t1.category_id in(".$v.")";
            }
			else{
				if(is_numeric($v)){
					$where.= " and (t1.id=".$v. " or t1.".$k." like '%".$v."%' )";
				}
				else{				
					$where.= " and t1.".$k." ='".$v."' ";
				}
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
		$sql = "SELECT t1.*,t3.username,t2.title as category_name,t2.model_id FROM website_common_info t1 
		left join
		website_category t2 on t1.category_id=t2.id left join 
		57sy_common_system_user t3 on t1.create_user=t3.id {$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		
		foreach($list as $k=>$v){
			if($list[$k]["model_id"]>0){
				$tmpModel = $this->M_common->querylist("select * from website_common_model where id=".$list[$k]["model_id"]);
			}
			else{
				$tmpModel = "";
			}

			if($tmpModel!=""){
				$list[$k]["addpage"] = $tmpModel[0]["addpage"];
				$list[$k]["editpage"] = $tmpModel[0]["editpage"];
				$list[$k]["listpage"] = $tmpModel[0]["listpage"];
			}
			else{
				$list[$k]["addpage"] = "";
				$list[$k]["editpage"] = "";
				$list[$k]["listpage"] = "";				
			}
		}		
		
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);	
		//print_r($data);				
		return $data;
	}
	
	function del($where){
		$sql = "delete from ".$this->table." where ".$where;
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		"删除栏目：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));
		return $num;		
	}

	
	
}