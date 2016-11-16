<?php
/**
 * 走出去数据管理
 * User: 嘉辉
 * Date: 2016-08-18
 * Time: 15:34
 */

/**
 * Class zcq_user
 * @property CI_Input $input
 * @property Common_upload $common_upload
 * @property CI_Router router
 *
 * @property M_user $user
 * @property m_zcq_fuwu_zixun $zixun 服务咨询模型
 * @property m_zcq_datamanage $datamanage 走出去模型
 * @property M_common_category_data $ccd 公用目录模型
 * @property M_common_system_user $sysuser
 *
 */
class zcq_datamanage extends MY_Controller
{

    private $sess;

    private $views_folder;

    private $upload_path = "";
    private $upload_save_url = "";

    private $controller_name = "adminx/zcq_datamanage";

    private $data;

    public function __construct()
    {
        parent::__construct();

        $this->sess = $this->parent_getsession();
        if (empty($this->sess ["userid"])) {
            header("location:" . site_url("admin/index"));
            exit();
        }

        /* 机构用户访问控制 */
        $usertype = $this->sess['usertype'];
        if ($usertype == '45064') {
            header("location:" . site_url("admin/index"));
            exit();
        }

        //加载模型
        $this->load->model("M_user", "user");
        $this->load->model('M_common_category_data', 'ccd');
        $this->load->model('M_common_system_user', 'sysuser');
        $this->load->model('M_zcq_fuwu_zixun', 'zixun');
        $this->load->model('m_zcq_datamanage', 'datamanage');


        $this->views_folder = __TEMPLET_FOLDER__ . "/zcq/datamanage";

        //一些网站的全局配置
        $this->data["config"] = $this->parent_sysconfig();
        $this->data['sess'] = $this->sess;
        //显示菜单
        $this->data['curset'] = 'datamanage';
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
     * 列表页
     */
    public function index()
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

        //排序
        $order_by = array("id" => "desc");

        //获得list，内有pager和list
        $search['t1.userid'] = $userid;
        $data = $this->datamanage->getList_qy($pageindex, $pagesize, $search, $order_by);

        //搜索变量
        $data['search_val'] = $search_val;

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/list", $data);
    }

    /**
     * 查看走出去数据
     */
    public function view()
    {

        //用户id
        $userid = verify_id($this->sess['userid']);

        $post = $this->input->post();
        $get = $this->input->get();

        //本条咨询的id
        $data_id = verify_id($this->input->get_post("id"));
        //获得模型
        $model = $this->datamanage->getModel($data_id);
        if (empty($model)) {
            parent::parent_showmessage(0, "没有这条数据！！！", site_url($this->controller_name . "/index"), 5);
            exit();
        }
        //只能查看自己的
        if ($model['userid'] != $userid) {
            parent::parent_showmessage(0, "不能查找这条数据！！！", site_url($this->controller_name . "/index"), 5);
            exit();
        }

        //设置为已经阅读
        $model['id'] = $data_id;
        $model['user_isread'] = "1";
        $this->datamanage->update($model);

        //用户模型
        $user_model = $this->user->GetModel($userid);
        $data['user_model'] = $user_model;
        $data['model'] = $model;

        //链接
        //$this->data['method'] = "list";
        $this->data['cur_url'] = site_url($this->controller_name . "/" . $this->data['method']) . "?id={$data_id}";

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/view", $data);
    }

    /**
     * 设置为已经阅读【暂不用】
     */
    public function doread()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $post = $this->input->post();
        $get = $this->input->get();
        //本条咨询的id
        $data_id = verify_id($this->input->get_post("id"));
        if ($data_id == 0) {
            parent::parent_showmessage(0, "设置已经阅读失败，没有数据！！！", site_url($this->controller_name . "/index"), 5);
            exit();
        }

        $model['id'] = $data_id;
        $model = $this->datamanage->getModel($data_id);

        $this->data['message'] = "成功设置已读！";
        $this->view();
    }

    public function test()
    {
        //var_dump($this->parent_getsession());
    }


}