<?php
/*
 *用户模型
 *author  王建 
 *time 2014-05-27
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
class UserModel extends MY_Controller{
	private  $itemname = '' ; //提示文字
	private $fieldname = '' ;//字段名称
	private $fieldtype = '' ; //字段类型
	private $vdefault = '' ; //默认值
	private $maxlength = '' ; //最大长度
	private $status = '1' ; //状态
	function UserModel(){
		parent::__construct();
		$this->load->model('M_common','',false , array('type'=>'real_data'));
	}
	function index(){
		$action = $this->input->get_post("action");	
		$action_array = array("show","ajax_data");
		$action = !in_array($action,$action_array)?'show':$action ;
		if($action == 'show'){
			$this->load->view(__TEMPLET_FOLDER__."/views_usermodel");
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
		$where = ' where 1= 1 ';
		
		$sql_count = "SELECT COUNT(*) AS tt FROM {$this->table_}common_user_model {$where}";
		$total  = $this->M_common->query_count($sql_count);
		$page_string = $this->common_page->page_string($total, $per_page, $page);
		$sql_role = "SELECT * FROM {$this->table_}common_user_model {$where} order by id desc   limit  {$limit}";	
		$list = $this->M_common->querylist($sql_role);
		foreach($list as $k=>$v){
			$list[$k]['status'] = ($v['status'] == 1 )?"开启":'<font color="red">关闭</font>';			
			$list[$k]['addtime'] = date("Y-m-d H:i:s",$v['addtime']);
			$list[$k]['issystem']  = ($v['issystem'] == 1 )?'<font color="red">[*]</font>':'' ;
		}
		echo result_to_towf_new($list, 1, '成功', $page_string) ;
	}
	///用户模型增加
	 function add(){
		$action = $this->input->get_post("action");		
		$action_array = array("add","doadd");
		$action = !in_array($action,$action_array)?'show':$action ;	
		if($action == 'show'){			
			$this->load->view(__TEMPLET_FOLDER__."/views_usermodel_add");		
		}elseif($action == 'doadd'){
			$this->doadd();
		}
	}
	//处理用户模型增加
	private function doadd(){
		$name = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("name")))));//
		$table= dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("table")))));//
		$status = verify_id($this->input->get_post("status")); //状态	
		$description = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("description")))));//
		if(empty($name)){
			showmessage("模型名称不能为空","usermodel/add",3,0);
			exit();
		}
		if(empty($table)){
			showmessage("表名称不能为空","usermodel/add",3,0);
			exit();
		}
		if(utf8_str($table) != 1 ){
			showmessage("表名称必须是英文","usermodel/add",3,0);
			exit();
		}
		$table = $this->table_.$table ;
		//查询表是否存在
		$info = $this->M_common->query_one("SELECT * FROM `{$this->table_}common_user_model` where `table` = '{$table}' limit 1 ");
		if(!empty($info)){
			showmessage("表{$table}已经存在","usermodel/add",3,0);
			exit();
		}
		$data = array(
			'name'=>$name,
			'status'=>$status,
			'table'=>$table,
			'description'=>$description,
			'addtime'=>time(),
			
		);
		
		$array = $this->M_common->insert_one("{$this->table_}common_user_model",$data);
		if($array['affect_num']>=1){
			$this->load->model('M_user');
			$this->M_user->build_table($table); //创建table
			write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),1,"添加模型为{$name}成功");
			header("Location:".site_url("usermodel/index"));
		}else{
			write_action_log($array['sql'],$this->uri->uri_string(),login_name(),get_client_ip(),0,"添加模型为{$name}失败");
			showmessage("添加模型失败","usermodel/add",3,0);
			exit();
		}
	}	
	//编辑页面
	function edit(){
		$action = $this->input->get_post("action");		
		$action_array = array("edit","doedit","addfield","doaddfield");
		$action = !in_array($action,$action_array)?'edit':$action ;		
		if($action == 'edit'){
			$id = verify_id($this->input->get_post("id"));
			$sql_user = "SELECT * FROM {$this->table_}common_user_model WHERE id = '{$id}'";
			$info = $this->M_common->query_one($sql_user);
			if(empty($info)){
				showmessage("暂无数据","usermodel/index",3,0);
				exit();
			}		
			$data = array(
				'info'=>$info
			);
			$this->load->view(__TEMPLET_FOLDER__."/views_usermodel_edit",$data);		
		}elseif($action == 'doedit'){
			$this->doedit();
		}elseif($action == "addfield"){
			//添加字段
			$this->addfield() ;
		}elseif($action == 'doaddfield'){
			//添加字段处理
			$this->doaddfield();
		}

	}
	//处理编辑数据
	private function doedit(){
		$username = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("username")))));//username
		$passwd = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("passwd")))));//passwd
		$status = verify_id($this->input->get_post("status")); //状态	
		$id = verify_id($this->input->get_post("id")); //id
		$expire = $this->input->get_post("expire"); //过期日期
		$set = '' ;
		if(!empty($username)){
			$set.=",`username` = '{$username}'";
		}
		if(!empty($passwd)){
			if(utf8_str($passwd) != 1 ){
				showmessage("密码必须是英文","user/edit",3,0,"?id={$id}");
				exit();
			}
			$passwd = md5($passwd);
			$set.=",`passwd`= '{$passwd}'";
		}
		
		if($expire != '0'  && strripos($expire,"-") !== FALSE ){
			$expire = strtotime($expire);
		}else{
			$expire = 0 ;
		}			
		$sql_edit = "UPDATE `{$this->table_}common_user` SET `expire` = '{$expire}' {$set} ,`status` = '{$status}' where uid = '{$id}'";
		$num = $this->M_common->update_data($sql_edit);
		if($num>=1){
			//
			write_action_log($sql_edit,$this->uri->uri_string(),login_name(),get_client_ip(),1,"修改用户为{$username}成功");
			header("Location:".site_url("user/index/"));
		}else{
			write_action_log($sql_edit,$this->uri->uri_string(),login_name(),get_client_ip(),0,"修改用户{$username}失败");
			showmessage("服务器繁忙，或者你没有修改任何数据","user/edit",3,0,"?id={$id}");
			die();
		}
	}
	//字段添加
	private function addfield(){
		$modelid = verify_id($this->input->get_post("modelid"));
		$this->load->view(__TEMPLET_FOLDER__."/views_usermodelfield_add",array('modelid'=>$modelid));		
	}
	//添加字段处理
	private function doaddfield(){
		$this->setdata();
		$modelid = verify_id($this->input->get_post("modelid",true));
		if(empty($this->itemname) || empty($this->fieldname)){
			showmessage("字段名称和提示文字","usermodel/edit",3,0,"?action=addfield&modelid={$modelid}");
			exit();
		}
		$model_table = '' ;
		$array_table = $this->M_common->query_one("SELECT `table` FROM {$this->table_}common_user_model where id = '{$modelid}' limit 1 "); 
		$model_table = $array_table['table'];
		if(empty($model_table)){
			showmessage("模型表不存在","usermodel/edit",3,0,"?action=addfield&modelid={$modelid}");
			exit();
		}
		$data = array(
			'itemname'=>$this->itemname,
			'fieldname'=>$this->fieldname,
			'fieldtype'=>$this->fieldtype , 
			'vdefault'=>$this->vdefault , 
			'maxlength'=>$this->maxlength , 
			'status'=>$this->status , 
		);
		$sql_field = "ALTER TABLE `{$model_table}` ADD " ;
		$this->fieldtype = strtoupper($this->fieldtype);
		switch ($this->fieldtype){			
			case 'VARCHAR' :
				$sql_field.=" {$this->fieldname} {$this->fieldtype}($this->maxlength) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '{$this->itemname}' ";
				break ; 
			case 'CHAR':
				$sql_field.=" {$this->fieldname} {$this->fieldtype}($this->maxlength) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '{$this->itemname}' ";
				break ; 
			case 'INT':
				$sql_field.=" {$this->fieldname} {$this->fieldtype}(11) UNSIGNED NULL DEFAULT '0'  COMMENT '{$this->itemname}' ";
				break ;
			case 'TEXT':
				$sql_field.=" {$this->fieldname} {$this->fieldtype} CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '{$this->itemname}' ";
				break  ; 
			case 'MEDIUMTEXT':
				$sql_field.=" {$this->fieldname} {$this->fieldtype} CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '{$this->itemname}' ";
				break  ; 	
			case 'FLOAT' :	
				$sql_field.=" {$this->fieldname} {$this->fieldtype}($this->maxlength) NULL DEFAULT NULL  COMMENT '{$this->itemname}' ";
				break ; 
			case 'DATETIME':
				$sql_field.=" {$this->fieldname} {$this->fieldtype} NULL DEFAULT NULL COMMENT '{$this->itemname}'";
				break ;
			case 'ENUM':
				$sql_field.=" {$this->fieldname} {$this->fieldtype}() CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '{$this->itemname}'";
				break ; 
		}
		echo $sql_field ;
		$info = serialize($data);
		echo "<pre>";
		print_r($data);
		die() ;
		$sql_edit = "UPDATE `{$this->table_}common_user_model` SET `info` = '{$info}' where id = '{$modelid}' "  ;
		$num = $this->M_common->update_data($sql_edit);
		
	}
	//设置data
	private function setdata(){
		$this->itemname = html_escape(strip_tags($this->input->get_post("itemname",true)));//itemname
		$this->fieldname = html_escape(strip_tags($this->input->get_post("fieldname",true)));//fieldname
		$this->fieldtype = html_escape(strip_tags($this->input->get_post("fieldtype",true)));//fieldtype
		$this->vdefault = html_escape(strip_tags($this->input->get_post("vdefault",true)));//itemname
		$this->maxlength = html_escape(strip_tags($this->input->get_post("maxlength",true)));//maxlength
		$this->status = html_escape(strip_tags($this->input->get_post("status",true)));//status
	}

	
}