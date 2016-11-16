<?php
/*
 *网站用户管理controller
 *author  王建 
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * 企业或者服务机构
 * Class User
 * @property M_user $user 用户模型
 * @property M_common_category_data $ccd 公用目录模型
 * @property M_website_category $wc 网站目录模型
 * @property m_common_sms $common_sms 发送短信模型
 *
 * @property CI_Session $session CI的sesscion
 * @property CI_Input $input
 */
class User extends MY_Controller
{
    var $data;
    private $views_folder;

    private $controller;

    function User()
    {
        parent::__construct();
        $this->load->model('M_common', '', false, array('type' => 'real_data'));
        $this->load->model('M_user', 'user');
        $this->load->model('M_common_system_user', 'system_user');
        $this->load->model('M_common_category_data', 'ccd');
        $this->load->model('m_website_category', 'wc');
        $this->load->model('m_swj_fujian', 'fj');

        $this->load->model('m_common_sms', 'common_sms');

        $this->load->library('session');

        $this->views_folder = __TEMPLET_FOLDER__ . "/zcq/user";

        $this->controller = "user";

        $this->upload_path = __ROOT__ . "/data/upload/user/";
        $this->upload_path_sys = "data/upload/user/";
        $this->data["sess"] = $this->parent_getsession();;
    }

    function index()
    {

        $pageindex = $this->input->get_post("per_page");

        if ($pageindex <= 0) {
            $pageindex = 1;
        }


        $get = $this->input->get();
        //搜索传递的变量
        $selcheck = isset($get["selcheck"]) ? $get["selcheck"] : "";
        //用于识别是否来自基础资料栏目，如是，则隐藏无用的按钮
        $isjichu = empty($get["isjichu"]) ? 0 : $get["isjichu"];
        $username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username", true)))));
        if (isset($get["status"])) {
            $status = daddslashes(html_escape(strip_tags(trim($this->input->get_post("status", true)))));
        } else {
            $status = "-1";
        }
        //45063代表企业45064代表机构
        $usertype = empty($get["usertype"]) ? "" : $get["usertype"];

        //数据库的where
        $search = array();

        //显示到页面时的搜索变量
        $search_val = array(
            "username" => "",//用户名/手机号/单位/联系人
            "status" => $status,//冻结状态
            "selcheck" => $selcheck,//审核状态
            "usertype" => $usertype,//企业或者结构
        );
        //用户类型
        if ($usertype > 0) {
            $search["usertype"] = $usertype;
        }
        if (!empty($username)) {
            $search_val["username"] = $username;
            $search["username"] = $username;
        }
        //冻结状态
        if ($status >= 0) {
            //加等号
            $search['status'] = "='" . strval($status) . "'";
            $search_val["status"] = $status;
        } else {
            $search['status'] = "";
            $search_val["status"] = "-1";
        }
        //审核状态
        if ($selcheck != "") {
            $search['selcheck'] = $selcheck;
        }

        //机构服务类型
        if (isset($get['server_type'])) {
            $server_type = $get['server_type'];
            //转化数组为字符串
            $str_server_type = "";
            foreach ($server_type as $item) {
                $str_server_type .= $item . ",";
            }
            //去掉最后一个逗号
            $str_server_type = substr($str_server_type, 0, strlen($str_server_type) - 1);

            if ($usertype == "45064") {
                //机构用户才可以搜索服务类型
                $search['server_type'] = $str_server_type;
            }

            //反馈到列表中
            $search_val['server_type'] = $server_type;
        } else {
            //
            $search_val['server_type'] = array();
        }

        $orderby["uid"] = "desc";
        $data = $this->user->GetInfoList($pageindex, 15, $search, $orderby);
        $data["isadd"] = $this->permition_for("user", "add");
        $data["isdel"] = $this->permition_for("user", "del");

        $data["search_val"] = $search_val;

        //用户类型列表,企业或者机构
        $usertype_list = $this->ccd->GetUserType();
        $data["usertype_list"] = $usertype_list;
        //服务类型列表
        $server_type_list = $this->ccd->GetServerType();
        $data["server_type_list"] = $server_type_list;

        //检查有无审核
        $data['usertype'] = $usertype;//45063代表企业45064代表机构

        foreach ($data["list"] as $k => $v) {
            $data["list"][$k]['status'] = ($v['status'] == 1) ? "" : '<font color="red">(冻结)</font>';
            $data["list"][$k]['regdate'] = $v['regdate'];//date("Y-m-d H:i:s", $v['regdate']);
            $data["list"][$k]['lastlogin'] = (date('Y', strtotime($v['lastlogin'])) == 1970) ? '<font color="green">-</font>' : date("Y-m-d H:i:s", strtotime($v['lastlogin']));
        }

        $this->load->view($this->views_folder . "/list", $data);
//        $this->load->view(__TEMPLET_FOLDER__ . "/views_user", $data);

    }

    //修改或者查看企业
    function edit_qy()
    {
        $post = $this->input->post();
        if (is_array($post)) {
            $userid = $post["userid"];
            $model = $this->qyuser->GetModelFromUserID($userid);
            $model2 = $this->getmysqlmodel();
            foreach ($model2 as $k => $v) {
                $model[$k] = $v;
            }
            //补充
            $model["company_type"] = empty($post["company_type"]) ? "" : (implode(",", $post["company_type"]));
            $model["business_model"] = empty($post["jiaoyi"]) ? "" : (implode(",", $post["jiaoyi"]));
            $model["upload_paper_type"] = $post["upload_paper_type"];
            $model['company_type_other'] = $post["company_type_other"];//其他企业类型
            $model['business_model_other'] = $post["business_model_other"];//其他交易模式
            //根据二级分类，取得主营产品一级分类
            if ($model["product2"] != "") {
                $model["product"] = $this->ccd->GetParentFromSub($model["product2"]);
            }
            //无值就设为审核通过
            if (!isset($model["audit"])) {
                $model["audit"] = "10";
            }

            $model["lastupdatetime"] = date("Y-m-d H:i:s");
            $model["inputtime"] = date("Y-m-d H:i:s");
            $model["updatetime"] = date("Y-m-d H:i:s");


            if (isset($model["userid"])) {
                $this->qyuser->update($model);
            } else {
                $model["userid"] = $userid;
                $this->qyuser->add($model);
            }

            showmessage("保存成功", $_SERVER['HTTP_REFERER'], 3, 1);

            exit();

        } else {
            $get = $this->input->get();
            $templet = @$get['op'] == 'look' ? 'views_user_look_qy' : 'views_user_edit_qy';//调用的模板查看或者编辑
            $userid = $get["id"];
            $this->data["model"] = $this->qyuser->GetModelFromUserID($userid);
            if (isset($this->data["model"]["product2"])) {
                //读主营商品
                if ($this->data["model"]["product2"] != "") {
                    //去掉左右的单引号,2016年4月6日09:13:25
                    $model_product2 = explode(',', $this->data["model"]["product2"]);
                    $model_product2 = array_filter($model_product2);//去掉空元素
                    // var_dump($model_product2);exit;
                    $model_product2 = implode(',', $model_product2);
                    $product2 = $this->ccd->GetList2(" id in(" . $model_product2 . ")");
                } else {
                    $product2 = "";
                }
            } else {
                $product2 = "";
            }
            if (count($this->data["model"]) > 0) {
                //获取证件id对应的附件信息
                $szhy = $this->data["model"]['three_code_add_id'];//三证合一
                $this->data["three_code_add_info"] = $szhy ? $this->fj->GetModel($szhy) : '';//三证合一
                $szhy = $this->data["model"]['business_licence_id'];//营业执照
                $this->data["business_licence_info"] = $szhy ? $this->fj->GetModel($szhy) : '';//营业执照
                $szhy = $this->data["model"]['organization_code_id'];//组织机构代码证
                $this->data["organization_code_info"] = $szhy ? $this->fj->GetModel($szhy) : '';//组织机构代码证
                $szhy = $this->data["model"]['shuiwu_register_code_id'];//税务登记证
                $this->data["shuiwu_register_code_info"] = $szhy ? $this->fj->GetModel($szhy) : '';//税务登记证
            }

            $this->data["product2"] = $product2;
            $this->data["company_type"] = $this->getccd(11, 45123);
            $this->data["jiaoyi"] = $this->getccd(11, 45129);
            $this->data["zhuying"] = $this->getccd(11, 45135);
            $this->data["town"] = $this->getccd(6, 3145, "id asc");
            $this->data["userid"] = $userid;
            $this->data["isedit"] = true;
            // var_dump($this->data["model"]);exit;
            $this->load->view(__TEMPLET_FOLDER__ . "/{$templet}", $this->data);
        }

    }

