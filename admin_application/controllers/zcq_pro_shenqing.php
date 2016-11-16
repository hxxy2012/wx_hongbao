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
 * @property M_user $user 用户模型
 * @property m_zcq_pro_shenqing $zcq_pro_shenqing
 */
class zcq_pro_shenqing extends MY_Controller
{
    var $data;

    function zcq_pro_shenqing()
    {
        parent::__construct();
        $this->load->model('M_common');
        $this->load->model('m_zcq_pro_type', 'zcq_pro_type');
        $this->load->model('m_zcq_pro_type_fujian', 'zcq_pro_type_fujian');
        $this->load->model('m_zcq_pro_fujian', 'zcq_pro_fujian');
        $this->load->model('m_zcq_pro_shenqing', 'zcq_pro_shenqing');
        $this->load->model('m_zcq_pro_shenqing_fujian', 'zcq_pro_shenqing_fujian');
        $this->load->model('M_user', 'user');
        $get = $this->input->get();
        $this->data["ls"] = empty($get["ls"]) ? "" : $get["ls"];
        $this->data["sess"] = $this->parent_getsession();
    }

    function index()
    {
        $pageindex = $this->input->get_post("per_page");
        $get = $this->input->get();
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        if ($get["isport"] == "yes") {
            $pagesize = 99999999;
        } else {
            $pagesize = 20;
        }
        $search = array();
        $search_val = array();
        $search_val["title"] = isset($get["sel_title"]) ? $get["sel_title"] : "";
        $search_val["check_status"] = isset($get["sel_cs"]) ? $get["sel_cs"] : "";
        $orderby["check_status"] = "asc";
        $orderby["id"] = "desc";
        if ($search_val["title"] != "") {
            $search["t1.title"] = " like '%" . $search_val["title"] . "%'";
        }
        if ($search_val["check_status"] != "") {
            $search["t1.check_status"] = "='" . $search_val["check_status"] . "'";
        }
        $list = $this->zcq_pro_shenqing->GetInfoList($pageindex, $pagesize, $search, $orderby);
        if ($get["isport"] == "yes") {
            $this->export_excel($list["list"]);
            exit();
        }
        $this->data["checkstatus"] = $this->zcq_pro_shenqing->getstatus();
        $this->data["list"] = $list["list"];
        $this->data["pager"] = $list["pager"];
        $this->data["search_val"] = $search_val;


        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/zijin/pro_shenqing/list", $this->data);

    }

    function del()
    {
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $arr = explode(",", $idlist);
        foreach ($arr as $v) {
            $this->zcq_pro_shenqing->del($v);
        }
    }

    /**
     * 设置审核
     */
    function set_check()
    {
        $get = $this->input->post();
        $idlist = $get["idlist"];
        $content = $get["content"];
        $cs = isset($get["cs"]) ? $get["cs"] : "";

        //记录发送邮件的一些信息
        $email_result = array();

        $arr = explode(",", $idlist);
        foreach ($arr as $v) {
            $model = $this->zcq_pro_shenqing->GetModel($v);

            if ($cs > 0) {
                //通过
                $model["check_status"] = "10";
                $model["check_time"] = time();
                $model["check_content"] = $content;
                $model["check_sysuserid"] = admin_id();

                if ($model["create_userid"] > 0) {
                    //记录要发的邮件信息
                    $user_model = $this->user->GetModel($model["create_userid"]);
                    $email_option['id'] = $v;//记录id
                    $email_option['usermodel'] = $user_model;//记录模型
                    $email_option['success'] = true;
                    //填写表单时邮件
                    if ($model["qiye_email"] != $user_model['email']) {
                        $email_option['qiye_email'] = $model["qiye_email"];//
                    } else {
                        $email_option['qiye_email'] = "";//
                    }
                    //加入数组
                    $email_result[] = $email_option;

                    //发站内信
                    send_zhan_mail(
                        $model["create_userid"],
                        "",
                        "申报编号：" . $model["id"] . ",审核已通过。",
                        "您好，申报编号：{$model["id"]}审核已通过" . ",位置：资金申请>我的申请。",
                        "0",
                        admin_id(),
                        $this->data["sess"]["session_id"]
                    );
                }

            } else {
                //不通过
                $model["check_status"] = "20";
                $model["check_no_time"] = time();
                $model["check_content"] = $content;
                $model["check_sysuserid"] = admin_id();

                if ($model["create_userid"] > 0) {
                    //记录要发的邮件信息
                    $user_model = $this->user->GetModel($model["create_userid"]);
                    $email_option['id'] = $v;//记录id
                    $email_option['usermodel'] = $user_model;//记录模型
                    $email_option['success'] = false;
                    //填写表单时邮件
                    if ($model["qiye_email"] != $user_model['email']) {
                        $email_option['qiye_email'] = $model["qiye_email"];//
                    } else {
                        $email_option['qiye_email'] = "";//
                    }
                    //加入数组
                    $email_result[] = $email_option;

                    //发站内信
                    send_zhan_mail(
                        $model["create_userid"],
                        "",
                        "申报编号：" . $model["id"] . ",审核不通过，请根据审核意见修改。",
                        "您好，请按审核意见修改：" . ($content == "" ? "没有审核意见" : $content) . ",位置：资金申请>我的申请。",
                        "0",
                        admin_id(),
                        $this->data["sess"]["session_id"]
                    );
                }

            }
            $this->zcq_pro_shenqing->update($model);


        }

        //异步发送邮件
        $url = site_url("zcq_pro_shenqing/sendChkEmail");
        $data = array(
            "option" => json_encode($email_result),
            "session_id" => $this->data["sess"]['session_id']
        );
        echo asyncPost($url, $data);

        exit();
    }

