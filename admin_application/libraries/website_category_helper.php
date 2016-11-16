<?php
class Website_category_helper extends CI_Model{
	function __construct() {
		//辅助类，因为其他控制不能直接调用别的控制器
		parent::__construct();
		$this->load->model('M_website_category','wc');		
	}
	
	//用于其他模块调用，需要自动生成栏目时用
	function add($model){	
		$addr = $model["addr"];	
		while($this->wc->GetAddrCount(0,$addr)>0){
			$addr.="_";
		}
		$parent_path = "0";
		if($model["pid"]>0){
			$parent_model = $this->wc->GetModel($model["pid"]);
			$parent_path = $parent_model["parent_path"].",".$parent_model["id"];
		}
		$model["title"]=$model["title"];
		$model["addr"] = $addr;
		$model["isshow"]="1";
		$model["model_id"]=empty($model["model_id"])?0:$model["model_id"];
		$model["pid"]=$model["pid"];
		$model["orderby"] = 50;
		$model["content"] = $model["content"];
		$model["beizhu"] =  $model["beizhu"];
		$model["parent_path"] = $parent_path;
		$result = $this->wc->Insert($model);
		write_action_log(
		$result['sql'],
		$this->uri->uri_string(),
		login_name(),
		get_client_ip(),
		1,
		"自动添加栏目：" .$model["title"]."生成ID:".$result['insert_id']);
		return $result['insert_id'];
	}
		

}
