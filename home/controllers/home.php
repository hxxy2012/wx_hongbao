<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
@ini_set("display_errors", "On");

/**
 * Class Home
 * @property M_user $user 用户模型
 *
 *
 * @property Common_upload $common_upload
 * @property CI_Input $input
 *
 * @property M_website_common_info $mwci
 * @property M_website_category $mwc
 * @property M_website_category $webcate
 * @property M_common_category_data $ccd
 *
 * @property M_user $user 用户
 * @property M_zcq_huodong $hudong
 * @property M_zcq_hdbaoming $hdbaoming
 */
class Home extends MY_ControllerLogout
{
    private $upload_path = "";

    var $data = array();

    function Home()
    {
        parent::__construct();
        $this->data["config"] = $this->parent_sysconfig();
        $this->load->model('M_user', 'user');

        $this->load->model('M_hb_hongbao_set','hbset');
        $this->load->model('M_hb_hongbao_list','hblist');
        $this->load->model('M_hb_tel','hb_tel');
        // $this->data["sess"] = $this->parent_getsession();//登陆者信息
        $this->upload_path = __ROOT__ . "/data/upload/user/";
        $this->data["ls"] = get_url();//获取当前连接
    }

    /**
     * 首页
     */
    function index()
    {
        // var_dump($this->data["zfwz"]);exit;
        $this->load->view(__TEMPLET_FOLDER__ . "/hb/index", $this->data);
    }

    function zjjl() {
        $this->load->view(__TEMPLET_FOLDER__ . "/hb/zjjl", $this->data);   
    }

    function send_tel_code()
    {
        $post = $this->input->post();
        if (!empty($post["tel"])) {
            $tel = trim($post["tel"]);
            $config = $this->parent_sysconfig();
            $code = rand(100000, 999999);
            //检查是否已使用，一小时内未使用，就用回上一个
            $isrep = false;
            if ($this->tel_code->count("tel='$tel' and isuse='1' and (UNIX_TIMESTAMP()-createtime)<120") > 0) {
                $model = $this->tel_code->GetModelFromTel($tel);
                $isrep = true;
            }
            $model["tel"] = $tel;
            if (!$isrep) {
                $model["code"] = $code;
            }
            $model["createtime"] = time();
            $model["isuse"] = '1';
            $model["sendtype"] = '1';
            $msg = $config["site_fullname"] . "手机验证码：" . $model["code"] . ",请及时注册。";
            if ($isrep) {
                $this->tel_code->update($model);
            } else {
                $this->tel_code->add($model);
            }
            $this->sms->send_message($msg, "注册手机验证", "-1", "-1",
                array(array("id" => "0", "type" => "0", "phone" => $tel))
                , "");
        }
    }
}