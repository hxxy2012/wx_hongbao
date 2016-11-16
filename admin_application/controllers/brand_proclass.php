<?php

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

class brand_proclass extends MY_Controller {

    public $brand_id = '';
    public $upload_path = '';
    public $upload_path_save = '';    

    function brand_proclass() {
        parent::__construct();

                
        $this->load->model("M_zx_brand_proclass", "brand_proclass");

        $this->load->library('MyEditor');
        $this->upload_path = __ROOT__ . "/data/upload/editor/"; // 编辑器上传的文件保存的位置
        $this->upload_save_url = base_url() . "/data/upload/editor/"; //编辑器上传图片的访问的路径
        $this->upload_path_save = "data/upload/editor/";//上传产品LOGO路径
        $this->brand_id = $this->input->get('brand_id');
        $pid = $this->input->get('pid');
        $this->pid = isset($pid) ? $pid : 0;
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
            $search["title"] = $search_title;
            $search_val["search_title"] = $search_title;
        } else {
            $search_val["search_title"] = "";
        }
        $search["brandid"] = $this->brand_id;
        $search["pid"] = $this->pid;
        $orderby["create_time"] = "desc";
        $data = array();
        $data = $this->brand_proclass->GetInfoList($pageindex, 10, $search, $orderby);
        $data["search_val"] = $search_val;
        $data['brand_id'] = $this->brand_id;
        $data["pid"] = $this->pid;
        $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_proclass", $data);
    }

    function add() {
        $data = array();
        $admin_auth_cookie = $_COOKIE['admin_auth'];
        $usersession = $this->session->userdata('session_id');
        $data['admin_auth_cookie'] = $admin_auth_cookie;
        $data['usersession'] = $usersession;
        $data['brand_id'] = $this->brand_id;
        $data['pid'] = $this->pid;
        $post = $this->input->post();
        if (is_array($post)) {
            $model = $this->getmysqlmodel();

            $model['isdel'] = 0;
            $model['common_system_userid'] = admin_id();
            $model['brandid'] = $this->brand_id;
            $id = $post['id'];
            
            $filepath = $this->upload_path."image/".date("Ymd")."/";
            $filepath2 = $this->upload_path_save."image/".date("Ymd")."/";
            if(!is_dir($filepath)){
            	mkdir($filepath);
            }
            
            if (!empty($id)) {
                //修改
                $old_model = $this->brand_proclass->GetModel($id);
                $model['edit_time'] = date('Y-m-d H:i:s', time());
                $model['id'] = $id;
                $model["logo"] = $old_model["logo"];
                $this->load->library("common_upload");
                $logo = $this->common_upload->upload_path(
                		$filepath,
                		'logo',
                		'png|jpg|gif|bmp'
                );
                if($logo!=""){
                	$model["logo"] = $filepath2.$logo;
                }                
                $this->brand_proclass->update($model);
            } else {            
                //添加
            	$this->load->library("common_upload");
            	$logo = $this->common_upload->upload_path(
            			$filepath,
            			'logo',
            			'png|jpg|gif|bmp'
            	);
            	if($logo!=""){
            		$logo = $filepath2.$logo;
            	}
            	$model["logo"] = $logo;                
                $model['create_time'] = date('Y-m-d H:i:s', time()); //创建时间
                $this->brand_proclass->add($model);
            }
            echo "<script>
			parent.tip_show('保存成功',1,1000);
                     
                   //   var url = \"" . base_url() . "admin.php/brand_proclass/index.shtml\";
			//parent.flushpage(url);	
                       top.topManager.closePage();
			</script>";

            //   header("Location:".site_url("brand/index"));
            exit();
        } else {
        	$model["logo"] = "";
        	$data["model"] = $model;        	
            $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_proclass_add", $data);
        }
    }

    function edit() {
        $data = array();
        $admin_auth_cookie = $_COOKIE['admin_auth'];
        $usersession = $this->session->userdata('session_id');
        $data['admin_auth_cookie'] = $admin_auth_cookie;
        $data['usersession'] = $usersession;
        $data['brand_id'] = $this->brand_id;
        $data['pid'] = $this->pid;
        $get = $this->input->get();
        $model = $this->brand_proclass->GetModel($get["id"]);
        $data["model"] = $model;
        $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_proclass_add", $data);
    }
    function dellogo(){
    	$get = $this->input->get();
    	$id = $get["id"];
    	if($id>0){
    		$model = $this->brand_proclass->GetModel($id);
    		if($model["logo"]!=""){
	    		@unlink(realpath($model["logo"]));
    		}
    		$model["logo"] = "";
    		$this->brand_proclass->update($model);
    	}   	
    }

    function count() {
        $get = $this->input->get();
        $dzzid = $get["dzzid"];
        if ($dzzid != "") {
            echo $this->brand_proclass->dzz_count($dzzid);
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
                $this->brand_proclass->del($v);
            }
        }
    }

}

?>