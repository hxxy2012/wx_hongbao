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
var seltel = "";

function GetNewTel(){
	seltel = parent.$("#selid").val();
}
GetNewTel();
function chktel(tel){
	GetNewTel();
	//alert(","+seltel+",");
	if(tel!=""){
		if((","+seltel+",").indexOf(","+tel+",")>=0){
			return true;
		}
		else{
			return false;
		}
	}
}
function tongji(){
	GetNewTel();	
	if(seltel!="")
	{
		if(seltel.split(",").length>0){
			$("#tongji_span").html("选中："+seltel.split(",").length+"条，<span style='cursor:pointer' onclick='cleartel()'>清空</span>，<span style='cursor:pointer' onclick='selall()'>全选</span>");
		}
		else{
			$("#tongji_span").html("");
		}
	}
	else{
		$("#tongji_span").html("<span style='cursor:pointer' onclick='selall()'>全选</span>");
	}
	
}
function cleartel(){
	//$("input[name='telbox']").checked=false;
	 $("input[name='telbox']").removeAttr("checked");
	parent.$("#selid").val("");
	tongji();
}

function selall(){
	$("input[name='telbox']").attr("checked","checked");
	$("input[name='telbox']").each(function(){
				
	    addtel($(this).attr('value'),document.getElementById($(this).attr("id")));
		});	
	tongji();	
}

function seltr(id){
	//document.getElementById("tel"+id).checked = !document.getElementById("tel"+id).checked;
	//addtel(document.getElementById("tel"+id).value,document.getElementById("tel"+id));
}

function addtel(tel,obj){

	GetNewTel();		
	if(obj.checked)
	{
		if((","+seltel+",").indexOf(','+tel+',')>=0){
			
		}
		else{
			if(seltel==""){
				seltel = tel;
			}
			else{
				seltel += ","+tel;
			}
			parent.$("#selid").val(seltel);
		}
	}
	else{
		var arr = seltel.split(",");
		var tmp = "";
		for(i=0;i<arr.length;i++){
			if(arr[i]!=tel){
				if(tmp==""){
					tmp  = arr[i];
				}
				else{	
					tmp += ","+arr[i];
				}
			}			
		}
		seltel = tmp;
		parent.$("#selid").val(seltel);
	}	
	tongji();
}





</script>    
<script>
BUI.use('common/page');
</script>        
</head>
<body class="definewidth" style="padding: 0px;">


<div class="form-inline definewidth m20" style="padding: 0px;" >
<form method="get" >  
<table><tr>
<td>

     
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="身份证" style="width:50px;"
    value="<?php echo $search_val['username'];?>">    
<input type="hidden" name="condition" value="1"/>
</td>
<td>
    <input type="text"  name="dzz_title" id="dzz_title"
    class="abc input-default" 
    placeholder="组织或支部名" style="display:none;width:80px;"
    value="<?php echo $search_val['dzz_title'];?>" <?php if(!is_super_admin()){ ?> style="display:none;"<?php } ?>>
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

	<select name="shenfen" style="width:60px;" id="shenfen">
		<option value="" <?php echo  $search_val["shenfen"]==""?"selected":"";?>>身份</option>
		<option value="45064" <?php echo  $search_val["shenfen"]=="45064"?"selected":"";?>>本区党员</option>
		<option value="45063" <?php echo  $search_val["shenfen"]=="45063"?"selected":"";?>>非本区党员</option>
	</select>
    
	<select name="status" style="width:60px;" id="status">
		<option value="-1" <?php echo  $search_val["status"]==""?"selected":"";?>>状态</option>
		<option value="1" <?php echo  $search_val["status"]==1?"selected":"";?>>开启</option>
		<option value="0" <?php echo  $search_val["status"]==0?"selected":"";?>>禁止</option>
	</select>
</td>
<td>	
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;
    
    <span id="tongji_span"></span>
</td>     
</tr>
</table>  
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
       
        <th>党组织</th>
        
        <th>姓名</th>
        <th>电话</th>       
		<th width='60'>本区党员</th>
		<th width='30'>选中</th>		        
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr(".$v["uid"].")'>";
            	//echo "<td>".$v["uid"]."</td>";
            	echo "<td>".$v["zzb_title"]."</td>";            	            	
            	echo "<td title='用户名：".$v["username"]."'>".$v["realname"];
            	echo ($v["sys_common_system_userid"]>0?"<span title='".$v["zzb_title"]."二级管理员' class='icon-user'></span>":"")."</td>";
            	echo "<td>".$v["tel"]."</td>";
            	//echo "<td>".$v["status"]."</td>";
            	echo "<td>".($v["usertype"]==45064?"是":"否")."</td>";
            	echo "<td><input type='checkbox' name='telbox' id='tel".$v["uid"]."' value='".$v["tel"]."' onclick=\"addtel('".$v["tel"]."',this);\">";
            	echo "<script>
    		if(chktel('".$v["tel"]."')){
    			document.getElementById('tel".$v["uid"]."').checked=true;
    		}    		
    		</script>";
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

   
       

<script>
tongji();
function GetDzz(pid){
	var url = "<?php echo site_url("user/getdzzjson");?>?pid="+pid;
	$.getJSON(
		url,
		function(data) {				
	        var tt="<option value=''>请支部</option>";
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

</body>
</html>




<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>