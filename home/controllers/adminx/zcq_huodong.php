<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//活动控制器
class zcq_huodong extends MY_Controller {
	var $data;
	function zcq_huodong(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_user','user');
		$this->load->model('M_zcq_huodong','huodong');
		$this->load->model('M_zcq_hdbaoming','hdbaoming');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		$this->data["config"] = $this->parent_sysconfig();
		//底部政府网站部门以及友情链接等    
        $this->data["finfo"] = $this->parent_getfinfo();
		$this->data['curset'] = 'huodong';//显示菜单
		
	}	

	//活动列表页
	function index(){
		// echo $this->data['sess']['userid'];exit;
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$pagesize = 20;
		$search = array();
		$search_val = array();
		$search_val["title"] = "";
		$orderby["id"] = "desc";
		if(!empty($get["search_title"])){
			$search["title"] =  trim($get["search_title"]);
			$search_val["title"] = $get["search_title"];
		}	
		if(!empty($get["status"])){
			$search["status"] =  trim($get["status"]);
			$search_val["status"] = $get["status"];
		}	
		// 根据是否带userid判断二级菜单
		if (!empty($get['userid'])) {//我的活动
			$search['userid'] = $this->data['sess']['userid'];
			$search_val["userid"] = $get["userid"];
			$this->data['sec_curset'] = 'mime_hd';
		} else {//活动列表
			$this->data['sec_curset'] = 'hd_list';
		}
		$search["isshow"] = '1';//只显示可公开的活动
		$list = $this->huodong->GetInfoList($pageindex,$pagesize,$search,$orderby);
		//循环活动，获取报名活动的人数
		foreach ($list['list'] as $key => $value) {
			$where = " isdel='0' and huodong_id='{$value['id']}'";
			$list['list'][$key]['bmnum'] = $this->hdbaoming->count($where);
			if ($this->checkBm($value['id'])) {//判断是否已经报名，是
				$list['list'][$key]['isbm'] = '是';
			} else {
				$list['list'][$key]['isbm'] = '否';
			}
		}
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/huodong/list",$this->data);		
	}
	
	//查看活动详情页
	function look(){		
		$get = $this->input->get();
		$id = empty($get["hdid"])?0:$get["hdid"];//活动id
		if(!is_numeric($id)){
			$id = 0;	
		}
		if($id==0){
			$this->parent_showmessage(
                0
                ,"没有找到该活动，请您刷新重试",
                site_url("zcq_huodong/index"),
                5,
                'showmessage');	
		}
		$model = $this->huodong->GetModel($id);

		if(!isset($model['isdel'])||$model["isdel"]=="1"){//不存在或者已删除
			$this->parent_showmessage(
                0
                ,"没有找到该活动，请您刷新重试",
                site_url("zcq_huodong/index"),
                5,
                'showmessage');			
		}		
		// if ($this->checkHdAuth($id)) {//是否有权限

		// }
		$this->data['model'] = $model;
		if($this->data["ls"]==""){
			$this->data["ls"]=site_url("zcq_huodong/index");	
		}

		//这个活动本用户是否已经报名
        $this->data['isbaoming'] = $this->checkBm($id);

		$this->data["id"] = $id;
		$this->load->view(__TEMPLET_FOLDER__."/zcq/huodong/look",$this->data);			
	}
	//ajax 进行活动报名
	function doBm() {
		$result = array('code' => -1,'info' => '系统出错，请您刷新重试！');
		$post = $this->input->post();
		$hdid = (int)$post['hdid'];//活动id
		if ($hdid<=0) {//数据非法
			$result['info'] = '没有找到该活动，请您刷新重试！';
			echo json_encode($result);exit;
		}
		$model = $this->huodong->GetModel($hdid);//获取活动信息
		if (!isset($model['isdel'])||$model['isdel']==1) {
			$result['info'] = '没有找到该活动，请您刷新重试！';
			echo json_encode($result);exit;
		}
		$nowTime = time();//当前时间
		if ($model['baoming_start']>$nowTime||$model['baoming_end']<$nowTime) {
			$result['info'] = '活动报名还未开始或者已经结束！';
			echo json_encode($result);exit;
		}
		if ($this->checkBm($hdid)) {//已经报名
			$result['code'] = 1;
			$result['info'] = '您已经报名该活动，请不要重复报名！';
			echo json_encode($result);exit;
		}
		//进行报名，插入到报名表中
		$model_bm = array('huodong_id' => $hdid, 
						'userid' => $this->data["sess"]['userid'],
						'isdel' => '0',
						'create_sysuserid' => $this->data["sess"]['userid'],
						'createtime' => $nowTime,
						'update_sysuserid' => $this->data["sess"]['userid'],
						'updatetime' => $nowTime);
		$newid = $this->hdbaoming->add($model_bm);
		if ($newid > 0) {
			$result['code'] = 0;
			$result['info'] = '报名成功，感谢您的参与！';
		}
		echo json_encode($result);
	}
	//判断活动是否已经报名是返回true，否false
	private function checkBm($hdid) {

		$where = " isdel='0' and huodong_id='$hdid' and userid='{$this->data["sess"]['userid']}' ";
		$num = $this->hdbaoming->count($where);
		if ($num > 0) {
			return true;
		} else {
			return false;
		}
	}
}