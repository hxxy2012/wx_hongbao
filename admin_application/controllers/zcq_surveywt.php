<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}

/**
 * 调查问卷问题控制器
 * Class zcq_surveywt
 * @property M_zcq_diaocha_wenti $wenti
 */
class zcq_surveywt extends MY_Controller {
	var $data;
	function zcq_surveywt(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_user','user');
		$this->load->model('M_zcq_survey','survey');
		$this->load->model('M_zcq_diaocha_wenti','wenti');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//问题列表页
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
		$search_val['diaocha_id'] = '';
		//如果存在调查问卷id的，搜索该调查问卷id
		if(!empty($get["diaocha_id"])){
			$search["diaocha_id"] 		=  intval($get["diaocha_id"]);
			$search_val["diaocha_id"]  =  $get["diaocha_id"];
		}
		$search_val["pid"] = '';
		/*if(!empty($get["pid"])&&$get["pid"]=='1'){
			$search['pid'] = 0;//只显示问题，不显示选项
			$search_val["pid"]  =  1;
		} else if (!empty($get["pid"])&&$get["pid"]=='2'){
			$search['pid'] = 1;//只显示选项，不显示问题
			$search_val["pid"]  =  2;
		}*/
		//列表页只显示问题
		$search['pid'] = 0;//只显示问题，不显示选项
		$search_val["pid"]  =  1;
		$list = $this->wenti->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];
		//获取调查问卷列表
		$suvInfo = $this->survey->GetInfoList(0,99999,array(),'id desc');
		$this->data["suv_list"] = $suvInfo['list'];
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/wt_list",$this->data);		
	}
	
	//编辑问题页
	function edit(){		
		$post = $this->input->post();		
		if(is_array($post)){
			
			$id = $post["id"];	
			$nowTime = time();//当前时间戳
			$admin_id = admin_id();//管理员id
			$model['id'] = $id;
			$model["title"] = trim($post["title"]);//主题
			$model["itemtype"] = $post["itemtype"];//题目类型
			$model["isother"] = isset($post["isother"])?$post['isother']:'0';//选项为其他时增加输入框
			$model["orderby"] = $post["orderby"];//排序
			$model["content"] = $post["content"];//备注
			$model["update_time"] = $nowTime;
			$model["update_sysuserid"] = $admin_id;			
			$this->wenti->update($model);
			//更新题目下的选项，先将该题目下的所有选项删除后添加新的题目
			$this->wenti->delByPid($id);
			$wtInfo = $this->wenti->GetModel($id);//题目信息
			$wt_xx = $post['wt_all_add'];//题目选项,为字符串
			$wt_xx_arr = @explode(PHP_EOL, $wt_xx);//打散为数组
			foreach ($wt_xx_arr as $k1 => $v1) {//将选项循环插入到表中
				$wtmodel1 = array('pid' => $id,
								'diaocha_id' => $wtInfo['diaocha_id'],
								'title' => str_replace(PHP_EOL, '', $v1),
								'create_time' => $nowTime,
								'update_time' => $nowTime,
								'create_sysuserid' => $admin_id,
								'update_sysuserid' => $admin_id);
				$this->wenti->add($wtmodel1);
			}
			$ls = empty($post["backurl"])?site_url("zcq_surveywt/index"):$post["backurl"];//site_url("zcq_surveywt/edit")."?id=".$id;//
			//showmessage("修改成功",$ls,1,1);
			echo "<script>
			parent.tip_show('修改成功',1,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();	
		}
		else{//页面
			$get = $this->input->get();
			$id = empty($get["id"])?0:$get["id"];//问题id
			if(!is_numeric($id)){
				$id = 0;	
			}
			if($id==0){
				showmessage("无数据","zcq_surveywt/index",3,0);			
			}
			$model   =  $this->wenti->GetModel($id);//问题信息
			$model_suv  =  $this->survey->GetModel($model['diaocha_id']);//问卷信息
			if($model["isdel"]=="1"){
				showmessage("无数据","zcq_surveywt/index",3,0);			
			}		
			$model_xx = $this->wenti->GetModelByPid($id);//问题下面的选项信息
			$this->data['model'] = $model;
			$this->data['model_xx'] = $model_xx;
			$this->data['model_suv'] = $model_suv;
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_surveywt/index");	
			}
			$this->data["id"] = $id;
			$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/wt_edit",$this->data);			
		}
	}
	//添加问题页
	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
			$nowTime = time();//当前时间戳
			$admin_id = admin_id();//管理员id

			$diaocha_id = $post['diaocha_id'];
			//保存中间表问题表
			//zcq_diaocha_wenti
			$wt_title_more = $post["wt_title_more"];//答卷问题数组
			foreach($wt_title_more as $k => $v){
				$wtmodel["itemtype"] = $post['wt_itemtype_more'][$k];//选项类型
				$wtmodel["isother"] = $post['isother_more'][$k];//问题设置，遇到其他字眼时是否增加输入框
				$wtmodel["pid"] = 0;//为问题
				$wtmodel["title"] = $v;//标题
				$wtmodel["diaocha_id"] = $diaocha_id;//调查问卷id
				$wtmodel["create_time"] = $nowTime;//创建时间
				$wtmodel["update_time"] = $nowTime;//最后更新时间
				$wtmodel["create_sysuserid"] = $admin_id;//创建id
				$wtmodel["update_sysuserid"] = $admin_id;//更新id
				$wtpid = $this->wenti->add($wtmodel);
				if ($post['wt_itemtype_more'][$k] != 3) {//如果该问题不为问答题的，将其传过来的题目选项也插入到问题表中
					$wt_xx = $post['wt_all_add_more'][$k];//题目选项,为字符串
					$wt_xx_arr = @explode(PHP_EOL, $wt_xx);//打散为数组
					foreach ($wt_xx_arr as $k1 => $v1) {//将选项循环插入到表中
						$wtmodel1 = array('pid' => $wtpid,
										'diaocha_id' => $diaocha_id,
										'title' => str_replace(PHP_EOL, '', $v1),
										'create_time' => $nowTime,
										'update_time' => $nowTime,
										'create_sysuserid' => $admin_id,
										'update_sysuserid' => $admin_id);
						$this->wenti->add($wtmodel1);
					}
				}
				
			}
			$ls = empty($post["ls"])?site_url("zcq_surveywt/index"):$post["ls"];
			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_surveywt/index");	
			}
			$this->data['diaocha_id'] = $this->input->get_post('diaocha_id');//调查问卷id
			//获取调查问卷列表
			$suvInfo = $this->survey->GetInfoList(0,99999,array(),array());
			$this->data["suv_list"] = $suvInfo['list'];
			$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/wt_add",$this->data);		
		}
	}
	
	//添加问题选项页
	function addxx(){	
		$post = $this->input->post();
		if(is_array($post)){
			$nowTime = time();//当前时间戳
			$admin_id = admin_id();//管理员id

			$diaocha_id = $post['diaocha_id'];//调查id
			$pid = $post['id'];//父级id
			//保存中间表问题表
			//zcq_diaocha_wenti
			$wt_xx = $post['wt_all_add'];//题目选项,为字符串
			$wt_xx_arr = @explode(PHP_EOL, $wt_xx);//打散为数组
			foreach ($wt_xx_arr as $k1 => $v1) {//将选项循环插入到表中
				$wtmodel1 = array('pid' => $pid,
								'diaocha_id' => $diaocha_id,
								'title' => str_replace(PHP_EOL, '', $v1),
								'create_time' => $nowTime,
								'update_time' => $nowTime,
								'create_sysuserid' => $admin_id,
								'update_sysuserid' => $admin_id);
				$this->wenti->add($wtmodel1);
			}
			$ls = empty($post["ls"])?site_url("zcq_surveywt/index"):$post["ls"];
			// showmessage("新增成功",$ls,3,1);
			echo "<script>
			parent.tip_show('修改成功',1,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_surveywt/index");	
			}
			$this->data['diaocha_id'] = $this->input->get_post('diaocha_id');//调查问卷id
			$this->data['id'] = $this->input->get_post('id');//问题id
			//获取调查问卷列表
			$this->data["suvInfo"] = $this->survey->GetModel($this->data['diaocha_id']);
			$this->data["wtInfo"]  = $this->wenti->GetModel($this->data['id']);
			$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/wt_add_xx",$this->data);		
		}
	}

	//删除问卷
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->wenti->del($v);
			}
		}
		//echo "ok";
	}
	function delcheck(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		$result["err"] = "ok";
		foreach($arr as $v){
			$count = $this->pro_shenbao->count("isdel='0' and checkstatus<>'99' and survey_id='".$v."'");
			if($count>0){
				$result["title"] = "";
				$model = $this->pro->GetModel($v);
				$result["title"] = $model["title"];
				$result["err"] = "no";
				break;
			}
		}
		echo json_encode($result);
		exit();
	}
}