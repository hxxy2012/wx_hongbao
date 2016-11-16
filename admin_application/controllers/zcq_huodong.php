<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//活动控制器
class zcq_huodong extends MY_Controller {
	var $data;
	function zcq_huodong(){
		parent::__construct();
		@mkdir("data/upload/huodong");
		$this->upload_path = __ROOT__ . "/data/upload/huodong/".date("Y")."/";
		$this->upload_path_save = "data/upload/huodong/".date("Y")."/";
		$this->load->model('M_common');
		$this->load->model('M_user','user');
		$this->load->model('M_zcq_huodong','huodong');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//活动列表页
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
		if(!empty($get["status"])){
			$search["status"] =  trim($get["status"]);
			$search_val["status"] = $get["status"];
		}	
		$list = $this->huodong->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/huodong/list",$this->data);		
	}
	
	//编辑活动页
	function edit(){		
		$post = $this->input->post();		
		if(is_array($post)){
			
			
			$id = $post["id"];	
			$nowTime = time();//当前时间戳
			$admin_id = admin_id();//管理员id
			$model['id'] = $id;
			$model["title"] = trim($post["title"]);//主题
			$model["isshow"] = $post["isshow"];//是否可视
			$model["content"] = $post["content"];//活动简介
			$model["pnum"] = $post["pnum"];//限制人数
			$model["starttime"] = strtotime($post["starttime"]);//活动简介
			$model["endtime"] = strtotime($post["endtime"]);//活动简介
			$model["baoming_start"] = strtotime($post["baoming_start"]);//活动简介
			$model["baoming_end"] = strtotime($post["baoming_end"]);//活动简介
			$model["updatetime"] = $nowTime;
			$model["update_sysuserid"] = $admin_id;	
			//上传附件
			if (!is_dir($this->upload_path)){
				@mkdir($this->upload_path);	
			}
			$this->load->library("common_upload");
			$thumb = $this->common_upload->upload_path(
					$this->upload_path, 
					'thumb',
					'png|jpg|gif|bmp'
					);	
			// echo $thumb;exit;		
			if($thumb!=""){//有上传的文件，将旧文件删除
				$thumb = $this->upload_path_save.$thumb;
				$info = $this->huodong->GetModel($id);//获取之前的信息
				@unlink(__ROOT__ . '/' . $info['thumb']);
				$model['thumb'] = $thumb;
			}		
			$this->huodong->update($model);

			$ls = empty($post["backurl"])?site_url("zcq_huodong/index"):$post["backurl"];//site_url("zcq_huodong/edit")."?id=".$id;//
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
				showmessage("无数据","zcq_huodong/index",3,0);			
			}
			$model = $this->huodong->GetModel($id);
	
			if($model["isdel"]=="1"){
				showmessage("无数据","zcq_huodong/index",3,0);			
			}		
			
			$this->data['model'] = $model;
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_huodong/index");	
			}
			$this->data["id"] = $id;
			$this->load->view(__TEMPLET_FOLDER__."/zcq/huodong/edit",$this->data);			
		}
	}
	//添加活动页
	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
			$nowTime = time();//当前时间戳
			$admin_id = admin_id();//管理员id
			$model["title"] = trim($post["title"]);//主题
			$model["isshow"] = $post["isshow"];
			$model["content"] = $post["content"];//活动简介
			$model["starttime"] = strtotime($post["starttime"]);//活动简介
			$model["endtime"] = strtotime($post["endtime"]);//活动简介
			$model["baoming_start"] = strtotime($post["baoming_start"]);//活动简介
			$model["baoming_end"] = strtotime($post["baoming_end"]);//活动简介
			$model["pnum"] = $post["pnum"];//限制人数
			$model["createtime"] = $nowTime;
			$model["create_sysuserid"] = $admin_id;
			$model["updatetime"] = $nowTime;
			$model["update_sysuserid"] = $admin_id;			
			$model["isdel"] = '0';
			//上传附件
			if (!is_dir($this->upload_path)){
				@mkdir($this->upload_path);	
			}
			$this->load->library("common_upload");
			$thumb = $this->common_upload->upload_path(
					$this->upload_path, 
					'thumb',
					'png|jpg|gif|bmp'
					);	
			// echo $thumb;exit;		
			if($thumb!=""){
				$thumb = $this->upload_path_save.$thumb;
			}
			// echo $thumb;exit;
			$model['thumb'] = $thumb;

			$newid = $this->huodong->add($model);
			
			$ls = empty($post["ls"])?site_url("zcq_huodong/index"):$post["ls"];
			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_huodong/index");	
			}
			$this->load->view(__TEMPLET_FOLDER__."/zcq/huodong/add",$this->data);		
		}
	}
	
	//删除活动
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->huodong->del($v);
			}
		}
		echo "ok";
	}
}