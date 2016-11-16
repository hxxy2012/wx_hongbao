<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/8
 * Time: 16:53
 * 项目类型控制类
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * Class zcq_pro_shenqing
 *
 * @property m_zcq_pro_shenqing $zcq_pro_shenqing
 * @property m_zcq_pro_fujian $zcq_pro_fujian
 * @property
 *
 * @property CI_Input $input
 *
 */
class zcq_pro_shenqing extends MY_Controller
{
    var $data;
    private $sess;

    function zcq_pro_shenqing()
    {
        parent::__construct();
        $this->load->model('M_common');
        $this->load->model('m_zcq_pro_type', 'zcq_pro_type');
        $this->load->model('m_zcq_pro_type_fujian', 'zcq_pro_type_fujian');
        $this->load->model('m_zcq_pro_fujian', 'zcq_pro_fujian');
        $this->load->model('m_zcq_pro_shenqing', 'zcq_pro_shenqing');
        $this->load->model('m_zcq_pro_shenqing_fujian', 'zcq_pro_shenqing_fujian');
        $this->data["config"] = $this->parent_sysconfig();
        $get = $this->input->get();
        $this->data["ls"] = empty($get["ls"]) ? "" : $get["ls"];
        $this->sess = $this->parent_getsession();
        $this->data["sess"] = $this->sess;

        //显示菜单
        $this->data['curset'] = $this->router->class;
        $this->data['sec_curset'] = '';
        $this->data['controller'] = $this->router->class;
        $this->data['method'] = $this->router->fetch_method();
        //页脚友情链接
        $this->data["finfo"] = $this->parent_getfinfo();
    }

    function index()
    {
        $this->data['sec_curset'] = $this->router->method;
        $userid = $this->data["sess"]["userid"];
        $pageindex = $this->input->get_post("per_page");
        $get = $this->input->get();
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $pagesize = 20;
        $search = array();
        $search_val = array();
        $search_val["title"] = isset($get["sel_title"]) ? $get["sel_title"] : "";
        $search_val["check_status"] = isset($get["sel_cs"]) ? $get["sel_cs"] : "";
        $orderby["check_status"] = "asc";
        $orderby["id"] = "desc";
        $search["t1.create_userid"] = "='" . $userid . "'";
        if ($search_val["title"] != "") {
            $search["t1.title"] = " like '%" . $search_val["title"] . "%'";
        }
        if ($search_val["check_status"] != "") {
            $search["t1.check_status"] = "='" . $search_val["check_status"] . "'";
        }
        $list = $this->zcq_pro_shenqing->GetInfoList($pageindex, $pagesize, $search, $orderby);
        $this->data["checkstatus"] = $this->zcq_pro_shenqing->getstatus();
        $this->data["list"] = $list["list"];
        $this->data["pager"] = $list["pager"];
        $this->data["search_val"] = $search_val;


        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/zijin/pro_shenqing/list", $this->data);
    }

