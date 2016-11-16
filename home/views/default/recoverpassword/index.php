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
    /* Example Styles for Demo */
    .etabs { margin: 0; padding: 0; }
    .tab { display: inline-block; zoom:1; *display:inline; background: #eee; border: solid 1px #999; border-bottom: none; -moz-border-radius: 4px 4px 0 0; -webkit-border-radius: 4px 4px 0 0; }
    .tab a { font-size: 14px; line-height: 2em; display: block; padding: 0 10px; outline: none; }
    .tab a:hover { text-decoration: underline; }
    .tab.active { background: #fff; padding-top: 6px; position: relative; top: 1px; border-color: #666; }
    .tab a.active { font-weight: bold; }
    .tab-container .panel-container { background: #fff; border: solid #666 1px; padding: 10px; -moz-border-radius: 0 4px 4px 4px; -webkit-border-radius: 0 4px 4px 4px; }
    .panel-container { margin-bottom: 10px; }
    #login #tab-container ul{padding: 0;}
    #login #tab-container .panel-container ul span{color:#333333;}
    .inheight25{height: 25px;}
  </style>
</style>
<script src="/home/views/static/js/jquery-1.8.1.min.js"></script>
<script src="/home/views/static/js/jquery.hashchange.min.js"></script>
<script src="/home/views/static/js/jquery.easytabs.min.js"></script>
<script src="/home/views/static/js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js"></script>
<script type="text/javascript">
    $(document).ready( function() {
      $('#tab-container').easytabs();
      //监听切换前的事件
      $('#tab-container').bind('easytabs:before', function(){
      		// 隐藏弹出的提示框
      		$('.error').hide();
      		$('.succ').hide();
      });
    });
  </script>
</head>

<body>
<div class="logo"><img src="/home/views/static/swj/images/logo.png" title="中山市电子商务信息管理系统" alt="中山市电子商务信息管理系统"></div>
<div class="middle">
	<div id="login">
    	<h3>忘记密码</h3>
    	<div id="tab-container" class='tab-container'>
		 <ul class='etabs'>
		   <li class='tab'><a href="#tabs1-email">邮箱找回</a></li>
		   <li class='tab'><a href="#tabs1-phone">手机找回</a></li>
		 </ul>
		 <div class='panel-container'>
		  <div id="tabs1-email">
		  	<div class="inheight25"></div>
			<form method="post" id="myform1" onSubmit="return chkform_yx()" name="myform1"  action="<?php echo site_url('Swj_recover_password/getPwdByEmail');?>">
				<ul>
			       		<li>
			       			<div style="width:46%;margin:0 auto;">
			       				<span>*注册邮箱：</span><input type="text" class="login_box3 r5"
			             name="mysql_email" id="mysql_email"  size="30" required>
			       			</div>
			       		</li>  
			       		 <li>
			            	<div style="width:46%;margin:0 auto;">
			            	<span>*验证码：</span><img  
			          	src="/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" alt="获取验证码" name="flush"  onClick="document.getElementById('flush1').src='/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" height="25" align="absbottom" id="flush1" style="cursor:pointer;margin-left:0px;" />
			          	<input name="code1" type="text" class="login_box3 r5" id="code1" size="8" maxlength="4" style="width:50px;" ame="code1" valType='yzm'  remoteUrl="/index.php/Swj_recover_password/chkcode.shtml"   required>     
			            	</div>
			            </li>            
			            <li style="text-align:center;"><input type="submit" id="send_email" value="发送" class="login_btn2 r5"><input type="button" value="返回上页" onClick="window.location.href='<?php echo $ls;?>';" class="login_btn3 r5">
			            </li>
			    </ul>
			</form>
		  </div>
		  <div id="tabs1-phone">
		  	<div class="inheight25"></div>
			<form method="post"  id="myform" onSubmit="return chkform()" action="<?php echo site_url('Swj_recover_password/getPwdByPhone');?>">
				<ul>
		       		<li>
		       			<div style="width:35%;margin:0 auto;">
		       			<span>*手机号：</span><input type="text" class="login_box3 r5"
		             name="mysql_tel" id="mysql_tel" required size="10" maxlength="11"  valType='mobile'   remoteUrl="/index.php/Swj_recover_password/chktel.shtml"  >
						</div>
		         	</li>              
		              
		            <li>
		            	<div style="width:35%;margin:0 auto;">
		            	<span>*验证码：</span><img  
		          	src="/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" alt="获取验证码" name="flush"  onClick="document.getElementById('flush').src='/index.php/Swj_recover_password/code.shtml?rnd='+Math.random()" height="25" align="absbottom" id="flush" style="cursor:pointer;margin-left:0px;" />
		          	<input name="code" type="text" class="login_box3 r5" id="code" size="8" maxlength="4" style="width:50px;" ame="code" valType='yzm'  remoteUrl="/index.php/Swj_recover_password/chkcode.shtml"   required>     
		            	</div>
		            </li>
		            <li style="text-align:center;"><input type="submit" value="找回" class="login_btn2 r5"><input type="button" value="返回上页" onClick="window.location.href='<?php echo $ls;?>';" class="login_btn3 r5">
		            </li>
		        </ul>
			</form>
		  </div>
		 </div>
	</div>
    </div>
    <div class="inheight25"></div>
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
			if (!email||!emailCheck(email)) {
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
                $('#mysql_email').focus();
				return false;
			}
			//验证码
			if ($("#myform1").Valid()) {	
		   	    //document.getElementById("myform").submit();
				return true;
			}
			else {
				return false;
			}
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
