<?php

/*
 *user model 文件
 *@author 王建 
 */

/**
 * Class M_user
 * @property Common_upload $common_upload
 * @property Common_page $common_page 分页辅助类别
 *
 * @property CI_Loader $load
 * @property CI_Input $input
 */
class M_user extends M_common
{
    public $table = "57sy_common_user";

    //上传附件的后缀名
    private $fileTypes;

    private $upload_path = "";

    function M_user()
    {
        parent::__construct();

        $this->fileTypes = array('jpg', 'jpeg', 'png');
        $this->upload_path = __ROOT__ . "/data/upload/user/";

        $this->load->library("common_upload");

    }


    function count($where)
    {
        return $this->M_common->query_count("select count(*) as dd from " . $this->table . " where $where");
    }


    function GetInfoList($pageindex, $pagesize, $search, $orderby, $base_url = "")
    {
        $this->load->library("common_page");
        $page = $pageindex;//$this->input->get_post("per_page");
        if ($page <= 0) {
            $page = 1;
        }
        $limit = ($page - 1) * $pagesize;
        $limit .= ",{$pagesize}";
        $where = ' where t1.isdel=0 ';

        foreach ($search as $k => $v) {
            if ($k == "status") {
                if ($v != "-1" && $v != "") {
                    $where .= " and " . $k . strval($v);
                }
            } else if ($k == "username") {
                $where .= " and (t1.username like '%" . $v . "%' or t1.tel like '%" . $v . "%')";
            }else if ($k == "exp"){//表达式
                $where .= " and {$v} ";
            } else {
                $where .= " and " . $k . strval($v);
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
        if (false) {//$base_url==""
            $page_string = $this->common_page->page_string2($total, $pagesize, $page);
        } else {
            $page_string = $this->common_page->page_string_ren($total, $pagesize, $page, $base_url);
        }

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

    /**
     *
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @param $orderby
     * @return array
     */
    function GetUserListByType($pageindex, $pagesize, $search, $orderby, $type)
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
                //服务类型+
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
        $usertype_list = $this->M_common->querylist("SELECT id,name FROM 57sy_common_category_data WHERE typeid='9' ");
        //用户类型
        $servertype_list = $this->M_common->querylist("SELECT id,name FROM 57sy_common_category_data WHERE typeid='7' ");

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
            "list" => $list
        );
        return $data;
    }

    /**
     * 根据用户类型获得用户list
     * @param $pageindex
     * @param $pagesize
     * @param $search
     * @param $typeid
     * @return array
     */
    public function getList($pageindex, $pagesize, $search, $typeid)
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
        $where = " t1.isdel = '0' and t1.usertype='{$typeid}' ";

        foreach ($search as $k => $v) {
            if ($k == "name") {
                $where .= " and ( t1.username like '%{$v}%' )";
            } elseif ($k == "server_type") {
                //服务类型
                //$v是一个数组【46063，45064】
                $str = "";
                foreach ($v as $item){//$item为46063或45064
                    $str .= " t1.server_type like '%{$item}%' and";
                }
                //去掉最后的‘or’
                //$str = substr($str, 0, strlen($str) - 2);
                //去掉最后的‘and’
                $str = substr($str, 0, strlen($str) - 3);

                $where .= " and ({$str}) ";
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

        $sql = "select t1.* from {$this->table} t1 WHERE {$where} limit {$limit}";

        $list = parent::querylist($sql);

        $re = array(
            "pager" => $page_string,
            "list" => $list
        );

        return $re;
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
    public function getList2($pageindex, $pagesize, $search, $orderby = array())
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
        // echo $sql;exit;
        //file_put_contents("e:aa.txt", $sql);
        return $this->M_common->querylist($sql);
    }

    function GetUserListOrderBy($where, $orderby = "t1.uid desc", $limit = 999999)
    {
        $sql = "SELECT t1.*,t2.name as shengfen
				 FROM " . $this->table . " t1 left join 
			57sy_common_category_data t2 on t1.province_id=t2.id
		where $where order by $orderby limit $limit";
        //file_put_contents("e:aa.txt", $sql);
        return $this->M_common->querylist($sql);
    }


    function GetModel($uid)
    {
        $model = $this->M_common->query_one("select * from " . $this->table . " where uid=$uid");
        return $model;
    }

    function GetModelFromUserName($username)
    {
        $model = $this->M_common->query_one("SELECT * FROM " . $this->table . " WHERE username='" . $username . "'");
        return $model;
    }

    function GetModelFromYuMing($yuming)
    {
        $model = $this->M_common->query_one("SELECT * FROM " . $this->table . " WHERE yuming='" . $yuming . "'");
        return $model;
    }

    function GetDangYuan($where)
    {
        return $this->M_common->query_one("select * from 57sy_common_user_dangyuan where $where");
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
        $insert_id = $arr['insert_id'];
        return $insert_id;
    }

    //更新
    function update($model)
    {

        $arr = $this->M_common->update_data2($this->table, $model, array("uid" => $model["uid"]));
        write_action_log(
            $arr['sql'],
            $this->uri->uri_string(),
            login_name(),
            get_client_ip(),
            ($arr['affect_num'] >= 1 ? 1 : 0),
            "前台更新用户：" . $model["company"] . ($arr['affect_num'] >= 1 ? "成功" : "失败")
            . "网址：" . current_url()
        );


        return $arr['affect_num'];
    }

    /**
     * 更新密码
     * @param $id
     * @param $pwd
     * @return
     */
    function updatepwd($id, $pwd)
    {
        //$arr = parent::update_data2($this->table, $model, array("uid" => $model["uid"]));
        $this->db->update($this->table, array("passwd" => $pwd), array("uid" => $id));
        $affacted_rows = $this->db->affected_rows();
        write_action_log(
            $this->db->last_query(),
            $this->uri->uri_string(),
            login_name(),
            get_client_ip(),
            ($affacted_rows >= 1 ? 1 : 0),
            "前台用户：{$id}的密码" . ($affacted_rows >= 1 ? "成功" : "失败")
            . "网址：" . current_url()
        );

        //'insert_id'=>$this->db->insert_id(),

        return $affacted_rows;
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

    /**
     * 上传文件
     * @param string $file_field_name 上传文件的字段名
     * @return mixed|string
     */
    public function isUploadNew($file_field_name)
    {
        if ($_FILES[$file_field_name]['error'] == 0) {
            //重新上传了该文件
            $pic_path = $this->common_upload->upload_path_ym($this->upload_path, $file_field_name, implode("|", $this->fileTypes));
            if (!$pic_path) {

                return 1;
            } else {
                return $pic_path;
            }
        } else {
            //没有重新上传了该文件
            return -1;
        }
    }

    public function chkpwd($id, $pwd)
    {
        $sql = "select * from {$this->table} WHERE uid={$id}";
        $model = parent::query_one($sql);
        if (md5($pwd) == $model['passwd']) {
            return true;
        }
        return false;
    }


    /**
     * 删除旧的图片
     * @param string $filepath
     * @return bool
     */
    public function delPic($filepath)
    {
        if ($filepath) {
            return @unlink(realpath($filepath));
        }
        return false;
    }
}