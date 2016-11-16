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
   
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
   
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>内容</th>
        <th>发送人</th>
        <th>发送时间</th>
        <th width='60'>是否已发</th>
        <th width='200'>备注</th>        
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["sms"]."</td>";
            	echo "<td>".$v["username"];
            	echo "</td>";
            	echo "<td>".$v["sendtime"]."</td>";
            	echo "<td>".($v["issend"]?"已发":"<b>未发</b>")."</td>";
            	echo "<td>".$v["beizhu"]."</td>";            	                       	            	             	
            	echo "</tr>";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>


   <input type="hidden" name="selid" id="selid" value=""/>
<?php if($isdel){?>     
       <button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button>
     
       <button class="button button-danger" onclick="godel()">删除</button>
<?php }?>       
       



</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});


function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{						
		var ajax_url = "<?php echo site_url("sms/del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo site_url("sms/index");?>";
		parent.parent.my_confirm(
				"确认删除选中信息？",
				ajax_url,
				url);
	}	
}

</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>