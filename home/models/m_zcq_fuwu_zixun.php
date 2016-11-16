<?php

/**
 * 走出去服务咨询模型
 * User: 嘉辉
 * Date: 2016-08-06
 * Time: 9:37
 */

/**
 * Class m_zcq_fuwu_zixun
 * @property CI_URI $uri
 * @property CI_Loader $load
 *
 * @property Common_page $common_page 分页辅助类别
 */
class m_zcq_fuwu_zixun extends M_common
{
    private $table = "zcq_fuwu_zixun";
    private $usertable = "57sy_common_user";
    private $admintable = "57sy_common_system_user";

    /**
     * 获得列表
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @return array
     */
    public function getList($pageindex, $pagesize, $search)//$admintype
    {
        //分页
        $this->load->library("common_page");
        $page_string = "";
        $page = $pageindex;
        if ($page <= 0) {
            $page = 1;
        }

        //搜索条件
        $where = " t1.send_userid=t2.uid  and  t1.isdel_sysuser = '0' ";
//        switch ($admintype){
//            case "admin":
//                $where .= " and  t1.isdel_sysuser = '1' ";
//                break;
//            case "jigou":
//                $where .= " and  t1.isdel_receive = '1' ";
//                break;
//            case "sender":
//                $where .= " and  t1.isdel_send = '1' ";
//                break;
//        }
//        if ($admintype=="admin") {
//        }
        foreach ($search as $k => $v) {
            if ($k == "name") {
                $where .= " and ( t1.title like '%{$v}%' or t2.username like '%{$v}%' or t2.tel like '%{$v}%' or t2.realname like '%{$v}%' or t2.company like '%{$v}%' )";
            }
            if ($k == "isread") {
                $where .= " and  t1.receive_isread = '{$v}' ";
            }
        }

        //结果总数
        $sql_count = "select count(*) from {$this->table} t1,{$this->usertable} t2 WHERE {$where}";
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

        $sql = "select * from {$this->table} t1,{$this->usertable} t2 WHERE {$where} limit {$limit}";
        $list = parent::querylist($sql);

        $re = array(
            "pager" => $page_string,
            "list" => $list
        );

        return $re;
    }

