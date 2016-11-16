<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户编辑__<?php echo isset($model['name'])?$model['name']:"";?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="/admin_application/views/static/Js/jquery-1.8.1.min.js"></script> 	
	<!--script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script-->
    
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js?v=2.1"></script>    
<script type="text/javascript" src="/home/views/static/js/uploadfile/jquery.uploadify-3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
<link rel="stylesheet" type="text/css" href="/home/views/static/js/uploadfile/uploadify.css"/>
    
	<link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
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
function show_zj_div(opt){
	if(opt==1){
		$("#zj1").css("display","block");	
		$("#zj2").css("display","none");	
	}
	else{
		$("#zj1").css("display","none");	
		$("#zj2").css("display","block");			
	}	
}
</script> 

<script type="text/javascript">
// var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
var i=0;//初始化数组下标
var file_arr='';//保存上传文件名称以<**>分割
$(function() {
    $('#file_three_code_add_id').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/home/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.gif; *.jpg; *.png;*;*.bmp',//限制允许上传的图片后缀
        'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
               file_arr = data;
               //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        // alert(obj.filesrc);
                        if (obj.code == 0) {
                            $("#file_three_code_box").empty();
                            $('#file_three_code_box').append("<li style='padding:0px;'><a href='/"+obj.filesrc+"'><img style='width:92px;height:70px;'  src='/"+obj.filesrc+"' /></a></li>");
                            // $('#file_three_code_box').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='yq_ziliao[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
			   // layer.msg('上传成功',
			   // {time: 1000}
			   // );
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数           		
		   $("#three_code_add_id").val(file_arr);
		   $("#upload1").css("display","none");
		   $("#upload1_ok").css("display","block");
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
	
	
    $('#file_business_licence_id').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/home/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.gif; *.jpg; *.png;*;*.bmp',//限制允许上传的图片后缀
        'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
			   file_arr = data;
               $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        // alert(obj.filesrc);
                        if (obj.code == 0) {
                            $("#business_licence_box").empty();
                            $('#business_licence_box').append("<li  style='padding:0px;'><a href='/"+obj.filesrc+"'><img style='width:92px;height:70px;'  src='/"+obj.filesrc+"' /></a></li>");
                            // $('#file_three_code_box').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='yq_ziliao[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
			   /*layer.msg('上传成功',
			   {time: 1000}
			   );*/
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数           		
		   $("#business_licence_id").val(file_arr);
		   $("#upload2").css("display","none");
		   $("#upload2_ok").css("display","block");
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
	
    $('#file_organization_code_id').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/home/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.gif; *.jpg; *.png;*;*.bmp',//限制允许上传的图片后缀
        'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
			   file_arr = data;
               $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        // alert(obj.filesrc);
                        if (obj.code == 0) {
                            $("#organization_code_box").empty();
                            $('#organization_code_box').append("<li  style='padding:0px;'><a href='/"+obj.filesrc+"'><img style='width:92px;height:70px;'  src='/"+obj.filesrc+"' /></a></li>");
                            // $('#file_three_code_box').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='yq_ziliao[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
			   // layer.msg('上传成功',
			   // {time: 1000}
			   // );
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数           		
		   $("#organization_code_id").val(file_arr);
		   $("#upload3").css("display","none");
		   $("#upload3_ok").css("display","block");
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });	
	
	
    $('#file_shuiwu_register_code_id').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/home/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.gif; *.jpg; *.png;*;*.bmp',//限制允许上传的图片后缀
        'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
			   file_arr = data;
               $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        // alert(obj.filesrc);
                        if (obj.code == 0) {
                            $("#shuiwu_register_code_box").empty();
                            $('#shuiwu_register_code_box').append("<li  style='padding:0px;'><a href='/"+obj.filesrc+"'><img style='width:92px;height:70px;'  src='/"+obj.filesrc+"' /></a></li>");
                            // $('#file_three_code_box').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='yq_ziliao[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
			   // layer.msg('上传成功',
			   // {time: 1000}
			   // );
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数           		
		   $("#shuiwu_register_code_id").val(file_arr);
		   $("#upload4").css("display","none");
		   $("#upload4_ok").css("display","block");
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });	
	
	
	
});

