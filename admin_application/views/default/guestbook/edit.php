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
	
	.bui-dialog .bui-stdmod-body{
		padding:0px;
	}
    </style>
</head>
<body class="definewidth">


<form action="" onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="id" id="id" value="<?php echo $model["id"];?>"/>


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">留言主题：</td>
        <td>

<input type="text" style="width:200px;" 
name="title"
id="title" 
value="<?php echo $model["title"];?>" required="true" 
        
        />        
*  



        </td>
    </tr>
   <tr>
        <td class="tableleft">联系人及手机号：</td>
        <td>
<input type="text" style="width:100px;" 
name="linkman"
id="linkman" 
placeholder="联系人" 
value="<?php echo $model["linkman"];?>" required="true" 
        
        />    
<input type="text" style="width:100px;" 
name="tel"
id="tel" 
placeholder="手机" 
value="<?php echo $model["tel"];?>" required="true" 
        
        />              
        </td>
    </tr>     
   <tr>
        <td class="tableleft">内容：</td>
        <td>
        
<textarea name="content" style="width:200px;height:80px;"><?php echo $model["content"];?></textarea>

        </td>
    </tr>
   <tr>
        <td class="tableleft">管理员回复：</td>
        <td>
        
<textarea name="content2" style="width:200px;height:80px;"><?php echo is_super_admin()?$model["content_admin"]:$model["content_admin2"];?></textarea>
<?php 
if($model["checkstatus"]>0 && $model["common_system_userid"]>0){
?>
<br/>
超管审核人：<?php echo (is_array($admin_user)?$admin_user["username"]:"")."　";?>
审核时间：<?php echo $model["content_admin_date"]?>
<?php 
}
?>
<?php 
if($model["checkstatus"]>0 && $model["common_system_userid2"]>0){
?>
<br/>
二级管理员审核人：<?php echo (is_array($admin_user2)?$admin_user2["username"]:"")."　";?>
审核时间：<?php echo $model["content_admin2_date"]?>
<?php 
}
?>
        </td>
    </tr>      
   <tr>
        <td class="tableleft">是否审核：</td>
        <td>
<input type="radio" name="checkstatus" value="80" <?php echo $model["checkstatus"]=="80"?"checked":"";?> />通过
<input type="radio" name="checkstatus" value="0" <?php echo $model["checkstatus"]=="0" || $model["checkstatus"]==""?"checked":"";?>/>未审
只有审核通过才能在前台显示
        </td>
   </tr>  
 


    <tr>
        <td class="tableleft"></td>
        <td>
<button type="submit" class="btn btn-primary"  id="btnSave">保存</button>            
<button type="button" class="btn btn-primary" onclick="top.topManager.closePage();" >关闭</button>
        </td>
    </tr>
</table>

</form>
</body>
</html>
<script type="text/javascript">
function postform(){

	
	if($("#myform").Valid()) {
		return true;
	}
	else{	
		return false;
	}
}


var errmsg = "<?php echo !isset($err)?"":$err;?>";
if(errmsg!=""){
	parent.tip_show(errmsg,2,3000);	
}
</script>

