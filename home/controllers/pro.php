<?php
/*
 *网站用户管理controller
 *author  王建 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class Pro extends MY_Controller{
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
		
	function Pro(){
		parent::__construct();		
		$this->load->model('M_common','',false , array('type'=>'real_data'));				
		$this->cache_category_path =  config_item("category_modeldata_cache") ; 
		$this->upload_path = __ROOT__."/data/upload/pro/fujian/" ; ; // 编辑器上传的文件保存的位置
		$this->upload_save_url = base_url()."/data/upload/pro/fujian/"; //编辑器上传图片的访问的路径		
		$this->upload_path_sys = "data/upload/pro/fujian/";//保存字段用的
	}
	function index(){
		$action = $this->input->get_post("action");	
		//$action_array = array("show","ajax_data");				
		$action_array = array("show","ajax_data","preview","upload");
		$action = !in_array($action,$action_array)?'show':$action ;
		if($action == 'show'){
			$this->load->view(__TEMPLET_FOLDER__."/views_user");
		}elseif($action == 'ajax_data'){
			$this->ajax_data();		
		}elseif($action == "upload" ){
			$this->upload() ;
		}			
	}
	function prolist(){
		$action = $this->input->get_post("action");	
		$action_array = array("show","ajax_data");
		$action = !in_array($action,$action_array)?'show':$action ;
		if($action == 'show'){
			$this->load->view(__TEMPLET_FOLDER__."/views_pro");
		}elseif($action == 'ajax_data'){
			$this->ajax_data();
		}		
	}	
	//ajax 获取数据
	private function ajax_data(){
		$this->load->library("common_page");
		$page = $this->input->get_post("page");	
		if($page <=0 ){
			$page = 1 ;
		}
		$per_page = 10;//每一页显示的数量
		$limit = ($page-1)*$per_page;
		$limit.=",{$per_page}";
		$where = ' where isdel=0 and pid=0 ';
		$sea_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title",true))))) ;
		
		if(!empty($sea_title)){
			$where.=" AND title like '%$sea_title%'";
		}		
		$sql_count = "SELECT COUNT(*) AS tt FROM rwfp_mokuai {$where}";
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_role = "SELECT id,title,create_time,create_username,jihua_start_time,jihua_end_time FROM rwfp_mokuai {$where} order by id desc   limit  {$limit}";	
		$list = $this->M_common->querylist($sql_role);
		foreach($list as $k=>$v){
			$list[$k]['create_time'] =  date("Y-m-d H:i:s",$v['create_time']);
			$list[$k]['jihua_start_time'] =  date("Y-m-d H:i:s",$v['jihua_start_time']);
			$list[$k]['jihua_end_time'] =  date("Y-m-d H:i:s",$v['jihua_end_time']);
		}		
		echo result_to_towf_new($list, 1, '成功', $page_string);
	}
	//编辑页面
	function edit(){
		$action = $this->input->get_post("action");		
		$action_array = array("edit","doedit");
		$action = !in_array($action,$action_array)?'edit':$action ;		
		if($action == 'edit'){
			$id = verify_id($this->input->get_post("id"));
			$sql_user = "SELECT * FROM rwfp_mokuai WHERE pid=0 and id = '{$id}'";
			$info = $this->M_common->query_one($sql_user);
			if(empty($info)){
				showmessage("暂无数据","pro/prolist",3,0);
				exit();
			}		
			$data['info'] = $info;		
			$sql_user = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'pro' order by orderby asc";
			$pro_status_list = $this->M_common->querylist($sql_user);
			$data["pro_status_list"]=$pro_status_list;
			$this->load->view(__TEMPLET_FOLDER__."/views_pro_edit",$data);		
		}elseif($action == 'doedit'){
			$this->doedit();
		}

	}
	//处理编辑数据
	private function doedit(){
		$this->set_params();
		$id = verify_id($this->input->get_post("id")); //id
		if(empty($this->title)){
			showmessage("请输入项目名称","pro/edit",3,0);
			exit();
		}
		if(empty($this->jihua_start_time) || empty($this->jihua_end_time)){
			showmessage("计划开始时间和结束时间不能为空","pro/edit",3,0);
			exit();
		}
		//取得旧信息
		$old = $this->M_common->query_one("select * from rwfp_mokuai where pid=0 and id=$id");

		if($old["fujian"]!="")
		{
			//检查delfujian是否选中，如果就删除文件
                    $delfujian = $this->input->get_post("delfujian");
			if(!empty($delfujian))
			{
				if($this->input->get_post("delfujian")=="yes")
				{
					@unlink(__ROOT__.$old["fujian"]);
				}
			}
		}
		$this->load->library("common_upload");
		$fujian_path = $this->common_upload->upload_path($this->upload_path,'fujian' , 'doc|docx|wps|ppt' );
		if($fujian_path!=""){
			$fujian_path = $this->upload_path_sys.$fujian_path;
			if($old["fujian"]!="")
			{
				@unlink(__ROOT__.$old["fujian"]);
			}			
		}
		else{
                    $delfujian = $this->input->get_post("delfujian");
			if(!empty($delfujian))
			{
				
			}
			else
			{
				$fujian_path = $old["fujian"];	
			}
		}
		$data = array(
			'title'=>$this->title,
			'fujian'=>$fujian_path,
			'content'=>$this->content,
			'jihua_start_time'=>strtotime($this->jihua_start_time),
			'jihua_end_time'=>strtotime($this->jihua_end_time),
			'content'=>$this->content,
			'mokuai_status_title'=>$this->mokuai_status_title,
			'mokuai_status'=>$this->mokuai_status,			
			'pid'=>"0",
			'isdel'=>'0',
			'update_userid'=>admin_id(),
			'update_time'=>time(),
			'update_username'=>login_name()
		);	
		//echo $this->upload_path;
		//查询项目名是否存在
		$info = $this->M_common->query_one("SELECT count(1) as dd FROM `rwfp_mokuai` where title= '".$this->title."' and id<>$id");
		if($info["dd"]>0){
			showmessage("项目".$this->title."已经存在","pro/edit?id=$id",3,0);
			exit();
		}
		$array = $this->M_common->update_data2("rwfp_mokuai",$data,array("id"=>$id));
		if($array['affect_num']>=1){
			write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),1,"修改项目".$this->title."成功");
			showmessage("修改成功","pro/prolist",3,1);//1:正确
			//header("Location:".site_url("pro/prolist"));
			//showmessage("添加用户成功","user/index",3,1);
			exit();
		}else{
			write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"修改项目".$this->title."失败");
			showmessage("修改项目失败","pro/edit?id=$id",3,0);
			exit();
		}		
	}
	
	//查看项目详情
	function proview(){
		$action = $this->input->get_post("action");	
		$id = verify_id($this->input->get_post("id"));	
		$sql_user = "SELECT * FROM rwfp_mokuai WHERE pid=0 and id = '{$id}'";
		$info = $this->M_common->query_one($sql_user);
		$info["content"] = str_replace("&lt;","<",$info["content"]);
		$info["content"] = str_replace("&gt;",">",$info["content"]);
		$data['info'] = $info;		
		$this->load->view(__TEMPLET_FOLDER__."/views_pro_view",$data);		
	}
	
	
	 function add(){
		$action = $this->input->get_post("action");		
		$action_array = array("add","doadd");
		$action = !in_array($action,$action_array)?'show':$action ;	
		if($action == 'show'){
			$sql_user = "SELECT * FROM rwfp_zidian WHERE zidian_flag= 'pro' order by orderby asc";
			$pro_status_list = $this->M_common->querylist($sql_user);
			$data = array("pro_status_list"=>$pro_status_list);
			$this->load->view(__TEMPLET_FOLDER__."/views_pro_add",$data);		
		}elseif($action == 'doadd'){

			$this->doadd();
		}
	}
	//处理增加
	private function doadd(){	
	
		$this->set_params();
		if(empty($this->title)){
			showmessage("请输入项目名称","pro/add",3,0);
			exit();
		}
		if(empty($this->jihua_start_time) || empty($this->jihua_end_time)){
			showmessage("计划开始时间和结束时间不能为空","pro/add",3,0);
			exit();
		}
		$this->load->library("common_upload");
		$fujian_path = $this->common_upload->upload_path($this->upload_path,'fujian' , 'doc|docx|wps|ppt' );
		if($fujian_path!=""){
			$fujian_path = $this->upload_path_sys.$fujian_path;
		}
		$data = array(
			'title'=>$this->title,
			'fujian'=>$fujian_path,
			'content'=>$this->content,
			'jihua_start_time'=>strtotime($this->jihua_start_time),
			'jihua_end_time'=>strtotime($this->jihua_end_time),
			'content'=>$this->content,
			'mokuai_status_title'=>$this->mokuai_status_title,
			'mokuai_status'=>$this->mokuai_status,			
			'pid'=>"0",
			'isdel'=>'0',
			'create_userid'=>admin_id(),
			'create_time'=>time(),
			'create_username'=>login_name(),
			'update_userid'=>admin_id(),
			'update_time'=>time(),
			'update_username'=>login_name()
		);	
		//echo $this->upload_path;
		//查询项目名是否存在
		$info = $this->M_common->query_one("SELECT count(1) as dd FROM `rwfp_mokuai` where title= '".$this->title."' ");
		if($info["dd"]>0){
			showmessage("项目".$this->title."已经存在","pro/add",3,0);
			exit();
		}
		$array = $this->M_common->insert_one("rwfp_mokuai",$data);
		if($array['affect_num']>=1){
			write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),1,"添加项目".$this->title."成功");
			header("Location:".site_url("pro/prolist"));
			//showmessage("添加用户成功","user/index",3,1);
			//exit();
		}else{
			write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"添加项目".$this->title."失败");
			showmessage("添加项目失败","user/add",3,0);
			exit();
		}
	}
	
	//生成缓存
	private function make_cache(){
		if(!is_really_writable($this->role_cache_path)){				
			exit("目录".$this->role_cache_path."不可写");
		}
		
		if(!file_exists($this->role_cache_path)){
			mkdir($this->role_cache_path);
		}
		$configfile = $this->role_cache_path."/cache_role_{$this->role_id}.inc.php";
		$str = '' ; 
		$time = date("Y-m-d H:i:s",time());
		$fp = fopen($configfile,'w');
		flock($fp,3);
		fwrite($fp,"<"."?php\r\n");
    	fwrite($fp,"/*团队角色缓存*/\r\n");
    	fwrite($fp,"/*author wangjian*/\r\n");
    	fwrite($fp,"/*time {$time}*/\r\n");
    	//fwrite($fp,"\$role_array = array(\r\n");
		/* foreach($this->perm_data as $k=>$v){
			fwrite($fp,"'{$k}' => '{$v}',\r\n");
		} */
		$str.="\$role_array = ";
    	$str.= var_export($this->perm_data,true)  ; 
    	fwrite($fp,"{$str};\r\n");
		//fwrite($fp,");\r\n");
		fwrite($fp,"?".">");
    	fclose($fp);
	}
	
		//上传文件
	private function upload(){
		//包含kindeditor的上传文件
		$save_path =$this->upload_path ; // 编辑器上传的文件保存的位置
		$save_url = $this->upload_save_url; //访问的路径
		include_once __ROOT__.'/'.APPPATH."libraries/JSON.php" ;
		include_once __ROOT__.'/'.APPPATH."libraries/upload_json.php" ;
	}
	
	//预览所在用户组的用户
	private function preview_user(){
		$id = verify_id($this->input->get_post("id"));
		if($id<=0){
			echo "参数传递错误"; 
			exit();
		}
		$list = $this->M_common->querylist("SELECT id,username,status FROM {$this->table_}common_system_user where gid = '{$id}' ");
		if($list){
			foreach($list as $k=>$v){
				$status = ($v['status'] == 1 )?"<font color='green'>正常</font>":'<font color="red" >禁止</font>' ;
				echo "<li style=\"text-decoration:none; display:block ; width:100px; height:30px; padding:2px; float:left; border:solid 1px #F0F0F0 ;  text-align:center;line-height:30px; margin-left:3px\">";
				echo $v['username']."【".$status."】";
				echo "</li>";
			}
		}else{
			echo "暂无用户";
		}
	}
	
	
//设置参数
	private function set_params(){
	  $this->title = html_escape($this->input->get_post("title",true));		
	  $this->jihua_start_time = daddslashes(html_escape(strip_tags($this->input->get_post("jihua_start_time",true))));
	  $this->jihua_end_time = daddslashes(html_escape(strip_tags($this->input->get_post("jihua_end_time",true))));
	  //比较调换，开始时间必须小于结束时间
	  if($this->jihua_start_time>$this->jihua_end_time){
		$tmp =  $this->jihua_end_time;
		$this->jihua_start_time = $this->jihua_end_time;
		$this->jihua_end_time = $tmp;
	  }	  		
	  $this->content = html_escape($this->input->get_post("content",true));
	  $this->mokuai_status = verify_id($this->input->get_post("mokuai_status",true));
	  //根据mokuai_status>0读取名称
	  if($this->mokuai_status>0){
		  $tmp = $this->M_common->query_one("select * from rwfp_zidian where zidian_flag='pro' and id=".$this->mokuai_status);
		  if(is_array($tmp)){			  
			$this->mokuai_status_title = $tmp["title"];
		  }
	  }
	}
	

}
