<?php

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

class Mokuai extends MY_Controller {

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

    function Mokuai() {
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

    function index() {
        $action = $this->input->get_post("action");
        //$action_array = array("show","ajax_data");				
        $action_array = array("show", "ajax_data", "preview", "upload");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        if ($action == 'show') {
            $this->load->view(__TEMPLET_FOLDER__ . "/views_user");
        } elseif ($action == 'ajax_data') {
            $this->ajax_data();
        } elseif ($action == "upload") {
            $this->upload();
        }
    }

    function lists() {
        $not_wancheng_ids = '';
        $not_wancheng_ids_session = $this->session->userdata('not_wancheng_ids');
        $redirect_type = $this->session->userdata('redirect_type');

        if ($redirect_type == 'not_wancheng') {
            if (isset($not_wancheng_ids_session) && !empty($not_wancheng_ids_session)) {
                $not_wancheng_ids = $not_wancheng_ids_session;
            }
            $this->session->unset_userdata('not_wancheng_ids');
            $this->session->unset_userdata('redirect_type');
        }
        $action = $this->input->get_post("action");
        $action_array = array("show", "ajax_data");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        if ($action == 'show') {
            $sql = "SELECT * FROM rwfp_mokuai WHERE pid=0 and isdel=0 order by id ";
            $pid_list = $this->M_common->querylist($sql);

            //2,4为待审，重审暂时无用
            $sql = "SELECT * FROM rwfp_zidian WHERE zidian_flag='mokuai' or zidian_flag='pro' and id not in(2,4) order by orderby asc";
            $mokuai_status_list = $this->M_common->querylist($sql);
            $sql = "SELECT * FROM rwfp_zidian WHERE zidian_flag='jinji' order by orderby asc";
            $mokuai_jinji_list = $this->M_common->querylist($sql);
            //按拼音排序相关人员
            $sql = "SELECT * FROM 57sy_common_system_user WHERE gid in(8,10) order by CONVERT(username USING gbk) asc ";
            $mokuai_userid_list = $this->M_common->querylist($sql);
            //修改排序：把gid8的先显示 gid=8(开发)
            if (!empty($mokuai_userid_list)) {
                foreach ($mokuai_userid_list as $k => $v) {
                    if ($v['gid'] == 8) {
                        $arr_gid_8[] = $v;
                    } else {
                        $arr_gid_10[] = $v;
                    }
                }
                $mokuai_userid_list = array_merge($arr_gid_8, $arr_gid_10);
            }

            //查出创建时间
            $sql = "SELECT FROM_UNIXTIME(create_time, '%Y-%m' ) date FROM `rwfp_mokuai` GROUP BY FROM_UNIXTIME(create_time, '%Y-%m' ) order by date desc";
            $mokuai_date_list = $this->M_common->querylist($sql);

            //查出创建时间
            $sql = "SELECT FROM_UNIXTIME(jihua_start_time, '%Y-%m' ) date FROM `rwfp_mokuai` GROUP BY FROM_UNIXTIME(jihua_start_time, '%Y-%m' ) order by date desc";
            $mokuai_date_list_jihua = $this->M_common->querylist($sql);

            //查出实际结束时间
            $sql = "SELECT FROM_UNIXTIME(shiji_end_time, '%Y-%m' ) date FROM `rwfp_mokuai` where FROM_UNIXTIME(shiji_end_time, '%Y-%m' )>'1970-01' GROUP BY FROM_UNIXTIME(shiji_end_time, '%Y-%m' ) order by date desc";
            $mokuai_date_list_shiji = $this->M_common->querylist($sql);
            $data = array(
                "pid_list" => $pid_list,
                "mokuai_status_list" => $mokuai_status_list,
                "mokuai_jinji_list" => $mokuai_jinji_list,
                "mokuai_date_list_shiji" => $mokuai_date_list_shiji,
                "mokuai_userid_list" => $mokuai_userid_list,
                'mokuai_date_list' => $mokuai_date_list,
                'mokuai_date_list_jihua' => $mokuai_date_list_jihua,
                'not_wancheng_ids' => $not_wancheng_ids,
            );
            $this->load->view(__TEMPLET_FOLDER__ . "/views_mokuai", $data);
        } elseif ($action == 'ajax_data') {
            $this->ajax_data();
        }
    }

    //ajax 获取数据
    private function ajax_data() {
        $this->load->library("common_page");
        $page = $this->input->get_post("page");
        if ($page <= 0) {
            $page = 1;
        }
        $per_page = 15; //每一页显示的数量
        $limit = ($page - 1) * $per_page;
        $limit.=",{$per_page}";
        $where = ' where t1.isdel=0 and t1.pid>0 ';
        $sea_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title", true)))));
        $sea_pid = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_pid", true)))));
        $sea_mokuai_status = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_status", true)))));
        $sea_showme = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_showme", true)))));
        $sea_jinji = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_jinji", true)))));
        $sea_mokuai_userid = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_userid", true)))));
        $sea_orderby = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_orderby", true)))));
        $search_mokuai_create_time = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_create_time", true)))));
        $search_mokuai_jihua_time = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_jihua_time", true)))));
        $search_mokuai_shiji_end_time = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_shiji_end_time", true)))));
        if (!empty($sea_title)) {
            //改为可以查询id号
//             $where.=" AND ( (t1.title like '%$sea_title%') or ( t1.id in ($sea_title) ) )";  //这是原来的
            //如果是数字
            if (is_numeric($sea_title)) {
                $where.=" AND  t1.id in ($sea_title) ";
            } else {

                //如果是多个id号的话还是用id来搜索
                if (stripos($sea_title, ',')) {
                    $arr = explode(',', $sea_title);
                    if (!empty($arr)) {
                        foreach ($arr as $k => $v) {
                            if (is_numeric($v)) {
                                $where.=" AND  t1.id in ($sea_title) ";
                            } else {
                                $where.=" AND ( (t1.title like '%$sea_title%')  )";
                            }
                        }
                    }
                } else {
                    $where.=" AND ( (t1.title like '%$sea_title%')  )";
                }
            }
        }
        if ($sea_pid > 0) {
            $where.=" AND t1.pid=" . $sea_pid;
        }
        if ($sea_mokuai_status > 0) {
            $where.=" AND t1.mokuai_status=" . $sea_mokuai_status;
        }
        if ($sea_mokuai_userid > 0) {
            $where.=" AND (t1.mokuai_userid=" . $sea_mokuai_userid . " or t1.mokuai_test_userid=" . $sea_mokuai_userid . ")";
        }
        if ($search_mokuai_create_time) {
            $where.=" AND   FROM_UNIXTIME(t1.create_time, '%Y-%m' )  = '$search_mokuai_create_time'";
        }

        if ($search_mokuai_jihua_time) {
            $where.=" AND   FROM_UNIXTIME(t1.jihua_start_time, '%Y-%m' )  = '$search_mokuai_jihua_time'";
        }
        if ($search_mokuai_shiji_end_time) {
            $where.=" AND   FROM_UNIXTIME(t1.shiji_end_time, '%Y-%m' )  = '$search_mokuai_shiji_end_time'";
        }

        if (!empty($sea_showme)) {
            if ($sea_showme == "yes") {
                if (role_id() == "10") {//测试人员
                    $where.=" AND t1.mokuai_test_userid=" . admin_id();
                } else {
                    $where.=" AND t1.mokuai_userid=" . admin_id();
                }
            }
        }
        if ($sea_orderby != "") {
            //  $orderby = " order by $sea_orderby";
//            19任务取消,11任务结束,23任务完成,10测试通过,9测试中,22测试暂停,8编码完成,1起草,7暂停,18测试不通过,6工作中,5未接收,20已接收
            //$orderby = "order by   FIELD(t1.`mokuai_status`, 19,11,23,10,9,22,8,1,7,18,6,5,20) desc ,t1.jinji  ,t1.id desc";
            $orderby = "order by  FIELD(t1.`mokuai_status`, 19,11,23,10,9,22,8,1,7,18,6,5,20) desc , CASE WHEN t1.`mokuai_status`=23  THEN t1.`id`  END  desc ,CASE WHEN t1.`mokuai_status`!=23  THEN t1.`jinji`  END  ASC ";
        } else {
            $orderby = "order by  FIELD(t1.`mokuai_status`, 19,11,23,10,9,22,8,1,7,18,6,5,20) desc , CASE WHEN t1.`mokuai_status`=23  THEN t1.`id`  END  desc ,CASE WHEN t1.`mokuai_status`!=23  THEN t1.`jinji`  END  ASC ";
            //$orderby = "order by t1.id desc";
        }

        if ($sea_jinji > 0) {
            $where.=" AND t1.jinji=" . $sea_jinji;
        }
        if (!is_super_admin()) {
            $where.=" AND t1.mokuai_status <> 1 ";
        }
        $sql_count = "SELECT COUNT(*) AS tt FROM rwfp_mokuai t1 left join rwfp_mokuai t2 on t1.pid=t2.id {$where}";

        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string($total, $per_page, $page);
        $sql = "SELECT t1.*,t2.title as pro_title FROM rwfp_mokuai t1 left join rwfp_mokuai t2 on t1.pid=t2.id {$where} " . $orderby . " limit  {$limit}";
//        echo $sql;exit;
//        file_put_contents("d:a.txt", $sql);
        $list = $this->M_common->querylist($sql);

        //取字典ICO
        $zidian_mokuai_status = $this->M_common->querylist("select * from rwfp_zidian where zidian_flag='pro' or zidian_flag='mokuai' or zidian_flag='jinji'");
        foreach ($list as $k => $v) {
            $list[$k]['create_time'] = date("Y-m-d H:i:s", $v['create_time']);
            $list[$k]['jihua_start_time'] = date("Y-m-d H:i:s", $v['jihua_start_time']);
            $list[$k]['jihua_end_time'] = date("Y-m-d H:i:s", $v['jihua_end_time']);
            $list[$k]['mokuai_status_ico'] = "";
            $list[$k]['jinji_ico'] = "";


            //测试人
            if (!empty($v['mokuai_test_username'])) {
                $list[$k]['mokuai_test_username'] = $v['mokuai_test_username'];
            } else {
                $list[$k]['mokuai_test_username'] = '无';
            }

            //实际总用时 
            $sql = "SELECT sum(userhours) all_userhours,sum(userseconds) all_userseconds FROM `rwfp_mokuai_usertime` where endtime>0 and xiangmu_id={$v['pid']} and mokuai_id={$v['id']} and mokuai_userid={$v['mokuai_userid']}  limit 1";
            $timelist = $this->M_common->query_one($sql);

            if (!empty($timelist)) {
                if (!empty($timelist['all_userhours']) && !empty($timelist['all_userseconds'])) {

                    //小于60秒显示
                    if (abs($timelist['all_userseconds']) < 60) {
                        $list[$k]['all_time'] = $timelist['all_userseconds'] . '秒'; //完成时间(秒)
                    } else {
                        if (abs($timelist['all_userseconds']) < 3600) {
                            $list[$k]['all_time'] = round($timelist['all_userseconds'] / 60, 2) . '分钟';
                        } else {
//                            $list[$k]['all_time'] = round($v['shiji_hours'], 2) . '小时'; //完成时间(小时)
                            $list[$k]['all_time'] = round($timelist['all_userhours'], 2) . '小时'; //完成时间(小时)
                        }
                    }
                } else {
                    $list[$k]['all_time'] = '未知';
//                    $list[$k]['all_time'] = round($timelist['all_userhours'], 2) . '小时';
                }
            }

            //实际开始时间
            if (!empty($v['shiji_start_time'])) {
                $list[$k]['shiji_start_time'] = date('Y-m-d H:i:s', $v['shiji_start_time']);
            } else {
                $list[$k]['shiji_start_time'] = '未开始';
            }

            // 实际完成时间 	
            if (!empty($v['shiji_end_time'])) {
                $list[$k]['shiji_end_time'] = date('Y-m-d H:i:s', $v['shiji_end_time']);
            } else {
                $list[$k]['shiji_end_time'] = '未知';
            }

            //完成时差 
            $list[$k]['wancheng_shicha_another_show'] = '';
            if (!empty($v['shiji_hours'])) {
                $shicha = $v['jihua_hours'] - $v['shiji_hours'];  //小时

                $seconds_shicha = $shicha * 3600;


                $list[$k]['wancheng_shicha_another_show'] = round($shicha, 2) . '小时';
                //小于60秒显示
                if (abs($seconds_shicha) < 60) {
                    $list[$k]['wancheng_shicha'] = $list[$k]['wancheng_shicha_another_show'] . '<br>(' . round($seconds_shicha, 2) . '秒)'; //完成时间(秒)
                } else {
                    if (abs($seconds_shicha) < 3600) {
                        //分钟
                        $list[$k]['wancheng_shicha'] = $list[$k]['wancheng_shicha_another_show'] . '<br>(' . round(($seconds_shicha / 60), 2) . '分钟)';
                    } else {
                        //小时
                        $list[$k]['wancheng_shicha'] = round($shicha, 2) . '小时' . '<br>(' . round(($seconds_shicha / 60), 1) . '分钟)'; //完成时间(小时)
                    }
                }
            } else {
                $list[$k]['wancheng_shicha'] = '未知';
            }

            //计划用时 
            $list[$k]['jihua_hours'] = $v['jihua_hours'];



            foreach ($zidian_mokuai_status as $v) {
                if ($v["id"] == $list[$k]['mokuai_status']) {
                    $list[$k]['mokuai_status_ico'] = str_replace("{0}", $list[$k]['mokuai_status_title'], $v["ico"]);
                    break;
                }
            }
            foreach ($zidian_mokuai_status as $v) {

                if ($v["id"] == $list[$k]['jinji']) {
                    $list[$k]['jinji_ico'] = str_replace("{0}", $list[$k]['jinji_title'], $v["ico"]);
                    break;
                }
            }
        }
        echo result_to_towf_new($list, 1, '成功', $page_string);
    }

    //编辑页面
    function edit() {
        $action = $this->input->get_post("action");
        $action_array = array("edit", "doedit");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        $url = $this->input->get_post("url");
        if ($url == "") {
            $url = site_url("mokuai/lists");
        }
        if ($action == 'show') {
            $id = verify_id($this->input->get_post("id"));
            $sql = "SELECT * FROM rwfp_mokuai WHERE pid>0 and id = '{$id}'";
            $info = $this->M_common->query_one($sql);
            if (empty($info)) {
                showmessage("暂无数据", "mokuai/lists", 3, 0);
                exit();
            }
            /*
              权限控制 只有在“未接收时才能修改” 管理员可任意改
             */
            if ($info["mokuai_userid"] != admin_id() && !is_super_admin()) {
                showmessage("无权限修改，只能修改自己的任务", $url, 3, 0);
                exit;
            }
            if ($info["mokuai_status"] != 5 && !is_super_admin()) {
                showmessage("无权限修改，只有在“未接收”状态时才能修改", $url, 3, 0);
                exit;
            }
            $sql = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'jinji' order by orderby asc";
            $mokuai_jinji_list = $this->M_common->querylist($sql);
            $sql = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'mokuai_flag' order by orderby asc";
            $mokuai_flag_list = $this->M_common->querylist($sql);
            //管理员能安排给所有开发者，开发者只能安排自己
            $where = "";
            if (!is_super_admin()) {
                $where = " and id=" . admin_id();
            }
            $sql = "SELECT * FROM 57sy_common_system_user WHERE gid=8 $where order by username asc";
            $mokuai_user_list = $this->M_common->querylist($sql);
            //所属项目
            $sql = "SELECT * FROM rwfp_mokuai WHERE pid=0 and isdel=0 ORDER BY id asc";
            $pro_list = $this->M_common->querylist($sql);

            //测试者列表
            $sql = "SELECT * FROM 57sy_common_system_user WHERE gid=10 and super_admin=0  order by username asc";
            $testers = $this->M_common->querylist($sql);


            //任务状态 列出项目状态和模块状态 去除项目状态中的待审和重审
            $sql = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'mokuai' or zidian_flag= 'pro' and id not in(2,4)   order by orderby asc";
            $mokuai_status_list = $this->M_common->querylist($sql);

            $data = array(
                "mokuai_jinji_list" => $mokuai_jinji_list,
                "mokuai_flag_list" => $mokuai_flag_list,
                "mokuai_user_list" => $mokuai_user_list,
                "mokuai_status_list" => $mokuai_status_list,
                "pro_list" => $pro_list,
                "info" => $info,
                "url" => $url,
                "testers" => $testers,
            );
            $this->load->view(__TEMPLET_FOLDER__ . "/views_mokuai_edit", $data);
        } elseif ($action == 'doedit') {

            $this->doedit();
        }
    }

    //处理编辑数据
    private function doedit() {
        $this->set_params();
        $id = verify_id($this->input->get_post("id")); //id
        if (empty($this->title)) {
            showmessage("请输入任务名称", "mokuai/edit?id=$id", 3, 0);
            exit();
        }
        if (empty($this->jihua_start_time) || empty($this->jihua_end_time)) {
            showmessage("计划开始时间和结束时间不能为空", "mokuai/edit?id=$id", 3, 0);
            exit();
        }
        if ($this->pid <= 0) {
            showmessage("请选择所属项目", "mokuai/edit?id=$id", 3, 0);
            exit();
        }
        $this->load->library("common_upload");
        $fujian_path = $this->common_upload->upload_path($this->upload_path, 'fujian', 'doc|docx|wps|ppt');
        if ($fujian_path != "") {
            $fujian_path = $this->upload_path_sys . $fujian_path;
        }
        //取得旧信息
        $old = $this->M_common->query_one("select * from rwfp_mokuai where pid>0 and id=$id");


        //如果跟上次的计划时数不同的时候，就把相关资料记录到 rwfp_change_hours_detail 表
        if ($old['jihua_hours'] != $this->jihua_hours) {
            $data = array(
                'mkid' => $id,
                'username' => login_name(),
                'username_id' => admin_id(),
                'org_jihua_hours' => $old['jihua_hours'],
                'new_jihua_hours' => $this->jihua_hours,
                'update_time' => time()
            );
            $array = $this->M_common->insert_one("rwfp_change_hours_detail", $data);
        }


        if ($old["fujian"] != "") {
            //检查delfujian是否选中，如果就删除文件
            $delfujian = $this->input->get_post("delfujian");
            if (!empty($delfujian)) {
                if ($this->input->get_post("delfujian") == "yes") {
                    @unlink(__ROOT__ . $old["fujian"]);
                }
            }
        }
        $this->load->library("common_upload");
        $fujian_path = $this->common_upload->upload_path($this->upload_path, 'fujian', 'doc|docx|wps|ppt');
        if ($fujian_path != "") {
            $fujian_path = $this->upload_path_sys . $fujian_path;
            if ($old["fujian"] != "") {
                @unlink(__ROOT__ . $old["fujian"]);
            }
        } else {
            $delfujian = $this->input->get_post("delfujian");
            if (!empty($delfujian)) {
                
            } else {
                $fujian_path = $old["fujian"];
            }
        }

        $data = array(
            'title' => $this->title,
            'jihua_hours' => $this->jihua_hours,
            'fujian' => $fujian_path,
            'content' => $this->content,
            'jihua_start_time' => strtotime($this->jihua_start_time),
            'jihua_end_time' => strtotime($this->jihua_end_time),
            'content' => $this->content,
            'mokuai_status_title' => $this->mokuai_status_title,
            'mokuai_status' => $this->mokuai_status,
            'jinji' => $this->jinji,
            'jinji_title' => $this->jinji_title,
            'mokuai_flag' => $this->mokuai_flag,
            'mokuai_flag_name' => $this->mokuai_flag_name,
            'mokuai_userid' => $this->mokuai_userid,
            'mokuai_username' => $this->mokuai_username,
            'pid' => $this->pid,
            'isdel' => '0',
            'update_userid' => admin_id(),
            'update_time' => time(),
            'update_username' => login_name(),
            'need_test' => $this->need_test,
            'mokuai_test_userid' => $this->mokuai_test_userid,
            'mokuai_test_username' => $this->mokuai_test_username,
        );
//        print_r($data);exit;
        //echo $this->upload_path;
        
        
//        //查询项目名是否存在
//        $info = $this->M_common->query_one("SELECT count(1) as dd FROM `rwfp_mokuai` where pid=" . $this->pid . " and title= '" . $this->title . "' and id<>$id");
//        if ($info["dd"] > 0) {
//            showmessage("任务名称" . $this->title . "已经存在", "mokuai/edit?id=$id", 3, 0);
//            exit();
//        }

        //变更状态
        $this->mokuai_status_log($id, $this->mokuai_status);

        $array = $this->M_common->update_data2("rwfp_mokuai", $data, array("id" => $id));


        //如果状态为通过（3） 则再把状态改为未接收5
        if ($this->mokuai_status == 3) {
            $data['mokuai_status'] = 5;
            $data['mokuai_status_title'] = '未接收';
            $this->mokuai_status = 5;
            //再变更状态
            $this->mokuai_status_log($id, $this->mokuai_status);
            $array = $this->M_common->update_data2("rwfp_mokuai", $data, array("id" => $id));
        }


        if ($array['affect_num'] >= 1) {
            //   状态为未接收才发送邮件和站内消息
            //5状态为未接收
            if ($this->mokuai_status == 5) {
                $mkid = $id;
                //通知开发者
                $uid = $this->mokuai_userid; //用户id
                $message = "<span style='color:red;font-size:bold;'>编号 " . $mkid . "...有任务</span>";
                $this->tongzhi_message_send_one($uid, $message, $mkid);

                //发送邮件
                $userinfo = $this->userinfo($uid);
                if ($userinfo['is_receive'] == 1) {
                    $to = $userinfo['email'];
                    $subject = '有任务';
                    $this->send_email($to, $subject, $message);
                }
            }

            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "修改任务" . $this->title . "成功");
            showmessage("修改成功", "mokuai/lists", 3, 1); //1:正确
            //header("Location:".site_url("pro/prolist"));
            //showmessage("添加用户成功","user/index",3,1);
            exit();
        } else {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "修改任务" . $this->title . "失败");
            showmessage("修改任务失败", "mokuai/edit?id=$id", 3, 0);
            exit();
        }
    }

    //编辑页面
    function jiuzheng() {
        $action = $this->input->get_post("action");
        $action_array = array("jiuzheng", "dojiuzheng");
        $action = !in_array($action, $action_array) ? 'show' : $action;

        $url = $this->input->get_post("url");
        if ($url == "") {
            $url = site_url("mokuai/lists");
        }
        $id = verify_id($this->input->get_post("id"));
        $sql = "SELECT * FROM rwfp_mokuai WHERE pid>0 and id = '{$id}'";
        $info = $this->M_common->query_one($sql);
        if ($action == 'show') {

//            $sql = "select * from rwfp_mokuai where id={$id} and mokuai_status=10"; //通过的数据
//            $tongguo = $this->M_common->query_one($sql);
//              if(!empty($tongguo)){
//                showmessage("测试通过不能纠正时间","mokuai/lists",3,0);
//			exit();
//            }


            if (empty($info)) {
                showmessage("暂无数据", "mokuai/lists", 3, 0);
                exit();
            }



            //开发时数累计历史				
            $usertime_list = $this->M_common->querylist("select * from rwfp_mokuai_usertime where endtime>0 and mokuai_userid=" . $info["mokuai_userid"] . " and mokuai_id=" . $info["id"] . " order by id desc");
            $data = array(
                "info" => $info,
                "usertime_list" => $usertime_list,
                "url" => $url
            );
            $this->load->view(__TEMPLET_FOLDER__ . "/views_mokuai_jiuzheng", $data);
        } elseif ($action == 'dojiuzheng') {

            $this->dojiuzheng($id, $info["mokuai_userid"]);
        }
    }

    //处理编辑数据  纠正时间错误
    private function dojiuzheng($mkid, $info_userid) {

        $id = verify_id($this->input->get_post("id")); //id
        //处理是否这个模块id下的id
        $jilu_id = verify_id($this->input->get_post("jilu_id")); //$jilu_id
        $hidden_id = $this->input->get_post("hidden_id"); //$hidden_id
        $hidden_id = explode(',', $hidden_id);
        if (!in_array($jilu_id, $hidden_id)) {
            $par = "?id=$mkid";
            showmessage("没有这个ID", "mokuai/jiuzheng", 3, 0, $par);
            exit();
        }

        $this->start_time = daddslashes(html_escape(strip_tags($this->input->get_post("start_time", true))));
        $this->end_time = daddslashes(html_escape(strip_tags($this->input->get_post("end_time", true))));
        $this->jilu = daddslashes(html_escape(strip_tags($this->input->get_post("content", true))));
        $this->jilu_id = daddslashes(html_escape(strip_tags($this->input->get_post("jilu_id", true))));

        //比较调换，开始时间必须小于结束时间
        if ($this->start_time > $this->end_time) {
            $tmp = $this->end_time;
            $this->start_time = $this->end_time;
            $this->end_time = $tmp;
        }


        if (empty($this->jilu)) {
            showmessage("内容不能为空", "mokuai/lists", 2, 0);
            exit();
        }
        if (empty($this->start_time) || empty($this->end_time)) {
            showmessage("开始时间和结束时间不能为空", "mokuai/jiuzheng?id=$id", 3, 0);
            exit();
        }
        if (empty($this->jilu_id)) {
            showmessage("ID不能为空", "mokuai/jiuzheng?id=$id", 3, 0);
            exit();
        }



        $sql = "SELECT beizhu FROM rwfp_mokuai_usertime WHERE mokuai_id = '{$id}'";
        $beizhu = $this->M_common->query_one($sql);
        $data = array(
            'beizhu' => $beizhu['beizhu'] . '(' . $this->jilu . ')',
            'starttime' => strtotime($this->start_time),
            'endtime' => strtotime($this->end_time),
            "userhours" => doubleval(( strtotime($this->end_time) - strtotime($this->start_time) ) / (60 * 60)),
            "userseconds" => strtotime($this->end_time) - strtotime($this->start_time),
        );

        $array = $this->M_common->update_data2("rwfp_mokuai_usertime", $data, array("id" => $this->jilu_id));

        //后补的任务，开始时间会不正确，用这里来修改
        //修改第一条开始时间，实际时间也会被同时修改
        $sql = "SELECT id FROM rwfp_mokuai_usertime WHERE mokuai_id = '{$id}' order by id limit 1";
        $rwfp_mokuai_usertime_start_id = $this->M_common->query_count($sql);
        if ($rwfp_mokuai_usertime_start_id == $jilu_id) {
            $sql = "select mokuai_userid from rwfp_mokuai where id=$id  limit 1";
            $query = $this->M_common->query_one($sql);
            $shiji_start_time = @$query['shiji_start_time'];
            if (strtotime($this->start_time) != $shiji_start_time) {
                $data = array(
                    'shiji_start_time' => strtotime($this->start_time),
                );
                $array = $this->M_common->update_data2("rwfp_mokuai", $data, array("id" => $id));
            }
        }

        if ($array['affect_num'] >= 1) {


            //更新主表rwfp_mokuai的shiji_hours
            //实际总用时 
            $sql = "SELECT sum(userhours) all_userhours,sum(userseconds) all_userseconds FROM `rwfp_mokuai_usertime` where endtime>0  and mokuai_id=$id and mokuai_userid='$info_userid'  limit 1";
            $timelist = $this->M_common->query_one($sql);

            $data = array(
//            'shiji_hours' => doubleval(( strtotime($this->end_time) - strtotime($this->start_time) ) / (60 * 60)),
                'shiji_hours' => $timelist['all_userhours'],
            );

            $array = $this->M_common->update_data2("rwfp_mokuai", $data, array("id" => $id));
            //echo $array['sql'];exit;
            //修改开发者最后更新时间
            $sql = "select mokuai_userid from rwfp_mokuai where id=$id  limit 1";
            $query = $this->M_common->query_one($sql);
            $mokuai_userid = $query['mokuai_userid'];

            $sql = "select * from rwfp_mokuai_usertime where mokuai_id=$id and mokuai_userid={$mokuai_userid} order by id desc limit 1";
            $query = $this->M_common->query_one($sql);
            $model["shiji_end_time"] = $query['endtime']; //测试通过时更新实际完成时间

            $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $id));


            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "修改时间" . $this->title . "成功");
            showmessage("修改成功", "mokuai/lists", 3, 1); //1:正确
            //header("Location:".site_url("pro/prolist"));
            //showmessage("添加用户成功","user/index",3,1);
            exit();
        } else {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "修改" . $this->title . "失败");
            showmessage("修改失败", "mokuai/jiuzheng?id=$id", 3, 0);
            exit();
        }
    }

    //查看项目详情
    function mkview() {
        $action = $this->input->get_post("action");
        $id = verify_id($this->input->get_post("id"));
        $url = $this->input->get_post("url");
        if ($url == "") {
            $url = site_url("mokuai/lists");
        }
        $sql = "SELECT * FROM rwfp_mokuai WHERE pid>0 and id = '{$id}'";
        $info = $this->M_common->query_one($sql);
        $info["content"] = str_replace("&lt;", "<", $info["content"]);
        $info["content"] = str_replace("&gt;", ">", $info["content"]);
        $info["content"] = str_replace("&amp;nbsp;", " ", $info["content"]);
        //所属项目
        $sql = "SELECT * FROM rwfp_mokuai WHERE pid=0 and id=" . $info["pid"];
        $promodel = $this->M_common->query_one($sql);
        $info["protitle"] = $promodel["title"];
        $zidian_mokuai_status = $this->M_common->querylist("select * from rwfp_zidian where zidian_flag='mokuai' or zidian_flag='pro' or zidian_flag='jinji'");
        $info['mokuai_status_ico'] = "";
        $info['mokuai_flag_ico'] = "";
        foreach ($zidian_mokuai_status as $v) {
            if ($v["id"] == $info['mokuai_status']) {
                $info['mokuai_status_ico'] = str_replace("{0}", $v["title"], $v["ico"]);
                break;
            }
        }
        foreach ($zidian_mokuai_status as $v) {
            if ($v["id"] == $info['jinji']) {
                $info['mokuai_jinji_ico'] = str_replace("{0}", $v["title"], $v["ico"]);
                break;
            }
        }
        //状态变更历史
        $status_list = $this->M_common->querylist("select * from rwfp_mokuai_status_log where mkid=" . $info["id"] . " order by id desc");
        //开发时数累计历史				
        $usertime_list = $this->M_common->querylist("select * from rwfp_mokuai_usertime where endtime>0 and mokuai_userid=" . $info["mokuai_userid"] . " and mokuai_id=" . $info["id"] . " order by id desc");
        if ($info["mokuai_test_userid"] > 0) {
            //测试用时
            $test_usertime_list = $this->M_common->querylist("select * from rwfp_mokuai_usertime where endtime>0 and mokuai_userid=" . $info["mokuai_test_userid"] . " and mokuai_id=" . $info["id"] . " order by id desc");
        } else {
            $test_usertime_list = array();
        }
        //修改时数详细表				
        $change_hours_detail_list = $this->M_common->querylist("select * from rwfp_change_hours_detail where mkid=" . $info["id"] . " order by id desc");

        //显示任务详细页中状态变更记录备注详情
        $show_renwu_detail_YouwBeiZhu = $this->M_common->querylist("SELECT * FROM `rwfp_mokuai_status_log` where mkid= {$info["id"]} and content <> '' order by id desc");
        //带备注的数据总数
        $beizhu_total = count($show_renwu_detail_YouwBeiZhu);

        //过滤危险的html
        foreach ($show_renwu_detail_YouwBeiZhu as $k => $value) {
            $this->guolu_html->guolu($show_renwu_detail_YouwBeiZhu[$k]['content']);  // 对象的实例名永远都是小写的
            //再次过滤
            $show_renwu_detail_YouwBeiZhu[$k]['content'] = html_entity_decode(htmlspecialchars($show_renwu_detail_YouwBeiZhu[$k]['content']));
        }
//        测试时数累计详情
        $show_renwu_detail_ceshiYouwBeiZhu = $this->M_common->querylist("SELECT * FROM `rwfp_mokuai_usertime` where mokuai_id = {$info["id"]} and content <> '' order by id desc");

        //带备注的数据总数
        $beizhu_total_ceshi = count($show_renwu_detail_ceshiYouwBeiZhu);
        //过滤危险的html
        foreach ($show_renwu_detail_ceshiYouwBeiZhu as $k => $value) {
            $this->guolu_html->guolu($show_renwu_detail_ceshiYouwBeiZhu[$k]['content']);  // 对象的实例名永远都是小写的
            //再次过滤
            $show_renwu_detail_ceshiYouwBeiZhu[$k]['content'] = html_entity_decode(htmlspecialchars($show_renwu_detail_ceshiYouwBeiZhu[$k]['content']));
//            $show_renwu_detail_ceshiYouwBeiZhu[$k]['content'] = str_replace('"', '\"', $show_renwu_detail_ceshiYouwBeiZhu[$k]['content']);
        }

        //工作图表数据
        $sql = "SELECT 
                FROM_UNIXTIME(starttime,'%Y-%m-%d %H:%i:%s') as  shiji_start_time,
                FROM_UNIXTIME(endtime,'%Y-%m-%d %H:%i:%s') as  shiji_end_time
                ,round(userhours,2) as  shiji_hours
                ,mokuai_userid
                ,(select gid from 57sy_common_system_user where id=mokuai_userid) as gid
                ,if(FROM_UNIXTIME(endtime,'%H')>=8 && FROM_UNIXTIME(endtime,'%H')<=12,1,2) as H
                ,FROM_UNIXTIME(starttime,'%Y-%m-%d') as  riqi
                FROM `rwfp_mokuai_usertime`
                where 
                mokuai_id={$id}
                and endtime>0
                order by starttime asc
                ;";
        $tmpdata = $this->M_common->querylist($sql);
        $workdata = array();
        $morning = array();
        $afteroom = array();
        foreach ($tmpdata as $k => $v) {
            if($v['H']==1){
                $morning[] = $v['H'];
            }else{
                $afteroom[] =  $v['H'];
            }
            $workdata[$v['riqi']][] = $tmpdata[$k];
        }

        $info = str_replace("&quot;", "\"", $info);
        $info = str_replace('&amp;lt;', "< ", $info);
        $info = str_replace('&amp;gt;', ">", $info);
        $data['info'] = $info;
        $data['url'] = $url;
        $data['status_list'] = $status_list;
        $data['usertime_list'] = $usertime_list;
        $data['test_usertime_list'] = $test_usertime_list;
        $data['change_hours_detail_list'] = $change_hours_detail_list;
        $data['show_renwu_detail_YouwBeiZhu'] = $show_renwu_detail_YouwBeiZhu;
        $data['beizhu_total'] = $beizhu_total;
        $data['show_renwu_detail_ceshiYouwBeiZhu'] = $show_renwu_detail_ceshiYouwBeiZhu;
        $data['beizhu_total_ceshi'] = $beizhu_total_ceshi;
        $data['workdata'] = $workdata;
        $data['morning'] = $morning;
        $data['afteroom'] = $afteroom;
        $this->load->view(__TEMPLET_FOLDER__ . "/views_mokuai_view", $data);
    }

    function mokuai_butongguo_view() {
        $id = verify_id($this->input->get_post("id"));
        $content = $this->M_common->query_one("select * from rwfp_mokuai_usertime where id=$id");
        //file_put_contents("e:/aa.txt", print_r($content,true));
        if (count($content) > 0) {
            //echo $content["content"];
            $list[0] = $content;
            //file_put_contents("e:/aa.txt", print_r($list,true));
            echo result_to_towf_new($list, 1, '', "");
        } else {
            echo result_to_towf_new(array(), 0, '没有内容', "");
        }
    }

    function mokuai_log_view() {
        $id = verify_id($this->input->get_post("id"));
        $content = $this->M_common->query_one("select * from rwfp_mokuai_status_log where id=$id");
        if (count($content) > 0) {
            //echo $content["content"];
            $list[0] = $content;
            //file_put_contents("e:/aa.txt", print_r($list,true));
            echo result_to_towf_new($list, 1, '', "");
        } else {
            echo result_to_towf_new(array(), 0, '没有内容', "");
        }
    }

    function add() {
        $action = $this->input->get_post("action");
        $action_array = array("add", "doadd");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        if ($action == 'show') {
            $sql = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'jinji' order by id ";
            $mokuai_jinji_list = $this->M_common->querylist($sql);
            $sql = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'mokuai_flag' order by orderby asc";
            $mokuai_flag_list = $this->M_common->querylist($sql);
            //管理员能安排给所有开发者，开发者只能安排自己
            $where = "";
            if (!is_super_admin()) {
                $where = " and id=" . admin_id();
            }
            $sql = "SELECT * FROM 57sy_common_system_user WHERE gid=8 $where order by username asc";
            $mokuai_user_list = $this->M_common->querylist($sql);
            //所属项目
            $sql = "SELECT * FROM rwfp_mokuai WHERE pid=0 and isdel=0 ORDER BY id asc";
            $pro_list = $this->M_common->querylist($sql);
            $url = $this->input->get_post("url");
            if ($url == "") {
                $url = site_url("mokuai/lists");
            }


            //状态
            $sql_user = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'pro' order by orderby asc";
            $pro_status_list = $this->M_common->querylist($sql_user);

            //测试者列表
            $sql = "SELECT * FROM 57sy_common_system_user WHERE gid=10 and super_admin=0  order by username asc";
            $testers = $this->M_common->querylist($sql);

            $data = array(
                "mokuai_jinji_list" => $mokuai_jinji_list,
                "mokuai_flag_list" => $mokuai_flag_list,
                "mokuai_user_list" => $mokuai_user_list,
                "pro_list" => $pro_list,
                "url" => $url,
                "testers" => $testers,
                "pro_status_list" => $pro_status_list,
            );
            $this->load->view(__TEMPLET_FOLDER__ . "/views_mokuai_add", $data);
        } elseif ($action == 'doadd') {

            $this->doadd();
        }
    }

    //处理增加
    private function doadd() {

        $this->set_params();
        if (empty($this->title)) {
            showmessage("请输入任务名称", "mokuai/add", 3, 0);
            exit();
        }
        if (empty($this->jihua_start_time) || empty($this->jihua_end_time)) {
            showmessage("计划开始时间和结束时间不能为空", "mokuai/add", 3, 0);
            exit();
        }
        if ($this->pid <= 0) {
            showmessage("请选择所属项目", "mokuai/add", 3, 0);
            exit();
        }
        $this->load->library("common_upload");
        $fujian_path = $this->common_upload->upload_path($this->upload_path, 'fujian', 'doc|docx|wps|ppt');
        if ($fujian_path != "") {
            $fujian_path = $this->upload_path_sys . $fujian_path;
        }
        $data = array(
            'title' => $this->title,
            'fujian' => $fujian_path,
            'content' => $this->content,
            'jihua_start_time' => strtotime($this->jihua_start_time),
            'jihua_end_time' => strtotime($this->jihua_end_time),
            'jihua_hours' => $this->jihua_hours,
            'mokuai_status_title' => $this->mokuai_status_title,
            'mokuai_status' => $this->mokuai_status,
            'jinji' => $this->jinji,
            'jinji_title' => $this->jinji_title,
            'mokuai_flag' => $this->mokuai_flag,
            'mokuai_flag_name' => $this->mokuai_flag_name,
            'mokuai_userid' => $this->mokuai_userid,
            'mokuai_username' => $this->mokuai_username,
            'pid' => $this->pid,
            'isdel' => '0',
            'create_userid' => admin_id(),
            'create_time' => time(),
            'create_username' => login_name(),
            'update_userid' => admin_id(),
            'update_time' => time(),
            'update_username' => login_name(),
            'need_test' => $this->need_test,
            'mokuai_test_userid' => $this->mokuai_test_userid,
            'mokuai_test_username' => $this->mokuai_test_username,
        );
        //file_put_contents("e:/aa.txt",print_r($data,true));
        //echo $this->upload_path;
        
        
//        //查询项目名是否存在
//        $info = $this->M_common->query_one("SELECT count(1) as dd FROM `rwfp_mokuai` where pid=" . $this->pid . " and title= '" . $this->title . "' ");
//        if ($info["dd"] > 0) {
//            showmessage("同一项目下" . $this->title . "已存在相同名称的任务", "mokuai/add", 3, 0);
//            exit();
//        }
        
        
        //状态3为通过,通过的状态直接显示为未接收
        if ($this->mokuai_status == 3) {
            $data['mokuai_status'] = 5;
            $data['mokuai_status_title'] = '未接收';
        } else if ($this->mokuai_status == 1) {
            //1为起草
            $data['mokuai_status_title'] = '起草';
        }
        $array = $this->M_common->insert_one("rwfp_mokuai", $data);
        if ($array['affect_num'] >= 1) {

//            状态为通过才发送邮件和站内消息
            //3为通过
            if ($this->mokuai_status == 3) {
                //通知开发者
                $id = $this->mokuai_userid; //用户id
                $message = "<span style='color:red;font-size:bold;'>编号 " . $array['insert_id'] . "...有任务</span>";
                $this->tongzhi_message_send_one($id, $message, $array['insert_id']);

                //发送邮件
                $userinfo = $this->userinfo($id);
                if ($userinfo['is_receive'] == 1) {
                    $to = $userinfo['email'];
                    $subject = '有任务';
                    $this->send_email($to, $subject, $message);
                }
            }

            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "添加模块" . $this->title . "成功");
            header("Location:" . site_url("mokuai/lists"));
            //showmessage("添加用户成功","user/index",3,1);
            //exit();
        } else {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "添加模块" . $this->title . "失败");
            showmessage("添加模块失败", "mokuai/lists", 3, 0);
            exit();
        }
    }

    //生成缓存
    private function make_cache() {
        if (!is_really_writable($this->role_cache_path)) {
            exit("目录" . $this->role_cache_path . "不可写");
        }

        if (!file_exists($this->role_cache_path)) {
            mkdir($this->role_cache_path);
        }
        $configfile = $this->role_cache_path . "/cache_role_{$this->role_id}.inc.php";
        $str = '';
        $time = date("Y-m-d H:i:s", time());
        $fp = fopen($configfile, 'w');
        flock($fp, 3);
        fwrite($fp, "<" . "?php\r\n");
        fwrite($fp, "/*团队角色缓存*/\r\n");
        fwrite($fp, "/*author wangjian*/\r\n");
        fwrite($fp, "/*time {$time}*/\r\n");
        //fwrite($fp,"\$role_array = array(\r\n");
        /* foreach($this->perm_data as $k=>$v){
          fwrite($fp,"'{$k}' => '{$v}',\r\n");
          } */
        $str.="\$role_array = ";
        $str.= var_export($this->perm_data, true);
        fwrite($fp, "{$str};\r\n");
        //fwrite($fp,");\r\n");
        fwrite($fp, "?" . ">");
        fclose($fp);
    }

    //上传文件
    private function upload() {
        //包含kindeditor的上传文件
        $save_path = $this->upload_path; // 编辑器上传的文件保存的位置
        $save_url = $this->upload_save_url; //访问的路径
        include_once __ROOT__ . '/' . APPPATH . "libraries/JSON.php";
        include_once __ROOT__ . '/' . APPPATH . "libraries/upload_json.php";
    }

    //预览所在用户组的用户
    private function preview_user() {
        $id = verify_id($this->input->get_post("id"));
        if ($id <= 0) {
            echo "参数传递错误";
            exit();
        }
        $list = $this->M_common->querylist("SELECT id,username,status FROM {$this->table_}common_system_user where gid = '{$id}' ");
        if ($list) {
            foreach ($list as $k => $v) {
                $status = ($v['status'] == 1 ) ? "<font color='green'>正常</font>" : '<font color="red" >禁止</font>';
                echo "<li style=\"text-decoration:none; display:block ; width:100px; height:30px; padding:2px; float:left; border:solid 1px #F0F0F0 ;  text-align:center;line-height:30px; margin-left:3px\">";
                echo $v['username'] . "【" . $status . "】";
                echo "</li>";
            }
        } else {
            echo "暂无用户";
        }
    }

    //模块状态分流
    public function status_switch() {
        $mkid = verify_id($this->input->get_post("id"));
        $statusid = verify_id($this->input->get_post("statusid"));

        switch ($statusid) {
            case 20:
                $this->jieshou($mkid);
                break;

            case 6:
                $this->kaishi($mkid);
                break;

            case 7:
                $this->zhanting($mkid);
                break;

            case 8:
                $content = $this->input->get_post("content");
                $this->wancheng($mkid, $content);
                break;

            case 9:
                $this->ceshi($mkid);
                break;

            case 10:
                $content = $this->input->get_post("content");
                $this->test_tongguo($mkid, $content);
                break;

            case 22:
                $this->test_zhanting($mkid);
                break;

            case 18:
                $content = $this->input->get_post("content");
                $this->test_butongguo($mkid, $content);
                break;

            case 19:
                $save_shishu = $this->input->get_post("saveshishu");
                $content = $this->input->get_post("content");
                //file_put_contents("e:/aa.txt",$save_shishu);
                //die();
                $this->admin_cancel($mkid, $content, $save_shishu);
                break;

            case 11://结束
                $this->admin_jieshu($mkid);
                break;
            case 12:
                $content = $this->input->get_post("content");
                $this->fan_ceshi($mkid, $content);
                break;
            case 13:
                $this->change_stat();
                break;

            case 9999999:
                //删除任务
                $this->admindel($mkid);
                break;
        }
    }

    //接受
    private function jieshou($mkid) {
        //检查模块当前状态,必须是"未接收"才能更改
        $info = $this->M_common->query_one("select * from rwfp_mokuai where pid>0 and id=$mkid");

        if ($info["mokuai_status"] == "5") {
            //检查任务开发者是不是操作人本身
            if (admin_id() == $info["mokuai_userid"]) {
                $info["mokuai_status"] = "20";
                $status = $this->M_common->query_one("select * from rwfp_zidian where id=" . $info["mokuai_status"]);
                $info["mokuai_status_title"] = $status["title"];
                $info["update_userid"] = admin_id();
                $info["update_time"] = time();
                $info["update_username"] = login_name();
                $this->mokuai_status_log($mkid, "20"); //已接收
                $this->M_common->update_data2("rwfp_mokuai", $info, array("id" => $mkid));
                write_action_log("", $this->uri->uri_string(), login_name(), get_client_ip(), 1, "修改状态" . $info["title"] . "成功");
                echo result_to_towf_new(array(), 1, '操作成功', "");
                //file_put_contents("e:/aa.txt",result_to_towf_new(array(), 1, '操作成功', ""));
            } else {
                echo result_to_towf_new(array(), 0, '你不是任务开发者，不能接收任务，你可以通知管理员将任务转移给你。', "");
                //file_put_contents("e:/aa.txt",result_to_towf_new(array(), 1, '2操作成功', ""));
                write_action_log("", $this->uri->uri_string(), login_name(), get_client_ip(), 1, "修改状态" . $info["title"] . "失败:你不是任务开发者，不能接收任务");
            }
        } else {

            echo result_to_towf_new(array(), 0, '当前状态不能修改', "");
            write_action_log("", $this->uri->uri_string(), login_name(), get_client_ip(), 1, "修改状态" . $info["title"] . "失败:当前状态不能修");
            //file_put_contents("e:/aa.txt",result_to_towf_new(array(), 1, '3操作成功', ""));
        }
    }

    //开始
    private function kaishi($mkid) {
        /*
          //检查是否有正在工作的任务
          $sql = "select * from rwfp_mokuai where isdel=0 and mokuai_status=6 and mokuai_userid=".$admin_id();
          $mokuai_working = $this->M_common->query_one($sql);
          if(is_array($mokuai_working)){
          //先暂停当前工作
          $mokuai_working_zd = $this->M_common->query_one("select title from rwfp_zidian where id=".$mokuai_working["mokuai_status"]);
          $sql = "update rwfp_mokuai set mokuai_status=7,mokuai_status_title='".$mokuai_working_zd["title"]."' where id=".$mokuai_working["id"];
          }
         */
        $sql = "select * from rwfp_mokuai where isdel=0 and id=$mkid and pid>0";
        $model = $this->M_common->query_one($sql);

        if (count($model) > 0) {
            $this->mokuai_usertime($model["pid"], $model["id"], $model["mokuai_userid"]);
            echo result_to_towf_new(array(), 1, '操作成功', "");
            //write_action_log("",$this->uri->uri_string(),login_name(),get_client_ip(),1,"开始工作：".$model["title"]);			
        } else {
            echo result_to_towf_new(array(), 0, '开始工作时找不到任务', "");
            write_action_log("", $this->uri->uri_string(), login_name(), get_client_ip(), 1, "开始工作失败：" . $model["title"]);
        }
    }

    //暂停
    private function zhanting($mkid, $showmsg = true) {
        //计算时数
        //查找时数表检查endtime是否为空
        $sql = "select * from rwfp_mokuai_usertime where endtime='' and mokuai_id=$mkid";
        $time_id = 0;
        $time_model = $this->M_common->query_one($sql);
        if (count($time_model) > 0) {
            $time_model["endtime"] = time();
            $time_id = $time_model["id"];
            $time_model["userhours"] = doubleval(($time_model["endtime"] - $time_model["starttime"]) / (60 * 60));
            $time_model["userseconds"] = ($time_model["endtime"] - $time_model["starttime"]);
            $time_model["beizhu"] = login_name() . "主动暂停任务";
            $this->M_common->update_data2(
                    "rwfp_mokuai_usertime", $time_model, array("id" => $time_model["id"])
            );
        }
        //暂停当前工作
        $zd = $this->M_common->query_one("select * from rwfp_zidian where id=7");
        $sql = "select * from rwfp_mokuai where id=$mkid";
        $model = $this->M_common->query_one($sql);
        $model["mokuai_status"] = "7";

        //记录状态变更
        $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
        //统计当前工作的实际时数
        $sql = "select sum(userhours) as dd from rwfp_mokuai_usertime where mokuai_userid=" . $model["mokuai_userid"] . " and mokuai_id=" . $model["id"];
        $sum = $this->M_common->query_one($sql);
        if (count($sum) > 0) {
            $model["shiji_hours"] = $sum["dd"];
        }
        $model["mokuai_status_title"] = $zd["title"];
        $model["update_userid"] = admin_id();
        $model["update_time"] = time();
        $model["update_username"] = login_name();
        $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
        if ($showmsg) {
            echo result_to_towf_new(array(), 1, '操作成功', "");
        }
        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "暂停任务：" . $model["title"]);
        }
        return $time_id;
    }

    //完成
    private function wancheng($mkid, $content) {
        //先暂停任务，再修改状态
        $this->zhanting($mkid);

        //更改工作状态为完成
        $zd = $this->M_common->query_one("select * from rwfp_zidian where id=8");
        $sql = "select * from rwfp_mokuai where id=$mkid";
        $model = $this->M_common->query_one($sql);


        //判断是否需要测试
        if ($model["mokuai_test_userid"] > 0) {
            $model["mokuai_status"] = "8";
            $model["mokuai_status_title"] = $zd["title"];
        } else {
            //当不用测试的时间直接把编码完成状态改为任务完成
            $model["mokuai_status"] = "23";
            $zd = $this->M_common->query_one("select * from rwfp_zidian where id=23");
            $model["mokuai_status_title"] = $zd["title"];
        }

        //记录状态变更
        $insertid = $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
        $model["update_userid"] = admin_id();
        $model["update_time"] = time();
        $model["update_username"] = login_name();
        $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));


        //如果不用测试者测试的话直接写入最后结束时间
        if ($model['need_test'] == 0) {
            $model["shiji_end_time"] = time(); //测试通过时更新实际完成时间
            $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
        }

        //通过内容，写入表
        if ($mkid > 0 && !empty($content)) {
//            $tongguo = $this->M_common->query_one("select * from rwfp_mokuai_usertime where id=$mkid");
//            $tongguo["beizhu"] = cn_substr(strip_tags($content), 200);
//            $tongguo["content"] = strip_tags($content);
//            
            //过滤script标签
            $content = htmlspecialchars_decode($content);
            $content = preg_replace("@<script(.*?)</script>@is", htmlspecialchars($content), $content);

            $tongguo["content"] = $content;
            $aaa = $this->M_common->update_data2("rwfp_mokuai_status_log", $tongguo, array("id" => $insertid));
//           print_r($aaa);exit;
            //echo result_to_towf_new(array(),1, '工作完成等待测试结果', "");
        }



        //是否需要通知测试
        if ($model["need_test"]) {
            // $gid = 10;
            $message = "<span style='color:red;font-size:bold;'>编号 " . $mkid . "...需要测试</span>";
            //$this->tongzhi_message($gid, $message, $mkid); //这是集体发送的
            $mokuai_test_userid = $model["mokuai_test_userid"];

            //发送邮件
            $userinfo = $this->userinfo($mokuai_test_userid);
            $to = $userinfo['email'];
            $subject = '测试';
            if ($userinfo['is_receive'] == 1) {
                if ($this->send_email($to, $subject, $message)) {
                    $this->tongzhi_message_send_one($mokuai_test_userid, $message, $mkid);
                }
            }


//                这是集体发送的
            /* $sql = "select * from 57sy_common_system_user where gid=$gid  and super_admin=0  ";
              $userinfo = $this->M_common->querylist($sql);
              foreach ($userinfo as $k => $v) {
              if ($v['is_receive'] == 1) {
              $to = $v['email'];
              $subject = '测试';
              $this->send_email($v['email'], $subject, $message);
              }
              } */
        }

        //  //当编码完成时，发送一封邮件给发布人
        $create_userid = $model["create_userid"];
        $userinfo = $this->userinfo($create_userid);
        if ($userinfo['is_receive'] == 1) {
            $info = $model["content"];
            $info = str_replace("&quot;", "\"", $info);
            $info = str_replace('&lt;', "<", $info);
            $info = str_replace('&gt;', ">", $info);
            //替换图片地址
            $replace = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/data/upload/';
//        $replace = 'http://192.168.1.218:82/data/upload/';
            $info = str_replace("/data/upload/", $replace, $info);
            $message = '';
            $message .= "";
            $message .= "编号：{$model["id"]} 已完成：{$model['title']}<br>";
            $message .= "内容：{$info}<br>";
            $message .= "计划工时：{$model["jihua_hours"]}<br>";
            $message .= "实际工时：" . round($model["shiji_hours"], 2) . "<br>";
            $message .= "计划时段：" . date("Y-m-d H:i:s", $model['jihua_start_time']) . "<br>";
            $message .= "结束时段：" . date("Y-m-d H:i:s", $model['jihua_end_time']) . "<br>";
            //发送邮件
            $to = $userinfo['email'];
            $subject = '编码已完成';
            if ($this->send_email($to, $subject, $message)) {
                $this->tongzhi_message_send_one($create_userid, $message, $mkid); //插入数据库
            }
        }

        //echo result_to_towf_new(array(),1, '工作完成等待测试结果', "");	  
        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "完成任务：" . $model["title"]);
        }
    }

    //测试
    private function ceshi($mkid, $content = '') {
        //检查是否有测试未完成
        $sql = "select * from rwfp_mokuai where mokuai_status=9 and isdel=0 and mokuai_test_userid=" . admin_id();
        $mokuai = $this->M_common->query_one($sql);
        if (count($mokuai) > 0) {
            //请先暂停其他测试
            echo result_to_towf_new(array(), 0, '您的“' . $mokuai['title'] . '”正在测试，请先暂停，再进行新的测试。', "");
        } else {
            //判断模块是否已经有人在测
            $sql = "select * from rwfp_mokuai where mokuai_status=8 and isdel=0 and id=" . $mkid;
            $mokuai2 = $this->M_common->query_one($sql);
            if (count($mokuai2) > 0) {
                if ($mokuai2["mokuai_test_userid"] > 0 && $mokuai2["mokuai_test_userid"] != admin_id()) {
                    $sql = "select * from 57sy_common_system_user where id=" . $mokuai2["mokuai_test_userid"];
                    $usermodel = $this->M_common->query_one($sql);
                    echo result_to_towf_new(array(), 0, $usermodel["username"] . '正负责此模块的测试，你不能测试。', "");
                    exit;
                }
            }

            $sql = "select * from rwfp_mokuai where id=$mkid";
            $model = $this->M_common->query_one($sql);
            $newjob["xiangmu_id"] = $model["pid"];
            $newjob["mokuai_id"] = $model["id"];
            $newjob["starttime"] = time();
            $newjob["endtime"] = 0;
            $newjob["userhours"] = 0;
            $newjob["beizhu"] = login_name() . "开始测试";
            $newjob["mokuai_userid"] = admin_id();
            $username = $this->M_common->query_one("select * from 57sy_common_system_user where id=" . admin_id());
            $newjob["mokuai_username"] = $username["username"];
            $newjob["userseconds"] = 0;
            $return = $this->M_common->insert_one("rwfp_mokuai_usertime", $newjob);

            //记录状态变更
            $insertid = $this->mokuai_status_log($mkid, "9"); //测试中	
            //写入返测试的原因内容
            if (!empty($content)) {
                $this->M_common->update_data2("rwfp_mokuai_status_log", array('content' => $content), array("id" => $insertid));
            }


            //更改当前任务状态
            $zd = $this->M_common->query_one("select * from rwfp_zidian where id=9");
            $model["mokuai_status"] = $zd["id"];
            $model["mokuai_status_title"] = $zd["title"];
            $model["update_userid"] = admin_id();
            $model["update_time"] = time();
            $model["update_username"] = login_name();
            //  $model["mokuai_test_userid"] = admin_id();
            //  $model["mokuai_test_username"] = login_name();
            //反测试时的时候把之前做好的实际结束时间清空
            if ($model["shiji_end_time"] != '') {
                $model["shiji_end_time"] = '';
            }

            $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
            //file_put_contents("e:/aa.txt",$array['sql']);
            if ($array['affect_num'] >= 1) {
                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "开始测试" . $model['title'] . "");
                echo result_to_towf_new(array(), 1, '操作成功', "");
            } else {
                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "不能开始测试" . $model['title'] . "");
            }
        }
    }

    //反测试内容保存
    private function fan_ceshi($mkid, $content) {
        //检查是否有测试未完成
        $sql = "select * from rwfp_mokuai where mokuai_status=9 and isdel=0 and mokuai_test_userid=" . admin_id();
        $mokuai = $this->M_common->query_one($sql);
        if (count($mokuai) > 0) {
            //请先暂停其他测试
            echo result_to_towf_new(array(), 0, '您的“' . $mokuai['title'] . '”正在测试，请先暂停，再进行新的测试。', "");
        } else {
            //判断模块是否已经有人在测
            $sql = "select * from rwfp_mokuai where mokuai_status=8 and isdel=0 and id=" . $mkid;
            $mokuai2 = $this->M_common->query_one($sql);
            if (count($mokuai2) > 0) {
                if ($mokuai2["mokuai_test_userid"] > 0 && $mokuai2["mokuai_test_userid"] != admin_id()) {
                    $sql = "select * from 57sy_common_system_user where id=" . $mokuai2["mokuai_test_userid"];
                    $usermodel = $this->M_common->query_one($sql);
                    echo result_to_towf_new(array(), 0, $usermodel["username"] . '正负责此模块的测试，你不能测试。', "");
                    exit;
                }
            }

            //过滤script标签
            $content = htmlspecialchars_decode($content);
            $content = preg_replace("@<script(.*?)</script>@is", htmlspecialchars($content), $content);
            $repeat_test["content"] = $content;
            $repeat_test["beizhu"] = cn_substr(strip_tags($content), 200);
            $repeat_test["mkid"] = $mkid;
            $repeat_test["test_id"] = admin_id();
            $array = $this->M_common->insert_one("rwfp_repeat_test", $repeat_test);

//            if ($array['affect_num'] >= 1) {
//                echo result_to_towf_new(array(), 1, '操作成功', "");
//                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "反测试：" . $mkid);
//            }
            $this->ceshi($mkid, $repeat_test["content"]);
        }
    }

    private function change_stat() {
        //计算时数
        //查找时数表检查endtime是否为空
        $mkid = $this->input->get_post("id");
        $lock_value = $this->input->get_post("is_lock");
        $sql = "select * from rwfp_mokuai_usertime where endtime='' and mokuai_id=$mkid and mokuai_userid=" . admin_id();
        $time_model = $this->M_common->query_one($sql);
        $time_id = 0;
        if (count($time_model) > 0) {
            $time_id = $time_model["id"];
            $time_model["is_control"] = $lock_value;
            $this->M_common->update_data2(
                    "rwfp_mokuai_usertime", $time_model, array("id" => $time_id)
            );
        }
    }

    //测试暂停
    private function test_zhanting($mkid, $showmsg = true) {
        //计算时数
        //查找时数表检查endtime是否为空
        $sql = "select * from rwfp_mokuai_usertime where endtime='' and mokuai_id=$mkid and mokuai_userid=" . admin_id();
        $time_model = $this->M_common->query_one($sql);


        $time_id = 0;
        if (count($time_model) > 0) {
            //判断操作人是否为本人
            if ($time_model["mokuai_userid"] != admin_id()) {
                echo result_to_towf_new(array(), 0, '您不是测试员本人不能操作。', "");
                exit;
            } else {
                $time_id = $time_model["id"];
                $time_model["endtime"] = time();
                $time_model["userhours"] = doubleval(($time_model["endtime"] - $time_model["starttime"]) / (60 * 60));
                $time_model["userseconds"] = ($time_model["endtime"] - $time_model["starttime"]);
                $time_model["beizhu"] = login_name() . "主动暂停测试";
                $time_model["is_control"] = 0;
                $this->M_common->update_data2(
                        "rwfp_mokuai_usertime", $time_model, array("id" => $time_model["id"])
                );
            }
        }
        //file_put_contents("e:/aa.txt"	,time());
        //暂停当前测试
        $zd = $this->M_common->query_one("select * from rwfp_zidian where id=22");
        $sql = "select * from rwfp_mokuai where id=$mkid";
        $model = $this->M_common->query_one($sql);
        $model["mokuai_status"] = $zd["id"];

        //记录状态变更
        $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
        //统计当前工作的实际时数
        $sql = "select sum(userhours) as dd from rwfp_mokuai_usertime where mokuai_userid=" . $model["mokuai_test_userid"] . " and mokuai_id=" . $model["id"];
        $sum = $this->M_common->query_one($sql);
        if (count($sum) > 0) {
            $model["shiji_test_hours"] = $sum["dd"];
        }
        $model["mokuai_status_title"] = $zd["title"];
        $model["update_userid"] = admin_id();
        $model["update_time"] = time();
        $model["update_username"] = login_name();
        $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
        if ($showmsg) {
            echo result_to_towf_new(array(), 1, '操作成功', "");
        }
        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "暂停测试：" . $model["title"]);
        }
        return $time_id;
    }

    //测试不通过
    private function test_butongguo($mkid, $content) {
        $insert_id = $this->test_zhanting($mkid);
        //更改工作状态为完成
        $zd = $this->M_common->query_one("select * from rwfp_zidian where id=18");
        $sql = "select * from rwfp_mokuai where id=$mkid";
        $model = $this->M_common->query_one($sql);
        $model["mokuai_status"] = $zd["id"];
        //记录状态变更
        $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
        $model["mokuai_status_title"] = $zd["title"];
        $model["update_userid"] = admin_id();
        $model["update_time"] = time();
        $model["update_username"] = login_name();
        $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
        //不通过内容，写入表
        if ($insert_id > 0) {
            $butongguo = $this->M_common->query_one("select * from rwfp_mokuai_usertime where id=$insert_id");

            //过滤script标签
            $content = htmlspecialchars_decode($content);
            $content = preg_replace("@<script(.*?)</script>@is", htmlspecialchars($content), $content);

            $butongguo["content"] = $content;
            $butongguo["beizhu"] = cn_substr(strip_tags($content), 200);
            $tongguo["is_control"] = 0; //解除控制
            $aaa = $this->M_common->update_data2("rwfp_mokuai_usertime", $butongguo, array("id" => $insert_id));
            //file_put_contents("e:/aa.txt","aaa=".$insert_id);
            //echo result_to_towf_new(array(),1, '工作完成等待测试结果', "");	  
        }

        //    测试员，测试不通过，通知开发人员
        $id = $model['mokuai_userid']; //用户id
        //  $message = "<span style='color:red;font-size:bold;'>" . mb_substr($model['title'], 0, 4, 'utf8') . "...测试不通过</span>";
        $message = "<span style='color:red;font-size:bold;'>编号 " . $mkid . "...测试不通过</span>";
        $this->tongzhi_message_send_one($id, $message, $mkid);
        //发送邮件
        $userinfo = $this->userinfo($id);
        if ($userinfo['is_receive'] == 1) {
            $to = $userinfo['email'];
            $subject = '测试不通过';
            $this->send_email($to, $subject, $message);
        }



        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "完成任务：" . $model["title"]);
        }
    }

    //纠正之前没有结束任务的方法
    /*
      function test() {
      $sql = "SELECT id FROM `rwfp_mokuai` where mokuai_status=23  and shiji_end_time is null";
      $list = $this->M_common->querylist($sql);
      foreach ($list as $k => $v) {
      $mkid = $v['id'];
      $sql = "select * from rwfp_mokuai where id=$mkid";
      $model = $this->M_common->query_one($sql);

      $sql = "select * from rwfp_mokuai_usertime where mokuai_id=$mkid and mokuai_userid={$model['mokuai_userid']} order by id desc limit 1";
      //            echo $sql;exit;
      $data = $this->M_common->query_one($sql);
      //            print_r($data);exit;
      $model["shiji_end_time"] = $data['endtime']; //测试通过时更新实际完成时间
      //$model["shiji_end_time"] = time(); //测试通过时更新实际完成时间
      $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
      }
      }
     * 
     */

    private function test_tongguo($mkid, $content) {
        //先暂停测试任务
        $insert_id = $this->test_zhanting($mkid, false);
        //更改工作状态为完成 测试通过
        $zd = $this->M_common->query_one("select * from rwfp_zidian where id=10");
        $sql = "select * from rwfp_mokuai where id=$mkid";
        $model = $this->M_common->query_one($sql);
        $model["mokuai_status"] = $zd["id"];


// //        判断是否需要测试
        if ($model["mokuai_test_userid"] > 0) {
            //当测试通过的直接把编码完成状态改为任务完成
            $model["mokuai_status"] = "23";
            $zd = $this->M_common->query_one("select * from rwfp_zidian where id=23");
            $model["mokuai_status_title"] = $zd["title"];
        }


        //记录状态变更
        $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
        $model["update_userid"] = admin_id();
        $model["update_time"] = time();
        $model["update_username"] = login_name();

        $sql = "select * from rwfp_mokuai_usertime where mokuai_id=$mkid and mokuai_userid={$model['mokuai_userid']} order by id desc limit 1";
        $data = $this->M_common->query_one($sql);
        $model["shiji_end_time"] = $data['endtime']; //测试通过时更新实际完成时间
        //$model["shiji_end_time"] = time(); //测试通过时更新实际完成时间
        $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));

        //通过内容，写入表
        if ($insert_id > 0) {
            $tongguo = $this->M_common->query_one("select * from rwfp_mokuai_usertime where id=$insert_id");

            //过滤script标签
            $content = htmlspecialchars_decode($content);
            $content = preg_replace("@<script(.*?)</script>@is", htmlspecialchars($content), $content);

            $tongguo["content"] = $content;
            $tongguo["beizhu"] = cn_substr(strip_tags($content), 200);
            $tongguo["is_control"] = 0; //解除控制
            $aaa = $this->M_common->update_data2("rwfp_mokuai_usertime", $tongguo, array("id" => $insert_id));
            //file_put_contents("e:/aa.txt","aaa=".$insert_id);
            //echo result_to_towf_new(array(),1, '工作完成等待测试结果', "");
        //
        }


        //测试通过，通知管理员组
        $gid = 9; //9 为管理员组
        $zhannei_message = "<span style='color:green;font-size:bold;'>" . mb_substr($model['title'], 0, 4, 'utf8') . "...测试通过</span>";
        $this->tongzhi_message($gid, $zhannei_message, $mkid);

        //发送邮件
        //编号：99,开发者：某某,测试者：某某 计划用时：3.5h,实际用时：2h,标题 ：xxxx. 
        $message = "<span style='color:green;font-size:bold;'>编号 $mkid ,标题 ：{$model['title']},开发者： {$model['mokuai_username']} ,测试者：{$model['mokuai_test_username']} ,计划用时：{$model['jihua_hours']} h,实际用时：" . round($model['shiji_hours'], 2) . "h 测试通过</span>";
        $sql = "select * from 57sy_common_system_user where gid=$gid   ";
        $userinfo = $this->M_common->querylist($sql);
        foreach ($userinfo as $k => $v) {
            if ($v['is_receive'] == 1) {
                $to = $v['email'];
                $subject = '测试通过(管理员接收)';
                if ($this->send_email($to, $subject, $message)) {
                    $id = $v['id'];
                    $this->tongzhi_message_send_one($id, $message, $mkid); //写入数据库
                }
            }
        }

        //测试通过，通知发布人
        $sql = "select * from 57sy_common_system_user where id={$model['create_userid']}  ";
        $userinfo = $this->M_common->query_one($sql);
        if ($userinfo['is_receive'] == 1) {
            $to = $userinfo['email'];
            $subject = '测试通过(发布人接收)';
            if ($this->send_email($to, $subject, $message)) {
                $id = $model['create_userid'];
                $this->tongzhi_message_send_one($id, $message, $mkid); //写入数据库
            }
        }


        //测试员，测试通过，通知开发人员
        $id = $model['mokuai_userid']; //用户id
        $userinfo = $this->userinfo($id);
        if ($userinfo['is_receive'] == 1) {
            $to = $userinfo['email'];
            $subject = '测试通过(开发人员接收)';
            //发送邮件
            if ($this->send_email($to, $subject, $message)) {
                $this->tongzhi_message_send_one($id, $message, $mkid); //写入数据库
            }
        }



        if ($array['affect_num'] >= 1) {
            echo result_to_towf_new(array(), 1, '操作成功', "");
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "测试通过：" . $model["title"]);
        }
    }

    //记录模块状态变更 
    /**
     * 
     * @param type $mkid 模块id
     * @param type $newstatusid 状态id
     * @return type 返回插入id
     */
    private function mokuai_status_log($mkid, $newstatusid) {
        $mk = $this->M_common->query_one("select * from rwfp_mokuai where pid>0 and id=$mkid");
        $pre_zd = $this->M_common->query_one("select * from rwfp_zidian where id=" . $mk["mokuai_status"]);
        $new_zd = $this->M_common->query_one("select * from rwfp_zidian where id=$newstatusid");
        $data["title"] = $new_zd["title"];
        $data["userid"] = admin_id();
        $data["username"] = login_name();
        $data["statusid"] = $newstatusid;
        $data["createtime"] = time();
        $data["beizhu"] = login_name() . "在" . date("Y-m-d H:i", $data["createtime"]) . "将" . $mk["title"] . "状态由" . $pre_zd["title"] . "更改为" . $new_zd["title"];
        $data["pre_status_id"] = $pre_zd["id"];
        $data["pre_status_title"] = $pre_zd["title"];
        $data["mkid"] = $mkid;
        $data["mktitle"] = $mk["title"];
        $array = $this->M_common->insert_one("rwfp_mokuai_status_log", $data);
        return $array["insert_id"];
    }

    //插入实际时数表，自动暂停其他任务
    private function mokuai_usertime($xiangmu_id, $mokuai_id, $userid) {
        $xiangmu = $this->M_common->query_one("select * from rwfp_mokuai where id=$mokuai_id");
        //查找任务表确认正在工作的有无记录 //pid=$xiangmu_id and id=$mokuai_id
        $sql = "select * from rwfp_mokuai where mokuai_status=6 and mokuai_userid=$userid and isdel=0";

        $model = $this->M_common->query_one($sql);
        if (count($model) > 0) {
            //查找时数表检查endtime是否为空
            $sql = "select * from rwfp_mokuai_usertime where endtime='' and mokuai_userid=$userid";
            $time_model = $this->M_common->query_one($sql);
            //file_put_contents("e:/aa.txt",(count($time_model)>0?"yes":"no"));
            if (count($time_model) > 0) {
                if ($time_model["endtime"] > 0) {
                    
                } else {
                    //计算时数
                    $time_model["endtime"] = time();
                    $time_model["userhours"] = doubleval(($time_model["endtime"] - $time_model["starttime"]) / (60 * 60));
                    $time_model["userseconds"] = ($time_model["endtime"] - $time_model["starttime"]);
                    $time_model["beizhu"] = "被“" . $xiangmu["title"] . "”任务打断，当前任务自动暂停。";
                    $this->M_common->update_data2(
                            "rwfp_mokuai_usertime", $time_model, array("id" => $time_model["id"])
                    );

                    //暂停当前工作
                    $zd = $this->M_common->query_one("select * from rwfp_zidian where id=7");
                    $model["mokuai_status"] = "7";

                    //记录状态变更
                    $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
                    //统计当前工作的实际时数
                    $sql = "select sum(userhours) as dd from rwfp_mokuai_usertime where mokuai_userid=" . $model["mokuai_userid"] . " and mokuai_id=" . $model["id"];
                    $sum = $this->M_common->query_one($sql);
                    if (count($sum) > 0) {
                        $model["shiji_hours"] = $sum["dd"];
                    }
                    $model["mokuai_status_title"] = $zd["title"];
                    $model["update_userid"] = admin_id();
                    $model["update_time"] = time();
                    $model["update_username"] = login_name();
                    $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
                }
            } else {

                //找不到时数记录就直接暂停当前任务
                $zd = $this->M_common->query_one("select * from rwfp_zidian where id=7");

                $model["mokuai_status"] = "7";
                //记录状态变更
                $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
                //统计当前工作的实际时数
                $sql = "select sum(userhours) as dd from rwfp_mokuai_usertime where mokuai_userid=" . $model["mokuai_userid"] . " and mokuai_id=" . $model["id"];
                $sum = $this->M_common->query_one($sql);
                if (count($sum) > 0) {
                    $model["shiji_hours"] = $sum["dd"];
                }
                $model["mokuai_status_title"] = $zd["title"];
                $model["update_userid"] = admin_id();
                $model["update_time"] = time();
                $model["update_username"] = login_name();
                $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
            }
        }
        //检查新工作有没有存在时数表中，如果没有，代表是第一次开始工作，需要记录实际开始时间
        $sql = "select count(1) as dd from rwfp_mokuai_usertime where mokuai_id=" . $xiangmu["id"] . " and mokuai_userid=" . $xiangmu["mokuai_userid"];
        $count = $this->M_common->query_one($sql);
        if ($count["dd"] == 0) {
            $xiangmu["shiji_start_time"] = time();
        }
        //没有正在工作的任务，可以直接开始工作
        $newjob["xiangmu_id"] = $xiangmu_id;
        $newjob["mokuai_id"] = $mokuai_id;
        $newjob["starttime"] = time();
        $newjob["endtime"] = 0;
        $newjob["userhours"] = 0;
        $newjob["beizhu"] = "";
        $newjob["mokuai_userid"] = $userid;
        $username = $this->M_common->query_one("select * from 57sy_common_system_user where id=$userid");
        $newjob["mokuai_username"] = $username["username"];
        $newjob["userseconds"] = 0;
        //判断是不是修改，检查是否有测试不通过，如果有，代表开发为修改
        $sql = "select count(1) as dd from rwfp_mokuai_status_log where mkid=$mokuai_id and statusid=18 ";
        $butongguo = $this->M_common->query_one($sql);
        if ($butongguo["dd"] > 0) {
            $newjob["isedit"] = "1";
        } else {
            $newjob["isedit"] = "0";
        }
        $this->M_common->insert_one("rwfp_mokuai_usertime", $newjob);
        //记录状态变更
        $this->mokuai_status_log($mokuai_id, "6");
        //更改当前任务状态
        $zd = $this->M_common->query_one("select * from rwfp_zidian where id=6");
        $xiangmu["mokuai_status"] = "6";
        $xiangmu["mokuai_status_title"] = $zd["title"];
        $xiangmu["update_userid"] = admin_id();
        $xiangmu["update_time"] = time();
        $xiangmu["update_username"] = login_name();
        $array = $this->M_common->update_data2("rwfp_mokuai", $xiangmu, array("id" => $xiangmu["id"]));
        //file_put_contents("e:/aa.txt",$array['sql']);
        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "开始工作" . $xiangmu['title'] . "成功");
        } else {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "开始工作" . $xiangmu['title'] . "失败");
        }
        //file_put_contents("e:/aa.txt","aa");					
    }

