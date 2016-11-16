<?php
/**
 * 走出去数据管理
 * User: 嘉辉
 * Date: 2016-08-08
 * Time: 14:40
 */

/**
 * Class zcq_data_manager
 * @property CI_Input $input 输入类
 *
 * @property m_zcq_datamanage $datamanage 走出去数据模型
 * @property M_user $user 用户模型
 * @property M_zcq_import_excel $excel excel模型
 * @property M_common $M_common 公用模型
 *
 * @property Common_upload $common_upload
 */
class zcq_datamanage extends MY_Controller
{
    private $upload_path = "";
    private $upload_save_url = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_common');
        $this->load->model('m_zcq_datamanage', 'datamanage');
        $this->load->model('M_user', 'user');
        $this->load->model('M_zcq_import_excel', 'excel');//excel模型

        // 上传excel的位置
        $this->upload_path = __ROOT__ . "/data/upload/editor/";
        //编辑器上传图片的访问的路径
        $this->upload_save_url = base_url() . "/data/upload/editor/";
    }

    /**
     * 数据库添加记录成功后，处理通知相关事务
     * @param array $model
     */
    private function handleNew(&$model)
    {
        //当境内企业名称跟会员表相同时绑定会员ID
        $user_model = $this->datamanage->getRelatedUser($model['company']);
        if (!empty($user_model)) {
            //找到可以绑定

            //数据库更新该记录的userid
            $newmodel['userid'] = $user_model['uid'];
            $newmodel['id'] = $model['id'];
            $this->datamanage->update($newmodel);

            //执行通知操作
            //
            //发送站内信
            if ($user_model['usertype']!='45063') {
                //企业用户才发送站内信
                return;
            }
            $title = "数据需要核对，编号为{$model['id']}";
            $content = "您好，您有一条数据需要核对，编号为{{$model['id']}}，请到位置：\"会员后台 > 资料核对\" 栏目，<a href='【adminx/zcq_datamanage/view】?id={$model['id']}'>查看</a>。";
            $sess = $this->parent_getsession();
            $re = send_zhan_mail($user_model['uid'], '', $title, $content, '0', admin_id(), $sess["session_id"]);
            if ($re == 'ok') {
            }
        } else {
            //没有找到
        }
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
        //境内企业名称/联系人/手机
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

        //排序
        $orderby = array("id" => "desc");

        $data = $this->datamanage->getList($pageindex, $pagesize, $search, $orderby);
        //搜索变量
        $data['search_val'] = $search_val;

        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/datamanage/list", $data);
    }

    /**
     * 增加页面
     */
    public function add()
    {
        $test = false;

        //显示到页面的一些变量
        $data['type'] = "add";
        $data['title'] = "新增";
        $data['form_url'] = site_url("zcq_datamanage/doadd");
        //没有数据的模型
        $model = $this->datamanage->getFields();


        //初始化一些默认数据
        $model['username'] = "";

        $model['baobiao_time'] = date("Y-m-d", time());
        $model['tianbao_time'] = date("Y-m-d", time());
        $model['huobi'] = "0.00";
        $model['ziyou'] = "0.00";
        $model['yinhang'] = "0.00";
        $model['other'] = "0.00";
        $model['shiwu'] = "0.00";
        $model['wuxing'] = "0.00";
        $model['xinzeng'] = "0.00";
        $model['xinzeng_zhai'] = "0.00";
        $model['zonge'] = "0.00";


        if ($test) {
            //测试用
            foreach ($model as $k => $v) {
                if ($model[$k] == "") {
                    $model[$k] = rand(13055510, 13055599) * 1000;
                } elseif ($model[$k] == "0.00") {
                    $model[$k] = rand(100, 200);
                }
            }
        }


        $data['model'] = $model;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/datamanage/add_or_edit", $data);
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
        //默认没有阅读
        $model['user_isread'] = '0';


        $newid = $this->datamanage->insert($model);

        if ($newid > 0) {
            //插入成功，然后关联到用户就更新userid
            $model['id'] = $newid;
            $this->handleNew($model);

            echo "<script>
                top.tip_show('添加走出去数据成功',1,500);
			    top.topManager.closePage();
			    //234是管理列表
			    top.topManager.reloadPage('262');
			</script>";
        } else {
            showmessage("添加走出去数据失败", "zcq_fuwu_zixun/index", 3, 0);
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
        $model = $this->datamanage->getModel($id);

        //显示到页面的一些变量
        $data['type'] = "edit";
        $data['title'] = "修改";
        $data['form_url'] = site_url("zcq_datamanage/doedit");

        //模型
        //格式化时间
        $model['baobiao_time'] = date("Y-m-d", strtotime($model['baobiao_time']));
        $model['tianbao_time'] = date("Y-m-d", strtotime($model['tianbao_time']));
        //去掉后面多余的0
        $model['huobi'] = floatval($model['huobi']);
        $model['ziyou'] = floatval($model['ziyou']);
        $model['yinhang'] = floatval($model['yinhang']);
        $model['other'] = floatval($model['other']);
        $model['shiwu'] = floatval($model['shiwu']);
        $model['wuxing'] = floatval($model['wuxing']);
        $model['xinzeng'] = floatval($model['xinzeng']);
        $model['xinzeng_zhai'] = floatval($model['xinzeng_zhai']);
        $model['zonge'] = floatval($model['zonge']);


        $data['model'] = $model;
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/datamanage/add_or_edit", $data);
    }

    public function doedit()
    {
        $post = $this->input->post();
        $model = $this->getmysqlmodel();

        if (isset($post['change_userid']) && $post['change_userid'] == '1') {
            //标志重新修改了用户id
            //验证修改的用户id
            $user_model = $this->user->GetModel($model['userid']);
            if (empty($user_model)) {
                showmessage("修改的用户id不存在", "zcq_datamanage/index", 3, 0);
                exit();
            }
        }


        //该条数据管理的id
        $model['id'] = $post['id'];

        //时间插入处理
        //$model['sheli_date'] = strtotime($model['sheli_date']);

        //修改人ID，即管理员ID
        $model['update_sysuserid'] = admin_id();
        //修改时间
        $model['updatetime'] = time();

        $num = $this->datamanage->update($model);
        if ($num > 0) {

            //发送站内信
            $title = "走出去数据需要重新核对，编号为{$model['id']}";
            $content = "您好，您的走出去数据被管理员修改，请核对，编号为{{$model['id']}}，请到位置：\"会员后台 > 资料核对\" 栏目，<a href='【adminx/zcq_datamanage/view】?id={$model['id']}'>查看</a>。";
            $sess = $this->parent_getsession();
            $re = send_zhan_mail($model['userid'], '', $title, $content, '0', admin_id(), $sess["session_id"]);

            echo "<script>
                top.tip_show('修改走出去数据成功',1,500);
			    top.topManager.closePage();
			    //262是列表
			    top.topManager.reloadPage('262');
			</script>";
        } else {
            showmessage("修改走出去数据失败！！！", "zcq_datamanage/index", 3, 0);
        }
        exit();
    }

    /**
     * 删除
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

            $rows = $this->datamanage->update($model);
            if ($rows == 0) {
                $count++;
            }
        }

        echo $count;
        exit();
    }

    /**
     * 企业用户选择页面
     */
    public function select()
    {
        $pageindex = $this->input->get_post("per_page");

        if ($pageindex <= 0) {
            $pageindex = 1;
        }

        $get = $this->input->get();
        /* 搜索相关,采用get */
        //初始化显示到页面时的搜索变量
        $search_val = array();
        //数据库的where
        $search = array();
        //搜索名称
        $search_val['name'] = isset($get['search_name']) ? $get['search_name'] : "";
        if ($search_val['name'] != "") {
            $search['name'] = $search_val['name'];
        }
        //搜索机构服务类型
        $search_val['server_type'] = isset($get['server_type']) ? $get['server_type'] : array();
        if (!empty($get['server_type'])) {
            //转化数组为字符串,在数据库中查询
            $search['server_type'] = $get['server_type'];
        }

        //排序
        $search['usertype'] = 45063;
        $order_by = array("uid" => "desc");

        //查企业列表
        $data = $this->user->getList($pageindex, 15, $search, $order_by);
        //搜索变量
        $data['search_val'] = $search_val;

        //合并
        //$data = array_merge($this->data, $data);
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/datamanage/select", $data);
    }

    /**
     * 导入页面
     */
    public function import()
    {
        $data = array();
        $this->load->view(__TEMPLET_FOLDER__ . "/zcq/datamanage/import", $data);
    }

    /**
     * 处理导入excel
     */
    public function doimport()
    {
        //文件可能比较大
        //ini_set('memory_limit', '1024M');

        /*上传excel*/
        //允许上传格式
        $fileTypes = array('xls', 'xlsx');
        $this->load->library("common_upload");
        if ($_FILES["excel"]["type"] == "application/vnd.ms-excel") {
            $inputFileType = 'Excel5';
        } elseif ($_FILES["excel"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $inputFileType = 'Excel2007';
        } else {
            showmessage("导入的excel类型错误，请检查您上传的excel是否为从系统中下载的模板", "zcq_datamanage/import", 3, 0);
            exit;
        }
        $fujian_path = $this->common_upload->upload_path_ym($this->upload_path, 'excel', implode("|", $fileTypes));
        if (!$fujian_path) {
            showmessage("导入excel失败，请检查您上传的excel的格式是否错误", "zcq_datamanage/import", 3, 0);
            exit;
        }

        // echo $fujian_path;exit;

        //读取excel数据
        $objPHPExcel = $this->excel->getObjPHPExcel($inputFileType, $fujian_path);
        //获得第一张表
        $sheet = $objPHPExcel->getSheet(0);
        //取得总行数
        $highestRow = $sheet->getHighestRow();
        //取得总列数
        $highestColumn = $sheet->getHighestColumn();

        //AA转化为数字27
        $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);

        if ($highestColumn != 27) {//上传的excel列数不对应
            //删除文件
            @unlink($fujian_path);
            showmessage("导入失败，请严格按照导入的模板填写数据", "zcq_datamanage/import", 3, 0);
            exit;
        }

        //统计导入失败条数
        $countFailed = 0;
        $sheet = $objPHPExcel->getActiveSheet();
        //从第二行开始读取数据
        for ($j = 2; $j <= $highestRow; $j++) {
            $model = array();//获取数据
            //从第1列开始读取数据,
            for ($k = 0; $k < $highestColumn; $k++) {
                //0转为A，1转B
                $col = PHPExcel_Cell::stringFromColumnIndex($k);
                $str = $sheet->getCell("{$col}{$j}")->getValue();//读取单元格
                $model[] = mb_convert_encoding($str, 'UTF-8');//转换成utf8
            }

            //对导入的数据进行处理(添加到数据库中)
            $result = $this->doModel($model);

            if (!$result) {
                //添加失败，失败条数+1
                $countFailed++;
            }

            // var_dump($model);exit;
        }

        //删除文件
        @unlink($fujian_path);
        $counttotal = $highestRow - 1;//导入总数
        $countSucc = $counttotal - $countFailed;//成功条数
        $message = "导入总数为：" . $counttotal . "条，" . $countSucc . '条成功、' . $countFailed . '条失败。';
        //echo $message;
        showmessage($message, "zcq_datamanage/import", 3, 1);
    }

    /**
     * 对model数据进行插入到数据库中并且返回result结果，成功or失败
     * @param  array $model 导入excel的数组
     * @return bool
     */
    private function doModel($model)
    {
//0 A报表时间 baobiao_time
//1 B报部状态 baobu_status
//2 C报表状态 baobiao_status
//3 D地区 addr
//4 E境内企业名称 company
//5 F境外项目编码 company2_code
//6 G境外项目名称 company2
//7 H申请事项 shenqing
//8 I业务类型 yewu_type
//9 J国别 guobie
//10 K投资类型 touzi_type
//11 L投资方式 touzi_fangshi
//12 M中方实际投资总额 zonge
//13 N其中：货币投资 huobi
//14 O其中：自有资金 ziyou
//15 P其中：银行贷款 yinhang
//16 Q其中：其他 other
//17 R其中：实物投资  shiwu
//18 S其中：无形资产投资 wuxing
//19 T其中：新增股权 xinzeng
//20 U其中：新增债务工具 xinzeng_zhai
//21 V项目负责人 fuzeren
//22 W联系人 linkman
//23 X联系人手机 tel
//24 Y填报人 tianbaoren
//25 Z填报时间 tianbao_time
//26 AA备注 beizhu

        $modelAdd = array(
            'baobiao_time' => date("Y-m-d H:i:s", strtotime($model[0])),
            'baobu_status' => $model[1],
            'baobiao_status' => $model[2],
            'addr' => $model[3],
            'company' => $model[4],
            'company2_code' => $model[5],
            'company2' => $model[6],
            'shenqing' => $model[7],
            'yewu_type' => $model[8],
            'guobie' => $model[9],
            'touzi_type' => $model[10],
            'touzi_fangshi' => $model[11],
            'zonge' => $model[12],
            'huobi' => $model[13],
            'ziyou' => $model[14],
            'yinhang' => $model[15],
            'other' => $model[16],
            'shiwu' => $model[17],
            'wuxing' => $model[18],
            'xinzeng' => $model[19],
            'xinzeng_zhai' => $model[20],
            'fuzeren' => $model[21],
            'linkman' => $model[22],
            'tel' => $model[23],
            'tianbaoren' => $model[24],
            'tianbao_time' => date("Y-m-d H:i:s", strtotime($model[25])),//excelTime($model[25],true),
            'beizhu' => $model[26]
        );

        //创建人ID，即管理员ID
        $modelAdd['create_sysuserid'] = admin_id();
        //创建时间
        $modelAdd['createtime'] = time();
        //大于0为已删除  为0未删除
        $modelAdd['del_sysuserid'] = 0;
        //默认没有阅读
        $modelAdd['user_isread'] = '0';


        $modelRst = $this->M_common->insert_one($this->datamanage->table, $modelAdd);
        if ($modelRst['affect_num'] <= 0) {
            //插入失败
            return false;
        } else {
            //插入成功，之后尝试关联一下用户，关联到就更新
            $modelAdd['id'] = $modelRst['insert_id'];
            $this->handleNew($modelAdd);

            return true;
        }
    }

    public function readfile(){
        $file = $this->input->get("file");
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: ". filesize($file));
        readfile($file);
    }

    /**
     * 测试用
     */
    public function test()
    {
        //echo strtotime("201608");

    }
}