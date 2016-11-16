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
 * @property M_common_category_data $ccd 公用目录模型
 * @property CI_Input $input
 */
class home_wx extends MY_ControllerLogout
{
    private $upload_path = "";

    var $data = array();

    function home_wx()
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
        //滚动图 读 滚动信息 下边信息       
        $this->data["adv"] = $this->ad->getlist("ad_type='2' and status='1'", "id asc");//首页banner下滚动图
        //工作状态
        $gzzt = $this->mwci->GetInfoList2('1', 'content', 0, 5, array('category_id' => 48, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));
        $this->data["gzzt"] = $gzzt['list'];
        //通知公告
        $tzgg = $this->mwci->GetInfoList2('1', 'content', 0, 5, array('category_id' => 49, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));
        $this->data["tzgg"] = $tzgg['list'];
        //政策法规
        $zcfg = $this->mwci->GetInfoList2('1', 'content', 0, 5, array('category_id' => 39, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));
        $this->data["zcfg"] = $zcfg['list'];
        //办事指南
        $bszn = $this->mwci->GetInfoList2('1', 'content', 0, 5, array('category_id' => 40, 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));
        $this->data["bszn"] = $bszn['list'];
        //风险防范
        $fxff = $this->mwci->GetInfoList2('1', 'content', 0, 5, array('t1.category_id_in' => '53,54,55', 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));
        $this->data["fxff"] = $fxff['list'];
        //服务机构下级栏目
        $this->data["fwjg_next_cat"] = $this->mwc->GetSubList(45);
        foreach ($this->data["fwjg_next_cat"] as $key => $value) {//循环栏目获取其内容
            $temp = $this->mwci->GetInfoList2('1', 'content', 0, 5, array('category_id' => $value['id'], 'jibie' => 0), array('istop' => 'desc', 'id' => 'desc'));
            $this->data['fwjg_next_cat_arr'][$key] = $temp['list'];
        }
        // var_dump($this->data["fwjg_next_cat_arr"]);exit;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front_wx/index", $this->data);
    }

    //根据栏目id，区分使用到列表页还是单页还是专区模板
    function transfer()
    {

        $pid = (int)$this->input->get_post("pid");//栏目父级id，0代表一级栏目
        $cid = (int)$this->input->get_post("cid");//栏目id
        $backurl = $this->input->get_post('backurl');//返回的url
        $backurl = $backurl ? $backurl : site_url("home_wx/index");
        $this->data['pid'] = $pid;
        $search = array('jibie' => 0);//公开显示的
        //获取显示那些菜单的内容列表
        if ($cid == 0&&$pid == 0) {
            $this->parent_showmessage(
                0
                , "没有找到该栏目",
                $backurl,
                3,
                'showmessage_logout_wx');exit;
        } else if ($cid == 0&&$pid != 0) {//栏目id为0父级栏目不为0，显示改父级id下的二级栏目下所有文章
            $this->data['cmodel'] = $this->webcate->GetModel($pid);//栏目信息
            //获取该栏目下的所有子栏目
            $sublist = $this->mwc->GetSubList($pid);
            $sublist_str = '';
            foreach ($sublist as $key => $value) {//循环下级栏目获取其以逗号分割字符串id
                if ($key == 0) {
                    $sublist_str = $value['id'];
                } else {
                    $sublist_str.= ','.$value['id'];
                }
            }
            if ($sublist_str != '') {//有下级栏目
                $search['t1.category_id_in'] = $sublist_str;
            } else {
                $search['t1.category_id_in'] = $pid;
            }
            $this->data['cid'] = $pid;
        } else {//获取该栏目下的所有文章列表
            $this->data['cmodel'] = $this->webcate->GetModel($cid);//栏目信息
            $search['category_id'] = $cid;
            $this->data['cid'] = $cid;
        }
        //通过栏目id获取文章信息
        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $pagesize = 12;//一页显示多少个
        $orderby["id"] = "desc";//倒序
        
        $this->data['list'] = $this->mwci->GetInfoList2WX('1', 'content', $pageindex, $pagesize, $search, array('istop' => 'desc', 'id' => 'desc'));//获取分页信息
        $list_cid = array(39,40,41,43,44,45,48,49);//只有8个栏目的信息列表
        //根据栏目id显示不同的模板
        if (in_array($this->data['cid'], $list_cid)) {//文字列表
           $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front_wx/list", $this->data);
        } else {
            $this->parent_showmessage(
                0
                , "没有找到该栏目",
                $backurl,
                3,
                'showmessage_logout_wx');
        }
    }

    //详细内容页
    function content()
    {
        $pid = (int)$this->input->get_post("pid");//栏目父级id，0代表一级栏目
        $cid = (int)$this->input->get_post("cid");//栏目id
        $id = (int)$this->input->get_post("id");//文章id
        $backurl = $this->input->get_post('backurl');//返回的url
        $backurl = $backurl ? $backurl : site_url("home_wx/index");
        $art_model = $this->mwci->GetCplModel($id);//文章信息
        if (empty($cid) || !isset($art_model) || count($art_model) <= 0) {//不存在的文章id
            $this->parent_showmessage(
                0
                , "没有找到该文章",
                $backurl,
                3,
                'showmessage_logout_wx');
        }
        //更新点击量
        $this->M_common->update_data("update website_common_info set clicks=clicks+1 where id='$id'");
        $this->data['art_model'] = $art_model;//文章信息
        $this->data['cmodel'] = $this->webcate->GetModel($cid);//栏目信息
        $list_cid = array(39,40,41,43,44,45,48,49);//只有8个栏目的信息列表
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front_wx/content", $this->data);
    }

    //搜索列表
    function search_list()
    {
        $keywords = $this->input->get_post("keywords");//首页搜索的关键词
        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $pagesize = 12;//每页显示12条
        $search = array('title' => $keywords, 
                'cidnotin' => '37,46,47,66,67,68,69,70,71,72,73');//搜索关键词以及去掉信息通知和专区栏目的信息
        $orderby["istop"] = "desc";//倒序
        $list = $this->mwci->GetInfoList2WX('1', 'content', $pageindex, $pagesize, $search, array('istop' => 'desc', 'id' => 'desc'));//获取分页信息
        foreach ($list['list'] as $key => $value) {
            //循环获取文章栏目的父级菜单，如果父级菜单为0则跳转链接cid为该栏目否则显示父级栏目
            $model = $this->webcate->GetModel($value['category_id']);
            $list['list'][$key]['pid'] = $model['pid'];
            $list['list'][$key]['cid'] = $model['pid'] ? $model['pid'] : $model['id'];
        }
        $this->data['list'] = $list;
        $this->data['search'] = $search;
        $this->data['cmodel']['title'] = '搜索结果';
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/front_wx/search_list", $this->data);
    }



}