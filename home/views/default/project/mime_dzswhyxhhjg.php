<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>电子商务行业协会或机构</title>
    <meta charset="UTF-8">
   <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/validate/validator.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/layer/layer.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>   
	<script src="/home/views/static/js/autocomplete/jquery.autocompleter.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/home/views/static/js/autocomplete/jquery.autocompleter.css">
    <style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
        .hint{color: red;}
        .lk_tip{color:#ccc;}
    </style>
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("Swj_mime_project/edit")."?id=".$id."&sbid=".$sbid;?>">返回上一步</a>
</div>
<form action="<?php echo site_url("Swj_mime_project/edit_step");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="sbid" value="<?php echo $sbid;?>">
<input type="hidden" name="tplguid" value="<?php echo $tplguid;?>">
<input type="hidden" name="checkstatus" id="checkstatus" value="">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">申请资助的项目（或活动）名称<span class="hint">*</span></td>
        <td>
            <span style=" position: relative;">
              <input name="actname"　type="text" placeholder="备案名称" id="actname"
               value="<?php if(isset($actname))echo $actname;?>"     
              autocomplete="off"
              style="width:333px;border:1px solid #c3c3d6;line-height:20px;border-radius:4px;padding:1px 4px;"
              >
             </span>
            <input type="hidden" name="huodong_id" id="huodong_id" value="<?php if(isset($huodong_id))echo $huodong_id;?>" />
            <span class="lk_tip">请填写系统中存在的活动名称以便下拉选择</span>
        </td>
    </tr>
    <!-- <tr>
        <td class="tableleft">行业协会（机构）的基本情况<span class="hint">*</span></td>
        <td>
            <input type="text" name="jianjie"  value="<?php //if(isset($jianjie))echo $jianjie;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">申请资助项目（或活动）的简介<span class="hint">*</span></td>
        <td>
            <input type="text" name="huodong_jianjie" value="<?php //if(isset($huodong_jianjie))echo $huodong_jianjie;?>" required/>
        </td>
    </tr> -->
    <tr>
        <td class="tableleft">拟申请资助的金额<span class="hint">*</span></td>
        <td>
            <input type="text" name="shenqing" value="<?php if(isset($shenqing))echo $shenqing;?>" required/>&nbsp;元
        </td>
    </tr>
    <?php $this->load->view(__TEMPLET_FOLDER__."/project/review") ?>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="button" class="btn btn-primary" id="btnSave" value="提交初审"> &nbsp;&nbsp;
    <!-- <input type="button" class="btn btn-primary" value="临时保存" onclick="javascript:lssave();">&nbsp;&nbsp; -->
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/edit")."?id=".$id."&sbid=".$sbid;?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script>
var beizhu;//备注
var describe;//活动简介
var flag_fuwu_type_other;//判断是否选中了其他产权选项,1为是
$(function () {
       /* var project_type=document.getElementsByName('project_type[]'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<project_type.length; i++){ 
            // alert(project_type[i].attributes['tname'].nodeValue);
            if(project_type[i].attributes['tname'].nodeValue=='其他'&&project_type[i].checked) {
                $('#beizhu_box').show();
            }
        } */
        // document.getElementById('year').value = new Date().getFullYear();
        //提交表单   
		$('#btnSave').click(function(){
            if ($("#huodong_id").val() == "") {
                layer.msg('请输入活动名称进行选择！！',{time: 1000});
                return false;
            }
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            //检查公用附件是否有上传
            if (!chk_fj()) {
                return false;
            }
            $('#checkstatus').val('0');//将状态改为未审核
            $('#myform').submit();
		});
       /* //监听项目服务类型点击事件
        $('input[name="project_type[]"]').click(function(){
            var obj = $(this);
            var tname = obj.attr('tname');
            if (tname=='其他'&&obj.is(":checked")) {
                flag_fuwu_type_other = 1;
                $('#beizhu_box').show();
            } else if (tname=='其他'&&!obj.is(":checked")){
                flag_fuwu_type_other = 0;
                $('#beizhu_box').hide();
            }
        });*/
        //下拉搜索菜单(活动名称)
        $('#actname').autocompleter({
            source: '<?php echo site_url("Swj_hdba/getactname");?>',
            template: '{{ label }}',
            // show hint
            hint:false,
            // abort source if empty field
            empty:false,       
            // max results
            limit: 10,
            highlightMatches: true,
            changeWhenSelect:false,  
            cache:false,      
            callback: function (value, index,selected) {
                
                $('#actname').val(selected.label);
                $('#huodong_id').val(value);
            }        
        });
});
 
 //点击临时保存按钮，将checkstatus设置为99代表临时保存
 function lssave() {
    $('#checkstatus').val('99');
    $('#myform').submit();
 }
 //获取项目服务类型选择的数量
 function get_check_num() {
    var obj = document.getElementsByName("project_type[]");
    var num = 0;
    for(var i=0; i<obj.length; i++) {
        if (obj[i].checked) {
            num++;
        }
    }
    return num;
 }
</script>
