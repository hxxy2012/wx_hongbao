<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   

    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/admin_application//views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/admin_application//views/static/Js/admin.js"></script>
<script type="text/javascript" src="/admin_application//views/static/assets/js/bui-min.js"></script>
<script type="text/javascript" src="/admin_application//views/static/assets/js/config-min.js"></script>   
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


    /**内容超出 出现滚动条 **/
    .bui-stdmod-body{
      overflow-x : hidden;
      overflow-y : auto;
    }
    .lk_ul{margin: 0; padding: 0;width: 100%;}
    .lk_ul li{float: left;width: 46%;margin:10px;}
    .fl{float: left;}
    .fr{float: right;}
    .clear{clear: both;}
	
	

	
	
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
<script>
BUI.use('common/page');
$(function(){
    //用户列表
    $('#useraudit').click(function(e){  
    e.preventDefault();
    if(top.topManager){
      //打开左侧菜单中配置过的页面
      top.topManager.openPage({
        moduleId:'224',
        id : '141',
        search : 'selcheck=-1',		
        reload : true
      });
    }
  });
  
  var hdxx_count = "<?php echo $beian['total'];?>";
     //信息备案审批
    $('#xxbasp').click(function(e){  
    e.preventDefault();
    if(top.topManager){
      //打开左侧菜单中配置过的页面
      top.topManager.openPage({
        moduleId:'224',
        id : (hdxx_count>0?'242':'241'),
        search : '',
        reload : true
      });
    }
  });
    //数据上报审核
	var shuju_shangbao = "<?php echo $sjsb['total']?>";
    $('#sjsbsh').click(function(e){  
    e.preventDefault();
    if(top.topManager){
      //打开左侧菜单中配置过的页面
      top.topManager.openPage({
        moduleId:'224',
        id : '246',
        search : (shuju_shangbao>0?'':"audit_status=10"),
        reload : true
      });
    }
  });
});
//audit_status
</script> 

<body>
<!-- <h1>
存放未审核信息
</h1> -->


<?php
if($qy_edit_count>0 || $xh_edit_count>0){
?>

 <div class="tips tips-small tips-info" style="margin-top:10px;margin-left:10px; margin-right:10px;">
 
        <span class="x-icon x-icon-small x-icon-info"><i class="icon icon-white icon-info"></i></span>
        <div class="tips-content">
<?php

if($qy_edit_count>0){
	echo "&nbsp;&nbsp;&nbsp;";
	echo "<a href=\"#\" data-id='editcheck1' class='page-action' data-href=\"".site_url("user/reg_edit_check_qy")."?selcheck=1\"
	title=\"审核企业资料\"
	>";
	echo "有";
	echo "<span style='color:red;'>";		
	echo $qy_edit_count;
	echo "</span>";
	echo "条企业资料修改审核";
	echo "</a>";
}
?>  

<?php
if($xh_edit_count>0){
	echo "&nbsp;&nbsp;&nbsp;";
	echo "<a href=\"#\" data-id='editcheck2' class='page-action' data-href=\"".site_url("user/reg_edit_check_xh")."?selcheck=1\"
	title=\"审核协会或机构资料\"
	>";	
	echo "有";
	echo "<span style='color:red;'>";			
	echo $xh_edit_count;
	echo "</span>";
	echo "条协会或机构资料修改审核";
	echo "</a>";
}
?>        
        </div>
      </div>
  
<?php
}
?> 

