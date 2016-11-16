<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户管理</title>
    <meta charset="UTF-8">
   	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>   
 
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
<script>
BUI.use('common/page');
</script>        
</head>
<body class="definewidth">
<div class="form-inline definewidth m20" >
<form method="get" >       
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="用户名" 
    value="<?php echo $search_val['username'];?>">    
	<select name="condition" id="condition">
		<option value="1" <?php echo $search_val['condition']=="1"?"selected":"";?>>模糊搜索</option>
		<option value="2" <?php echo $search_val['condition']=="2"?"selected":"";?>>精确搜索</option>
	</select>	
    <input type="text"  name="dzz_title" id="dzz_title"
    class="abc input-default" 
    placeholder="党组织名" 
    value="<?php echo $search_val['dzz_title'];?>" <?php if(!is_super_admin()){ ?> style="display:none;"<?php } ?>>   	
	<select name="status" id="status">
		<option value="-1" <?php echo  $search_val["checkstatus"]=="-1"?"selected":"";?>>审核状态</option>
		<option value="0" <?php echo  $search_val["checkstatus"]==0?"selected":"";?>>未审</option>
		<option value="99" <?php echo  $search_val["checkstatus"]==99?"selected":"";?>>不通过</option>
	</select>
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
   
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>党组织</th>
        <th>用户名称</th>
        <th>姓名</th>
        <th>电话</th>
        <th width='60'>审核状态</th>
		<th width='132'>注册日期</th>
		<th width='132'>不通过原因</th>
        <th width='80'>操作</th>
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["uid"]."</td>";
            	echo "<td>".$v["zzb_title"]."</td>";
            	echo "<td>".$v["username"];
            	echo ($v["sys_common_system_userid"]>0?"<span title='".$v["zzb_title"]."二级管理员' class='icon-user'></span>":"")."</td>";
            	echo "<td>".$v["realname"]."</td>";
            	echo "<td>".$v["tel"]."</td>";
            	echo "<td>";
            			
           			
switch($v["checkstatus"]){
	case "0":
		echo "未审";
		break;
	case "99":
		echo "不通过";
		break;
	default:
		echo "-";
		break;
            	}            			            	            	
            	echo "</td>";
            	echo "<td>".$v["regdate"]."</td>";
            	echo "<td>".$v["checkno_yuanyin"]."</td>";
            	echo "<td>";            	
				echo "<a class=' icon-share-alt' title='审核注册用户' onclick=\"showzz('".$v["uid"]."')\" href='javascript:void(0);'></a>";            	
				echo "</td>";                          	            	             	
            	echo "</tr>";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>


   <input type="hidden" name="selid" id="selid" value=""/>
       <button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button>
<?php if($isdel){?>       
       <button class="button button-danger" onclick="godel()">删除</button>
<?php }?>       
       
  
  


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});
function showzz(uid){		
	parent.showpage("/admin.php/user/regview.shtml?uid="+uid,"审核用户",500,420);
}
function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{						
		var ajax_url = "/admin.php/user/del.shtml?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>admin.php/user/newreg.shtml";
		parent.parent.my_confirm(
				"确认删除选中用户？",
				ajax_url,
				url);
	}	
}


</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>