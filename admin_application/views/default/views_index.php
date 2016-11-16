<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>中山市走出去项目管理系统-管理员后台</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
        <!--meta http-equiv="X-UA-Compatible" content="IE=edge" /-->
        <style>
            .float_layer { width: 300px; border: 1px solid #aaaaaa; display:none; background: #fff; }
            .float_layer h2 { height: 25px; line-height: 25px; padding-left: 10px; font-size: 14px; color: #333; background: url(<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/title_bg.gif) repeat-x; border-bottom: 1px solid #aaaaaa; position: relative; }
            .float_layer .min { width: 21px; height: 20px; background: url(<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/min.gif) no-repeat 0 bottom; position: absolute; top: 2px; right: 25px; }
            .float_layer .min:hover { background: url(<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/min.gif) no-repeat 0 0; }
            .float_layer .max { width: 21px; height: 20px; background: url(<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/max.gif) no-repeat 0 bottom; position: absolute; top: 2px; right: 25px; }
            .float_layer .max:hover { background: url(<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/max.gif) no-repeat 0 0; }
            .float_layer .close { width: 21px; height: 20px; background: url(<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/close.gif) no-repeat 0 bottom; position: absolute; top: 2px; right: 3px; }
            .float_layer .close:hover { background: url(<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/close.gif) no-repeat 0 0; }
            .float_layer .content { height: 120px; overflow: hidden; font-size: 14px; line-height: 18px; color: #666; text-indent: 28px; }
            .float_layer .wrap { padding: 10px; }

            .float{
                float:left;
            }
            .clear{
                float:none;
                clear:both;
            }

        </style>
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/main-min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
        <script>
            $(function() {
                nav();
            });

        </script>
        <script type="text/javascript">
            function miaovAddEvent(oEle, sEventName, fnHandler)
            {
                if (oEle.attachEvent)
                {
                    oEle.attachEvent('on' + sEventName, fnHandler);
                }
                else
                {
                    oEle.addEventListener(sEventName, fnHandler, false);
                }
            }


            window.onload = function()
            {
                var oDiv = document.getElementById('miaov_float_layer');
                var oBtnMin = document.getElementById('btn_min');
                var oBtnClose = document.getElementById('btn_close');
                var oDivContent = oDiv.getElementsByTagName('div')[0];

                var iMaxHeight = 0;

                var isIE6 = window.navigator.userAgent.match(/MSIE 6/ig) && !window.navigator.userAgent.match(/MSIE 7|8/ig);

                oDiv.style.display = 'block';
                iMaxHeight = oDivContent.offsetHeight;

                if (isIE6)
                {
                    oDiv.style.position = 'absolute';
                    repositionAbsolute();
                    miaovAddEvent(window, 'scroll', repositionAbsolute);
                    miaovAddEvent(window, 'resize', repositionAbsolute);
                }
                else
                {
                    oDiv.style.position = 'fixed';
                    repositionFixed();
                    miaovAddEvent(window, 'resize', repositionFixed);
                }

                oBtnMin.timer = null;
                oBtnMin.isMax = true;
                oBtnMin.onclick = function()
                {
                    startMove
                            (
                                    oDivContent, (this.isMax = !this.isMax) ? iMaxHeight : 0,
                                    function()
                                    {
                                        oBtnMin.className = oBtnMin.className == 'min' ? 'max' : 'min';
                                    }
                            );
                };

                oBtnClose.onclick = function()
                {
                    oDiv.style.display = 'none';
                };
            };

            function startMove(obj, iTarget, fnCallBackEnd)
            {
                if (obj.timer)
                {
                    clearInterval(obj.timer);
                }
                obj.timer = setInterval
                        (
                                function()
                                {
                                    doMove(obj, iTarget, fnCallBackEnd);
                                }, 30
                                );
            }

            function doMove(obj, iTarget, fnCallBackEnd)
            {
                var iSpeed = (iTarget - obj.offsetHeight) / 8;

                if (obj.offsetHeight == iTarget)
                {
                    clearInterval(obj.timer);
                    obj.timer = null;
                    if (fnCallBackEnd)
                    {
                        fnCallBackEnd();
                    }
                }
                else
                {
                    iSpeed = iSpeed > 0 ? Math.ceil(iSpeed) : Math.floor(iSpeed);
                    obj.style.height = obj.offsetHeight + iSpeed + 'px';

                    ((window.navigator.userAgent.match(/MSIE 6/ig) && window.navigator.userAgent.match(/MSIE 6/ig).length == 2) ? repositionAbsolute : repositionFixed)()
                }
            }

            function repositionAbsolute()
            {
                var oDiv = document.getElementById('miaov_float_layer');
                var left = document.body.scrollLeft || document.documentElement.scrollLeft;
                var top = document.body.scrollTop || document.documentElement.scrollTop;
                var width = document.documentElement.clientWidth;
                var height = document.documentElement.clientHeight;

                oDiv.style.left = left + width - oDiv.offsetWidth + 'px';
                oDiv.style.top = top + height - oDiv.offsetHeight + 'px';
            }

            function repositionFixed()
            {
                var oDiv = document.getElementById('miaov_float_layer');
                var width = document.documentElement.clientWidth;
                var height = document.documentElement.clientHeight;

                oDiv.style.right = '0px';
                oDiv.style.bottom = '0px';
            }


             

              
        </script> 
    </head>
    <body>

        <div class="header">

            <div class="dl-title">
             <!--<img src="/chinapost/Public/assets/img/top.png">-->			 
			 中山市走出去项目管理系统-管理员后台
            </div>

            <div class="dl-log">

         
            
            <a href="#" id="open_tab_shenqing" style="display:none;">申请入会审核 <span class="badge" id="shenqing_count"></span></a>
            <a href="#" id="open_tab_baoming" style="display:none;">报名预约<span class="badge" id="baoming_count"></span></a>

          
          
            欢迎您，<span class="dl-log-user"><?php echo $username; ?>
            所在组:<?php echo $group_name; ?></span>
    
            &nbsp;
			<a href="#" id="btnShow"  class="dl-log-quit" >[修改密码]</a>
			&nbsp;
			<a href="/" target="_blank"   class="dl-log-quit"   >[回到首页]</a>

            <a href="javascript:void(0)" title="退出系统" class="dl-log-quit" onclick="login_out()">[退出]</a>
                <div><a href="javascript:void(0);" onclick="cc()">saas</a></div>
            </div> 
        </div>
<script>
$('#open_tab_shenqing').click(function(e){  
    e.preventDefault();
    if(top.topManager){
      //打开左侧菜单中配置过的页面
      top.topManager.openPage({
    	moduleId:'130',
        id : '216',
        search : '',
        reload : true
      });
    }
  });

$('#open_tab_baoming').click(function(e){  
    e.preventDefault();
    if(top.topManager){
      //打开左侧菜单中配置过的页面
      top.topManager.openPage({
    	moduleId:'130',
        id : '220',
        search : '',
        reload : true
      });
    }
  }); 



</script>        
        <div class="content">
            <div class="dl-main-nav">
                <div class="dl-inform"><div class="dl-inform-title"><s class="dl-inform-icon dl-up"></s></div></div>
                <ul id="J_Nav"  class="nav-list ks-clear">
                    <!-- 
                   <li class="nav-item dl-selected"><div class="nav-item-inner nav-home">系统管理</div></li>
                   <li class="nav-item dl-selected"><div class="nav-item-inner nav-order">业务管理</div></li>  
                    -->
                    <?php
                    if (isset($list) && $list) {
                        foreach ($list as $l_k => $l_v) {
                            $selected = '';
                            if ($l_k == 0) {
                                $selected = 'dl-selected';
                            }
                          

                           
                            ?> 


                            <li  <?php if ($l_v['status'] == '0' || !$l_v["isshow"]): ?>style="display:none;"<?php endif; ?>     class="nav-item <?php echo $selected; ?>"><div class="nav-item-inner nav-order"><?php echo $l_v['name']; ?></div></li>

                            <?php
                        }
                    }
                    ?>     

                </ul>
            </div>
            <ul id="J_NavContent" class="dl-tab-conten">

            </ul>
        </div>

        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/common/main-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
        <script type="text/javascript" >
                    function nav() {
                        BUI.use('common/main', function() {
                            // new PageUtil.MainPage({
                            //modulesConfig : config
                            //});
                            //获取json
                            $.getJSON('<?php echo site_url('common/get_menu'); ?>', function(config) {
                                //console.dir(config);
                                new PageUtil.MainPage({
                                    modulesConfig: config
                                });

                            });
                        });

                    }
        </script>
    </body>
</html>
<div class="float_layer" id="miaov_float_layer">
    <h2>
        <strong>温馨提示:</strong>
        <a id="btn_min" href="javascript:;" class="min"></a>
        <a id="btn_close" href="javascript:;" class="close"></a>
    </h2>
    <div class="content">

        <div class="wrap">
            <p>你好:<?php echo $this->username; ?></p>
            <p>上次登录时间:<?php echo isset($login_info[1]['logintime']) ? $login_info[1]['logintime'] : '首次登录'; ?></p>
            <p>登录ip地址:<?php echo isset($login_info[1]['ip']) ? $login_info[1]['ip'] : '首次登录'; ?></p>

            <!--通知 开始-->
            <div>
                <div class="float" id="tongzhi_message"></div>
<!--                <div id="know" class="float"><input type="hidden" id="mkid" name="mkid" value=""/><input type="radio" name="is_know"  value="1" id="is_know" /><label for="is_know">不再提示</label></div>-->
            </div>
            <div class="clear"></div>
            <!--通知 结束-->
        </div>


    </div>
</div>
</div>

<script type="text/javascript">
    var Overlay = BUI.Overlay
    var dialog = new Overlay.Dialog({
        title: '修改密码',
        width: 500,
        height: 200,
        loader: {
            url: '<?php echo site_url('sys_admin/edit_passwd') ?>',
            autoLoad: false, //不自动加载
            params: {"inajax": "1"}, //附加的参数
            lazyLoad: false //不延迟加载
        },
        mask: true,
        success: function() {
            var passwd = $("#passwd").val();
            var repasswd = $("#repasswd").val();
            if (passwd == '' || repasswd == '') {
                BUI.Message.Alert('密码不可以为空', 'error');
                return false;
            } else if (passwd != repasswd) {
                BUI.Message.Alert('2次密码不相同', 'error');
                return false;
            }
            var data_ = {
                'action': "doedit",
                'passwd': passwd,
                'repasswd': repasswd
            };
            $.ajax({
                type: "POST",
                url: "<?php echo site_url("sys_admin/edit_passwd"); ?>",
                data: data_,
                cache: false,
                dataType: "json",
                //  async:false,
                success: function(msg) {
                    var code = msg.resultcode;
                    var message = msg.resultinfo.errmsg;
                    if (code == 1) {
                        window.location.href = "<?php echo site_url("login/login_out"); ?>";
                    } else if (code < 0) {
                        BUI.Message.Alert('对不起没权限', 'error');
                        return false;
                    } else {
                        BUI.Message.Alert(message, 'error');
                        return false;
                    }
                },
                beforeSend: function() {
                    $("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
                },
                error: function() {
                    alert('服务器繁忙请稍。。。。');
                }

            });

            this.close();
        }
    });
//dialog.show(); //是否自动显示
    $('#btnShow').on('click', function() {
        dialog.show();
        dialog.get('loader').load();
    });
    var dialog_showpage; 
    function showpage(url,pagetitle,width,height){
        BUI.use('bui/overlay',function(Overlay){
        	this.dialog_showpage = new Overlay.Dialog({
              title:pagetitle,
              width:width,
              height:height,
              closeAction : 'destroy', //每次关闭dialog释放
              mask:false,
              buttons:[],
              loader: {
                  url: url,
                  autoLoad: true, //不自动加载
                  params: {"inajax": "1"}, //附加的参数
                  lazyLoad: false //不延迟加载
              }              
            });
        	dialog_showpage.show();         
        });    	
    }
    function showpage_close(){
        //alert(this.dialog_showpage);
        dialog_showpage.close();
    }
    function login_out() {
    	window.location.href = "<?php echo site_url("login/login_out"); ?>";
    	/*
        BUI.Message.Confirm('确定要退出系统吗?',
            function() {
            window.location.href = "<?php echo site_url("login/login_out"); ?>";
        }, 'question'
            
        );
        */

    }

    /*
     *加载提示消息
     */
    function tips() {
        var Overlay = BUI.Overlay
        var dialog = new Overlay.Dialog({
            title: '系统提示信息',
            width: 500,
            height: 200,
            loader: {
                url: '<?php echo site_url('sys_admin/edit_passwd') ?>',
                autoLoad: false, //不自动加载
                params: {"inajax": "1"}, //附加的参数
                lazyLoad: false //不延迟加载
            },
            mask: true,
            success: function() {

            }
        });
        dialog.show();
        dialog.get('loader').load();
    }
//setInterval("tips()",5000);
//$("#J_Nav li:eq(2)").remove();


//    setInterval("tongzhi_message()", 1000);
    know = document.getElementById('know');
    function tongzhi_message()
    {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("mokuai/check_message"); ?>",
            cache: false,
            dataType: "json",
            success: function(msg) {
                if (msg != null) {

                    var oDiv = document.getElementById('miaov_float_layer');
                    oDiv.style.display = 'block';


//                    $('#tongzhi_message').html('<span style="color:red;font-size:bold;">'+SetSub(msg.m_title,10)+'&nbsp;'+msg.message+'</span>');
                    $('#tongzhi_message').html(msg.message);

                    $('#mkid').val(msg.mokuai_id);
                    $('#know').show();
                    //改为不选中状态
                    know.checked = false;
                } else {

                    $('#tongzhi_message').html('');
                    $('#mkid').val('');
                    $('#know').hide();
                }


            }
        });
    }


//更新数据库，改为知道后就不会再提示
    $('#is_know').click(function() {
        var mkid = $('#mkid').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("mokuai/change_message_stat"); ?>",
            data: 'mkid=' + mkid,
            dataType: "json",
        });
        //改为不选中状态
        this.checked = false;
    });






