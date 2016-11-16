<?php

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

class Tongji_detail extends MY_Controller {

    public $upload_path = '';
    public $upload_save_url = '';
    public $upload_path_sys = '';
    //表字段
    public $title = "";
    public $fujian = "";
    public $jihua_start_time = "";
    public $jihua_end_time = "";
    public $jihua_hours = 0;
    public $mokuai_status = "";
    public $mokuai_status_title = "";
    public $pid = 0;
    public $jinji = 0;
    public $jinji_title = "";
    public $mokuai_userid = 0;
    public $mokuai_username = "";
    public $mokuai_flag = 0;
    public $mokuai_flag_name = "";
    public $start_time = "";
    public $end_time = "";
    public $dump_header = "";
    public $export = "";
    public $need_test = "";
    public $mokuai_test_userid = "";
    public $mokuai_test_username = "";

    function Tongji_detail() {
        parent::__construct();
        $this->load->model('M_common', '', false, array('type' => 'real_data'));
        $this->cache_category_path = config_item("category_modeldata_cache");
        $this->upload_path = __ROOT__ . "/data/upload/pro/fujian/";
        ; // 编辑器上传的文件保存的位置
        $this->upload_save_url = base_url() . "/data/upload/pro/fujian/"; //编辑器上传图片的访问的路径		
        $this->upload_path_sys = "data/upload/pro/fujian/"; //保存字段用的
        $this->load->library('guolu_html');
        $this->load->library('session');
    }

    function tongji_action() {
        $where = ' where 1 and isdel=0 ';
        $uid = daddslashes(html_escape(strip_tags(trim($this->input->get_post("uid", true)))));
        $pro_id = daddslashes(html_escape(strip_tags(trim($this->input->get_post("pro_id", true)))));
        $start_time = strtotime(daddslashes(html_escape(strip_tags(trim($this->input->get_post("start_time", true))))));
        $end_time = strtotime(daddslashes(html_escape(strip_tags(trim($this->input->get_post("end_time", true))))));
        $type = daddslashes(html_escape(strip_tags(trim($this->input->get_post("type", true)))));
        if (!empty($pro_id) && $pro_id != 'all') {
            $where.=" AND pid = {$pro_id}";
        }
        if (!empty($start_time) && !empty($end_time)) {
            //使用开始和结束时间
            $where.=" and jihua_start_time between $start_time  and  $end_time";
        }
        switch($type){  
            //  未完成     
            case "not_wancheng": $sql = "select id from rwfp_mokuai  $where and  mokuai_userid={$uid} and mokuai_status  in (6,22,7,8,9,18) order by mokuai_status  ";break;
               // 未完成任务总数：跨时间段的任务数
            case "not_in_time":$sql = " select id from rwfp_mokuai  $where and  jihua_end_time < shiji_end_time and mokuai_userid={$uid} and mokuai_status  in (6,7,8,9,11,18,22,23) order by mokuai_status";break;
            //超时任务数
            case "over_time":$sql ="select * from rwfp_mokuai $where and  jihua_hours - shiji_hours < 0  and mokuai_userid={$uid} and mokuai_status not in (19,5,20) ";break;
//            任务总数 
            case "renwu_total":$sql = "select id from rwfp_mokuai $where and mokuai_userid={$uid} and mokuai_status not in (19,5,20)";break;
        }
        

        
        
//           echo $sql;exit;
        $list = $this->M_common->querylist($sql);
//        print_r($list);
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $ids[] = $v['id'];
            }
            $ids = implode(',', $ids);
           
        }else{
            $ids = -1;
        }
         $newdata = array(
                'not_wancheng_ids' => $ids,
                'redirect_type'=>'not_wancheng',
            );
            $this->session->set_userdata($newdata);
        redirect('/mokuai/lists', 'refresh');
    }
    
}