    /**
     * 企业获得列表
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @param array $orderby
     * @param $id
     * @return array
     * @internal param $type
     */
    public function getList_qy($pageindex, $pagesize, $search, $orderby = array(), $id)//$admintype
    {
        //分页
        $this->load->library("common_page");
        $page_string = "";
        $page = $pageindex;
        if ($page <= 0) {
            $page = 1;
        }

        //搜索条件
        $where = " t1.send_userid={$id} and  t1.isdel_send = '0'  ";
        //((t1.receive_userid = t2.uid and t1.receive_sysuserid=0 )  or (t1.receive_sysuserid = t3.id and t1.receive_userid=0))
        foreach ($search as $k => $v) {
            if ($k == "name") {
                $where .= " and ( t1.title like '%{$v}%' or t2.username like '%{$v}%' or t3.username like '%{$v}%' )";
            }
            elseif ($k == "isread") {
                $where .= " and  t1.receive_isread = '{$v}' ";
            }
            else {
                $where .= " and  t1.$k = '{$v}' ";
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
        $table_join = "{$this->table} t1 left join {$this->usertable} t2 on(t1.receive_userid = t2.uid) left join {$this->admintable} t3 on (t1.receive_sysuserid = t3.id)";
        $sql_count = "select count(*) from {$table_join} WHERE {$where} "; //{$this->usertable} t2, {$this->admintable} t3
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

        $sql = "select t1.*,t2.username as name_user,t3.username as name_admin from {$table_join} WHERE {$where} {$orderby_str} limit {$limit}";
        $list = parent::querylist($sql);

        foreach ($list as $k => $v) {
            if ($v['name_user'] == "") {
                //管理员
                $list[$k]['zixun_type'] = "[管理员]";
                $list[$k]['username'] = $v['name_admin'];
            } else {
                //会员
                $list[$k]['zixun_type'] = "[机构]";
                $list[$k]['username'] = $v['name_user'];
            }
        }

        $re = array(
            "pager" => $page_string,
            "list" => $list
        );

        return $re;
    }

    /**
     * 机构获得列表
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @param array $orderby
     * @param $id
     * @return array
     * @internal param $type
     */
    public function getList_jigou($pageindex, $pagesize, $search, $orderby = array(), $id)//$admintype
    {
        //分页
        $this->load->library("common_page");
        $page_string = "";
        $page = $pageindex;
        if ($page <= 0) {
            $page = 1;
        }

        //搜索条件
        $where = " t1.receive_userid={$id} and  t1.isdel_receive = '0'  ";
        //((t1.receive_userid = t2.uid and t1.receive_sysuserid=0 )  or (t1.receive_sysuserid = t3.id and t1.receive_userid=0))
        foreach ($search as $k => $v) {
            if ($k == "name") {
                $where .= " and ( t1.title like '%{$v}%' or t2.username like '%{$v}%' )";
            } elseif ($k == "isread") {
                $where .= " and  t1.receive_isread = '{$v}' ";
            } else {
                $where .= " and  t1.$k = '{$v}' ";
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
        $table_join = "{$this->table} t1 left join {$this->usertable} t2 on(t1.send_userid = t2.uid) ";
        $sql_count = "select count(*) from {$table_join} WHERE {$where} "; //{$this->usertable} t2, {$this->admintable} t3
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

        $sql = "select t1.*,t2.username from {$table_join} WHERE {$where} {$orderby_str} limit {$limit}";
        $list = parent::querylist($sql);

        foreach ($list as $k => $v) {
            $list[$k]['zixun_type'] = "[企业]";
        }

        $re = array(
            "pager" => $page_string,
            "list" => $list
        );

        return $re;
    }

    /**
     * 获得模型
     * @param $id
     * @return
     */
    public function getModel($id)
    {
        $sql = "select * from {$this->table} where id={$id}";
        $model = parent::query_one($sql);
        return $model;
    }

    /**
     * 获得常用的几个机构
     * @param $sender_id
     * @return array
     */
    public function getCommonJigou($sender_id)
    {
        $join_table = " {$this->table} t1 left join {$this->usertable} t2 on( t1.receive_userid = t2.uid ) ";
        $where = " t1.send_userid = {$sender_id} and t1.receive_userid != 0 ";
        $sql = "select count(t1.id) as total,t2.username,t1.receive_userid from {$join_table} where {$where} group by t1.receive_userid ORDER BY total desc LIMIT  3";

        $list = parent::querylist($sql);

        return $list;
        //$sql="select count(t1.id) as total,t2.username,t1.receive_userid from zcq_fuwu_zixun t1 left join 57sy_common_user t2 on( t1.receive_userid = t2.uid ) where t1.send_userid=93 and t1.receive_userid!=0 group by t1.receive_userid ORDER BY total desc LIMIT  3";
    }

    /**
     * 获得常发送的几个管理员
     * @param int $sender_id 发送者id
     * @return array
     */
    public function getCommonAdmin($sender_id){

        $join_table = " {$this->table} t1 left join {$this->admintable} t2 on( t1.receive_sysuserid = t2.id ) ";
        $where = " t1.send_userid = {$sender_id} and t1.receive_sysuserid != 0 ";

        $sql = "select count(t1.id) as total,t2.username,t1.receive_sysuserid from {$join_table} where {$where} group by t1.receive_sysuserid ORDER BY total desc LIMIT  3";

        $list = parent::querylist($sql);
        return $list;

    }


    /**
     * 插入
     * @param $model
     * @return mixed
     */
    function insert($model)
    {
        $arr = parent::insert_one($this->table, $model);
        write_action_log(
            $arr['sql'],
            $this->uri->uri_string(),
            login_name(),
            get_client_ip(),
            ($arr['affect_num'] >= 1 ? 1 : 0),
            "用户{$model["send_userid"]}发布咨询：" . $model["title"] . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
        return $arr["insert_id"];
    }

    /**
     * 更新
     * @param $model
     * @return mixed
     */
    function update($model)
    {

        $arr = parent::update_data2($this->table, $model, array("id" => $model["id"]));
        write_action_log(
            $arr['sql'],
            $this->uri->uri_string(),
            login_name(),
            get_client_ip(),
            ($arr['affect_num'] >= 1 ? 1 : 0),
            "回复服务咨询：" . $model["id"] . ($arr['affect_num'] >= 1 ? "成功" : "失败"));

        return $arr['affect_num'];
    }

    //获取列表有排序
    function GetInfoList($pageindex, $pagesize, $search, $orderby)
    {
        $this->load->library("common_page");
        $page = $pageindex;//$this->input->get_post("per_page");
        if ($page <= 0) {
            $page = 1;
        }
        $limit = ($page - 1) * $pagesize;
        $limit .= ",{$pagesize}";
        $where = ' where t1.isdel_sysuser=\'0\'  ';
        foreach ($search as $k => $v) {
            if ($k == "t1.title") {
                $where .= " and (";
                $where .= "t1.title " . $v . " or t1.qiye_linkman " . $v . " or t1.qiye_tel " . $v . " or t1.qiye_mobile " . $v;
                $where .= ")";
            } else {
                $where .= " and " . $k . "" . $v;
            }
        }
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
        } else {
            $orderby_str = " order by id desc";//默认
        }
        $sql_count = "SELECT COUNT(*) AS tt FROM " . $this->table . " t1 left join 
         57sy_common_user t2 on t1.send_userid=t2.uid 
         {$where}";

        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string2($total, $pagesize, $page);
        $sql = "SELECT t1.* FROM " . $this->table . " t1 left join 
         57sy_common_user t2 on t1.send_userid=t2.uid " . $where . $orderby_str . " limit  {$limit}";
        //echo $sql;
        $list = $this->M_common->querylist($sql);
        $data = array(
            "pager" => $page_string,
            "list" => $list
        );
        return $data;
    }
}