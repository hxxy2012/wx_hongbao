<?php
class M_user_edit_hisotry extends M_common {
	private $table = "57sy_common_user_edit_hisotry";
	function M_user_edit_hisotry(){
		parent::__construct();	
		
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
		if($k=="username3"){
			if($v!=""){
				$where.= " and t3.".$k.strval($v);
			}
		}
		else if($k=="username"){
			$where.= " and t2.username"
					.strval($v);
		}
		else if($k=="realname"){
			$where.= " and t2.".$k.strval($v);
		}
		else if($k=="zzb_dangzuzhi_id"){
			$where.= " and t2.zzb_dangzuzhi_id".strval($v);
		}		
		else{
			$where.= " and ".$k.strval($v);
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
		$orderby_str = " order by editdate desc";//默认
	}
	$sql_count = "SELECT COUNT(*) AS tt FROM  
`57sy_common_user_edit_hisotry` t1 left join 57sy_common_user t2 on t1.common_user_id=t2.uid
left join 57sy_common_system_user t3 on t1.common_system_user=t3.id
	 {$where}";
	
	$total = $this->M_common->query_count($sql_count);
	$page_string = $this->common_page->page_string2($total, $pagesize, $page);
	$sql = "SELECT t1.*,t2.username as username2,t2.realname as realname2,t2.idcard as idcard2,t3.username as username3 FROM 
`57sy_common_user_edit_hisotry` t1 left join 57sy_common_user t2 on t1.common_user_id=t2.uid
left join 57sy_common_system_user t3 on t1.common_system_user=t3.id
	
	{$where} " . $orderby_str . " limit  {$limit}";
	//echo $sql;
	$list = $this->M_common->querylist($sql);
	$data = array(
	"pager"=>$page_string,
	"list"=>$list
	);
	return $data;	
}

	function insert($model){
		$arr = $this->M_common->insert_one($this->table,$model);
		$insert_id = 0;
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"添加信息变更记录：前台用户ID" . $model["common_user_id"] . ($arr['affect_num']>=1?"成功":"失败"));		
	}

	


}