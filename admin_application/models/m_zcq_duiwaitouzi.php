<?php

/**
 * 走出去项目管理模型
 * User: 嘉辉
 * Date: 2016-08-08
 * Time: 14:53
 */

/**
 * Class m_zcq_datamanage
 * @property CI_URI $uri
 * @property CI_Loader $load
 *
 * @property Common_page $common_page 分页辅助类别
 */
class m_zcq_duiwaitouzi extends M_common
{
    public $table = "zcq_duiwaitouzi";
    public $usertable = "57sy_common_user";

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

    /**
     * 获得列表
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @param array $orderby
     * @return array
     */
    public function getList($pageindex, $pagesize, $search, $orderby = array())//$admintype
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
                $where .= " and ( t1.company_name like '%{$v}%' or t1.company_name2 like '%{$v}%' or  t2.username like '%{$v}%' or t2.realname like '%{$v}%' )";
            } elseif ($k == "createtime_start") {
                $time = strtotime($v);
                $where .= " and t1.create_time >= {$time}";
            } elseif ($k == "createtime_end") {
                $time = strtotime($v);
                $where .= " and t1.create_time <= {$time}";
            } elseif ($k == "status") {
                //状态 -1代表选择菜单未选 0未提交 1未审核 2通过 3退回
                if ($v != -1) {
                    $where .= " and t1.check_status = '{$v}' ";
                }
            } elseif ($k == "exp") {
                //表达式
                $where .= " and {$v} ";
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
        $table_join = "{$this->table} t1 left join {$this->usertable} t2 on (t1.create_userid = t2.uid)";
        $sql_count = "select count(*) from $table_join WHERE {$where} ";
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

        $sql = "select t1.*, t2.realname, t2.username from $table_join WHERE {$where} {$orderby_str} limit {$limit}";

        $list = parent::querylist($sql);

        $re = array(
            "pager" => $page_string,
            "list" => $list,
            "sql_total"=>$total
        );

        return $re;
    }

    public function getModel($id)
    {
        $sql = "select * from {$this->table} where id={$id}";
        return parent::query_one($sql);
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
            "添加对外投资联系：" . $model["company_name"] . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
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
            "更新对外投资联系表：" . $model["id"] . ($arr['affect_num'] >= 1 ? "成功" : "失败"));

        return $arr['affect_num'];
    }

    /**
     * 根据公司名字获得用户
     * @param string $company 公司全称或者简称
     * @return
     */
    function getRelatedUser($company)
    {
        $sql = "select * from {$this->usertable} WHERE username='{$company}' or company='{$company}'";
        return parent::query_one($sql);
    }
}
