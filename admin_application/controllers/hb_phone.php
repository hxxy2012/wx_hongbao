<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//手机控制器
class hb_phone extends MY_Controller {
	var $data;
	function hb_phone(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_hb_tel','hb_tel');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//手机列表页
	function index(){
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
		$list = $this->hb_tel->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/hb/phone/list",$this->data);		
	}
	
	//编辑手机页
	function edit(){		
		$post = $this->input->post();		
		if(is_array($post)){
			$ls = empty($post["backurl"])?site_url("hb_phone/index"):$post["backurl"];//site_url("hb_phone/edit")."?id=".$id;//
			$id = $post['id'];
			if ($this->check_repeat_phone($post['tel'], $id)) {
				echo "<script>
				parent.tip_show('该号码已经存在，请不要重复添加',0,1000);
				var url = \"$ls\";
				parent.flushpage(url);					
				setTimeout(\"top.topManager.closePage();\",1000);
				</script>";			
				die();	
			}
			$model['id']  = $id;
			$model['tel'] = $post['tel'];//手机号码
			$model['beizhu']  = $post['beizhu'];//备注
			// $model['create_time'] = time();//无更新时间注释
			$this->hb_tel->update($model);
			//showmessage("修改成功",$ls,1,1);
			echo "<script>
			parent.tip_show('修改成功',1,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();	
		}
		else{
			$get = $this->input->get();
			$id = empty($get["id"])?0:$get["id"];
			if(!is_numeric($id)){
				$id = 0;	
			}
			if($id==0){
				showmessage("无数据","hb_phone/index",3,0);			
			}
			$model = $this->hb_tel->GetModel($id);
			$this->data['model'] = $model;
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_phone/index");	
			}
			$this->data["id"] = $id;
			$this->load->view(__TEMPLET_FOLDER__."/hb/phone/edit",$this->data);			
		}
	}
	//添加手机号页
	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
			$ls = empty($post["ls"])?site_url("hb_phone/index"):$post["ls"];
			$tels = $post["tel"];//手机号码，以英文逗号分隔
			$tel_arr = @explode(',', $tels);//打散为数组
			$tel_arr = array_unique($tel_arr);//去掉重复项
			$model['create_time'] = time();
			foreach ($tel_arr as $key => $value) {
				if ($this->check_repeat_phone($value)) {//已经存在数据库的不添加
					continue;
				}
				$model['tel'] = $value;
				$model['beizhu']  = $post['beizhu'];//备注
				$newid = $this->hb_tel->add($model);
			}
			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_phone/index");	
			}
			$this->load->view(__TEMPLET_FOLDER__."/hb/phone/add",$this->data);		
		}
	}
	
	//删除手机
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->M_common->del_data("delete from hb_tel where id='$v'");
			}
		}
		//echo "ok";
	}
	//检查手机号是已经存在数据库，是返回true，否则false，$tel手机号，$id不为该id可以为空
	function check_repeat_phone($tel, $id='') {

		$where = " tel='$tel'";
		if ($id) {
			$where .= " and id!='$id'";
		}
		$list = $this->hb_tel->getlist($where);
		if (is_array($list)&&count($list)>0) {
			return true;
		} else {
			return false;
		}
	}
	//ajax检查手机号是已经存在数据库，是返回true，否则false，$tel手机号，$id不为该id可以为空
	function check_repeat_phone_ajax() {

		$result = array('code' => '-1', 'info' => '服务器错误，请刷新重试');
		$get = $this->input->get();
		$id  = $get['id'];
		$tel = $get['tel'];
		$where = " tel='$tel' and id!='$id'";
		$list = $this->hb_tel->getlist($where);
		if (is_array($list)&&count($list)>0) {
			$result['code'] = 1;
			$result['info'] = '系统中已经存在该手机号码，请不要重复添加';
		} else {
			$result['code'] = 0;
			$result['info'] = '无重复手机号码';
		}
		echo json_encode($result);exit;
	}
}