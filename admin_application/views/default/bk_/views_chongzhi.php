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
    placeholder="用户名/手机号" 
    value="<?php echo $search_val['username'];?>">    
<select name="brandid" id="brandid" >
<option value="">品牌</option>
<?php
foreach($brandlist as $v){
	echo '<option value="'.$v["guid"].'"
	
	'.($brandid==$v["guid"]?" selected ":"").'
	>'.$v['title'].'</option>';
}
?>
</select>
 
<select name="sheng" id="sheng" onChange="getshi();">
<option>省份</option>
<?php
foreach($sheng_list as $v){
	echo "<option value='".$v["id"]."'
	".($search_val['sheng']==$v["id"]?" selected ":"")."
	>".$v["name"]."</option>";
}
?>
</select>
 
<select name="shi" id="shi">
<option>市</option>
<?php
foreach($shi_list as $v){
	echo "<option value='".$v["id"]."'
	".($search_val['shi']==$v["id"]?" selected ":"")."
	>".$v["name"]."</option>";
}
?>
</select>


<select name="usertype" >
<option selected>会员类型</option>
<?php
foreach($usertype_list as $v){
	echo "<option value='".$v["id"]."'
	 ".($search_val['usertype']==$v["id"]?"selected":"")."
	>".$v["name"]."</option>";
}
?>

</select>

    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
    
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='120'>充值时间</th>
        <th width='50'>金额</th>
        <th>用户名称</th>
        <th>姓名</th>
        <th>电话</th>
        <th width='60'>是否成功</th>
		<th width='200'>备注</th>		
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr>";
            	echo "<td>".$v["create_time"]."</td>";
            	echo "<td>";
            	echo $v["amount"];
   				echo "</td>";
            	echo "<td>".$v["username"];
            	echo "</td>";
            	echo "<td>".$v["realname"]."</td>";
            	echo "<td>".$v["tel"]."</td>";
            	echo "<td>".($v["succeed"]=="1"?"成功":"不成功")."</td>";
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
       <!--button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button-->
    
       
  


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
            
</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>