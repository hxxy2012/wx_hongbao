<?php
/**
 * 服务咨询-home控制器
 * User: 嘉辉
 * Date: 2016-08-16
 * Time: 16:56
 */

/**
 * Class zcq_user
 * @property CI_Input $input
 * @property Common_upload $common_upload
 * @property CI_Router router
 *
 * @property M_user $user
 * @property m_zcq_fuwu_zixun $zixun 服务咨询模型
 * @property M_common_category_data $ccd 公用目录模型
 * @property m_zcq_datamanage $datamanage 走出去数据模型
 * @property M_common_system_user $sysuser
 *
 */
class zcq_fuwu_zixun extends MY_Controller
{

    private $sess;

    private $views_folder;

    private $upload_path = "";
    private $upload_save_url = "";

    private $controller_name = "adminx/zcq_fuwu_zixun";

    private $data;

    public function __construct()
    {
        parent::__construct();

        $this->sess = $this->parent_getsession();
        if (empty($this->sess ["userid"])) {
            header("location:" . site_url("admin/index"));
            exit();
        }

        $usertype = $this->sess['usertype'];
        //控制器方法
        $method = $this->router->fetch_method();
        if ($usertype == '45064') {/* 机构用户访问控制 */
            //禁止列表
            $jigou_forbid_list = array("start", "doask", "doedit", "view");
            if (in_array($method, $jigou_forbid_list)) {
                //转到列表
                header("location:" . site_url($this->controller_name . "/lists"));
                exit();
            }
        } elseif ($usertype == '45063') {/* 企业用户访问控制 */
            //禁止列表
            $qiye_forbid_list = array("reply", "doreply");
            if (in_array($method, $qiye_forbid_list)) {
                //转到列表
                header("location:" . site_url($this->controller_name . "/lists"));
                exit();
            }
        }

        //加载模型
        $this->load->model("M_user", "user");
        $this->load->model('M_common_category_data', 'ccd');
        $this->load->model('M_common_system_user', 'sysuser');
        $this->load->model('M_zcq_fuwu_zixun', 'zixun');

        $this->views_folder = __TEMPLET_FOLDER__ . "/zcq/fuwu_zixun";

        //一些网站的全局配置
        $this->data["config"] = $this->parent_sysconfig();
        $this->data['sess'] = $this->sess;
        //显示菜单
        $this->data['curset'] = 'zi_xun';
        $this->data['method'] = $this->router->fetch_method();

        $this->data["finfo"] = $this->parent_getfinfo();

        //位置链接
        $this->data['admin_url'] = site_url("admin/index");
        $this->data['cur_url'] = site_url($this->controller_name . "/" . $this->data['method']);
        $this->data['controller'] = $this->controller_name;

        // 编辑器上传的文件保存的位置
        $this->upload_path = __ROOT__ . "/data/upload/zixun/";
        //编辑器上传图片的访问的路径
        $this->upload_save_url = "/data/upload/zixun/";
    }

    /**
     * 我要咨询页面
     */
    public function start()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $data = array();

        //常用接收人【管理员】
        $data['calist'] = $this->zixun->getCommonAdmin($userid);
        //常用接收人【会员】
        $data['culist'] = $this->zixun->getCommonJigou($userid);

