<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>中山市电子商务信息管理系统-重置密码</title>
<link rel="stylesheet" type="text/css" href="/home/views/static/swj/images/swj.css">
<!--[if IE 6]>
        <script src="/home/views/static/swj/js/iepng.js" type="text/javascript"></script>
        <script type="text/javascript">
            EvPNG.fix('*');  //EvPNG.fix('包含透明PNG图片的标签'); 多个标签之间用英文逗号隔开。
        </script>
<![endif]-->
<script src="/home/views/static/js/jquery-1.8.1.min.js"></script>
<script src="/home/views/static/js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js"></script>
<script>
function chkform(){	

	/*if($("input[name='mysql_usertype']:checked").length==0){
		layer.msg('企业用户/协会或机构用户未选','2');
	 	return false;	
	}*/
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
	
	if ($("#myform").Valid()) {	
   	    //document.getElementById("myform").submit();
		return true;
	}
	else {
		return false;
	}
}
</script>
</head>

<body>
<div class="logo"><img src="/home/views/static/swj/images/logo.png" title="中山市电子商务信息管理系统" alt="中山市电子商务信息管理系统"></div>
<form method="post"  id="myform" onSubmit="return chkform()" action="<?php echo site_url("Swj_recover_password/step2_do");?>">
<div class="middle">
	 <input type="hidden" name="mysql_username" readonly="readonly" value="<?php echo $mysql_username;?>">
          <input type="hidden" name="mysql_tel" readonly="readonly" value="<?php echo $mysql_tel;?>">
          <input type="hidden" name="telcode" readonly="readonly" value="<?php echo $telcode;?>">                          
	<div id="login">
    	<h3>重置密码</h3>
    	<ul>
          <li><span>*密　码：</span><input name="pwd" type="password" required class="login_box3 r5" id="pwd" placeholder="长度6位到18位，字母和数字组合" size="36" minLength="6" maxlength="18" valType="mm"></li>
          <li><span>*确认密码：</span><input name="pwd2" type="password" required class="login_box3 r5" id="pwd2"  placeholder="长度6位到18位，字母和数字组合" size="36" minLength="6" maxlength="18" valType="mm"></li>              
          <li style="text-align:center;"><input type="submit" value="提交" class="login_btn2 r5"><input type="button" value="返回首页" onClick="window.location.href='<?php echo $ls;?>';" class="login_btn3 r5">
<!-- <span onClick="chkform()"          >testtest</span> -->
          </li>
        </ul>
    </div>
</div>
</form>
<div class="foot"><img src="/home/views/static/swj/images/index_03.png"></div>
</body>
</html>
