<?php
/*
 *user model 文件
 *@author 王建 
 */
class M_zx_chongzhi_log extends M_common {
	private $table = "zx_chongzhi_log";
	private  $tablename = "充值流水记录";
	function M_zx_chongzhi_log(){
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
	$where = ' where t2.isdel=0 ';
	
	foreach($search as $k=>$v){
		if($k=="status"){
			if($v!="-1" && $v!=""){
				$where.= " and ".$k.strval($v);				
			}
		}
		else if($k=="username"){
			$where .= " and (t2.realname like '%".$v."%' or t1.username like '%".$v."%' or t2.tel like '%".$v."%')";	
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
		$orderby_str = " order by uid desc";//默认
	}
	$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t2 left join 
	57sy_common_user t1 on t2.userid=t1.uid 
		 {$where}";
	
	$total = $this->M_common->query_count($sql_count);
	$page_string = $this->common_page->page_string2($total, $pagesize, $page);
	$sql = "SELECT t2.*,t1.username FROM  ".$this->table." t2 left join 
	57sy_common_user t1 on t2.userid=t1.uid 
		 
	{$where} " . $orderby_str . " limit  {$limit}";
	//echo $sql;
	$list = $this->M_common->querylist($sql);
	$data = array(
	"pager"=>$page_string,
	"list"=>$list
	);
	return $data;	
}

	function GetModel($uid){
		$model = $this->M_common->query_one("select * from ".$this->table." where uid=$uid");

		
		return $model;
	}

	
	//插入
	function insert($model){
		//print_r($model);
		//die();
		$arr = $this->M_common->insert_one($this->table,$model);
		$insert_id = 0;
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		$this->tablename."：" . $model["username"] . ($arr['affect_num']>=1?"成功":"失败"));

		
	}
	
	//更新
	function update($model){
		
		$arr = $this->M_common->update_data2($this->table,$model,array("uid"=>$model["uid"]));		
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"更新".$this->tablename."：" . $model["username"] . ($arr['affect_num']>=1?"成功":"失败"));		
		
		
		return $arr['affect_num'];
	}

	function del($uid){
		
		$model = $this->GetModel($uid);
			$model["isdel"]="1";
			$arr = $this->M_common->update_data2($this->table,$model,array("uid"=>$model["uid"]));
			write_action_log(
			$arr['sql'],
			$this->uri->uri_string(),
			login_name(),
			get_client_ip(),
			1,
			"删除".$this->tablename."：" . $model["username"] . "成功");		

	}
	

	

}