function upl(id){
	//$("#"+id).uploadify('settings', 'formData', {'test':''});	
	$("#"+id).uploadify('upload');
}

//附件ID,控件ID
function cansel(fjid,control_id){
	$("#"+control_id).css("display","block");
	$("#"+control_id+"_ok").css("display","none");	
	fujianid = $('#'+fjid).val();	
	//删除刚才的附件
	if(fujianid>0){
		url2= "<?php echo site_url("swj_upload/delfj");?>?id="+fujianid+"&rnd="+Math.random();				
		$.ajax({
			url:url2,
			dataType: "text",
			type: "GET",			
			async:false,
			success: function(data){
				//alert(data);
      		},
			error:function(a,b,c){
				
			}
		});				
	}
	$('#'+fjid).val("");	
}
</script>

   <link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />   
    
</head>
<body class="definewidth">
<div class="form-inline definewidth m20" >
<?php
if(count($model)==0){
	echo "<div style='text-align:center;color:red;line-height:200%;'>";
	echo "<b>暂无资料，请录入以下资料</b>";
	echo "</div>";
}
?>

   
   
</div>
<form enctype="multipart/form-data" action="<?php echo site_url("user/edit_qy");?>"  method="post" name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo isset($model['id'])?$model['id']:"";?>">
<input type="hidden" name="userid" value="<?php echo isset($userid)?$userid:"";?>">

<table class="table table-bordered table-hover definewidth">
  <tr>
        <td width="13%" class="tableleft">*企业名称</td>
        <td><input name="mysql_name" type="text" id="mysql_name" placeholder=""  style="width:300px" required
valType="yyyy"  remoteUrl="<?php echo site_url("user/chkcompany");?>?id=<?php echo $userid;?>"         
         value="<?php  echo isset($model['name'])?$model['name']:""; ?>"/></td>
        <td>企业名称需要用全名（营业执照上的名字）</td>
    </tr>	
     <tr>
        <td class="tableleft">*电商企业类型</td>
        <td>
<?php
if(isset($model["company_type"])){
	$company_type_arr = explode(",",$model["company_type"]);
}
else{
	$company_type_arr  = array();
}
foreach($company_type as $v){
	echo "<input type='checkbox' tname='{$v["name"]}' name='company_type[]'";
	if(in_array($v["id"],$company_type_arr)){
		echo " checked ";	
	}
	echo " value='".$v["id"]."'/> ";
	echo $v["name"];
	echo "　";
}
?> <br><br> 
<input type="text" style="display:none;margin:0;" name="company_type_other" placeholder="其他企业类型" id="company_type_other" value="<?php  echo isset($model['company_type_other'])?$model['company_type_other']:""; ?>">       
        </td>
        <td>&nbsp;</td>
    </tr> 	
    <tr>
	   <td class="tableleft">*电商交易模式</td>
	   <td><?php
if(isset($model["business_model"])){
	$business_model_arr = explode(",",$model["business_model"]);
}
else{
	$business_model_arr  = array();
}	   
foreach($jiaoyi as $v){
	echo "<input type='checkbox' tname='{$v["name"]}' name='jiaoyi[]' ";
	if(in_array($v["id"],$business_model_arr)){
		echo "checked";	
	}
	echo " value='".$v["id"]."'/> ";
	echo $v["name"];
	echo "　";
}
?>
<input type="text" style="display:none;margin:0;" name="business_model_other" placeholder="其他交易模式" id="business_model_other" value="<?php  echo isset($model['business_model_other'])?$model['business_model_other']:""; ?>">    
</td>
	   <td>&nbsp;</td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">*主营产品</td>
        <td>
<span id="product_text">        
<?php
if(isset($model["product"])){
?>
<?php 
$product_list = "";
foreach($product2 as $v){
	if($product_list==""){
		$product_list = $v["name"];
	}
	else{
		$product_list .=",".$v["name"];
	}
}
echo $product_list;
?>
<?php	
}
?>      
</span>
<input type="hidden" name="mysql_product2" id="product" value="<?php echo isset($model["product2"])?$model["product2"]:"";?>" />
        </td>
        <td>