    //修改或者查看协会
    function edit_xh()
    {
        $post = $this->input->post();
        if (is_array($post)) {
            $userid = $post["userid"];
            $model = $this->xhuser->GetModelFromUserID($userid);
            $model2 = $this->getmysqlmodel();
            foreach ($model2 as $k => $v) {
                $model[$k] = $v;
            }

            //无值就设为审核通过
            if (!isset($model["audit"])) {
                $model["audit"] = "10";
            }
            $model["lastupdatetime"] = date("Y-m-d H:i:s");
            $model["inputtime"] = date("Y-m-d H:i:s");
            $model["updatetime"] = date("Y-m-d H:i:s");


            if (isset($model["userid"])) {
                $this->xhuser->update($model);
            } else {
                $model["userid"] = $userid;
                $this->xhuser->add($model);
            }
            showmessage("保存成功", $_SERVER['HTTP_REFERER'], 3, 1);
            exit();

        } else {
            $userid = $get = $this->input->get();
            $templet = @$get['op'] == 'look' ? 'views_user_look_xh' : 'views_user_edit_xh';//调用的模板查看或者编辑
            $userid = $get["id"];
            $this->data["model"] = $this->xhuser->GetModelFromUserID($userid);
            $this->data["isedit"] = true;
            $this->data["userid"] = $userid;
            $this->load->view(__TEMPLET_FOLDER__ . "/{$templet}", $this->data);
        }

    }

    //读取公共数据
    private function getccd($typeid, $pid)
    {
        return $this->ccd->GetListFromTypeidAndPid($typeid, $pid);
    }

    //编辑页面
    function edit()
    {
        $action = $this->input->get_post("action");
        $action_array = array("edit", "doedit");
        $action = !in_array($action, $action_array) ? 'edit' : $action;
        $session_id = $this->session->userdata('session_id');
        $company = $this->ccd->GetListFromTypeid(7);
        $fenzhi_jigou = $this->wc->GetSubList("23");
        if ($action == 'edit') {
            $id = verify_id($this->input->get_post("id"));
            //用户的信息数组$info
            $info = $this->user->GetModel($id);
            $data = array(
                'session_id' => $session_id,
                'isadmin' => is_super_admin()
            );

            //$data["istop_list"] = array("3天"=>"3","一周"=>"7","一个月"=>30,"三个月"=>"90","半年"=>"180");

            //用户分类list有两个记录，企业用户或者机构用户的分类
            $usertype_list = $this->ccd->GetUserType();
            $data["usertype_list"] = $usertype_list;

            //服务类型
            $server_list = $this->ccd->GetServerType();
            $data['server_list'] = $server_list;

            //默认是企业
            $data['usertype_name'] = "企业";
            if ($info["usertype"] == "0") {
                $info["usertype"] = "45063";
            }

            //判断企业还是机构
            foreach ($usertype_list as $each) {
                //$info["usertype"]是45063或者45064
                if ($each["id"] == $info["usertype"]) {
                    //mb_substr截取中文字符串，一个中文字一个字符
                    $data['usertype_name'] = mb_substr($each["name"], 0, strlen($each["name"] - 4));
                }
            }

            //其它资料，图片数组
            $data["ziliaos"] = json_decode($info['ziliao_other']);
            $data["ziliaos"] = $data["ziliaos"] ? json_decode($info['ziliao_other']) : array();

            //表单的
            $data['form_url'] = site_url("user/doedit");
            $data["model"] = $info;
            $data["company"] = $company;
            $this->load->view($this->views_folder . "/edit_qyjg_v2", $data);
        } elseif ($action == 'doedit') {
            $this->doedit();
        }

    }

    //处理编辑数据
    public function doedit()
    {

        $post = $this->input->post();
        $uid = $post["id"];
        $model = $this->user->GetModel($uid);
        //企业简称
        $username = $post["mysql_username"];
        //将表单传来的数据设置好
        $model_form = $this->getmysqlmodel();
        foreach ($model_form as $k => $v) {
            $model[$k] = $v;
        }

        if ($post["pwd"] != "") {
            $model["passwd"] = md5($post["pwd"]);
        }
        $model["expire"] = 0;//过期日期
        //$expire = 0;

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

        /* 其他资料保存 */
        if (isset($post['ziliao_uploads']) && isset($post['ziliao_des'])) {
            //图片路径
            $ziliao_paths = $post['ziliao_uploads'];
            //图片描述
            $ziliao_des = $post['ziliao_des'];
            $ziliaos = array();
            foreach ($ziliao_paths as $k => $v) {
                $ziliao = array("path" => $ziliao_paths[$k], "des" => $ziliao_des[$k]);
                $ziliaos[] = $ziliao;
            }
            $model['ziliao_other'] = json_encode($ziliaos);
        }

        if ($post["mysql_username"] == "") {
            showmessage("企业简称不能为空", "user/index", 3, 0);
            exit();
        }

        //查询是否有uid不能，username相同的记录存在
        $info = $this->M_common->query_one("SELECT username FROM `{$this->table_}common_user` where isdel='0' and uid<>$uid and username = '{$username}' limit 1 ");
        if (!empty($info)) {
            showmessage("企业简称{$username}已经存在", "user/index", 3, 0);
            exit();
        }

        $this->user->update($model);
        if (true) {
            if ($post["backurl"] == "") {
                $backurl = site_url("user/index");
            } else {
                $backurl = $post["backurl"];

            }
            echo "<script>
			top.tip_show('修改成功',1,500);
			var url = \"$backurl\";
			parent.flushpage(url);
			top.topManager.closePage();
			</script>";
            exit();
        } else {
            //write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"新增用户为{$username}失败");
            showmessage("修改用户失败", "user/index", 3, 0);
            exit();
        }

        //}
    }

    ///用户增加
    function add()
    {
        $action = $this->input->get_post("action");
        $session_id = $this->session->userdata('session_id');
        $action_array = array("add", "doadd");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        //$isadmin = is_super_admin();
        $company = $this->ccd->GetListFromTypeid(7);
        $fenzhi_jigou = $this->wc->GetSubList("23");
        if ($action == 'show') {
            $data = array(
                'session_id' => $session_id,
                'isadmin' => is_super_admin()
            );
            //$data["istop_list"] = array("3天" => "3", "一周" => "7", "一个月" => 30, "三个月" => "90", "半年" => "180");


            //用户分类list有两个记录，企业用户或者机构用户的分类
            $usertype_list = $this->ccd->GetUserType();
            $data["usertype_list"] = $usertype_list;

            //服务类型
            $server_list = $this->ccd->GetServerType();
            $data['server_list'] = $server_list;

            //$data["sheng_list"] = $this->ccd->GetSheng();

            //$data["company"] = $company;
            //$data["fenzhi_jigou"] = $fenzhi_jigou;

            //$shi_list = $this->ccd->GetShi($shengid);

            //空模型
            $model = $this->user->getFields();
            //三证
            $model['sanzheng'] = 1;
            $model["usertype"] = 45063;
            $model["status"] = 1;
            //其它资料
            $data["ziliaos"] = array();
            $data['model'] = $model;
            //表单的
            $data['form_url'] = site_url("user/doadd");
            $this->load->view($this->views_folder . "/edit_qyjg_v2", $data);
        } elseif ($action == 'doadd') {
            $this->doadd();
        }
    }

