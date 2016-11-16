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
 * @property m_zcq_pro_guanli $guanli 服务咨询模型
 * @property M_common_category_data $ccd 为了获得镇区
 *
 */
class zcq_pro_guanli extends MY_Controller
{
    private $upload_path = "";
    private $upload_save_url = "";

    /**
     * @var bool 是否调试模式
     */
    private $debug = false;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_zcq_pro_guanli', 'guanli');
        $this->load->model('M_common_category_data','ccd');
        // 编辑器上传的文件保存的位置
        $this->upload_path = __ROOT__ . "/data/upload/editor/";
        //编辑器上传图片的访问的路径
        $this->upload_save_url = base_url() . "/data/upload/editor/";
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
        //国内投资主体名称/境外企业名称/联系人/手机
        $search_val['name'] = isset($get['search_name']) ? $get['search_name'] : "";
        if ($search_val['name'] != "") {
            $search['name'] = $search_val['name'];
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


        $data = $this->guanli->getList($pageindex, $pagesize, $search);
        //搜索变量
        $data['search_val'] = $search_val;

        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/pro_guanli/list", $data);
    }

    /**
     * 增加页面
     */
    public function add()
    {

        //显示到页面的一些变量
        $data['type'] = "add";
        $data['title'] = "新增";
        $data['form_url'] = site_url("zcq_pro_guanli/doadd");
        //没有数据的模型
        $model = $this->guanli->getFields();

        //初始化一些默认数据
        $model['sheli_date'] = date("Y-m-d", time());

        if ($this->debug) {
            //测试用
            foreach ($model as $k => $v) {
                if ($model[$k] == "") {
                    $model[$k] = 100 * rand(153613130, 153613631);
                }
            }
            $model['tel'] = "0755-88880000";

        }

        //镇区选择
        $data['town'] = $this->ccd->GetListFromTypeidAndPid(6,3145);

        $data['model'] = $model;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/pro_guanli/add_or_edit", $data);
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

        $newid = $this->guanli->insert($model);

        if ($newid > 0) {
            //插入成功，然后关联到用户就更新userid
            //$model['id'] = $newid;
            //$this->handleNew($model);

            echo "<script>
                top.tip_show('添加数据成功',1,500);
			    top.topManager.closePage();
			    //237是项目管理列表
			    top.topManager.reloadPage('237');
			</script>";
        } else {
            showmessage("添加数据失败", "zcq_pro_guanli/index", 3, 0);
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
        $model = $this->guanli->getModel($id);

        //显示到页面的一些变量
        $data['type'] = "edit";
        $data['title'] = "修改";
        $data['form_url'] = site_url("zcq_pro_guanli/doedit");


        //格式化时间
        $model['sheli_date'] = date("Y-m-d", $model['sheli_date']);

        //镇区选择
        $data['town'] = $this->ccd->GetListFromTypeidAndPid(6,3145);
        //模型
        $data['model'] = $model;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/pro_guanli/add_or_edit", $data);
    }

    public function doedit()
    {
        $post = $this->input->post();
        $model = $this->getmysqlmodel();
        $model['id'] = $post['id'];

        //时间插入处理,sheli_date是int类型的，所以要做处理
        $model['sheli_date'] = strtotime($model['sheli_date']);

        //修改人ID，即管理员ID
        $model['update_sysuserid'] = admin_id();
        //修改时间
        $model['updatetime'] = time();

        $num = $this->guanli->update($model);
        if ($num > 0) {
            echo "<script>
                top.tip_show('修改数据成功',1,500);
			    top.topManager.closePage();
			    //237是列表
			    top.topManager.reloadPage('237');
			</script>";
        } else {
            showmessage("修改数据失败", "zcq_pro_guanli/index", 3, 0);
        }
        exit();
    }

    /**
     * 查看页面
     */
    public function view(){
        //id
        $id = verify_id($this->input->get_post("id"));
        //获得模型
        $model = $this->guanli->getModel($id);

        //显示到页面的一些变量
        $data['type'] = "view";
        $data['title'] = "修改";
        $data['form_url'] = site_url("zcq_pro_guanli/view");

        //格式化时间
        $model['sheli_date'] = date("Y-m-d", $model['sheli_date']);

        //镇区选择
        $data['town'] = $this->ccd->GetListFromTypeidAndPid(6,3145);
        //模型
        $data['model'] = $model;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/pro_guanli/add_or_edit", $data);
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

        $rows = $this->guanli->update($model);
        if ($rows==0) {
            $re['success'] = false;
            $re['msg'] = "删除项（id:{$id}）失败！";
        }else{
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

            $rows = $this->guanli->update($model);
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