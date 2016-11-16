<?php

/**
 * 走出去 -- home后台 -- 用户管理
 * User: 嘉辉
 * Date: 2016-08-12
 * Time: 17:12
 */

/**
 * Class zcq_user
 * @property CI_Input $input
 * @property Common_upload $common_upload
 * @property CI_Router router
 * @property M_common_category_data $ccd 公用目录模型
 *
 * @property M_user $user
 *
 */
class zcq_user extends MY_Controller
{

    private $sess;

    private $views_folder;
    private $upload_path = "";

    private $controller_name = "adminx/zcq_user";

    private $data;

    public function __construct()
    {
        parent::__construct();

        $this->sess = $this->parent_getsession();
        if (empty($this->sess ["userid"])) {
            header("location:" . site_url("admin/index"));
            exit();
        }

        //加载模型
        $this->load->model("M_user", "user");
        $this->load->model('M_common_category_data', 'ccd');
        $this->load->model('M_common_system_user', 'sysuser');


        $this->views_folder = __TEMPLET_FOLDER__ . "/zcq/user";

        //一些网站的全局配置
        $this->data["config"] = $this->parent_sysconfig();
        $this->data['sess'] = $this->sess;
        //显示菜单
        $this->data['curset'] = 'user';
        $this->data['method'] = $this->router->fetch_method();
        $this->data['controller'] = $this->controller_name;

        $this->data["finfo"] = $this->parent_getfinfo();

        //位置链接
        $this->data['admin_url'] = site_url("admin/index");
        $this->data['cur_url'] = site_url($this->controller_name . "/" . $this->data['method']);


        $this->upload_path = __ROOT__ . "/data/upload/user/";
    }

//$this->parent_getsession()
//array (size=13)
//'user_data' => string '' (length=0)
//'userid' => string '93' (length=2)
//'username' => string '瓒呬汉鐢ㄦ埛' (length=12)
//'tel' => string '15361363221' (length=11)
//'last_login' => string '2016-08-12 17:07:39' (length=19)
//'usertype' => string '45063' (length=5)
//'usertype_name' => string '浼佷笟鐢ㄦ埛' (length=12)
//'realname' => string '鍦伴樋鍩�' (length=9)
//'yuming' => null
//'email' => null
//'editpwd' => string '2' (length=1)
//'wxid' => string '' (length=0)
//'session_id' => string '5e78314bc8691419a2d49618ab832424' (length=32)

    /**
     * 修改信息页面
     */
    public function editinfo()
    {
        $id = verify_id($this->sess['userid']);
        //用户的信息数组$info
        $model = $this->user->GetModel($id);

        if ($model['usertype'] == '45063') {
            //企业
        } elseif ($model['usertype'] == '45064') {
            //机构
        }

        $data = array();

        //用户类型列表,企业或者机构
        $usertype_list = $this->ccd->GetUserType();
        $data["usertype_list"] = $usertype_list;
        //服务类型列表
        $server_type_list = $this->ccd->GetServerType();
        $data["server_type_list"] = $server_type_list;

        //表单提交地址
        $form_url = site_url($this->controller_name . "/doedit");
        $data['form_url'] = $form_url;

        //模型
        $data['model'] = $model;
        $data = array_merge($this->data, $data);
        //var_dump($model);
        $this->load->view($this->views_folder . "/editinfo", $data);
    }