//字符串截取

    function SetSub(str, n) {
        var strReg = /[^\x00-\xff]/g;
        var _str = str.replace(strReg, "**");
        var _len = _str.length;
        if (_len > n) {
            var _newLen = Math.floor(n / 2);
            var _strLen = str.length;
            for (var i = _newLen; i < _strLen; i++) {
                var _newStr = str.substr(0, i).replace(strReg, "**");
                if (_newStr.length >= n) {
                    return str.substr(0, i) + "...";
                    break;
                }
            }
        } else {
            return str;
        }
    }
    //SetSub("中文english",5) //中文e...  

    //管理员点信息后的url跳转
    function urlgo(urlhref, mkids) {
        //获取当前id
        var container = document.getElementById('container');
        if (container) {
            //再修改获取到元素id
            container.id = 'container_' + Math.floor(Math.random() * 100000);
            document.getElementById(container.id).src = urlhref;
        } else {
            //点击第二次的
            var container_name = document.getElementsByName('container')[0];
            document.getElementById(container_name.id).src = urlhref;
        }
//        search_title
        setCookie("mokuai_search_title", mkids, 7200);
    }

	//显示短暂信息 ：内容，显示图标，显示时间  如：500毫秒
    function tip_show (content,showmode,showtime) {
            BUI.Message.Show({
              msg:content,
              icon:(showmode==1?'success':'question'),
              buttons:[],
              autoHide:true,
              autoHideDelay:showtime
    	});
            
    }    
    //判断是否为JSON格式
    function MyisJson(obj){
        var isjson = typeof(obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;        
        return isjson;
    }    
    var my_GetAjax_data = "";
    //url:激活TAB的网址
    function my_confirm(title,ajax_url,url){       
    	BUI.Message.Confirm(title,function(){                	
    	   	if(url!=""){
    	   		my_GetAjax(ajax_url);    	   		
    	   		if(my_GetAjax_data!="")
    	   		{
        	   		//alert(my_GetAjax_data);
    	   	    	if(my_GetAjax_data!=""){
        	   	    	if(MyisJson(my_GetAjax_data)){
        	   	    		alert(my_GetAjax_data.resultinfo);
        	   	    	}
        	   	    	else{
        	        		alert(my_GetAjax_data);
        	   	    	}
        				//tip_show(my_GetAjax_data,0,2000);
        	   			my_GetAjax_data=""; 
        	    	}  
    	   		}
    	   		else{
	    	    	$('.tab-content').each(function(index,item){
	    	    	   //alert($(item).find('iframe').attr("src"));
	    	    	   url2 = $(item).find('iframe').attr("src");
	    	    	   url = url.toLowerCase();
	    	    	   url2 = url2.toLowerCase();	    	    	   
	    	    	   //alert("url="+url+"\n url2="+$url2);
	    	    	   //alert($url2.indexOf(url));
	    	    	      if(url2.indexOf(url)>=0){
	      	    	        //刷新页面    		    	  	
	    		    	  	$(item).find('iframe').attr("src",url2);
	    	    	      } 	 
	    	    	});
    	   		}    	  	   	    	   	
        	}    		  			       		 
          },'question'); 
       
    }
    
    function flushpage(url){
    	$('.tab-content').each(function(index,item){
	    	   //alert($(item).find('iframe').attr("src"));
	    	   $url2 = $(item).find('iframe').attr("src");	    	  
	    	      if(url.indexOf($url2)>=0){
	    	        //刷新页面    	    	        	    	  
		    	  	$(item).find('iframe').attr("src",url);
	    	      } 	 
	    	});
    }
	
    function my_GetAjax(url){
        $.ajax({
            type: "GET",
            url: url,            
            dataType: "html",
            async : false,
            error:function(a,b,c,d){},
            success: function(data){                      
              			if(data=="yes"){
    						//window.location.reload();              				
              			}
              			else if(data!="yes" && data!=""){
              				//tip_show(data,0,2000);
              				my_GetAjax_data = data;            				
              			}
              			else{
    						
              			}
                     }
        });			
    }

    function my_GetAjaxPost(url,data){
        //data:{title:'aa',id:'2'}
        $.ajax({
            type: "POST",
            url: url,
            data:data,            
            dataType: "html",
            async : false,
            error:function(a,b,c,d){},
            success: function(data){                      
              			if(data=="yes"){
    						//window.location.reload();
              			}
              			else{
    						
              			}
                     }
        });	
    }
    

</script>