<div class="index-table">
<ul>
<li title="用户管理" id="a_user">
<?php
if(isset($user['total'])){
	if($user['total']>0){
?>
<div><?php echo $user['total'];?></div>
<?php
	}
}
?>
<a href="javascript:void(0);" >
<img src="/admin_application/views/static/Images/user.png"  />
</a>
用户管理
</li>
<li title="基础资料:电商园区或基地" onclick="$('#yuanqu_jidi').click();">
<?php
if(isset($yuanqu_jidi)){
	if(count($yuanqu_jidi)>0){
?>
<div><?php echo count($yuanqu_jidi);?></div>
<?php
	}
}
?>
<a href="javascript:void(0);" style="padding-top:8px;" >
<img src="/admin_application/views/static/Images/yuanqu.png" />
</a>
园区或基地
</li>
<li title="信息发布" id="xinxifabu">
<a href="javascript:void(0);">
<img src="/admin_application/views/static/Images/post.png" />
</a>
信息发布
</li>
<li title="项目管理" id="pmguanli">
<a href="javascript:void(0);">
<img src="/admin_application/views/static/Images/pro.png" />
</a>
项目管理
</li>
<li title="活动备案" onclick="$('#xxbasp').click();">
<?php
if(isset($beian['total'])){
	if($beian['total']>0){
?>
<div><?php echo $beian['total'];?></div>
<?php
	}
}
?>
<a href="javascript:void(0);">
<img src="/admin_application/views/static/Images/huodong.png" />
</a>
活动备案
</li>
<li title="数据上报" onclick="$('#sjsbsh').click();">
<?php
if(isset($sjsb['total'])){
	if($sjsb['total']>0){
?>
<div><?php echo $sjsb['total'];?></div>
<?php
	}
}
?>
<a href="javascript:void(0);">
<img src="/admin_application/views/static/Images/shuju.png" />
</a>
数据上报
</li>
<li title="管理员列表" id="adminlist">
<a href="javascript:void(0);">
<img src="/admin_application/views/static/Images/admin.png" />
</a>
管理员列表
</li>
<li title="数据库备份" id="dbbackup">
<a href="javascript:void(0);">
<img src="/admin_application/views/static/Images/backup.png" />
</a>
数据库备份
</li>
</ul>
</div>

<div style="clear:both;"></div>
<ul class="lk_ul">



  <li>
    <h2 class="fl">需审核的用户单位资料(<?php echo $user['total']?>条未审)</h2><h2 class="fr"><a  id="useraudit" title="用户列表" href="javascript:void(0);" >更多</a></h2>
    <div class="clear"></div>
    <table class="table table-bordered table-hover" style="width:100%;">
        <tr>
            <th>登录账号</th>
            <th width="124">提交日期</th>
            <th>提交单位</th>
        </tr>
        <?php 
	
		foreach ($user['list'] as $key => $value): ?>
            <tr>
                <td title="<?php echo $value['username'];?>">
  
                    
