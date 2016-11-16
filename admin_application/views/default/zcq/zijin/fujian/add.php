<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
   	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css"/>
	
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   

   
   <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/uploadfile/jquery.uploadify-3.1.min.js"></script>
   <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.css"/>   
      
   <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
   <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
   
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
<script>
function chkform(){

	if($("#myform").Valid()){
		if($("#filepath").val()==""){
			layer.msg('请上传附件',{time:1000});
			return false;
		}
	}
	else{
		return false;
	}
	
}
</script>
</head>
<body class="definewidth">

<div class="form-inline definewidth m20" >
<form method="POST" name="myform" id="myform" onsubmit="return chkform()" >

<table class="table table-bordered table-hover  m10">
<tr>
	<td class="tableleft">
	附件标题：
	</td>
	<td>
	<input type="text" style="width:200px;" maxlength="200" name="title" value="" required/>
	</td>
	<td>
	
	</td>		
</tr>
<tr>
	<td class="tableleft">
	上传模板：
	</td>
	<td>
<div id="upload_btn" style="">	
	<input type="file" name="filepath2" id="filepath2" required />
	<input type="hidden" name="filepath" id="filepath" />
</div>
<div id="look_btn" style="display:none;">
<input type="button" name="btn_look" onclick="lookfj()" class="button button-success" value="查看"/>
<input type="button" onclick="reload()" name="btn_look2" class="button button-danger" value="重新上传"/>
</div>
	</td>
	<td>
	支持类型:xls、xlsx、doc、docx、ppt、pptx、txt、pdf，文件大小在5M以内
	</td>		
</tr>
<tr>
	<td colspan="3" >
	<div style="text-align: center;">
	<input class="btn button-warning"  type="submit" name="btn_post" value="提交"/>
	<input class="button"  type="button" value="返回" onclick="window.location.href='<?php echo $ls;?>';"/>
	</div>
	</td>
	
</tr>
</table>
 </div> 
  
</form>  
<script>
function lookfj(){
	window.open("/"+$("#filepath").val());
}
function reload(){
	filepath = $("#filepath").val();
	if(filepath!=""){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("zcq_zijin/fujian_file_del");?>",
            data: {fp:filepath},
            dataType: "text",
            success: function(data){
				            
			}
        });
	}
	$("#upload_btn").css("display","");
	$("#look_btn").css("display","none");
	$("#filepath").val("");
}

$('#filepath2').uploadify({
    'auto'     : true,//关闭自动上传
    'removeTimeout' : 1,//文件队列上传完成1秒后删除
    'swf'      : '/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.swf',
    'uploader' : '<?php echo site_url("swj_upload/upload2");?>?path=fujian&session_id=<?php echo $sess["session_id"];?>',
    'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
    'buttonText' : '选择附件',//设置按钮文本
    'multi'    : false,//允许同时上传多张图片
    'uploadLimit' : 1,//一次最多只允许上传10张图片
    'fileTypeDesc' : '*.xls,*.xlsx,*.doc,*.docx,*.ppt,*.pptx,*.txt,*.pdf',//只允许上传图像
    'fileTypeExts' : '*.doc;*.docx;*.ppt;*.pptx;*.txt;*.xls;*.xlsx,*.pdf',//限制允许上传的图片后缀
    'fileSizeLimit' : '5048KB',//限制上传的图片不得超过200KB 
    'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
        //通过ajax获取图片id(data)对应的路径
        //alert(data);return;
        $("#filepath").val(data);
 		$("#upload_btn").css("display","none");
 		$("#look_btn").css("display",""); 		
        
    },
    'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数                 
        
    },
    'onError' : function (event, ID, fileObj, errorObj) {
       if (errorObj.type === "File Size"){
        alert('超过文件上传大小限制（5M）！');
        return;
       }
       alert(errorObj.type + ', Error: ' + errorObj.info);
    },  
    // Put your options here
});

</script>
</body>
</html>