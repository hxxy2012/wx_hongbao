<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//调查问卷控制器
class zcq_survey extends MY_Controller {
	var $data;
	function zcq_survey(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_hb_hongbao_list','hblist');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//问卷列表页
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
		$list = $this->survey->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/list",$this->data);		
	}
	
	//编辑问卷页
	function edit(){		
		$post = $this->input->post();		
		if(is_array($post)){
			
			
			$id = $post["id"];	
			$nowTime = time();//当前时间戳
			$admin_id = admin_id();//管理员id
			$model['id'] = $id;
			$model["title"] = trim($post["title"]);//主题
			$model['quanxian'] = @implode(',', $post['quanxian']);//选择的答卷对象
			$model["isshow"] = $post["isshow"];
			$model["content"] = $post["content"];
			$model["update_time"] = $nowTime;
			$model["update_sysuserid"] = $admin_id;			
			$this->survey->update($model);

			$ls = empty($post["backurl"])?site_url("zcq_survey/index"):$post["backurl"];//site_url("zcq_survey/edit")."?id=".$id;//
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
				showmessage("无数据","zcq_survey/index",3,0);			
			}
			$model = $this->survey->GetModel($id);
	
			if($model["isdel"]=="1"){
				showmessage("无数据","zcq_survey/index",3,0);			
			}		
			
			$this->data['model'] = $model;
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_survey/index");	
			}
			$this->data["id"] = $id;
			$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/edit",$this->data);			
		}
	}
	//添加问卷页
	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
			$nowTime = time();//当前时间戳
			$admin_id = admin_id();//管理员id
			$model["title"] = trim($post["title"]);//主题
			$model['quanxian'] = @implode(',', $post['quanxian']);//选择的答卷对象
			$model["isshow"] = $post["isshow"];
			$model["content"] = $post["content"];
			$model["create_time"] = $nowTime;
			$model["create_sysuserid"] = $admin_id;
			$model["update_time"] = $nowTime;
			$model["update_sysuserid"] = $admin_id;			
			$model["isdel"] = '0';

			$newid = $this->survey->add($model);
			//保存中间表问题表
			//zcq_diaocha_wenti
			$wt_title_more = $post["wt_title_more"];//答卷问题数组
			foreach($wt_title_more as $k => $v){
				$wtmodel["itemtype"] = $post['wt_itemtype_more'][$k];//选项类型
				$wtmodel["isother"] = $post['isother_more'][$k];//问题设置，遇到其他字眼时是否增加输入框
				$wtmodel["pid"] = 0;//为问题
				$wtmodel["title"] = $v;//标题
				$wtmodel["diaocha_id"] = $newid;//答卷id
				$wtmodel["create_time"] = $nowTime;//创建时间
				$wtmodel["update_time"] = $nowTime;//最后更新时间
				$wtmodel["create_sysuserid"] = $admin_id;//创建id
				$wtmodel["update_sysuserid"] = $admin_id;//更新id
				$wtpid = $this->wenti->add($wtmodel);
				if ($post['wt_itemtype_more'][$k] != 3) {//如果该问题不为问答题的，将其传过来的题目选项也插入到问题表中
					$wt_xx = $post['wt_all_add_more'][$k];//题目选项,为字符串
					$wt_xx_arr = @explode("\n", $wt_xx);//打散为数组
					foreach ($wt_xx_arr as $k1 => $v1) {//将选项循环插入到表中
						$wtmodel1 = array('pid' => $wtpid,
										'diaocha_id' => $newid,
										'title' => $v1,
										'create_time' => $nowTime,
										'update_time' => $nowTime,
										'create_sysuserid' => $admin_id,
										'update_sysuserid' => $admin_id);
						$this->wenti->add($wtmodel1);
					}
				}
				
			}
			$ls = empty($post["ls"])?site_url("zcq_survey/index"):$post["ls"];
			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_survey/index");	
			}
			$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/add",$this->data);		
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
				$this->survey->del($v);
			}
		}
		//echo "ok";
	}
	
	//按题目查看调查问卷结果，
	function wt_result_list() {
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$ls = empty($get["ls"])?site_url("zcq_survey/index"):$get["ls"];
		if (empty($get['diaocha_id'])) {
			echo "<script>
			parent.tip_show('无数据',0,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();
		}
		$pagesize = 20;
		$search = array();

		$diaocha_id = $get['diaocha_id'];
		$search['diaocha_id'] = $diaocha_id;//调查id
		$orderby["id"] = "desc";
		if(!empty($get["search_title"])){
			$search["title"] =  trim($get["search_title"]);
		}		
		$search['pid'] = 0;//找出所有的问题
		$list = $this->wenti->GetInfoList($pageindex,$pagesize,$search,$orderby);
		//根据问题id，列出对应的选项跟数量
        foreach ($list["list"] as $key => $value) {
        	$wtpid = $value['id'];//问题id
        	$itemtype = $value['itemtype'];//问题类型
        	$wt_xx_str = '';//存储问题的选项
        	if ($itemtype != 3) {//如果不为问答题的，找出问题下的选项信息
        		$wt_xx = $this->wenti->GetModelByPid($wtpid);//问题选项信息
	        	$wt_total_hd = $this->canjia->gettotalxx(" diaocha_id='$diaocha_id' and wenti_id='$wtpid'");
	        	foreach ($wt_xx as $k => $v) {
	        		$xx_id = $v['id'];//选项id
	        		$sql = " diaocha_id='$diaocha_id' and wenti_id='$wtpid' and (item_id='$xx_id' or item_id like '%$xx_id,%' or item_id like '%,$xx_id,%' or item_id like '%,$xx_id')";
	        		$xx_total_hd = $this->canjia->count($sql);//选项总数
	        		$zb = @round($xx_total_hd/$wt_total_hd,4)*100;//占比
	        		if ($k==0) {//是第一次的直接赋值
	        			$wt_xx_str .= $v['title'] . '(数量：' . $xx_total_hd . ',占比：' . $zb . '%)';
	        		} else {
	        			$wt_xx_str .= '<br/>' . $v['title'] . '(数量：' . $xx_total_hd . ',占比：' . $zb . '%)';
	        		}
	        	}
	        	$list['list'][$key]['wt_xx_str'] = $wt_xx_str;
	        	if ($itemtype == 1) {//单选题
	        		$list['list'][$key]['title_add'] = '(<span style="color:red;">单选题</span>)';
	        	} else {//多选题
	        		$list['list'][$key]['title_add'] = '(<span style="color:red;">多选题</span>)';
	        	}
        	} else {//是问答题的直接显示问题
        		$wt_total_hd = $this->canjia->count(" diaocha_id='$diaocha_id' and wenti_id='$wtpid'");
        		$list['list'][$key]['wt_xx_str'] = $value['title'];
        		$list['list'][$key]['title_add'] = '(<span style="color:red;">问答题</span>)';
        	}
        	$list['list'][$key]['wt_total_hd'] = $wt_total_hd;//回答该问题的总数
        }
        // $list['list']['total_hd'] = $this->canjia->count(" diaocha_id='$diaocha_id' ");//总回答该调查问卷数量
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/canjia_wt_list",$this->data);
	}
	//调查问卷问题回答详情
	function wt_cj_detail() {
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$ls = empty($get["ls"])?site_url("zcq_survey/index"):$get["ls"];
		if (empty($get['diaocha_id'])||empty($get['wenti_id'])) {
			echo "<script>
			parent.tip_show('无数据',0,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();
		}
		$pagesize = 20;
		$search = array();
		if(!empty($get["username"])){
			$search["username"] =  trim($get["username"]);
		}
		$orderby["id"] = "desc";
		$search['diaocha_id'] = $get['diaocha_id'];//调查id
		$search['wenti_id'] = $get['wenti_id'];	//问题id
		$list = $this->canjia->GetInfoList($pageindex,$pagesize,$search,$orderby);
		foreach ($list['list'] as $key => $value) {//循环列表信息获取该回答详情
			$itemtype = $value['itemtype'];//问题类型
			$item_id = $value['item_id'];//选项id
			if ($itemtype == 3) {//为问答题的读content字段
				$list['list'][$key]['hd'] = $value['content'].'(<span style="color:red;">问答题</span>)';
			} else{//选择题
				$xxidarr = @explode(',', $item_id);
				foreach ($xxidarr as $k1 => $v1) {
					$xxInfo = $this->wenti->GetModel($v1);
					if ($k1 == 0) {//第一次进入直接赋值
						$list['list'][$key]['hd']  = $xxInfo['title'];
					} else {
						$list['list'][$key]['hd'] .= ','.$xxInfo['title'];
					}
				}
				if ($itemtype == 2) {//为多选题
					$list['list'][$key]['hd'] .= '(<span style="color:red;">多选题</span>)';
				} else {
					$list['list'][$key]['hd'] .= '(<span style="color:red;">单选题</span>)';
				}
			}
		}
		$this->data["list"]  = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/canjia_wt_detail_list",$this->data);
	}
	//按会员查看调查问卷结果，
	function hy_result_list() {
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$ls = empty($get["ls"])?site_url("zcq_survey/index"):$get["ls"];
		if (empty($get['diaocha_id'])) {
			echo "<script>
			parent.tip_show('无数据',0,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();
		}
		$pagesize = 20;
		$search = array();
		$diaocha_id = $get['diaocha_id'];
		$search['diaocha_id'] = $diaocha_id;//调查id
		$orderby["id"] = "desc";
		if(!empty($get["realname"])){//查询联系人
			$search["realname"] =  trim($get["realname"]);
		}			
		$list = $this->canjia->GetHyInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/canjia_hy_list",$this->data);
	}
	//调查问卷会员回答详情
	function hy_cj_detail() {
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$ls = empty($get["ls"])?site_url("zcq_survey/index"):$get["ls"];
		// var_dump($get);exit;
		if (empty($get['diaocha_id'])||empty($get['linkname'])) {
			echo "<script>
			parent.tip_show('无数据',0,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();
		}
		$pagesize = 20;
		$search = array();
		if(!empty($get["wtname"])){
			$search["wtname"] =  trim($get["wtname"]);
		}
		$orderby["id"] = "desc";
		$search['diaocha_id'] = $get['diaocha_id'];//调查id
		$search['linkname'] = $get['linkname'];//用户id
		$list = $this->canjia->GetInfoList($pageindex,$pagesize,$search,$orderby);
		foreach ($list['list'] as $key => $value) {//循环列表信息获取该回答详情
			$itemtype = $value['itemtype'];//问题类型
			$item_id = $value['item_id'];//选项id
			if ($itemtype == 3) {//为问答题的读content字段
				$list['list'][$key]['hd'] = $value['content'];
				$list['list'][$key]['title_add'] = '(<span style="color:red;">单选题</span>)';
			} else{//选择题
				$xxidarr = @explode(',', $item_id);
				foreach ($xxidarr as $k1 => $v1) {
					$xxInfo = $this->wenti->GetModel($v1);
					if ($k1 == 0) {//第一次进入
						$list['list'][$key]['hd'] = $xxInfo['title'];
					} else {
						$list['list'][$key]['hd'] .= ','.$xxInfo['title'];
					}
				}
				if ($itemtype == 1) {//单选题
	        		$list['list'][$key]['title_add'] = '(<span style="color:red;">单选题</span>)';
	        	} else {//多选题
	        		$list['list'][$key]['title_add'] = '(<span style="color:red;">多选题</span>)';
	        	}
			}
		}
		$this->data["list"]  = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/canjia_hy_detail_list",$this->data);
	}
	//删除调查结果详情
	function del_canjia() {
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->canjia->del($v);
			}
		}
	}
}