<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * 管理员后台首页控制器
 * Class zcq_index
 * @property m_zcq_pro_shenqing $zcq_pro_shenqing 资金申请模型
 * @property m_zcq_mail $zcq_mail 站内信模型
 * @property M_zcq_fuwu_zixun $zixun 服务咨询模型
 * @property M_zcq_survey $survey 调查问卷模型
 * @property M_zcq_diaocha_canjia $canjia 调查问卷参加模型
 * @property m_zcq_duiwaitouzi $touzi 对外投资模型
 * @property M_user $user 用户模型
 * @property M_common_category_data $ccd 公用目录模型
 */
class zcq_index extends MY_Controller
{
    var $data;

    function zcq_index()
    {
        parent::__construct();
        $this->load->model('M_common');

        $this->load->model('m_zcq_pro_shenqing', 'zcq_pro_shenqing');//资金申请模型
        $this->load->model('m_zcq_mail', 'zcq_mail');//站内信模型
        $this->load->model('M_zcq_fuwu_zixun', 'zixun');//服务咨询模型
        $this->load->model('M_zcq_survey', 'survey');//调查问卷模型
        $this->load->model('M_zcq_diaocha_canjia', 'canjia');//调查问卷参加模型
        $this->load->model('M_zcq_duiwaitouzi', 'touzi');//对外投资模型
        $this->load->model('M_user', 'user');//用户模型
        $this->load->model('M_common_category_data', 'ccd');//公用目录模型
        $this->data['adminid'] = admin_id();//管理员id
    }

    function index()
    {

        //我的申报
        //$search      = array("t1.create_userid" => "='{$userid}'");
        $search = array("t1.check_status" => "='0'");
        $orderby = array("id" => "desc");
        $this->data['list_mimesb'] = $this->zcq_pro_shenqing->GetInfoList(1, 5, $search, $orderby);

        // 最新站内信
        $search = array("t1.receive_sysuserid" => "='" . admin_id() . "'","isread"=>" = '0' ");
        $orderby = array("isread" => "asc", "id" => "desc");
        $this->data['list_znx'] = $this->zcq_mail->GetInfoList(1, 5, $search, $orderby);

        //对外投资联系
        $search = array("check_status" => " '1' ");
        $orderby = array("check_status" => "asc", "id" => "desc");
        $this->data['list_touzi'] = $this->touzi->getList(1, 5, $search, $orderby);

        //最新服务咨询
        $search = array("receive_isread" => "'0'");
        $orderby = array("receive_isread" => "asc", "id" => "desc");
        $this->data['list_fwzx'] = $this->zixun->getList(1, 5, $search, $orderby);

        //注册审核
        $search = array("checkstatus" => " '0' ");
        $orderby = array("uid" => "desc");
        $this->data['list_user'] = $this->user->GetInfoList(1, 5, $search, $orderby);
        //-->用户类型列表,企业或者机构
        $usertype_list = $this->ccd->GetUserType();
        $this->data["usertype_list"] = $usertype_list;
        //-->服务类型列表
        $server_type_list = $this->ccd->GetServerType();
        $this->data["server_type_list"] = $server_type_list;


        // var_dump($this->data['list_survey']);exit;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/index", $this->data);
    }
}