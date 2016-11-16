<?php
/**
 * 走出去-项目管理控制器
 * Created by PhpStorm.
 * User: 嘉辉
 * Date: 2016-08-11
 * Time: 11:06
 */

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * @property
 * Class zcq_fuwu_zixun
 * @property CI_Input $input 输入类
 *
 * @property  m_zcq_zhongdian_pro $model 重点项目模型
 * @property M_common_category_data $ccd 为了获得镇区
 *
 */
class zcq_zhongdian_pro extends MY_Controller
{
    private $upload_path = "";
    private $upload_save_url = "";

    private $controller_name = "zcq_zhongdian_pro";

    //视图路径
    private $views_path;

    private $data;

    /**
     * @var bool 是否调试模式
     */
    private $debug = true;

    public function __construct()
    {
        parent::__construct();

        $this->load->model("m_" . $this->controller_name, 'model');
        $this->load->model('M_common_category_data', 'ccd');
        // 编辑器上传的文件保存的位置
        $this->upload_path = __ROOT__ . "/data/upload/editor/";
        //编辑器上传图片的访问的路径
        $this->upload_save_url = base_url() . "/data/upload/editor/";

        $this->data = array();
        $this->views_path = __TEMPLET_FOLDER__ . "/zcq/zhongdian_pro/";

        //初始化url
        $this->data['url_edit'] = site_url($this->controller_name . "/edit");
        $this->data['url_add'] = site_url($this->controller_name . "/add");
        $this->data['url_view'] = site_url($this->controller_name . "/view");
        $this->data['url_deleteone'] = site_url($this->controller_name . "/deleteone");
        $this->data['url_delete'] = site_url($this->controller_name . "/delete");

    }

    /**
     * 列表
     */
    public function index()
    {
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
        //企业名称/项目名称/拟投资国家/联系人/手机
        $search_val['name'] = isset($get['search_name']) ? $get['search_name'] : "";
        if ($search_val['name'] != "") {
            $search['name'] = $search_val['name'];
        }
        //镇区选择
        $search_val['town'] = isset($get['search_town']) ? $get['search_town'] : "";
        if ($search_val['town'] != "") {
            $search['town'] = $search_val['town'];
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

        //结果保存在$this->data中
        $data = $this->model->getList($pageindex, $pagesize, $search);
        //搜索变量
        $data['search_val'] = $search_val;

        //镇区list
        $data['town'] = $this->ccd->GetListFromTypeidAndPid(6, 3145);

        //设置每个模型的镇区名字
        foreach ($data['list'] as $k => $v) {
            //默认是
            $data['list'][$k]['town_name'] = "未选择";
            foreach ($data['town'] as $each) {
                if ($v['zhenqu_id'] == $each['id']) {
                    //$data['list'][$k]就是$v
                    $data['list'][$k]['town_name'] = $each['name'];
                }
            }
        }

        //合并数组
        $this->data = array_merge($data, $this->data);
        $this->load->view($this->views_path . "/list", $this->data);
    }

    /**
     * 增加页面
     */
    public function add()
    {

        //显示到页面的一些变量
        $data['type'] = "add";
        $data['title'] = "新增";
        $data['form_url'] = site_url($this->controller_name . "/doadd");
        //没有数据的模型
        $model = $this->model->getFields();
        //初始化一些默认数据
        if ($this->debug) {
            //测试用
            foreach ($model as $k => $v) {
                if ($model[$k] == "") {
                    //不需要初始化bylk，2016年9月9日17:23:32
                    // $model[$k] = 100 * rand(153613130, 153613631);
                    $model[$k] = '';
                }
            }
        }

        //镇区选择
        $data['town'] = $this->ccd->GetListFromTypeidAndPid(6, 3145);

        $data['model'] = $model;
        $this->load->view($this->views_path . "/add_or_edit", $data);
    }

    public function doadd()
    {
        //$post = $this->input->post();
        $model = $this->getmysqlmodel();

        //创建人ID，即管理员ID
        $model['create_sysuserid'] = admin_id();
        //创建时间
        $model['createtime'] = time();
        //大于0为已删除  为0未删除
        $model['del_sysuserid'] = 0;
        //默认为0为未删除
        $model['isdel'] = '0';

        $newid = $this->model->insert($model);

        if ($newid > 0) {
            //插入成功，然后关联到用户就更新userid
            //$model['id'] = $newid;
            //$this->handleNew($model);

            echo "<script>
                top.tip_show('添加数据成功',1,500);
			    top.topManager.closePage();
			    //238是重点项目管理列表
			    top.topManager.reloadPage('238');
			</script>";
        } else {
            showmessage("添加数据失败", $this->controller_name . "/index", 3, 0);
        }

    }

    /**
     * 修改页面
     */
    public function edit()
    {
        //id
        $id = verify_id($this->input->get_post("id"));
        //获得模型
        $model = $this->model->getModel($id);

        //显示到页面的一些变量
        $data['type'] = "edit";
        $data['title'] = "修改";
        $data['form_url'] = site_url($this->controller_name . "/doedit");

        //镇区选择
        $data['town'] = $this->ccd->GetListFromTypeidAndPid(6, 3145);

        //模型
        $data['model'] = $model;
        $this->load->view($this->views_path . "/add_or_edit", $data);
    }

    public function doedit()
    {
        $post = $this->input->post();
        $model = $this->getmysqlmodel();
        $model['id'] = $post['id'];

        //修改人ID，即管理员ID
        $model['update_sysuserid'] = admin_id();
        //修改时间
        $model['updatetime'] = time();

        $num = $this->model->update($model);
        if ($num > 0) {
            echo "<script>
                top.tip_show('修改数据成功',1,500);
			    top.topManager.closePage();
			    //238是列表页面的id
			    top.topManager.reloadPage('238');
			</script>";
        } else {
            showmessage("修改数据失败", $this->controller_name . "/index", 3, 0);
        }
        exit();
    }

    /**
     * 查看页面
     */
    public function view()
    {
        //id
        $id = verify_id($this->input->get_post("id"));
        //获得模型
        $model = $this->model->getModel($id);

        //显示到页面的一些变量
        $data['type'] = "view";
        $data['title'] = "修改";
        $data['form_url'] = $this->data['url_view'];

        //镇区选择
        $data['town'] = $this->ccd->GetListFromTypeidAndPid(6, 3145);
        //模型
        $data['model'] = $model;
        $this->load->view($this->views_path . "/add_or_edit", $data);
    }

    /**
     * 删除一条记录
     */
    public function deleteone()
    {
        $id = $this->input->get_post("id");

        //返回的json
        $re = array();

        $model['id'] = $id;
        $model['deltime'] = time();
        $model['del_sysuserid'] = admin_id();
        $model['isdel'] = '1';

        $rows = $this->model->update($model);
        if ($rows == 0) {
            $re['success'] = false;
            $re['msg'] = "删除项（id:{$id}）失败！";
        } else {
            $re['success'] = true;
            $re['msg'] = "删除项（id:{$id}）成功！";
        }

        echo json_encode($re);
    }

    /**
     * 删除多条记录
     */
    public function delete()
    {
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $arr = explode(",", $idlist);
        $count = 0;//记录操作失败的数量
        foreach ($arr as $id) {
            //$model = $this->zixun->getModel($id);
            $model['id'] = $id;
            $model['deltime'] = time();
            $model['del_sysuserid'] = admin_id();
            $model['isdel'] = '1';

            $rows = $this->model->update($model);
            if ($rows == 0) {
                $count++;
            }
        }

        echo $count;
        exit();
    }

    public function test()
    {

    }
}