<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
header("Content-type: text/html; charset=utf-8");
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
    var $wx_art = "http://mp.weixin.qq.com/s?__biz=MjM5NDM1NjUzNg==&mid=2652798408&idx=1&sn=862e9d17ee356519f21536fb98601862&chksm=bd63d19f8a14588989491dbf1e74791ddf6560dc71e2a62a43e1bd04ae523812af76c2746a2a#rd";//微信文章页

    function Home()
    {
        parent::__construct();
        $this->data["config"] = $this->parent_sysconfig();
        $this->load->model('M_user', 'user');

        $this->load->model('M_hb_hongbao_set','hbset');
        $this->load->model('M_hb_hongbao_list','hblist');
        $this->load->model('M_hb_tel','hb_tel');
        $this->load->library("Weixin","weixin");
        $this->data["sess"] = $this->parent_getsession();//登陆者信息
        $this->upload_path = __ROOT__ . "/data/upload/user/";
        $this->data["ls"] = get_url();//获取当前连接
    }

    /**
     * 首页
     */
    function index()
    {
        if(!isset($this->data["sess"]["tel"])){
            header("location:".site_url("home/login"));
            exit();
        }
        $this->data["pagetitle"] = "摇一摇";
        $userinfo = $this->getUinfoBysq();//用户授权获取用户信息
        // var_dump($this->data["zfwz"]);exit;
        $this->load->view(__TEMPLET_FOLDER__ . "/hb/index", $this->data);
    }

    function login(){
        $openid = $this->weixin->getopenid();
        if($openid==""){
            header("location:".$this->getwxurl(site_url("home/login")));
        }
        $this->data["openid"]=$openid;
        $this->load->view(__TEMPLET_FOLDER__ . "/hb/login", $this->data);
    }

    function dologin(){
        $post =  $this->input->post();
        $tel = isset($post["tel"])?$post["tel"]:"";
        $openid = isset($post["openid"])?$post["openid"]:"";
        if($tel==""){
            echo "没有手机号";
            exit();
        }
        $tel = trim($tel);
        $list = $this->hb_tel->getlist("tel='".$tel."'");
        if(count($list)>0){
            $usermodel["tel"] = $tel;
            $usermodel["openid"] = $openid;
            $this->parent_setsession($usermodel);
            echo "ok";
        }
        else{
            echo "手机号不存在，不能参加摇一摇";
            exit();
        }
    }

    //中奖纪录
    function zjjl() {
        $this->load->view(__TEMPLET_FOLDER__ . "/hb/zjjl", $this->data);   
    }
    //用户授权方式获取用户信息
    private function getUinfoBysq(){
         /*用户授权方式获取用户信息开始*/
        $get = $this->input->get();
        $this->load->library("weixin");
        if (isset($get['code'])&&$get['code']!='') {
            //用户授权获取用户信息
            $reUrl = site_url('home/index');
            $wxurl = $this->weixin->getwxurl($reUrl);
            header("Location: $wxurl");exit;
        }
        //通过code获取用户授权access_token
        $accurl  = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->weixin->Appkey}&secret={$this->weixin->AppSecret}&code={$get['code']}&grant_type=authorization_code";
        $accjson = $this->weixin->httpGet($accurl);
        $accinfo = json_decode($accjson,true);
        if(!__WXKF__) {
            if (!isset($accinfo['access_token']) || $accinfo['access_token'] == '') {
                echo '出错，请您退出重试！';
                exit;
            }
        }
        //通过openid判断该用户是否已经关注公众号，没有关注的跳到素材页
        if(!__WXKF__) {
            if (!$this->checksubscribe($accinfo['openid'])) {
                header("Location: " . $this->wx_art);
                exit;
            }
        }
        //拉取用户信息
        if(!__WXKF__) {
            $useurl = "https://api.weixin.qq.com/sns/userinfo?access_token={$accinfo['access_token']}&openid={$accinfo['openid']}&lang=zh_CN";
            $usejson = $this->weixin->httpGet($useurl);
            $useinfo = json_decode($usejson, true);//用户的信息
            return $useinfo;
        }
        else{
            return array();
        }
        /*用户授权方式获取用户信息结束*/



    } 
    //通过openid判断该用户是否已经关注公众号,已关注返回true，否则false
    private function checksubscribe($openid) {
        $this->load->library("weixin");
        $acctoken = $this->weixin->getacc();//获取基础调用token
        $issuburl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$acctoken&openid=$openid&lang=zh_CN";
        $issubjson= $this->weixin->httpGet($issuburl);
        $issubinfo= json_decode($issubjson,true);
        if (isset($issubinfo['subscribe'])&&$issubinfo['subscribe']==1) {//已关注
            return true;
        }
        return false;
    }

    //发送现金红包,$money发送的金额
    public function paybyxjhb() {
        $this->load->library("weixin");
        $mch_id = '';//商户id
        $shkey  = '';//商户里的api密钥，设置路径：微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置
        $send_name = '';//商户名称
        $re_openid = '';//接收红包用户openid
        $money = '';//发送的金额
        $wishing = '恭喜您抢到了一个红包,快拆开看看吧！';//红包祝福语
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $model = array();//发送post，xml请求的所有信息数组
        $nowTime = time();
        $model['nonce_str'] = $this->weixin->createNonceStr(32);//随机字符串
        //mt_rand(1000000000, 9999999999)商户订单号,唯一,mch_id+yyyymmdd+10位一天内不能重复的数字
        $model['mch_billno'] = $mch_id . date('Ymd',$nowTime) . mt_rand(10000,99999) . substr($nowTime, 5);
        $model['mch_id'] = $mch_id;//商户id
        $model['wxappid'] = $this->weixin->Appkey;//公众号appid
        $model['send_name'] = $send_name;//公众号appid
        $model['re_openid'] = $re_openid;//接收红包用户openid
        $model['total_amount'] = $money;//付款金额,单位分
        $model['total_num'] = 1;//红包发放总人数
        $model['wishing'] = $wishing;//红包祝福语
        $model['client_ip'] = getIP();//ip地址
        $model['act_name'] = '摇一摇，抢红包';//活动名称
        $model['remark'] = '摇一摇，抢红包';//备注
        $model['scene_id'] = 2;//场景id，2代表抽奖
        // $model['risk_info'] = '';//活动信息,暂时不需要注释
        // $model['consume_mch_id'] = '';//资金授权商户号,服务商替特约商户发放时使用。暂时不需要注释
        $model['sign'] = $this->weixin->getxjhbSign($model, $shkey);//获取签名
        $xmldata = $this->weixin->formArrToXml($model);//将数组转化成xml格式
        //进行发送红包
        $result = $this->weixin->httpPostXml($url, $xmldata);//返回xml格式的数据
        $rst_arr = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);//转成数组
        if ($rst_arr['result_code'] == 'SUCCESS') {//发送红包成功
            return true;
        } else {
            return false;
        }
    }


    function send_tel_code()
    {
        $post = $this->input->post();
        /*$this->parent_showmessage(
                0
                , "没有找到该栏目",
                $backurl,
                3,
                'showmessage_logout');*/
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