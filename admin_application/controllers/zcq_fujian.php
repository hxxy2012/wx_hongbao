<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * 用于接收上传附件的,企业和机构的证书
 * Class fujian
 * @property Common_upload $common_upload 上传库
 *
 * @property CI_Input $input
 */
class zcq_fujian extends MY_Controller
{

    private $upload_path = "";

    public function __construct()
    {
        parent::__construct();
        $this->upload_path = __ROOT__ . "/data/upload/user/";
    }

    /**
     * 接收上传
     */
    public function upload()
    {
        //返回的数据
        $re = array("success" => false);

        if (!empty($_FILES)) {

            //得到上传的临时文件流
            $tempFile = $_FILES['Filedata']['tmp_name'];

            //允许的文件后缀
            $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'doc', 'pdf', 'rar', 'xls', 'xlsx');

            $this->load->library("common_upload");
            $fujian_path = $this->common_upload->upload_path_ym_width($this->upload_path, 'Filedata', implode("|", $fileTypes), 2000);
            if ($fujian_path != "") {
                $re['success'] = true;
                $re['filepath'] = $fujian_path;
            }
        } else {
            $re['msg'] = "文件上传过程中出现问题！";
        }
        echo json_encode($re);
    }

    /**
     * 删除文件
     */
    public function del()
    {
        $gets = $this->input->get();
        $filepath = $gets['filepath'];
        if ($filepath) {
            echo unlink(realpath($filepath));
        }else{
            echo false;
        }
    }
}
