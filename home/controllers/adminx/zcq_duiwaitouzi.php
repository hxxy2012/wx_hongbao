<?php
/**
 * 走出去对外投资-home控制器
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
 * @property M_zcq_duiwaitouzi $touzi 对外投资模型
 * @property M_common_category_data $ccd 公用目录模型
 * @property m_zcq_datamanage $datamanage 走出去数据模型
 * @property M_common_system_user $sysuser
 *
 */
class zcq_duiwaitouzi extends MY_Controller
{

    private $sess;

    private $views_folder;

    private $upload_path = "";
    private $upload_save_url = "";

    private $controller_name = "adminx/zcq_duiwaitouzi";

    private $data;

    private $debug = false;

    public function __construct()
    {
        parent::__construct();

        $this->sess = $this->parent_getsession();
        if (empty($this->sess ["userid"])) {
            header("location:" . site_url("admin/index"));
            exit();
        }

        /* 机构用户访问禁止 */
        $usertype = $this->sess['usertype'];
        if ($usertype == '45064') {
            header("location:" . site_url("admin/index"));
        }

        //加载模型
        $this->load->model("M_zcq_duiwaitouzi", 'touzi');
        $this->load->model("M_user", "user");
        $this->load->model('M_common_category_data', 'ccd');
        $this->load->model('M_common_system_user', 'sysuser');
        $this->load->model('M_zcq_fuwu_zixun', 'zixun');

        $this->views_folder = __TEMPLET_FOLDER__ . "/zcq/duiwaitouzi";

        //一些网站的全局配置
        $this->data["config"] = $this->parent_sysconfig();
        $this->data['sess'] = $this->sess;
        //显示菜单
        $this->data['curset'] = 'touzi';
        $this->data['method'] = $this->router->fetch_method();

        $this->data["finfo"] = $this->parent_getfinfo();

        //位置链接
        $this->data['admin_url'] = site_url("admin/index");
        $this->data['cur_url'] = site_url($this->controller_name . "/" . $this->data['method']);
        $this->data['controller'] = $this->controller_name;

        // 编辑器上传的文件保存的位置
        $this->upload_path = __ROOT__ . "/data/upload/touzi/";
        //编辑器上传图片的访问的路径
        $this->upload_save_url = "/data/upload/touzi/";
    }

    /**
     * 我的对外投资列表页面
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
        //状态 0未提交 1通过 2退回
        $search_val['status'] = isset($get['search_status']) ? $get['search_status'] : "";
        if ($search_val['status'] != "") {
            $search['status'] = $search_val['status'];
        }
        //创建开始时间
        $search_val['createtime_start'] = isset($get['search_createtime_start']) ? $get['search_createtime_start'] : "";
        if ($search_val['createtime_start'] != "") {
            $search['createtime_start'] = $search_val['createtime_start'];
        }
        //创建结束时间
        $search_val['createtime_end'] = isset($get['search_createtime_end']) ? $get['search_createtime_end'] : "";
        if ($search_val['createtime_end'] != "") {
            $search['createtime_end'] = $search_val['createtime_end'];
        }


        //列表排序
        $search['create_userid'] = $userid;
        $orderby = array("id" => "desc");
        $data = $this->touzi->getList($pageindex, $pagesize, $search, $orderby);

        //搜索变量
        $data['search_val'] = $search_val;

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/list", $data);
    }

    /**
     * 会员新建投资
     */
    public function add()
    {
        $data = array();

        //没有数据的模型
        $model = $this->touzi->getFields();

        //初始化一些默认数据
        if ($this->debug) {
            //测试用
            foreach ($model as $k => $v) {
                if ($model[$k] == "") {
                    $model[$k] = 100 * rand(153613130, 153613631);
                }
            }
            $model['stat_zuoji'] = '0755-88880000';
            $model['sea_zuoji'] = '0755-88880000';
            $model['pro_zuoji'] = '0755-88880000';
        }

        //显示到页面的一些变量
        $data['type'] = "add";
        $data['title'] = "新增";
        //表单提交地址
        $form_url = site_url($this->controller_name . "/doadd");
        $data['form_url'] = $form_url;
        $data['model'] = $model;

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/add_or_edit", $data);
    }

    /**
     * 处理新建
     */
    public function doadd()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $post = $this->input->post();

        $model = $this->getmysqlmodel();

        //创建人 会员ID
        $model['create_userid'] = $userid;
        //创建时间
        $model['create_time'] = time();
        //【修改时间】初始化为【创建时间】
        $model['update_time'] = time();
        //大于0为已删除  为0未删除
        $model['del_sysuserid'] = 0;
        //默认为0为未删除
        $model['isdel'] = '0';

