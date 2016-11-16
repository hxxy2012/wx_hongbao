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
        $this->load->model("M_swj_reg_tel_code", "tel_code");
        $this->load->model('m_common_sms', 'sms');//发送短信
        $this->load->model('M_swj_info_pub', 'msip');//信息通知
        $this->load->model('M_swj_info_hdba', 'hdba');//活动备案

        $this->load->model('M_website_category', 'webcate');//栏目模型
        $this->load->model('M_website_common_info', 'mwci');//文章等信息模型
        $this->load->model('M_website_category', 'mwc');//栏目模型
        $this->load->model('M_common');//栏目模型 
        $this->load->model('M_common_category_data', 'ccd');//目录信息

        $this->load->model('M_zcq_huodong', 'hudong');//活动模型
        $this->load->model('M_user', 'user');//用户模型
        $this->load->model('M_zcq_hdbaoming', 'hdbaoming');//用户模型

        $this->data["sess"] = $this->parent_getsession();//登陆者信息
        $this->upload_path = __ROOT__ . "/data/upload/user/";
        //底部政府网站部门以及友情链接等    
        $this->data["finfo"] = $this->parent_getfinfo();
        $this->data['left_menu'] = $this->parent_getlrmenu();//左边菜单信息(固定最新动态)
        $this->data["ls"] = get_url();//获取当前连接
    }

    /**
     * 首页
     */
    function index()
    {
        //会员可见控制
        if ($this->data['sess']) {
            //登录了
            $search = array('jibie' => 1);//游客和会员专有文章可见
            $search2 = array('jibie' => 1);//游客和会员专有文章可见
        } else {
            $search = array('jibie' => 0);//游客可见
            $search2 = array('jibie' => 0);//游客可见
        }

        //滚动图 读 滚动信息 下边信息
        $this->data["adv"] = $this->ad->getlist("ad_type='2' and status='1'", "id asc", 4);//首页banner下滚动图
        //工作状态
        $search['category_id'] = 48;
        $gzzt = $this->mwci->GetInfoList2('1', 'content', 0, 4, $search, array('istop' => 'desc', 'id' => 'desc'));
        $this->data["gzzt"] = $gzzt['list'];
        //通知公告
        $search['category_id'] = 49;
        $tzgg = $this->mwci->GetInfoList2('1', 'content', 0, 5, $search, array('istop' => 'desc', 'id' => 'desc'));
        $this->data["tzgg"] = $tzgg['list'];
        //政策法规
        $search['category_id'] = 39;
        $zcfg = $this->mwci->GetInfoList2('1', 'content', 0, 5, $search, array('istop' => 'desc', 'id' => 'desc'));
        $this->data["zcfg"] = $zcfg['list'];
        //办事指南
        $search['category_id'] = 40;
        $bszn = $this->mwci->GetInfoList2('1', 'content', 0, 4, $search, array('istop' => 'desc', 'id' => 'desc'));
        $this->data["bszn"] = $bszn['list'];
        //风险防范
        $search2['t1.category_id_in'] = '53,54,55';
        $fxff = $this->mwci->GetInfoList2('1', 'content', 0, 8, $search2, array('istop' => 'desc', 'id' => 'desc'));
        $this->data["fxff"] = $fxff['list'];
        //服务机构下级栏目
        $this->data["fwjg_next_cat"] = $this->mwc->GetSubList(45);
        foreach ($this->data["fwjg_next_cat"] as $key => $value) {//循环栏目获取其内容
            $temp = $this->mwci->GetInfoList2('1', 'content', 0, 8, array('category_id' => $value['id'], 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));
            $this->data['fwjg_next_cat_arr'][$key] = $temp['list'];
        }
        // var_dump($this->data["zfwz"]);exit;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/index", $this->data);
    }

    //根据栏目id，区分使用到列表页还是单页还是专区模板
    function transfer()
    {

        $pid = (int)$this->input->get_post("pid");//栏目父级id，0代表一级栏目
        $cid = (int)$this->input->get_post("cid");//栏目id
        $backurl = $this->input->get_post('backurl');//返回的url
        $backurl = $backurl ? $backurl : site_url("home/index");
        $model = $this->webcate->GetModel($cid);//栏目信息
        if (!isset($model) || count($model) <= 0) {//不存在的栏目id
            $this->parent_showmessage(
                0
                , "没有找到该栏目",
                $backurl,
                3,
                'showmessage_logout');
        }
        $this->data['cMenuInfo'] = array();//存储列表页的左边子菜单
        $this->data['cid'] = $cid;//选中的栏目,显示该栏目的文章列表信息

        //判断显示哪个栏目的文章列表
        //如果是一级栏目，判断其下面是否有二级栏目，
        //如果有则显示第一个二级栏目的列表以及找到该栏目父级菜单的所有二级菜单,否则显示该栏目的列表
        if ($pid == 0) {
            $this->data['pcateInfo'] = $model;//左边父级菜单信息
            $cmodel = $this->webcate->GetSubList($cid);
            if (count($cmodel) > 0) {//有二级菜单
                $this->data['cMenuInfo'] = $cmodel;
                $this->data['cid'] = $cmodel[0]['id'];
            }
        } else {//不是一级栏目的直接显示该栏目的列表以及找到该栏目父级菜单的所有二级菜单
            $this->data['pcateInfo'] = $this->webcate->GetModel($pid);//左边父级菜单信息
            $this->data['cMenuInfo'] = $this->webcate->GetSubList($pid);//左边子菜单信息
        }
        //通过栏目id获取文章信息
        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        if ($this->data['cid'] != $cid) {//不是显示传送过来的栏目id内容，则重新获取栏目信息
            $this->data['model'] = $this->webcate->GetModel($this->data['cid']);//栏目信息
        } else {
            $this->data['model'] = $model;//栏目信息
        }
        $pagesize = 7;//一页显示多少个
        $orderby["id"] = "desc";//倒序

        $search = array('category_id' => $this->data['cid'], 'jibie' => 0);//公开显示的
        if ($this->data['sess']) {
            //登录会员设置为1，可看到游客的和会员的
            $search['jibie'] = 1;
        }

        $this->data['list'] = $this->mwci->GetInfoList2('1', 'content', $pageindex, $pagesize, $search, array('istop' => 'desc', 'id' => 'desc'));//获取分页信息
        $list_no_pic_arr = array(38, 39, 40, 41, 42, 43, 44, 48, 49, 50, 51, 52, 53, 54,
            55, 56, 57, 58, 59);//文字列表的栏目
        $list_pic_arr = array(45, 60, 61, 62, 63, 64, 65);//图片列表栏目
        $list_nrlyzq_arr = array(46, 66, 67, 68, 69);//尼日利亚专区
        $list_nfzq_arr = array(47, 70, 71, 72, 73);//南非专区
        //根据栏目id显示不同的模板
        if (in_array($this->data['cid'], $list_no_pic_arr)) {//文字列表
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/list_txt", $this->data);
        } else if (in_array($this->data['cid'], $list_pic_arr)) {//图片列表
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/list_img", $this->data);
        } else if (in_array($this->data['cid'], $list_nfzq_arr)) {//南非专区
            if ($pid == 0 || $pid == 74) {//显示南非专区首页
                $this->data['tzhj'] = $this->mwci->GetInfoList2('1', 'content', 0, 1, array('category_id' => 70, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//投资环境
                $this->data['zsxm'] = $this->mwci->GetInfoList2('1', 'content', 0, 4, array('category_id' => 71, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//招商项目
                $this->data['yhzc'] = $this->mwci->GetInfoList2('1', 'content', 0, 3, array('category_id' => 72, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//优惠政策
                $this->data['bszn'] = $this->mwci->GetInfoList2('1', 'content', 0, 3, array('category_id' => 73, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//办事指南
                $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/nfzq_index", $this->data);
            } else {//显示南非专区列表页
                if ($this->data['cid'] == 70) {//投资环境为单页查找该栏目下的一篇文章进行跳转到内容页中
                    $firInf = $this->mwci->getlist_orderby("category_id='{$this->data['cid']}'", "istop desc", 1);
                    $id = @(count($firInf) > 0) ? $firInf[0]['id'] : 0;
                    header('Location:' . site_url('home/content') . "?pid=$pid&cid=70&id=$id");
                    exit;
                }
                $cate_arr = array(70 => 'investment environment', 71 => 'investment projects'
                , 72 => 'preferential policy', 73 => 'service guide');
                $this->data['model']['title_en'] = $cate_arr[$this->data['cid']];//显示英文栏目
                $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/nfzq_list", $this->data);
            }
        } else if (in_array($this->data['cid'], $list_nrlyzq_arr)) {//尼日利亚专区
            if ($pid == 0 || $pid == 74) {//显示尼日利亚专区首页
                $this->data['tzhj'] = $this->mwci->GetInfoList2('1', 'content', 0, 1, array('category_id' => 66, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//投资环境
                $this->data['zsxm'] = $this->mwci->GetInfoList2('1', 'content', 0, 4, array('category_id' => 67, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//招商项目
                $this->data['yhzc'] = $this->mwci->GetInfoList2('1', 'content', 0, 3, array('category_id' => 68, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//优惠政策
                $this->data['bszn'] = $this->mwci->GetInfoList2('1', 'content', 0, 3, array('category_id' => 69, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));//办事指南
                $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/nrlyzq_index", $this->data);
            } else {//显示尼日利亚专区列表页
                if ($this->data['cid'] == 66) {//投资环境为单页查找该栏目下的一篇文章进行跳转到内容页中
                    $firInf = $this->mwci->getlist_orderby("category_id='{$this->data['cid']}'", "istop desc", 1);
                    $id = @(count($firInf) > 0) ? $firInf[0]['id'] : 0;
                    header('Location:' . site_url('home/content') . "?pid=$pid&cid=66&id=$id");
                    exit;
                }
                $cate_arr = array(66 => 'investment environment', 67 => 'investment projects'
                , 68 => 'preferential policy', 69 => 'service guide');
                $this->data['model']['title_en'] = $cate_arr[$this->data['cid']];//显示英文栏目
                $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/nrlyzq_list", $this->data);
            }
        } else {
            $this->parent_showmessage(
                0
                , "没有找到该栏目",
                $backurl,
                3,
                'showmessage_logout');
        }
    }

    //详细内容页
    function content()
    {
        $pid = (int)$this->input->get_post("pid");//栏目父级id，0代表一级栏目
        $cid = (int)$this->input->get_post("cid");//栏目id
        $id = (int)$this->input->get_post("id");//文章id
        $backurl = $this->input->get_post('backurl');//返回的url
        $backurl = $backurl ? $backurl : site_url("home/index");
        $art_model = $this->mwci->GetCplModel($id);//文章信息
        if (empty($cid) || !isset($art_model) || count($art_model) <= 0) {//不存在的文章id
            $this->parent_showmessage(
                0
                , "没有找到该文章",
                $backurl,
                3,
                'showmessage_logout');
        }
        //对于会员级别的文章，没有登录是不能查看的
        if ($art_model['jibie'] == '1' && empty($this->data['sess'])) {
            $this->parent_showmessage(0, '请登录后查看', $backurl, 3, 'showmessage_logout');
        }
        //更新点击量
        $this->M_common->update_data("update website_common_info set clicks=clicks+1 where id='$id'");
        $this->data['art_model'] = $art_model;//文章信息
        $this->data['model'] = $this->webcate->GetModel($cid);//栏目信息
        $this->data['pcateInfo'] = $this->webcate->GetModel($pid);//左边父级菜单信息
        $this->data['cMenuInfo'] = $this->webcate->GetSubList($pid);//左边子菜单信息
        $con_nzq_arr = array(39, 40, 41, 42, 43, 44, 45, 48, 49, 50, 51, 52, 53, 54,
            55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65);//非专区栏目
        $con_nfzq_arr = array(47, 70, 71, 72, 73);//南非专区栏目
        $con_nrlyzq_arr = array(46, 66, 67, 68, 69);//尼日利亚专区栏目
        //根据不同的栏目id显示专区内容页或者非专区内容页的模板
        if (in_array($cid, $con_nzq_arr)) {//非专区的内容显示
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/content", $this->data);
        } else if (in_array($cid, $con_nfzq_arr)) {//南非专区的内容显示
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/nfzq_content", $this->data);
        } else if (in_array($cid, $con_nrlyzq_arr)) {//尼日利亚专区的内容显示
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/nrlyzq_content", $this->data);
        } else {
            $this->parent_showmessage(
                0
                , "没有找到该文章",
                $backurl,
                3,
                'showmessage_logout');
        }
    }

    //搜索列表
    function search_list()
    {
        $keywords = $this->input->get_post("keywords");//关键词
        $keyword_list = $this->input->get_post("keyword_list");//列表页的关键词
        if (!empty($keyword_list)) {
            $keywords = $keyword_list;
        }
        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $pagesize = 7;//每页显示7条
        $search = array('title' => $keywords, 'cidnotin' => '37', 'jibie' => 0);//搜索关键词
        if ($this->data['sess']) {
            $search['jibie'] = 1;
        }
        $orderby["istop"] = "desc";//倒序
        $list = $this->mwci->GetInfoList($pageindex, $pagesize, $search, $orderby);
        foreach ($list['list'] as $key => $value) {
            //循环获取文章栏目的父级菜单，如果父级菜单为0则左边栏显示该菜单
            $model = $this->webcate->GetModel($value['category_id']);
            $list['list'][$key]['pid'] = $model['pid'] ? $model['pid'] : $model['id'];
        }
        $this->data['list'] = $list;
        $this->data['search'] = $search;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front/search_list", $this->data);
    }

    //信息通知列表页面
    function news_list()
    {
        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $orderby["id"] = "desc";//倒序
        $search = array('jibie' => 0);//公开显示的
        $data = $this->msip->GetInfoList($pageindex, 10, $search, $orderby);//获取分页信息
        $this->load->view(__TEMPLET_FOLDER__ . "/home/news_list", $data);
    }

    //信息通知详细页面
    function news_info()
    {
        $id = $this->input->get_post("id");//信息通知id
        $data = $this->msip->getInfo($id);//获取信息通知详细信息
        //限制没有登录的人员不能查询没有公开的信息
        $sess = $this->parent_getsession();//$this->session->all_userdata();
        if (count($data) <= 0 || ($data['jibie'] == 1 && empty($sess))) {
            $this->parent_showmessage(
                0
                , "找不到该信息通知",
                site_url("home/login"),
                3
            );
            exit();
        }
        $get = $this->input->get();
        $backurl = !isset($get["backurl"]) ? site_url("home/news_list") : $get["backurl"];
        $data["backurl"] = $backurl;
        $this->load->view(__TEMPLET_FOLDER__ . "/home/news_info", $data);
    }

    //协会活动列表页面
    function act_list()
    {
        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $orderby["id"] = "desc";//倒序
        $search = array();//搜索条件
        $data = $this->hdba->GetInfoList($pageindex, 10, $search, $orderby);//获取分页信息
        $this->load->view(__TEMPLET_FOLDER__ . "/home/act_list", $data);
    }

    //协会活动详细页面
    function act_info()
    {
        $id = $this->input->get_post("id");//信息通知id
        $data = $this->hdba->getInfo($id);//获取信息通知详细信息
        $get = $this->input->get();
        $backurl = !isset($get["backurl"]) ? site_url("home/act_list") : $get["backurl"];
        $data["backurl"] = $backurl;
        $this->load->view(__TEMPLET_FOLDER__ . "/home/act_info", $data);
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

    function chktelcode()
    {
        $get = $this->input->get();
        $title = $get["param"];
        $tel = $get["tel"];

        if ($tel == "" || $title == "") {
            $data = array("result" => "0", "msg" => "验证号不存在或已失效");
            echo json_encode($data);
            exit();
        }
        if (!$this->chktelcode_($tel, $title)) {
            $data = array("result" => "0", "msg" => "验证号不存在或已失效");
            echo json_encode($data);
        } else {
            $data = array("result" => "1");
            echo json_encode($data);
        }
        exit();
    }

    private function chktelcode_($tel, $code)
    {
        if ($tel == "" || $code == "") {
            return false;
        }
        $model = $this->M_common->query_one("select * from swj_reg_tel_code where isuse='1' and tel='$tel' and code='" . $code . "' and (UNIX_TIMESTAMP()-createtime)<120");
        if (count($model) == 0) {
            return false;
        } else {
            return true;
        }
    }

    function reg()
    {
        $sess = $this->parent_getsession();
        if (!empty($sess["userid"])) {
            header("location:" . site_url("admin/index"));
            exit();
        }

        $post = $this->input->post();
        $get = $this->input->get();

        $config = $this->parent_sysconfig();

        $backurl = !isset($get["ls"]) ? site_url("home/index") : $get["ls"];
        $this->data["ls"] = $backurl;
        $this->data["config"] = $config;

        $model = $this->user->getFields();
        $this->data['left_menu'] = $this->parent_getlrmenu();//左边菜单信息

        //用户类型列表,企业或者机构
        $usertype_list = $this->ccd->GetUserType();
        $this->data["usertype_list"] = $usertype_list;
        //服务类型列表
        $server_type_list = $this->ccd->GetServerType();
        $this->data["server_type_list"] = $server_type_list;

        //可以初始化一些数据
        $model['usertype'] = "45063";
        $model['sanzheng'] = "2";
        $this->data['model'] = $model;

        $this->load->view(__TEMPLET_FOLDER__ . "/home/reg2", $this->data);

    }

    /**
     * 注册页面重定向
     */
    private function regDirect($model, $msg)
    {
        $config = $this->parent_sysconfig();
        $data["config"] = $config;
        //绑定数据，表单不用重填
        $data['message'] = $msg;
        $data['model'] = $model;
        $this->load->view(__TEMPLET_FOLDER__ . "/home/reg2", $data);
        exit();
    }


    function doreg()
    {
        //消息
        $succ_message = "注册成功！正在审核中！审核完成才可以登录！！！";

        $post = $this->input->post();
        //模型
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

        //密码
        $model["passwd"] = md5($post["pwd"]);
        //注册日期
        $model["regdate"] = date("Y-m-d H:i:s", time());
        //默认未审核
        $model["checkstatus"] = 0;
        //状态1为正常，0为冻结
        $model['status'] = 1;
        //0:未审,1:已审,99:审核不通过
        $model['checkstatus'] = 0;

        //上传文件保存
        //允许上传格式
        $fileTypes = array('jpg', 'jpeg', 'png');
        $this->load->library("common_upload");

        //企业境外投资证书[可选的]
        if (!empty($_FILES['file_touzizhengshu']['tmp_name'])) {
            $pic_path = $this->common_upload->upload_path_ym($this->upload_path, 'file_touzizhengshu', implode("|", $fileTypes));
            if (!$pic_path) {
                $this->regDirect($model, "三证合一图片保存失败，请重新填写表单");
            } else {
                $model['fujian_touzizhengshu'] = $pic_path;
            }
        }

        if ($model['sanzheng'] == 1) {
            /* 三证合一 */
            $pic_path = $this->common_upload->upload_path_ym($this->upload_path, 'file_sanzheng', implode("|", $fileTypes));
            if (!$pic_path) {

                $this->regDirect($model, "三证合一图片保存失败，请重新填写表单");

            } else {
                $model['fujian_sanzheng'] = $pic_path;

                $newid = $this->user->insert($model);
                if ($newid) {
                    //成功
                    //showmessage("新增用户成功", "home/index", 3, 0);
                    $this->parent_showmessage(1, $succ_message, site_url("home/index"), 10, 'showmessage_logout');

                } else {

                    //绑定数据，表单不用重填
                    $this->regDirect($model, "注册失败，请重新申请！");
                }
            }
        } else {

            //营业执照证书
            $yingye_path = $this->common_upload->upload_path_ym($this->upload_path, 'file_yingye', implode("|", $fileTypes));
            //组织证书
            $zuzhi_path = $this->common_upload->upload_path_ym($this->upload_path, 'file_zuzhi', implode("|", $fileTypes));
            //税务证书
            $shuiwu_path = $this->common_upload->upload_path_ym($this->upload_path, 'file_shuiwu', implode("|", $fileTypes));

            if (!$yingye_path || !$zuzhi_path || !$shuiwu_path) {
                //文件保存错误或者格式不正确

                $this->regDirect($model, "图片保存失败，请重新填写表单");

            } else {

                $model['fujian_gongshang'] = $yingye_path;
                $model['fujian_zuzhi'] = $zuzhi_path;
                $model['fujian_shuiwu'] = $shuiwu_path;

                $newid = $this->user->insert($model);

                if ($newid) {
                    $this->parent_showmessage(1, $succ_message, site_url("home/index"), 10, 'showmessage_logout');
                    //showmessage("新增用户成功", "home/index", 3, 0);
                } else {

                    //绑定数据，表单不用重填
                    $this->regDirect($model, "注册失败，请重新申请！");
                }
            }
        }
    }

    //生成验证码
    function code()
    {
        $this->load->library("code", array(
            'width' => 80,
            'height' => 35,
            'fontSize' => 20,
            'font' => __ROOT__ . "/" . APPPATH . "/fonts/font.ttf"
        ));
        $this->code->show();
        //echo $this->code->getCode();
    }

    //zx99 检查验证码
    function chkcode()
    {
        $get = $this->input->get();
        $code = $get["param"];
        $isok = false;
        @session_start();
        if (strtolower($_SESSION['code']) == strtolower($code)) {
            $isok = true;
        } else {
            $isok = false;
        }
        if (!$isok) {
            $data = array("result" => "0", "msg" => "验证码不正确");
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
        $where = ($id > 0 ? " and uid<>{$id}" : "");

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
     * 走出去，组织或者机构的全称是否重复
     */
    function chkfullname()
    {
        $this->commonCheck("company", "组织或者机构的全称已存在");
    }

    /**
     * 会员登录
     */
    function dologin()
    {

        $post = $this->input->post();
        //判断验证码
        @session_start();
        if (strtolower($_SESSION['code']) != strtolower($post["code"])) {
            $this->parent_showmessage(
                0
                , "验证码不正确",
                site_url("home/login"),
                3,
                'showmessage_logout');
            exit();
        }
        $username = empty($post["user"]) ? "" : trim($post["user"]);
        $pwd = empty($post["pwd"]) ? "" : trim($post["pwd"]);
        if ($username == "" || $pwd == "") {
            $this->parent_showmessage(
                0
                , "信息不能为空",
                site_url("home/login"),
                3,
                'showmessage_logout'
            );
            exit();
        }
        $url = empty($post["backurl"]) ? site_url("admin/index") : $post["backurl"];
        $data = array(
            "url" => $url,//$this->weixin->getwxurl($url),
            "err" => "");
        $list = $this->user->GetUserList("username='$username' and passwd=md5('" . $pwd . "') and isdel=0");
        if (count($list) > 0) {
            $model = $list[0];
            if ($model["status"] == "0") {
                $this->parent_showmessage(
                    0
                    , "会员被禁止登录",
                    site_url("home/login"),
                    999,
                    'showmessage_logout');
            } else {
                //判断是否认证会员，如是：看看有无过期，过期就更新为未续费会员
                if ($model["usertype"] == "45064") {
                    if ($model["expire"] < time()) {
                        $model["usertype"] == "45063";
                        $model["expire"] = 0;
                    }
                }
                $model["lastlogin"] = date("Y-m-d H:i:s");
                $this->parent_setsession($model);
                //检查是否需要重置密码
                if ($model["editpwd"] == "1") {
                    //跳转去重置界面
                    header("location:" . site_url("home/editpwd"));
                    exit();
                }
                $this->user->update($model);
                $this->parent_showmessage(
                    1
                    , "登录成功！",
                    site_url("admin/index"),
                    2,
                    'showmessage_logout');
            }
        } else {
            $this->parent_showmessage(
                0
                , "会员不存在！",
                site_url("home/login"),
                2,
                'showmessage_logout');
        }
    }

    function editpwd()
    {

        $post = $this->input->post();
        $sess = $this->parent_getsession();

        if (!is_array($post)) {
            if (!isset($sess["username"])) {
                header("location:/index.php");
                exit();
            }

            $data["sess"] = $sess;
            $this->load->view(__TEMPLET_FOLDER__ . "/home/editpwd", $data);
        } else {
            //
            if ($post["pwd"] == $post["pwd2"] && $post["pwd"] != "" && $post["pwd2"] != "") {
                //保存
                $model = $this->user->GetModel($sess["userid"]);
                $model["editpwd"] = '2';//不需要重置
                $model["passwd"] = md5($post["pwd"]);
                $model["lastlogin"] = date("Y-m-d H:i:s");
                $this->user->update($model);
                $this->parent_setsession($model);
                $this->parent_showmessage(
                    1
                    , "重置成功，现在登入系统",
                    site_url("admin/index"),
                    2,
                    'showmessage_logout'
                );
            } else {
                $this->parent_showmessage(
                    0
                    , "两次输入密码不同",
                    site_url("home/editpwd"),
                    2,
                    'showmessage_logout'
                );
            }
        }
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

    /**
     * 活动提醒 cron job 每天特定时候运行
     */
    function hudongtixing()
    {
        $where = " baoming_end <unix_timestamp(now()) and  starttime<unix_timestamp(now())+3600*24 and unix_timestamp(now())<starttime";
        $list = $this->hudong->getlist($where);

        echo "执行通知活动任务!";

        $url = "";//site_url("login");
        foreach ($list as $item) {
            //获取所有用户
            $users = $this->hdbaoming->getHudongUsers($item['id']);
            //活动开始时间
            $str_time = date('Y-m-d H:i', $item['starttime']);

            foreach ($users as $user) {
                echo "发送邮件 {$user['email']} ";
                $msg = "您好，您参加的活动{$item['title']}将在{$str_time}进行，请按时参加.<a href='{$url}'>登录后台查看更多信息</a>";
                $msg = "关于工作上的问题";
                sendmail_help2($user['email'], "【走出去】", $msg);
                //sleep(1);
            }
        }
    }
}