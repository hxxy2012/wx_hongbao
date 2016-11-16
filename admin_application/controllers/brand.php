<?php

if (!defined('BASEPATH')) {
    exit('Access Denied');
}

class brand extends MY_Controller {

    public $upload_path = '';
    public $upload_save_url = '';

    function brand() {
        parent::__construct();
        $this->load->model("M_zx_brand", "brand");

        $this->load->library('MyEditor');
        //拼音
        $this->load->library('pin');
        $this->upload_path = __ROOT__ . "/data/upload/editor/"; // 编辑器上传的文件保存的位置
        $this->upload_save_url = base_url() . "/data/upload/editor/"; //编辑器上传图片的访问的路径		

        $this->load->library('Website_category_helper');
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
        $orderby["create_time"] = "desc";
        $data = array();
        $data = $this->brand->GetInfoList($pageindex, 10, $search, $orderby);
        $data["search_val"] = $search_val;
        $this->load->view(__TEMPLET_FOLDER__ . "/views_brand", $data);
    }

    function add() {
        $data = array();
        $admin_auth_cookie = $_COOKIE['admin_auth'];
        $usersession = $this->session->userdata('session_id');
        $data['admin_auth_cookie'] = $admin_auth_cookie;
        $data['usersession'] = $usersession;
        $post = $this->input->post();
        if (is_array($post)) {
            $model = $this->getmysqlmodel();
            //上传的文件配置
            $dir = 'data/upload/brand/' . date('Ym') . '/';
            mkdirs($dir);
            $config['upload_path'] = $dir;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '200';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);
            //logo上传
            if ($_FILES['mysql_logo']['error'] == 0) {
                if ($this->upload->do_upload('mysql_logo')) {
                    $upload_data = $this->upload->data();
                    $model['logo'] = $dir . $upload_data['file_name'];
                }
            }

            //品牌页页头图
            if ($_FILES['mysql_brand_pic']['error'] == 0) {
                if ($this->upload->do_upload('mysql_brand_pic')) {
                    $upload_data = $this->upload->data();
                    $model['brand_pic'] = $dir . $upload_data['file_name'];
                }
            }

            //用户页页头图
            if ($_FILES['mysql_user_pic']['error'] == 0) {
                if ($this->upload->do_upload('mysql_user_pic')) {
                    $upload_data = $this->upload->data();
                    $model['user_pic'] = $dir . $upload_data['file_name'];
                }
            }
            //flv视频 //配置
            $config['upload_path'] = $dir;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);
            //flv视频上传
            if ($_FILES['flv']['error'] == 0) {
                $this->upload->set_allowed_types('flv');
                $this->upload->set_max_filesize(15000); //15m
                if ($this->upload->do_upload('flv')) {
                    $upload_data = $this->upload->data();
                    $model['flv'] = $dir . $upload_data['file_name'];
                } else {
                    echo $this->upload->display_errors();
                }
            }

            $model['guid'] = create_guid();

            $guid = $post['guid'];
            //批量上传的滚动图片
            if (!empty($model['ad_pic'])) {
                if (empty($guid)) {
                    //新增
                    $ad_picarr = array_filter(explode("###", $model['ad_pic']));
                    if (!empty($ad_picarr)) {
                        foreach ($ad_picarr as $num => $addr) {
                            //新增时批量添加上传图片的链接地址(空链接)
                            $picmodel['guid'] = $model['guid'];
                            $picmodel['pic_id'] = $num;
                            $picmodel['link'] = '';
                            $this->brand->add_pic_link($picmodel);
                        }
                    }

                    $model['ad_pic'] = json_encode($ad_picarr);
                } else {
                    //修改
                    $res = $this->db->select('ad_pic')->get_where('zx_brand', array('guid' => $guid))->result();
                    $arr = json_decode($res[0]->ad_pic);
                    $count_arr = count($arr);
                    $picarr = array_filter(explode("###", $model['ad_pic']));
                  
                    foreach ( $picarr as $num => $addr) {
                        $arr[] = $addr;
                          //批量添加上传图片的链接地址(空链接)
                            $picmodel['guid'] =$guid;
                            $picmodel['pic_id'] = $count_arr+$num;
                            $picmodel['link'] = '';
                            $this->brand->add_pic_link($picmodel);
                    }
//                      print_r($arr);exit;
                    $model['ad_pic'] = json_encode($arr);
                }
            }
            $model['isdel'] = 0;
            $model['common_system_userid'] = admin_id();
            $model['istop'] = strtotime($model['istop']);

