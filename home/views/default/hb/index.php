<!DOCTYPE html>
<html>
	<head>
		<title>手机摇一摇抢红包</title>
    	<?php $this->load->view(__TEMPLET_FOLDER__ . '/hb/headerinc.php'); ?>
    	<script type="text/javascript">
		function playAudio(){
			if (window.HTMLAudioElement) {
				var oAudio = document.getElementById('audio');
				oAudio.play();
			}
		}
		window.onload = function() {
		
		    //create a new instance of shake.js.
		    var myShakeEvent = new Shake({
		        threshold: 15
		    });
		
		    // start listening to device motion
		    myShakeEvent.start();
		
		    // register a shake event
		    window.addEventListener('shake', shakeEventDidOccur, false);
		
		    //shake event callback
		    function shakeEventDidOccur () {
		
		        //put your own code here etc.
				$('.shakeimg').removeClass('shake');
				setInterval(function(){$('.shakeimg').addClass('shake')},0)
		        playAudio();
		    }
		};
		</script>
		<script type="text/javascript" src="/home/views/static/hb/js/shake.js"></script>
		<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script>
			wx.config({
			    debug: false,
			    appId: '<?php echo $jssdk['appId']?>',
			    timestamp: <?php echo $jssdk['timestamp']?>,
			    nonceStr: '<?php echo $jssdk['nonceStr']?>',
			    signature: '<?php echo $jssdk['signature']?>',
			    jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone']
			});
			wx.ready(function(){

			    // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
				//分享到朋友圈
				wx.onMenuShareTimeline({
				    title: '手机摇一摇抢红包', // 分享标题
				    link: 'http://ryr.qiuqiuqi.com/', // 分享链接
				    imgUrl: '', // 分享图标
				    success: function () { 
				        // 用户确认分享后执行的回调函数
				    },
				    cancel: function () { 
				        // 用户取消分享后执行的回调函数
				    }
				});
				//分享给朋友
				wx.onMenuShareAppMessage({
				    title: '手机摇一摇抢红包', // 分享标题
				    desc: '摇一摇，红包', // 分享描述
				    link: 'http://ryr.qiuqiuqi.com/', // 分享链接
				    imgUrl: '', // 分享图标
				    type: '', // 分享类型,music、video或link，不填默认为link
				    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
				    success: function () { 
				        // 用户确认分享后执行的回调函数
				    },
				    cancel: function () { 
				        // 用户取消分享后执行的回调函数
				    }
				});
				//分享到qq
				wx.onMenuShareQQ({
				    title: '手机摇一摇抢红包', // 分享标题
				    desc: '摇一摇，红包', // 分享描述
				    link: 'http://ryr.qiuqiuqi.com/', // 分享链接
				    imgUrl: '', // 分享图标
				    success: function () { 
				       // 用户确认分享后执行的回调函数
				    },
				    cancel: function () { 
				       // 用户取消分享后执行的回调函数
				    }
				});
				//分享到腾讯微博
				wx.onMenuShareWeibo({
				    title: '手机摇一摇抢红包', // 分享标题
				    desc: '摇一摇，红包', // 分享描述
				    link: 'http://ryr.qiuqiuqi.com/', // 分享链接
				    imgUrl: '', // 分享图标
				    success: function () { 
				       // 用户确认分享后执行的回调函数
				    },
				    cancel: function () { 
				        // 用户取消分享后执行的回调函数
				    }
				});
				//分享到QQ空间
				wx.onMenuShareQZone({
				    title: '手机摇一摇抢红包', // 分享标题
				    desc: '摇一摇，红包', // 分享描述
				    link: 'http://ryr.qiuqiuqi.com/', // 分享链接
				    imgUrl: '', // 分享图标
				    success: function () { 
				       // 用户确认分享后执行的回调函数
				    },
				    cancel: function () { 
				        // 用户取消分享后执行的回调函数
				    }
				});
			});
		</script>
	</head>
	<body>
		<div class="audio" style="width:0; height:0px; overflow:hidden; text-indent:-999px;">
			<audio id='audio' src='/home/views/static/hb/sound/5018.mp3' autoplay  style="width:0; height:0px;"></audio>
		</div>
		<div class="content">
			<div class="indexbg">
				<img class="indexBg" src="/home/views/static/hb/images/indexbg.png" />
				<img class="shakeimg" src="/home/views/static/hb/images/shake.png" />
				<img class="cp" src="/home/views/static/hb/images/cp.png" />
			</div>
			<div class="nav">
				<ul class="clearfix">
					<li  onclick="document.getElementById('hdsm').style.display='block';document.getElementById('hdsmContent').style.display='block';"><a href="#"><img src="/home/views/static/hb/images/hdsm.png"/><p>活动说明</p></a></li>
					<li><a href="<?php echo site_url('home/zjjl')?>"><img src="/home/views/static/hb/images/zjjl.png"/><p>中奖纪录</p></a></li>
					<li onclick="document.getElementById('hdsm').style.display='block';document.getElementById('fx').style.display='block';"><a href="#"><img src="/home/views/static/hb/images/fx.png"/><p>分享</p></a></li>
				</ul>
			</div>
		</div>
		<div class="power">你好世界!</div>
        
        
        
		<div id="hdsm">
			<div id="hdsmContent" class="hdsmContent">
				<h3>活动说明</h3>
				<div class="back"><a href="javascrpit:void(0)" onclick="document.getElementById('hdsm').style.display='none';document.getElementById('hdsmContent').style.display='none';"><img src="/home/views/static/hb/images/X.png" /></a></div>
				<div class="pContent">
					<p>活动说明活动说明活说明活动说明活动说明活动说明活说明活动说明活动说明活动说明</p>
					<p>活动说明活动说明活动说明活动说明活动说明活说明活动说明活动说明活动说明活动说明活动说明</p>
					<p>活动说明活动说明活说明活动说明活动说明活动说明活活动说明活动说明活动说明活动说明</p>
					<p>活动说明说明活动说明活动说明活动说明活说明活动说明活动说明活动说明活明活动说明活动说明活动说明活动说明</p>
					<p>活动说明说明活动说明活动说明活动说明活说明活动说明活动说明活动说明活明活动说明活动说明活动说明活动说明</p>
					<p>活动说明说明活动说明活动说明活动说明活说明活动说明活动说明活动说明活明活动说明活动说明活动说明活动说明</p>
					<p>活动说明说明活动说明活动说明活动说明活说明活动说明活动说明活动说明活明活动说明活动说明活动说明活动说明</p>
				</div>
			</div>
			<div id="fx">
				<img src="/home/views/static/hb/images/fxbg.png" />
			</div>
		</div>
	</body>
</html>