    //处理增加
    public function doadd()
    {
        $post = $this->input->post();
        $username = $post["mysql_username"];
        $model = $this->getmysqlmodel();
        $model["passwd"] = md5($post["pwd"]);
        $model["expire"] = 0;//过期日期

        //$model["email"] = $username;
        //$expire = 0;

        //注册日期
        $model["regdate"] = date("Y-m-d H:i:s", time());
        //默认未审核
        $model["checkstatus"] = 0;
        //状态1为正常，0为冻结
        $model['status'] = 1;

        /* 服务类型 */
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

        /* 其他资料保存 */
        if (isset($post['ziliao_uploads']) && isset($post['ziliao_des'])) {
            //图片路径
            $ziliao_paths = $post['ziliao_uploads'];
            //图片描述
            $ziliao_des = $post['ziliao_des'];
            $ziliaos = array();
            foreach ($ziliao_paths as $k => $v) {
                $ziliao = array("path" => $ziliao_paths[$k], "des" => $ziliao_des[$k]);
                $ziliaos[] = $ziliao;
            }
            $model['ziliao_other'] = json_encode($ziliaos);
        }


        if ($post["mysql_username"] == "") {
            showmessage("简称不能为空!", "user/add", 3, 0);
            exit();
        }
        //查询用户是否存在
        $info = $this->M_common->query_one("SELECT username FROM `{$this->table_}common_user` where isdel='0' and username = '{$username}' limit 1 ");
        if (!empty($info)) {
            showmessage("简称{$username}已经存在", "user/add", 3, 0);
            exit();
        }

        $newid = $this->user->insert($model);

        if (true) {
            if ($post["backurl"] == "") {
                $backurl = site_url("user/index");
            } else {
                $backurl = $post["backurl"];
            }

            $backurl = site_url("user/edit") . "?id=" . $newid . "&backurl=" . urlencode("user/index");

//            if ($model["usertype"] == "45063") {
//                $backurl = site_url("user/edit") . "?id=" . $newid . "&backurl=" . urlencode("user/index");
//            }
//            if ($model["usertype"] == "45064") {
//                $backurl = site_url("user/edit") . "?id=" . $newid . "&backurl=" . urlencode("user/index");
//            }
            echo "<script>
			top.tip_show('新增成功，现在转到编辑页面，请继续编辑补料单位资料',1,2000);
			setTimeout(\"window.location.href='" . $backurl . "';\",2000);	
			</script>";
            //header("Location:".$backurl);
            //showmessage("新增用户成功","user/index",3,1);
            //exit();
            exit();
        } else {
            //write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"新增用户为{$username}失败");
            showmessage("新增用户失败", "user/add", 3, 0);
            exit();
        }
    }

    /**
     * 查看用户
     */
    public function view()
    {

        $session_id = $this->session->userdata('session_id');
        $company = $this->ccd->GetListFromTypeid(7);

        $id = verify_id($this->input->get_post("id"));
        //用户的信息数组$info
        $model = $this->user->GetModel($id);
        $data = array(
            'session_id' => $session_id,
            'isadmin' => is_super_admin()
        );

        //用户分类list有两个记录，企业用户或者机构用户的分类
        $usertype_list = $this->ccd->GetUserType();
        $data["usertype_list"] = $usertype_list;

        //服务类型
        $server_list = $this->ccd->GetServerType();
        $data['server_list'] = $server_list;

        //默认是企业
        $data['usertype_name'] = "企业";
        if ($model["usertype"] == "0") {
            $model["usertype"] = "45063";
        }

        //判断企业还是机构
        foreach ($usertype_list as $each) {
            //$info["usertype"]是45063或者45064
            if ($each["id"] == $model["usertype"]) {
                //mb_substr截取中文字符串，一个中文字一个字符
                $data['usertype_name'] = mb_substr($each["name"], 0, strlen($each["name"] - 4));
            }
        }

        //其它资料，图片数组
        $data["ziliaos"] = json_decode($model['ziliao_other']);
        $data["ziliaos"] = $data["ziliaos"] ? json_decode($model['ziliao_other']) : array();

        //表单的
        $data['form_url'] = site_url("user/view");

        $data["model"] = $model;
        $data["company"] = $company;
        $data["view_mod"] = true;
        $this->load->view($this->views_folder . "/edit_qyjg_v2", $data);

    }

    //生成缓存
    private function make_cache()
    {
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
        $str .= "\$role_array = ";
        $str .= var_export($this->perm_data, true);
        fwrite($fp, "{$str};\r\n");
        //fwrite($fp,");\r\n");
        fwrite($fp, "?" . ">");
        fclose($fp);
    }

    //预览所在用户组的用户
    private function preview_user()
    {
        $id = verify_id($this->input->get_post("id"));
        if ($id <= 0) {
            echo "参数传递错误";
            exit();
        }
        $list = $this->M_common->querylist("SELECT id,username,status FROM {$this->table_}common_system_user where gid = '{$id}' ");
        if ($list) {
            foreach ($list as $k => $v) {
                $status = ($v['status'] == 1) ? "<font color='green'>正常</font>" : '<font color="red" >禁止</font>';
                echo "<li style=\"text-decoration:none; display:block ; width:100px; height:30px; padding:2px; float:left; border:solid 1px #F0F0F0 ;  text-align:center;line-height:30px; margin-left:3px\">";
                echo $v['username'] . "【" . $status . "】";
                echo "</li>";
            }
        } else {
            echo "暂无用户";
        }
    }

    /**
     * 删除一条记录
     */
    public function deleteone()
    {
        $id = $this->input->get_post("id");

        //返回的json
        $re = array();

        $model['uid'] = $id;
        $model['isdel'] = '1';

        $rows = $this->user->update($model);
        if ($rows == 0) {
            $re['success'] = false;
            $re['msg'] = "删除项（id:{$id}）失败！";
        } else {
            $re['success'] = true;
            $re['msg'] = "删除项（id:{$id}）成功！";
        }

        echo json_encode($re);
    }

    function del()
    {
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $isdel = false;
        if (!is_super_admin()) {
            if ($this->permition_for('user', 'del')) {
                $isdel = true;
            }
        } else {
            $isdel = true;
        }
        //只有超管、二级管理员且同一党组织才能删除
        if ($idlist != "" && $isdel) {

            $arr = explode(",", $idlist);
            foreach ($arr as $v) {
                $this->user->del($v);
            }
        }
    }

    function setfufei()
    {
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $fufei = $get["fufei"];

        $arr = explode(",", $idlist);
        foreach ($arr as $v) {
            //$model = $this->user->GetModel($v);
            //$model["ispay"] = $fufei;
            //$this->user->update($model);
            if ($fufei == '1') {
                if ($this->jiaofei->count("userid=" . $v . " and niandu='" . date("Y") . "'") == 0) {
                    $this->jiaofei->add(array("userid" => $v, "niandu" => date("Y")));
                }
            } else {
                $this->jiaofei->del_from_userid($v);
            }
        }

    }

    //检查单位名
    function chkcompany()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $model = $this->M_common->query_one("select * from swj_register_dsqy where isdel=0 and name='$title'" . ($id > 0 ? " and userid<>$id" : ""));
        if (count($model) > 0) {
            $data = array("result" => "0", "msg" => "单位已存在");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }

    //检查单位名
    function chkcompany_xh()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $model = $this->M_common->query_one("select * from swj_register_xiehuiorjigou where isdel=0 and name='$title'" . ($id > 0 ? " and userid<>$id" : ""));
        if (count($model) > 0) {
            $data = array("result" => "0", "msg" => "单位已存在");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }

    //检查手机号是否重复
    function chktel()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $model = $this->M_common->query_one("select * from 57sy_common_user where isdel=0 and tel='$title'" . ($id > 0 ? " and uid<>$id" : ""));
        if (count($model) > 0) {
            $data = array("result" => "0", "msg" => "手机号码已存在");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }

    /**
     * 检查用户名是否重复
     */
    function chkusername()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $model = $this->M_common->query_one("select * from 57sy_common_user where isdel=0 and username='$title'" . ($id > 0 ? " and uid<>$id" : ""));
        if (count($model) > 0) {
            $data = array("result" => "0", "msg" => "用户名已存在");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }

    /**
     * 检查email是否重复
     */
    function chkemail()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $model = $this->M_common->query_one("select * from 57sy_common_user where isdel=0 and email='$title'" . ($id > 0 ? " and uid<>$id" : ""));
        if (count($model) > 0) {
            $data = array("result" => "0", "msg" => "邮箱已存在");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }

    /**
     * 公用检查函数
     * @param string $field 字段名字
     * @param string $errorMsg 错误消息
     */
    private function commonCheck($field, $errorMsg)
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $where = ($id > 0 ? " and uid<>$id" : "");

        //返回的数据,1为默认是成功的
        $re = array("result" => 1);

        $model = $this->M_common->query_one("select * from 57sy_common_user where isdel=0 and {$field}='{$title}' $where");
        if (count($model) > 0) {
            //重复了，返回失败信息
            $re['result'] = 0;
            $re['msg'] = $errorMsg;
        }
        echo json_encode($re);
    }

