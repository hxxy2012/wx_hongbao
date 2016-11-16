<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>中山市电子商务信息管理系统-用户注册</title>
<link rel="stylesheet" type="text/css" href="/home/views/static/swj/images/swj.css">
<!--[if IE 6]>
        <script src="/home/views/static/swj/js/iepng.js" type="text/javascript"></script>
        <script type="text/javascript">
            EvPNG.fix('*');  //EvPNG.fix('包含透明PNG图片的标签'); 多个标签之间用英文逗号隔开。
        </script>
<![endif]-->
<script src="/home/views/static/js/jquery-1.8.1.min.js"></script>
<!--script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script-->
<script src="/home/views/static/js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js?v=2.1"></script>

<script>

function chkform(){	

	
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
<form method="post"  id="myform" onSubmit="return chkform()" action="">
<div class="middle">
	<div id="login">
    	<h3>密码重置</h3>

<input name="pwd3" style="display:none;" type="password" />        
    	<ul>
			<li><span>　　　</span>注意：重置后，才能继续使用系统</li>            
        	<li><span>*邮箱：</span>
            <?php echo $sess["username"];?>
            </li>
            <li><span>*密　码：</span><input name="pwd" type="password" required class="login_box3 r5" id="pwd" placeholder="长度6位到18位，字母和数字组合" size="36" minLength="6" maxlength="18" valType="mm"></li>
            <li><span>*确认密码：</span><input name="pwd2" type="password" required class="login_box3 r5" id="pwd2"  placeholder="长度6位到18位，字母和数字组合" size="36" minLength="6" maxlength="18" valType="mm"></li>              
       		                                            
          <li style="text-align:center;"><input type="submit" value="修改密码" class="login_btn2 r5"><input type="button" value="暂不修改" onClick="window.location.href='<?php echo site_url('home/logout');?>';" class="login_btn3 r5">
<!--span onClick="chktelcode()"          >testtest</span-->
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

function chktelcode(){
	istrue = false;
	if($("telcode").val()!="" && $("#mysql_tel").val()!=""){
		$.ajax({
			url:"<?php echo site_url("home/chktelcode");?>",
			data:{param:$("#telcode").val(),tel:$("#mysql_tel").val()},
			dataType: "json",
			type: "GET",			
			async:false,
			success: function(data){			
				json = eval(data);
				if(json.result=="1"){
					istrue = true;	
				}
				else{
					layer.msg(json.msg,'2');
					$("#telcode").focus();
				}
			},
			error:function(a,b,c){
				
			}
		});	
	}
	return istrue;
}
</script>