            $model['pinyin'] = @$this->pin->Pinyin($model["title"], 'UTF8');

            if (!empty($guid)) {
                //修改
                $model['edit_time'] = date('Y-m-d H:i:s', time());
                $model['guid'] = $guid;
                if (empty($model['ad_pic'])) {
                    unset($model['ad_pic']);
                }
                /*
                //域名自定义
                //判断拼音是否已存在，如果不存就则写入,否则加随机数
                $yuming = $model["yuming"];
                while ($this->brand->count("yuming='" . $yuming . "' and isdel='0'") > 0) {
                    $yuming.=rand(1, 100);
                }
                $model["yuming"] = $yuming;
				*/


                $this->brand->update($model);
                //修改时批量添加上传图片的链接地址
                if (!empty($post['ad_pic_link'])) {
                    foreach ($post['ad_pic_link'] as $num_up => $addr_up) {
                        $picmodel_up['link'] = $addr_up;
                        $this->brand->update_pic_link($picmodel_up,$guid,$num_up);
                        
                    }
                }
            } else {
                //wei 创建新闻栏目并绑定 2015.6.6
                if ($this->brand->count("title='" . $model["title"] . "' and isdel='0'") == 0) {
                    $category_model = array(
                        "title" => $model["title"],
                        "model_id" => "1", //文 章
                        "pid" => "10", //品牌新闻
                        "content" => "",
                        "beizhu" => "",
                        "addr" => $this->pin->Pinyin($model["title"], 'UTF8')
                    );
                    $cid = $this->website_category_helper->add($category_model);
                    //绑定品牌            	
                    $model["website_category"] = $cid;
                    $this->brand->update($model);
                }
                //end
                //域名新增自动填写
                //判断拼音是否已存在，如果不存就则写入,否则加随机数
                $yuming = $model["pinyin"];
                while ($this->brand->count("yuming='" . $yuming . "' and isdel='0'") > 0) {
                    $yuming.=rand(1, 100);
                }
                $model["yuming"] = $yuming;



                //添加
                $model['create_time'] = date('Y-m-d H:i:s', time());  //创建时间
                $this->brand->add($model);
            }
            echo "<script>
			parent.tip_show('保存成功',1,1000);
                     
                   //   var url = \"" . base_url() . "admin.php/brand/index.shtml\";
			//parent.flushpage(url);	
                       top.topManager.closePage();
			</script>";

