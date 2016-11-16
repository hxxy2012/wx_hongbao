<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户查看__<?php echo isset($model['name'])?$model['name']:"";?></title>
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





    
</head>
<body class="definewidth">
<div class="form-inline definewidth m20" >
<?php
if(count($model)==0){
	echo "<div style='text-align:center;color:red;line-height:200%;'>";
	echo "<b>暂无资料</b>";
	echo "</div>";
}
?>

   
   
</div>
<form enctype="multipart/form-data" action="<?php echo site_url("user/edit_xh");?>"  method="post" name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo isset($model['id'])?$model['id']:"";?>">
<input type="hidden" name="userid" value="<?php echo isset($userid)?$userid:"";?>">

<input type="hidden" name="beian_certificate_id" id="beian_certificate_id" value="<?php echo isset($model["beian_certificate_id"])?$model["beian_certificate_id"]:"0";?>">
<input type="hidden" name="social_organization_registration_certificate_id" id="social_organization_registration_certificate_id" value="<?php echo isset($model["social_organization_registration_certificate_id"])?$model["social_organization_registration_certificate_id"]:"0";?>">

<table class="table table-bordered table-hover definewidth">
  <tr>
        <td width="20%" class="tableleft">*行业协会或机构名称：</td>
        <td><?php  echo isset($model['name'])?$model['name']:""; ?></td>
    </tr>	
     <tr>
        <td class="tableleft">*社会团体法人登记证：</td>
        <td>
        
<div>
<ul class="gallery1" id="upload1_updateimg">
</ul>
</div>        
        
        </td>
    </tr> 	
    <tr>
	   <td class="tableleft">*社会团体法人登记证号码：</td>
	   <td><?php echo isset($model["code"])?$model["code"]:"";?></td>
    </tr>
    <tr>
        <td width="13%" class="tableleft">*组织机构代码证</td>
        <td>

<div>
<ul class="gallery1" id="upload2_updateimg">
</ul>
组织机构代码证号:<?php echo isset($model["zzjgdmzjh"])?$model["zzjgdmzjh"]:"";?>
</div>

        
      </td>
    </tr>
	<tbody id="t_0" class="pp"> 
	</tbody>
	<tbody id="t_1" > 
	</tbody> 
    <tr>
        <td width="13%" class="tableleft">常用开户银行：</td>
        <td><?php echo isset($model["open_account_bank"])?$model["open_account_bank"]:"";?></td>
    </tr> 	

    <tr>
      <td class="tableleft">常用对公银行账号：</td>
      <td><?php echo isset($model["public_bank_account"])?$model["public_bank_account"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">协会或机构人数：</td>
      <td><?php echo isset($model["socialorinstitutionnumber"])?$model["socialorinstitutionnumber"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">*注册地址：</td>
      <td><?php echo isset($model["register_address"])?$model["register_address"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">*企业固定电话：</td>
      <td><?php echo isset($model["guding_phone"])?$model["guding_phone"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">*移动电话：</td>
      <td><?php echo isset($model["mobilephone"])?$model["mobilephone"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">传真：</td>
      <td><?php echo isset($model["faxphone"])?$model["faxphone"]:"";?></td>
    </tr>
    <tr>
        <td class="tableleft">*电子邮箱：</td>
        <td><?php echo isset($model["email"])?$model["email"]:"";?></td>
    </tr>
    	
    <tr>
      <td valign="top" class="tableleft"><p>*协会或机构简介</p></td>
      <td>
      <pre style="border:none; background-color:transparent;">
   <?php echo isset($model["socialorinstitution_summary"])?$model["socialorinstitution_summary"]:"";?>
      </pre>
      </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
<input type="hidden" name="audit" id="audit" value="-1" />       
 <!-- <button type="submit" class="btn btn-warning"  id="btnSave">提交修改</button> -->
 
<a  class="btn btn-primary" id="addnew" onClick="parent.flushpage('<?php echo empty($_GET["backurl"])?"":$_GET["backurl"]?>');top.topManager.closePage();">关闭</a>     
        </td>
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
<script src="/home/views/static/js/zoom/zoom.min.js"></script>

