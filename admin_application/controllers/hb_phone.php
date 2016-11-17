<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//手机控制器
class hb_phone extends MY_Controller {
	var $data;
	function hb_phone(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_hb_tel','hb_tel');
		$this->load->model('M_common_category_data','cd');	
		$this->load->model('M_hb_import_excel', 'excel');//excel模型			
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
		// 上传excel的位置
        $this->upload_path = __ROOT__ . "/data/upload/hb/";
        //编辑器上传图片的访问的路径
        $this->upload_save_url = base_url() . "/data/upload/hb/";
	}	

	//手机列表页
	function index(){
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$pagesize = 15;
		$search = array();
		$search_val = array();
		$search_val["title"] = "";
		$orderby["id"] = "desc";
		if(!empty($get["search_title"])){
			$search["title"] =  trim($get["search_title"]);
			$search_val["title"] = $get["search_title"];
		}		
		$list = $this->hb_tel->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/hb/phone/list",$this->data);		
	}
	
	//编辑手机页
	function edit(){		
		$post = $this->input->post();		
		if(is_array($post)){
			$ls = empty($post["backurl"])?site_url("hb_phone/index"):$post["backurl"];//site_url("hb_phone/edit")."?id=".$id;//
			$id = $post['id'];
			if ($this->check_repeat_phone($post['tel'], $id)) {
				echo "<script>
				parent.tip_show('该号码已经存在，请不要重复添加',0,1000);
				var url = \"$ls\";
				parent.flushpage(url);					
				setTimeout(\"top.topManager.closePage();\",1000);
				</script>";			
				die();	
			}
			$model['id']  = $id;
			$model['tel'] = $post['tel'];//手机号码
			$model['beizhu']  = $post['beizhu'];//备注
			// $model['create_time'] = time();//无更新时间注释
			$this->hb_tel->update($model);
			//showmessage("修改成功",$ls,1,1);
			echo "<script>
			parent.tip_show('修改成功',1,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();	
		}
		else{
			$get = $this->input->get();
			$id = empty($get["id"])?0:$get["id"];
			if(!is_numeric($id)){
				$id = 0;	
			}
			if($id==0){
				showmessage("无数据","hb_phone/index",3,0);			
			}
			$model = $this->hb_tel->GetModel($id);
			$this->data['model'] = $model;
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_phone/index");	
			}
			$this->data["id"] = $id;
			$this->load->view(__TEMPLET_FOLDER__."/hb/phone/edit",$this->data);			
		}
	}
	//添加手机号页
	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
			$ls = empty($post["ls"])?site_url("hb_phone/index"):$post["ls"];
			$tels = $post["tel"];//手机号码，以英文逗号分隔
			$tel_arr = @explode(',', $tels);//打散为数组
			$tel_arr = array_unique($tel_arr);//去掉重复项
			$model['create_time'] = time();
			foreach ($tel_arr as $key => $value) {
				if ($this->check_repeat_phone($value)) {//已经存在数据库的不添加
					continue;
				}
				$model['tel'] = $value;
				$model['beizhu']  = $post['beizhu'];//备注
				$newid = $this->hb_tel->add($model);
			}
			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_phone/index");	
			}
			$this->load->view(__TEMPLET_FOLDER__."/hb/phone/add",$this->data);		
		}
	}
	
	//删除手机
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->M_common->del_data("delete from hb_tel where id='$v'");
			}
		}
		//echo "ok";
	}
	//检查手机号是已经存在数据库，是返回true，否则false，$tel手机号，$id不为该id可以为空
	function check_repeat_phone($tel, $id='') {

		$where = " tel='$tel'";
		if ($id) {
			$where .= " and id!='$id'";
		}
		$list = $this->hb_tel->getlist($where);
		if (is_array($list)&&count($list)>0) {
			return true;
		} else {
			return false;
		}
	}
	//ajax检查手机号是已经存在数据库，是返回true，否则false，$tel手机号，$id不为该id可以为空
	function check_repeat_phone_ajax() {

		$result = array('code' => '-1', 'info' => '服务器错误，请刷新重试');
		$get = $this->input->get();
		$id  = $get['id'];
		$tel = $get['tel'];
		$where = " tel='$tel' and id!='$id'";
		$list = $this->hb_tel->getlist($where);
		if (is_array($list)&&count($list)>0) {
			$result['code'] = 1;
			$result['info'] = '系统中已经存在该手机号码，请不要重复添加';
		} else {
			$result['code'] = 0;
			$result['info'] = '无重复手机号码';
		}
		echo json_encode($result);exit;
	}

	 /**
     * 导入页面
     */
    public function import()
    {
        $this->load->view(__TEMPLET_FOLDER__ . "/hb/phone/import", $this->data);
    }

    /**
     * 处理导入excel
     */
    public function doimport()
    {
        //文件可能比较大
        @ini_set('memory_limit', '1024M');

        $ls = empty($post["backurl"])?site_url("hb_phone/import"):$post["backurl"];//site_url("hb_phone/edit")."?id=".$id;//
        /*上传excel*/
        //允许上传格式
        $fileTypes = array('xls', 'xlsx');
        $this->load->library("common_upload");
        if ($_FILES["excel"]["type"] == "application/vnd.ms-excel") {
            $inputFileType = 'Excel5';
        } elseif ($_FILES["excel"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $inputFileType = 'Excel2007';
        } else {
            showmessage("导入的excel类型错误，请检查您上传的excel是否为从系统中下载的模板", $ls, 3, 0);
            exit;
        }
        if (!file_exists($this->upload_path)){ 
        	mkdir ($this->upload_path); 
    	}
        $fujian_path = $this->common_upload->upload_path_ym($this->upload_path, 'excel', implode("|", $fileTypes));
        if (!$fujian_path) {
            showmessage("导入excel失败，请检查您上传的excel的格式是否错误", $ls, 3, 0);
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
        //AA转化为数字
        $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);
        if ($highestColumn != 2) {//上传的excel列数不对应
            //删除文件
            @unlink($fujian_path);
            showmessage("导入失败，请严格按照导入的模板填写数据", $ls, 3, 0);
            exit;
        }

        //统计导入失败条数
        $countFailed = 0;
        //统计有重复的书
        $countrepeat = 0;
        $sheet = $objPHPExcel->getActiveSheet();
        //从第二行开始读取数据
        $nowTime = time();
        for ($j = 2; $j <= $highestRow; $j++) {
            $tpmodel = array();//获取数据
            //从第1列开始读取数据,
            for ($k = 0; $k < $highestColumn; $k++) {
                //0转为A，1转B
                $col = PHPExcel_Cell::stringFromColumnIndex($k);
                $str = $sheet->getCell("{$col}{$j}")->getValue();//读取单元格
                $tpmodel[] = mb_convert_encoding($str, 'UTF-8');//转换成utf8
            }

            //对导入的数据进行处理(添加到数据库中)
            if ($this->check_repeat_phone($tpmodel[0])) {//已经存在数据库的不添加
            	$countFailed++;
            	$countrepeat++;
				continue;
			}
			$model['tel'] = $tpmodel[0];//手机号
			$model['beizhu']  = $tpmodel[1];//备注
			$model['create_time'] = $nowTime;
			$newid = $this->hb_tel->add($model);
            if (!$newid) {
                //添加失败，失败条数+1
                $countFailed++;
            }
        }

        //删除文件
        @unlink($fujian_path);
        $counttotal = $highestRow - 1;//导入总数
        $countSucc = $counttotal - $countFailed;//成功条数
        $message = "导入总数为：" . $counttotal . "条，" . $countSucc . 
        			'条成功、' . $countFailed . '条失败,其中'.$countrepeat.'重复。';
        //echo $message;
        showmessage($message, $ls, 3, 1);
    }
}