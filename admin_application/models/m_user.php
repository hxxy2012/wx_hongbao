<?php

/*
 *user model 文件
 *@author 王建 
 */

class M_user extends M_common
{
    private $table = "57sy_common_user";

    function M_user()
    {
        parent::__construct();

    }

    //生成表
    function build_table($table = '')
    {
        $sql_table = <<<EOT
CREATE TABLE IF NOT EXISTS `$table` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;		
EOT;
        $query = $this->db->query($sql_table);
    }


    function GetInfoList($pageindex, $pagesize, $search, $orderby)
    {
        $this->load->library("common_page");
        $page = $pageindex;//$this->input->get_post("per_page");
        if ($page <= 0) {
            $page = 1;
        }
        $limit = ($page - 1) * $pagesize;
        $limit .= ",{$pagesize}";
        $where = " where t1.isdel=0 ";

        foreach ($search as $k => $v) {
            if ($k == "status") {
                if ($v != "-1" && $v != "") {
                    $where .= " and " . $k . strval($v);
                }
            } else if ($k == "username") {
                $where .= " and ( t1.username like '%" . $v . "%' or t1.tel like '%" . $v . "%' or t1.realname like '%" . $v . "%' or t1.company like '%" . $v . "%')";
            } else if ($k == "selcheck") {
                //checkstatus字段：0:未审,1:已审,99:审核不通过
                $where .= " and checkstatus=" . strval("'{$v}'");
            } else if ($k == "server_type") {
                //服务类型
                //必须是机构用户
                $where .= " and usertype='45064' and server_type=" . strval("'{$v}'");
            } else {
                //usertype用户类型
                $where .= " and {$k}=" . strval($v);
            }
        }


        $orderby_str = "";
        if (is_array($orderby)) {
            $i = 0;
            foreach ($orderby as $k => $v) {
                $orderby_str .= "$k $v";
                if ($i++ > 0) {
                    $orderby_str .= ",";
                }
            }
            if ($i > 0) {
                $orderby_str = " order by " . $orderby_str;
            }
        } else {
            $orderby_str = " order by uid desc";//默认
        }
        $sql_count = "SELECT COUNT(*) AS tt FROM " . $this->table . " t1 	
	
		 {$where}";

        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string2($total, $pagesize, $page);
        $sql = "SELECT 
t1.*
FROM " . $this->table . " t1   
	
	
	{$where} " . $orderby_str . " limit  {$limit}";
        //echo $sql;
        $list = $this->M_common->querylist($sql);

        //读出用户类型
        $usertype_list = $this->M_common->querylist("select id,name from 57sy_common_category_data where typeid='9' ");
        //用户类型
        $servertype_list = $this->M_common->querylist("select id,name from 57sy_common_category_data where typeid='7' ");

        foreach ($list as $k => $v) {
            $list[$k]["usertype_title"] = "-";
            $list[$k]["audit"] = $list[$k]["checkstatus"];
            if ($list[$k]["audit"] == "0") {
                $list[$k]["audit_color"] = "#cccccc";
                $list[$k]["audit_title"] = "未审";
            } else if ($list[$k]["audit"] == "1") {
                $list[$k]["audit_color"] = "#8FCC33";
                $list[$k]["audit_title"] = "通过";
            } else if ($list[$k]["audit"] == "99") {
                $list[$k]["audit_color"] = "red";
                $list[$k]["audit_title"] = "不通过";
            } else {
                $list[$k]["audit_color"] = "#DD6D22";
                $list[$k]["audit_title"] = "未完善资料";
                $list[$k]["name"] = "-";
            }
            //用户类型
            foreach ($usertype_list as $kk => $vv) {
                if ($vv["id"] == $v["usertype"]) {
                    $list[$k]["usertype_title"] = $vv["name"];
                    break;
                }
            }
            //服务类型
            foreach ($servertype_list as $item) {
            }
        }
        $data = array(
            "pager" => $page_string,
            "list" => $list,
            "sql_total" => $total
        );
        return $data;
    }

    /**
     * 根据用户类型获得用户list
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @param array $orderby
     * @return array
     * @internal param $typeid
     */
    public function getList($pageindex, $pagesize, $search, $orderby = array())
    {
        //分页
        $this->load->library("common_page");

        $page_string = "";
        $page = $pageindex;
        if ($page <= 0) {
            $page = 1;
        }

        //搜索条件
        //t1.userid=t2.uid  and
        $where = " t1.isdel = '0' ";

        foreach ($search as $k => $v) {
            if ($k == "name") {
                $where .= " and ( t1.username like '%{$v}%' )";
            } elseif ($k == "server_type") {
                //服务类型
                //$v是一个数组【46063，45064】
                $str = "";
                foreach ($v as $item) {//$item为46063或45064
                    $str .= " t1.server_type like '%{$item}%' and";
                }
                //去掉最后的‘or’
                //$str = substr($str, 0, strlen($str) - 2);
                //去掉最后的‘and’
                $str = substr($str, 0, strlen($str) - 3);

                $where .= " and ({$str}) ";
            } else {
                $where .= " and {$k} = {$v} ";
            }
        }

        //排序
        $orderby_str = "";
        if (is_array($orderby)) {
            $i = 0;
            foreach ($orderby as $k => $v) {
                if ($i++ == 0) {
                    $orderby_str = $k . " " . $v;
                } else {
                    $orderby_str .= "," . $k . " " . $v;
                }
            }
            if ($i > 0) {
                $orderby_str = " order by " . $orderby_str;
            }
        }

        //结果总数
        $sql_count = "select count(*) from {$this->table} t1 WHERE {$where}";
        $total = parent::query_count($sql_count);

        //如果page大于总页数 floor舍去法取整(向下取整)
        $totalPage = floor(($total + $pagesize - 1) / $pagesize);
        if ($totalPage != 0) {
            //$total为0的时候，$totalPage也为0
            if ($page > $totalPage) {
                //设置为最大页数
                $page = $totalPage;
            }
        }
        $page_string = $this->common_page->page_string2($total, $pagesize, $page);
        $limit = ($page - 1) * $pagesize;
        $limit .= ",{$pagesize}";

        $sql = "select t1.* from {$this->table} t1 WHERE {$where} {$orderby_str} limit {$limit}";

        $list = parent::querylist($sql);

        $re = array(
            "pager" => $page_string,
            "list" => $list
        );

        return $re;
    }