<?php
if($isedit){
?>        
<button type="button" id="btn_open_pro" class="btn btn-warning" >选择</button>
<?php
}
else{
?>
<button type="button" class="btn btn-warning" disabled >选择</button>         
<?php
}
?></td>
    </tr>
	<tbody id="t_0" class="pp">
    <tr>
        <td width="10%" class="tableleft">*是否三证合一</td>
      <td>
      
      <input name="upload_paper_type" id="upload_paper_type2" type="radio" onClick=" show_zj_div(1)" value="2" checked />是
      &nbsp;&nbsp;
      <input type="radio" name="upload_paper_type" id="upload_paper_type1"  value="1"  onClick=" show_zj_div(2)" />否  
      </td>
      <td>
<input type="hidden" name="mysql_business_licence_id" id="business_licence_id" value="<?php echo isset($model["business_licence_id"])?$model["business_licence_id"]:"";?>"/>
<input type="hidden" name="mysql_three_code_add_id" id="three_code_add_id" value="<?php echo isset($model["three_code_add_id"])?$model["three_code_add_id"]:"";?>"/>
<input type="hidden" name="mysql_organization_code_id"  id="organization_code_id" value="<?php echo isset($model["organization_code_id"])?$model["organization_code_id"]:"";?>" />
<input type="hidden" name="mysql_shuiwu_register_code_id"  id="shuiwu_register_code_id" value="<?php echo isset($model["shuiwu_register_code_id"])?$model["shuiwu_register_code_id"]:"";?>"/>

      </td>
    </tr> 
	 <tr>
        <td width="10%" class="tableleft">&nbsp;</td>
        <td>
        
        <div id="zj1" style="display:block;">
<table id="upload1"><tr><td style="border:none;">
三证合一：
</td>
<td style="border:none;">
<?php
if($isedit){
?>  
<input type="file" name="file_three_code_add_id" 
id="file_three_code_add_id" 
value="<?php echo isset($model["three_code_add_id"])?$model["three_code_add_id"]:"";?>"
/>
<?php
}
else{
	echo "-";	
}
?>
</td>
<td style="border:none;">
<?php
if($isedit){
?>  
<input type="button" value="上传" class="button button-danger" onClick="upl('file_three_code_add_id')"/>
文件大小不超过2M，格式为：jpg/png/gif/bmp
<?php
}
?>
</td>
</tr></table>
<table id="upload1_ok" style="display:none;" width="300"><tr><td style="border:none;">
<!-- <input type="button" value="查看三证合一"  class="button button-info" onClick="lookpic($('#three_code_add_id').val())"/> -->

    <div class="container">

        <ul class="gallery1" id="file_three_code_box">
            <?php if(isset($three_code_add_info['filesrc'])){?>
            <li style="padding:0px;"><a href="/<?php echo $three_code_add_info['filesrc'];?>"><img style='width:92px;height:70px;'  src="/<?php echo $three_code_add_info['filesrc'];?>" /></a></li>

            <?php }?>
        </ul>
        <div class="clear"></div>
    </div>
    
<?php
if($isedit){
?>
<input type="button" value="重新上传" style="margin-top:3px;"  class="button button-danger" onClick="cansel('three_code_add_id','upload1')"/>
<?php
}
?>
</td></tr></table>
        </div>
        
        <div id="zj2" style="display:none;">

<table id="upload2"><tr>
<td style="border:none;" width="105">        
营业执照：
</td>
<td style="border:none;">
<?php
if($isedit){
?>
<input type="file" name="file_business_licence_id" id="file_business_licence_id" />
<?php
}
else{
	echo "-";	
}
?>
</td>
<td style="border:none;">
<?php
if($isedit){
?>
<input type="button" value="上传" class="button button-danger" onClick="upl('file_business_licence_id')"/>
文件大小不超过2M，格式为：jpg/png/gif/bmp
<?php
}
?>
</td>
</tr></table>
<table id="upload2_ok" style="display:none;"><tr><td style="border:none;"  width="110">
<!-- <input type="button" value="查看营业执照"  class="button button-info" onClick="lookpic($('#business_licence_id').val())"/> -->
营业执照：
</td>
<td style="border:none;">
<div class="container">
    <ul class="gallery1" id="business_licence_box">
        <?php if(isset($business_licence_info['filesrc'])){?>
        <li style="padding:0px;"><a href="/<?php echo $business_licence_info['filesrc'];?>"><img style='width:92px;height:70px;'  src="/<?php echo $business_licence_info['filesrc'];?>" /></a></li>
        <?php }?>
    </ul>
    <div class="clear"></div>