        if (isset($post['save'])) {
            /*保存模式*/
            //0未提交 1未审核 2通过 3退回
            $model['check_status'] = '0';

            $newid = $this->touzi->insert($model);
            if ($newid > 0) {
                //插入成功
                $this->parent_showmessage(1, "新增对外投资联系成功！", site_url($this->controller_name . "/lists"), 3);
            } else {
                $this->parent_showmessage(0, "新增对外投资联系失败！", site_url($this->controller_name . "/lists"), 3);
            }

        }else{
            /*提交审核模式*/
            $model['check_status'] = '1';

            $newid = $this->touzi->insert($model);
            if ($newid > 0) {
                //插入成功
                $this->parent_showmessage(1, "新增对外投资联系,并且提交审核成功！", site_url($this->controller_name . "/lists"), 3);

                /*发送站内信*/
                $title = "有一条新的对外投资联系表需要审核，编号为{$touzi_id}";
                $content = "管理员您好，您有一条新的对外投资联系表需要审核，编号为{$touzi_id}，请到对外投资联系表管理栏目查看";
                $re = send_zhan_mail('', get_all_admin_id(), $title, $content, $userid, null, $this->sess["session_id"]);
            } else {
                $this->parent_showmessage(0, "新增对外投资联系失败！", site_url($this->controller_name . "/lists"), 3);
            }

        }

    }

    /**
     * 修改页面
     */
    public function edit()
    {
        $data = array();

        //用户id
        $userid = verify_id($this->sess['userid']);

        //对外投资id
        $id = verify_id($this->input->get_post("id"));

        //获得模型
        $model = $this->touzi->getModel($id);
        if (empty($model)) {
            parent::parent_showmessage(0, "没有这个对外投资联系表！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        //权限检查
        if ($model['create_userid'] != $userid) {
            parent::parent_showmessage(0, "不能修改该对外投资联系表！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        if ($model['check_status'] == '2' || $model['check_status']==1) {
            //未审核也就是审核中,审核通过这两种情况不能修改
            parent::parent_showmessage(0, "该对外投资联系表需要管理员才能修改！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }

        //显示到页面的一些变量
        $data['type'] = "edit";
        $data['title'] = "修改";
        //表单提交地址
        $form_url = site_url($this->controller_name . "/doedit");
        $data['form_url'] = $form_url;
        $data['model'] = $model;

        //链接
        $this->data['method'] = "lists";

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/add_or_edit", $data);
    }


    /**
     * 处理编辑
     */
    public function doedit()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $post = $this->input->post();
        $model = $this->getmysqlmodel();

        //本条对外投资的id
        $touzi_id = verify_id($this->input->get_post("id"));
        //原本的数据模型
        $model_pre = $this->touzi->getModel($touzi_id);
        if (empty($model_pre)) {
            parent::parent_showmessage(0, "没有这个对外投资联系表！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        //权限检查
        if ($model_pre['create_userid'] != $userid) {
            parent::parent_showmessage(0, "不能修改该对外投资联系表！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        if ($model_pre['check_status'] == '2' || $model['check_status']==1) {
            //未审核也就是审核中,审核通过这两种情况不能修改
            parent::parent_showmessage(0, "该对外投资联系表需要管理员才能修改！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }

        $model['id'] = $touzi_id;
        //更新修改时间
        $model['update_time'] = time();
        $model['update_userid'] = $userid;

        if (isset($post['save'])) {
            //保存模式
            //0未提交 1未审核 2通过 3退回
            //$model['check_status'] = '0';

            $re_str_ok = "保存成功！";
            $re_str_fail = "保存失败";
            $num = $this->touzi->update($model);
            if ($num > 0) {
                parent::parent_showmessage(1, $re_str_ok, site_url($this->controller_name . "/lists"), 3);
            } else {
                parent::parent_showmessage(0, $re_str_fail, site_url($this->controller_name . "/lists"), 3);
            }

        } else {
            //提交审核模式
            $model['check_status'] = '1';

            $re_str_ok = "提交审核成功！";
            $re_str_fail = "提交失败成功";
            $num = $this->touzi->update($model);
            if ($num > 0) {
                parent::parent_showmessage(1, $re_str_ok, site_url($this->controller_name . "/lists"), 3);

                /*发送站内信*/
                $title = "有一条新的对外投资联系表需要审核，编号为{$touzi_id}";
                $content = "管理员您好，您有一条新的对外投资联系表需要审核，编号为{$touzi_id}，请到对外投资联系表管理栏目查看";
                $re = send_zhan_mail('', get_all_admin_id(), $title, $content, $userid, null, $this->sess["session_id"]);

            } else {
                parent::parent_showmessage(0, $re_str_fail, site_url($this->controller_name . "/lists"), 3);
            }
        }

        exit();
    }

    /**
     * 查看页面
     */
    public function view()
    {
        //用户id
        $userid = verify_id($this->sess['userid']);

        $post = $this->input->post();
        $get = $this->input->get();

        //本条对外投资id
        $touzi_id = verify_id($this->input->get_post("id"));
        //获得模型
        $model = $this->touzi->getModel($touzi_id);
        if (empty($model)) {
            parent::parent_showmessage(0, "没有这个对外投资联系表！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }
        //权限检查
        if ($model['create_userid'] != $userid) {
            parent::parent_showmessage(0, "不能查看该对外投资联系表！！！", site_url($this->controller_name . "/lists"), 3);
            exit();
        }

        $data['model'] = $model;

        //显示到页面的一些变量
        $data['type'] = "view";
        $data['title'] = "查看";
        //表单提交地址
        $form_url = site_url($this->controller_name . "/view");
        $data['form_url'] = $form_url;

        //链接
        $this->data['method'] = "lists";
        //$this->data['cur_url'] = site_url($this->controller_name . "/" . $this->data['method']);

        $data = array_merge($this->data, $data);
        $this->load->view($this->views_folder . "/add_or_edit", $data);
    }

    /**
     * 执行删除操作
     */
    public function delete()
    {

        //用户id
        $userid = verify_id($this->sess['userid']);
        $usertype = $this->sess['usertype'];

        $get = $this->input->get();
        $idlist = $get["idlist"];
        $arr = explode(",", $idlist);
        //记录操作失败的数量
        $count = 0;
        foreach ($arr as $zixun_id) {
            //$model = $this->zixun->getModel($id);
            //每一条咨询id
            $model['id'] = $zixun_id;

            if ($usertype == '45063') {
                //企业
                $model['isdel_send'] = "1";
                $model['del_time_send'] = time();
                $model['del_send_userid'] = $userid;
            } elseif ($usertype == '45064') {
                //机构
                $model['isdel_receive'] = "1";
                $model['del_time_receive'] = time();
                $model['del_receive_userid'] = $userid;
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
        //var_dump($this->parent_getsession());
    }


}