<?php echo "<a class='page-action' data-href='".site_url($value["usertype"]=="45063"?'user/edit_qy':'user/edit_xh')."?id=".$value["uid"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='userlist_".$value["uid"]."' id='open_edit_".$value["uid"]."' title=\"编辑".$value["username"]."的单位资料\">".msubstr(stripslashes($value['username']), 0, 20, mb_strlen(stripslashes($value['username']), 'utf8'))."</a>"                    ;
?>
                    
                </td>
                <td><?php echo date('Y-m-d H:i', strtotime($value['updatetime']))?></td>
                <td><?php echo msubstr(stripslashes($value['danwei']), 0, 31, mb_strlen(stripslashes($value['danwei']), 'utf8'))?></td>
            </tr>
        <?php

		endforeach ?>
      
    </table>
  </li>
  <li>
  
  
    <h2 class="fl">需审核的电商园区或基地(<?php echo count($yuanqu_jidi);?>条未审)</h2><h2 class="fr"><a id="yuanqu_jidi" title="查看未审园区或基地"  href="javascript:void(0);">更多</a></h2>
    <div class="clear"></div>
    <table class="table table-bordered table-hover" style="width:100%;">
        <tr>
            <th>名称</th>
            <th width="124">提交日期</th>
            <th>提交单位</th>
        </tr>
        <?php 
		$i = 0;
		foreach ($yuanqu_jidi as $key => $value): ?>
            <tr>
                <td title="<?php echo $value['name'];?>">
  
                    
<?php echo "<a class='page-action' data-href='".site_url("swj_dsyq/check")."?id=".$value["id"]."' href=\"\" data-id='dsyq_jidi_details_".$value["id"]."' id='open_edit_".$value["id"]."' title=\"编辑".$value["name"]."的园区或基地\">".msubstr(stripslashes($value['name']), 0, 20, mb_strlen(stripslashes($value['name']), 'utf8'))."</a>"                    ;
?>
                    
                </td>
                <td><?php echo date('Y-m-d H:i', strtotime($value['updatetime']))?></td>
                <td><?php echo msubstr(stripslashes($value['danwei']), 0, 31, mb_strlen(stripslashes($value['danwei']), 'utf8'))?></td>
            </tr>
        <?php
		$i++;
		if($i>=10){
			break;	
		}
		endforeach ?>
      
    </table>  
  
  </li>
  <li>
    <h2 class="fl">需审核的活动备案(<?php echo $beian['total']?>条未审)</h2><h2 class="fr"><a href="#" id="xxbasp">更多</a></h2>
    <div class="clear"></div>
    <table class="table table-bordered table-hover" style="width:100%;">
        <tr>
            <th>名称</th>
            <th width="124">提交日期</th>
            <th>提交单位</th>
        </tr>
        <?php foreach ($beian['list'] as $key => $value): ?>
            <tr>
                <td title="<?php echo $value['actname'];?>">
                    <a href="#" class="page-tab page-action" data-id='hdbeian_check_<?php echo $value["id"];?>' title="活动备案编号<?php echo $value["id"];?>"  data-href="<?php echo site_url("swj_xxbasp/check");?>?id=<?php echo $value["id"];?>">
                    <?php echo msubstr(stripslashes($value['actname']), 0, 20, mb_strlen(stripslashes($value['actname']), 'utf8'))?>
                    </a>
                </td>
                <td><?php echo date("Y-m-d H:i",strtotime($value['updatetime']));?></td>
                <td><?php echo msubstr(stripslashes($value['company']), 0, 31, mb_strlen(stripslashes($value['company']), 'utf8'))?></td>
            </tr>
        <?php endforeach ?>
      
    </table>
  </li>
  <li>
    <h2 class="fl">需审核的数据上报(<?php echo $sjsb['total']?>条未审)</h2><h2 class="fr"><a id="sjsbsh" href="javascript:void(0);">更多</a></h2>
    <div class="clear"></div>
    <table class="table table-bordered table-hover" style="width:100%;">
        <tr>
            <th>名称</th>
            <th width="124">提交日期</th>
            <th>提交单位</th>
        </tr>
        <?php foreach ($sjsb['list'] as $key => $value): ?>
            <tr>
                <td title="<?php echo $value['name'];?>">
                    <a href="#" data-id='shujushangbao_check_<?php echo $value["id"];?>' title="数据上报编号<?php echo $value["id"];?>" data-href="<?php echo site_url("swj_sjsbcx/check");?>?id=<?php echo $value["id"];?>" class="page-tab page-action"  >
                    <?php echo msubstr(stripslashes($value['name']), 0, 20, mb_strlen(stripslashes($value['name']), 'utf8'))?>
                    </a>
                </td>
                <td><?php echo date("Y-m-d H:i",strtotime($value['updatetime']));?></td>
                <td><?php echo msubstr(stripslashes($value['company']), 0, 31, mb_strlen(stripslashes($value['company']), 'utf8'))?></td>
            </tr>
        <?php endforeach ?>
      
    </table>
  </li>
  
  
</ul>
<div class="clear"></div>

<span id="a_user"></span>


<script type="text/javascript">   
	//优先查看未审,并显示提示条数
	var a_user_check = "<?php echo $user['total'];?>";    
	$('#a_user').click(function(e){  
	e.preventDefault();
	if(top.topManager){
	  //打开左侧菜单中配置过的页面
	  top.topManager.openPage({
        moduleId:'224',
        id : '141',
		search : (a_user_check>0?'selcheck=-1':''),
		reload : true
	  });
	}
	});
	
	var a_yuanqu_jidi_check = "<?php echo count($yuanqu_jidi);?>";    
	$('#yuanqu_jidi').click(function(e){  
	e.preventDefault();
	if(top.topManager){
	  //打开左侧菜单中配置过的页面
	  top.topManager.openPage({
        moduleId:'224',
        id : '227',
		search : (a_yuanqu_jidi_check>0?'audit=10':''),
		reload : true
	  });
	}
	});	

	$('#xinxifabu').click(function(e){  
	e.preventDefault();
	if(top.topManager){
	  //打开左侧菜单中配置过的页面
	  top.topManager.openPage({
        moduleId:'224',
        id : '232',
		search :'',
		reload : true
	  });
	}
	});	
	
	$('#pmguanli').click(function(e){  
	e.preventDefault();
	if(top.topManager){
	  //打开左侧菜单中配置过的页面
	  top.topManager.openPage({
        moduleId:'224',
        id : '237',
		search :'',
		reload : true
	  });
	}
	});		
	
	$('#adminlist').click(function(e){  
	e.preventDefault();
	if(top.topManager){
	  //打开左侧菜单中配置过的页面
	  top.topManager.openPage({
        moduleId:'73',
        id : '59',
		search :'',
		reload : true
	  });
	}
	});	
	
	$('#dbbackup').click(function(e){  
	e.preventDefault();
	if(top.topManager){
	  //打开左侧菜单中配置过的页面
	  top.topManager.openPage({
        moduleId:'73',
        id : '126',
		search :'',
		reload : true
	  });
	}
	});				
	
</script>



</body>
</html>