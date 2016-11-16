<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/8
 * Time: 16:53
 * 项目类型控制类
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

class zcq_pro_type extends MY_Controller
{
    var $data;

    function zcq_pro_type()
    {
        parent::__construct();
        $this->load->model('M_common');
        $this->load->model('m_zcq_pro_type', 'zcq_pro_type');
        $this->load->model('m_zcq_pro_type_fujian','zcq_pro_type_fujian');
        $this->load->model('m_zcq_pro_fujian','zcq_pro_fujian');
        $this->load->model('m_zcq_pro_shenqing','zcq_pro_shenqing');
        $get = $this->input->get();
        $this->data["ls"] = empty($get["ls"]) ? "" : $get["ls"];
        $this->data["sess"] = $this->parent_getsession();
    }

    function index()
    {
        $pageindex= $this->input->get_post("per_page");
        $get = $this->input->get();
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $pagesize = 20;
        $search = array();
        $search_val = array();
        $search_val["title"] = isset($get["sel_title"])?$get["sel_title"]:"";
        $orderby["id"] = "desc";
        if($search_val["title"]!=""){
            $search["title"]= " like '%".$search_val["title"]."%'";
        }

        $list = $this->zcq_pro_type->GetInfoList($pageindex,$pagesize,$search,$orderby);
        $this->data["list"] = $list["list"];
        $this->data["pager"] = $list["pager"];
        $this->data["search_val"] = $search_val;

        $this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/pro_type/list",$this->data);

    }

    function add(){
        $post = $this->input->post();
        if($this->data["ls"]==""){
            $this->data["ls"] = site_url("zcq_pro_type/index");
        }
        if(is_array($post)){
            //$this->data["ls"] = site_url("zcq_pro_type/edit")."?id=".$post["id"];
            $model["title"] = $post["title"];
            $model["starttime"] = strtotime($post["starttime"].":00");
            $model["endtime"] = strtotime($post["endtime"].":00");
            if($model["starttime"]>$model["endtime"]){
                //调换时间
                $tmp = $model["endtime"];
                $model["endtime"] = $model["starttime"];
                $model["starttime"] = strtotime(date("Y-m-d H:i",$tmp).":59");
            }
            $model["isshow"] = $post["isshow"];
            $model["beizhu"] = $post["beizhu"];

            $model["isdel"] = "0";
            $model["create_sysuserid"] = admin_id();
            $model["update_sysuserid"] = 0;
            $model["createtime"] = time();
            $model["updatetime"] = 0;
            $newid = $this->zcq_pro_type->add($model);
            //插入附件表
            $pro_type_fujian = isset($post["pro_type_fujian"])?$post["pro_type_fujian"]:"";
            $arr = explode(",",$pro_type_fujian);
            $i=100;
            foreach($arr as $v){
                if($v>0) {
                    $fjmodel["type_id"] = $newid;
                    $fjmodel["fujian_id"] = $v;
                    $fjmodel["orderby"] = $i++;
                    $fjmodel["create_sysuserid"] = admin_id();
                    $fjmodel["update_sysuserid"] = 0;
                    $fjmodel["del_sysuserid"] = 0;
                    $fjmodel["createtime"] = time();
                    $fjmodel["updatetime"] = 0;
                    $fjmodel["deltime"] = 0;
                    $this->zcq_pro_type_fujian->add($fjmodel);
                }
            }


            showmessage("保存成功",
                $this->data["ls"], 1, 1,
                $params = '');
        }
        else{
            //$fujian = $this->zcq_pro_type_fujian->getlist();
            //$fujian = $this->zcq_pro_fujian->getlist();
            $this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/pro_type/add",$this->data);
        }
    }