//设置参数
    private function set_params() {
        $this->title = html_escape($this->input->get_post("title", true));
        $this->jihua_start_time = daddslashes(html_escape(strip_tags($this->input->get_post("jihua_start_time", true))));
        $this->jihua_end_time = daddslashes(html_escape(strip_tags($this->input->get_post("jihua_end_time", true))));

//        if ($this->input->get_post("need_test", true)) {
//            $this->need_test = daddslashes(html_escape(strip_tags($this->input->get_post("need_test", true))));
//        } else {
//            $this->need_test = 0;
//        }
        //处理测试者数据
        $tester_data = $this->input->get_post("mokuai_test_userid", true);
        if ($tester_data) {
            $tester_data_arr = explode(',', $tester_data);
            $testid = $tester_data_arr[0];
            $test_username = $tester_data_arr[1];

            //需要测试
            $this->need_test = 1;

            //测试者id
            $this->mokuai_test_userid = verify_id($testid);
            $this->mokuai_test_username = $test_username;
        } else {
            //测试者id
            $this->mokuai_test_userid = 0;
            $this->mokuai_test_username = '';

            //不需要测试
            $this->need_test = 0;
        }


        //比较调换，开始时间必须小于结束时间
        if ($this->jihua_start_time > $this->jihua_end_time) {
            $tmp = $this->jihua_end_time;
            $this->jihua_start_time = $this->jihua_end_time;
            $this->jihua_end_time = $tmp;
        }
        $this->content = html_escape($this->input->get_post("content", true));
        /*
          权限控制
          非管理员不能直控制任务状态
         */
        if (!is_super_admin()) {
            $id = verify_id($this->input->get_post("id"));
            if ($id > 0) {
                $sql = "SELECT * FROM rwfp_mokuai WHERE pid>0 and id = '{$id}'";
                $info = $this->M_common->query_one($sql);
                $this->mokuai_status = $info["mokuai_status"];
            } else {
                $this->mokuai_status = "5"; //未接收
            }
        } else {
            $this->mokuai_status = verify_id($this->input->get_post("mokuai_status", true));
        }
        //根据mokuai_status>0读取名称
        if ($this->mokuai_status > 0) {
            // $tmp = $this->M_common->query_one("select * from rwfp_zidian where zidian_flag='mokuai' and id=" . $this->mokuai_status);
            $tmp = $this->M_common->query_one("select * from rwfp_zidian where  id=" . $this->mokuai_status);
            if (is_array($tmp)) {
                $this->mokuai_status_title = $tmp["title"];
            }
        }

        $this->pid = verify_id($this->input->get_post("pid", true));
        $this->jinji = verify_id($this->input->get_post("jinji", true));
        if ($this->jinji > 0) {
            $tmp = $this->M_common->query_one("select * from rwfp_zidian where zidian_flag='jinji' and id=" . $this->jinji);
            if (is_array($tmp)) {
                $this->jinji_title = $tmp["title"];
            }
        }
        $this->mokuai_userid = verify_id($this->input->get_post("mokuai_userid", true));
        if ($this->mokuai_userid > 0) {
            $tmp = $this->M_common->query_one("SELECT * FROM 57sy_common_system_user WHERE id=" . $this->mokuai_userid);
            if (is_array($tmp)) {
                $this->mokuai_username = $tmp["username"];
            }
        }
        $this->mokuai_flag = verify_id($this->input->get_post("mokuai_flag", true));
        if ($this->mokuai_flag > 0) {
            $tmp = $this->M_common->query_one("select * from rwfp_zidian where zidian_flag='mokuai_flag' and id=" . $this->mokuai_flag);
            if (is_array($tmp)) {
                $this->mokuai_flag_name = $tmp["title"];
            }
        }
        $this->jihua_hours = verify_double($this->input->get_post("jihua_hours", true));
    }

    //管理员删除任务
    private function admindel($mkid) {
        $isok = false;
        $array = array();
        $model = array();
        //检查是否管理员
        if (is_super_admin()) {
            $model = $this->M_common->query_one("select * from rwfp_mokuai where id=" . $mkid);
            if (count($model) > 0) {
                $model["isdel"] = 1;
                $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $mkid));
                //删除时数表
                $this->M_common->del_data("delete from rwfp_mokuai_usertime where mokuai_id=" . $mkid);
                $isok = true;
            }
        }

        if ($isok) {
            if ($array['affect_num'] >= 1) {
                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "删除成功，编号:$mkid," . $model["title"]);
                echo result_to_towf_new(array(), 1, '操作成功', "");
            } else {
                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "删除失败，编号:$mkid," . $model["title"]);
                echo result_to_towf_new(array(), 0, '操作失败', "");
            }
        } else {
            write_action_log("", $this->uri->uri_string(), login_name(), get_client_ip(), 0, "删除失败，编号：$mkid");
            echo result_to_towf_new(array(), 0, '操作失败', "");
        }
    }

    //取消任务
    private function admin_cancel($mkid, $content, $save_shishu) {
        $isok = false;
        $array = array();
        //检查是否管理员
        $model = array();
        $log_id = 0;
        if (is_super_admin()) {
            //更改工作状态为完成
            $zd = $this->M_common->query_one("select * from rwfp_zidian where id=19");
            $sql = "select * from rwfp_mokuai where id=$mkid";
            $model = $this->M_common->query_one($sql);
            if ($model["mokuai_status"] == "6") {
                //暂停工作
                $this->zhanting($mkid, false);
            }
            if ($model["mokuai_status"] == "9") {
                //暂停测试
                $this->test_zhanting($mkid, false);
            }
            $model["mokuai_status"] = $zd["id"];
            //记录状态变更
            $log_id = $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
            $model["mokuai_status_title"] = $zd["title"];
            $model["update_userid"] = admin_id();
            $model["update_time"] = time();
            $model["update_username"] = login_name();
            $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
            //不通过内容，写入表
            if ($log_id > 0) {
                $quxiao = $this->M_common->query_one("select * from rwfp_mokuai_status_log where id=$log_id");
                $quxiao["content"] = $content;
                $quxiao["beizhu"] = cn_substr(strip_tags($content), 200);
                $aaa = $this->M_common->update_data2("rwfp_mokuai_status_log", $quxiao, array("id" => $log_id));
                //file_put_contents("e:/aa.txt","aaa=".$insert_id);
                //echo result_to_towf_new(array(),1, '工作完成等待测试结果', "");	  
            }
            //不保留时数
            if ($save_shishu != "yes") {
                //删除时数表
                $this->M_common->del_data("delete from rwfp_mokuai_usertime where mokuai_id=" . $mkid);
            }
            $isok = true;
        }
        if ($isok) {
            if ($array['affect_num'] >= 1) {
                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "取消成功，编号:$mkid," . $model["title"]);
                echo result_to_towf_new(array(), 1, '操作成功', "");
            } else {
                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "取消失败，编号:$mkid," . $model["title"]);
                echo result_to_towf_new(array(), 0, '操作失败', "");
            }
        } else {
            write_action_log("", $this->uri->uri_string(), login_name(), get_client_ip(), 0, "取消失败，编号：$mkid");
            echo result_to_towf_new(array(), 0, '操作失败', "");
        }
    }

    private function admin_jieshu($mkid) {
        $isok = false;
        $array = array();
        //检查是否管理员
        $model = array();
        $log_id = 0;
        if (is_super_admin()) {
            //更改工作状态为完成
            $zd = $this->M_common->query_one("select * from rwfp_zidian where id=11");
            $sql = "select * from rwfp_mokuai where id=$mkid";
            $model = $this->M_common->query_one($sql);
            if ($model["mokuai_status"] == "6") {
                //暂停工作
                $this->zhanting($mkid, false);
            }
            if ($model["mokuai_status"] == "9") {
                //暂停测试
                $this->test_zhanting($mkid, false);
            }
            $model["mokuai_status"] = $zd["id"];


            //记录状态变更
            $log_id = $this->mokuai_status_log($model["id"], $model["mokuai_status"]);
            $model["mokuai_status_title"] = $zd["title"];
            $model["update_userid"] = admin_id();
            $model["update_time"] = time();
            $model["update_username"] = login_name();
            $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
            $isok = true;


            //修改开发者最后更新时间 xie 2014.09.11  不在经过测试者测试，直接结束的话 那个最后时间为空的，所以用这段代码加上那个最后时间
            $sql = "select * from rwfp_mokuai where id=$mkid  limit 1";
            $query = $this->M_common->query_one($sql);
            $mokuai_userid = $query['mokuai_userid'];
            $shiji_end_time = $query['shiji_end_time'];
            if (empty($shiji_end_time)) {
                $sql = "select * from rwfp_mokuai_usertime where mokuai_id=$mkid and mokuai_userid={$mokuai_userid} order by id desc limit 1";
                $query = $this->M_common->query_one($sql);
                $model_data["shiji_end_time"] = $query['endtime']; //测试通过时更新实际完成时间
                $array = $this->M_common->update_data2("rwfp_mokuai", $model_data, array("id" => $mkid));
            }
        }
        if ($isok) {
            if ($array['affect_num'] >= 1) {

                //   管理员操作，模块结束，通知开发人员和测试人员 
                $id = $model['mokuai_userid']; //开发id
                $message = "<span style='color:green;font-size:bold;'>编号 " . $mkid . "...结束任务</span>";
                $this->tongzhi_message_send_one($id, $message, $mkid);
                //发送邮件
                $userinfo = $this->userinfo($id);

                if ($userinfo['is_receive'] == 1) {
                    $to = $userinfo['email'];
                    $subject = '结束任务';
                    $this->send_email($to, $subject, $message);
                }

                $test_id = $model['mokuai_test_userid']; //测试id
                //如果没有测试者就不发了
                if ($test_id != 0) {
                    $message = "<span style='color:green;font-size:bold;'>编号 " . $mkid . "...结束任务</span>";
                    $this->tongzhi_message_send_one($test_id, $message, $mkid);
                    //发送邮件
                    $userinfo = $this->userinfo($test_id);
                    if ($userinfo['is_receive'] == 1) {
                        $to = $userinfo['email'];
                        $subject = '结束任务';
                        $this->send_email($to, $subject, $message);
                    }
                }



                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "结束成功，编号:$mkid," . $model["title"]);
                echo result_to_towf_new(array(), 1, '操作成功', "");
            } else {
                write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "结束失败，编号:$mkid," . $model["title"]);
                echo result_to_towf_new(array(), 0, '操作失败', "");
            }
        } else {
            write_action_log("", $this->uri->uri_string(), login_name(), get_client_ip(), 0, "结束失败，编号：$mkid");
            echo result_to_towf_new(array(), 0, '操作失败', "");
        }
    }

    public function check_message() {
        //超级用户
        //查出超级管理员id列表
        $sql = "select id from  57sy_common_system_user where super_admin=1 ";
        $super_admin_data = $this->M_common->querylist($sql);
        //处理2维数组
        foreach ($super_admin_data as $val) {
            $super_admin_ids[] = $val['id'];
        }
        if (!empty($super_admin_ids) && in_array($this->admin_id, $super_admin_ids)) {
            //查出发给管理员信息的总数
            $sql = "select  message.*,m.title m_title from rwfp_message message left join rwfp_mokuai m on m.id=message.mokuai_id  where is_know=0  and send_id=$this->admin_id ";
            $admin_message_data = $this->M_common->querylist($sql);
            $count = count($admin_message_data);

            if (!empty($count)) {
                //遍历模块id
                foreach ($admin_message_data as $k => $v) {
                    $mokuai_ids[] = $v['mokuai_id'];
                }
                $str_ids = implode(',', $mokuai_ids);
                $url = base_url() . "admin.php/mokuai/lists";

                $message = "<div><a style='cursor:pointer;' onclick=\"window.parent.urlgo('$url','$str_ids');return false;\">你共有{$count}条信息</a></div>";
                echo json_encode(array('message' => $message, 'mokuai_id' => $str_ids));
            }
        } else {
            // 普通用户
            $sql = "select message.*,m.title m_title from rwfp_message message left join rwfp_mokuai m on m.id=message.mokuai_id  where is_know=0  and send_id=$this->admin_id limit 1 ";
            $data = $this->M_common->query_one($sql);
            if (!empty($data)) {
                echo json_encode($data);
            }
        }
    }

    public function change_message_stat() {
        $data = array();
        $data['is_know'] = 1;
        $mkid = daddslashes(html_escape(strip_tags($this->input->get_post("mkid", true))));
        //多个id同时改为1 不再提示
        $array_kid = explode(',', $mkid);
        foreach ($array_kid as $k => $v) {
            $array = $this->M_common->update_data2("rwfp_message", $data, array("mokuai_id" => $v, 'send_id' => $this->admin_id));
        }
    }

    /**
     * @param type $gid    //$gid为需要通知的组，10为测试员
     * @param type $message 提示信息
     * @param type $mkid  需要测试的模块id
     * @return void
     */
    public function tongzhi_message($gid, $message, $mkid) {
        $sql = "select id from 57sy_common_system_user where gid=$gid and super_admin=0 ";
        $test_data = $this->M_common->querylist($sql);
        foreach ($test_data as $k => $v) {
            //测试通过后,把需要测试的模块id写入信息表
            $message_data['mokuai_id'] = $mkid;
            $message_data['send_id'] = $v['id'];
            $message_data['message'] = $message;
            $this->M_common->insert_one('rwfp_message', $message_data);
        }
    }

    /**
     * 
     * @param type $id 发送id
     * @param type $message 信息
     * @param type $mkid 模块id
     */
    public function tongzhi_message_send_one($id, $message, $mkid) {
        $message_data['mokuai_id'] = $mkid;
        $message_data['send_id'] = $id;
        $message_data['message'] = $message;
        $this->M_common->insert_one('rwfp_message', $message_data);
    }

    /**
     * 数据导出处理
     */
    public function php_export() {
        $dump_header = "<tr>
            <td align='center'>编号</td>
            <td align='center'>开发者</td>
            <td align='center'>任务简述</td>
            <td align='center'>计划开始时间</td>
            <td align='center'>计划结束时间</td>
            <td align='center'>计划用时(小时)</td>
             <td align='center'>实际开始时间</td>
            <td align='center'>实际结束时间</td>
            <td align='center'>实际用时(小时)</td>
            <td align='center'>完成时差(小时)</td>
                </tr>";
        $dump_text = "";
        $sea_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title", true)))));
        $sea_pid = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_pid", true)))));
        $sea_mokuai_status = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_status", true)))));
        $sea_jinji = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_jinji", true)))));
        $sea_mokuai_userid = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_userid", true)))));
        $search_mokuai_create_time = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_create_time", true)))));
        $search_mokuai_jihua_time = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_jihua_time", true)))));
        $search_mokuai_shiji_end_time = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_mokuai_shiji_end_time", true)))));
        $where = '';
        if (!empty($sea_title)) {
            //改为可以查询id号
            $where.=" AND ( (t1.title like '%$sea_title%') or ( t1.id in ($sea_title) ) )";
        }
        if ($sea_pid > 0) {
            $where.=" AND t1.pid=" . $sea_pid;
        }
        if ($sea_mokuai_status > 0) {
            $where.=" AND t1.mokuai_status=" . $sea_mokuai_status;
        }
        if ($sea_mokuai_userid > 0) {
            $where.=" AND (t1.mokuai_userid=" . $sea_mokuai_userid . " or t1.mokuai_test_userid=" . $sea_mokuai_userid . ")";
        }
        if ($search_mokuai_create_time) {
//            $where.=" AND   FROM_UNIXTIME(t1.create_time, '%Y-%m' )  = '$search_mokuai_create_time'";
            $where.=" AND   FROM_UNIXTIME(t1.jihua_start_time, '%Y-%m' )  = '$search_mokuai_create_time'";
        }

        if ($search_mokuai_jihua_time) {
            $where.=" AND   FROM_UNIXTIME(t1.jihua_start_time, '%Y-%m' )  = '$search_mokuai_jihua_time'";
        }
        if ($search_mokuai_shiji_end_time) {
            $where.=" AND   FROM_UNIXTIME(t1.shiji_end_time, '%Y-%m' )  = '$search_mokuai_shiji_end_time'";
        }


        if ($sea_jinji > 0) {
            $where.=" AND t1.jinji=" . $sea_jinji;
        }

        //19任务取消 5未接收 20已接收;
        $sql = "SELECT t1.*,t2.title as pro_title FROM rwfp_mokuai t1 left join rwfp_mokuai t2 on t1.pid=t2.id  where 1  {$where} and t1.isdel=0 and t1.mokuai_status not in(19,5,20) order by id desc ";
//        echo $sql;exit;
        $lists = $this->M_common->querylist($sql);
        foreach ($lists as $k => $v) {
            $shicha = $v['jihua_hours'] - $v['shiji_hours'];
            $dump_text .="<tr>";
            $dump_text .="<td align='center'>{$v['id']}</td>";
            $dump_text .="<td align='center'>{$v['mokuai_username']}</td>";
            $dump_text .="<td align='center'>{$v['title']}</td>";
            $dump_text .="<td align='center'>" . date('Y-m-d H:i:s', $v['jihua_start_time']) . "</td>";
            $dump_text .="<td align='center'>" . date('Y-m-d H:i:s', $v['jihua_end_time']) . "</td>";
            $dump_text .="<td align='center'>{$v['jihua_hours']}</td>";
            $dump_text .="<td align='center'>" . date('Y-m-d H:i:s', $v['shiji_start_time']) . "</td>";
            $dump_text .="<td align='center'>" . date('Y-m-d H:i:s', $v['shiji_end_time']) . "</td>";
            $dump_text .="<td align='center'>{$v['shiji_hours']}</td>";
            $dump_text .="<td align='center'>{$shicha}</td>";
            $dump_text .="</tr>";
        }
        file_put_contents('header.txt', $dump_header);
        file_put_contents('outexport.txt', $dump_text);
    }

    /**
     * 执行导出
     */
    public function output() {
        $dump_header = file_get_contents('header.txt');
        $dump_text = file_get_contents('outexport.txt');
        $filename = iconv("utf-8", "gbk//IGNORE", "开发者数据导出.xls");
        header("Content-type:aplication/vnd.ms-excel;charset=gbk");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: public');
        header("Content-Transfer-Encoding: binary ");


        $dump_text = iconv("utf-8", "gbk//IGNORE", "<table width='100%' border='1'>" . $dump_header . "\n" . $dump_text . "</table>");
        echo $dump_text;
        @unlink('header.txt');
        @unlink('outexport.txt');
    }

    /**
     * 发送邮件
     * @param type $to 发给
     * @param type $subject 标题
     * @param type $message 内容
     */
    public function send_email($to, $subject, $message) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.163.com',
            'smtp_port' => 994,
            'smtp_user' => 'zhida_renwuxitong@163.com', //新开的163邮箱账号
            'smtp_pass' => 'qwe123',
            'mailtype' => 'html',
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $this->email->from('zhida_renwuxitong@163.com', '任务系统消息');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  查看用户信息
     * @param type $uid 用户id
     * @return array
     */
    public function userinfo($uid) {
        $sql = "select * from {$this->table_}common_system_user where id= $uid ";
        $userinfo = $this->M_common->query_one($sql);
        return $userinfo;
    }

    /**
     * 统计开发者信息
     */
    public function devlist() {

//          $sql = "SELECT  FROM_UNIXTIME(starttime,'%Y-%m') time   FROM `rwfp_mokuai_usertime` where   mokuai_userid={$uid}  group by FROM_UNIXTIME(starttime,'%Y-%m') order by starttime desc";
        $sql = "SELECT  FROM_UNIXTIME(starttime,'%Y-%m') time   FROM `rwfp_mokuai_usertime`   group by FROM_UNIXTIME(starttime,'%Y-%m') order by starttime desc";

        $time_list = $this->M_common->querylist($sql);


        $sql = "select id,title from rwfp_mokuai where pid=0";
        $prolist = $this->M_common->querylist($sql);
        $data = array(
            'prolist' => $prolist,
            'time_list' => $time_list,
        );
        $action = $this->input->get_post("action");
        $action_array = array("show", "ajax_data");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        if ($action == 'show') {
            $this->load->view(__TEMPLET_FOLDER__ . "/views_dev_tongji", $data);
        } elseif ($action == 'ajax_data') {
            $this->devajax_data();
        }
    }

    //ajax 获取数据
    private function devajax_data() {
        $this->load->library("common_page");
        $page = $this->input->get_post("page");
        if ($page <= 0) {
            $page = 1;
        }
        $per_page = 10; //每一页显示的数量
        $limit = ($page - 1) * $per_page;
        $limit.=",{$per_page}";

        $where = ' where 1 and isdel=0 ';
        $pro_id = daddslashes(html_escape(strip_tags(trim($this->input->get_post("pro_id", true)))));
        $start_time = strtotime(daddslashes(html_escape(strip_tags(trim($this->input->get_post("start_time", true))))));
        $end_time = strtotime(daddslashes(html_escape(strip_tags(trim($this->input->get_post("end_time", true))))));
        $renshu = '';
        if (!empty($pro_id) && $pro_id != 'all') {
            $where.=" AND pid = {$pro_id}";
            $sql = " select distinct mokuai_userid from rwfp_mokuai where pid={$pro_id}";
            $list = $this->M_common->querylist($sql);
            foreach ($list as $k => $v) {
                $ids[] = $v['mokuai_userid'];
            }
            $idstr = implode(',', $ids);
            $renshu = " and id in($idstr)";
        }

        $not_in_time = '';
        if (!empty($start_time) && !empty($end_time)) {
            //使用开始和结束时间
            $where.=" and shiji_start_time between $start_time  and  $end_time";
        }

        //分页总数
        $sql_count = "SELECT count(*) FROM 57sy_common_system_user where 1 and  status=1 and gid=8 ";
        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string($total, $per_page, $page);
        //mokuai_status 19任务取消 5未接收 20已接收

        $sql_role = "
            SELECT 
            
            -- 开发者姓名
            username,
            id,
            
            -- 任务总数 
            (select count(*) from rwfp_mokuai $where and mokuai_userid=A.id and mokuai_status not in (19,5,20) ) AS renwu_total,

            -- 超时任务数
            (select count(*) from rwfp_mokuai $where and  jihua_hours - shiji_hours < 0  and mokuai_userid=A.id and mokuai_status not in (19,5,20) ) AS over_time,
                

            -- 计划时间
            (select ifnull(round(sum(jihua_hours),2),0) from rwfp_mokuai $where and  mokuai_userid=A.id and mokuai_status  in (23) ) AS jihua_hours,
                
            -- 实际时间
            (select ifnull(round(sum(shiji_hours),2),0) from rwfp_mokuai $where and  mokuai_userid=A.id and mokuai_status  in (23) ) AS shiji_hours,
                
            -- 时差
            (select ifnull(round((sum(jihua_hours))-(sum(shiji_hours)),2),0) from rwfp_mokuai $where and  mokuai_userid=A.id and mokuai_status  in (23) ) AS wancheng_shicha,
           

            --  未完成     
             (select count(*) from rwfp_mokuai  $where and  mokuai_userid=A.id and mokuai_status  in (6,22,7,8,9,18) order by mokuai_status ) not_wancheng ,
                 
            -- 完成     
             (select count(*) from rwfp_mokuai  $where and  mokuai_userid=A.id  and mokuai_status  in (23,11) order by mokuai_status )  wancheng ,
                 
            -- 未完成任务总数：跨时间段的任务数
                 (select count(*) from rwfp_mokuai  $where and  jihua_end_time < shiji_end_time and   mokuai_userid=A.id and mokuai_status  in (6,7,8,9,11,18,22,23) order by mokuai_status)  not_in_time 
                     
            from 57sy_common_system_user A 
            where 1 and STATUS=1
            {$renshu} 
            and gid=8
            limit {$limit}
        ";
//           echo $sql_role;exit;
        $list = $this->M_common->querylist($sql_role);

//        //获取用户id
        //计算超出指定完成时间小时数
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $sql = "select from_unixtime(jihua_end_time) as jihua_end_time ,from_unixtime(shiji_end_time) as shiji_end_time from rwfp_mokuai  $where and  jihua_end_time < shiji_end_time and   mokuai_userid={$v['id']} and mokuai_status  in (23) order by mokuai_status ";




                $time_list[$v['id']] = $this->M_common->querylist($sql);
                foreach ($time_list[$v['id']] as $k1 => $v1) {
//                $riqi_start = "2014-10-29 17:30:00";
//                $riqi_end = "2014-10-31 12:00:00";
                    $riqi_start = $v1['jihua_end_time'];
                    $riqi_end = $v1['shiji_end_time'];
                    $over = 0;
                    $sixAndri = 0;
                    $day = (strtotime($riqi_end) - strtotime($riqi_start)) / 60 / 60 / 24;

                    //取周六日的数目
                    $arr = $this->return_six_ri($riqi_start, $riqi_end);


                    $y_start_y = date('Y', strtotime($riqi_start));  //开始年
                    $y_start = date('m', strtotime($riqi_start));  //开始月
                    $y_start_day = date('d', strtotime($riqi_start));  //开始日
                    $y_start_h = date('H', strtotime($riqi_start));  //截止小时
                    $y_start_m = date('i', strtotime($riqi_start));  //截止分钟
                    $y_start_s = date('s', strtotime($riqi_start));  //截止秒

                    $y_end_y = date('Y', strtotime($riqi_end));  //截止年
                    $y_end = date('m', strtotime($riqi_end));  //截止月
                    $y_end_day = date('d', strtotime($riqi_end));  //截止日
                    $y_end_h = date('H', strtotime($riqi_end));  //截止小时
                    $y_end_m = date('i', strtotime($riqi_end));  //截止分钟
                    $y_end_s = date('s', strtotime($riqi_end));  //截止秒
                    //同时大于0才减
                    if ($arr['six'] > 0 && $arr['ri'] > 0) {
                        $sixAndri = ($arr['six'] * 3.5) + ($arr['ri'] * 7);
                    }


                    //早上
                    if (($y_end_h == 8 && $y_end_m >= 30 ) | $y_end_h >= 9 && $y_end_h <= 13) {
                        //休息时间12-13
                        if (($y_end_h >= 12 && $y_end_m > 0) && $y_end_h <= 13) {
                            // echo 2;exit;
                            $end_time_biaozhun = strtotime(date('Y-m-d', strtotime($riqi_end)) . ' 12:00:00');
                            $second = strtotime($riqi_end) - $end_time_biaozhun;
                            $biaozhunHour = $second / 60 / 60;
                            $over = $over ? "0" : $biaozhunHour;
                        }



                        //正常工作时间
                        // 当天的开始早上，结束是早上
                        if ($y_start_day == $y_end_day && $y_start_h <= 12 && $y_end_h <= 13) {

                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;


                            // 天数不同的开始下午，结束是早上
                        } elseif (($y_start_day <= $y_end_day || $y_start < $y_end) && $y_start_h >= 14 && $y_end_h <= 13) {

                            // 天数不同的开始下午，结束是早上并且隔2天或以上
                            if (($y_end_day - $y_start_day) >= 2 && $y_start_h >= 14 && $y_end_h <= 13) {
                                $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * 17) + 2 - $over - $sixAndri;
                                $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                                $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                                $time_list[$v['id']][$k1]['over'] = $over;
                                $time_list[$v['id']][$k1]['sixAndri'] = $sixAndri;
                            } else {


                                $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * 15) - $over - $sixAndri;
                                $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                                $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                                $time_list[$v['id']][$k1]['over'] = $over;
                            }

                            // 天数不同的开始早上，结束是早上
                        } elseif (( $y_start_day <= $y_end_day || $y_start < $y_end ) && $y_start_h <= 12 && $y_end_h <= 13) {
                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * 17) - $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;
                        }
                    }

                    //下午
                    elseif ($y_end_h >= 14) {
                        $end_time_biaozhun = strtotime(date('Y-m-d', strtotime($riqi_end)) . ' 17:30:00');
                        $second = strtotime($riqi_end) - $end_time_biaozhun;
                        $biaozhunHour = $second / 60 / 60;

                        //下班过后
                        if (($y_end_h >= 17 && $y_end_m > 30) | $y_end_h >= 18) {

                            $over = $over ? "0" : $biaozhunHour;
                        }

                        //下午正常工作时间
                        //当天的开始早上，结束是下午
                        if ($y_start_day == $y_end_day && $y_start_h <= 12 && $y_end_h >= 14) {

                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * (17)) - (round($day) * 2) - $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;
                        } elseif ($y_start_day == $y_end_day && $y_start_h >= 14 && $y_end_h >= 14) {

                            // 当天的开始下午，结束是下午
                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * (17)) - $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;
                        } elseif ($y_start_day < $y_end_day && $y_start_h <= 12 && $y_end_h >= 14) {

                            // 天数不同的开始早上，结束是下午
                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * (17)) - (round($day) * 2) - $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;
                        } elseif (($y_start_day < $y_end_day || $y_start < $y_end) && $y_start_h >= 14 && $y_end_h >= 14) {
                            // $time_list[$v['id']][$k1]['overtime'] = 5;exit;
                            // 天数不同的开始下午，结束是下午
                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * (17)) - $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;
                            $time_list[$v['id']][$k1]['sixAndri'] = $sixAndri;
                        }
                    }
                    //8点30分前
                    elseif ($y_end_h <= 8) {
                        // 天数不同的开始下午，结束是早上并且隔2天或以上
                        $end_time_biaozhun = strtotime(date('Y-m-d', strtotime($riqi_end)) . ' 8:30:00');
                        $second = $end_time_biaozhun - strtotime($riqi_end);
                        $biaozhunHour = $second / 60 / 60;
                        $over = $over ? "0" : $biaozhunHour;

                        //隔2天或以上
                        if (($y_end_day - $y_start_day) >= 2 || $y_start < $y_end) {

                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * 17) + 2 + $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;
                        } else {

                            $time_list[$v['id']][$k1]['overtime'] = $day * 24 - (round($day) * 15) + $over - $sixAndri;
                            $time_list[$v['id']][$k1]['six'] = $arr['six'] * 3.5;
                            $time_list[$v['id']][$k1]['ri'] = $arr['ri'] * 7;
                            $time_list[$v['id']][$k1]['over'] = $over;
                        }
                    }

                    foreach ($list as $k3 => &$v3) {
                        if ($v3['id'] == $v['id']) {
                            $v3['overtime_info'] = $time_list[$v['id']];
                            @$v3['overtime_total'] += $time_list[$v['id']][$k1]['overtime'];
                        }
                    }
                }
            }
        }
