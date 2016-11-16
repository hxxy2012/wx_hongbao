<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>项目申报</title>
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

    </style>
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("swj_project/index");?>">申报列表</a>
</div>
<form action="<?php echo site_url("swj_project/shenbao");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="shenbao_step">
<input type="hidden" name="id" value="<?php echo $id;?>">
<table class="table table-bordered table-hover m10">
<!--     <tr>
        <td class="tableleft">当前类目</td>
        <td>
            <?php //echo $name;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审核状态：<?php //echo $audit;?>&nbsp;&nbsp;&nbsp;&nbsp;
            审核时间：<?php //if($audit_time) echo $audit_time;else echo '无';?>
        </td>
    </tr> -->
    <tr>
        <td class="tableleft">项目名称</td>
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
    <tr>
        <td class="tableleft">选择一个模板<span class="hint">*</span></td>
        <td>
            <?php foreach ($template as $key => $value): ?>
                <input type="radio" name="template" tname="<?php if(empty($value['parenttitle'])) echo $value['title'];else echo $value['parenttitle'].'('.$value['title'].')'?>" 
                <?php if(isset($template_guid)&&$value['guid']==$template_guid)echo 'checked';?>
                value="<?php echo $value['guid']?>" style="margin-top:0;">&nbsp;<?php if(empty($value['parenttitle'])) echo $value['title'];else echo $value['parenttitle'].'('.$value['title'].')'?>&nbsp;&nbsp;<br/>
            <?php endforeach ?>
            <!-- <input type="text" style="display:none;margin:0;" name="jiaoyimodel_other" placeholder="其他交易模式" id="jiaoyimodel_other" value=""> -->
        </td>
    </tr>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <button class="btn btn-primary" type="button" id="btnSave">我要申报</button> &nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("swj_project/index");?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/lang/zh_CN.js"></script>
<script>
var beizhu;//备注
var describe;//活动简介
$(function () {
        /*var obj_jiaoyimodel=document.getElementsByName('jiaoyimodel[]'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<obj_jiaoyimodel.length; i++){ 
            // alert(obj_jiaoyimodel[i].attributes['tname'].nodeValue);
            if(obj_jiaoyimodel[i].attributes['tname'].nodeValue=='其他'&&obj_jiaoyimodel[i].checked) {
                $('#jiaoyimodel_other').show();
            }
        } */
        // document.getElementById('year').value = new Date().getFullYear();
        //提交表单   
		$('#btnSave').click(function(){
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            if (!$('input:radio[name="template"]').is(":checked")) {
                layer.msg('请选择一个模板！！',{time: 1000});
                return false;
            }
            $("#myform").submit();
		});
});
 

/*KindEditor.ready(function(K) {
   describe = K.create('#describe',{
            width:'100%',
            height:'400px',
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
</script>