</div>
<?php
if($isedit){
?>
<input type="button" value="重新上传" style="margin-top:3px;"  class="button button-danger" onClick="cansel('business_licence_id','upload2')"/>
<?php
}
?>
</td></tr></table>

<div style="clear:both;"></div>

<table id="upload3"><tr>

<td style="border:none;" width="105">        
组织机构代码证：
</td>
<td style="border:none;">
<?php
if($isedit){
?>
<input type="file" name="file_organization_code_id" id="file_organization_code_id" />
<?php
}
else{
	echo "-";	
}
?>
</td>
<td style="border:none;">
<?php
if($isedit){
?>
<input type="button" value="上传" class="button button-danger" onClick="upl('file_organization_code_id')"/>
文件大小不超过2M，格式为：jpg/png/gif/bmp
<?php
}
?>
</td>
</tr></table>
<table id="upload3_ok" style="display:none;"><tr><td style="border:none;"  width="110">
<!-- <input type="button" value="查看组织机构代码证"  class="button button-info" onClick="lookpic($('#organization_code_id').val())"/> -->
组织机构代码证：
</td>
<td style="border:none;">
<div class="container">
    <ul class="gallery1" id="organization_code_box">
        <?php if(isset($organization_code_info['filesrc'])){?>
        <li style="padding:0px;"><a href="/<?php echo $organization_code_info['filesrc'];?>"><img style='width:92px;height:70px;'  src="/<?php echo $organization_code_info['filesrc'];?>" /></a></li>
        <?php }?>
    </ul>
    <div class="clear"></div>
</div>
<?php
if($isedit){
?>
<input type="button" value="重新上传" style="margin-top:3px;"  class="button button-danger" onClick="cansel('organization_code_id','upload3')"/>
<?php
}
?>
</td></tr></table>

<div style="clear:both;"></div>
<table id="upload4"><tr>

<td style="border:none;" width="105">        
税务登记证：
</td>
<td style="border:none;">
<?php
if($isedit){
?>
<input type="file" name="file_shuiwu_register_code_id" id="file_shuiwu_register_code_id" />
<?php
}
else{
	echo "-";	
}
?>
</td>
<td style="border:none;">
<?php
if($isedit){
?>
<input type="button" value="上传" class="button button-danger" onClick="upl('file_shuiwu_register_code_id')"/>
文件大小不超过2M，格式为：jpg/png/gif/bmp
<?php
}
?>
</td>
</tr></table>
<table id="upload4_ok" style="display:none;"><tr><td style="border:none;" width="110">
<!-- <input type="button" value="查看税务登记证"  class="button button-info" onClick="lookpic($('#shuiwu_register_code_id').val())"/> -->
税务登记证：
</td>
<td style="border:none;">
<div class="container">
    <ul class="gallery1" id="shuiwu_register_code_box">
        <?php if(isset($shuiwu_register_code_info['filesrc'])){?>
        <li style="padding:0px;"><a href="/<?php echo $shuiwu_register_code_info['filesrc'];?>"><img style='width:92px;height:70px;'  src="/<?php echo $shuiwu_register_code_info['filesrc'];?>" /></a></li>
        <?php }?>
    </ul>
    <div class="clear"></div>
</div>
<?php
if($isedit){
?>
<input type="button" value="重新上传" style="margin-top:3px;" class="button button-danger" onClick="cansel('shuiwu_register_code_id','upload4')"/>
<?php
}
?>
</td></tr></table>

        </div>
        </td>
        <td>&nbsp;</td>
     </tr> 
    <tr>
        <td width="10%" class="tableleft">*证件号码：</td>
        <td>
        <input type="text" name="mysql_code" id="mysql_code"  placeholder="组织机构代码或三码合一代码" required style="width:300px"
