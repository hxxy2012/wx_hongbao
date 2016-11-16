<?php

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

class Swj_businesstracking extends MY_Controller {

    function Swj_businesstracking() {
        parent::__construct();
        $this->load->model('M_common');
        $this->load->helper('file');
        $this->load->helper('download');
    }

    function index() {
        // var_dump(decode_data());exit;
        $action = $this->input->get_post("action");
        $action_array = array("show", "ajax_data");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        if ($action == 'show') {
            //查询所有的log表数据，生成select
            $this->load->view(__TEMPLET_FOLDER__ . "/wjgl/swj_businesstracking_list");
        } elseif ($action == 'ajax_data') {
            $this->ajax_data();
        }
    }

    //ajax get data
    private function ajax_data() {

        $this->load->library("common_page");
        $page = intval($this->input->get_post("page"));
        if ($page <= 0) {
            $page = 1;
        }
        $per_page = 20; //每一页显示的数量
        $limit = ($page - 1) * $per_page;
        $limit.=",{$per_page}";
        $where = ' where 1= 1 and isdel=0 '; //查询条件
        // $name = daddslashes(html_escape(strip_tags($this->input->get_post("name")))); //名称
        // $condition = daddslashes(html_escape(strip_tags($this->input->get_post("condition")))); //可视权限
        $tablename = 'swj_businesstracking';
        $where .= " AND `audit` = '1'";//默认显示公开的
        /*if (!empty($name)) {//查询名称
            $where.=" AND `name` LIKE '%{$name}%'";
        }
        if (!empty($condition)) {//查询是否可视
            if ($condition == 2) {//选择了不公开
                $where .= " AND `audit` = '2'";
            } else {
                $where .= " AND `audit` = '1'";
            }
        }*/
        $sql_count = "SELECT COUNT(*) AS tt FROM {$tablename} {$where} ";
        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string($total, $per_page, $page);
        $sql_log = "SELECT * FROM {$tablename} {$where} order by id desc limit  {$limit}";

        $list = $this->M_common->querylist($sql_log);

        echo result_to_towf_new($list, 1, '成功', $page_string);
    }

   
    function download() {
        $id = $_GET['id'] + 0;
        $sql = "select f.filesrc,f.title from swj_businesstracking as m,swj_fujian as f where m.upload_fujian_id=f.id and m.id='$id'";
        $row = $this->M_common->query_one($sql);
        if (!empty($row)) {
            //原文件路径
            $filesrc = $row['filesrc'];
            $title = $row['title'];
            /*$data = file_get_contents($filesrc); // 读文件内容
            force_download($title, $data);*/
            die("<script>window.location.href='/".$filesrc."';</script>");
        } else {
            showmessage("没有文件", "Swj_businesstracking/index", 1, 0);
            exit();
        }
    }



}
