<?php

class M_zx_brand_proclass extends M_common {

    private $table = "zx_brand_proclass";
    private $tablename = "品牌产品分类表";

    function M_zx_brand_proclass() {
        parent::__construct();
    }

    function count($where) {
        return $this->M_common->query_count("select count(*) as dd from " . $this->table . " where $where");
    }

    function getlist($where) {
        $sql = "select * from " . $this->table . " where $where";
        return $this->M_common->querylist($sql);
    }

    function GetModel($id) {
        $sql = "select * from " . $this->table . " where id='$id'";
        return $this->M_common->query_one($sql);
    }

    function add($model) {
        $arr = $this->M_common->insert_one($this->table, $model);
        write_action_log(
                $arr['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), ($arr['affect_num'] >= 1 ? 1 : 0), "添加" . $this->tablename . "：" . login_name() . "|管理员ID=" . admin_id() . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
        return $arr['insert_id'];
    }

    function update($model) {
        $arr = $this->M_common->update_data2($this->table, $model, array("id" => $model["id"]));
        write_action_log(
                $arr['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), ($arr['affect_num'] >= 1 ? 1 : 0), "更新" . $this->tablename . "：" . login_name() . "|管理员ID=" . admin_id() . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
    }

    function del($id) {
        $list = $this->getlist("id='" . $id . "'");
        foreach ($list as $v) {
            $model = $this->GetModel($v["id"]);
            $model["isdel"] = "1";
              $model["del_time"] =  date('Y-m-d H:i:s', time());
            $arr = $this->update($model);
            write_action_log(
                    $arr['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), ($arr['affect_num'] >= 1 ? 1 : 0), "删除" . $this->tablename . "：" . login_name() . "|管理员ID=" . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
        }
    }

    function GetInfoList($pageindex, $pagesize, $search, $orderby) {
        $this->load->library("common_page");
        $page = $pageindex; //$this->input->get_post("per_page");
        if ($page <= 0) {
            $page = 1;
        }
        $limit = ($page - 1) * $pagesize;
        $limit.=",{$pagesize}";
        $where = ' where t1.isdel=0 ';

        foreach ($search as $k => $v) {
            if ($k == "title") {
                $where .= " and (t1.title like '%" . $v . "%' )";
            }elseif($k=='brandid'){
                $where .= " and (t1.brandid ='{$v}' )";
            } else if($k=='pid'){
                $where .= " and (t1.pid ='{$v}') ";
            }
        }


        $orderby_str = "";
        if (is_array($orderby)) {
            $i = 0;
            foreach ($orderby as $k => $v) {
                $orderby_str .= "$k $v";
                if ($i++ > 0) {
                    $orderby_str .=",";
                }
            }
            if ($i > 0) {
                $orderby_str = " order by " . $orderby_str;
            }
        } else {
            $orderby_str = " order by uid desc"; //默认
        }
        $sql_count = "SELECT COUNT(*) AS tt FROM " . $this->table . " t1 
		 {$where}";

        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string2($total, $pagesize, $page);
        $sql = "SELECT t1.* FROM " . $this->table . " t1  
		 
	{$where} " . $orderby_str . " limit  {$limit}";
        //echo $sql;
        $list = $this->M_common->querylist($sql);
        $data = array(
            "pager" => $page_string,
            "list" => $list
        );
        return $data;
    }

}

?>