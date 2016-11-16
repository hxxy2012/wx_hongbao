<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class Website_model_art extends MY_Controller{

	function Website_model_art(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_website_common_model');		
	}
	
	function add(){
		$this->load->view(__TEMPLET_FOLDER__."/website/website_model_art/add");
	}
	
}	
?>