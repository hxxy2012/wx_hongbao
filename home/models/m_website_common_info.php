<?php
class M_website_common_info extends M_common {
	private $table = "website_common_info";
	function M_website_common_info(){
		parent::__construct();	
		
	}


	
	function count($where){		
		$sql = "select count(*) as dd from ".$this->table." where $where";		
		$dd = $this->M_common->query_count($sql);
		return $dd;
	}
	

	function update($model){
		return $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
	}
	
	
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	
	//获取主表与附表完整的信息
	function GetCplModel($id){
		//根据MODEL_ID写出表名，再读附表
		$model = $this->GetModel($id);
		$tname = "";
		if(count($model)>0){
			if($model["common_model_id"]>0){
				$cmodel = $this->M_common->query_one("select * from website_common_model where id=".$model["common_model_id"]);
				$tname = $cmodel["tablename"];
			}
		}
		if($tname!=""){
			$model_fb =  $this->M_common->query_one("select content from website_model_".$tname." where aid=$id");
		}
		
		return array_merge($model, $model_fb);
	}	

	//读副表
	function GetTwoModel($id){
		//根据MODEL_ID写出表名，再读附表
		$model = $this->GetModel($id);
		$tname = "";
		if(count($model)>0){
			if($model["common_model_id"]>0){
				$cmodel = $this->M_common->query_one("select * from website_common_model where id=".$model["common_model_id"]);
				$tname = $cmodel["tablename"];
			}
		}
		if($tname!=""){
			return $this->M_common->query_one("select * from website_model_".$tname." where aid=$id");
		}
		else{
			return "";
		}
	}	
	
	function getlist($where,$orderby,$limit){
		
	}
	
	function getlist_orderby($where,$orderby="istop desc",$limit) {
		$sql = "select * from " . $this->table . " where $where order by ".$orderby." limit ".$limit;
		return $this->M_common->querylist($sql);
	}
	//读出副表,副表字段
	function getlist_orderby2($model_id,$tfield,$where,$orderby="istop desc",$limit) {
		$sql = "select tablename from website_common_model where id='$model_id'";
		$tablemodel = $this->M_common->query_one($sql);
		$tname = "";
		if(count($tablemodel)>0){
			$tname = $tablemodel["tablename"];
		}
				
		$sql = "select t1.*".($tfield!=""?(",".$tfield):"")." from " . $this->table . "
			t1 left join website_model_".$tname." t2 on t1.id=t2.aid where $where order by ".$orderby." limit ".$limit;		
		return $this->M_common->querylist($sql);
	}	
	
	
	function GetInfoList($pageindex,$pagesize,$search,$orderby){
		
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where arcrank=99 ';
	
		foreach($search as $k=>$v){		
			
			if($k=="category_id"){
				$where .= " and (category_id=$v or category_id2=$v or category_id3=$v) ";
			}
			elseif($k=="category_id_in"){
				$where .= " and category_id in($v) ";
			} elseif ($k == 'title') {
				$where .= " and ".$k." like '%".$v."%'";
			} elseif ($k == 'cidnotin') {//不搜索该栏目的文章
				$where .= " and category_id not in($v) ";
			} elseif ($k=='jibie'){
                //级别，公开还是会员可看的
                if ($v=='0') {//公开，只显示游客的
                    $where .= "and jibie = 0 ";
                }else{//会员，显示游客可看和会员可看的
                    $where .= "and (jibie=0 or jibie=1) ";
                }
            }else{
				$where .= " and ".$k."='".$v."'";
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
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." {$where}";
	
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string_ren($total, $pagesize, $page);
		$sql = "SELECT * FROM ".$this->table." {$where} " . $orderby_str . " limit  {$limit}";
		// echo $sql;exit;
		$list = $this->M_common->querylist($sql);
		
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}	
	
	//连副表读出
	//tfield:副表字段
	//base_url如果没有指定，默认自动网址，重写需要指定网址
	function GetInfoList2($model_id,$tfield,$pageindex,$pagesize,$search,$orderby,$base_url=''){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		
		$sql = "select tablename from website_common_model where id='$model_id'";
		$tablemodel = $this->M_common->query_one($sql);		
		$tname = "";
		if(count($tablemodel)>0){
			$tname = $tablemodel["tablename"];
		}
		$sql = "";		
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where t1.arcrank=99 ';
		foreach($search as $k=>$v){
			if($k=="category_id"){
				$where .= " and (t1.category_id=$v or t1.category_id2=$v or t1.category_id3=$v) ";
			}
			elseif($k=="t1.category_id_in"){
				$where .= " and t1.category_id in($v) ";
			} elseif ($k == 'cidnotin') {//不搜索该栏目的文章
				$where .= " and t1.category_id not in($v) ";
			}  elseif ($k == 'title') {
				$where .= " and ".$k." like '%".$v."%'";
			}elseif ($k=='jibie'){
			    //级别，公开还是会员可看的
                if ($v=='0') {//公开，只显示游客的
                    $where .= "and jibie = 0 ";
                }else{//会员，显示游客可看和会员可看的
                    $where .= "and (jibie=0 or jibie=1) ";
                }
            }
			else{
				$where .= " and ".$k."='".$v."'";
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
			$orderby_str = " order by t1.id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t1 left join website_model_".$tname." t2 on t1.id=t2.aid {$where}";
	//echo $sql_count;
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string_ren($total, $pagesize, $page,$base_url);
		$sql = "SELECT t1.*".($tfield==""?"":(",".$tfield))." FROM ".$this->table." t1 left join website_model_".$tname." t2 on t1.id=t2.aid {$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
	
		$data = array(
				"pager"=>$page_string,
				"list"=>$list,
				"all"=>$total
		);
		return $data;
	}	
		
	//连副表读出
	//tfield:副表字段
	//base_url如果没有指定，默认自动网址，重写需要指定网址
	function GetInfoList2WX($model_id,$tfield,$pageindex,$pagesize,$search,$orderby,$base_url=''){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		
		$sql = "select tablename from website_common_model where id='$model_id'";
		$tablemodel = $this->M_common->query_one($sql);		
		$tname = "";
		if(count($tablemodel)>0){
			$tname = $tablemodel["tablename"];
		}
		$sql = "";		
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where t1.arcrank=99 ';
		foreach($search as $k=>$v){
			if($k=="category_id"){
				$where .= " and (t1.category_id=$v or t1.category_id2=$v or t1.category_id3=$v) ";
			}
			elseif($k=="t1.category_id_in"){
				$where .= " and t1.category_id in($v) ";
			} 
			elseif ($k == 'title') {
				$where .= " and ".$k." like '%".$v."%'";
			} 
			elseif ($k == 'cidnotin') {//不搜索该栏目的文章
				$where .= " and t1.category_id not in($v) ";
			} 			
			else{
				$where .= " and ".$k."='".$v."'";
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
			$orderby_str = " order by t1.id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t1 left join website_model_".$tname." t2 on t1.id=t2.aid {$where}";
	//echo $sql_count;
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string_wx($total, $pagesize, $page,$base_url);
		$sql = "SELECT t1.*".($tfield==""?"":(",".$tfield))." FROM ".$this->table." t1 left join website_model_".$tname." t2 on t1.id=t2.aid {$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
	
		$data = array(
				"pager"=>$page_string,
				"list"=>$list,
				"all"=>$total
		);
		return $data;
	}
	
	
	


}