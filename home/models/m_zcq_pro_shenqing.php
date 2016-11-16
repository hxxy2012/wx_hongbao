<?php
class m_zcq_pro_shenqing extends M_common {
	private $table = "zcq_pro_shenqing";
	private $tablename = "项目申请表";
	function m_zcq_pro_shenqing(){
		parent::__construct();	
		
	}


	
	function count($where){
        $model = $this->M_common->query_one("select count(*) as dd from " . $this->table . " where $where");
		return $model["dd"];		
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
        return $arr['affect_num'];
    }
	
	function del($id){
		$model = $this->GetModel($id);
		$model["isdel"] = "1";
		$model["del_sysuserid"]=admin_id();
		$model["deltime"]=time();
		$arr = $this->update($model);
		write_action_log(
		$arr['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		($arr['affect_num']>=1?1:0),
		"删除".$this->tablename."：".login_name()."|管理员ID=" . ($arr['affect_num']>=1?"成功":"失败"));				
	}	
	
	function GetInfoList($pageindex,$pagesize,$search,$orderby){
		$this->load->library("common_page");
		$page = $pageindex;//$this->input->get_post("per_page");
		if ($page <= 0) {
			$page = 1;
		}
		$limit = ($page - 1) * $pagesize;
		$limit.=",{$pagesize}";
		$where = ' where t1.isdel=\'0\'  ';
		foreach($search as $k=>$v){
		    if($k=="t1.title"){
		        $where .= " and (";
                $where .= "t1.title ".$v." or t1.qiye_linkman ".$v." or t1.qiye_tel ".$v." or t1.qiye_mobile ".$v;
                $where.= ")" ;
            }
            else {
                $where .= " and " . $k . "" . $v;
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
		$sql_count = "SELECT COUNT(*) AS tt FROM ".$this->table." t1 left join 
		 57sy_common_user t2 on t1.create_userid=t2.uid 
		 left join zcq_pro_type t3 on t1.type_id=t3.id
		 {$where}";
	
		$total = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string2($total, $pagesize, $page);
		$sql = "SELECT t1.*,t2.realname,t2.company,t2.username,t3.title as typename FROM ".$this->table." t1 left join 
		 57sy_common_user t2 on t1.create_userid=t2.uid 
		 left join zcq_pro_type t3 on t1.type_id=t3.id ".$where.$orderby_str . " limit  {$limit}";
		//echo $sql;
		$list = $this->M_common->querylist($sql);
        $status = $this->getstatus();
        foreach($list as $k=>$v){
            $list[$k]["check_status_title"]="";
            foreach($status as $kk=>$vv){
                if($kk==$v["check_status"]){
                    $list[$k]["check_status_title"]=$vv;
                    break;
                }
            }
        }
		$data = array(
				"pager"=>$page_string,
				"list"=>$list
		);
		return $data;
	}


	function getstatus(){
	    $array = array(
            "0"=>"未审",
            "10"=>"通过",
            "20"=>"不通过",
            "99"=>"临时保存"
        );
        return $array;
    }
	


}