    function GetUserList($where)
    {
        $sql = "SELECT * FROM " . $this->table . " where $where";
        return $this->M_common->querylist($sql);
    }

    function GetBrand()
    {
        $sql = "select * from zx_brand where isdel=0 order by title asc";
        return $this->M_common->querylist($sql);
    }


    function GetModel($uid)
    {
        $model = $this->M_common->query_one("select * from " . $this->table . " where uid=$uid");
        return $model;
    }


    //插入
    function insert($model)
    {
        //print_r($model);
        //die();
        $arr = $this->M_common->insert_one($this->table, $model);
        $insert_id = 0;
        write_action_log(
            $arr['sql'],
            $this->uri->uri_string(),
            login_name(),
            get_client_ip(),
            ($arr['affect_num'] >= 1 ? 1 : 0),
            "添加用户：" . $model["username"] . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
        return $arr["insert_id"];

    }

    //更新
    function update($model)
    {

        $arr = $this->M_common->update_data2($this->table, $model, array("uid" => $model["uid"]));

        $info = isset($model["username"]) ? $model["username"] : $model["uid"];

        write_action_log(
            $arr['sql'],
            $this->uri->uri_string(),
            login_name(),
            get_client_ip(),
            ($arr['affect_num'] >= 1 ? 1 : 0),
            "用户：" . $info . ($arr['affect_num'] >= 1 ? "成功" : "失败"));


        return $arr['affect_num'];
    }

    function del($uid)
    {

        $model = $this->GetModel($uid);
        $model["isdel"] = "1";
        $arr = $this->M_common->update_data2($this->table, $model, array("uid" => $model["uid"]));
        write_action_log(
            $arr['sql'],
            $this->uri->uri_string(),
            login_name(),
            get_client_ip(),
            1,
            "删除用户：" . $model["username"] . "成功");

    }

    //获取限制的用户信息(不分页)
    function GetLimitList($limit, $search, $orderby)
    {
        $where = " where t1.isdel=0 and (t2.audit=0 or t3.audit=0) ";

        foreach ($search as $k => $v) {
            $where = " and $k=" . $v;
        }


        $orderby_str = "";
        if (is_array($orderby)) {
            $i = 0;
            foreach ($orderby as $k => $v) {
                $orderby_str .= "$k $v";
                if ($i++ > 0) {
                    $orderby_str .= ",";
                }
            }
            if ($i > 0) {
                $orderby_str = " order by " . $orderby_str;
            }
        } else {
            $orderby_str = " order by uid desc";//默认
        }
        $sql_count = "SELECT COUNT(*) AS tt FROM " . $this->table . " t1 	 left join 
		swj_register_dsqy t2 on t1.uid=t2.userid 
		 left join 
		swj_register_xiehuiorjigou t3 on t1.uid=t3.userid
			 {$where}";

        $total = $this->M_common->querylist($sql_count);
        $total = $total[0]["tt"];
        $sql = "SELECT 
	t1.*,
	IFNULL(t2.name,t3.name) as `name`,
	IFNULL(t2.audit,t3.audit) as `audit`,
	IFNULL(t2.name,t3.name) AS danwei,
	IFNULL(t2.updatetime,t3.updatetime) as updatetime
	FROM " . $this->table . " t1   
		 left join 
		swj_register_dsqy t2 on t1.uid=t2.userid 
		 left join 
		swj_register_xiehuiorjigou t3 on t1.uid=t3.userid  
		
		{$where} " . $orderby_str . " limit  {$limit}";
        // echo $sql;exit;
        $list = $this->M_common->querylist($sql);
        //读出用户类型
        $usertype_list = $this->M_common->querylist("select id,name from 57sy_common_category_data where typeid='9' ");


        foreach ($list as $k => $v) {
            $list[$k]["usertype_title"] = "-";
            if ($list[$k]["audit"] == "0") {
                $list[$k]["audit_color"] = "#cccccc";
                $list[$k]["audit_title"] = "未审";
            } else if ($list[$k]["audit"] == "10") {
                $list[$k]["audit_color"] = "#8FCC33";
                $list[$k]["audit_title"] = "通过";
            } else if ($list[$k]["audit"] == "20") {
                $list[$k]["audit_color"] = "red";
                $list[$k]["audit_title"] = "不通过";
            } else {
                $list[$k]["audit_color"] = "#DD6D22";
                $list[$k]["audit_title"] = "未完善资料";
                $list[$k]["name"] = "-";
            }
            foreach ($usertype_list as $kk => $vv) {
                if ($vv["id"] == $v["usertype"]) {
                    $list[$k]["usertype_title"] = $vv["name"];
                    break;
                }
            }
        }
        $data = array(
            "total" => $total,
            "list" => $list
        );
        return $data;
    }

    /**
     * 获得表的字段
     * @return mixed
     */
    public function getFields()
    {
        $fields = parent::get_fields($this->table);
        $model = array();
        foreach ($fields as $item) {
            $model[$item] = "";
        }
        return $model;
    }
}