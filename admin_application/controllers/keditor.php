<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class Keditor extends MY_Controller{
	public $upload_path = ''; 
	public $upload_save_url = '';	
	public $upload_path_sys = '';
	
	//表字段
	public $title = "";
	public $fujian = "";
	public 	$jihua_start_time = "";
	public 	$jihua_end_time = "";
	public	$mokuai_status = "";
	public $mokuai_status_title = "";
		
	function Keditor(){
		parent::__construct();		
		$this->load->model('M_common','',false , array('type'=>'real_data'));				
		$this->cache_category_path =  config_item("category_modeldata_cache") ; 
		$this->upload_path = __ROOT__."/data/upload/pro/fujian/"; // 编辑器上传的文件保存的位置
		$this->upload_save_url = base_url()."/data/upload/pro/fujian/"; //编辑器上传图片的访问的路径		
		$this->upload_path_sys = "data/upload/pro/fujian/";//保存字段用的
	}
	function index(){
		$action = $this->input->get_post("action");	
		//$action_array = array("show","ajax_data");				
		$action_array = array("upload","mkview_upload");
		$action = !in_array($action,$action_array)?'show':$action ;
		if($action == 'show'){			
		}elseif($action == 'ajax_data'){
			
		}elseif($action == "upload" ){
			$this->upload() ;
		}
		elseif($action == "mkview_upload"){
			
			$this->mkview_upload();	
		}
		
	}
	
	function mkview_upload(){
		$this->upload();
	}
	//上传文件
	private function upload(){
		//包含kindeditor的上传文件
		$save_path =$this->upload_path ; // 编辑器上传的文件保存的位置
		$save_url = $this->upload_save_url; //访问的路径
		include_once __ROOT__.'/'.APPPATH."libraries/JSON.php" ;
		include_once __ROOT__.'/'.APPPATH."libraries/upload_json.php" ;
	}
	
	

}
