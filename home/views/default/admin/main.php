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
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/home/views/static/js/bui-min.js"></script>        


<style>
    .lk_ul{margin: 0; padding: 0;width: 100%;}
    .lk_ul li{float: left;width: 46%;margin:10px;}
    .fl{float: left;}
    .fr{float: right;}
    .clear{clear: both;}
    .index-table-p{}
</style>
<script>
BUI.use('common/page');
</script> 
<style>
	.index-table ul li div{position:absolute;
	left:56px;
	top:2px;
	background:#FF0000;
	color:#FFFFFF;
	font-weight:bold;
	width:20px;
	border-radius:8px;
	}
	.index-table ul{padding:0px;margin:0px;}
	.index-table ul li {
		position:relative;
		margin-top:10px;
		width:80px; height:80px;
		 float: left; margin-left:2.5%;text-align: center; 
		border-radius: 10px;
		border:1px #999999 solid;
   		background: -moz-linear-gradient(top, #cccccc 0%, #ffffff 100%);
	    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#cccccc), color-stop(100%,#ffffff));
    	background: -webkit-linear-gradient(top, #cccccc 0%,#ffffff 100%);
	    background: -o-linear-gradient(top, #cccccc 0%,#ffffff 100%);
	    background: -ms-linear-gradient(top, #cccccc 0%,#ffffff 100%);
	    background: linear-gradient(to bottom, #cccccc 0%,#ffffff 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#cccccc,endcolorstr=#ffffff,gradientType=0);	
		cursor:pointer;
		list-style:none;
	}
	.index-table ul li a { display: block; padding: 0px; }
	.index-table ul li img { margin-top:8%; height:80%; border:none; }
	.index-table ul li a span { display: block; margin-top: 10px; text-align: center; font-size: 14px; }	
</style>   

  
</head>
<body style="padding-left:8px;padding-right:8px;">
<br/>


<?php
if(
isset($qyuser_tmp_model)
|| 
isset($xhuser_tmp_model) 
){
	
?>

<?php
//单位修改待审
$msg = "";
$msgbtn = "";

$msgstyle[] = "tips tips-small tips-info";
$msgstyle[] = "x-icon x-icon-small x-icon-info";
$msgstyle[] = "icon icon-white icon-info";

if(isset($qyuser_tmp_model)){
	if(count($qyuser_tmp_model)>0){
		if($qyuser_tmp_model["check_status"]=="1"){
			$msg = "单位资料的修改正在审核中";	
		}
		if($qyuser_tmp_model["check_status"]=="2"){
			//显示两天		
			if(
			($qyuser_tmp_model["check_time"]+(60*60*24*2))>time()){
				$msg = "单位资料审核通过，最新修改已经生效";	
			}				
		}
		if($qyuser_tmp_model["check_status"]=="3"){
			$msg = "单位资料审核不通过";	
			if($qyuser_tmp_model["check_content"]!=""){
				$msg .= "，审核意见：".$qyuser_tmp_model["check_content"];
			}
			$msgstyle = "";
			$msgstyle[] = "tips tips-small tips-warning";
			$msgstyle[] = "x-icon x-icon-small x-icon-error";
			$msgstyle[] = "icon icon-white icon-bell";					
		}
		$msgbtn = site_url("swj_admin/reg_edit_qy");		
	}
}
if(isset($xhuser_tmp_model)){
	if(count($xhuser_tmp_model)>0){
		if($xhuser_tmp_model["check_status"]=="1"){
			$msg = "单位资料的修改正在审核中";	
		}
		if($xhuser_tmp_model["check_status"]=="2"){
			//显示两天		
			if(
			($xhuser_tmp_model["check_time"]+(60*60*24*2))>time()){
				$msg = "单位资料审核通过，最新修改已经生效";			
			}				
		}
		if($xhuser_tmp_model["check_status"]=="3"){
			$msg = "单位资料审核不通过";	
			if($xhuser_tmp_model["check_content"]!=""){
				$msg .= "，审核意见：".$xhuser_tmp_model["check_content"];
			}
			$msgstyle = "";
			$msgstyle[] = "tips tips-small tips-warning";
			$msgstyle[] = "x-icon x-icon-small x-icon-error";
			$msgstyle[] = "icon icon-white icon-bell";			
		}
		$msgbtn = site_url("swj_admin/reg_edit_xh");		
	}
}
?>
<?php
if($msg!=""){
?>
	<div class="<?php echo $msgstyle[0];?>">
        <span class="<?php echo $msgstyle[1];?>">
        <i class="<?php echo $msgstyle[2];?>"></i></span>
        <div class="tips-content"><?php
        echo $msg;
		?>
        (<a
        class='page-action'
        href="#"
        data-id='dwzl'
        id="dwzl"
        title="单位资料"
        data-href='<?php echo $msgbtn;?>'
        >点击查看/修改</a>)</div>
    </div>
 
<?php
}
?>    
    
    
<?php
}
?>

<div class="index-table">
	<h3 class="tips" style="font-weight:bold;">快速入口</h3>
<ul>
<li title="修改注册资料" id="regedit">
<a href="javascript:void(0);">
<img src="/home/views/static/images/regedit.png" />
</a>
注册资料
</li>
<li title="修改单位资料" id="danwei">
<a href="javascript:void(0);">
<img src="/home/views/static/images/danwei.png" />
</a>
单位资料
</li>
<!--/views/static/Images/huodong.png-->
<?php
if($sess["usertype"]=="45063"){
?>
<li title="电商园区或基地" id="yuanqu_jidi">
<a href="javascript:void(0);" style="padding-top:8px;">
<img src="/home/views/static/images/yuanqu.png" />
</a>
园区基地
</li>
<?php
}
?>
<?php
if($sess["usertype"]=="45064"){
?>
<li title="活动备案" id="huodong_beian">
<a href="javascript:void(0);" style="padding-top:1px;">
<img src="/home/views/static/images/huodong.png" />
</a>
活动备案
</li>
<?php
}
?>
<li title="信息列表" id="xinxi">
<a href="javascript:void(0);">
<img src="/home/views/static/images/xinxi.png" />
</a>
信息列表
</li>
<li title="我的上报" id="shangbao">
<a href="javascript:void(0);">
<img src="/home/views/static/images/shuju.png" />
</a>
我的上报
</li>
<li title="文件下载" id="download">
<a href="javascript:void(0);">
<img src="/home/views/static/images/download.png" />
</a>
文件下载
</li>
<li title="项目申报详细教程">
<a href="/other/help/sb.htm" target="_blank">
<img src="/home/views/static/images/help.png" />
</a>
帮助
</li>
<li title="修改密码" id="mima">
<a href="javascript:void(0);">
<img src="/home/views/static/images/mima.png" />
</a>
修改密码
</li>
<li title="退出系统" id="logout">
<a href="<?php echo site_url("home/logout");?>" target="_top">
<img src="/home/views/static/images/logout.png" />
</a>
退出系统
</li>
</ul>

</div>
<div style="clear:both;"></div>
    <ul class="lk_ul">
  <li>
    <h2 class="fl">信息通知(<?php echo $pub_total?>条)</h2><h2 class="fr"><a class='page-action' data-id='xxtz' id="xxtz" title="信息列表"
    data-href='<?php echo site_url('swj_notice/index')?>' href="#">更多</a></h2>
    <div class="clear"></div>
    <table class="table table-bordered table-hover" style="width:100%;">  
    <tr>
        <th>标题</th>
        <th>日期</th>
    </tr>
    <?php foreach ($list_pub as $key => $value): ?>
        <tr>
            <td>
                <a class='page-action' data-id='xxtz_info' id="xxtz_info" title="<?php echo $value['title'];if($value['status']==0) echo '(未读)';else echo '(已读)';?>" 
                href="#" <?php if($value['status']==0) echo "style='font-weight:bold;'";else echo "style='font-weight:normal;'"; ?> data-href="<?php echo site_url('swj_notice/look').'?id='.$value['id'];?>">
                <?php echo msubstr(stripslashes($value['title']), 0, 28, mb_strlen(stripslashes($value['title']), 'utf8'))?>
                </a>
            </td>
            <td><?php echo date('Y-m-d H:i:s', $value['update_time'])?></td>
        </tr>
    <?php endforeach ?>
  
</table>
  </li>
</ul>
<script>
BUI.use('common/page');
	$('#regedit').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'regedit',
			search : '',
			reload : true
		  });
		}
	});
	$('#danwei').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'regedit2',
			search : '',
			reload : true
		  });
		}
	});	
	
	$('#mima').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'regedit',
			search : '',
			reload : true
		  });
		}
	});	
	$('#yuanqu_jidi').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'operation',
			search : '',
			reload : true
		  });
		}
	});	
	
	$('#xinxi').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'xxtz',
			search : '',
			reload : true
		  });
		}
	});			

	$('#shangbao').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'wdsb2',
			search : '',
			reload : true
		  });
		}
	});	
	
	$('#download').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'wjxz',
			search : '',
			reload : true
		  });
		}
	});

	$('#huodong_beian').click(function(e){  
		e.preventDefault();
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'zonghe',
			id : 'hdba',
			search : '',
			reload : true
		  });
		}
	});	
	
	
/*	
yuanqu_jidi		
xinxi
shangbao
download
*/
</script>
</body>
</html>