    function edit(){
        $post = $this->input->post();
        if($this->data["ls"]==""){
            $this->data["ls"] = site_url("zcq_pro_type/index");
        }
        if(is_array($post)){

            $id = $post["id"];
            $this->data["ls"] = site_url("zcq_pro_type/edit")."?id=".$id;
            $model = $this->zcq_pro_type->GetModel($id);
            $model["title"] = $post["title"];
            $model["starttime"] = strtotime($post["starttime"].":00");
            $model["endtime"] = strtotime($post["endtime"].":00");
            if($model["starttime"]>$model["endtime"]){
                //调换时间
                $tmp = $model["endtime"];
                $model["endtime"] = $model["starttime"];
                $model["starttime"] = strtotime(date("Y-m-d H:i",$tmp).":59");
            }
            $model["isshow"] = $post["isshow"];
            $model["beizhu"] = $post["beizhu"];

            $model["isdel"] = "0";
            $model["create_sysuserid"] = admin_id();
            $model["update_sysuserid"] = 0;
            $model["createtime"] = time();
            $model["updatetime"] = 0;
            $this->zcq_pro_type->update($model);

            showmessage("保存成功",
                $this->data["ls"], 1, 1,
                $params = '');
        }
        else{

            //$fujian = $this->zcq_pro_fujian->getlist();
            $this->data["fujian"] = "";
            $get = $this->input->get();
            $id = $get["id"];
            if($id>0){
                $fujian_count = $this->zcq_pro_type_fujian->count("type_id='".$id."' and del_sysuserid='0'");
                $model = $this->zcq_pro_type->GetModel($id);
                $this->data["model"] = $model;
                $this->data["fujian_count"] = $fujian_count;
            }
            else{
                showmessage("无资料",
                    $this->data["ls"], 1, 0,
                    $params = '');
            }

            $this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/pro_type/edit",$this->data);
        }
    }
    //检查是否有关联，有关联不能删除
    function chkdel(){
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $arr = explode(",",$idlist);
        $have = 0;
        foreach ($arr as $v){
            $where = "isdel='0' and type_id='".$v."'";
            if($this->zcq_pro_shenqing->count($where)>0){
                $have++;
            }
        }
        echo $have;
    }

    function del(){
        $get = $this->input->get();
        $idlist = $get["idlist"];
        $arr = explode(",",$idlist);
        foreach ($arr as $v){
            $this->zcq_pro_type->del($v);
        }
    }
    /*
     * 分页读出待选的附件
     */
    function getfjbox(){
        $pageindex= $this->input->get_post("per_page");
        $get = $this->input->get();
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $pagesize = 6;
        $search = array();
        $search_val = array();
        $search_val["title"] = isset($get["sel_title"])?$get["sel_title"]:"";
        $orderby["id"] = "desc";
        if($search_val["title"]!=""){
            $search["title"]= " like '%".$search_val["title"]."%'";
        }

        $list = $this->zcq_pro_fujian->GetInfoList($pageindex,$pagesize,$search,$orderby);
        $this->data["list"] = $list["list"];
        $this->data["pager"] = $list["pager"];
        $this->data["search_val"] = $search_val;
        $this->data["idlist"] = isset($get["idlist"])?$get["idlist"]:"";
        $this->data["idlist_arr"] = isset($get["idlist"])?explode(",",$get["idlist"]):array();
        $this->data["per_page"] = $pageindex;
        $this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/pro_type/selfj",$this->data);
    }