//        print_r($time_list);exit;
//        print_r($list); exit;
        $this->session->set_userdata($list);
        echo result_to_towf_new($list, 1, '成功', $page_string);
    }

    function mydate($riqi_start, $riqi_end = 1) {
        $six = 0;
        $ri = 0;
        $return_array = array();
        $return_array['six'] = array();
        $return_array['ri'] = array();
        $total_days = date('t', strtotime($riqi_start)); //当月最大天数
        $str = date('Y-m-', strtotime($riqi_start)); //每日
        $riqi_start = date('d', strtotime($riqi_start));  //开始日期
        $riqi_end = ($riqi_end == 1) ? $str . $total_days : date('d', strtotime($riqi_end));  //截止日期
        $monday = date('w', strtotime($str . '1')); //1号是星期几
        // echo "<br>只显示周六日";
        // echo "<table width='700' border='1'>";
        // echo "<tr>";
        // echo "<th>星期日</th><th>星期一</th><th>星期二</th>";
        // echo "<th>星期三</th><th>星期四</th><th>星期五</th>";
        // echo "<th>星期六</th>";
        // echo "</tr>";
        // echo "<tr>";
        //从第一天开始循环到day天
        for ($d = 1; $d <= $total_days;) {
            for ($i = 0; $i < 7; $i++) {
                $week_ = date('w', strtotime($str . $d)); //每日星期几
                if ((($d <= $total_days && $d > 1) || ($d == 1 && $monday == $i))) {

                    if ($d >= $riqi_start && ($week_ == 0 ) && $d <= $riqi_end) {
                        $return_array['ri'][] = $d;
                        //	echo "<td>{$d}</td>";
                    } else if ($d >= $riqi_start && ($week_ == 6 ) && $d <= $riqi_end) {
                        $return_array['six'][] = $d;
                        //	echo "<td>{$d}</td>";
                    } else {
                        //	echo "<td>&nbsp;</td>";
                    }
                    // echo "<td>{$d}</td>";
                    $d++;
                } else {
                    //	echo "<td>&nbsp;</td>";
                }
            }
            //	echo "</tr><tr>";
        }

//	echo "</tr>";
//	echo "</table>";
//	echo '<pre>';
//        print_r($return_array);
        //统计
        $six = count($return_array['six']);
        $ri = count($return_array['ri']);
        return array('six' => $six, 'ri' => $ri);
    }

    public function return_six_ri($riqi_start, $riqi_end) {
        $six = 0;
        $ri = 0;
        $y_start_y = date('Y', strtotime($riqi_start));  //开始年
        $y_start = date('m', strtotime($riqi_start));  //开始月
        $y_start_day = date('d', strtotime($riqi_start));  //开始日


        $y_end_y = date('Y', strtotime($riqi_end));  //截止年
        $y_end = date('m', strtotime($riqi_end));  //截止月
        $y_end_day = date('d', strtotime($riqi_end));  //截止日
        // 开始月小于结束月
        if ($y_start <= $y_end) {
            $count_y = ($y_end - $y_start) + 1;
        } else {
            // 开始月大于结束月
            $count_y = ($y_end - 1) + (12 - $y_start) + 2;
        }
        $month = array(
            12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
        );
        // 截取片段
        $output_month = array_slice($month, $y_start, $count_y);
        $count_output_month = count($output_month);
        for ($i = 0; $i < $count_output_month; $i++) {

            if ($i == 0) {
                //只有一个月的时候
                if ($count_output_month == 1) {
                    $riqi_start = $y_start_y . '-' . $output_month[$i] . '-' . $y_start_day;
                    $riqi_end = $y_start_y . '-' . $output_month[$i] . '-' . $y_end_day;
                    $return = $this->mydate($riqi_start, $riqi_end);
                    $six += $return['six'];
                    $ri += $return['ri'];
                } else {
                    // 第一个月
                    $riqi_start = $y_start_y . '-' . $output_month[$i] . '-' . $y_start_day;
                    $return = $this->mydate($riqi_start);
                    $six += $return['six'];
                    $ri += $return['ri'];
                }
            } else {
                //开始月小于结束月
                if ($y_start < $y_end) {
                    //最后一个月
                    if ($i == $count_output_month - 1) {

                        $riqi_start = $y_start_y . '-' . $output_month[$i] . '-1';
                        $riqi_end = $y_start_y . '-' . $output_month[$i] . '-' . $y_end_day;
                        $return = $this->mydate($riqi_start, $riqi_end);
                        $six += $return['six'];
                        $ri += $return['ri'];
                    } else {
                        $riqi_start = $y_start_y . '-' . $output_month[$i] . '-1';
                        $return = $this->mydate($riqi_start);
                        $six += $return['six'];
                        $ri += $return['ri'];
                    }
                } else {
                    //最后一个月
                    if ($i == $count_output_month - 1) {

                        $riqi_start = $y_end_y . '-' . $output_month[$i] . '-1';
                        $riqi_end = $y_end_y . '-' . $output_month[$i] . '-' . $y_end_day;
                        $return = $this->mydate($riqi_start, $riqi_end);
                        $six += $return['six'];
                        $ri += $return['ri'];
                    } else {

                        // 修正年份
                        if ($output_month[0] < $output_month[$i]) {
                            $riqi_start = $y_start_y . '-' . $output_month[$i] . '-1';
                        } else {
                            $riqi_start = $y_end_y . '-' . $output_month[$i] . '-1';
                        }
                        $return = $this->mydate($riqi_start);
                        $six += $return['six'];
                        $ri += $return['ri'];
                    }
                }
            }

            //echo '<br>';
        }

        return array('six' => $six, 'ri' => $ri);
    }

    /**
     * 统计开发者信息详细
     */
    public function devlist_detail() {
        $uid = verify_id($this->input->get_post("uid"));

        $sql = "SELECT  FROM_UNIXTIME(starttime,'%Y-%m') time   FROM `rwfp_mokuai_usertime` where   mokuai_userid={$uid}  group by FROM_UNIXTIME(starttime,'%Y-%m') order by starttime desc";
        $time_list = $this->M_common->querylist($sql);
        $data = array(
            'time_list' => $time_list,
        );


        $action = $this->input->get_post("action");
        $action_array = array("show", "ajax_data");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        if ($action == 'show') {
            $this->load->view(__TEMPLET_FOLDER__ . "/views_dev_tongji_detail", $data);
        } elseif ($action == 'ajax_data') {
            $this->devajax_detail_data($uid);
        }
    }

    //ajax 获取数据
    private function devajax_detail_data($uid) {
        $this->load->library("common_page");
        $page = $this->input->get_post("page");
        if ($page <= 0) {
            $page = 1;
        }
        $per_page = 10; //每一页显示的数量
        $limit = ($page - 1) * $per_page;
        $limit.=",{$per_page}";

        $where = ' ';

        $time = daddslashes(html_escape(strip_tags(trim($this->input->get_post("time", true)))));
        if ($time != 'all') {
            $where .= " and  FROM_UNIXTIME(starttime,'%Y-%m') >= '{$time}' ";
        }

        //分页总数
        $sql = " SELECT  count(*) num   FROM `rwfp_mokuai_usertime` where   mokuai_userid={$uid} {$where}  group by FROM_UNIXTIME(starttime,'%Y-%m-%d')";
        $list_num = $this->M_common->querylist($sql);
        $total = count($list_num);

        $sql = "
            SELECT 
            mokuai_username as name,
            FROM_UNIXTIME(starttime,'%Y-%m-%d') as  shiji_start_time,
            concat(round(sum(userhours),2),'小时') as shiji_time,
            concat(if(FROM_UNIXTIME(starttime,'%w') < 6  ,round((sum(round(userhours,2))/7)*100,2),round((sum(round(userhours,2))/3.5)*100,2)),'%') as shangganglv  
--             ,count(userhours) num
             ,group_concat(distinct convert(mokuai_id,char)) mkids 
              FROM `rwfp_mokuai_usertime`
             where 
            mokuai_userid={$uid}
            {$where}  
             group by FROM_UNIXTIME(starttime,'%Y-%m-%d') order by starttime asc limit {$limit}
        ";
        $list = $this->M_common->querylist($sql);
        $page_string = $this->common_page->page_string($total, $per_page, $page, 'ajax_data');
//        $page_string = $this->common_page->page_string($total, $per_page, $page, 'ajax_data2',$uid);
        echo result_to_towf_new($list, 1, '成功', $page_string);
    }

}