valType="yyyy"  remoteUrl="<?php echo site_url("user/chkzhengjianhao");?>?id=<?php echo $userid;?>"     
value="<?php echo isset($model["code"])?$model["code"]:"";?>"   
        />
        </td>
        <td>&nbsp;</td>
    </tr> 
	</tbody>
	<tbody id="t_1" >
    <tr>
        <td width="10%" class="tableleft">*所属镇区：</td>
        <td>
        <select name="mysql_town_id" required>
        <option value="">请选择</option>
        <?php
		foreach($town as $v){
			echo "<option value='".$v["id"]."'";
			if(isset($model["town_id"])){
				echo $model["town_id"]==$v["id"]?" selected ":"";
			}
			echo ">";
			echo $v["name"];
			echo "</option>\n";	
		}
		?>
        </select>
        </td>
        <td>&nbsp;</td>
    </tr> 
	</tbody>
    <tr>
        <td width="10%" class="tableleft">*注册资金：</td>
        <td><input type="text" name="mysql_register_money"  placeholder="" valType="int" required style="width:300px"  value="<?php echo isset($model["register_money"])?$model["register_money"]:"";?>" />
        万元</td>
        <td>营业执照上的注册资金</td>
    </tr> 
    <tr>
        <td width="10%" class="tableleft">常用开户银行：</td>
        <td><input type="text" name="mysql_open_account_bank" id="open_account_bank" placeholder=""     style="width:300px"
value="<?php echo isset($model["open_account_bank"])?$model["open_account_bank"]:"";?>"        
         /></td>
        <td>例如：中国工商银行中山火炬开发区支行</td>
    </tr> 	
    <tr>
      <td class="tableleft">常用对公银行账号：</td>
      <td><input type="text" name="mysql_public_bank_account" placeholder="" style="width:300px" value="<?php echo isset($model["public_bank_account"])?$model["public_bank_account"]:"";?>"/></td>
      <td>
        例如：62220220110********
      </td>
    </tr>
    <tr>
      <td class="tableleft">公司人数：</td>
      <td><input name="mysql_company_number" type="text"   placeholder=""  style="width:100px" maxlength="5" valType="int"
value="<?php echo isset($model["company_number"])?$model["company_number"]:"";?>"      
      /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="tableleft">电商部门人数：</td>
      <td><input name="mysql_electronic_number" type="text"   placeholder=""  style="width:100px"  maxlength="5" valType="int"
value="<?php echo isset($model["electronic_number"])?$model["electronic_number"]:"";?>"       
      /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="tableleft">*注册地址：</td>
      <td><input type="text" name="mysql_register_address" placeholder=""     style="width:300px" required
value="<?php echo isset($model["register_address"])?$model["register_address"]:"";?>"      
       /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="tableleft">*企业固定电话：</td>
      <td><input type="text" name="mysql_guding_phone"   placeholder=""     style="width:300px" required
value="<?php echo isset($model["guding_phone"])?$model["guding_phone"]:"";?>"      
      /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="tableleft">*企业移动电话：</td>
      <td><input name="mysql_mobilephone" type="text" id="mysql_mobilephone" placeholder=""     style="width:100px"  valType="mobile"  required value="<?php echo isset($model["mobilephone"])?$model["mobilephone"]:"";?>" maxlength="11"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="tableleft">企业传真：</td>
      <td><input name="mysql_faxphone" type="text" id="mysql_faxphone" placeholder=""     style="width:100px"   value="<?php echo isset($model["faxphone"])?$model["faxphone"]:"";?>" maxlength="20"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="tableleft">*企业电子邮箱：</td>
        <td><input type="text" name="mysql_email" value="<?php echo isset($model["email"])?$model["email"]:"";?>" placeholder=""     style="width:300px" id="mysql_email" valType="email" required /></td>
        <td>&nbsp;</td>
    </tr>
    	
    <tr>
      <td valign="top" class="tableleft"><p>*企业简介</p></td>
      <td>
      <textarea name="mysql_company_summary" required style="width:99%; height:100px;"><?php echo isset($model["company_summary"])?$model["company_summary"]:"";?></textarea>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>&nbsp;
        <button type="submit" class="btn btn-warning" id="btnSave">提交修改</button>
        
