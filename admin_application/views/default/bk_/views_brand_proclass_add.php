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
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
	<link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
	   
 
<!--在线编辑器-->
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>

 
 
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

<form 
action="<?php echo site_url("brand_proclass/add")."?brand_id={$brand_id}";?>" enctype='multipart/form-data' onsubmit="return postform()" method="post"
enctype="multipart/form-data"
name="myform" id="myform">
<input type="hidden" name="action" value="doadd">
<input type="hidden" id="id" name="id" value="<?php echo empty($model["id"])?"":$model["id"];?>"/>

<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">分类名称：</td>
        <td>
            <input type="text" style="width:200px;" name="mysql_title"  value="<?php echo !empty($model['title'])?$model['title']:''?>" id="title" required="true" 
valType="check" />
        </td>
    </tr>
    <tr>
        <td class="tableleft">排序：</td>
        <td>
            <input type="text" style="width:200px;" name="mysql_orderby"  value="<?php echo !empty($model['orderby'])?$model['orderby']:''?>" id="orderby" required="true" 
valType="check" />
        </td>
    </tr>
   
    <tr>
        <td class="tableleft">图标：</td>
        <td>
      
<?php if(empty($model["logo"]) || $model["logo"]==""){?>	           	           
<input type="file" tips="格式：JPG|GIF|PNG|BMP" name="logo" id="logo" style="width:160px;" />
<?php }
else{
?>
<img style="width:100px; height:60px;border:#000 1px solid;cursor:pointer;"   src="/<?php echo $model["logo"];?>" id="thumb_img" name="thumb_img" tips="点击图片即删除"  />
<input type="hidden" name="logo" id="logo"  value="<?php echo $model["logo"];?>" />
<?php 
}?>            
        </td>
    </tr>   
   
        <tr>
        <td class="tableleft">分类备注：</td>
         <td> 
  
<textarea style="width:100%;height:150px;" id="mysql_beizhu" name="mysql_beizhu"  ><?php echo !empty($model['beizhu'])?$model['beizhu']:''?></textarea>

<script>
    KindEditor.ready(function(K) {
window.editor = K.create('#mysql_beizhu', {
width: '100%',
height: '200px',
allowFileManager: false,
allowUpload: false,
afterCreate: function() {
this.sync();
},
afterBlur: function() {
this.sync();
},
extraFileUploadParams: {
'cookie': "<?php echo $admin_auth_cookie;?>"
},
uploadJson: "<?php echo site_url("website_category/upload").'?action=upload&session='.$usersession;?>"

});
});

</script>



        </td>
    </tr>  
    
    
  

    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
<a  class="btn btn-primary" id="addnew" onclick="golist()" href="javascript:;">返回列表</a>       
            
            <input type='hidden' name='mysql_pid' value='<?php echo  $pid?>'>
        </td>
    </tr>
</table>
</form>
</body>
</html>



  
 <script type="text/javascript">
 function golist(){
        top.topManager.closePage();
     window.location.href="<?php echo site_url("brand_proclass/index")."?brand_id={$brand_id}";?>";
 }
 
function postform(){

return true;
}

BUI.use('bui/tooltip',function (Tooltip) {
	<?php if(empty($model["logo"]) || $model["logo"]==""){?>	

	var t2 = new Tooltip.Tip({
		  trigger : '#logo',
		  alignType : 'bottom',
		  offset : 10,
		  title : '-',
		  elCls : 'tips tips-success',
		  titleTpl : '<span class="x-icon x-icon-small x-icon-success"><i class="icon icon-white icon-question"></i></span>\
		  <div class="tips-content">'+$("#logo").attr("tips")+'</div>'
		});
		t2.render();
		t2.show();	
	
	<?php
	}
	else {
	?>
	var t2 = new Tooltip.Tip({
	  trigger : '#thumb_img',
	  alignType : 'bottom',
	  offset : 10,
	  title : '-',
	  elCls : 'tips tips-success',
	  titleTpl : '<span class="x-icon x-icon-small x-icon-success"><i class="icon icon-white icon-question"></i></span>\
	  <div class="tips-content">'+$("#thumb_img").attr("tips")+'</div>'
	});
	t2.render();
	t2.show();
	<?php }?>
	});

	<?php if(!empty($model["logo"])){?>
	$("#thumb_img").click(
	function(){
		parent.my_confirm(
				"是否删除缩略图?",
				"<?php echo site_url("brand_proclass/dellogo");?>?id="+$("#id").val(),
				"<?php echo get_url();?>"
		);		
	});

	<?php 

	}
	?>


</script>