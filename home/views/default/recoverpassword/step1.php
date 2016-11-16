<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>中山市电子商务信息管理系统-忘记密码</title>
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
var flag_yz = false;//判断是否验证手机号跟账号是否一致true代表成功
var flag_telcode = false;//判断手机验证码是否正确
function chkform(){	

/*	if($("input[name='mysql_usertype']:checked").length==0){
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
	}*/
	
	if ($("#telcode").val() == '') {
		alert('请您输入手机验证码');
		$('#telcode').focus();
		return false;
	} 
	flag_telcode = true;
	//验证手机验证码是否正确
	$.ajax({
		url:"<?php echo site_url("Swj_recover_password/chktelcode");?>",
		data:{telcode:$("#telcode").val(),tel:$("#mysql_tel").val()},
		dataType: "text",
		type: "POST",			
		async:false,
		success: function(data){
			var obj = eval('(' + data + ')');
			if (obj.result == 0){
				flag_telcode = false;
				alert(obj.msg)
				// layer.msg(data.msg, '2');
			}
		},
		error:function(a,b,c){
			flag_telcode = false;
			// layer.msg('服务器出错,请刷新重试！！', '2');
		}
	});
	if (!flag_telcode) {
		//手机验证码不正确
		$('#telcode').focus();
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
<form method="post"  id="myform" onSubmit="return chkform()" action="<?php echo site_url("Swj_recover_password/step2");?>">
<div class="middle">
	<div id="login">
    	<h3>忘记密码</h3>
    	<ul>
      
                                    
        	<li><span>*用户名：</span><input type="text" class="login_box3 r5" name="mysql_username" id="mysql_username" required size="50" maxlength="50" placeholder="注册时的邮箱、手机号、公司简称首字母" valType='yzm'  remoteUrl="/index.php/Swj_recover_password/chkusername.shtml"  ></li>
       		<li><span>*手机号：</span><input type="text" class="login_box3 r5"
             name="mysql_tel" id="mysql_tel" required size="10" maxlength="11"  valType='mobile'   remoteUrl="/index.php/Swj_recover_password/chktel.shtml"  ></li>              
              
          <li><span>*手机验证码：</span><input name="telcode" id="telcode" type="text"  class="login_box3 r5" size="8" maxlength="6" style="width:50px;"><input type="button" value="获取验证码" id="btn_telcode" class="login_btn4 r5"></li>
          <li><span>*验证码：</span><input name="code" type="text" class="login_box3 r5" id="code" size="8" maxlength="4" style="width:50px;" ame="code" valType='yzm'  remoteUrl="/index.php/Swj_recover_password/chkcode.shtml"   required><img  
          	src="/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" alt="获取验证码" name="flush"  onClick="document.getElementById('flush').src='/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" height="25" align="absbottom" id="flush" style="cursor:pointer;margin-left:10px;" />
            <!-- <img  src="" alt="获取验证码" name="flush"  onClick="document.getElementById('flush').src='/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" height="25" align="absbottom" id="flush" style="cursor:pointer;" /> -->
           
          <!--  <span style='color:#000;cursor:pointer;moz-user-select: -moz-none;
-moz-user-select: none;
-o-user-select:none;
-khtml-user-select:none;
-webkit-user-select:none;
-ms-user-select:none;
user-select:none;' onClick="document.getElementById('flush').src='/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()">
           刷新图
           </span>   -->          
            </li>
          <li style="text-align:center;"><input type="submit" value="下一步" class="login_btn2 r5"><input type="button" value="返回上页" onClick="window.location.href='<?php echo $ls;?>';" class="login_btn3 r5">
<!-- <span onClick="chkform()"          >testtest</span> -->
          </li>
        </ul>
    </div>
</div>
</form>
<div class="foot"><img src="/home/views/static/swj/images/index_03.png"></div>
</body>
</html>
<script>
$("#btn_telcode").click("on",function(){
	tel = $("#mysql_tel").val();
	//检查是否有手机手机号
	var tel_ = /^(130|131|132|133|134|135|136|137|138|139|150|153|157|158|159|180|187|188|189)\d{8}$/;
	if(!tel_.test(tel))
	{
		$("#mysql_tel").focus();
		layer.msg('手机格式不正确','2');
		return false;					
	}
	flag_yz = true;
	//验证账号跟手机号是否正确
	$.ajax({
		url:"<?php echo site_url("Swj_recover_password/chkuserphone");?>",
		data:{username:$("#mysql_username").val(),tel:$("#mysql_tel").val()},
		dataType: "text",
		type: "POST",			
		async:false,
		success: function(data){
			var obj = eval('(' + data + ')');
			if (obj.result == 0){
				flag_yz = false;
				alert(obj.msg)
				// layer.msg(data.msg, '2');
			}
		},
		error:function(a,b,c){
			flag_yz = false;
			// layer.msg('服务器出错,请刷新重试！！', '2');
		}
	});
	if (!flag_yz) {
		return false;
	}
	start();
});
miao = 60;
var dj = "";
function daoji(){
	if(miao>0){
		miao--;
		$("#btn_telcode").attr("disabled",true);
		$("#btn_telcode").val(miao+"秒后重发");
	}
	else{
		miao = 60;		
		window.clearInterval(dj);	
		$("#btn_telcode").attr("disabled",false);
		$("#btn_telcode").val("重发");
	}
}
function start(){
	dj = window.setInterval("daoji()",1000);
	$.ajax({
		url:"<?php echo site_url("home/send_tel_code");?>",
		data:{tel:$("#mysql_tel").val()},
		dataType: "text",
		type: "POST",			
		async:false,
		success: function(data){
			//alert(data);
		},
		error:function(a,b,c){
			
		}
	});
}
</script>
