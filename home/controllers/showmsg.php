<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class showmsg extends MY_ControllerLogout
{


    var $data = array();
    function showmsg(){
        parent::__construct();
        $this->load->model('M_user','user');
        $get = $this->input->get();
        $this->data['sess'] = $this->parent_getsession();
        $this->data["config"] = $this->parent_sysconfig();
        $this->data["backurl"] = !isset($get["backurl"])?site_url("home/index"):$get["backurl"];
        $this->data["finfo"] = $this->parent_getfinfo();
    }

    function msg(){
        $get = $this->input->get();
        $isok = $get["isok"];
        $message = $get["msg"];
        $url = $get["url"];
        $miao = $get["miao"];
        $view = isset($get["view"])?$get["view"]:"showmessage_logout";

        $this->data["isok"] = $isok;
        $this->data["pagetitle"] = ($isok==1?"操作成功":"操作失败");
        $this->data["message"] = $message;
        $this->data["url"] = $url;
        $this->data["miao"] = $miao;
        $this->load->view(__TEMPLET_FOLDER__."/".$view,$this->data);
    }

}