    /**
     * 发送审核结果通知邮件
     * @internal param string $email_result 数组
     */
    public function sendChkEmail()
    {
        $post = $this->input->post();
        if (isset($post['option'])) {
            $url = base_url();
            //解码转换成数组
            $email_result = json_decode($post['option']);
            foreach ($email_result as $item) {
                $model = $item->usermodel;
                echo "发送{$model->email}";

                //发送注册时填写的邮件
                if ($model->email != "") {
                    write_action_log("", "", "", "", 1, "资金申请审核发送邮件{$model->email}");

                    if ($item->success) {
                        sendmail_help($model->email, "【走出去】审核通知", "您好，{$model->company}，申报编号{$item->id}审核已通过，<a href='$url'>请登录后台查看</a>");
                    } else {
                        sendmail_help($model->email, "【走出去】审核通知", "您好，{$model->company}，申报编号{$item->id}审核不通过，<a href='$url'>请登录后台查看</a>");
                    }
                    sleep(1);
                }

                //发送填写表单时邮件
                if ($item->qiye_email != "") {
                    write_action_log("", "", "", "", 1, "资金申请审核发送邮件{$item->qiye_email}");

                    if ($item->success) {
                        sendmail_help($item->qiye_email, "【走出去】审核通知", "您好，{$model->company}，申报编号{$item->id}审核已通过，<a href='$url'>请登录后台查看</a>");
                    } else {
                        sendmail_help($item->qiye_email, "【走出去】审核通知", "您好，{$model->company}，申报编号{$item->id}审核不通过，<a href='$url'>请登录后台查看</a>");
                    }
                    sleep(1);
                }
            }
        } else {
            echo "该页面不能被访问!!!";
            exit();
        }

    }

    private function export_excel($list)
    {

        require_once 'include/PHPExcel.php';
        require_once 'include/PHPExcel/Writer/Excel2007.php';
        $objPHPExcel = new PHPExcel();


        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "状态");
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, "申请企业名称");
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, "法人姓名");
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, "法人电话");
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, "通讯地址");
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, "企业联系人");
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, "联系电话");
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, "电子邮件");
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, "移动电话");
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, "开户银行名称");
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, "开户银行地址");
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, "银行账户账号");
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, "银行账户户名");
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, "申报补贴类型");

        //设置宽度

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);

        foreach ($list as $k => $v) {
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $this->convertUTF8($v['check_status_title']));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $this->convertUTF8($v["title"]));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $this->convertUTF8($v["faren"]));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $this->convertUTF8($v["faren_tel"]));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $this->convertUTF8($v["addr"]));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $this->convertUTF8($v["qiye_linkman"]));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $this->convertUTF8($v["qiye_tel"]));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $this->convertUTF8($v["qiye_email"]));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $this->convertUTF8($v["qiye_mobile"]));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $this->convertUTF8($v["kaihu"]));
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $this->convertUTF8($v["kaihu_addr"]));
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $this->convertUTF8($v["kaihu_zhanghu"]));
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $this->convertUTF8($v["kaihu_huming"]));
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $this->convertUTF8($v["typename"]));
        }
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //或者$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel); 非2007格式
        $filename = "shenqing_" . date("YmdHis") . '.xls';
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $filename . '"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('data/upload/tmp/' . $filename);//php://output
        header("location:/data/upload/tmp/" . $filename);
    }


    private function convertUTF8($str)
    {
        if (empty($str)) return '';
        //return  iconv('gb2312','utf-8', $str);
        return $str;
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
            $tmp = $this->getmysqlmodel();
            foreach ($tmp as $k => $v) {
                $model[$k] = $v;
            }
            $model["updatetime"] = time();
            $model["update_sysuserid"] = admin_id();
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
            $this->data["ls"] = site_url("zcq_pro_shenqing/edit") . "?id=" . $id . "&ls=" . urlencode($this->data["ls"]);
            showmessage("保存成功",
                $this->data["ls"], 1, 1,
                $params = '');
        } else {
            $id = isset($get["id"]) ? $get["id"] : 0;
            $model = array();
            if ($id > 0) {
                $model = $this->zcq_pro_shenqing->GetModel($id);
            }
            if (count($model) == 0) {
                showmessage("无资料",
                    $this->data["ls"], 1, 0,
                    $params = '');
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
                if (count($fujian_model) > 0) {
                    $fujian[$k]["title"] = $fujian_model["title"];
                }
            }

            $this->data["pro_type_model"] = $this->zcq_pro_type->GetModel($model["type_id"]);
            $this->data["model"] = $model;
            $this->data["fujian"] = $fujian;
            $this->data["id"] = $id;
            $this->data["checkstatus"] = $this->zcq_pro_shenqing->getstatus();
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/zijin/pro_shenqing/edit", $this->data);
        }
    }
}

