<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * Class Swj_upload
 * @property M_swj_fujian $fj
 */
class Swj_upload extends MY_Controller{

	function Swj_upload(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_swj_fujian','fj');
		$this->table_ =table_pre('real_data');
		$this->table = "swj_activity_beian";
		$post = $this->input->get();	
		$get = $this->input->get();
		$this->userInfo = $this->parent_getsession(empty($get["session_id"])?"":$get["session_id"]);//获取登录信息
		$this->upload_path = __ROOT__."/data/upload/user/"; // 编辑器上传的文件保存的位置
		$this->upload_save_url = __ROOT__."/data/upload/user/"; //编辑器上传图片的访问的路径		
	}

	function index(){
		
	}
	
	//上传文件，不写入附件表
	public function upload2(){
		$get = $this->input->get();
		//设置上传目录
		$path = 'data/upload/'.(isset($get["path"])?$get["path"]:"user");
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}
		$year = date('Y', time());//文件按年份存储
		$path .= $year.'/';
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}
		
		if (!empty($_FILES)) {
				
			//得到上传的临时文件流
			$tempFile = $_FILES['Filedata']['tmp_name'];
				
			//允许的文件后缀
			$fileTypes = array('jpg','jpeg','gif','png','doc','pdf','rar','xls','xlsx','docx','ppt','pptx','txt');
				
			$this->load->library("common_upload");
			$fujian_path = $this->common_upload->upload_path_ym_width(
					$this->upload_path, 
					'Filedata',
					implode("|",$fileTypes),
					2000);//宽度
			echo $fujian_path;	
		}
	}

	//多文件上传
	public function upload() {
		//设置上传目录
		$path = 'data/upload/act_record/';
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}
		$year = date('Y', time());//文件按年份存储
		$path .= $year.'/';
		if (!is_dir($path)) {//不存在文件夹创建
			mkdir($path);
		}
		
		if (!empty($_FILES)) {
			
			//得到上传的临时文件流
			$tempFile = $_FILES['Filedata']['tmp_name'];
			
			//允许的文件后缀
			$fileTypes = array('jpg','jpeg','gif','png','doc','pdf','rar','xls','xlsx','docx','ppt','pptx','txt'); 
			
			$this->load->library("common_upload");
			$fujian_path = $this->common_upload->upload_path_ym_width($this->upload_path, 'Filedata',implode("|",$fileTypes),2000);
			if ($fujian_path != "") {
				//写入附件表				
				$model["title"] = $fujian_path;
				$model["filesrc"] = $fujian_path;
				$model["beizhu"] = "";
				$model["userid"] = "0";
				$model["system_userid"] = $this->userInfo["admin_id"];
				$model["createtime"] = time();
				$model["beizhu"] = "临时";				
				$newid = $this->fj->add($model);				
				echo $newid;
				
			}
			else{
				echo "-1";	
			}
		}
		else{
			echo "-1";	
		}
	}
	
	public function delfj(){
		$get = $this->input->get();
		$id = $get["id"];		
		if($id>0){
			$model = $this->fj->GetModel($id);
			//$userid = $this->userInfo["userid"];
			if(isset($model["filesrc"])){
				if($model["filesrc"]!=""){
					@unlink(realpath($model["filesrc"]));
				}
			}
			$this->fj->del($id);
		}
	}
	//通过附件id获取文件的路径
	public function getUrl() {
		$post =  $this->input->post();
		$id   =  $post['id'];
		$rst  =  array('code' => -1, 'id' => $id);
		if ($id>0) {
			$model = $this->fj->GetModel($id);
			if(isset($model["filesrc"])){
				if($model["filesrc"]!=""){
					$rst['filesrc'] = $model["filesrc"];
					$rst['code'] = 0;
				}
			}
		}
		echo json_encode($rst);
	}
	//删除临时附件(前一天)
	public function delLsFj() {
		$nowTime = time();//当前时间戳
		$day     = 86400 * 1;//相差一天
		$nowDay  = date('Y-m-d H:i:s', $nowTime);//当前日期
		$where = " where beizhu='临时' and (createtime-$nowTime>$day) ";//删除条件
		$sql = "select filesrc from swj_fujian {$where}";
		$model = $this->M_common->querylist($sql);
		foreach ($model as $key => $value) {
			if($model["filesrc"]!=""){//删除该附件
				@unlink(realpath($model["filesrc"]));
			}
		}
		//删除数据库的数据
		$sql = "delete from swj_fujian {$where}";
		$this->M_common->del_data($sql);
	}
}