    /**
     * 走出去，检查社会信用代码是否重复
     */
    function chkxinyongzheng()
    {
        $this->commonCheck("xinyongzheng", "社会信用代码已存在");
    }

    /**
     * 走出去，检查组织机构是否重复
     */
    function chkzuzhijigou()
    {
        $this->commonCheck("zuzhijigou", "组织机构代码已存在");
    }

    /**
     * 走出去，组织或者机构的全称是否重复
     */
    function chkfullname()
    {
        $this->commonCheck("company", "组织或者机构的全称已存在");
    }

    /**
     * 设置审核是否通过
     */
    function set_check()
    {
        $get = $this->input->get();
        $idlist = $get["idlist"];

        //10代表通过审核，20代表不能通过审核
        $check = $get["check"];

        //审核失败的内容
        $content = empty($get["content"]) ? "" : $get["content"];

        $arr = explode(",", $idlist);
        $ischeck_0 = 0;//记录状态为未完善资料的用户数
        //$is_audit_change = false;//是否重复审核

        //记录发送邮件的一些信息
        $email_result = array();

        //遍历处理
        foreach ($arr as $v) {
            $model = $this->user->GetModel($v);
            $model['checkno_yuanyin'] = $content;
            //$model["uid"] = $v;

            if ($check == "10") {
                //checkstatus代表 0:未审,1:已审,99:审核不通过
                $model['checkstatus'] = 1;
                $model['checkdate'] = date("Y-m-d H:i:s");

            } elseif ($check == "20") {
                $model['checkstatus'] = 99;
                $model['checkdate2'] = date("Y-m-d H:i:s");

            }

            $rows = $this->user->update($model);
            if ($rows == 0) {
                $ischeck_0++;
            } else {
                //设置数据库成功后

                //发送邮件
                $email_option['id'] = $v;//记录id
                $email_option['model'] = $model;//记录模型
                $url = base_url();

                //if ($model['email'] != "") {
                if ($check == "10") {
                    $email_option['success'] = true;
                    //sendmail_help($model['email'], "【走出去】审核通知", "恭喜审核成功，<a href='$url'>请登录后台查看</a>");
                } else {
                    $email_option['success'] = false;
                    //sendmail_help($model['email'], "【走出去】审核通知", "本次审核不成功，请联系管理员！");
                }
                //}

                //加入数组
                $email_result[] = $email_option;
            }


            //判断用户类型
//            if ($model["usertype"] == "45063") {
//                $qy_model = $this->qyuser->GetModelFromUserID($model["uid"]);
//                if (count($qy_model) > 0) {
//                    if ($qy_model["audit"] == $check) {
//                        $is_audit_change = true;
//                    }
//                    $qy_model["audit"] = $check;
//                    $qy_model["auditer"] = login_name();
//                    $qy_model["auditcontent"] = $content;
//                    $qy_model["audittime"] = date("Y-m-d H:i:s");
//                    $qy_model["audit_userid"] = admin_id();
//                    $this->qyuser->update($qy_model);
//                } else {
//                    $ischeck_0++;
//                }
//            }
//            if ($model["usertype"] == "45064") {
//                $xh_model = $this->xhuser->GetModelFromUserID($model["uid"]);
//                if (count($xh_model) > 0) {
//                    if ($xh_model["audit"] == $check) {
//                        $is_audit_change = true;
//                    }
//                    $xh_model["audit"] = $check;
//                    $xh_model["auditer"] = login_name();
//                    $xh_model["auditcontent"] = $content;
//                    $xh_model["audittime"] = date("Y-m-d H:i:s");
//                    $xh_model["audit_userid"] = admin_id();
//                    $this->xhuser->update($xh_model);
//
//                } else {
//                    $ischeck_0++;
//                }
//            }
//
            //发送短信
            if ($check == 10) {
                //成功
                $this->common_sms->sendSmsToUser(
                    "【走出去】资料审核通过",
                    $model["uid"],
                    admin_id(),
                    "您好," . $model["realname"] . ",您的资料通过审核，可以登录系统使用",
                    "");

            }
            if ($check == 20) {
                //不通过
                $this->common_sms->sendSmsToUser(
                    "【走出去】资料审核不通过",
                    $model["uid"],
                    admin_id(),
                    "您好," . $model["realname"] . ",您的资料修改不通过；不通过原因为：{$model['checkno_yuanyin']}，请联系管理员",
                    "");
            }

        }


        //异步发送邮件
        $url = site_url($this->controller . "/sendChkEmail");
        //$temp = $this->parent_getsession();
        $data = array(
            "option" => json_encode($email_result),
            "session_id" => $this->data["sess"]['session_id']
        );
        asyncPost($url, $data);

        echo $ischeck_0;
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
                $model = $item->model;
                echo "发送{$model->email}";

                if ($model->email != "") {

                    write_action_log("", "", "", "", 1, "用户审核发送邮件{$model->email}");

                    if ($item->success) {
                        sendmail_help($model->email, "【走出去】审核通知", "您好，{$model->company}，恭喜审核成功，<a href='$url'>请登录后台查看</a>");
                    } else {
                        sendmail_help($model->email, "【走出去】审核通知", "您好，{$model->company}，本次审核不成功；不通过原因为：{$model->checkno_yuanyin}，请联系管理员！");
                    }
                    sleep(1);
                }
            }
        } else {
            echo "该页面不能被访问!!!";
            exit();
        }

    }

    //检查证件号是否唯一
    function chkzhengjianhao()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $model = $this->M_common->query_one("select * from swj_register_dsqy where isdel=0 and code='$title'" . ($id > 0 ? " and userid<>$id" : ""));
        if (count($model) > 0) {
            $data = array("result" => "0", "msg" => "证件号码已存在");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }

    //检查证件号是否唯一
    function chkzhengjianhao_xh()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $id = empty($get["id"]) ? 0 : $get["id"];
        $model = $this->M_common->query_one("select * from swj_register_xiehuiorjigou where isdel=0 and code='$title'" . ($id > 0 ? " and userid<>$id" : ""));
        if (count($model) > 0) {
            $data = array("result" => "0", "msg" => "证件号码已存在");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }


    //查看附件中的证书
    function lookpic()
    {
        $get = $this->input->get();
        if (empty($get["id"])) {
            $this->parent_showmessage(
                0
                , "资料不存在",
                "",
                999999
            );
            exit();
        }
        $id = $get["id"];
        if (!is_numeric($id)) {
            $this->parent_showmessage(
                0
                , "资料不存在",
                "",
                999999
            );
            exit();
        }
        $model = $this->fj->GetModel($id);
        if (isset($model["filesrc"])) {
            $this->data["img"] = "/" . $model["filesrc"];
            $this->load->view(__TEMPLET_FOLDER__ . "/shangwuju/swj_lookpic", $this->data);
        } else {
            $this->parent_showmessage(
                0
                , "资料不存在",
                "",
                999999
            );
            exit();
        }
    }


    function prolist()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        $pid = empty($get["pid"]) ? "-1" : $get["pid"];
        $sel = empty($get["sel"]) ? "" : $get["sel"];


        //$newid = empty($get["id"])?"":$get["id"];//从网址读取ID
        $title = array();

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            //删除选中ID
            if (!empty($get["delid"])) {
                if ($sel != "") {
                    $sel = explode(",", $sel);
                    $sel = array_unique($sel);
                    $sel2 = array();
                    foreach ($sel as $v) {
                        if ($get["delid"] != $v) {
                            $sel2[] = $v;
                        }
                    }
                }
                header("location:" . site_url("swj_admin/prolist") . "?pid=" . (empty($get["pid"]) ? 0 : $get["pid"]) . "&sel=" . (isset($sel2) ? implode(",", $sel2) : ""));
            }


            $this->data["mainlist"] = $this->ccd->GetModelList_orderby(11, 45135, $orderby = 'disorder asc');
            if ($pid == -1) {
                if (count($this->data["mainlist"]) > 0) {
                    $pid = $this->data["mainlist"][0]["id"];
                }
            }
            $this->data["sublist"] = array();
            if ($pid > 0) {
                $this->data["sublist"] = $this->ccd->GetModelList_orderby(11, $pid, $orderby = 'disorder asc');

            }
            if ($sel != "") {
                $this->data["sel"] = explode(",", $sel);
                $this->data["sel"] = array_unique($this->data["sel"]);
            } else {
                $this->data["sel"] = "";
            }
            $this->data["newid"] = $sel;
            $this->data["pid"] = $pid;
            $this->load->view(__TEMPLET_FOLDER__ . "/shangwuju/swj_prolist", $this->data);
        } else {


            $arr = $post["id"];
            $id_ = "";
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    $id_ = implode(",", $arr);
                }
            }
            if ($id_ != "") {
                if ($sel != "") {
                    $sel .= "," . $id_;
                } else {
                    $sel .= $id_;
                }
            }

            if ($sel != "") {
                $sel = explode(",", $sel);
                $sel = array_unique($sel);
                $sel = implode(",", $sel);
                $list = $this->ccd->GetList2(" id in(" . $sel . ")");
                foreach ($list as $v) {
                    $title[] = $v["name"];
                }
            }
            if (count($title) > 0) {
                $title = implode(",", $title);
            } else {
                $title = "";
            }
            echo "<script>";
            echo "parent.$('#product_text').html('');";
            echo "parent.$('#product_text').html('" . $title . "');";
            echo "parent.$('#product').val('" . $sel . "');";
            echo "</script>";
            showmessage("添加成功", site_url("user/prolist") . "?sel=" . $sel, 1, 1, "");
            exit();

        }
    }


    //改为一页显示
    function prolist2()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        $pid = empty($get["pid"]) ? "-1" : $get["pid"];
        $sel = empty($get["sel"]) ? "" : $get["sel"];


        //$newid = empty($get["id"])?"":$get["id"];//从网址读取ID
        $title = array();

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            //删除选中ID
            if (!empty($get["delid"])) {
                if ($sel != "") {
                    $sel = explode(",", $sel);
                    $sel = array_unique($sel);
                    $sel2 = array();
                    foreach ($sel as $v) {
                        if ($get["delid"] != $v) {
                            $sel2[] = $v;
                        }
                    }
                }
                header("location:" . site_url("swj_admin/prolist") . "?pid=" . (empty($get["pid"]) ? 0 : $get["pid"]) . "&sel=" . (isset($sel2) ? implode(",", $sel2) : ""));
            }


            $mainlist = $this->ccd->GetModelList_orderby(11, 45135, $orderby = 'disorder asc');
            foreach ($mainlist as $k => $v) {
                $mainlist[$k]["sublist"] = $this->ccd->GetModelList_orderby(11, $v["id"], $orderby = 'disorder asc');
            }

            if ($sel != "") {
                $this->data["sel"] = explode(",", $sel);
                $this->data["sel"] = array_unique($this->data["sel"]);
            } else {
                $this->data["sel"] = "";
            }
            $this->data["newid"] = $sel;
            $this->data["pid"] = $pid;
            $this->data["mainlist"] = $mainlist;
            $this->load->view(__TEMPLET_FOLDER__ . "/shangwuju/swj_prolist2", $this->data);
        } else {


            $arr = $post["id"];
            $id_ = "";
            $title = "";
            $sel = "";
            $title = "";
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    $id_ = implode(",", $arr);
                }
            }

            if ($id_ != "") {
                $sel = explode(",", $id_);
                $sel = array_unique($sel);
                $sel = implode(",", $sel);
                $list = $this->ccd->GetList2(" id in(" . $sel . ")");
                foreach ($list as $v) {
                    $title[] = $v["name"];
                }
            }
            if (count($title) > 0 && $title != "") {
                $title = implode(",", $title);
            } else {
                $title = "";
            }
            echo "<script>";
            echo "parent.$('#product_text').html('');";
            echo "parent.$('#product_text').html('" . $title . "');";
            echo "parent.$('#product').val('" . $sel . "');";
            echo "</script>";

            showmessage("添加成功",
                site_url("user/prolist2") . "?sel=" . $sel,
                1, 1, "");
            exit();

        }
    }


    function set_check_qy_edit()
    {


        $get = $this->input->get();
        $idlist = $get["idlist"];
        $check = $get["check"];
        $content = empty($get["content"]) ? "" : $get["content"];

        $arr = explode(",", $idlist);
        $ischeck_0 = 0;//记录状态为未完善资料的用户数
        foreach ($arr as $v) {
            $qy_model = $this->qyuser_tmp->GetModel($v);
            if (count($qy_model) > 0) {
                $usermodel = $this->user->GetModel($qy_model["userid"]);
                $qy_model["system_username"] = login_name();
                $qy_model["system_userid"] = admin_id();
                $qy_model["check_content"] = $content;
                $qy_model["check_status"] = $check;
                $qy_model["check_time"] = time();
                //同时如果是通过，同时更新用户表
                $model = $this->qyuser->GetModelFromUserID($qy_model["userid"]);
                $qy_model["content_old"] = json_encode($model);
                if ($check == 2) {
                    $json = $qy_model["content"];
                    $json = json_decode($json, true);
                    foreach ($json as $k => $v) {
                        $model[$k] = $v;
                    }
                    //处理主营产品一级分类
                    if ($model["product2"] != "") {
                        $model["product"] = $this->ccd->GetParentFromSub($model["product2"]);
                    }
                    $this->qyuser->update($model);
                }

                $this->qyuser_tmp->update($qy_model);

                //发送短信
                if ($check == 2) {
                    //成功
                    $this->common_sms->sendSmsToUser(
                        "企业资料修改审核",
                        $qy_model["userid"],
                        admin_id(),
                        "您好," . $usermodel["realname"] . ",您的企业资料修改通过审核，已生效",
                        "");
                }
                if ($check == 3) {
                    //不通过
                    $this->common_sms->sendSmsToUser(
                        "企业资料修改审核",
                        $qy_model["userid"],
                        admin_id(),
                        "您好," . $usermodel["realname"] . ",您的企业资料修改不通过，登录系统查看原因",
                        "");
                }


            }
        }
        echo "0";
        exit();


    }

    function set_check_xh_edit()
    {

        $get = $this->input->get();
        $idlist = $get["idlist"];
        $check = $get["check"];
        $content = empty($get["content"]) ? "" : $get["content"];

        $arr = explode(",", $idlist);
        $ischeck_0 = 0;//记录状态为未完善资料的用户数
        foreach ($arr as $v) {
            $qy_model = $this->xhuser_tmp->GetModel($v);
            if (count($qy_model) > 0) {
                $usermodel = $this->user->GetModel($qy_model["userid"]);
                $qy_model["system_username"] = login_name();
                $qy_model["system_userid"] = admin_id();
                $qy_model["check_content"] = $content;
                $qy_model["check_status"] = $check;
                $qy_model["check_time"] = time();
                //同时如果是通过，同时更新用户表
                $model = $this->xhuser->GetModelFromUserID($qy_model["userid"]);
                $qy_model["content_old"] = json_encode($model);
                if ($check == 2) {
                    $json = $qy_model["content"];
                    $json = json_decode($json, true);
                    foreach ($json as $k => $v) {
                        $model[$k] = $v;
                    }
                    $this->xhuser->update($model);
                }

                $this->xhuser_tmp->update($qy_model);


                //发送短信
                if ($check == 2) {
                    //成功
                    $this->common_sms->sendSmsToUser(
                        "协会或机构资料修改审核",
                        $qy_model["userid"],
                        admin_id(),
                        "您好," . $usermodel["realname"] . ",您的企业资料修改通过审核，已生效",
                        "");
                }
                if ($check == 3) {
                    //不通过
                    $this->common_sms->sendSmsToUser(
                        "协会或机构资料修改审核",
                        $qy_model["userid"],
                        admin_id(),
                        "您好," . $usermodel["realname"] . ",您的企业资料修改不通过，登录系统查看原因",
                        "");
                }


            }
        }
        echo "0";
        exit();


    }

    //审核企业会员资料修改
    function reg_edit_check_qy()
    {

        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $get = $this->input->get();
        $selcheck = empty($get["selcheck"]) ? "" : $get["selcheck"];
        $username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username", true)))));
        if (isset($get["status"])) {
            $status = daddslashes(html_escape(strip_tags(trim($this->input->get_post("status", true)))));
        } else {
            $status = "-1";
        }
        $search = array();

        $search_val = array(
            "username" => "",
            "status" => $status,
            "selcheck" => $selcheck

        );

        if (!empty($username)) {
            $search_val["username"] = $username;
            $search["username"] = $username;
        }


        if ($selcheck != "") {
            if ($selcheck == "-1") {
                $search['selcheck'] = "0";
            } else {
                $search['selcheck'] = $selcheck;
            }
        }
        //print_r($search_val);
        //die();
        $data = array();

        $orderby["id"] = "desc";
        $data = $this->qyuser_tmp->GetInfoList($pageindex, 30, $search, $orderby);

        $data["isadd"] = $this->permition_for("user", "add");
        $data["isdel"] = $this->permition_for("user", "del");
        $data["search_val"] = $search_val;
        $data["ls"] = empty($get["ls"]) ? site_url("user/index") : $get["ls"];


        foreach ($data["list"] as $k => $v) {
            $data["list"][$k]['status'] = ($v['status'] == 1) ? "" : '<font color="red">(冻结)</font>';
        }


        $this->load->view(__TEMPLET_FOLDER__ . "/shangwuju/reg_edit_check_qy", $data);
    }

    //审核协会会员资料修改
    function reg_edit_check_xh()
    {

        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $get = $this->input->get();
        $selcheck = empty($get["selcheck"]) ? "" : $get["selcheck"];
        $username = daddslashes(html_escape(strip_tags(trim($this->input->get_post("username", true)))));
        if (isset($get["status"])) {
            $status = daddslashes(html_escape(strip_tags(trim($this->input->get_post("status", true)))));
        } else {
            $status = "-1";
        }
        $search = array();

        $search_val = array(
            "username" => "",
            "status" => $status,
            "selcheck" => $selcheck

        );

        if (!empty($username)) {
            $search_val["username"] = $username;
            $search["username"] = $username;
        }


        if ($selcheck != "") {
            if ($selcheck == "-1") {
                $search['selcheck'] = "0";
            } else {
                $search['selcheck'] = $selcheck;
            }
        }
        //print_r($search_val);
        //die();
        $data = array();

        $orderby["id"] = "desc";
        $data = $this->xhuser_tmp->GetInfoList($pageindex, 30, $search, $orderby);
        $data["isadd"] = $this->permition_for("user", "add");
        $data["isdel"] = $this->permition_for("user", "del");
        $data["search_val"] = $search_val;
        $data["ls"] = empty($get["ls"]) ? site_url("user/index") : $get["ls"];


        foreach ($data["list"] as $k => $v) {
            $data["list"][$k]['status'] = ($v['status'] == 1) ? "" : '<font color="red">(冻结)</font>';
        }
        $this->load->view(__TEMPLET_FOLDER__ . "/shangwuju/reg_edit_check_xh", $data);
    }


    function set_check_qy_del()
    {

        $get = $this->input->get();
        $idlist = $get["idlist"];
        $isdel = false;
        if (!is_super_admin()) {
            if ($this->permition_for('user', 'del')) {
                $isdel = true;
            }
        } else {
            $isdel = true;
        }

        if ($idlist != "" && $isdel) {

            $arr = explode(",", $idlist);
            foreach ($arr as $v) {
                $this->qyuser_tmp->del($v);
            }
        }


    }

    function set_check_xh_del()
    {
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $isdel = false;
        if (!is_super_admin()) {
            if ($this->permition_for('user', 'del')) {
                $isdel = true;
            }
        } else {
            $isdel = true;
        }

        if ($idlist != "" && $isdel) {

            $arr = explode(",", $idlist);
            foreach ($arr as $v) {
                $this->xhuser_tmp->del($v);
            }
        }
    }


    function reg_edit_check_xh_details()
    {
        $get = $this->input->get();
        $id = $get["id"];
        if ($id > 0) {
            $editmodel = $this->xhuser_tmp->GetModel($id);
            if ($editmodel["content_old"] == "") {
                $oldmodel = $this->xhuser->GetModelFromUserID($editmodel["userid"]);
            } else {
                $oldmodel = json_decode($editmodel["content_old"], true);
            }
            $newmodel = json_decode($editmodel["content"], true);
            //对比一下，将相同值删除
            foreach ($newmodel as $k => $v) {
                if ($oldmodel[$k] == $newmodel[$k]) {
                    unset($newmodel[$k]);
                }
            }
            //需要读通用表的字段
            $getinfo = array();
            $getfujian = array(
                "social_organization_registration_certificate_id",
                "beian_certificate_id"
            );
            $field = $this->M_common->get_fields_all("swj_register_xiehuiorjigou");
            foreach ($oldmodel as $k => $v) {
                if (in_array($k, $getinfo)) {
                    $tmp = $this->ccd->getlist("id in(" . $v . ")");
                    for ($i = 0; $i < count($tmp); $i++) {
                        if ($i == 0) {
                            $oldmodel[$k] = $tmp[$i]["name"];
                        } else {
                            $oldmodel[$k] .= "<br/>" . $tmp[$i]["name"];
                        }
                    }
                }

                if (in_array($k, $getfujian)) {
                    if ($v > 0) {
                        $fjmodel = $this->fj->GetModel($v);
                        if (count($fjmodel) > 0) {
                            if ($fjmodel["filesrc"] != "") {
                                $oldmodel[$k] = "<a href='/" . $fjmodel["filesrc"] . "' target='_blank'>查看</a>";
                            } else {
                                $oldmodel[$k] = "-";
                            }
                        } else {
                            $oldmodel[$k] = "-";
                        }
                    }
                }
            }
            foreach ($newmodel as $k => $v) {
                if (in_array($k, $getinfo)) {
                    $tmp = $this->ccd->getlist("id in(" . $v . ")");
                    for ($i = 0; $i < count($tmp); $i++) {
                        if ($i == 0) {
                            $newmodel[$k] = $tmp[$i]["name"];
                        } else {
                            $newmodel[$k] .= "<br/>" . $tmp[$i]["name"];
                        }
                    }
                }

                if (in_array($k, $getfujian)) {
                    if ($v > 0) {
                        $fjmodel = $this->fj->GetModel($v);
                        if (count($fjmodel) > 0) {
                            if ($fjmodel["filesrc"] != "") {
                                $newmodel[$k] = "<a href='/" . $fjmodel["filesrc"] . "' target='_blank'>查看</a>";
                            } else {
                                $newmodel[$k] = "-";
                            }

                        } else {
                            $newmodel[$k] = "-";
                        }
                    }
                }

            }
            $data["editmodel"] = $editmodel;
            $data["usermodel"] = $this->user->GetModel($editmodel["userid"]);
            $data["newmodel"] = $newmodel;
            $data["oldmodel"] = $oldmodel;
            $data["field"] = $field;
            $data["ls"] = empty($get["ls"]) ? site_url("user/reg_edit_check_xh") : $get["ls"];
            $this->load->view(__TEMPLET_FOLDER__ . "/shangwuju/reg_edit_check_xh_details", $data);
        } else {
            showmessage("没有资料", site_url("user/index"), 2000, 0, "");
            exit();
        }
    }


    function reg_edit_check_qy_details()
    {
        $get = $this->input->get();
        $id = $get["id"];
        if ($id > 0) {
            $editmodel = $this->qyuser_tmp->GetModel($id);
            if ($editmodel["content_old"] == "") {
                $oldmodel = $this->qyuser->GetModelFromUserID($editmodel["userid"]);
            } else {
                $oldmodel = json_decode($editmodel["content_old"], true);
            }
            //print_r($oldmodel);
            $newmodel = json_decode($editmodel["content"], true);
            //print_r($newmodel);
            //对比一下，将相同值删除
            foreach ($newmodel as $k => $v) {
                if ($oldmodel[$k] == $newmodel[$k]) {
                    unset($newmodel[$k]);
                }
            }
            //需要读通用表的字段
            $getinfo = array(
                "company_type",
                "business_model",
                "product2",
                "town_id"
            );
            $getfujian = array(
                "business_licence_id",
                "organization_code_id",
                "shuiwu_register_code_id",
                "three_code_add_id"
            );
            $field = $this->M_common->get_fields_all("swj_register_dsqy");
            foreach ($oldmodel as $k => $v) {
                if (in_array($k, $getinfo)) {
                    $tmp = $this->ccd->getlist("id in(" . $v . ")");
                    for ($i = 0; $i < count($tmp); $i++) {
                        if ($i == 0) {
                            $oldmodel[$k] = $tmp[$i]["name"];
                        } else {
                            $oldmodel[$k] .= "<br/>" . $tmp[$i]["name"];
                        }
                    }
                }

                if (in_array($k, $getfujian)) {
                    if ($v > 0) {
                        $fjmodel = $this->fj->GetModel($v);
                        if (count($fjmodel) > 0) {
                            if ($fjmodel["filesrc"] != "") {
                                $oldmodel[$k] = "<a href='/" . $fjmodel["filesrc"] . "' target='_blank'>查看</a>";
                            } else {
                                $oldmodel[$k] = "-";
                            }
                        } else {
                            $oldmodel[$k] = "-";
                        }
                    }
                }
            }
            foreach ($newmodel as $k => $v) {
                if (in_array($k, $getinfo)) {
                    $tmp = $this->ccd->getlist("id in(" . $v . ")");
                    for ($i = 0; $i < count($tmp); $i++) {
                        if ($i == 0) {
                            $newmodel[$k] = $tmp[$i]["name"];
                        } else {
                            $newmodel[$k] .= "<br/>" . $tmp[$i]["name"];
                        }
                    }
                }

                if (in_array($k, $getfujian)) {
                    if ($v > 0) {
                        $fjmodel = $this->fj->GetModel($v);
                        if (count($fjmodel) > 0) {
                            if ($fjmodel["filesrc"] != "") {
                                $newmodel[$k] = "<a href='/" . $fjmodel["filesrc"] . "' target='_blank'>查看</a>";
                            } else {
                                $newmodel[$k] = "-";
                            }

                        } else {
                            $newmodel[$k] = "-";
                        }
                    }
                }

            }
            $data["editmodel"] = $editmodel;
            $data["usermodel"] = $this->user->GetModel($editmodel["userid"]);
            $data["newmodel"] = $newmodel;
            $data["oldmodel"] = $oldmodel;
            $data["field"] = $field;
            $data["ls"] = empty($get["ls"]) ? site_url("user/reg_edit_check_qy") : $get["ls"];
            $this->load->view(__TEMPLET_FOLDER__ . "/shangwuju/reg_edit_check_qy_details", $data);
        } else {
            showmessage("没有资料", site_url("user/index"), 2000, 0, "");
            exit();
        }
    }

    function jichu_dianshang()
    {
        //跳转
        $url = site_url("user/index") . "?isjichu=1&selcheck=10&usertype=45063&status=-1";
        header("location:" . $url);
    }

    function jichu_xiehui()
    {
        //跳转
        $url = site_url("user/index") . "?isjichu=1&selcheck=10&usertype=45064&status=-1";
        header("location:" . $url);
    }

    //导入企业excel
    function import_qy()
    {
        $action = $this->input->get_post("action");
        $action_array = array("import_qy", "doimport_qy");
        $action = !in_array($action, $action_array) ? 'import_qy' : $action;
        if ($action == 'import_qy') {//导入excel模板页面
            $this->load->view(__TEMPLET_FOLDER__ . "/views_user_import_qy");
        } elseif ($action == 'doimport_qy') {
            $this->doimport_qy();
        }
    }

    //处理导入企业excel
    function doimport_qy()
    {
        @ini_set('memory_limit', '1024M');//文件可能比较大
        //上传excel
        $fileTypes = array('xls', 'xlsx');//允许上传格式
        $this->load->library("common_upload");
        if ($_FILES["excel"]["type"] == "application/vnd.ms-excel") {
            $inputFileType = 'excel5';
        } elseif ($_FILES["excel"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $inputFileType = 'excel2007';
        } else {
            showmessage("导入的excel类型错误，请检查您上传的excel是否为从系统中下载的模板", "user/import_qy", 3, 0);
            exit;
        }
        $fujian_path = $this->common_upload->upload_path_ym($this->upload_path, 'excel', implode("|", $fileTypes));
        if (!$fujian_path) {
            showmessage("导入的excel类型错误，请检查您上传的excel是否为从系统中下载的模板", "user/import_qy", 3, 0);
            exit;
        }
        // echo $fujian_path;exit;
        //读取excel数据
        $objPHPExcel = $this->msie->getObjPHPExcel($inputFileType, $fujian_path);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//取得总行数
        $highestColumn = $sheet->getHighestColumn();//取得总列数
        if ($highestColumn < 'Y') {//上传的excel列数不对应
            @unlink($fujian_path);
            showmessage("导入失败，请严格按照导入的模板填写数据", "user/import_qy", 3, 0);
            exit;
        }
        $countFailed = 0;//统计导入失败条数
        for ($j = 3; $j <= $highestRow; $j++) {//从第三行开始读取数据
            $model = array();//获取数据
            for ($k = 'A'; $k <= $highestColumn; $k++) {//从A列读取数据
                $str = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();//读取单元格
                $model[] = mb_convert_encoding($str, 'UTF-8');//转换成utf8
            }
            $result = $this->doModelQy($model);//对导入的数据进行处理(添加到数据库中)
            if (!$result) {//添加失败，失败条数+1
                $countFailed++;
            }
            // var_dump($model);exit;
        }
        @unlink($fujian_path);
        $counttotal = $highestRow - 2;//导入总数
        $countSucc = $counttotal - $countFailed;//成功条数
        $message = "导入总数为：" . $counttotal . "条，" . $countSucc . '条成功、' . $countFailed . '条失败。';
        showmessage($message, "user/jichu_dianshang", 3, 1);
    }

    //$model为导入excel的数组,对model数据进行插入到数据库中并且返回result结果，成功or失败
    private function doModelQy($model)
    {
        $nowTime = time();
        $nowDate = date('Y-m-d H:i:s', $nowTime);
        //先插入到57sy_common_user基础信息表中
        $modelBase = array(
            'username' => $model[0],
            'passwd' => md5($model[1]),
            'realname' => $model[2],
            'tel' => $model[3],
            'qq' => $model[4],
            'email' => $model[0],
            'regdate' => $nowTime,
            'status' => '1',
            'usertype' => '45063',
            'checkstatus' => '1',
            'isdel' => '0'
        );
        $modelRst = $this->M_common->insert_one('57sy_common_user', $modelBase);
        if ($modelRst['affect_num'] <= 0) {//插入失败
            return false;
        }
        $userid = $modelRst['insert_id'];//用户id
        $town = $model[13];//镇区名称
        $townid = 0;//镇区id
        $company_type = $model[6];//电商企业类型
        $ctype = '';//电商企业类型（插入到数据库中）
        $business_model = $model[8];//电商交易模式
        $bmodel = '';//电商交易模式(插入到数据库中)
        $product = $model[10];//主营产品名称（一级）
        $pd_str = '';//主营产品名称（一级）插入到数据库中
        $pd2_str = '';//主营产品名称（二级）插入到数据库中
        $upload_paper_type = $model[11] == '是' ? '2' : '1';//上传证件类型，是三证合一为2
        //插入到企业用户附表中swj_register_dsqy
        //获取镇区id，typeid=6,pid=3145
        if ($town) {//镇区id在57sy_common_category_data通用表中
            $sql = "select id from 57sy_common_category_data where typeid='6' and pid='3145' and name='$town'";
            $data = $this->M_common->query_one($sql);
            if (count($data) > 0) {
                $townid = $data['id'];//镇区id
            }
        }
        //存在电商企业类型，将其名称打散为数组，遍历数组找到其id
        if ($company_type) {
            $cpn_type_arr = explode(',', $company_type);
            foreach ($cpn_type_arr as $key => $value) {
                $sql = "select id from 57sy_common_category_data where typeid='11' and pid='45123' and name like '%$value%'";
                $data = $this->M_common->query_one($sql);
                if (count($data) > 0) {
                    if ($key == 0) {//如果是第一次进入则直接赋值
                        $ctype = $data['id'];
                    } else {
                        $ctype .= ',' . $data['id'];
                    }
                }
            }
        }
        //存在交易模式，将其名称打散为数组，遍历数组找到其id
        if ($business_model) {
            $bns_model_arr = explode(',', $business_model);
            foreach ($bns_model_arr as $key => $value) {
                $sql = "select id from 57sy_common_category_data where typeid='11' and pid='45129' and name like '%$value%'";
                $data = $this->M_common->query_one($sql);
                if (count($data) > 0) {
                    if ($key == 0) {//如果是第一次进入则直接赋值
                        $bmodel = $data['id'];
                    } else {
                        $bmodel .= ',' . $data['id'];
                    }
                }
            }
        }
        //存在主营产品,搜索数据库中匹配的数据
        if ($product) {//typeid:11,一级产品的上级pid为45135
            $product_arr = explode(',', $product);
            $flag = 0;//判断是否第一次进入if语句
            foreach ($product_arr as $key => $value) {
                if (empty($value)) {//为空时继续循环
                    continue;
                }
                $sql = "select id,pid from 57sy_common_category_data where typeid='11' and name like '%$value%'";
                $data = $this->M_common->query_one($sql);
                if (count($data) > 0) {
                    $pid = $data['pid'];//上级id
                    //查询是否存在该一级主营产品
                    $sql = "select id from 57sy_common_category_data where id='$pid' and pid='45135'";
                    $data_check = $this->M_common->query_one($sql);
                    if (count($data_check) <= 0) {//不存在,继续下一个循环
                        continue;
                    }
                    if ($flag == 0) {//如果是第一次进入则直接赋值
                        $flag = 1;
                        $pd_str = $data['id'];
                        $pd2_str = $pid;
                    } else {
                        $pd_str .= ',' . $data['id'];
                        $pd2_str .= ',' . $pid;
                    }
                }
            }
        }
        // echo $i.'<br/>';
        // echo $pd_str.'<br/>'.$pd2_str;exit;
        // $this->data["company_type"] = $this->getccd(11,45123);
        // $this->data["jiaoyi"] = $this->getccd(11,45129);
        $modelAdd = array(
            'name' => $model[5],
            'company_type' => $ctype,
            'company_type_other' => $model[7],
            'business_model' => $bmodel,
            'business_model_other' => $model[9],
            'product' => $pd2_str,
            'product2' => $pd_str,
            'upload_paper_type' => $upload_paper_type,
            'code' => $model[12],
            'town_id' => $townid,
            'register_money' => $model[14],
            'open_account_bank' => $model[15],
            'public_bank_account' => $model[16],
            'company_number' => $model[17],
            'electronic_number' => $model[18],
            'register_address' => $model[19],
            'guding_phone' => $model[20],
            'mobilephone' => $model[21],
            'faxphone' => $model[22],
            'email' => $model[23],
            'company_summary' => $model[24],
            'isdel' => '0',
            'audit' => '10',
            'auditer' => 'admin',
            'audittime' => $nowDate,
            'audit_userid' => '16',
            'inputtime' => $nowDate,
            'updatetime' => $nowDate,
            'lastupdatetime' => $nowDate,
            'userid' => $userid
        );
        $modelRst = $this->M_common->insert_one('swj_register_dsqy', $modelAdd);
        if ($modelRst['affect_num'] <= 0) {//插入失败
            $this->user->del($userid);//删除主表内容
            return false;
        } else {
            return true;
        }
    }

    //导入协会excel
    function import_xh()
    {
        $action = $this->input->get_post("action");
        $action_array = array("import_xh", "doimport_xh");
        $action = !in_array($action, $action_array) ? 'import_xh' : $action;
        if ($action == 'import_xh') {//导入excel模板页面
            $this->load->view(__TEMPLET_FOLDER__ . "/views_user_import_xh");
        } elseif ($action == 'doimport_xh') {
            $this->doimport_xh();
        }
    }

    //处理导入协会excel
    function doimport_xh()
    {
        @ini_set('memory_limit', '1024M');//文件可能比较大
        //上传excel
        $fileTypes = array('xls', 'xlsx');//允许上传格式
        $this->load->library("common_upload");
        if ($_FILES["excel"]["type"] == "application/vnd.ms-excel") {
            $inputFileType = 'excel5';
        } elseif ($_FILES["excel"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $inputFileType = 'excel2007';
        } else {
            showmessage("导入的excel类型错误，请检查您上传的excel是否为从系统中下载的模板", "user/import_xh", 3, 0);
            exit;
        }
        $fujian_path = $this->common_upload->upload_path_ym($this->upload_path, 'excel', implode("|", $fileTypes));
        if (!$fujian_path) {
            showmessage("导入的excel类型错误，请检查您上传的excel是否为从系统中下载的模板", "user/import_xh", 3, 0);
            exit;
        }
        // echo $fujian_path;exit;
        //读取excel数据
        $objPHPExcel = $this->msie->getObjPHPExcel($inputFileType, $fujian_path);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//取得总行数
        $highestColumn = $sheet->getHighestColumn();//取得总列数
        if ($highestColumn < 'P') {//上传的excel列数不对应
            @unlink($fujian_path);
            showmessage("导入失败，请严格按照导入的模板填写数据", "user/import_xh", 3, 0);
            exit;
        }
        $countFailed = 0;//统计导入失败条数
        for ($j = 2; $j <= $highestRow; $j++) {//从第二行开始读取数据
            $model = array();//获取数据
            for ($k = 'A'; $k <= $highestColumn; $k++) {//从A列读取数据
                $str = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();//读取单元格
                $model[] = mb_convert_encoding($str, 'UTF-8');//转换成utf8
            }
            $result = $this->doModelXh($model);//对导入的数据进行处理(添加到数据库中)
            if (!$result) {//添加失败，失败条数+1
                $countFailed++;
            }
            // var_dump($model);exit;
        }
        @unlink($fujian_path);
        $counttotal = $highestRow - 1;//导入总数
        $countSucc = $counttotal - $countFailed;//成功条数
        $message = "导入总数为：" . $counttotal . "条，" . $countSucc . '条成功、' . $countFailed . '条失败。';
        showmessage($message, "user/jichu_xiehui", 3, 1);
    }

    //$model为导入excel的数组,对model数据进行插入到数据库中并且返回result结果，成功or失败
    private function doModelXh($model)
    {
        $nowTime = time();
        $nowDate = date('Y-m-d H:i:s', $nowTime);
        //先插入到57sy_common_user基础信息表中
        $modelBase = array(
            'username' => $model[0],
            'passwd' => md5($model[1]),
            'realname' => $model[2],
            'tel' => $model[3],
            'qq' => $model[4],
            'email' => $model[0],
            'regdate' => $nowTime,
            'status' => '1',
            'usertype' => '45064',
            'checkstatus' => '1',
            'isdel' => '0'
        );
        $modelRst = $this->M_common->insert_one('57sy_common_user', $modelBase);
        if ($modelRst['affect_num'] <= 0) {//插入失败
            return false;
        }
        $userid = $modelRst['insert_id'];//用户id
        //插入到协会附表中
        $modelAdd = array(
            'name' => $model[5],
            'code' => $model[6],
            'open_account_bank' => $model[7],
            'public_bank_account' => $model[8],
            'socialorinstitutionnumber' => $model[9],
            'register_address' => $model[10],
            'guding_phone' => $model[11],
            'mobilephone' => $model[12],
            'faxphone' => $model[13],
            'email' => $model[14],
            'socialorinstitution_summary' => $model[15],
            'isdel' => '0',
            'audit' => '10',
            'auditer' => 'admin',
            'audittime' => $nowDate,
            'audit_userid' => '16',
            'inputtime' => $nowDate,
            'updatetime' => $nowDate,
            'lastupdatetime' => $nowDate,
            'userid' => $userid
        );
        $modelRst = $this->M_common->insert_one('swj_register_xiehuiorjigou', $modelAdd);
        if ($modelRst['affect_num'] <= 0) {//插入失败
            $this->user->del($userid);//删除主表内容
            return false;
        } else {
            return true;
        }
    }
}
