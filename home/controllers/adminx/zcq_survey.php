<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//调查问卷控制器
class zcq_survey extends MY_Controller {
	var $data = array();
	function zcq_survey(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_user','user');
		$this->load->model('M_zcq_survey','survey');
		$this->load->model('M_zcq_diaocha_wenti','wenti');
		$this->load->model('M_zcq_diaocha_canjia','canjia');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		$this->data["config"] = $this->parent_sysconfig();
		//底部政府网站部门以及友情链接等    
        $this->data["finfo"] = $this->parent_getfinfo();
		$this->data['curset'] = 'survey';//显示菜单
		
	}	

	//问卷列表页
	function index(){
		// var_dump($this->data["sess"]);exit;
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
		$search['isshow'] = 1;//显示的调查问卷	
		$userid = $this->data["sess"]['userid'];//用户id
		$usertype = $this->data["sess"]['usertype'];//用户类型，企业（投资者）45063,机构45064
		$quanxian = array('45063' => 1, '45064' => 2);//调查问卷所属的用户类型
		$search['quanxian'] = $quanxian[$usertype];//搜索该用户类型所能显示的调查问卷
		$list = $this->survey->GetInfoList($pageindex,$pagesize,$search,$orderby);
		foreach ($list['list'] as $key => $value) {//循环判断是否已经参与了该调查问卷
			$where = " isdel='0' and diaocha_id='{$value['id']}' 
						and create_userid='$userid'";
			if ($this->canjia->count($where)>0) {//已参与
				$list['list'][$key]['iscanyu'] = true;
			} else {
				$list['list'][$key]['iscanyu'] = false;
			}
		}
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/list",$this->data);		
	}
	
	//进行答卷
	function dosurvey(){		

		$post = $this->input->post();
		// var_dump($post['item_id']);exit;		
		if(is_array($post)){//插入到调查结果表中
			$id = $post["id"];//问题id	
			$diaocha_id = $post['diaocha_id'];//调查id
			$nowTime = time();//当前时间戳
			$model = $this->survey->GetModel($diaocha_id);//获取调查问卷信息
			// echo$model['quanxian'].','.$quanxian[$usertype];exit;
			if(!$post['linkname']||!$post['tel']||!$post['company']){//没有填写联系人等信息
				$this->parent_showmessage(
                    0
                    ,"请填写完整的信息",
                    site_url("adminx/zcq_survey/dosurvey?id=$diaocha_id"),
                    5,
                    'showmessage');			
			}	
			if(!$this->checkAuth($model)){//无权限参与
				$this->parent_showmessage(
                    0
                    ,"没找到该问卷或者您无权限参与该问卷",
                    site_url("adminx/zcq_survey/index"),
                    5,
                    'showmessage');			
			}	
			foreach ($id as $key => $value) {//循环问题id，将用户回答的问题插入到结果表中
				$wt_model = $this->wenti->GetModel($value);
				if (count($wt_model) <= 0) {//不存在该问题
					continue;
				}
				$cj_model = array('diaocha_id' => $diaocha_id,
							'wenti_id' => $value,
							'create_time' => $nowTime,
							'update_time' => $nowTime,
							'create_userid' => $this->data['sess']['userid'],
							'update_sysuserid' => $this->data['sess']['userid'],
							'isdel' => '0',
							'linkname' => $post['linkname'],
							'tel' => $post['tel'],
							'company' => $post['company'],
							'ip' => getIP());//添加到调查结果表的数据
				if ($wt_model['itemtype'] != '3') {//单选题或多选
					$cj_model['item_id'] = @implode(',', $post['item_id'][$key]);
					$cj_model['other'] = $post['other'][$key];
				} else {//问答题
					$cj_model['content'] = $post['content'][$key];
				}
				$this->canjia->add($cj_model);
			}

			$ls = empty($post["backurl"])?site_url("adminx/zcq_survey/index"):$post["backurl"];//site_url("zcq_survey/edit")."?id=".$id;//
			//showmessage("修改成功",$ls,1,1);
			$this->parent_showmessage(
                    1
                    ,"提交成功，感谢您的参与！",
                    $ls,
                    5,
                    'showmessage');	
		}
		else{
			$get = $this->input->get();
			$id = empty($get["id"])?0:$get["id"];//调查id
			if(!is_numeric($id)){
				$id = 0;	
			}
			if($id==0){
				$this->parent_showmessage(
                    0
                    ,"没找到该问卷",
                    site_url("adminx/zcq_survey/index"),
                    5,
                    'showmessage');	
			}
			$model = $this->survey->GetModel($id);//获取调查问卷信息
			// echo$model['quanxian'].','.$quanxian[$usertype];exit;
			if(!$this->checkAuth($model)){//无权限参与
				$this->parent_showmessage(
                    0
                    ,"没找到该问卷或者您无权限参与该问卷",
                    site_url("adminx/zcq_survey/index"),
                    5,
                    'showmessage');			
			}		
			$this->data['model'] = $model;
			// 搜索其下面的所有问题
			$orderby["id"] = "desc";
			$search = array('isdel' => 0, 'diaocha_id' => $id, 'pid' => 0);
			$list = $this->wenti->GetInfoList(0, 99999, $search, $orderby);
			$itemtype_arr = array('1' => '单选题', '2' => '多选题', '3' => '问答题');
			foreach ($list['list'] as $key => $value) {//循环获取其问题下的选项
				$list['list'][$key]['itemtype_txt'] = $itemtype_arr[$value['itemtype']];
				if ($value['itemtype'] != '3') {//不为问答题，查找其下面的选项
					$list['list'][$key]['xx'] = $this->wenti->GetModelByPid($value['id']);//问题选项信息
				}
			}
			$this->data['wenti'] = $list['list'];
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("adminx/zcq_survey/index");
			}
			$this->data["id"] = $id;
			$this->data['userInfo'] = $this->user->GetModel($this->data['sess']['userid']);
			$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/canyu",$this->data);			
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
			$ls = empty($post["ls"])?site_url("adminx/zcq_survey/index"):$post["ls"];
			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("adminx/zcq_survey/index");
			}
			$this->load->view(__TEMPLET_FOLDER__."/zcq/survey/add",$this->data);		
		}
	}

	//按题目查看调查问卷结果，
	function wt_result_list() {
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		if (empty($get['diaocha_id'])) {
			echo "<script>
			parent.tip_show('无数据',0,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();
		}
		$pagesize = 10;
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
	//判断登录用户是否有权限参与调查问卷,$model 为该调查问卷的信息数组
	private function checkAuth($model) {

		if (count($model) <= 0) {
			return false;
		}
		$userid = $this->data["sess"]['userid'];//用户id
		$usertype = $this->data["sess"]['usertype'];//用户类型，企业（投资者）45063,机构45064
		$quanxian = array('45063' => '1', '45064' => '2');//调查问卷所属的用户类型
		if ($model["isdel"]=="1"||$model["isshow"]=="0"
				||strripos($model['quanxian'], $quanxian[$usertype])===false) {
			return false;
		} else {
			return true;
		}
	}
}