            //   header("Location:".site_url("brand/index"));
            exit();
        } else {
            $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_add", $data);
        }
    }

    function edit() {
        $data = array();
        $admin_auth_cookie = $_COOKIE['admin_auth'];
        $usersession = $this->session->userdata('session_id');
        $data['admin_auth_cookie'] = $admin_auth_cookie;
        $data['usersession'] = $usersession;

        $get = $this->input->get();
        $model = $this->brand->GetModel($get["id"]);
        $model['ad_pic'] = json_decode($model['ad_pic']);
        if (!empty($model['ad_pic'])) {
            foreach ($model['ad_pic'] as $k => &$v) {
                $v = str_replace('/\\', "/", $v);
                $model['ad_pic_data'][$k]['path'] = $v;
                //批量上传图片链接的数据
                $pic_link_row = $this->brand->pic_link_data($get["id"], $k);
                if (!empty($pic_link_row)) {
                    $model['ad_pic_data'][$k]['link'] = $pic_link_row['link'];
                }else{
                     $model['ad_pic_data'][$k]['link'] = '';
                }
            }
        }

        $model['istop'] = date('Y-m-d H:i:s', $model['istop']);
        $data["model"] = $model;

        //新闻
        // $res = $this->db->select('ad_pic')->get_where('zx_brand', array('guid' => $guid))->result();
        //品牌新闻一级
        $news_data = $this->brand->getnews_category();

        $data["news_data"] = $news_data;

        $this->load->view(__TEMPLET_FOLDER__ . "/views_brand_add", $data);
    }

    function chkyuming() {
        $get = $this->input->get();
        $title = strtolower(trim($get["param"]));
        $id = empty($get["id"]) ? "" : $get["id"];
        if ($title == "www") {
            $data = array("result" => "0", "msg" => "不能使用WWW");
            echo json_encode($data);
        } else {
            $model = $this->M_common->query_one("select * from zx_brand where isdel=0 and yuming='$title'" . ($id != "" ? " and guid<>'" . $id . "'" : ""));
            if (count($model) > 0) {
                $data = array("result" => "0", "msg" => "已存在相同的域名");
                echo json_encode($data);
            } else {
                $data = array("result" => "1");
                echo json_encode($data);
            }
        }
        exit();
    }

    function count() {
        $get = $this->input->get();
        $dzzid = $get["dzzid"];
        if ($dzzid != "") {
            echo $this->brand->dzz_count($dzzid);
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
                $this->brand->del($v);
            }
        }
    }

    //批量上传图片
    function piliangUploadPic() {

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");


// Support CORS
// header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }


        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

// header("HTTP/1.0 500 Internal Server Error");
// exit;
// 5 minutes execution time
        @set_time_limit(5 * 60);

// Uncomment this one to fake upload time
        usleep(5000);

// Settings
// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = 'data/upload_tmp/';
        mkdirs($targetDir);
        $uploadDir = 'data/upload/brand/' . date('Ym') . '/';
        mkdirs($uploadDir);



        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
// Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }

        $x = explode('.', $fileName);
        $fileName = md5(uniqid(mt_rand())) . '.' . strtolower(end($x));

        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $md5File = $md5File ? $md5File : array();

        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File) !== FALSE) {
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
        }





        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;









// Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


// Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


// Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        if ($done) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }

// Return Success JSON-RPC response
//        die('{`jsonrpc" : "2.0", "result" : null, "id" : "id"}');

        $arr = json_encode(array('result' => $uploadPath));
        die($arr);
    }

    //用于文 本编辑器上传
    function upload() {
        //file_put_contents("e:aa.txt","bbbb=".print_r($_SESSION,true));		
        $this->myeditor->upload();
    }

    //ajax删除单张图片
    function ajax_delpic() {
        $data = $this->input->post();
        if (!empty($data['field']) && !empty($data['brandguid'])) {
            $model[$data['field']] = '';
            $sql = "select {$data['field']} from zx_brand where guid='{$data['brandguid']}' limit 1";
            $row = $this->M_common->query_one($sql);
            $array = $this->M_common->update_data2("zx_brand", $model, array("guid" => $data['brandguid']));
            if ($array['affect_num'] == 1) {
                @unlink($row[$data['field']]);
                echo 1;
            }
        }
    }

    //ajax批量删除图片
    function ajax_piliang_delpic() {
        $data = $this->input->post();
        $guid = $data['brandguid'];
        $index = $data['index'];
        $res = $this->db->select('ad_pic')->get_where('zx_brand', array('guid' => $guid))->result();
//           $this->db->last_query()
        $arr = json_decode($res[0]->ad_pic);
        $arr[$index] = '';

        $datajson = json_encode($arr);
//          print_r($datajson);exit;
        $ulist = array(
            'ad_pic' => $datajson,
            'guid' => $guid,
        );
        $this->brand->update($ulist);
        if ($this->db->affected_rows() >= 1) {
            //如果都为空就设置为空值
            $res = $this->db->select('ad_pic')->get_where('zx_brand', array('guid' => $guid))->result();
            $arr = json_decode($res[0]->ad_pic);
            $count = count($arr);
            $i = 0;
            foreach ($arr as $k => $v) {
                if (empty($v)) {
                    $i++;
                }
            }
            if ($i == $count) {
                $ulist = array(
                    'ad_pic' => '',
                    'guid' => $guid,
                );
                $this->brand->update($ulist);
            }
            echo 1;

            //把图片链接删除
            $this->brand->del_pic_link($guid, $index);
        } else {
            echo 2;
        }
    }

}

?>