        //表单提交地址
        $form_url = site_url($this->controller_name . "/doask");
        $data['form_url'] = $form_url;

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/start", $data);
    }

    /**
     * 接收咨询
     */
    public function doask()
    {
        //用户id
        $id = verify_id($this->sess['userid']);

        $post = $this->input->post();
        //表单数据
        $model = $this->getmysqlmodel();
        //接受者管理员
        $receive_sysuserid = isset($post['receive_sysuserid']) ? $post['receive_sysuserid'] : array();
        //接受者用户
        $receive_userid = isset($post['receive_userid']) ? $post['receive_userid'] : array();

        //插入模型
        //发送人用户ID
        $model['send_userid'] = $id;
        $model['create_time'] = time();

        $error_list1 = array();
        $error_list2 = array();
        //插入管理员
        foreach ($receive_sysuserid as $item) {
            $model['receive_sysuserid'] = $item;
            $model['receive_userid'] = 0;
            $newid = $this->zixun->insert($model);
            if ($newid > 0) {
                //插入成功！

            } else {
                //插入失败！
                $error_list1[] = $item;
            }
        }

        //插入会员
        foreach ($receive_userid as $item) {
            $model['receive_userid'] = $item;
            $model['receive_sysuserid'] = 0;
            $newid = $this->zixun->insert($model);
            if ($newid > 0) {
                //插入成功！

                /*发送站内信*/
                //$user_model = $this->user->GetModel($item);

                $title = "{$this->sess['username']}向您咨询";
                $url = site_url($this->controller_name . "/reply") . "?id={$newid}";
                $content = "您好，您有一条新的服务咨询，编号为{$newid}，请到位置：\"会员后台 > 服务咨询\" 栏目，查看。<a href='{$url}' target='_blank'>或点击此处直接查看</a>";
                send_zhan_mail($model['receive_userid'], '', $title, $content, $model['send_userid'], null, $this->data["sess"]["session_id"]);


            } else {
                //插入失败！
                $error_list2[] = $item;
            }
        }


        /* 显示页面的信息 */
        $str = "发布咨询成功！请耐心等候回复！";
        $success = true;
        if (!empty($error_list1)) {
            $str = "";
            $success = false;

            foreach ($error_list1 as $item) {
                $str .= "管理员（id：{$item} ),";
            }
            $str .= "发送失败\n";

        }
        if (!empty($error_list2)) {
            $str = "";
            $success = false;
            foreach ($error_list1 as $item) {
                $str .= "机构（id：{$item} ),";
            }
            $str .= "发送失败\n";
        }
        $this->parent_showmessage($success, $str, site_url($this->controller_name . "/lists"), 5);
    }

    /**
     * 处理编辑，从view页面发起的请求
     */
    public function doedit()
    {
        $post = $this->input->post();

        //本条咨询的id
        $zixun_id = verify_id($this->input->get_post("id"));
        $model = $this->zixun->getModel($zixun_id);
        if (empty($model)) {
            parent::parent_showmessage(0, "没有这条咨询！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }

        //无权编辑
        if ($this->sess['userid'] != $model['send_userid']) {
            parent::parent_showmessage(0, "不能修改这条咨询！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }

        //$model['id'] = $zixun_id;
        //咨询内容
        $model['content'] = $post['content'];

        $num = $this->zixun->update($model);
        $view_url = site_url($this->controller_name . "/view") . "?id={$zixun_id}";
        if ($num > 0) {
            parent::parent_showmessage(1, "修改咨询内容成功！！！", $view_url, 3);
        } else {
            parent::parent_showmessage(0, "修改咨询内容失败！！！", $view_url, 3);
        }

        exit();
    }


    /**
     * 我的咨询页面
     */
    public function lists()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $post = $this->input->post();
        $get = $this->input->get();

        //分页相关
        $pageindex = $this->input->get_post("per_page");
        $pagesize = 15;
        if (empty($pageindex) || $pageindex <= 0) {
            $pageindex = 1;
        }

        /* 搜索相关,采用get */
        //初始化显示到页面时的搜索变量
        $search_val = array();
        //初始化数据库的where
        $search = array();
        //标题/咨询对象
        $search_val['name'] = isset($get['search_name']) ? $get['search_name'] : "";
        if ($search_val['name'] != "") {
            $search['name'] = $search_val['name'];
        }
        //是否已经回复，0未回，1代表已回
        $search_val['isread'] = isset($get['search_isread']) ? $get['search_isread'] : "";
        if ($search_val['isread'] != "") {
            $search['isread'] = $search_val['isread'];
        }

        $search['pid'] = 0;//显示父级id为0的咨询
        //列表排序
        $orderby = array("receive_isread" => "asc", "id" => "desc");

        //获得list，内有pager和list
        $usertype = $this->sess['usertype'];
        if ($usertype == '45063') {
            //企业列表
            $data = $this->zixun->getList_qy($pageindex, $pagesize, $search, $orderby, $userid);
        } else {
            //机构列表
            $data = $this->zixun->getList_jigou($pageindex, $pagesize, $search, $orderby, $userid);
        }

        //遍历list
//        foreach ($data['list'] as $k => $v) {
//            //$k是数组的序号,$v是每一个咨询
//            if ($v['receive_userid'] != 0) {
//                //查找会员
//                $model = $this->user->GetModel($v['receive_userid']);
//                $data['list'][$k]['username'] = $model['username'];
//                $data['list'][$k]['zixun_type'] = "[机构]";
//            } else {
//                //查找管理员
//                $model = $this->sysuser->GetModel($v['receive_sysuserid']);
//                $data['list'][$k]['username'] = $model['username'];
//                $data['list'][$k]['zixun_type'] = "[管理员]";
//            }
//        }

        //搜索变量
        $data['search_val'] = $search_val;

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/list", $data);
    }

    /**
     * 查看服务咨询页面
     */
    public function view()
    {

        //链接
        $this->data['method'] = "lists";
        $this->data['cur_url'] = site_url($this->controller_name . "/" . $this->data['method']);

        //用户id
        $id = verify_id($this->sess['userid']);

        $post = $this->input->post();
        $get = $this->input->get();


        //本条咨询的id
        $zixun_id = verify_id($this->input->get_post("id"));
        //获得模型
        $model = $this->zixun->getModel($zixun_id);
        if (empty($model)) {
            parent::parent_showmessage(0, "没有这条咨询！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        //无权查看
        if ($this->sess['userid'] != $model['send_userid']) {
            parent::parent_showmessage(0, "不能查看这条咨询！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }

        if ($model['receive_userid'] != 0) {
            //查找会员
            $user_model = $this->user->GetModel($model['receive_userid']);
            $model['username'] = $user_model['username'];
        } else {
            //查找管理员
            $admin_model = $this->sysuser->GetModel($model['receive_sysuserid']);
            $model['username'] = $admin_model['username'];
        }
        //获取追问信息
        $orderby = array("receive_isread" => "asc", "id" => "desc");
        $zw_model = $this->zixun->getList_qy(0, 99999, array('pid'=>$zixun_id), $orderby, $id);
        $data['zw_model'] = $zw_model;//咨询追问信息
        $data['model'] = $model;
        //追问表单
        $form_url = site_url($this->controller_name . "/doview");
        $data['form_url'] = $form_url;

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/edit", $data);
    }
     /**
     * 【企业】执行追问
     */
    public function doview()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $get = $this->input->get();
        $post = $this->input->post();

        //本条咨询id(父级id)
        $zixun_id = verify_id($this->input->get_post("mysql_pid"));
        if ($zixun_id == 0) {
            parent::parent_showmessage(0, "追问失败！请重新尝试！", $this->data['cur_url'], 5);
            exit();
        }

        //表单数据
        $model = $this->getmysqlmodel();

        $model['receive_isread'] = '0';
        $model['create_time'] = time();
        $model['send_userid'] = $userid;

        $nums = $this->zixun->insert($model);//插入追问信息
        if ($nums > 0) {
            /*发送站内信*/
            $title = "{$this->sess['username']}向您追问咨询";
            $url = site_url($this->controller_name . "/reply") . "?id={$zixun_id}";
            $content = "您好，您有一条服务咨询追问信息，编号为{$zixun_id}，请到位置：\"会员后台 > 服务咨询\" 栏目，查看。<a href='{$url}' target='_blank'>或点击此处直接查看</a>";
            send_zhan_mail($model['receive_userid'], '', $title, $content, $model['send_userid'], null, $this->data["sess"]["session_id"]);

            parent::parent_showmessage(1, "追问成功！！！", site_url($this->controller_name . "/lists"), 5);
        } else {
            parent::parent_showmessage(0, "追问失败！请重新尝试！", $this->data['cur_url'], 5);
        }
    }
    /**
     * 执行删除操作,自己删除的自己看不到
     */
    public function delete()
    {
        //用户id
        $userid = $this->sess['userid'];
        $usertype = $this->sess['usertype'];

        $get = $this->input->get();
        //id列表
        $idlist = $get["idlist"];
        $arr = explode(",", $idlist);
        //记录操作失败的数量
        $count = 0;
        foreach ($arr as $zixun_id) {

            $model = $this->zixun->getModel($zixun_id);

            //每一条咨询id
            //$model['id'] = $zixun_id;

            if ($usertype == '45063') {
                //企业
                //有权才能删除
                if ($userid == $model['send_userid']) {
                    $model['isdel_send'] = "1";
                    $model['del_time_send'] = time();
                    $model['del_send_userid'] = $userid;
                }
            } elseif ($usertype == '45064') {
                //机构

                //无权删除
                if ($userid == $model['receive_userid']) {
                    $model['isdel_receive'] = "1";
                    $model['del_time_receive'] = time();
                    $model['del_receive_userid'] = $userid;
                }
            }

            $rows = $this->zixun->update($model);
            if ($rows == 0) {
                $count++;
            }
        }

        echo $count;
        exit();
    }

    /**
     * 回复页面
     */
    public function reply()
    {
        //链接
        $this->data['method'] = "lists";
        $this->data['cur_url'] = site_url($this->controller_name . "/" . $this->data['method']);

        //用户id
        $userid = verify_id($this->sess['userid']);

        $post = $this->input->post();
        $get = $this->input->get();

        //本条咨询的id
        $zixun_id = verify_id($this->input->get_post("id"));
        //获得模型
        $model = $this->zixun->getModel($zixun_id);
        //检查条件
        if (empty($model)) {
            parent::parent_showmessage(0, "没有这条咨询！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        if ($userid != $model['receive_userid']) {
            parent::parent_showmessage(0, "不能回复这条咨询！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        //获取追问信息
        $orderby = array("receive_isread" => "asc", "id" => "desc");
        $zw_model = $this->zixun->getList_jigou(0, 99999, array('pid'=>$zixun_id), $orderby, $userid);
        $data['zw_model'] = $zw_model;//咨询追问信息

        //名字
        $user_model = $this->user->GetModel($model['send_userid']);
        $model['username'] = $user_model['username'];

        //表单提交地址
        $form_url = site_url($this->controller_name . "/doreply");
        $data['form_url'] = $form_url;

        $data['model'] = $model;
        // echo $zixun_id.','.$userid;exit;
        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/reply", $data);
    }

    /**
     * 【机构】执行回复
     */
    public function doreply()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $get = $this->input->get();
        $post = $this->input->post();

        //本条咨询id
        $zixun_id = verify_id($this->input->get_post("id"));
        if ($zixun_id == 0) {
            parent::parent_showmessage(0, "回复失败！请重新尝试！", $this->data['cur_url'], 5);
            exit();
        }

        //表单数据
        $model = $this->getmysqlmodel();

        $model['id'] = $zixun_id;
        $model['receive_isread'] = '1';
        $model['receive_time'] = time();

        $nums = $this->zixun->update($model);
        if ($nums > 0) {
            /*发送站内信*/
            $model = $this->zixun->getModel($zixun_id);

            $title = "您的服务咨询已收到回复，编号为{$zixun_id}";
            $url = site_url($this->controller_name . "/view") . "?id={$zixun_id}";
            $content = "您好，您有一条服务咨询已收到回复，编号为{$zixun_id}，请到位置：\"会员后台 > 我的咨询\" 栏目，查看。<a href='{$url}' target='_blank'>点击此处直接查看</a>";
            $re = send_zhan_mail($model['send_userid'], '', $title, $content, $model['receive_userid'], null, $this->data["sess"]["session_id"]);
            //echo $re;
            //exit();
            parent::parent_showmessage(1, "回复成功！！！", site_url($this->controller_name . "/lists"), 5);
        } else {
            parent::parent_showmessage(0, "回复失败！请重新尝试！", $this->data['cur_url'], 5);
        }
    }
    //追问回复弹框bylk
    public function zw_reply()
    {
        $get = $this->input->get();

        //搜索变量
        $zixun_id = verify_id($get['id']);
        //服务类型列表
        $data['model'] = $this->zixun->getModel($zixun_id);
        //合并
        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/zw_reply", $data);
    }
    //进行追问信息答复
    public function dozwreply() 
    {
        $userid = verify_id($this->sess['userid']);
        $result   = array('code' => -1, 'msg' => '回复失败！请重新尝试！');
        $zixun_id = verify_id($this->input->get_post("zxid"));//追问咨询id
        $content  = $this->input->get_post('content');//回复内容

        $model = $this->zixun->getModel($zixun_id);
        //检查条件
        if (empty($model)) {
            $result['msg'] = '没有这条咨询！！！';
            echo json_encode($result);
            exit();
        }
        if ($userid != $model['receive_userid']) {
            $result['msg'] = '不能回复这条咨询！！！';
            exit();
        }
        $data = array('id'=>$zixun_id, 'receive_isread'=>'1',
            'receive_time'=>time(),'receive_content'=>$content);
        $nums = $this->zixun->update($data);
        if ($nums > 0) {
            /*发送站内信*/
            $title = "您的追问服务咨询已收到回复，编号为{$model['pid']}";
            $url = site_url($this->controller_name . "/view") . "?id={$model['pid']}";
            $content = "您好，您有一条服务咨询已收到回复，编号为{$model['pid']}，请到位置：\"会员后台 > 我的咨询\" 栏目，查看。<a href='{$url}' target='_blank'>点击此处直接查看</a>";
            $re = send_zhan_mail($model['send_userid'], '', $title, $content, $model['receive_userid'], null, $this->data["sess"]["session_id"]);
            $result['code'] = 1;
            $result['msg'] = '回复成功';
            echo json_encode($result);exit;
        } else {
            echo json_encode($result);exit;
        }
    }
    /**
     * 插件(kindeditor)上传文件
     */
    public function upload()
    {
        // echo $this->sysconfig->test33();exit;
        //包含kindeditor的上传文件
        $save_path = $this->upload_path; // 编辑器上传的文件保存的位置
        $save_url = $this->upload_save_url; //访问的路径
        include_once __ROOT__ . '/' . APPPATH . "libraries/JSON.php";
        include_once __ROOT__ . '/' . APPPATH . "libraries/upload_json.php";
    }

    /**
     * 选择会员
     */
    public function seluser()
    {
        $pageindex = $this->input->get_post("per_page");

        if ($pageindex <= 0) {
            $pageindex = 1;
        }

        $get = $this->input->get();
        /* 搜索相关,采用get */
        //初始化显示到页面时的搜索变量
        $search_val = array();
        //数据库的where
        $search = array();
        //搜索名称
        $search_val['name'] = isset($get['search_name']) ? $get['search_name'] : "";
        if ($search_val['name'] != "") {
            $search['name'] = $search_val['name'];
        }
        //搜索机构服务类型
        $search_val['server_type'] = isset($get['server_type']) ? $get['server_type'] : array();
        if (!empty($get['server_type'])) {
            //转化数组为字符串,在数据库中查询

//            $str_server_type = "";
//            foreach ($get['server_type'] as $item) {
//                $str_server_type .= $item . ",";
//            }
//            //去掉最后一个逗号
//            $str_server_type = substr($str_server_type, 0, strlen($str_server_type) - 1);

            $search['server_type'] = $get['server_type'];
        }

        //查列表
        $search['usertype'] = "45064";//类型为机构
        $search['status'] = 1;//选择正常用户
        $search['checkstatus']=1;//选择通过审核的用户
        //排序
        $order_by['uid'] = "desc";
        $data = $this->user->getList2($pageindex, 25, $search, $order_by);
        //搜索变量
        $data['search_val'] = $search_val;
        //服务类型列表
        $server_type_list = $this->ccd->GetServerType();
        $data["server_type_list"] = $server_type_list;

        //合并
        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/selectuser", $data);
    }

    /**
     * 选择管理员
     */
    public function seladmin()
    {
        $pageindex = $this->input->get_post("per_page");

        if ($pageindex <= 0) {
            $pageindex = 1;
        }

        $get = $this->input->get();

        /* 搜索相关,采用get */
        //初始化显示到页面时的搜索变量
        $search_val = array();
        //数据库的where
        $search = array();
        //搜索名称
        $search_val['name'] = isset($get['search_name']) ? $get['search_name'] : "";
        if ($search_val['name'] != "") {
            $search['username'] = $search_val['name'];
        }

        //查列表
        $orderby["convert(username using gbk)"] = "asc";
        $data = $this->sysuser->GetInfoList($pageindex, 25, $search, $orderby);
        //$data = $this->user->getList($pageindex, 25, $search, 45064);
        //搜索变量
        $data['search_val'] = $search_val;
        //服务类型列表
        $server_type_list = $this->ccd->GetServerType();
        $data["server_type_list"] = $server_type_list;

        //合并
        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/selectadmin", $data);
    }

    public function test()
    {
        //var_dump($this->parent_getsession());
    }


}