    /**
     * 执行修改操作
     */
    public function doedit()
    {
        $post = $this->input->post();
        //表单数据
        $model = $this->getmysqlmodel();

        //服务类型
        $str_server_type = "";
        if (isset($post['server_type'])) {
            $serverTypes = $post['server_type'];
            foreach ($serverTypes as $item) {
                $str_server_type .= $item . ",";
            }
            //去掉最后一个逗号
            $str_server_type = substr($str_server_type, 0, strlen($str_server_type) - 1);
        }
        $model['server_type'] = $str_server_type;

        //表示有无重新上传了图片,-1无，1有
        $isReUpImg = $post['reupload_pic'];

        //用户id
        $id = verify_id($this->sess['userid']);
        $model['uid'] = $id;

        //原本数据库中的数据
        $model_pre = $this->user->GetModel($id);

        if ($isReUpImg == '1') {
            //如果重新上传了证书，需要设置未审核
            //默认未审核
            $model["checkstatus"] = 0;
        }else{
            //未上传证书都设置未审核
            $model["checkstatus"] = 0;
        }

        //上传文件保存
        //允许上传格式
        $fileTypes = array('jpg', 'jpeg', 'png');
        $this->load->library("common_upload");

        //检查保存企业境外投资证书[可选的]
        if (!empty($_FILES['file_touzizhengshu']['tmp_name'])) {
            $pic_path = $this->common_upload->upload_path_ym($this->upload_path, 'file_touzizhengshu', implode("|", $fileTypes));
            if (!$pic_path) {
                $this->editDirect($model_pre, $model, "企业境外投资证书保存失败");
            } else {
                $model['fujian_touzizhengshu'] = $pic_path;
            }
        }

        if ($model['sanzheng'] == 1) {
            /* 三证合一 */

            //新图片路径
            $pic_path = $this->user->isUploadNew('file_sanzheng');
            if ($pic_path == -1) {
                //没有重新上传
            } elseif ($pic_path == 1) {
                $this->editDirect($model_pre, $model, "三证合一图片保存失败");
            } else {
                $model['fujian_sanzheng'] = $pic_path;
            }

            //数据库更新
            $num = $this->user->update($model);
            if ($num > 0) {
                //成功

                if (isset($model['fujian_sanzheng'])) {
                    //删掉旧的图片
                    $this->user->delPic($model_pre['fujian_sanzheng']);
                }

                $this->edit_successDirect($isReUpImg, $id);
            } else {

                //绑定数据，表单不用重填
                $this->editDirect($model_pre, $model, "提交资料失败，请重新尝试！");
            }

        } else {
            /* 三证分开 */

            //营业执照证书
            $pic_yingye = $this->user->isUploadNew('file_yingye');
            if ($pic_yingye == -1) {
                //没有重新上传
            } elseif ($pic_yingye == 1) {
                $this->editDirect($model_pre, $model, "营业执照证书图片保存失败");
            } else {
                $model['fujian_gongshang'] = $pic_yingye;
            }

            //组织证书
            $pic_zuzhi = $this->user->isUploadNew('file_zuzhi');
            if ($pic_zuzhi == -1) {
                //没有重新上传
            } elseif ($pic_zuzhi == 1) {
                $this->editDirect($model_pre, $model, "组织证书图片保存失败");
            } else {
                $model['fujian_zuzhi'] = $pic_zuzhi;
            }

            //税务证书
            $pic_shuiwu = $this->user->isUploadNew('file_shuiwu');
            if ($pic_shuiwu == -1) {
                //没有重新上传
            } elseif ($pic_shuiwu == 1) {
                $this->editDirect($model_pre, $model, "税务证书图片保存失败");
            } else {
                $model['fujian_shuiwu'] = $pic_shuiwu;
            }

            //数据库更新
            $num = $this->user->update($model);
            if ($num > 0) {
                //成功

                //删除旧的图片
                if (isset($model['fujian_gongshang'])) {
                    $this->user->delPic($model_pre['fujian_gongshang']);
                }
                if (isset($model['fujian_zuzhi'])) {
                    $this->user->delPic($model_pre['fujian_zuzhi']);
                }
                if (isset($model['fujian_shuiwu'])) {
                    $this->user->delPic($model_pre['fujian_shuiwu']);
                }

                $this->edit_successDirect($isReUpImg, $id);
                //echo "修改资料成功,资料审核完成前不能登录";
            } else {

                //绑定数据，表单不用重填
                $this->editDirect($model_pre, $model, "提交资料失败，请重新尝试！");
            }

        }

        exit();
    }

    /**
     * 修改密码页面
     */
    public function editpwd()
    {
        $id = verify_id($this->sess['userid']);

        $data = array();
        //表单提交地址
        $form_url = site_url($this->controller_name . "/doeditpwd");
        $data['form_url'] = $form_url;

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/editpwd", $data);
    }

    /**
     * 执行修改密码
     */
    public function doeditpwd()
    {
        $post = $this->input->post();
        $id = verify_id($this->sess['userid']);
        //旧密码
        $old_pwd = $post['old_pwd'];
        if ($this->user->chkpwd($id, $old_pwd)) {
            //执行更新密码操作
            $new_pwd = md5($post['new_pwd']);
            $rows = $this->user->updatepwd($id, $new_pwd);
            if ($rows > 0) {
                parent::parent_showmessage(1, "修改密码成功", site_url($this->controller_name . "/editpwd"), 5);
            } else {
                parent::parent_showmessage(0, "修改密码失败，请联系管理员", site_url($this->controller_name . "/editpwd"), 5);
            }
        } else {
            parent::parent_showmessage(0, "原密码不正确！", site_url($this->controller_name . "/editpwd"), 5);
        }
    }

    /**
     * 编辑页面重定向
     * @param string $model_pre
     * @param  array $model
     * @param string $msg
     */
    private function editDirect($model_pre, $model, $msg)
    {
        $model = array_merge($model_pre, $model);

        //绑定数据，表单不用重填

        $form_url = site_url("adminx/zcq_user/doedit");
        $data['form_url'] = $form_url;

        $data['message'] = $msg;
        //模型
        $data['model'] = $model;
        //整合
        $data = array_merge($this->data, $data);

        $this->load->view($this->views_folder . "/editinfo", $data);

        exit();
    }

    /**
     * 编辑页面成功了
     * @param $isReUpImg
     * @param $id
     */
    private function edit_successDirect($isReUpImg, $id)
    {

        if ($isReUpImg == "1") {
            $this->parent_showmessage(1, "重新修改资料，需要审核通过才能使用系统，审核期间禁止登录！！",
                site_url("admin/index"), 30);

            /*发送站内信*/
            $model = $this->user->getModel($id);
            $title = "[{$model['username']}]重新修改了资料，请审核！";
            $content = "管理员您好，[{$model['username']}]重新上传了证书，请审核！<a class='page-action' data-id='news_{$model['id']}' data-href='【user/edit】?id={$id}'>点击此处直接查看</a>";
            send_zhan_mail('', get_all_admin_id(), $title, $content, $this->sess['userid'], null, $this->data["sess"]["session_id"]);

        } else {
            $this->parent_showmessage(1, "修改资料成功!",
                site_url("admin/index"), 3);
        }
    }

    /**
     * 验证密码是否正确
     */
    public function chkpwd()
    {
        $get = $this->input->get();
        $pwd = $get["param"];
        $id = verify_id($this->sess['userid']);

        //返回的数据,1为默认是成功的
        $re = array("result" => 1);

        if (!$this->user->chkpwd($id, $pwd)) {
            //返回失败信息
            $re['result'] = 0;
            $re['msg'] = "密码不正确";
        }

        echo json_encode($re);
    }

    public function test()
    {
        //var_dump($this->parent_getsession());
    }

}