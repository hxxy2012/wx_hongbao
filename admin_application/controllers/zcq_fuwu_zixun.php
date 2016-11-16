<?php
/**
 * 走出去——服务咨询控制器
 * User: 嘉辉
 * Date: 2016-08-06
 * Time: 9:10
 */

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * @property
 * Class zcq_fuwu_zixun
 * @property CI_Input $input 输入类
 *
 * @property m_zcq_fuwu_zixun $zixun 服务咨询模型
 * @property M_user $user 用户模型
 */
class zcq_fuwu_zixun extends MY_Controller
{
    private $upload_path = "";
    private $upload_save_url = "";


    public function __construct()
    {
        parent::__construct();

        $this->load->model('M_zcq_fuwu_zixun', 'zixun');
        $this->load->model('M_user', 'user');

        // 编辑器上传的文件保存的位置
        $this->upload_path = __ROOT__ . "/data/upload/editor/";
        //编辑器上传图片的访问的路径
        $this->upload_save_url = base_url() . "/data/upload/editor/";
    }

    /**
     * 列表页
     */
    public function index()
    {
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
        //标题/全称/简称/单位/联系人
        $search_val['name'] = isset($get['search_name']) ? $get['search_name'] : "";
        if ($search_val['name'] != "") {
            $search['name'] = $search_val['name'];
        }
        //是否已经回复，0未回，1代表已回
        $search_val['isread'] = isset($get['search_isread']) ? $get['search_isread'] : "";
        if ($search_val['isread'] != "") {
            $search['isread'] = $search_val['isread'];
        }

        //排序条件
        $orderby["id"] = "desc";
        $orderby["receive_isread"] = "asc";

        //获得list，内有pager和list
        $data = $this->zixun->getList($pageindex, $pagesize, $search, $orderby);
        //搜索变量
        $data['search_val'] = $search_val;


        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/fuwu_zixun/list", $data);
    }

    /**
     * 回复页
     */
    public function reply()
    {
        //整数
        $id = verify_id($this->input->get_post("id"));

        //获得模型
        $model = $this->zixun->getModel($id);
        //用户模型
        $user_model = $this->user->GetModel($model['send_userid']);

        $data['model'] = $model;
        $data['user_model'] = $user_model;

        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/fuwu_zixun/reply", $data);
    }

    /**
     * 执行回复
     */
    public function doreply()
    {
        $post = $this->input->post();
        $model = parent::getmysqlmodel();
        $model['id'] = $post['id'];
        //接收人是否已读
        $model['receive_isread'] = "1";
        //接收人回复时间
        $model['receive_time'] = time();
        //接收人管理员ID
        $model['receive_sysuserid'] = admin_id();

        $num = $this->zixun->update($model);
        if ($num > 0) {
            echo "<script>
                top.tip_show('服务咨询回复成功',1,500);
			    top.topManager.closePage();
			    //234是管理列表
			    top.topManager.reloadPage('234');
			</script>";
        } else {
            showmessage("服务咨询回复失败", "zcq_fuwu_zixun/index", 3, 0);
        }
        exit();
    }

    /**
     * 管理员执行删除操作
     */
    public function delete_admin()
    {
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $arr = explode(",", $idlist);
        $count = 0;//记录操作失败的数量
        foreach ($arr as $id) {
            //$model = $this->zixun->getModel($id);
            $model['id'] = $id;
            //管理员是否删除
            $model['isdel_sysuser'] = "1";
            $model['del_time_sysuser'] = time();
            $model['del_sysuserid'] = admin_id();

            $rows = $this->zixun->update($model);
            if ($rows == 0) {
                $count++;
            }
        }

        echo $count;
        exit();
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

    public function test()
    {
        /* 为了能够转换url
        * 使用方法：只需填写【控制器/方法】，里面的内容将会转化成链接
         */
        $string = "[超人用户]重新上传了证书，请审核！<a href='【zcq_mail/myview】?id=116'>点击此处直接查看</a>";
        $pattern = '/([\s\S]*)【(.*?)】([\s\S]*)/i';
        preg_match($pattern, $string, $matches);
        if (!empty($matches)) {
            $control_method = $matches[2];
            $string = $matches[1] . site_url($control_method) . $matches[3];
        }
        echo $string;
    }
}