<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class Swj_admin extends MY_Controller{	
	var $data;
	function Swj_admin(){
		parent::__construct();
		$this->parent_chksession();
		$this->load->model('M_swj_register_xiehuiorjigou','xhuser');
		$this->load->model('M_user','user');
		$this->load->model('M_swj_register_dsqy','qyuser');	
		$this->load->model('M_swj_register_dsqy_tmp','qyuser_tmp');	
		$this->load->model('M_swj_register_xh_tmp','xhuser_tmp');			
		$this->load->model('M_swj_register_xiehuiorjigou','xhuser');
		$this->load->model('M_common_category_data','ccd'); 
		$this->load->model('M_swj_fujian','fj'); 
		$this->load->model('m_common_sms','sms');
		$this->data["sess"] = $this->parent_getsession();
		if($this->data["sess"]["editpwd"]=="1"){
			//需要重置密码	
			header("location:".site_url("home/editpwd"));
		}		
		// var_dump($this->data["sess"]);exit;
	}
	
	function index(){
		//判断有无完成注册时的详细资料填写，如果无或未审核通过则跳到登记页，
		$this->load->view(__TEMPLET_FOLDER__."/admin/index",$this->data);
	}
	
	function useredit(){	
	    //新注册的企业用户修改
		$post = $this->input->post();
		if(is_array($post)){
			$userid = $this->data["sess"]["userid"];
			$model = $this->qyuser->GetModelFromUserID($userid);
			$model2 = $this->getmysqlmodel();		
			foreach($model2 as $k=>$v){
				$model[$k] = $v;
			}			
			//补充
			$model["company_type"] = empty($post["company_type"])?"":(implode(",",$post["company_type"]));
			$model["business_model"] = empty($post["jiaoyi"])?"":(implode(",",$post["jiaoyi"]));
			$model["upload_paper_type"] = $post["upload_paper_type"];
			$model['company_type_other'] = $post["company_type_other"];//其他企业类型
			$model['business_model_other'] = $post["business_model_other"];//其他交易模式
			//根据二级分类，取得主营产品一级分类
			if($model["product2"]!=""){
				$model["product"] = $this->ccd->GetParentFromSub($model["product2"]);				
			}
			//已审或未审时，不通做“临时保存”
			if(isset($model["audit"])){
				if($model["audit"]=="-1" || $model["audit"]=="20"){
					$model["audit"] = $post["audit"];	
				}		
			}
			else{
				$model["audit"] = $post["audit"];	
			}
			$model["lastupdatetime"]=date("Y-m-d H:i:s");
			$model["inputtime"]=date("Y-m-d H:i:s");
			$model["updatetime"]=date("Y-m-d H:i:s");
			if($model["audit"]=="0"){
				//待审发送短信
				$msg = $model["name"]."提交了注册资料，请及时审核。提交人:".$this->data["sess"]["realname"].",联系电话:".$this->data["sess"]["tel"];	
				$this->sms->sendSmsToSystem("企业资料",$this->data["sess"]["userid"], $msg,"");
			}
			if(isset($model["userid"])){
				$this->qyuser->update($model);
			}
			else{
				$model["userid"] = $userid;
				$this->qyuser->add($model);
			}
			
			$this->parent_showmessage(
					1
					,($post["audit"]=="-1"?"临时保存成功":"提交成功等待审核"),
					$_SERVER['HTTP_REFERER'],
					3
					);
				
			exit();				
			
		}
		else{
			$userid = $this->data["sess"]["userid"];
			$this->data["model"] = $this->qyuser->GetModelFromUserID($userid);	
			if(isset($this->data["model"]["product2"])){
				//读主营商品
				if($this->data["model"]["product2"]!=""){
					$product2 = $this->ccd->GetList2(" id in(".$this->data["model"]["product2"].")");	
				}
				else{
					$product2 = "";
				}
			}
			else{
				$product2 = "";
			}
			$this->data["product2"] = $product2;
			$this->data["company_type"] = $this->getccd(11,45123);
			$this->data["jiaoyi"] = $this->getccd(11,45129);
			$this->data["zhuying"] = $this->getccd(11,45135);
			$this->data["town"] = $this->getccd(6,3145,"id asc");
			//获取证件id对应的附件信息
			$szhy = @$this->data["model"]['three_code_add_id'];//三证合一
			$this->data["three_code_add_info"] = $szhy?$this->fj->GetModel($szhy):'';//三证合一
			$szhy = @$this->data["model"]['business_licence_id'];//营业执照
			$this->data["business_licence_info"] = $szhy?$this->fj->GetModel($szhy):'';//营业执照
			$szhy = @$this->data["model"]['organization_code_id'];//组织机构代码证
			$this->data["organization_code_info"] = $szhy?$this->fj->GetModel($szhy):'';//组织机构代码证
			$szhy = @$this->data["model"]['shuiwu_register_code_id'];//税务登记证
			$this->data["shuiwu_register_code_info"] = $szhy?$this->fj->GetModel($szhy):'';//税务登记证
			$isedit = false;
			if(isset($this->data["model"]["audit"])){
				if($this->data["model"]["audit"]=="-1" || $this->data["model"]["audit"]=="20"){
				$isedit = true;
				}
			}
			else{
				$isedit = true;	
			}
			$this->data["isedit"] = $isedit;
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_useredit_qy",$this->data);
		}
	}
	
	function useredit2(){	
	    //新注册的协会用户修改
		$post = $this->input->post();
		if(is_array($post)){
			$userid = $this->data["sess"]["userid"];
			$model = $this->xhuser->GetModelFromUserID($userid);
			$model2 = $this->getmysqlmodel();		
			foreach($model2 as $k=>$v){
				$model[$k] = $v;
			}					
			//已审或未审时，不通做“临时保存”
			if(isset($model["audit"])){
				if($model["audit"]=="-1" || $model["audit"]=="20"){
					$model["audit"] = $post["audit"];	
				}		
			}
			else{
				$model["audit"] = $post["audit"];	
			}
			$model["lastupdatetime"]=date("Y-m-d H:i:s");
			$model["inputtime"]=date("Y-m-d H:i:s");
			$model["updatetime"]=date("Y-m-d H:i:s");
			if($model["audit"]=="0"){
				//待审发送短信
				$msg = $model["name"]."提交了注册资料，请及时审核。提交人:".$this->data["sess"]["realname"].",联系电话:".$this->data["sess"]["tel"];	
				$this->sms->sendSmsToSystem("协会资料",$this->data["sess"]["userid"], $msg,"");
			}
			if(isset($model["userid"])){
				$this->xhuser->update($model);
			}
			else{
				$model["userid"] = $userid;
				$this->xhuser->add($model);
			}
			
			$this->parent_showmessage(
					1
					,($post["audit"]=="-1"?"临时保存成功":"提交成功等待审核"),
					$_SERVER['HTTP_REFERER'],
					3
					);
				
			exit();				
			
		}
		else{
			$userid = $this->data["sess"]["userid"];
			$this->data["model"] = $this->xhuser->GetModelFromUserID($userid);			
			$isedit = false;
			if(isset($this->data["model"]["audit"])){
				if($this->data["model"]["audit"]=="-1" || $this->data["model"]["audit"]=="20"){
				$isedit = true;
				}
			}
			else{
				$isedit = true;	
			}
			$this->data["isedit"] = $isedit;
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_useredit_xh",$this->data);
		}
	}
	
	
	//检查证件号是否唯一
	function chkzhengjianhao(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from swj_register_dsqy where isdel=0 and code='$title'".($id>0?" and userid<>$id":""));
		if(count($model)>0){
			$data = array("result"=>"0","msg"=>"证件号码已存在");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}
	
	//检查证件号是否唯一
	function chkzhengjianhao_xh(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from swj_register_xiehuiorjigou where isdel=0 and code='$title'".($id>0?" and userid<>$id":""));
		if(count($model)>0){
			$data = array("result"=>"0","msg"=>"证件号码已存在");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}


	
	//查看附件中的证书
	function lookpic(){
		$get=$this->input->get();
		if(empty($get["id"])){
			$this->parent_showmessage(
					0
					,"资料不存在",
					"",
					999999
					);		
			exit();	
		}
		$id = $get["id"];
		if(!is_numeric($id)){
			$this->parent_showmessage(
					0
					,"资料不存在",
					"",
					999999
					);		
			exit();				
		}
		$model = $this->fj->GetModel($id);
		if(isset($model["filesrc"])){
			if($model["userid"]==$this->data["sess"]["userid"]){
				$this->data["img"] = "/".$model["filesrc"];
				$this->load->view(__TEMPLET_FOLDER__."/admin/swj_lookpic",$this->data);
			}
			else{
				$this->parent_showmessage(
						0
						,"无权查看",
						"",
						999999
						);		
				exit();					
			}
		}
		else{
			$this->parent_showmessage(
					0
					,"资料不存在",
					"",
					999999
					);		
			exit();				
		}
	}
	
	//检查单位名
	function chkcompany(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from swj_register_dsqy where isdel=0 and name='$title'".($id>0?" and userid<>$id":""));
		if(count($model)>0){
			$data = array("result"=>"0","msg"=>"单位已存在");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}	
	

	//检查单位名
	function chkcompany_xh(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from swj_register_xiehuiorjigou where isdel=0 and name='$title'".($id>0?" and userid<>$id":""));
		if(count($model)>0){
			$data = array("result"=>"0","msg"=>"单位已存在");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}	
	
	
	//检查手机号是否重复
	function chktel(){
		$get=$this->input->get();
		$title = $get["param"];
		$id = empty($get["id"])?0:$get["id"];
		$model = $this->M_common->query_one("select * from 57sy_common_user where isdel=0 and tel='$title'".($id>0?" and uid<>$id":""));
		if(count($model)>0){
			$data = array("result"=>"0","msg"=>"手机号码已存在");
			echo json_encode($data);
		}
		else{
			$data = array("result"=>"1");
			echo json_encode($data);
		}
		exit();
	}		
	
	function prolist(){
		$get = $this->input->get();
		$post = $this->input->post();
		$pid = empty($get["pid"])?"-1":$get["pid"];
		$sel = empty($get["sel"])?"":$get["sel"];
		
		
		//$newid = empty($get["id"])?"":$get["id"];//从网址读取ID
		$title = array();					
		
		if($_SERVER['REQUEST_METHOD']=="GET"){	
			//删除选中ID
			if(!empty($get["delid"])){
				if($sel!=""){
					$sel = explode(",",$sel);
					$sel = array_unique($sel);
					$sel2 = array();
					foreach($sel as $v){
						if($get["delid"]!=$v){
							$sel2[] = $v;
						}
					}
				}
				header("location:".site_url("swj_admin/prolist")."?pid=".(empty($get["pid"])?0:$get["pid"])."&sel=".(isset($sel2)?implode(",",$sel2):""));
			}
			
						
			$this->data["mainlist"] = $this->ccd->GetModelList_orderby(11,45135,$orderby='disorder asc');
			if($pid==-1){
				if(count($this->data["mainlist"])>0){
					$pid = $this->data["mainlist"][0]["id"];
				}
			}
			$this->data["sublist"] = array();
			if($pid>0){
				$this->data["sublist"] = $this->ccd->GetModelList_orderby(11,$pid,$orderby='disorder asc');			
			
			}
			if($sel!=""){
				$this->data["sel"] = explode(",",$sel);
				$this->data["sel"] = array_unique($this->data["sel"]);
			}
			else{
				$this->data["sel"] = "";
			}
			$this->data["newid"] = $sel;
			$this->data["pid"] = $pid;		
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_prolist",$this->data);
		}
		else{
			
			
			$arr = $post["id"];
			$id_ = "";
			if(is_array($arr)){
				if(count($arr)>0){
					$id_ = implode(",",$arr);
				}
			}
			if($id_!=""){								
				if($sel!=""){
					$sel .= ",".$id_;
				}
				else{
					$sel .= $id_;
				}
			}	

			if($sel!=""){
				$sel = explode(",",$sel);
				$sel = array_unique($sel);
				$sel = implode(",",$sel);
				$list = $this->ccd->GetList2(" id in(".$sel.")");	
				foreach($list as $v){
					$title[] = $v["name"];	
				}
			}
			if(count($title)>0){
				$title = implode(",",$title);	
			}
			else{
				$title = "";
			}
			echo "<script>";
			echo "parent.$('#product_text').html('');";
			echo "parent.$('#product_text').html('".$title."');";
			echo "parent.$('#product').val('".$sel."');";
			echo "</script>";
			
			$this->parent_showmessage(
					1
					,"添加成功",
					site_url("swj_admin/prolist")."?sel=".$sel,
					3				
					);
			exit();			
						
		}
	}
	
	//改为一页显示
	function prolist2(){
		$get = $this->input->get();
		$post = $this->input->post();
		$pid = empty($get["pid"])?"-1":$get["pid"];
		$sel = empty($get["sel"])?"":$get["sel"];
		
		
		//$newid = empty($get["id"])?"":$get["id"];//从网址读取ID
		$title = array();					
		
		if($_SERVER['REQUEST_METHOD']=="GET"){	
			//删除选中ID
			if(!empty($get["delid"])){
				if($sel!=""){
					$sel = explode(",",$sel);
					$sel = array_unique($sel);
					$sel2 = array();
					foreach($sel as $v){
						if($get["delid"]!=$v){
							$sel2[] = $v;
						}
					}
				}
				header("location:".site_url("swj_admin/prolist")."?pid=".(empty($get["pid"])?0:$get["pid"])."&sel=".(isset($sel2)?implode(",",$sel2):""));
			}
			
						
			$mainlist = $this->ccd->GetModelList_orderby(11,45135,$orderby='disorder asc');
			foreach($mainlist as $k=>$v){				
				$mainlist[$k]["sublist"] = $this->ccd->GetModelList_orderby(11,$v["id"],$orderby='disorder asc');
			}			
			
			if($sel!=""){
				$this->data["sel"] = explode(",",$sel);
				$this->data["sel"] = array_unique($this->data["sel"]);
			}
			else{
				$this->data["sel"] = "";
			}
			$this->data["newid"] = $sel;
			$this->data["pid"] = $pid;		
			$this->data["mainlist"] = $mainlist;
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_prolist2",$this->data);
		}
		else{
			
			
			$arr = $post["id"];
			$id_ = "";
			$title = "";
			$sel = "";
			$title = "";
			if(is_array($arr)){
				if(count($arr)>0){
					$id_ = implode(",",$arr);
				}
			}
	
			if($id_!=""){
				$sel = explode(",",$id_);
				$sel = array_unique($sel);
				$sel = implode(",",$sel);
				$list = $this->ccd->GetList2(" id in(".$sel.")");	
				foreach($list as $v){
					$title[] = $v["name"];	
				}
			}
			if(count($title)>0 && $title!=""){
				$title = implode(",",$title);	
			}
			else{
				$title = "";
			}
			echo "<script>";
			echo "parent.$('#product_text').html('');";
			echo "parent.$('#product_text').html('".$title."');";
			echo "parent.$('#product').val('".$sel."');";
			echo "</script>";
			
			$this->parent_showmessage(
					1
					,"添加成功",
					site_url("swj_admin/prolist2")."?sel=".$sel,
					3				
					);
			exit();			
						
		}
	}
	
	
	
	
	function reg_edit(){
		$post = $this->input->post();
		if(is_array($post)){
			$model = $this->user->GetModel($this->data["sess"]["userid"]);
			$model2 = $this->getmysqlmodel();
			foreach($model2 as $k=>$v){
				$model[$k] = $v;
			}
			if($post["pwd"]!=""){
				$model["passwd"] = md5($post["pwd"]);
			}
			$this->user->update($model);
			$this->parent_showmessage(
					1
					,"修改成功",
					$_SERVER['HTTP_REFERER'],
					3
					);
			exit();			
		}
		else{
			$model = $this->user->GetModel($this->data["sess"]["userid"]);
			$this->data["model"] = $model;
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_regedit",$this->data);	
		}
	}
	
	function reg_edit_qy(){
		$post = $this->input->post();
		if(is_array($post)){						
			$userid = $this->data["sess"]["userid"];
			$model = $this->qyuser->GetModelFromUserID($userid);
			$model2 = $this->getmysqlmodel();		
			foreach($model2 as $k=>$v){
				$model[$k] = $v;
			}			
			//补充
			$model["company_type"] = empty($post["company_type"])?"":(implode(",",$post["company_type"]));
			$model["business_model"] = empty($post["jiaoyi"])?"":(implode(",",$post["jiaoyi"]));
			$model["upload_paper_type"] = $post["upload_paper_type"];
			$model['company_type_other'] = $post["company_type_other"];//其他企业类型
			$model['business_model_other'] = $post["business_model_other"];//其他交易模式
			//根据二级分类，取得主营产品一级分类
			if($model["product2"]!=""){
				$model["product"] = $this->ccd->GetParentFromSub($model["product2"]);				
			}
			//已审或未审时，不通做“临时保存”
			if(isset($model["audit"])){
				if($model["audit"]=="-1" || $model["audit"]=="20"){
					$model["audit"] = $post["audit"];	
				}		
			}
			else{
				$model["audit"] = $post["audit"];	
			}
			$model["lastupdatetime"]=date("Y-m-d H:i:s");
			$model["inputtime"]=date("Y-m-d H:i:s");
			$model["updatetime"]=date("Y-m-d H:i:s");
			//判断有无需要审核的内容			
			if($this->reg_edit_qy_chk_($model)){
				//判断有无未审核的，如有，不能提交
				if($this->qyuser_tmp->count("userid='".$this->data["sess"]["userid"]."' and check_status='1'")>0){
					$this->parent_showmessage(
						0
						,("上次提交的修改还在审核中，不能重复提交。"),
						$_SERVER['HTTP_REFERER'],
						5
						);					
					exit();
				}
				//待审发送短信
				$msg = $model["name"]."修改了企业资料，请及时审核。提交人:".$this->data["sess"]["realname"].",联系电话:".$this->data["sess"]["tel"];	
				$this->sms->sendSmsToSystem("企业资料",$this->data["sess"]["userid"], $msg,"");
				$check = array(
				   "name",
				   "company_type",
				   "business_model",
				   "product2",
				   "upload_paper_type",
				   "business_licence_id",
				   "organization_code_id",
				   "shuiwu_register_code_id",
				   "three_code_add_id",
				   "code",
				   "town_id",
				   "open_account_bank",
				   "public_bank_account",
				   "company_type_other",
				   "business_model_other"		   
				);	
				//提取要审核的字段
				$savemodel = array();
				foreach($model as $k=>$v){
					if(in_array($k,$check)){
						$savemodel[$k] = $v;
					}
				}
				$newmodel["content"] = json_encode($savemodel);
				$newmodel["create_time"] =  time();
				$newmodel["userid"] = $this->data["sess"]["userid"];
				$newmodel["username"] = ($this->data["sess"]["realname"]==""?$this->data["sess"]["username"]:$this->data["sess"]["realname"]);
				$newmodel["check_status"] = "1";
				$this->qyuser_tmp->add($newmodel);								
				$this->parent_showmessage(
						1
						,("提交成功，待审核通过后，新修改内容才生效。"),
						$_SERVER['HTTP_REFERER'],
						5
						);
					
				exit();					
			}
			
			if(isset($model["userid"])){
				$this->qyuser->update($model);
			}
		
			
			$this->parent_showmessage(
					1
					,"保存成功",
					$_SERVER['HTTP_REFERER'],
					3
					);
				
			exit();				
			
		}
		else{
			$userid = $this->data["sess"]["userid"];
			$this->data["model"] = $this->qyuser->GetModelFromUserID($userid);			
			if(isset($this->data["model"]["product2"])){
				//读主营商品
				if($this->data["model"]["product2"]!=""){
					$product2 = $this->ccd->GetList2(" id in(".$this->data["model"]["product2"].")");	
				}
				else{
					$product2 = "";
				}
			}
			else{
				$product2 = "";
			}
			//获取证件id对应的附件信息
			$szhy = $this->data["model"]['three_code_add_id'];//三证合一
			$this->data["three_code_add_info"] = $szhy?$this->fj->GetModel($szhy):'';//三证合一
			$szhy = $this->data["model"]['business_licence_id'];//营业执照
			$this->data["business_licence_info"] = $szhy?$this->fj->GetModel($szhy):'';//营业执照
			$szhy = $this->data["model"]['organization_code_id'];//组织机构代码证
			$this->data["organization_code_info"] = $szhy?$this->fj->GetModel($szhy):'';//组织机构代码证
			$szhy = $this->data["model"]['shuiwu_register_code_id'];//税务登记证
			$this->data["shuiwu_register_code_info"] = $szhy?$this->fj->GetModel($szhy):'';//税务登记证
			
			$this->data["product2"] = $product2;
			$this->data["company_type"] = $this->getccd(11,45123);
			$this->data["jiaoyi"] = $this->getccd(11,45129);
			$this->data["zhuying"] = $this->getccd(11,45135);
			$this->data["town"] = $this->getccd(6,3145,"id asc");
			$isedit = false;
			if(isset($this->data["model"]["audit"])){
				if($this->data["model"]["audit"]=="10"){
				$isedit = true;
				}
			}
			else{
				$isedit = true;	
			}
			$this->data["isedit"] = $isedit;
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_useredit_qy2",$this->data);
		}
		
	}
	

	function reg_edit_xh(){
		$post = $this->input->post();
		if(is_array($post)){						
			$userid = $this->data["sess"]["userid"];
			$model = $this->xhuser->GetModelFromUserID($userid);
			$model2 = $this->getmysqlmodel();		
			foreach($model2 as $k=>$v){
				$model[$k] = $v;
			}			
		
			//已审或未审时，不通做“临时保存”
			if(isset($model["audit"])){
				if($model["audit"]=="-1" || $model["audit"]=="20"){
					$model["audit"] = $post["audit"];	
				}		
			}
			else{
				$model["audit"] = $post["audit"];	
			}
			$model["lastupdatetime"]=date("Y-m-d H:i:s");
			$model["inputtime"]=date("Y-m-d H:i:s");
			$model["updatetime"]=date("Y-m-d H:i:s");
			//判断有无需要审核的内容			
			if($this->reg_edit_xh_chk_($model)){
				//判断有无未审核的，如有，不能提交
				if($this->xhuser_tmp->count("userid='".$this->data["sess"]["userid"]."' and check_status='1'")>0){
					$this->parent_showmessage(
						0
						,("上次提交的修改还在审核中，不能重复提交。"),
						$_SERVER['HTTP_REFERER'],
						5
						);					
					exit();
				}
				//待审发送短信
				$msg = $model["name"]."修改了协会或机构资料，请及时审核。提交人:".$this->data["sess"]["realname"].",联系电话:".$this->data["sess"]["tel"];	
				$this->sms->sendSmsToSystem("企业资料",$this->data["sess"]["userid"], $msg,"");
				$check = array(
				   "name",
				   "social_organization_registration_certificate_id",
				   "beian_certificate_id",
				   "code",
				   "open_account_bank",
				   "public_bank_account",
				   "socialorinstitutionnumber"		   
				);	
				//提取要审核的字段
				$savemodel = array();
				foreach($model as $k=>$v){
					if(in_array($k,$check)){
						$savemodel[$k] = $v;
					}
				}
				$newmodel["content"] = json_encode($savemodel);
				$newmodel["create_time"] =  time();
				$newmodel["userid"] = $this->data["sess"]["userid"];
				$newmodel["username"] = ($this->data["sess"]["realname"]==""?$this->data["sess"]["username"]:$this->data["sess"]["realname"]);
				$newmodel["check_status"] = "1";
				$this->xhuser_tmp->add($newmodel);								
				$this->parent_showmessage(
						1
						,("提交成功，待审核通过后，新修改内容才生效。"),
						$_SERVER['HTTP_REFERER'],
						5
						);
					
				exit();					
			}
			
			if(isset($model["userid"])){
				$this->xhuser->update($model);
			}
		
			
			$this->parent_showmessage(
					1
					,"保存成功",
					$_SERVER['HTTP_REFERER'],
					3
					);
				
			exit();				
			
		}
		else{
			$userid = $this->data["sess"]["userid"];
			$this->data["model"] = $this->xhuser->GetModelFromUserID($userid);						
			$isedit = false;
			if(isset($this->data["model"]["audit"])){
				if($this->data["model"]["audit"]=="10"){
				$isedit = true;
				}
			}
			else{
				$isedit = true;	
			}
			$this->data["isedit"] = $isedit;
			$this->load->view(__TEMPLET_FOLDER__."/admin/swj_useredit_xh2",$this->data);
		}
		
	}
	
	
	function reg_edit_qy_chk(){
		$post = $this->input->post();
		if(is_array($post)){
			$model = $this->getmysqlmodel();
			//补充
			$model["company_type"] = empty($post["company_type"])?"":(implode(",",$post["company_type"]));
			$model["business_model"] = empty($post["jiaoyi"])?"":(implode(",",$post["jiaoyi"]));
			$model["upload_paper_type"] = $post["upload_paper_type"];
			//根据二级分类，取得主营产品一级分类
			if($model["product2"]!=""){
				$model["product"] = $this->ccd->GetParentFromSub($model["product2"]);				
			}
			$model["userid"] = $this->data["sess"]["userid"];
			echo $this->reg_edit_qy_chk_($model)?"check":"no";						
		}
		else{
			echo "no";
		}
	}
	//检查电商企业修改的资料中有无要审核的
	// 返回是否需要审核 true:需要
	// $model 电商企业资料实体
	private function reg_edit_qy_chk_($model){
		//需要审核的字段
		$check = array(
		   "name",
		   "company_type",
		   "business_model",
		   "product2",
		   "upload_paper_type",
		   "business_licence_id",
		   "organization_code_id",
		   "shuiwu_register_code_id",
		   "three_code_add_id",
		   "code",
		   "town_id",
		   "open_account_bank",
		   "public_bank_account"		   
		);		
		$userid = $model["userid"];
		$oldmodel = $this->qyuser->GetModelFromUserID($userid);
		$check_count = 0;
		foreach($model as $k=>$v){
			if(in_array($k,$check)){
				if($oldmodel[$k]!=$model[$k]){
					$check_count++;
				}
			}
		}
		
		return $check_count>0;
	}


	function reg_edit_xh_chk(){
		$post = $this->input->post();
		if(is_array($post)){
			$model = $this->getmysqlmodel();
			$model["userid"] = $this->data["sess"]["userid"];
			echo $this->reg_edit_xh_chk_($model)?"check":"no";						
		}
		else{
			echo "no";
		}
	}

	// 检查协会或机构修改的资料中有无要审核的
	// 返回是否需要审核 true:需要
	// $model 协会资料实体
	private function reg_edit_xh_chk_($model){
		//需要审核的字段
		$check = array(
		   "name",
		   "social_organization_registration_certificate_id",
		   "beian_certificate_id",
		   "code",
		   "open_account_bank",
		   "public_bank_account",
		   "socialorinstitutionnumber"
		);		
		$userid = $model["userid"];
		$oldmodel = $this->xhuser->GetModelFromUserID($userid);
		$check_count = 0;
		foreach($model as $k=>$v){
			if(in_array($k,$check)){
				if($oldmodel[$k]!=$model[$k]){
					$check_count++;
				}
			}
		}
		
		return $check_count>0;
	}

	
	//读取公共数据
	private function getccd($typeid,$pid){
		return $this->ccd->GetListFromTypeidAndPid($typeid,$pid);
	}
}