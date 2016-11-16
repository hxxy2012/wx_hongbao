<?php

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

class brand_product extends MY_Controller {

    public $upload_path = '';
    public $upload_save_url = '';
    public $brand_id = '';

    function brand_product() {
        parent::__construct();
        $this->load->model("M_zx_brand_product", "brand_product");

        $this->load->library('MyEditor');
        $this->upload_path = __ROOT__ . "/data/upload/editor/"; // 编辑器上传的文件保存的位置
        $this->upload_save_url = base_url() . "/data/upload/editor/"; //编辑器上传图片的访问的路径	
        $this->brand_id = $this->input->get('brand_id');
    }

    function index() {

        $pageindex = $this->input->get_post("per_page");
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $get = $this->input->get();
        $search = array();
        $search_title = empty($get["search_title"]) ? "" : trim($get["search_title"]);
        if ($search_title != "") {
            $search["pro_name"] = $search_title;
            $search_val["search_title"] = $search_title;
        } else {
            $search_val["search_title"] = "";
        }
        $search["brandid"] = $this->brand_id;
        $orderby["create_time"] = "desc";
        $data = array();
        $data = $this->brand_product->GetInfoList($pageindex, 10, $search, $orderby);
        $data['brand_id'] = $this->brand_id;
        $data["search_val"] = $search_val;
        $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_product", $data);
    }

    function add() {
        $data = array();
        $admin_auth_cookie = $_COOKIE['admin_auth'];
        $usersession = $this->session->userdata('session_id');
        $data['admin_auth_cookie'] = $admin_auth_cookie;
        $data['usersession'] = $usersession;
        $data['brand_id'] = $this->brand_id;
        //默认显示1级
        $cat1 = $this->brand_product->top_category($this->brand_id); //1级分类
        //默认显示2级
        if (!empty($cat1[0]['id'])) {
            $cat2 = $this->brand_product->second_category($cat1[0]['id']); //2级分类
        }
        $data['cat1'] = $cat1;
        $data['cat2'] = empty($cat2)?"":$cat2;

        $post = $this->input->post();
        if (is_array($post)) {
            $model = $this->getmysqlmodel();

            //上传的文件配置
            $dir = 'data/upload/brand_product/' . date('Ym') . '/';
            mkdirs($dir);
            $config['upload_path'] = $dir;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '200';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);
            //封面图上传
            if ($_FILES['mysql_thumb']['error'] == 0) {
                if ($this->upload->do_upload('mysql_thumb')) {
                    $upload_data = $this->upload->data();
                    $model['thumb'] = $dir . $upload_data['file_name'];
                }
            }
            $model['isdel'] = 0;
            $model['common_system_user_id'] = admin_id();
            $model['guid'] = create_guid();
            $model['istop'] = strtotime($model['istop']);
            $model['brandid'] = $this->brand_id;
            $model['orderby'] = $model['orderby'] + 0;
            $guid = $post['guid'];

            if (!empty($guid)) {
                //修改
                $model['edit_time'] = date('Y-m-d H:i:s', time());
                $model['guid'] = $guid;
                $this->brand_product->update($model);
            } else {
                //添加
                $model['create_time'] = date('Y-m-d H:i:s', time()); //创建时间
                $this->brand_product->add($model);
            }
            echo "<script>
			parent.tip_show('保存成功',1,1000);
                     
                   //   var url = \"" . base_url() . "admin.php/brand_product/index.shtml\";
			//parent.flushpage(url);	
                       top.topManager.closePage();
			</script>";

            exit();
        } else {
            $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_product_add", $data);
        }
    }

    function edit() {
        $data = array();
        $admin_auth_cookie = $_COOKIE['admin_auth'];
        $usersession = $this->session->userdata('session_id');
        $data['admin_auth_cookie'] = $admin_auth_cookie;
        $data['usersession'] = $usersession;
        $data['brand_id'] = $this->brand_id;


        $get = $this->input->get();
        if (!empty($get["id"])) {
            $model = $this->brand_product->GetModel($get["id"]);
            $model['istop'] = date('Y-m-d H:i:s', $model['istop']);
            $data["model"] = $model;
            $cat1 = $this->brand_product->top_category($this->brand_id); //1级分类
            $cat2 = $this->brand_product->second_category($model['proclass']); //2级分类
            $data['cat1'] = $cat1;
            $data['cat2'] = $cat2;
        }
        $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_product_add", $data);
    }

    function count() {
        $get = $this->input->get();
        $dzzid = $get["dzzid"];
        if ($dzzid != "") {
            echo $this->brand_product->dzz_count($dzzid);
        } else {
            echo "999";
        }
    }

    function del() {
        $get = $this->input->get();
        $idlist = $get["idlist"];
        if ($idlist != "") {
            $arr = explode(",", $idlist);
            foreach ($arr as $v) {
                $this->brand_product->del($v);
            }
        }
    }

    //ajax获取二级分类函数
    function getsecondCategory() {
        $id = $this->input->post('id');
        $res = $this->brand_product->second_category($id);
        echo json_encode($res);
    }

}

?>