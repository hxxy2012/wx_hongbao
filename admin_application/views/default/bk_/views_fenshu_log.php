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
<body class="definewidth" style="padding-top:10px;">

<div class="form-inline definewidth m20" >
<form method="get" >       
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="姓名或身份证" 
    value="<?php echo $search_val['username'];?>">    

       	
	<select name="zzb_fenshu_leibie_id" id="zzb_fenshu_leibie_id">
	<option value="">分数类别</option>
<?php 
foreach($fenshu_leibie as $v){
	echo "<option value='".$v["id"]."'";
	echo $v["id"]==$search_val['zzb_fenshu_leibie_id']?"selected":"";		
	echo ">".($v["typename"])."</option>";
}
?>		
	</select>
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
<?php 
if(isset($userfenshu)){	
	echo "分数合计：".$userfenshu;
}
?>    
</form>    
</div>


<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>姓名</th>
        <th>身份证</th>
        <th>分数类别</th>
        <th>得分</th>
        <th>备注</th>
        <th width="150">创建时间</th> 
<?php 
if($isedit){               
?>
        <th></th>
<?php 
}
?>
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["realname"]."</td>";
            	echo "<td>".$v["idcard"]."</td>";
            	echo "<td>".$v["typename"];
            	echo "</td>";
            	echo "<td>".$v["fenshu"]."</td>";
            	echo "<td>".$v["beizhu"]."</td>";
            	echo "<td>".date("Y-m-d H:i",$v["create_date"])."</td>";                	
            	if($isedit){            		            	        	
            	echo "<td>";
            	echo "<a class='page-action icon-edit' data-href='".site_url('fenshu/edit')."?id=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='".__CLASS__.$v["id"]."' id='open_edit_".$v["id"]."' title=\"编辑分数编号".$v["id"]."\"></a>";
    			echo "</td>";
            	}
            	echo "</tr>";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>


<?php if($isdel){?> 
   <input type="hidden" name="selid" id="selid" value=""/>
       <button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button>
      
       <button class="button button-danger" onclick="godel()">删除</button>
<?php }?>  

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
					
			var ajax_url = "/admin.php/fenshu/del.shtml?idlist="+$("#selid").val();
			//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
			var url = "<?php echo base_url();?>admin.php/fenshu/fslog.shtml";
			parent.parent.my_confirm(
					"确认删除选中分数？",
					ajax_url,
					url);
		
	}	
}

</script>
       
  
<br/>
<br/>


<?php 
if(is_super_admin()){
?>  
<br/>
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
只能超管才能修改和删除分数。
</div>  
<?php 
}
?>  


</body>
</html>




<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>