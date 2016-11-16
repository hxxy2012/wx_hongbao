<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>文件添加</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
	   	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/laydate/laydate.js"></script>
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
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("Swj_businesstracking/index");?>">列表</a>
</div>
<form action="<?php echo site_url("Swj_businesstracking/add");?>" enctype="multipart/form-data"  method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doadd">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">业务名称</td>
        <td><input type="text" name="name" id="name" required="true" tip="请输入业务名称"/></td>
    </tr>
  
    <tr>
        <td class="tableleft">可视权限</td>
        <td>
            <input type="radio" name="audit" value="1" checked/> 公开
            <input type="radio" name="audit" value="2"/> 不公开
        </td>
    </tr>
    <tr>
         <td class="tableleft">上传附件</td>
        <td><input type="file" name="file" id="file" required="true" tip="请上传文件"/></td>
    </tr>
    
    <tr>
        <td class="tableleft">经办人</td>
        <td><input type="text" name="jingbanren" id="jingbanren" required="true" tip="请输入经办人"/></td>
    </tr>
    <tr>
        <td class="tableleft">完成日期</td>
        <td><input type="text" name="complete_date" id="complete_date"   onclick="laydate({istime: false, format: 'YYYY-MM-DD'})"  required="true"   placeholder="请输入完成日期"  errMsg="请输入完成日期" tip="请输入完成日期"/></td>
    </tr>
    
    
    <tr>
        <td class="tableleft">备注</td>
        <td>
            <textarea style="width:300px; height:150px" id="content"  required="true"  name="content" placeholder="描述"></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
            <!-- <input type="button" class="btn btn-primary" value="返回" id="back" onclick="javascript:history.go(-1);">&nbsp;&nbsp; -->
        </td>
    </tr>
</table>
</form>
</body>
</html>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
$(function () {       
		
		$("#btnSave").click(function(){
			if($("#myform").Valid() == false || !$("#myform").Valid()) {
				return false ;
			}
		});

});
/*KindEditor.ready(function(K) {
       window.editor = K.create('#content',{
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
});*/

 
</script>
