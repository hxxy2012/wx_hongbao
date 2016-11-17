<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//中奖控制器
class hb_prize extends MY_Controller {
	var $data;
	function hb_prize(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_hb_hongbao_list','hblist');
		$this->load->model('M_hb_hongbao_set','hbset');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//中奖列表页
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
		$hb_list = $this->hbset->getlist('1=1');
		$this->data['hb_list'] = $hb_list;//红包活动列表
		if (!isset($get['hb_sid'])||empty($get['hb_sid'])) {//默认显示最后一轮的红包活动
			$search['hb_sid'] = @$hb_list[0]['id'];
			$search_val['hb_sid'] = @$hb_list[0]['id'];
		} else {
			$search['hb_sid'] = $get['hb_sid'];
			$search_val['hb_sid'] = $get['hb_sid'];
		}
		$list = $this->hblist->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/hb/prize/list",$this->data);		
	}
	
	
	//删除中奖
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->M_common->del_data("delete from hb_hongbao_list where id='$v'");
			}
		}
		//echo "ok";
	}
	
}