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
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
  党员资料库用于识别注册者是否本区党员，不提供修改，如果信息有误，请删除党员记录，重新导入。
另外，已注册的党员不能删除。
</div>
<form method="get" >  
<table>
<tr>
<td>     
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="姓名" 
    value="<?php echo $search_val['username'];?>">
</td>
<td>        
    <input type="text"  name="idcard" id="idcard"
    class="abc input-default" 
    placeholder="身份证号" 
    value="<?php echo $search_val['idcard'];?>">         
</td>
<td>
<select name="dzz_id" style="width:90px;" id="dzz_id"
onchange="GetDzz(this.value)"
 >
<option value="">选党组织</option>
<?php foreach($dzz as $v)
{
	?>
<option value="<?php echo $v["id"];?>" 
<?php echo $dzzid==$v["id"]?"selected":"";?>
 >
<?php echo $v["title"];?>
</option>	
<?php }
?>	
</select>
</td>
<td>

<select name="dzz_id2" style="width:90px;<?php echo count($dzz2)>0?"":"display:none;"; ?>" id="dzz_id2"     >
<option value="">选支部</option>
<?php 
foreach($dzz2 as $v){
	echo "<option value='".$v["id"]."' 
			".($v["id"]==$dzzid2?"selected":"")."
			>".$v["title"]."</option>";
}
?>
</select>     
</td> 
<td>
    <input style="display: none;" type="text"  name="dzz_title" id="dzz_title"
    class="abc input-default" 
    placeholder="党组织名" 
    value="<?php echo $search_val['dzz_title'];?>">   	
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
</td>
<td>    
    <a  class="btn btn-success" id="addnew" href="<?php echo site_url("user/common_user_dangyuan_import");?>">按支部ID导入党员</a>
</td>
<td>    
<?php 
//只能超管才可以用
if(is_super_admin()){
?>    
    <a  class="btn btn-success" id="addnew2" href="<?php echo site_url("user/common_user_dangyuan_import_zhibu");?>">按支部名称导入党员</a>
<?php 
}
?>    
</td>
</tr></table>   
</form>    
</div>

<table class="table table-bordered table-hover m10">
    <thead>
    <tr>
        <th width='80'>资料库编号</th>        
        <th>党组织</th>       
        <th>注册账号</th>
        <th>姓名</th>
        <th>身份证号</th>       
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["zzb_title"];
            	echo ($v["zzb_title2"]!=""?("<span style='font-weight:bold;font-size:12px;color:#cccccc;'>-></span>".$v["zzb_title2"]):"");
            	echo "</td>";
            	echo "<td>".($v["username"]==""?"未注册":$v["username"])."</td>";
            	echo "<td>".$v["realname"];            	
            	echo "<td>".$v["idcard"]."</td>";                        	            	            
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
      
       <button class="button button-danger" onclick="godel()">删除</button>
     
       
  
  


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
		var ajax_url = "<?php echo site_url("user/common_user_dangyuan_del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo site_url("user/common_user_dangyuan");?>";
		parent.parent.my_confirm(
				"确认删除选中党员资料？",
				ajax_url,
				url);
	}	
}
function GetDzz(pid){
	var url = "/admin.php/user/getdzzjson.shtml?pid="+pid;
	$.getJSON(
		url,
		function(data) {				
	        var tt="<option value=''>选支部</option>";
	        var isdata = false;
	        for(i=0;i<data.length;i++){
		        isdata = true;
		        
	        	tt += "<option value='"+data[i].id+"'>"+data[i].title+"</option>";
	        }	        
	        $("#dzz_id2").html(tt);
	        //alert(isdata);
	        if(isdata && pid>0){
	        	//$('#dzz_id2').attr("required","true");
	        	$('#dzz_id2').css("display","block");	
	        }
	        else{
	        	//$('#dzz_id2').removeAttr("required");
	        	$('#dzz_id2').css("display","none");	
	        }
		}	
		);	
}
if($("#dzz_id").val()>0 && $("#dzz_id2").val()==0){
	GetDzz($("#dzz_id").val());
}
</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>