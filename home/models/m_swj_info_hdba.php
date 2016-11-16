<?php
//活动备案
class M_swj_info_hdba extends M_common {
	private $table = "swj_activity_beian";
	private $tablename = "活动备案表";
	function M_swj_info_hdba(){
		parent::__construct();	
		$this->load->model('M_swj_register_xiehuiorjigou','msrx');//协会或机构模型
	}


	//计算条数
	function count($where){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where $where");				
	}
	

	
	
	//获取该id活动备案
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	function GetList($where){
		$sql = "select * from ".$this->table." $where";
		return  $this->M_common->querylist($sql);
	}
	//通过创建用户的id获取信息
	function GetModelFromUserID($userid){
		$sql = "select * from ".$this->table." where createid=$userid";
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
	//通过活动备案id获取信息通知的详细信息
	function getInfo($id) {
		$where = " where id='$id' ";//该id的信息
		$sql = "SELECT * FROM ".$this->table." {$where}";
		return  $this->M_common->query_one($sql);
	}
	//获取活动备案列表（分页）
	function GetInfoList($pageindex,$pagesize,$search,$orderby){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where isdel=0 and nature=1 and (audit_status>=20 and audit_status!=30) ';
		foreach($search as $k=>$v){//搜索条件
			$where .= " and ".$k."=".$v;
		}
		$orderby_str = "";
		if(is_array($orderby)) {
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
		} else {
			$orderby_str = " order by id desc";//默认
		}
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." {$where}";
	
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string3($total, $pagesize, $page, 3);
		$sql = "SELECT * FROM ".$this->table." {$where} " . $orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
		//循环获取主题图，字段为：themejyid
		foreach ($list as $key => $value) {
			$themejyid = $value['themejyid'];//主题图字段
			$theme_arr = $this->getFile($themejyid);
			if (count($theme_arr) > 0) {//有数据返回
				$list[$key]['img'] = $theme_arr[$themejyid]['filesrc'];
			} else {//显示系统默认的图片
				$list[$key]['img'] = 'data/default/default.jpg';
			}
			//获取该备案活动的名称2016年4月21日17:01:22
			$createid = $value['createid'];//协会id
			$xiehuimodel = $this->msrx->GetModelFromUserID($createid);
			$list[$key]['xiehuiname'] = $xiehuimodel['name'];//组合成活动名称
		}
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}	
	
	//获取活动备案列表（首页显示限制跳数）$limit限制跳数，$search条件，$orderby排序
	function GetInfoLimit($limit,$search,$orderby){
		$this->load->library("common_page");
		$where = ' where isdel=0 and nature=1 and (audit_status>=20 and audit_status!=30) ';//终审通过,未删除，公开
		foreach($search as $k=>$v){//搜索条件
			$where .= " and ".$k."=".$v;
		}
		$orderby_str = "";
		if(is_array($orderby)) {//排序
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
		} else {
			$orderby_str = " order by id desc";//默认
		}
	
		$sql = "SELECT * FROM ".$this->table." {$where} " . $orderby_str . " limit  {$limit}";
		// echo $sql;exit;
		$list = $this->M_common->querylist($sql);
		//循环获取主题图，字段为：themejyid
		foreach ($list as $key => $value) {
			$themejyid = $value['themejyid'];//主题图字段
			$theme_arr = $this->getFile($themejyid);
			if (count($theme_arr) > 0) {//有数据返回
				$list[$key]['img'] = $theme_arr[$themejyid]['filesrc'];
			} else {//显示系统默认的图片
				$list[$key]['img'] = 'data/default/default.jpg';
			}
			//获取该备案活动的名称2016年4月21日17:01:22
			$createid = $value['createid'];//协会id
			$xiehuimodel = $this->msrx->GetModelFromUserID($createid);
			$list[$key]['xiehuiname'] = $xiehuimodel['name'];//组合成活动名称
		}
		return $list;
	}	

	//获取图片的信息,$str,附件表id，以逗号分隔.返回该附件的数组键值为该附件id
	private function getFile($str) {

		$arr = explode(',', $str);
		$rst = array();//返回的数组
		foreach ($arr as $key => $value) {
			$sql = "select * from `swj_fujian` where `id`='$value' limit 0,1";
			$data = $this->M_common->query_one($sql);//查找数据
			if ($data) {//存存数据
				$rst[$value] = $data;
			}
		}
		return $rst;
	}

}