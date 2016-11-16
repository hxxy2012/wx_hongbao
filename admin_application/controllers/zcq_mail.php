<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//活动控制器
class zcq_mail extends MY_Controller {
	var $data;
	function zcq_mail(){
		parent::__construct();


		$this->load->model('M_common');
		$this->load->model('M_user','user');
        $this->load->model('M_common_system_user','sysuser');
		$this->load->model('M_common_category_data','cd');
        $this->load->model('m_zcq_mail','zcq_mail');
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
        $orderby["isread"] = "asc";
		$orderby["id"] = "desc";
		if(!empty($get["sel_title"])){
			$search["title"] = trim($get["sel_title"]);
			$search_val["title"] = trim($get["sel_title"]);
		}
		$search["t1.receive_sysuserid"] = "='".admin_id()."'";

		$list = $this->zcq_mail->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/zcq/mail/list",$this->data);
	}
	

	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
            $receive_userid = isset($post["receive_userid"])?$post["receive_userid"]:"";
            $receive_userid2 = isset($post["receive_userid2"])?$post["receive_userid2"]:"";
            $receive_sysuserid = isset($post["receive_sysuserid"])?$post["receive_sysuserid"]:"";

            //界面上接收的快速选人
            $jieshou = isset($post["jieshou"])?$post["jieshou"]:array();
            $jieshou2 = isset($post["jieshou2"])?$post["jieshou2"]:array();//管理员

            if($receive_userid2!=""){
                $receive_userid .= ($receive_userid==""?"":",").$receive_userid2;
            }
            if(count($jieshou)>0) {
                foreach ($jieshou as $k => $v) {
                    if ($v == "") {
                        unset($jieshou);
                    }
                }
            }
            if(count($jieshou2)>0) {
                foreach ($jieshou2 as $k => $v) {
                    if ($v == "") {
                        unset($jieshou2);
                    }
                }
            }

            $userid = 0;
            $adminid = admin_id();
            if(isset($post["sysuserid"])){
                if($post["sysuserid"]>0) {
                    $adminid = $post["sysuserid"];
                }
            }
            else{
                if(isset($post["userid"])){
                    if($post["userid"]>0) {
                        $userid = $post["userid"];
                    }
                }
            }

