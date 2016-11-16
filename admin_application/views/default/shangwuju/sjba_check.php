<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看审核</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
	
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


    </style>
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("swj_sjsbcx/index");?>">审核列表</a>
</div>
<form action="<?php echo site_url("swj_sjsbcx/check");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="docheck">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="passornot" id="passornot" value="">
<input type="hidden" name="audit" value="<?php echo $audit_status;?>">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">当前类目</td>
        <td>
            <?php echo $name;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审核状态：<?php echo $audit;?>&nbsp;&nbsp;&nbsp;&nbsp;
            审核时间：<?php if($audit_time) echo $audit_time;else echo '无';?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">上报单位</td>
        <td>
            <?php echo $company;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">入驻的电商平台</td>
        <td>
            <?php foreach ($all_platform as $key => $value): ?>
                <input type="checkbox" readonly disabled="true" name="type" value="<?php echo $value['id']?>" <?php if(in_array($value['id'], $platform_arr)) echo "checked";?>><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">电商交易模式</td>
        <td>
            <?php foreach ($all_jyms as $key => $value): ?>
                <input type="checkbox" readonly disabled="true" name="jiaoyimodel[]" tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" <?php if(in_array($value['id'], $jymodel_arr)) echo "checked";?>><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <input type="text" readonly disabled style="display:none;margin:0;" name="jiaoyimodel_other" placeholder="其他交易模式" id="jiaoyimodel_other" value="<?php echo $jiaoyimodel_other;?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">年份</td>
        <td>
            <?php echo $year;?>&nbsp;年
        </td>
    </tr>
    <tr>
        <td class="tableleft">11月11日交易量</td>
        <td>
            <?php echo $jyl_11_11;?>&nbsp;次
        </td>
    </tr>
    <tr>
        <td class="tableleft">11月11日交易额</td>
        <td>
            <?php echo $jye_11_11;?>&nbsp;元
        </td>
    </tr>
    <tr>
        <td class="tableleft">11月12日交易量</td>
        <td>
            <?php echo $jyl_11_12;?>&nbsp;次
        </td>
    </tr>
    <tr>
        <td class="tableleft">11月12日交易额</td>
        <td>
            <?php echo $jye_11_12;?>&nbsp;元
        </td>
    </tr>
    <tr>
        <td class="tableleft">11月13日交易量</td>
        <td>
            <?php echo $jyl_11_13;?>&nbsp;次
        </td>
    </tr>
    <tr>
        <td class="tableleft">11月13日交易额</td>
        <td>
            <?php echo $jye_11_13;?>&nbsp;元
        </td>
    </tr>
    <tr>
        <td class="tableleft">审核意见(不通过时为必填)</td>
        <td>
            <textarea style="width:100%; height:150px" id="beizhu" name="beizhu" placeholder="审核意见"><?php echo htmlspecialchars($audit_idea);?></textarea>
        </td>
    </tr>
<!--     <tr>
    <td class="tableleft"></td>
    <td>
        <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
        <input type="button" class="btn btn-primary" value="返回" id="back" onclick="javascript:history.go(-1);">&nbsp;&nbsp;
    </td>
</tr> -->
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="button" class="btn btn-primary" value="审核通过" id="pass">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="审核不通过" id="nopass">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("swj_sjsbcx/index");?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script type="text/javascript" src="<?php echo  base_url();?>/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url();?>/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
var beizhu;//备注
var description;//活动简介
$(function () {
        var obj_jiaoyimodel=document.getElementsByName('jiaoyimodel[]'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<obj_jiaoyimodel.length; i++){ 
            // alert(obj_jiaoyimodel[i].attributes['tname'].nodeValue);
            if(obj_jiaoyimodel[i].attributes['tname'].nodeValue=='其他'&&obj_jiaoyimodel[i].checked) {
                $('#jiaoyimodel_other').show();
            }
        } 
        //通过审核,将值设为1，提交表单   
		$('#pass').click(function(){
			 $("#passornot").val(1);
             myform.submit();
		});
        //不通过审核,将值设为0，提交表单
		$("#nopass").click(function(){
            if (beizhu.isEmpty()) {
                alert("审核意见不能为空！！");
                beizhu.focus();
                return false;
            }
			$("#passornot").val(0);
            myform.submit();
		});

});
KindEditor.ready(function(K) {
       beizhu = K.create('#beizhu',{
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
                uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
       });
       // plan_beizhu.readonly(true);
});

 
</script>
