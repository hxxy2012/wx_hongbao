<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>编辑项目申报</title>
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
        .table_tbody{display: none;}
    </style>
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("Swj_mime_project/index");?>">我的申报</a>
</div>
<form action="<?php echo site_url("Swj_mime_project/edit");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $id;?>">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft" style="width:211px;">选择模板<span class="hint">*</span></td>
        <td>
            <?php foreach ($template as $key => $value): ?>
                <input type="radio" name="template" tname="<?php if(empty($value['parenttitle'])) echo $value['title'];else echo $value['parenttitle'].'('.$value['title'].')'?>" 
                <?php if($value['guid']==$template_guid)echo 'checked';?>
                value="<?php echo $value['guid']?>" style="margin-top:0;">&nbsp;<?php if(empty($value['parenttitle'])) echo $value['title'];else echo $value['parenttitle'].'('.$value['title'].')'?>&nbsp;&nbsp;<br/>
            <?php endforeach ?>
            <!-- <input type="text" style="display:none;margin:0;" name="jiaoyimodel_other" placeholder="其他交易模式" id="jiaoyimodel_other" value=""> -->
        </td>
    </tr>
    <tbody class="table_tbody" id="7A48A574-223F-44F4-A06D-47E86D422C09">
         <tr>
            <td class="tableleft">园区名称<span class="hint">*</span></td>
            <td>
                <input type="text" name="yuanqu" style="width:333px;" value="<?php if(isset($yuanqu))echo $yuanqu;?>" required/>
            </td>
        </tr>
        <tr>
            <td class="tableleft">运营企业对园区的实际投入<span class="hint">*</span></td>
            <td>
                <input type="text" name="touru"  value="<?php if(isset($touru))echo $touru;?>" required/>&nbsp;万元
            </td>
        </tr>
        <tr>
            <td class="tableleft">拟申请资助的金额<span class="hint">*</span></td>
            <td>
                <input type="text" name="zizhu"  value="<?php if(isset($zizhu))echo $zizhu;?>" required/>&nbsp;元
            </td>
        </tr>
        <tr>
            <td class="tableleft">园区产权情况<span class="hint">*</span></td>
            <td>
                <?php foreach ($all_yqqk as $key => $value): ?>
                    <input type="radio" name="chanquan_id" style="margin-top:0;" 
                    <?php if(isset($chanquan_id)&&$chanquan_id==$value['id']) echo 'checked';?>
                    tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" ><?php echo $value['name']?>&nbsp;&nbsp;
                <?php endforeach ?>
                <br><br>
                <span id="beizhu_box" style="display:none;margin:0;">
                    备注：<input type="text" name="chanquan_beizhu" placeholder="其他产权备注" id="chanquan_beizhu" value="<?php if(isset($chanquan_beizhu))echo $chanquan_beizhu;?>">&nbsp;&nbsp;
                    年限：<input type="text" name="chanquan_year"
                    onkeyup="if(isNaN(value))execCommand('undo')"  onafterpaste="if(isNaN(value))execCommand('undo')"
                     placeholder="年限" id="chanquan_year" value="<?php if(isset($chanquan_year))echo $chanquan_year;?>">
                </span>
            </td>
        </tr>
        <tr>
            <td class="tableleft">园区经营面积<span class="hint">*</span></td>
            <td>
                <input type="text" name="jingying_mianji"  value="<?php if(isset($jingying_mianji))echo $jingying_mianji;?>" required/>&nbsp;平方米
            </td>
        </tr>
        <tr>
            <td class="tableleft">用于电子商务相关产业的经营面积<span class="hint">*</span></td>
            <td>
                <input type="text" name="dianshang_mianji"  value="<?php if(isset($dianshang_mianji)) echo $dianshang_mianji;?>" required/>&nbsp;平方米
            </td>
        </tr>
        <tr>
            <td class="tableleft">园区进驻企业数<span class="hint">*</span></td>
            <td>
                <input type="text" name="qiyeshu"  value="<?php if(isset($qiyeshu))echo $qiyeshu;?>" required/>&nbsp;家
            </td>
        </tr>
        <tr>
            <td class="tableleft">电子商务应用企业数<span class="hint">*</span></td>
            <td>
                <input type="text" name="yingyong_qiyeshu"  value="<?php if(isset($yingyong_qiyeshu))echo $yingyong_qiyeshu;?>" required/>&nbsp;家
            </td>
        </tr>
        <tr>
            <td class="tableleft">电子商务服务企业数<span class="hint">*</span></td>
            <td>
                <input type="text" name="fuwu_qiyeshu"  value="<?php if(isset($fuwu_qiyeshu))echo $fuwu_qiyeshu;?>" required/>&nbsp;家
            </td>
        </tr>
        <tr>
            <td class="tableleft">年度电子商务营业收入<span class="hint">*</span></td>
            <td>
                <input type="text" name="year_in"  value="<?php if(isset($year_in))echo $year_in;?>" required/>&nbsp;万元
            </td>
        </tr>
        <tr>
            <td class="tableleft">年度电子商务营业收入占比<span class="hint">*</span></td>
            <td>
                <input type="text" name="year_in_bl"  value="<?php if(isset($year_in_bl))echo $year_in_bl;?>" required/>&nbsp;%
            </td>
        </tr>
        <tr>
            <td class="tableleft">年度企业纳税金额<span class="hint">*</span></td>
            <td>
                <input type="text" name="year_out"  value="<?php if(isset($year_out))echo $year_out;?>" required/>&nbsp;万元
            </td>
        </tr>
    </tbody>
    <tbody class="table_tbody" id="B048AAE0-536B-4D58-AA6E-D40B7AFC10B0">
        <tr>
            <td class="tableleft">电子商务应用项目<span class="hint">*</span></td>
            <td>
                4545
            </td>
        </tr>
    </tbody>
    <tbody class="table_tbody" id="447B3AE5-0007-4701-9969-5DC8566DC4AF">
        <tr>
            <td class="tableleft">电子商务服务项目<span class="hint">*</span></td>
            <td>
                4545
            </td>
        </tr>
    </tbody>
    <tbody class="table_tbody" id="3F1BF950-4201-4574-A09C-447605BCDD20">
        <tr>
            <td class="tableleft">电子商务行业协会或机构<span class="hint">*</span></td>
            <td>
                4545
            </td>
        </tr>
    </tbody>
    <tbody class="table_tbody" id="F97C5BE0-C642-4C85-9481-D19FAC4132CD">
        <tr>
            <td class="tableleft">电子商务交易平台<span class="hint">*</span></td>
            <td>
                4545
            </td>
        </tr>
    </tbody>
    <tbody class="table_tbody" id="BA038762-97D3-4EF7-8A17-B5BE0CB33252">
        <tr>
            <td class="tableleft">电子商务综合服务平台<span class="hint">*</span></td>
            <td>
                4545
            </td>
        </tr>
    </tbody>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <button class="btn btn-primary" type="button" id="btnSave">提交</button> &nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/index");?>';">&nbsp;&nbsp;
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
    // 循环检测选择了哪个模板
        var template=document.getElementsByName('template'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<template.length; i++){ 
            /*alert(template[i].value);
            if(template[i].attributes['tname'].nodeValue=='其他'&&template[i].checked) {
                $('#jiaoyimodel_other').show();
            }*/
            if (template[i].checked) {
                var tpvalue = template[i].value;
                $('#'+tpvalue).show();
            }
        } 
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
        //监听点击模板按钮事件，显示或隐藏tbody
        $('input[name="template"]').click(function(){
            var tpvalue = $(this).val();
            $('.table_tbody').hide();
            $('#'+tpvalue).show();
        });
});
 

KindEditor.ready(function(K) {
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
}); 
</script>
