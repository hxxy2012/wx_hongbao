<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看电子商务行业协会或机构</title>
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

<form action="<?php echo site_url("Swj_mime_project/edit_step");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<caption>项目基础信息</caption>
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft" style="width:198px;">项目名称</td>
        <td>
            <?php echo $title;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">申报时段</td>
        <td>
            <?php echo $timepart;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">项目简介</td>
        <td>
            <?php echo $content;?>
            <!-- <textarea style="width:100%; height:150px" id="describe" name="describe" placeholder="简介"><?php //echo $content;?></textarea> -->
        </td>
    </tr>
    
</table>
<caption>审核信息(模板：<?php echo $template_title?>)</caption>
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft" style="width:198px;">申请资助的项目（或活动）名称</td>
        <td>
            <?php if(isset($actname))echo $actname;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请资助的金额</td>
        <td>
            <?php if(isset($shenqing))echo $shenqing;?>&nbsp;元
        </td>
    </tr>
    <?php $this->load->view(__TEMPLET_FOLDER__."/project/review_look") ?>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <!-- <input type="button" class="btn btn-primary" id="btnSave" value="提交初审"> &nbsp;&nbsp; -->
    <!-- <input type="button" class="btn btn-primary" value="临时保存" onclick="javascript:lssave();">&nbsp;&nbsp; -->
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/index")?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/lang/zh_CN.js"></script>
<script>
var beizhu;//备注
var describe;//活动简介
var flag_chanquan_other;//判断是否选中了其他产权选项,1为是
$(function () {
        var chanquan_id=document.getElementsByName('chanquan_id'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<chanquan_id.length; i++){ 
            // alert(chanquan_id[i].attributes['tname'].nodeValue);
            if(chanquan_id[i].attributes['tname'].nodeValue=='其他'&&chanquan_id[i].checked) {
                $('#beizhu_box').show();
            }
        } 
        // document.getElementById('year').value = new Date().getFullYear();
});
/* KindEditor.ready(function(K) {
   describe = K.create('#describe',{
            width:'100%',
            height:'300px',
            allowFileManager:false ,
            allowUpload:false,
            afterCreate : function() {
                this.sync();
            },
            afterBlur:function(){
                  this.sync();
            },
            extraFileUploadParams:{
                'cookie':''
            },
            uploadJson:"<?php echo site_url("Swj_hdba/upload");?>?session_id=<?php echo $sess["session_id"];?>"
   });
   describe.readonly(true);
}); */
 function print_r() {
    window.print();
 }
</script>