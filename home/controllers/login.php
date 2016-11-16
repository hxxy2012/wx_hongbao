<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * Class login
 * @property M_user $user
 */
class login extends MY_ControllerLogout
{
    var $data = array();

    function login()
    {
        parent::__construct();
        $this->load->model('M_user', 'user');
        $get = $this->input->get();
        $this->data["config"] = $this->parent_sysconfig();
        $this->data["backurl"] = !isset($get["backurl"]) ? site_url("home/index") : $get["backurl"];
        $this->data["finfo"] = $this->parent_getfinfo();
        $this->data['left_menu'] = $this->parent_getlrmenu();//左边菜单信息
        $this->load->model('M_common');
        // var_dump($this->data['left_menu']);exit;
    }

    function index()
    {
        $sess = $this->parent_getsession();//$this->session->all_userdata();
        if (!empty($sess["userid"])) {
            header("location:" . site_url("admin/index"));
            exit();
        }
        //$this->session->sess_destroy();//清空掉所有的seession
        $this->load->view(__TEMPLET_FOLDER__ . "/home/login", $this->data);
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

    //检查验证码
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

    function logout()
    {
        $get = $this->input->get();
        //$this->session->sess_destroy();
        $sess = $this->parent_getsession();//$this->session->all_userdata();
        //print_r($sess);
        //die();
        $backurl = site_url("login/index");//$this->weixin->getwxurl(site_url("home/login"));
        if (!empty($get["backurl"])) {
            $backurl = $get["backurl"];
        }
        if (is_array($sess)) {
            $this->session->unset_userdata($sess);
            $this->parent_delsession($sess["session_id"]);
            //保存COOKE用于单点登录
            $cookie = array(
                'name' => 'sid',
                'value' => $sess["session_id"],
                'expire' => '-1',
                'domain' => '.' . $this->home_data["config"]["cfg_cookie_domain"],
                'path' => '/',
                'prefix' => 'zx_',
                'secure' => FALSE
            );

            //$this->input->set_cookie($cookie);
            delete_cookie("sid", '.' . $this->home_data["config"]["cfg_cookie_domain"], '/', 'zx_');
        }
        header("Location:" . $backurl);
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
                site_url("login/index"),
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
                site_url("login/index"),
                3,
                'showmessage_logout'
            );
            exit();
        }
        $url = empty($post["backurl"]) ? site_url("admin/index") : $post["backurl"];
        $data = array(
            "url" => $url,//$this->weixin->getwxurl($url),
            "err" => "");


        //$list = $this->user->GetUserList("username='$username' and passwd=md5('".$pwd."') and isdel=0");
        $list = $this->user->GetUserList("username='{$username}' and isdel=0 ");


        if (count($list) > 0) {
            $model = $list[0];
             //判断是否超过登录次数2016年9月3日10:44:42 bylk
            $limitnum = $this->get_sys_info('failed_login_limitnum');//系统设置登录次数限制
            $limittime = $this->get_sys_info('failed_login_limittime') * 60;//系统设置限制失败时间
            $nowTime = time();
            $sy_num  = $limitnum - $model['failed_login_num'];//剩余登录次数
            //失败次数超过系统设置的次数,提示不能账号在$nowtTime-$failed_login_lasttime>$limittime时间后解锁登录
            if ($sy_num <= 0&&$nowTime-$model['failed_login_lasttime']<$limittime) {
                $sy_minute = floor($limittime/60) - floor(($nowTime-$model['failed_login_lasttime'])/60);
                $this->parent_showmessage(0, "该账号登录次数过多，{$sy_minute}分钟后可以后可以重新登录！", site_url("login/index"), 2, 'showmessage_logout');
                exit;
            } else if ($nowTime-$model['failed_login_lasttime']>=$limittime) {//$sy_num <= 0&&
                //已经超过限制失败登录时间,重置登录失败次数
                $data = array('failed_login_num' => 0,'uid' => $model['uid']);
                $this->user->update($data);//更新登录失败信息
                $model['failed_login_num'] = 0;
                $sy_num = $limitnum;//剩余登录次数为系统设置的登录次数
            }  
            //判断登录限制结束
            if ($model["status"] == "0") {
                $this->parent_showmessage(
                    0
                    , "会员被禁止登录",
                    site_url("login/index"),
                    999,
                    'showmessage_logout');
            } elseif ($model["isdel"] == 1) {
                $this->parent_showmessage(0, "会员已被删除！", site_url("login/index"), 2, 'showmessage_logout');
            } elseif ($model["passwd"] != md5($pwd)) {
                //更新数据库失败登录信息学
                $data = array('failed_login_num' => $model['failed_login_num']+1, 'failed_login_lasttime' => $nowTime
                            ,'uid' => $model['uid']);
                $this->user->update($data);//更新登录失败信息
                $sy_num--;//剩余次数减一（该次登录算作失败）
                $message = "剩余登录次数：".$sy_num;
                $this->parent_showmessage(0, "密码不正确！{$message}", site_url("login/index"), 2, 'showmessage_logout');
            } else if ($model["checkstatus"] == "0") {
                $this->parent_showmessage(
                    0
                    , "会员正在审核中，请稍后登录",
                    site_url("login/index"),
                    5,
                    'showmessage_logout');
            }else if ($model["checkstatus"]=="99"){
                //审核不通过，开菜单修改资料
                $model["lastlogin"] = date("Y-m-d H:i:s");
                $this->parent_setsession($model);
                $this->user->update($model);
                $this->parent_showmessage(1, "用户资料审核不成功，修改提交资料审核后才能继续使用其它功能！", site_url("adminx/zcq_user/editinfo"), 5, 'showmessage_logout');
            }
            else {
                $model["lastlogin"] = date("Y-m-d H:i:s");
                $this->parent_setsession($model);
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
                site_url("login/index"),
                2,
                'showmessage_logout');
        }
    }

    //获取系统变量信息$name 变量名称
    private function get_sys_info($name) {
        $result = '';
        $sql = "select value from `57sy_common_sysconfig` where `varname`='$name'";
        $result = $this->M_common->query_one($sql);
        return $result['value'];
    }
}