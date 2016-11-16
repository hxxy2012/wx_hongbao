<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户添加</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/jquery-1.8.1.min.js"></script> 	
	<!--跟layer冲突script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script-->
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js?v=2.1"></script>    
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
<form enctype="multipart/form-data" action="<?php echo site_url("user/add");?>" onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="action" value="doadd">
<input name="pwd3" style="display:none;" type="password" />
<br/>
<table width="99%" border="0" cellpadding="3" cellspacing="1">
<tr>
<td width="100" class="tableleft">
*单位属于：
</td>
<td>
<input type="radio"  name="mysql_usertype" value="45063" />企业用户
&nbsp;
<input type="radio"  name="mysql_usertype" value="45064" />协会或机构用户
</td>
<td>&nbsp;</td>
</tr>
<tr>
  <td class="tableleft">*邮箱：</td>
  <td><input type="text"  name="mysql_username" required size="50" maxlength="50" placeholder="建议：邮箱" valtype='email' minlength="6" style="width:400px;" remoteurl="<?php echo site_url("user/chkusername");?>"  ></td>
  <td>6到50位字母、数字、下划线组合</td>
</tr>
<tr>
  <td class="tableleft">*密　码：</td>
  <td><input name="pwd" type="password" required  id="pwd" placeholder="长度6位到18位，字母和数字组合" style="width:400px;" size="36" minlength="6" maxlength="18" valtype="mm"></td>
  <td>6-18位字母数字组合</td>
</tr>
<tr>
  <td class="tableleft">*确认密码：</td>
  <td><input name="pwd2" type="password" required  id="pwd2"  placeholder="长度6位到18位，字母和数字组合" style="width:400px;" size="36" minlength="6" maxlength="18" valtype="mm"></td>
  <td>6-18位字母数字组合</td>
</tr>
<tr>
  <td class="tableleft"><span>*</span>联系人：</td>
  <td><input type="text"  name="mysql_realname" required size="10" maxlength="50" placeholder="输入姓名" style="width:400px;" /></td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class="tableleft">*手机号：</td>
  <td><input type="text" 
             name="mysql_tel" id="mysql_tel" required size="10" maxlength="11"  valtype='mobile' style="width:400px;"   remoteurl="<?php echo site_url("user/chktel");?>"  ></td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class="tableleft">QQ号码：</td>
  <td><input type="text" style="width:400px;" name="mysql_qq" valtype="int" size="10" maxlength="100" ></td>
  <td>&nbsp;</td>
</tr>
<!--tr>
  <td class="tableleft">微信账号：</td>
  <td><input type="text"  style="width:400px;" name="mysql_weixin_account"  size="10" maxlength="100" ></td>
  <td>&nbsp;</td>
</tr-->
<tr>
  <td class="tableleft">&nbsp;</td>
  <td>
  
<input type="submit" class="btn button-warning" name="btn_save" value="保存" />
  
  <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("user/index");?>">返回列表</a>
  
  </td>
  <td>&nbsp;</td>
</tr>
</table>

    	
  <input type="hidden" name="backurl" value="<?php echo empty($_GET["backurl"])?"":$_GET["backurl"]?>" />
</form>
</body>
</html>
<script type="text/javascript">

function postform(){
	
	if($("input[name='mysql_usertype']:checked").length==0){
		layer.msg('企业用户/协会或机构用户未选','2');
	 	return false;	
	}
	if($("#pwd").val()!=$("#pwd2").val()){
	 	layer.msg('两次输入密码不相同','2');
		//layer.alert('两次输入密码不相同');
	 	return false;	
	}
	if($("#pwd").val().length<6){
	 	layer.msg('密码长度至少6位','2');
		//layer.alert('两次输入密码不相同');
	 	return false;			
	}	
	
	
	if($("#myform").Valid()) {
		return true;
	}
	else{	
		return false;
	}
}
</script>

