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

    /**
     * 管理员获得列表
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @param $orderby
     * @return array
     */
    public function getList($pageindex, $pagesize, $search, $orderby)//$admintype
    {
        //分页
        $this->load->library("common_page");
        $page_string = "";
        $page = $pageindex;
        if ($page <= 0) {
            $page = 1;
        }

        //搜索条件
        $id = admin_id();
        $where = " t1.receive_sysuserid={$id} and t1.send_userid=t2.uid  and  t1.isdel_sysuser = '0' ";
        foreach ($search as $k => $v) {
            if ($k == "name") {
                $where .= " and ( t1.title like '%{$v}%' or t2.username like '%{$v}%' or t2.tel like '%{$v}%' or t2.realname like '%{$v}%' or t2.company like '%{$v}%' )";
            } elseif ($k == "isread") {
                $where .= " and  t1.receive_isread = '{$v}' ";
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
        $sql_count = "select count(*) from {$this->table} t1,{$this->usertable} t2 WHERE {$where} {$orderby_str}";
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

        $sql = "select * from {$this->table} t1,{$this->usertable} t2 WHERE {$where} {$orderby_str} limit {$limit}";
        $list = parent::querylist($sql);

        $re = array(
            "pager" => $page_string,
            "list" => $list,
            "sql_total"=>$total//总数
        );

        return $re;
    }

    /**
     * 获得模型
     */
    public function getModel($id)
    {
        $sql = "select * from {$this->table} where id={$id}";
        return parent::query_one($sql);
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