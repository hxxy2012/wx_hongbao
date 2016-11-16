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
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/DatePicker/WdatePicker.js"></script>    
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

<input type="text" name="search_yearmonth" 
id="search_yearmonth" 
class="Wdate" 
placeholder="做题月份" 
value="<?php echo $search_val['search_yearmonth'];?>" onclick="WdatePicker({dateFmt: 'yyyy-MM', isShowClear: true, readOnly: false})"  style="width:80px" 
                               
errMsg="做题月份" />    
    
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
   
</form>  
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
    	<th width='50'>名次</th>       
        <th>党组织</th>
        <th>用户名称</th>
        <th>姓名</th>
        <th>电话</th>
        <th width='120'>做题数量</th>		
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr>";
            	echo "<td>".$v["pm"]."</td>";            	
            	echo "<td>".$v["dzz_title"]."</td>";
            	echo "<td>".$v["username"];
            	echo "</td>";
            	echo "<td>".$v["realname"]."</td>";
            	echo "<td>".$v["tel"]."</td>";
            	echo "<td>".$v["timu_count"]."</td>";            	            					      	            	             
            	echo "</tr>";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>



    
       
  



</body>
</html>



<script>
$(function () {
	
});

<?php 
		if(false){
		?>  
		 
	 
function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{	
		var ajax_url = "/admin.php/zzb_jjfz_dati_history/del.shtml?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>admin.php/zzb_jjfz_dati_history/index.shtml";
		parent.parent.my_confirm(
				"一旦删除无法恢复,确认操作？",
				ajax_url,
				url);
	}	
}
<?php 
		}
		?> 
</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>