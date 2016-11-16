<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

/**
 * Class Admin
 * @property M_user $user 用户模型
 * @property m_zcq_fuwu_zixun $zixun 服务咨询模型
 */
class Admin extends MY_Controller{	
	var $data;
	function Admin(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_swj_register_xiehuiorjigou','xhuser');	
		$this->load->model('M_swj_register_dsqy','qyuser');	
		$this->load->model('M_swj_info_pub','msip');//信息通知	
		$this->load->model('m_swj_register_dsqy_tmp','qyuser_tmp');
		$this->load->model('m_swj_register_xh_tmp','xhuser_tmp');

		$this->load->model('m_zcq_pro_shenqing','zcq_pro_shenqing');//资金申请模型
		$this->load->model('m_zcq_mail','zcq_mail');//站内信模型
		$this->load->model('M_zcq_fuwu_zixun', 'zixun');//服务咨询模型
		$this->load->model('M_zcq_survey','survey');//调查问卷模型
		$this->load->model('M_zcq_diaocha_canjia','canjia');//调查问卷参加模型
		$this->data["sess"] = $this->parent_getsession();
        $this->data["config"] = $this->parent_sysconfig();
        //底部政府网站部门以及友情链接等    
        $this->data["finfo"] = $this->parent_getfinfo();
		$this->data['curset'] = 'admin_index';//显示菜单
	}
	
	function index(){
		$userid = $this->data["sess"]['userid'];//用户id
		$usertype = $this->data["sess"]['usertype'];//用户类型，企业（投资者）45063,机构45064
		//判断有无完成注册时的详细资料填写，如果无或未审核通过则跳到登记页，
		if($usertype=="45063"){//企业用户的找到我的资金申请列表
			//企业用户  	
	        $search      = array("t1.create_userid" => "='".$userid."'");
	        $orderby     = array("check_status" => "asc", "id" => "desc");
	        // $search["t1.check_status"]  = "='0'";//未审的
	        $this->data['list_mimesb'] = $this->zcq_pro_shenqing->GetInfoList(1,5,$search,$orderby);
		}
		/*if($this->data["sess"]["usertype"]=="45064"){
			//协会或机构用户  
			$model = $this->xhuser->GetModelFromUserID($userid);
		}*/
		// 最新站内信
		$search   = array("t1.receive_userid" => "='".$userid."'");
		$orderby  = array("isread" => "asc", "id" => "desc");
		$this->data['list_znx'] = $this->zcq_mail->GetInfoList(1,5,$search,$orderby);
		//最新服务咨询
        $orderby  = array("receive_isread" => "asc", "id" => "desc");
        $search = array();
        if ($usertype=="45063") {//企业
            $this->data['list_fwzx'] = $this->zixun->getList_qy(1,5,$search,$orderby,$userid);
        }else{//机构
            $this->data['list_fwzx'] = $this->zixun->getList_jigou(1,5,$search,$orderby,$userid);
        }
		//最新调查问卷
		$this->data['list_survey'] = $this->getNewSurvey();
		// var_dump($this->data['list_survey']);exit;
		$this->load->view(__TEMPLET_FOLDER__."/admin/index",$this->data);
	}
	//后台首页页面
	function main() {
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$orderby["id"] = "desc";//倒序
		$search = array();//不限制
		$limit = 10;//限制10条
		$data['sess'] = $this->data["sess"];
		$data['list_pub']  = $this->msip->GetInfoLimit($limit, $search, $orderby);//获取信息通知
		$data['list_pub']  = $this->addStatusInfo($data['list_pub']);//将信息加上已读和未读状态
		// var_dump($data['list_pub']);exit;
		$data['pub_total'] = $this->msip->count('1=1');
		// var_export($data);exit;
		//检查资料修改审核
		if($this->data["sess"]["usertype"]=="45063"){
			//企业用户	
			$data["qyuser_tmp_model"] = $this->qyuser_tmp->GetModelFromUserID($this->data["sess"]["userid"]);
		}
		if($this->data["sess"]["usertype"]=="45064"){
			//电商用户
			$data["xhuser_tmp_model"] = $this->xhuser_tmp->GetModelFromUserID($this->data["sess"]["userid"]);			
		}
		$this->load->view(__TEMPLET_FOLDER__."/admin/main",$data);
	}
	
	function kaifa(){
			$this->parent_showmessage(
						0
						,"功能开发中",
						site_url("admin/main"),
						999999
						);
				exit();			
	}
	//将信息加上已读和未读状态,$list_pub信息列表数组
	private function addStatusInfo($list_pub) {
		$uid = $this->data["sess"]['userid'];//登录用户id
		foreach ($list_pub as $key => $value) {
			$infoid = $value['id'];//信息id
			//判断该用户是否已读该信息,如果已读status为1否则为0
			$sql    =  "select id from `swj_info_read` where uid='$uid' and infoid='$infoid'";
			$data   =  $this->M_common->query_one($sql);
			if (count($data) > 0) {//已读
				$list_pub[$key]['status'] = 1;
			} else {//未读
				$list_pub[$key]['status'] = 0;
			}
		}
		return $list_pub;
	}
	//获取最新的调查问卷（该用户可看的）
	private function getNewSurvey() {
		$userid = $this->data["sess"]['userid'];//用户id
		$usertype = $this->data["sess"]['usertype'];//用户类型，企业（投资者）45063,机构45064
		$orderby["id"] = "desc";
		$search['isshow'] = 1;//显示的调查问卷	
		$quanxian = array('45063' => 1, '45064' => 2);//调查问卷所属的用户类型
		$search['quanxian'] = $quanxian[$usertype];//搜索该用户类型所能显示的调查问http://v.qq.com/x/cover/g6qwozxv09oheq1/h0187n39j4o.html?ptag=qqbrowser&ADTAG=zd-winqb9卷
		$list_survey = $this->survey->GetInfoList(1,5,$search,$orderby);
		foreach ($list_survey['list'] as $key => $value) {//循环判断是否已经参与了该调查问卷
			$where = " isdel='0' and diaocha_id='{$value['id']}' 
						and create_userid='$userid'";
			if ($this->canjia->count($where)>0) {//已参与
				$list_survey['list'][$key]['iscanyu'] = true;
			} else {
				$list_survey['list'][$key]['iscanyu'] = false;
			}
		}
		return $list_survey;
	}
}