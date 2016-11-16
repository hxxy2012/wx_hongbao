<?php
class M_common_system_user extends M_common {
	private $table = "57sy_common_system_user";
	function M_common_system_user(){
		parent::__construct();	
	}

	
	function GetModel($id){
		//echo "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one("select * from ".$this->table." where id=$id");
	}	
	
	function insert($model){		
		$arr = $this->M_common->insert_one($this->table,$model);
		$insert_id = 0;
		$insert_id = $arr["insert_id"];
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"添加管理员：" . $model["username"] . ($arr['affect_num']>=1?"成功":"失败"));
		return $insert_id;
	}
	
	function update($model){
		$arr = $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));		
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"更新管理员：" . $model["username"] . ($arr['affect_num']>=1?"成功":"失败"));
		return 1;
	}	

	function count($where){
		$sql = "select count(*) as dd from ".$this->table." where ".$where;
		return $this->M_common->query_count($sql);	
	}
	
	function del($id){
		$model = $this->GetModel($id);
		$model["status"]=-1;
		$model["username"] = $model["username"]."_del".date("Y-m-d H:i:s");
		$arr = $this->M_common->update_data2($this->table,$model,array("id"=>$model["id"]));
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"删除管理员：" . $model["username"] . ($arr['affect_num']>=1?"成功":"失败"));
		return 1;		
	}



    function GetInfoList($pageindex,$pagesize,$search,$orderby){
        $this->load->library("common_page");
        $page = $pageindex;//$this->input->get_post("per_page");
        if ($page <= 0) {
            $page = 1;
        }
        $limit = ($page - 1) * $pagesize;
        $limit.=",{$pagesize}";
        $where = " where 1=1 ";

        foreach($search as $k=>$v){
            if($k=="status"){
                if($v!="-1" && $v!=""){
                    $where.= " and ".$k.strval($v);
                }
            }
            else if($k=="username"){
                $where .= " and ( t1.username like '%".$v."%' )";
            }else if ($k=="selcheck"){
                //checkstatus字段：0:未审,1:已审,99:审核不通过
                $where .= " and checkstatus=".strval("'{$v}'");
            }else if ($k=="server_type"){
                //服务类型
                //必须是机构用户
                $where .= " and usertype='45064' and server_type=".strval("'{$v}'");
            }
            else{
                //usertype用户类型
                $where.= " and {$k}=".strval($v);
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
        $sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t1 	
	
		 {$where}";

        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string2($total, $pagesize, $page);
        $sql = "SELECT 
t1.*
FROM ".$this->table." t1   
	
	
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
?>