<?php if(count($model)>0&&$model["audit"]=="0")
{
?>	


<button class="button button-success" onclick="return goset_check_yes()" style=" ">审核通过</button>
<button class="button button-danger" id="btn_check_no" style=" ">审核不通过</button>
<?php
}
?>         
<a  class="btn btn-primary" id="addnew" onClick="parent.flushpage('<?php echo empty($_GET["backurl"])?"":$_GET["backurl"]?>');top.topManager.closePage();">关闭</a>        
        </td>
        <td>&nbsp;</td>
    </tr>
</table>

<input type="hidden" name="backurl" value="<?php echo empty($_GET["backurl"])?"":$_GET["backurl"]?>" />

</form>	   
</body>
</html>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
<script>
var flag_company_type_other = 0;//标记企业类型是否选中
var flag_business_model_other = 0;//标记电商模式是否选中
$(function(){
    var obj_company_type=document.getElementsByName('company_type[]'); //选择所有name="'type[]'"的对象，返回数组 
    //循环检测活动其他类型是否选中，如果选中则显示text 
    for(var i=0; i<obj_company_type.length; i++){ 
        // alert(obj_company_type[i].attributes['tname'].nodeValue);
        if(obj_company_type[i].attributes['tname'].nodeValue=='其他'&&obj_company_type[i].checked) {
            flag_company_type_other = 1;
            $('#company_type_other').show();
        }
    } 
    var obj_business_model=document.getElementsByName('jiaoyi[]'); //选择所有name="'type[]'"的对象，返回数组 
    //循环检测活动其他类型是否选中，如果选中则显示text 
    for(var i=0; i<obj_business_model.length; i++){ 
        // alert(obj_business_model[i].attributes['tname'].nodeValue);
        if(obj_business_model[i].attributes['tname'].nodeValue=='其他'&&obj_business_model[i].checked) {
            flag_business_model_other = 1;
            $('#business_model_other').show();
        }
    }
    //监听企业类型点击事件
    $('input[name="company_type[]"]').click(function(){
        var obj = $(this);
        var tname = obj.attr('tname');
        if (tname=='其他'&&obj.is(':checked')) {
            flag_company_type_other = 1;
            $('#company_type_other').show();
        } else if(tname=='其他'&&!obj.is(':checked')){
            flag_company_type_other = 0;
            $('#company_type_other').val('');
            $('#company_type_other').hide();
        }
    });
    //监听电商交易模式点击事件
    $('input[name="jiaoyi[]"]').click(function(){
        var obj = $(this);
        var tname = obj.attr('tname');
        if (tname=='其他'&&obj.is(':checked')) {
            flag_business_model_other = 1;
            $('#business_model_other').show();
        } else if(tname=='其他'&&!obj.is(':checked')){
            flag_business_model_other = 0;
            $('#business_model_other').val('');
            $('#business_model_other').hide();
        }
    });
});
	$("#btn_open_pro").on("click",function(){
		layer.open({
			title: "选择",
			type: 2,
			area: ['700px', '300px'],
			fix: false, //不固定
			maxmin: true,
			content: '<?php echo site_url("user/prolist2");?>?sel='+$("#product").val()
		});	
	});
	

	
$("#btnSave").on("click",function(){


	if(chkform()){
			if(confirm("管理员修改的资料不需要审核，直接生效，\n是否继续？")){				 
				return true;
			}
			else{
				return false;	
			}			
	
	}
	else{
		return false;	
	}
});		

function chkform(){

/*
	layer.confirm('您是如何看待前端开发？', {
		btn: ['确认','取消'] //按钮
	}, function(){
		return true;
	}, function(){

	});	
*/	
	//检查电商企业类型
	if($("input[name='company_type[]']:checked").length==0){
		layer.msg("请选择电商企业类型");
		return false;
	}
    if (flag_company_type_other&&$('#company_type_other').val()=='') {
        layer.msg("请填写其他企业类型");
        $('#company_type_other').focus();
        return false;
    }
	//电商交易模式
	if($("input[name='jiaoyi[]']:checked").length==0){
		layer.msg("请选择电商交易模式");
		return false;
	}	
    if (flag_business_model_other&&$('#business_model_other').val()=='') {
        layer.msg("请填写其交易模式");
        $('#business_model_other').focus();
        return false;
    }   
	//主营产品
	//product
	if($("#product").val()==""){
		layer.msg("请选择主营产品");
		return false;
	}
	//是否三证合一
	if($("input[name='upload_paper_type']:checked").val()=="2"){
		//三证合一	
		if($("#three_code_add_id").val()=="" || $("#three_code_add_id").val()=="0"){
			layer.msg("请上传三证合一照片");	
			return false;
		}
	}
	else{
		//营业执照等
		if($("#business_licence_id").val()=="" || $("#business_licence_id").val()=="0"){
			layer.msg("请上传营业执照照片");	
			return false;
		}	
		
		if($("#organization_code_id").val()=="" || $("#organization_code_id").val()=="0"){
			layer.msg("请上传组织机构代码证照片");	
			return false;
		}	
		
		if($("#shuiwu_register_code_id").val()=="" || $("#shuiwu_register_code_id").val()=="0"){
			layer.msg("请上传税务登记证照片");	
			return false;
		}							
	}
	
	if ($("#myform").Valid()) {	
		return true;
	}
	else {
		return false;
	}	
}

