<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

class zcq_zijin extends MY_Controller{
	var $data;
	function zcq_zijin(){
		parent::__construct();
		$this->load->model('M_common');

		$this->load->model('m_zcq_pro_fujian','zcq_pro_fujian');
        $this->load->model('m_zcq_pro_type_fujian','zcq_pro_type_fujian');

		$get = $this->input->get();
		$this->data["ls"] = empty($get["ls"])?"":$get["ls"];	
		$this->upload_path = __ROOT__."/data/upload/pro_fujian/"; // 编辑器上传的文件保存的位置
		$this->upload_save_url = __ROOT__."/data/upload/pro_fujian/"; //编辑器上传图片的访问的路径
		$this->data["sess"] = $this->parent_getsession();		
	}

	function index(){
		
	}
	
	function fujian(){		
		$pageindex= $this->input->get_post("per_page");
		$get = $this->input->get();
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$pagesize = 20;
		$search = array();
		$search_val = array();
		$search_val["title"] = isset($get["sel_title"])?$get["sel_title"]:"";
		$orderby["id"] = "desc";		
		if($search_val["title"]!=""){
			$search["title"]= " like '%".$search_val["title"]."%'";	
		}
		
		$list = $this->zcq_pro_fujian->GetInfoList($pageindex,$pagesize,$search,$orderby);		
		$this->data["list"] = $list["list"];
		$this->data["pager"] = $list["pager"];
		$this->data["search_val"] = $search_val;

		$this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/fujian/list",$this->data);
	}
	
	function fujian_add(){
		$post = $this->input->post();
		if($this->data["ls"]==""){
			$this->data["ls"] = site_url("zcq_zijin/fujian");
		}		
		if(is_array($post)){
			$model["title"] = $post["title"];
			$model["filepath"] = $post["filepath"];
			$model["isdel"] = "0";
			$model["create_sysuserid"] = admin_id();
			$model["update_sysuserid"] = 0;
			$model["createtime"] = time();
			$model["updatetime"] = 0;
			$this->zcq_pro_fujian->add($model);
			showmessage("保存成功",
				$this->data["ls"], 1, $iserror = 1,
				$params = '');			
		}
		else{
			$this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/fujian/add",$this->data);
		}
	}
	
	function fujian_edit(){
		$get = $this->input->get();
		$post = $this->input->post();
		if($this->data["ls"]==""){
			$this->data["ls"] = site_url("zcq_zijin/fujian_edit");
		}		
		if(is_array($post)){
			$model = $this->zcq_pro_fujian->GetModel($post["id"]);
			$model["title"] = $post["title"];				
			$model["filepath"] = $post["filepath"];
			$model["updatetime"] = time();
			$model["update_sysuserid"] = admin_id();
			$this->zcq_pro_fujian->update($model);
            if (strpos($this->data["ls"],"?")) {
                $this->data["ls"] .= "&close=yes&id=".$model["id"];
            }else{
                $this->data["ls"] .= "?close=yes&id=".$model["id"];
            }
			showmessage("保存成功", $this->data["ls"], 1, $iserror = 1, $params = '');
		}
		else{
			$id = !is_numeric($get["id"])?0:$get["id"];
			$model = $this->zcq_pro_fujian->GetModel($id);
			$this->data["close"] = isset($get["close"])?"yes":"";			
			$this->data["model"] = $model;
			$this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/fujian/edit",$this->data);
		}
	}
	
	function fujian_file_del(){
		$get = $this->input->post();
		$filepath  = empty($get["fp"])?"":$get["fp"];
		@unlink($filepath);		
	}

	/*
	 * 检查申报类型有无引用
	 */
	private function fujian_chkdel($fjid){
        $list = $this->zcq_pro_type_fujian->getlist3("t3.isdel='0' and t1.del_sysuserid='0' and fujian_id='".$fjid."'");
        return $list;
    }
	
	function fujian_del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
        $errarr = array();
		if($ids!=""){
			foreach($arr as $v) {
                $typelist = $this->fujian_chkdel($v);
                $model = $this->zcq_pro_fujian->GetModel($v);
                if (count($typelist) == 0) {

                } else {
                    $errarr[] = array(
                        "typetitle" => $typelist[0]["typetitle"],
                        "fujian_title" => $model["title"]);
                }
			}
            if (count($errarr) == 0) {
                foreach($arr as $v) {
                    $model = $this->zcq_pro_fujian->GetModel($v);
                    if (is_array($model)) {
                        if (is_file($model["filepath"])) {
                            @unlink($model["filepath"]);
                        }
                    }
                    $this->zcq_pro_fujian->del($v);
                }
            }

		}

		echo json_encode($errarr);
		
	}

}