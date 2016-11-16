<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//红包控制器
class hb_index extends MY_Controller {
	var $data;
	function hb_index(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_hb_hongbao_set','hbset');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//红包列表页
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
		$list = $this->hbset->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/hb/index/list",$this->data);		
	}
	
	//编辑红包页
	function edit(){		
		$post = $this->input->post();		
		if(is_array($post)){
			$ls = empty($post["backurl"])?site_url("hb_index/index"):$post["backurl"];//site_url("hb_index/edit")."?id=".$id;//
			if ($post['curr']==1&&$this->checkhasingnoajax($post["id"])) {//如果添加的是正在进行的红包活动则判断
				echo "<script>
				parent.tip_show('已经存在正在进行的红包活动，请先停止该红包活动再添加',0,1000);
				var url = \"$ls\";
				parent.flushpage(url);					
				setTimeout(\"top.topManager.closePage();\",1000);
				</script>";			
				die();
			}
			$id = $post["id"];	
			$oldmodel = $this->hbset->GetModel($id);//之前的数据
			if ($oldmodel['curr'] == '1') {//如果是正在进行的红包活动则只能修改标题跟结束活动
				$model['id'] = $id;
				$model["title"] = trim($post["title"]);//主题
				$model['curr']  = $post['curr'];
			} else {
				$model['id'] = $id;
				$model["title"] = trim($post["title"]);//主题
				$model['curr']  = $post['curr'];//是否正在进行
				$model["suiji"] = $post["suiji"];//随机还固定
				$model["hongbao_shu"] = $post["hongbao_shu"];//红包数
				$model["jine"]  = $post["jine"];//总金额
				$model["qibu_jine"]   = $post["qibu_jine"];//最低红包数量	
				if ($oldmodel['suiji']!=$model['suiji']||$oldmodel['hongbao_shu']!=$model['hongbao_shu']
					||$oldmodel['jine']!=$model['jine']||$oldmodel['qibu_jine']!=$model['qibu_jine']) {
					//如果有其中一个的修改了就进行重新计算红包金额插入到hb_prize表中
					$temp = 1;
				}
			}
				
			$this->hbset->update($model);
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
				showmessage("无数据","hb_index/index",3,0);			
			}
			$model = $this->hbset->GetModel($id);
			$this->data['model'] = $model;
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_index/index");	
			}
			$this->data["id"] = $id;
			$this->load->view(__TEMPLET_FOLDER__."/hb/index/edit",$this->data);			
		}
	}
	//添加红包页
	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
			$ls = empty($post["ls"])?site_url("hb_index/index"):$post["ls"];
			if ($post['curr']==1&&$this->checkhasingnoajax()) {//如果添加的是正在进行的红包活动则判断
				showmessage("已经存在正在进行的红包活动，请先停止该红包活动再添加",$ls,3,0);exit;
			}
			$model["title"] = trim($post["title"]);//红包活动名称
			$model['curr']  = $post['curr'];//正在进行
			$model["suiji"] = $post["suiji"];//随机固定
			$model["hongbao_shu"] = $post["hongbao_shu"];//红包总数
			$model["jine"]  = $post["jine"];//总金额
			$model["qibu_jine"]   = $post["qibu_jine"];//最低红包金额
			$newid = $this->hbset->add($model);
			//通过算法计算出每个红包金额插入到hb_prize表中（暂定）

			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_index/index");	
			}
			$this->load->view(__TEMPLET_FOLDER__."/hb/index/add",$this->data);		
		}
	}
	
	//删除红包
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->M_common->del_data("delete from hb_hongbao_set where id='$v'");
			}
		}
		//echo "ok";
	}
	

	//ajax查询是否存在正在进行的红包,
	function checkhasing() {
		$result = array('code' => '-1', 'info' => '服务器错误，请刷新重试');
		$get = $this->input->get();
		$where  = " curr='1' ";
		if (isset($get['id'])&&$get['id']) {
			$where .= " and id!='{$get['id']}' ";
		}
		$hbInfo = $this->hbset->getlist($where);
		if (count($hbInfo)<=0) {
			$result['code'] = 0;
			$result['info'] = '无正在进行的红包活动';
		} else {
			$result['code'] = 1;
			$result['info'] = $hbInfo[0]['title'].'红包活动正在进行，所有轮红包活动中只能存在一轮正在进行，请先停止该轮红包活动';
		}
		echo json_encode($result);
	}
	//判断是否存在正在进行的红包活动，是返回true，否则false
	private function checkhasingnoajax($id='') {
		$where  = " curr='1' ";
		if ($id) {
			$where .= " and id!='$id' ";
		}
		$hbInfo = $this->hbset->getlist($where);
		if (count($hbInfo)<=0) {
			return false;
		} else {
			return true;
		}
	}
}