function lookpic(ctrid){
	window.open("<?php echo site_url('user/lookpic');?>?id="+ctrid);
}
<?php
if(isset($model["upload_paper_type"])){
	if($model["upload_paper_type"]=="2"){
		echo '$("#upload_paper_type2").click();';
	}
	else{
		echo '$("#upload_paper_type1").click();';		
	}
}
if(isset($model["three_code_add_id"])){
	if($model["three_code_add_id"]>0){
		echo '$("#upload1").css("display","none");';	
		echo '$("#upload1_ok").css("display","block");';			
	}
}

if(isset($model["business_licence_id"])){
	if($model["business_licence_id"]>0){
		echo '$("#upload2").css("display","none");';	
		echo '$("#upload2_ok").css("display","block");';			
	}
}

if(isset($model["organization_code_id"])){
	if($model["organization_code_id"]>0){
		echo '$("#upload3").css("display","none");';	
		echo '$("#upload3_ok").css("display","block");';			
	}
}

if(isset($model["shuiwu_register_code_id"])){
	if($model["shuiwu_register_code_id"]>0){
		echo '$("#upload4").css("display","none");';	
		echo '$("#upload4_ok").css("display","block");';			
	}
}


	
?>


</script> 	



<script>
var ids = "<?php echo $model["userid"];?>";
function goset_check_yes(){
		
		if(ids==""){		
			parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
		}
		
		if(confirm("确认操作？")){
			var url2 = "/gl.php/user/set_check.shtml?check=10&idlist="+ids;	
			$.ajax({
				url:url2,
				dataType: "text",
				type: "GET",			
				async:false,
				success: function(data){
					//alert(data);
					if(data==0){					
						parent.tip_show('操作成功',1,1000);
						window.setTimeout("window.location.reload();",1000);
					}
					else{
						parent.tip_show('操作成功，但有部分[未完善资料]的会员不能审核',1,2000);
						window.setTimeout("window.location.reload();",2000);					
					}
				},
				error:function(a,b,c){
					
				}
			});	
			return false;	
		}
		else{
			return false;	
		}
}

 BUI.use('bui/overlay',function(Overlay){
          var dialog = new Overlay.Dialog({
            title:'输入审核不通过原因',
            width:500,
            height:220,
            //配置文本
            bodyContent:'<textarea id="check_content" name="content" style="width:100%;height:150px;"></textarea>',
            success:function () {                            
			  
				if(ids==""){		
					parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
					return false;
				}
				if(confirm("确认操作？")){
					var url2 = "/gl.php/user/set_check.shtml?check=20&idlist="+ids+"&content="+$("#check_content").val();	
					$.ajax({
						url:url2,
						dataType: "text",
						type: "GET",			
						async:false,
						success: function(data){
							//alert(data);
							if(data==0){					
								parent.tip_show('操作成功',1,1000);
								window.setTimeout("window.location.reload();",1000);
							}
							else{
								parent.tip_show('操作成功，但有部分[未完善资料]的会员不能审核',1,2000);
								window.setTimeout("window.location.reload();",2000);					
							}
						},
						error:function(a,b,c){
							
						}
					});		
				}							  
				
            }
          });
		  
	
	$("#btn_check_no").click("on",function(){			
			$("#check_content").val("");
			dialog.show();	
			return false;
	});	  
 });


</script>
