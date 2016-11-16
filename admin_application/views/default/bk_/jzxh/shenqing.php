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
        
</head>
<body class="definewidth">
<script>
BUI.use('common/page');
</script>   
<div class="form-inline definewidth m20" >
<form method="get" >       
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="联系人/手机号" 
    value="<?php echo $search_val['username'];?>">    


	<select name="check_status" id="check_status">
		<option value="0" <?php echo  $search_val["check_status"]==""?"selected":"";?>>审核状态</option>
		<option value="1" <?php echo  $search_val["check_status"]==1?"selected":"";?>>通过</option>
		<option value="2" <?php echo  $search_val["check_status"]==2?"selected":"";?>>不通过</option>
	</select>
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
  
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th width='60'>状态</th>
        <th width='100'>联系人</th>
        <th>联系电话</th>
         <th width='150'>备注</th>
        <th>申请账号</th>
        <th>公司名称</th>               
		<th width='132'>申请时间</th>		        
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>";
            	echo $v["check_status"]=="0"?"<span style='color:#cccccc;'>未处理</span>":"";
            	echo $v["check_status"]=="1"?"<span style='color:blue'>已通过</span>":"";
            	echo $v["check_status"]=="2"?"<span style='color:red'>不通过</span>":"";
   				echo "</td>";
            	echo "<td>".$v["linkman"];
            	
            	echo "</td>";            	
            	echo "<td>".$v["tel"]."</td>";            	
            	echo "<td>".$v["beizhu"]."</td>";
            	echo "<td><a class='page-action' data-href='".site_url('user/edit')."?id=".$v["userid"]."' href=\"#\" data-id='userlist_".$v["userid"]."' id='open_edit_".$v["userid"]."' title=\"编辑".$v["username"]."的基本信息\">".$v["username"]."</a></td>";            	
            	echo "<td>".$v["companyname"]."</td>";
            	echo "<td>".date("Y-m-d H:i",$v["create_time"])."</td>";                        	            	             
            	echo "</tr>";
            	echo "\n";
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
   
       <button class="button button-danger" onclick="godel()">删除</button>
       
<button class="btn btn-info" onclick="goset_check(1)">设为通过</button>
<button class="btn btn-warning" onclick="goset_check(2)">设为不通过</button>
       
  
<div class="alert alert-warning alert-dismissable">
<strong>温馨提示</strong>
设为通过：用户类型同时设为“协会用户”
</div>


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});

function goedit(uid){
	alert($("#open_edit_"+uid));
	//window.location.href="<?php echo site_url("user/edit");?>?";
	$("#open_edit_"+uid).click();		
}

function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{						
		var ajax_url = "<?php echo site_url("user/shenqing_del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/user/shenqing.shtml";
		parent.parent.my_confirm(
				"确认删除选中？",
				ajax_url,
				url);
	}	
}
function goset_check(yesorno){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{						
		var ajax_url = "<?php echo site_url("user/shenqing_set_check");?>?check="+yesorno+"&idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/user/shenqing.shtml";
		parent.parent.my_confirm(
				"确认操作？",
				ajax_url,
				url);
	}	
}

</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>