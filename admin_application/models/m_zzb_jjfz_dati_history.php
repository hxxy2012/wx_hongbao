<?php
class M_zzb_jjfz_dati_history extends M_common {
	private $table = "zzb_jjfz_dati_history";
	private $tablename = "分子答题记录";
	function M_zzb_jjfz_dati_history(){
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
		"添加".$this->tablename."：".login_name()."|管理员ID=" . admin_id() . ($arr['affect_num']>=1?"成功":"失败"));
		return $arr['insert_id'];	
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
		$sql = "delete from ".$this->table." where id in($id)";
		$num = $this->M_common->del_data($sql);
		write_action_log(
		$sql,
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($num>=1?1:0),
		"删除".$this->tablename."：".login_name()."|管理员ID=" . ($num>=1?"成功":"失败"));				
	}	
	
	function GetInfoList($pageindex,$pagesize,$search,$orderby){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where t2.isdel=0 ';
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
			$orderby_str = " order by t1.id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t1 left join
		57sy_common_user t2 on t1.common_userid=t2.uid 
		left join zzb_dangzuzhi t3 on t2.zzb_dangzuzhi_id=t3.id
		{$where}";
	
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT t1.*,t2.realname,t2.idcard,t2.tel,t2.username,t3.title as dzz_title FROM ".$this->table." t1 left join
		57sy_common_user t2 on t1.common_userid=t2.uid	left join
		zzb_dangzuzhi t3 on t2.zzb_dangzuzhi_id=t3.id
		{$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		
		return $data;
	}
	
	function GetPaiMing($pageindex,$pagesize,$search){
		//select *,sum(fenshu) as allfenshu from zzb_dangyuan_fenshu group by create_common_userid order by allfenshu desc
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where t2.isdel=0 ';
		foreach($search as $k=>$v){
			if($k=="dzz_title"){
				$where .= " and t3.title".$v;
			}
			elseif($k=="create_date"){
				$where .= " and ".$v;
			}
			else{
				$where .= " and ".$k.$v;
			}
		}
	
		$sql_count = "SELECT count(1) as tt FROM ".$this->table."
		t1 left join 57sy_common_user t2 on t1.common_userid=t2.uid
		left join zzb_dangzuzhi t3 on t2.zzb_dangzuzhi_id=t3.id {$where} group by t1.common_userid
		";
		//echo $sql_count;
		$total = $this->M_common->querylist($sql_count);
		$total = count($total);
		//echo $total;
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT  t1.*,t2.tel,t2.realname,t2.username,count(t1.id) as timu_count,t3.title as dzz_title FROM ".$this->table."
		t1 left join 57sy_common_user t2 on t1.common_userid=t2.uid
		left join zzb_dangzuzhi t3 on t2.zzb_dangzuzhi_id=t3.id {$where} group by t1.common_userid order by timu_count desc limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		$pm = ($pageindex-1)*$pagesize+1;
		for($i=0;$i<count($list);$i++){
			if($i>0){
				if($list[$i]["timu_count"]!=$list[$i-1]["timu_count"]){
					$pm = $i+1;
					$list[$i]["pm"]=$pm;
				}
				else{
					$list[$i]["pm"]=$pm;
				}
			}
			else{
				$list[$i]["pm"]=1;
			}
		}
		$data = array(
		"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}	
	

	//取第一条
	function getfirst(){
		$sql = "select * from ".$this->table." order by id asc limit 1";
		$list = $this->M_common->querylist($sql);
		if(count($list)>0){
			return $list[0];
		}
		else{
			return "";
		}
	}	

	
	


}