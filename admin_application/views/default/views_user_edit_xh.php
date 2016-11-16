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
<link rel="stylesheet" type="text/css" href="/home/views/static/js/zoom/zoom.css" />    
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
    $('#file_social_organization_registration_certificate_id').uploadify({
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
                    async:true, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
						alert(obj.code);
                        if (obj.code == 0) {
                            $('#upload1_updateimg').append("<li style='padding:1px;'><a href='/"+obj.filesrc+"' target='_blank'><img style='width:92px;height:70px;' src='/"+obj.filesrc+"'/></a></li>");
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

        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数           		
		   $("#social_organization_registration_certificate_id").val(file_arr);
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
	
	
    $('#file_beian_certificate_id').uploadify({
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
                    async:true, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('#upload2_updateimg').append("<li style='padding:1px;'><a href='/"+obj.filesrc+"' target='_blank'><img style='width:92px;height:70px;' src='/"+obj.filesrc+"'/></a></li>");
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
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数           		
		   $("#beian_certificate_id").val(file_arr);
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
	$('#'+control_id+"_updateimg").html("");
	$('#'+fjid).val("");	
}
</script>

    
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
<form enctype="multipart/form-data" action="<?php echo site_url("user/edit_xh");?>"  method="post" name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo isset($model['id'])?$model['id']:"";?>">
<input type="hidden" name="userid" value="<?php echo isset($userid)?$userid:"";?>">

<table class="table table-bordered table-hover definewidth">
  <tr>
        <td width="20%" class="tableleft">*行业协会或机构名称：</td>
        <td><input name="mysql_name" type="text" id="mysql_name" placeholder=""  style="width:300px" required
valType="yyyy"  remoteUrl="<?php echo site_url("user/chkcompany_xh");?>?id=<?php echo $userid;?>"         
         value="<?php  echo isset($model['name'])?$model['name']:""; ?>"/></td>
        <td>名称需要用全名</td>
    </tr>	
     <tr>
        <td class="tableleft">*社会团体法人登记证：</td>
        <td>
        
<div id="zj1" style="display:block;">
<table id="upload1"><tr>
<td style="border:none;">
<?php
if($isedit){
?>  
<input type="file" name="file_social_organization_registration_certificate_id" 
id="file_social_organization_registration_certificate_id" 
value="<?php echo isset($model["social_organization_registration_certificate_id"])?$model["social_organization_registration_certificate_id"]:"";?>"
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
<input type="button" value="上传" class="button button-danger" onClick="upl('file_social_organization_registration_certificate_id')"/><?php
}
?>
</td>
</tr></table>
<table id="upload1_ok" style="display:none;"><tr><td style="border:none;">
<!--input type="button" value="查看"  class="button button-info" onClick="lookpic($('#social_organization_registration_certificate_id').val())"/-->
<div>
<ul class="gallery1" id="upload1_updateimg">
</ul>
</div>
<?php
if($isedit){
?>
<input type="button" value="重新上传" style="margin-top:3px;"  class="button button-danger" onClick="cansel('social_organization_registration_certificate_id','upload1')"/>
<?php
}
?>
</td></tr></table>
        </div>        
        
        </td>
        <td><input type="hidden" name="mysql_social_organization_registration_certificate_id" id="social_organization_registration_certificate_id" value="<?php echo isset($model["social_organization_registration_certificate_id"])?$model["social_organization_registration_certificate_id"]:"";?>"/>
        <span style="border:none;">文件大小不超过2M，格式为：jpg/png/gif/bmp</span></td>
    </tr> 	
    <tr>
	   <td class="tableleft">*社会团体法人登记证号码：</td>
	   <td><input type="text" name="mysql_code" id="mysql_code"  placeholder="组织机构代码或三码合一代码" required style="width:300px"
valtype="yyyy"  remoteurl="<?php echo site_url("user/chkzhengjianhao_xh");?>?id=<?php echo $userid;?>"     
value="<?php echo isset($model["code"])?$model["code"]:"";?>"   
        /></td>
	   <td>&nbsp;</td>
    </tr>
    <tr>
        <td width="13%" class="tableleft">*组织机构代码证</td>
        <td>

<table id="upload2"><tr>
<td style="border:none;">
<?php
if($isedit){
?>
<input type="file" name="file_beian_certificate_id" id="file_beian_certificate_id" />
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
<input type="button" value="上传" class="button button-danger" onClick="upl('file_beian_certificate_id')"/><?php
}
?>
</td>
</tr></table>
<table id="upload2_ok" style="display:none;"><tr><td style="border:none;">
<!--input type="button" value="查看"  class="button button-info" onClick="lookpic($('#beian_certificate_id').val())"/-->
<div>
<ul class="gallery1" id="upload2_updateimg">
</ul>
</div>
<?php
if($isedit){
?>
<input type="button" value="重新上传" style="margin-top:3px;"  class="button button-danger" onClick="cansel('beian_certificate_id','upload2')"/>
<?php
}
?>
</td></tr></table>
        <input type="text" name="mysql_zzjgdmzjh" required placeholder="组织机构代码证号" style="width:300px" value="<?php echo isset($model["zzjgdmzjh"])?$model["zzjgdmzjh"]:"";?>"/>
      </td>
        <td><input type="hidden" name="mysql_beian_certificate_id" id="beian_certificate_id" value="<?php echo isset($model["beian_certificate_id"])?$model["beian_certificate_id"]:"";?>"/>
        <span style="border:none;">文件大小不超过2M，格式为：jpg/png/gif/bmp</span></td>
    </tr>
	<tbody id="t_0" class="pp"> 
	</tbody>
	<tbody id="t_1" > 
	</tbody> 
    <tr>
        <td width="13%" class="tableleft">常用开户银行：</td>
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
      <td class="tableleft">协会或机构人数：</td>
      <td><input name="mysql_socialorinstitutionnumber" type="text"   placeholder=""  style="width:100px" maxlength="5" valType="int"
value="<?php echo isset($model["socialorinstitutionnumber"])?$model["socialorinstitutionnumber"]:"";?>"      
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
      <td>协会或机构专用</td>
    </tr>
    <tr>
      <td class="tableleft">*移动电话：</td>
      <td><input name="mysql_mobilephone" type="text" id="mysql_mobilephone" placeholder=""     style="width:100px"  valType="mobile"  required value="<?php echo isset($model["mobilephone"])?$model["mobilephone"]:"";?>" maxlength="11"/></td>
      <td>协会或机构专用</td>
    </tr>
    <tr>
      <td class="tableleft">传真：</td>
      <td><input name="mysql_faxphone" type="text" id="mysql_faxphone" placeholder=""     style="width:100px"   value="<?php echo isset($model["faxphone"])?$model["faxphone"]:"";?>" maxlength="20"/></td>
      <td>协会或机构专用</td>
    </tr>
    <tr>
        <td class="tableleft">*电子邮箱：</td>
        <td><input type="text" name="mysql_email" value="<?php echo isset($model["email"])?$model["email"]:"";?>" placeholder=""     style="width:300px" id="mysql_email" valType="email" required /></td>
        <td>协会或机构专用</td>
    </tr>
    	
    <tr>
      <td valign="top" class="tableleft"><p>*协会或机构简介</p></td>
      <td>
      <textarea name="mysql_socialorinstitution_summary" required style="width:99%; height:100px;"><?php echo isset($model["socialorinstitution_summary"])?$model["socialorinstitution_summary"]:"";?></textarea>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
<input type="hidden" name="audit" id="audit" value="-1" />       
<button type="submit" class="btn btn-warning"  id="btnSave">提交修改</button>
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
<script>
$("#btnSave").on("click",function(){
	if(chkform()){
		if(confirm("管理员修改的资料不需要审核，直接生效，\n是否继续？")){						
			return true;
		}
		else{
			return false;	
		}
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

	  if($("#social_organization_registration_certificate_id").val()=="" || $("#social_organization_registration_certificate_id").val()=="0"){
		  layer.msg("请上传社会团体法人登记证照片");	
		  return false;
	  }	
	  
	  if($("#beian_certificate_id").val()=="" || $("#beian_certificate_id").val()=="0"){
		  layer.msg("请上传民政部门备案证照片");	
		  return false;
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

if(isset($model["social_organization_registration_certificate_id"])){
	if($model["social_organization_registration_certificate_id"]>0){
		echo '$("#upload1").css("display","none");';	
		echo '$("#upload1_ok").css("display","block");';			
	}
}

if(isset($model["beian_certificate_id"])){
	if($model["beian_certificate_id"]>0){
		echo '$("#upload2").css("display","none");';	
		echo '$("#upload2_ok").css("display","block");';			
	}
}


	
?>
</script> 	

<?php
//审核时，能显示
if(isset($model["audit"]))
{
	if($model["audit"]=="0"){
		$isedit = true;
	}
}
if($isedit){
?>
<script>
//编辑时显示已上传图片
if($('#social_organization_registration_certificate_id').val()>0){
 			$.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':$('#social_organization_registration_certificate_id').val()},
                    cache:false, 
                    async:true, 
                    success: function(data){ 
					
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('#upload1_updateimg').append("<li style='padding:1px;'><a href='/"+obj.filesrc+"' target='_blank'><img style='width:92px;height:70px;' src='/"+obj.filesrc+"'/></a></li>");
                 
                        } 
                    } 
                });	
}

if($('#beian_certificate_id').val()>0){
 			$.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':$('#beian_certificate_id').val()},
                    cache:false, 
                    async:true, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('#upload2_updateimg').append("<li style='padding:1px;'><a href='/"+obj.filesrc+"' target='_blank'><img style='width:92px;height:70px;' src='/"+obj.filesrc+"'/></a></li>");
                 
                        } 
                    } 
                });	
}

</script>
<?php
}
?>

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
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