 /*
 * 分页读出待选的附件(编辑页使用edit)
 */
    function getfjbox_edit(){

        $pageindex= $this->input->get_post("per_page");
        $get = $this->input->get();
        if ($pageindex <= 0) {
            $pageindex = 1;
        }
        $typeid = $get["typeid"];
        $pagesize = 6;
        $search = array();
        $search_val = array();
        $search_val["title"] = isset($get["sel_title"])?$get["sel_title"]:"";
        $orderby["id"] = "desc";
        if($search_val["title"]!=""){
            $search["title"]= " like '%".$search_val["title"]."%'";
        }

        //将已选的附件全部读出来
        $this->data["fjlist"] = $this->zcq_pro_type_fujian->getlist2("t1.del_sysuserid='0' and t1.type_id='".$typeid."'","t1.orderby asc,t1.id asc");
        $this->data["typeid"] = $typeid;
        $selfjid = "";//附件ID
        foreach( $this->data["fjlist"] as $v){
            if( $selfjid==""){
                $selfjid = $v["id"];
            }
            else{
                $selfjid.=",".$v["id"];
            }
        }
        if( $selfjid!="") {
            $search["id"] = " not in(" . $selfjid . ")";
        }
        $typemodel = $this->zcq_pro_type->GetModel($typeid);
        $list = $this->zcq_pro_fujian->GetInfoList($pageindex,$pagesize,$search,$orderby);
        $this->data["list"] = $list["list"];
        $this->data["pager"] = $list["pager"];
        $this->data["search_val"] = $search_val;
        $this->data["idlist"] = $selfjid;
        $this->data["per_page"] = $pageindex;
        $this->data["typemodel"] = $typemodel;
        $this->load->view(__TEMPLET_FOLDER__."/zcq/zijin/pro_type/selfj_edit",$this->data);

    }
    /*
     * 项目附件设置排序
     */
    function pro_fujian_set_add(){
        //1加，0减
        $post = $this->input->post();
        $type = isset($post["type"])?$post["type"]:"";

        if($type>=0){
            $profjid = isset($post["profjid"])?$post["profjid"]:"";
            $fjid = isset($post["fjid"])?$post["fjid"]:"";
            $typeid = isset($post["typeid"])?$post["typeid"]:"";
            if($type>0){
                $model["type_id"] = $typeid;
                $model["fujian_id"] = $fjid;
                $model["createtime"] = time();
                $model["create_sysuserid"] = admin_id();
                $model["orderby"] = "100";
                $model["del_sysuserid"] = "0";
                $model["update_sysuserid"] = "0";
                $model["deltime"] = "0";
                $model["updatetime"] = "0";
                //读出最大值
                $list = $this->zcq_pro_type_fujian->getlist("type_id='".$typeid."' and del_sysuserid='0' "," orderby asc ");
                if(count($list)>0){
                    $model["orderby"] = $list[count($list)-1]["orderby"]+1;
                }

                $this->zcq_pro_type_fujian->add($model);
            }
            else{
                echo $profjid;
                $this->zcq_pro_type_fujian->del($profjid);
            }
        }
    }
    /*
     * 项目附件设置排序
     */
    function pro_fujian_set_orderby(){

        $post = $this->input->post();
        $otype = isset($post["otype"])?$post["otype"]:"";
        $profjid = isset($post["profjid"])?$post["profjid"]:"0";
        if($otype!=""){
            $model = $this->zcq_pro_type_fujian->GetModel($profjid);

            $list = $this->zcq_pro_type_fujian->getlist2("t1.del_sysuserid='0' and t1.type_id='".$model["type_id"]."'","t1.orderby asc,t1.id asc");

            foreach($list as $k=>$v) {
                if($v["profj_id"]==$profjid){
                     if(strtolower($otype) == "up"){
                         if($k>0){
                             $tmporderby = $list[$k-1]["orderby"];
                             $list[$k-1]["orderby"] = $list[$k]["orderby"];
                             $list[$k]["orderby"] = $tmporderby;
                             $model2 = $this->zcq_pro_type_fujian->GetModel($list[$k-1]["profj_id"]);
                             $model3 = $this->zcq_pro_type_fujian->GetModel($list[$k]["profj_id"]);
                             $model2["orderby"] = $list[$k-1]["orderby"];
                             $model3["orderby"] = $list[$k]["orderby"];
                             $this->zcq_pro_type_fujian->update($model2);
                             $this->zcq_pro_type_fujian->update($model3);
                         }
                     }
                     else{
                         if($k< (count($list)-1) ){
                             $tmporderby = $list[$k+1]["orderby"];
                             $list[$k+1]["orderby"] = $list[$k]["orderby"];
                             $list[$k]["orderby"] = $tmporderby;
                             $model2 = $this->zcq_pro_type_fujian->GetModel($list[$k+1]["profj_id"]);
                             $model3 = $this->zcq_pro_type_fujian->GetModel($list[$k]["profj_id"]);
                             $model2["orderby"] = $list[$k+1]["orderby"];
                             $model3["orderby"] = $list[$k]["orderby"];
                             $this->zcq_pro_type_fujian->update($model2);
                             $this->zcq_pro_type_fujian->update($model3);
                         }
                     }

                }
                //die("fuck test".time());
                //$model["orderby"] = strtolower($otype) == "up" ? ($model["orderby"] - 1) : ($model["orderby"] + 1);
            }
            //die("fuck test".time());
        }
    }



}

