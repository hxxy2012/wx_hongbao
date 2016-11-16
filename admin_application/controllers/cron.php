<?php

class Cron extends CI_Controller {

    function Cron() {
        parent::__construct();
        $this->load->model('M_common', '', false, array('type' => 'real_data'));
        $this->cache_category_path = config_item("category_modeldata_cache");
        $this->upload_path = __ROOT__ . "/data/upload/pro/fujian/";
        ; // 编辑器上传的文件保存的位置
        $this->upload_save_url = base_url() . "/data/upload/pro/fujian/"; //编辑器上传图片的访问的路径		
        $this->upload_path_sys = "data/upload/pro/fujian/"; //保存字段用的
    }

    function getObject() {
        //mokuai_status=6 工作中
        //mokuai_status=9 测试中
        $status = array('zhanting' => '6', 'test_zhanting' => '9');
        foreach ($status as $k => $v) {
            $sql = "select id from rwfp_mokuai where mokuai_status=$v ";
            $data = $this->M_common->querylist($sql);
            if (!empty($data)) {
                foreach ($data as $value) {
                    $this->$k($value['id'], false);
                }
            }
        }
    }

    function zhanting($mkid, $showmsg = true) {
        //计算时数
        //查找时数表检查endtime是否为空
        $sql = "select * from rwfp_mokuai_usertime where endtime='' and mokuai_id=$mkid";
        $time_id = 0;
        $time_model = $this->M_common->query_one($sql);
//        print_r($time_model);exit;
        if (count($time_model) > 0) {
            $time_model["endtime"] = time();
            $time_id = $time_model["id"];
            $time_model["userhours"] = doubleval(($time_model["endtime"] - $time_model["starttime"]) / (60 * 60));
            $time_model["userseconds"] = ($time_model["endtime"] - $time_model["starttime"]);
            $time_model["beizhu"] = "系统主动暂停任务";
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
        $model["update_username"] = "系统";
        $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
        if ($showmsg) {
            echo result_to_towf_new(array(), 1, '操作成功', "");
        }
        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), '"系统', get_client_ip(), 1, "暂停任务：" . $model["title"]);
        }
        return $time_id;
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
        $data["username"] = "系统";
        $data["statusid"] = $newstatusid;
        $data["createtime"] = time();
        $data["beizhu"] = $data["username"] . "在" . date("Y-m-d H:i", $data["createtime"]) . "将" . $mk["title"] . "状态由" . $pre_zd["title"] . "更改为" . $new_zd["title"];
        $data["pre_status_id"] = $pre_zd["id"];
        $data["pre_status_title"] = $pre_zd["title"];
        $data["mkid"] = $mkid;
        $data["mktitle"] = $mk["title"];
        $array = $this->M_common->insert_one("rwfp_mokuai_status_log", $data);
        return $array["insert_id"];
    }

    //测试暂停
    function test_zhanting($mkid, $showmsg = false) {
        //计算时数
        //查找时数表检查endtime是否为空并且是否在控制
        $sql = "select * from rwfp_mokuai_usertime where endtime='' and is_control=0  and mokuai_id=$mkid";
        $time_model = $this->M_common->query_one($sql);

        if(count($time_model) > 0) {
            $time_id = 0;
            if (count($time_model) > 0) {
                //判断操作人是否为本人
                //   if ($time_model["mokuai_userid"] != admin_id()) {
                //    echo result_to_towf_new(array(), 0, '您不是测试员本人不能操作。', "");
                //    exit;
                //  } else {
                $time_id = $time_model["id"];
                $time_model["endtime"] = time();
                $time_model["userhours"] = doubleval(($time_model["endtime"] - $time_model["starttime"]) / (60 * 60));
                $time_model["userseconds"] = ($time_model["endtime"] - $time_model["starttime"]);
                $time_model["beizhu"] = "系统主动暂停任务";
                $this->M_common->update_data2(
                        "rwfp_mokuai_usertime", $time_model, array("id" => $time_model["id"])
                );
                // }
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
            $model["update_username"] = "系统主";
            $array = $this->M_common->update_data2("rwfp_mokuai", $model, array("id" => $model["id"]));
            if ($showmsg) {
                echo result_to_towf_new(array(), 1, '操作成功', "");
            }
            if ($array['affect_num'] >= 1) {
                write_action_log($array['sql'], $this->uri->uri_string(), '系统', get_client_ip(), 1, "暂停测试：" . $model["title"]);
            }
            return $time_id;
        }
    }

}
