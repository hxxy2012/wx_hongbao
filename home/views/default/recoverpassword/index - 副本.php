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
<style>
	.tab_box{width:100%;margin: 0 auto;text-align: center;}
	.tab{width: 100%;margin: 0 auto;text-align: center;}
	.tab ul{margin: 0;padding: 0;list-style: none;;}
	.tab ul li{width: 15%;float: left;margin-left: 0;background: #B0C915;padding: 5px 0 5px 0;}
	.tab ul li:hover{cursor: pointer;}
	.tab ul .on{background: #FB9100;color: #fff;}
	.tab ul .on:hover{cursor: text;}
	.content_box{clear: both;width: 100%;text-align: left;}
	.content_box div{display: none;}
	.content_box .show{display: block;}
	.hide{display: none;}
	.inheight50{height: 50px;}
	#login .tab_box .tab span{display: inline;color: #333333;}
	#login .tab_box .on span{display: inline;color: #fff;}
	#login .tab_box .tab li{min-height: 0px;}
</style>
<script src="/home/views/static/js/jquery-1.8.1.min.js"></script>
<script src="/home/views/static/js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js"></script>
</head>

<body>
<div class="logo"><img src="/home/views/static/swj/images/logo.png" title="中山市电子商务信息管理系统" alt="中山市电子商务信息管理系统"></div>
<div class="middle">
	<div id="login">
    	<h3>忘记密码</h3>
    	<div class="tab_box">
    		<div class="tab">
    			<ul>
    				<li class="on"><span>手机找回</span></li>
    				<li><span>邮箱找回</span></li>
    			</ul>
    		</div>
    		<div class="clear"></div>
    		<div class="inheight50"></div>
    		<div class="content_box">
    			<div class="show">
    				<form method="post"  id="myform" onSubmit="return chkform()" action="<?php echo site_url('Swj_recover_password/getPwdByPhone');?>">
    					<ul>
				       		<li><span>*手机号：</span><input type="text" class="login_box3 r5"
				             name="mysql_tel" id="mysql_tel" required size="10" maxlength="11"  valType='mobile'   remoteUrl="/index.php/Swj_recover_password/chktel.shtml"  ></li>              
				              
				            <li><span>*验证码：</span><input name="code" type="text" class="login_box3 r5" id="code" size="8" maxlength="4" style="width:50px;" ame="code" valType='yzm'  remoteUrl="/index.php/Swj_recover_password/chkcode.shtml"   required><img  
				          	src="/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" alt="获取验证码" name="flush"  onClick="document.getElementById('flush').src='/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" height="25" align="absbottom" id="flush" style="cursor:pointer;margin-left:10px;" />     
				            </li>
				            <li style="text-align:center;"><input type="submit" value="找回" class="login_btn2 r5"><input type="button" value="返回上页" onClick="window.location.href='<?php echo $ls;?>';" class="login_btn3 r5">
				            </li>
				        </ul>
    				</form>
    			</div>
    			<div>
    				<form method="post" id="myform1" onSubmit="return chkform_yx()" name="myform1"  action="<?php echo site_url('Swj_recover_password/getPwdByEmail');?>">
	    				<ul>
					       		<li><span>*注册邮箱：</span><input type="text" class="login_box3 r5"
					             name="mysql_email" id="mysql_email"  size="30"></li>              
					            <li style="text-align:center;"><input type="submit" id="send_email" value="发送" class="login_btn2 r5"><input type="button" value="返回上页" onClick="window.location.href='<?php echo $ls;?>';" class="login_btn3 r5">
					            </li>
					    </ul>
					</form>
    			</div>
    		</div>
    	</div>
    </div>
</div>
<div class="foot"><img src="/home/views/static/swj/images/index_03.png"></div>
</body>
</html>

<script>
	flag_yz = true;//验证账号全局变量
	//手机找回密码验证表单
	function chkform(){	

		if ($("#myform").Valid()) {	
	   	    //document.getElementById("myform").submit();
			return true;
		}
		else {
			return false;
		}
	}
	//邮箱找回密码验证表单
	function chkform_yx() {
				//邮箱找回密码验证填写的邮箱
			var email = $('#mysql_email').val();
			if (!emailCheck(email)) {
				layer.msg('请输入正确的邮箱格式',
                            {time: 3000}
                           ); 
                $('#mysql_email').focus();
				return false;
			}
			//ajax验证账号是否存在
			flag_yz = true;
			$.ajax({
				url:"<?php echo site_url("Swj_recover_password/chkusername");?>",
				data:{param:email},
				dataType: "text",
				type: "GET",			
				async:false,
				success: function(data){
					var obj = eval('(' + data + ')');
					if (obj.result == 0){
						flag_yz = false;
						layer.msg(obj.msg,
                            {time: 3000}
                           ); 
					}
				},
				error:function(a,b,c){
					flag_yz = false;
				}
			});
			if (!flag_yz) {
				layer.msg('您填写的邮箱不存在',
                            {time: 3000}
                           ); 
				return false;
			}
			return true;
	}
	$(function(){
		//选项卡切换
		$('.tab ul li').click(function(){
			var obj = $(this);
			var index = obj.index();//索引值
			obj.addClass('on').siblings('li').removeClass('on');
			$('.content_box div').eq(index).addClass('show').siblings('div').removeClass('show');
			if (index == 0) {
				$('#vtip').show();
			} else {
				$('#vtip').hide();
			}
		});
	});
	//验证邮箱格式
	function emailCheck(email) {  
	    var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;  
	    if (!pattern.test(email)) {   
	        return false;  
	    }  
	    return true;  
	}  
</script>