            $guid = create_guid();
            if($receive_userid!="" || count($jieshou)>0 ){
                $list = explode(",",$receive_userid);
                if(is_array($jieshou)){
                    $list = array_merge($list,$jieshou);
                }
                $list = array_unique($list);
                //print_r($list);
                //die();
                foreach($list as $v) {
                    if($v==""){
                        continue;
                    }
                    $model["mail_status"] = "1";//0草稿 1已发
                    $model["title"] = $post["title"];
                    $model["receive_userid"] = $v;
                    $model["receive_sysuserid"] = "0";
                    $model["content"] = $post["content"];
                    $model["isdel"] = "0";
                    $model["create_sysuserid"] = $adminid;
                    $model["update_sysuserid"] = "0";
                    $model["createtime"] = time();
                    $model["updatetime"] = "0";
                    $model["deltime"] = "0";
                    $model["create_userid"] = $userid;
                    $model["update_userid"] = "0";
                    $model["del_userid"] = "0";
                    $model["guid"] = $guid;
                    $model["isread"] = "0";
                    $this->zcq_mail->add($model);
                }
            }
            if($receive_sysuserid!="" || count($jieshou2)>0 ) {
                $list = explode(",", $receive_sysuserid);
                if(is_array($jieshou2)){
                    $list = array_merge($list,$jieshou2);
                }
                $list = array_unique($list);
                foreach ($list as $v) {
                    if($v==""){
                        continue;
                    }
                    $model["mail_status"] = "1";//0草稿 1已发
                    $model["title"] = $post["title"];
                    $model["receive_userid"] = 0;
                    $model["receive_sysuserid"] = $v;
                    $model["content"] = $post["content"];
                    $model["isdel"] = "0";
                    $model["create_sysuserid"] = $adminid;
                    $model["update_sysuserid"] = "0";
                    $model["createtime"] = time();
                    $model["updatetime"] = "0";
                    $model["deltime"] = "0";
                    $model["create_userid"] = $userid;
                    $model["update_userid"] = "0";
                    $model["del_userid"] = "0";
                    $model["guid"] = $guid;
                    $model["isread"] = "0";
                    $this->zcq_mail->add($model);
                }
            }
            //方便程序调用在help中
            if(isset($post["ishelp"])){
                echo "ok";
            }else {
                $ls = empty($post["ls"]) ? site_url("zcq_mail/add") : $post["ls"];
                showmessage("发送成功", $ls, 3, 1);
            }
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
            //常发送的
            $qiye = $this->zcq_mail->getlist_huiyuan_orderby("t1.create_sysuserid='".admin_id()."' and t1.receive_userid>0 and t2.isdel='0' and usertype='45063'");
            $jigou = $this->zcq_mail->getlist_huiyuan_orderby("t1.create_sysuserid='".admin_id()."' and t1.receive_userid>0 and t2.isdel='0' and usertype='45064'");
            $sysuser_list = $this->zcq_mail->getlist_sys_orderby("t1.create_sysuserid='".admin_id()."' and t1.receive_sysuserid>0");

			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("zcq_huodong/index");	
			}
            $this->data["qiye"] = $qiye;
            $this->data["jigou"] = $jigou;
            $this->data["sysuser_list"] = $sysuser_list;
            //send_zhan_mail("1","6","test","haha",0,admin_id(),$this->data["sess"]["session_id"]);
			$this->load->view(__TEMPLET_FOLDER__."/zcq/mail/add",$this->data);
		}
	}

	//选择会员发送站内信
	function selmember(){

	    $get = $this->input->get();
        $post = $this->input->post();
        if(is_array($post)){
            $idarr = $post["id"];
            $idlist = implode(",",$idarr);
            $usertype = isset($post["usertype"])?$post["usertype"]:"";
            //header("location:".site_url("zcq_mail/selmember")."?usertype=".$usertype."&idlist=".$idlist);
            echo "<script>";
            if($usertype=="45063") {
                echo "parent.updatesel('qiye_btn','".$idlist."');";
            }
            if($usertype=="45064") {
                echo "parent.updatesel('jigou_btn','".$idlist."');";
            }
            echo "parent.parent.tip_show (\"成功添加\",0,1000);";
            echo "parent.closefj();";
            die("</script>");
        }
        else {
            $usertype = isset($get["usertype"]) ? $get["usertype"] : "0";
            $idlist = isset($get["idlist"]) ? $get["idlist"] : "";
            $sel_title = isset($get["sel_title"]) ? $get["sel_title"] : "";
            $orderby["convert(username using gbk)"] = "asc";
            $pagesize = 99999;
            $search["usertype"] = $usertype;
            if ($sel_title != "") {
                $search["username"] = $sel_title;
            }
            $pageindex = 1;
            $list = $this->user->GetInfoList($pageindex, $pagesize, $search, $orderby);
            $this->data["list"] = $list["list"];
            $this->data["pager"] = $list["pager"];
            $this->data["idlist"] = $idlist;
            $this->data["usertype"] = $usertype;
            $this->data["sel_title"] = $sel_title;
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/mail/selmember", $this->data);
        }
    }

    function seladmin()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        if (is_array($post)) {
            $idarr = $post["id"];
            $idlist = implode(",", $idarr);
            echo "<script>";
            echo "parent.updatesel('sysuser_btn','" . $idlist . "');";
            echo "parent.parent.tip_show ('成功添加',0,1000);";
            echo "parent.closefj();";
            die("</script>");
        } else {

            $idlist = isset($get["idlist"]) ? $get["idlist"] : "";
            $sel_title = isset($get["sel_title"]) ? $get["sel_title"] : "";
            $orderby["convert(username using gbk)"] = "asc";
            $pagesize = 99999;
            $search = array();
            if ($sel_title != "") {
                $search["username"] = $sel_title;
            }
            $pageindex = 1;
            $list = $this->sysuser->GetInfoList($pageindex, $pagesize, $search, $orderby);
            $this->data["list"] = $list["list"];
            $this->data["pager"] = $list["pager"];
            $this->data["idlist"] = $idlist;
            $this->data["sel_title"] = $sel_title;
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/mail/seladmin", $this->data);
        }


    }


	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->zcq_mail->del($v);
			}
		}
		echo "ok";
	}

	function myview(){
        $get = $this->input->get();
        $id = isset($get["id"])?$get["id"]:0;
        if($id>0){
            $model = $this->zcq_mail->GetModel($id);
            if( $model["receive_sysuserid"]==admin_id() ){
                if($model["isread"]=="0") {
                    $model["isread"] = "1";
                    $model["updatetime"] = time();
                    $model["update_sysuserid"] = admin_id();
                }
                $this->zcq_mail->update($model);
            }
            else{
                showmessage("没有资料", "", 999,0 );
            }
            $this->data["model"] = $model;
            $this->load->view(__TEMPLET_FOLDER__ . "/zcq/mail/view", $this->data);
        }
    }
}