    function edit()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        if (is_array($post)) {
            //print_r($post);
            //die();
            $id = $post["id"];
            $model = $this->zcq_pro_shenqing->GetModel($id);
            if (count($model) > 0) {
                if ($model["create_userid"] != $this->data["sess"]["userid"]) {
                    $this->parent_showmessage(
                        0
                        , "无资料",
                        $this->data["ls"],
                        3,
                        'showmessage_logout'
                    );
                    exit();
                }
            } else {
                $this->parent_showmessage(
                    0
                    , "无资料",
                    $this->data["ls"],
                    3,
                    'showmessage_logout'
                );
                exit();
            }
            $tmp = $this->getmysqlmodel();
            foreach ($tmp as $k => $v) {
                $model[$k] = $v;
            }
            $model["updatetime"] = time();
            $model["update_sysuserid"] = admin_id();

            //0未审 10通过 20不通过 99临时保存
            if (isset($post['temp_save'])) {
                $model["check_status"] = "99";//继续临时保存
            } else {
                $model["check_status"] = "0";//提交审核
            }

            $this->zcq_pro_shenqing->update($model);
            //保存附件 POST里有附件值 才保存
            $typelist = $this->zcq_pro_type_fujian->getlist($model["type_id"]);
            foreach ($typelist as $v) {
                if (isset($post["fj_upload" . $id . "_" . $v["fujian_id"]])) {
                    if ($post["fj_upload" . $id . "_" . $v["fujian_id"]] != "") {
                        $filepath = $post["fj_upload" . $id . "_" . $v["fujian_id"]];
                        if ($this->zcq_pro_shenqing_fujian->count("del_userid='0' and del_sysuserid='0' and shenqing_id='" . $id . "' and fujian_id='" . $v["fujian_id"] . "'") > 0) {
                            //修改
                            $fjlist = $this->zcq_pro_shenqing_fujian->getlist("del_userid='0' and del_sysuserid='0' and shenqing_id='" . $id . "' and fujian_id='" . $v["fujian_id"] . "'");
                            $model2 = $fjlist[0];
                            if ($model2["filepath"] != $filepath && $model2["filepath"] != "") {
                                //删除旧附件
                                @unlink("./" . $model2["filepath"]);
                            }
                            $model2["filepath"] = $filepath;
                            $model2["updatetime"] = time();
                            $model2["update_sysuserid"] = admin_id();
                            $this->zcq_pro_shenqing_fujian->update($model2);
                        } else {
                            //region 实体
                            $model2["shenqing_id"] = $id;
                            $model2["type_id"] = $model["type_id"];
                            $model2["fujian_id"] = $v["fujian_id"];
                            $model2["filepath"] = $filepath;
                            $model2["create_sysuserid"] = admin_id();
                            $model2["update_sysuserid"] = 0;
                            $model2["del_sysuserid"] = 0;
                            $model2["createtime"] = time();
                            $model2["updatetime"] = 0;
                            $model2["deltime"] = 0;
                            $model2["create_userid"] = 0;
                            $model2["update_userid"] = 0;
                            $model2["del_userid"] = 0;
                            //endregion
                            $this->zcq_pro_shenqing_fujian->add($model2);
                        }
                    }
                }
            }
            //向所有管理发站内信通知
            $mailtitle = "申报编号:" . $id . "需要审核！";
            $mailbody = "您好，管理员，" . $this->data["sess"]["username"] . "提交了申报资料，请查阅后审核。";
            $all_admin_id = get_all_admin_id();
            if ($all_admin_id != "") {
                $code = send_zhan_mail(
                    "",
                    $all_admin_id,
                    $mailtitle,
                    $mailbody,
                    $this->data["sess"]["userid"],
                    null,
                    $this->data["sess"]["session_id"]
                );
                //die("aaaa=".$code);
            }
            if (isset($post['temp_save'])) {
                $msg = "临时保存成功";//继续临时保存
            } else {
                $msg = "提交成功，等待审核";//提交审核
            }
            $this->parent_showmessage(
                1
                , $msg,
                $this->data["ls"],
                3,
                'showmessage_logout'
            );
            exit();
        } else {

            /* 编辑页面 */

            //echo send_zhan_mail("","16","aaa","test",$this->data["sess"]["userid"],0, $this->data["sess"]["session_id"]);
            $id = isset($get["id"]) ? $get["id"] : 0;
            $model = array();
            if ($id > 0) {
                $model = $this->zcq_pro_shenqing->GetModel($id);
            }
            if (count($model) == 0) {
                $this->parent_showmessage(
                    0
                    , "无资料",
                    $this->data["ls"],
                    3,
                    'showmessage_logout'
                );
                exit();
            }
            //读出项目需要的附件
            $fujian = $this->zcq_pro_type_fujian->getlist("del_sysuserid='0' and type_id='" . $model["type_id"] . "'", "orderby asc");
            foreach ($fujian as $k => $v) {
                $where = "del_sysuserid='0' and del_userid='0'  and  shenqing_id='" . $id . "' and fujian_id='" . $v["fujian_id"] . "' ";
                $sqlist = $this->zcq_pro_shenqing_fujian->getlist($where);
                if (count($sqlist) > 0) {
                    $fujian[$k]["filepath2"] = $sqlist[0]["filepath"];//会员上传的附件
                } else {
                    $fujian[$k]["filepath2"] = "";
                }
                $fujian[$k]["title"] = "";
                $fujian_model = $this->zcq_pro_fujian->GetModel($v["fujian_id"]);
                $fujian[$k]["filepath"] = "";
                if (count($fujian_model) > 0) {
                    $fujian[$k]["title"] = $fujian_model["title"];
                    $fujian[$k]["filepath"] = $fujian_model["filepath"];
                }
            }

            $this->data['method'] = "index";//为了展开菜单
            $this->data["pro_type_model"] = $this->zcq_pro_type->GetModel($model["type_id"]);
            $this->data["model"] = $model;
            $this->data["fujian"] = $fujian;
            $this->data["id"] = $id;
            $this->data["checkstatus"] = $this->zcq_pro_shenqing->getstatus();
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/zijin/pro_shenqing/edit", $this->data);
        }
    }

    /**
     * 设置为删除
     */
    public function delete()
    {
        $get = $this->input->get();
        $post = $this->input->post();

        //本条id
        $id = verify_id($get['id']);

        //用户id
        $userid = verify_id($this->sess['userid']);

        $model = $this->zcq_pro_shenqing->GetModel($id);
        if (empty($model)) {
            $this->parent_showmessage(0, "该申报不存在!", $this->data["ls"], 3);
            exit();
        }
        if ($model['check_status'] != 99 && $model['check_status'] != 20) {
            $this->parent_showmessage(0, "只有临时保存或者审核不成功的申请才可以删除!", $this->data["ls"], 3);
            exit();
        }
        if ($model['create_userid'] != $userid) {
            $this->parent_showmessage(0, "无权操作!", $this->data["ls"], 3);
            exit();
        }

        $model['del_userid'] = $userid;
        $model['isdel'] = '1';
        $model['deltime'] = time();

        $rows = $this->zcq_pro_shenqing->update($model);
        if ($rows > 0) {
            $this->parent_showmessage(1, "删除成功!", $this->data["ls"], 3);
        } else {
            $this->parent_showmessage(0, "操作失败!", $this->data["ls"], 3);
        }
        exit();
    }
}