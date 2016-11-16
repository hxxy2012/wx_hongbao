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
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
	<link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
    
    
    
    
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

<form action="<?php echo site_url("user/common_user_dangyuan_import");?>" 
onsubmit="return postform()" method="post" enctype="multipart/form-data"  name="myform" id="myform">

<br/>
<?php 
if($errmsg!=""){
echo "<div style='color:red;text-align:center;'>$errmsg</div>";	
}
?>
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
姓名、身份证号已存在于党员资料库中的记录，自动跳过。
</div>


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">EXCEL模板下载：</td>
        <td>
<a href="/data/import_dy.xls" target="_blank">点击下载</a>        
        </td>
    </tr>
    <tr>
        <td class="tableleft">选择EXCEL：</td>
        <td>
<input type="file" required="true" name="fujian" id="fujian" />   
<span style="color:#666666">格式：xls文件，5M以内</span>     
        </td>
    </tr>    
    <tr>
        <td class="tableleft">选择党组织:</td>
        <td>
<table><tr>

<td  style="padding:0px;border:none;">
<select name="dzz_id" id="dzz_id"
onchange="GetDzz(this.value)"
required="true"  >
<option value="">请选择</option>
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
</td><td style="padding-left:10px;padding-top:0px;border:none;">
<select name="dzz_id2" id="dzz_id2" <?php echo count($dzz2)>0?"required=\"true\"":"style=\"display:none;\""; ?>    >
<option value="">请选择</option>
<?php 
foreach($dzz2 as $v){
	echo "<option value='".$v["id"]."' 
			".($v["id"]==$dzzid2?"selected":"")."
			>".$v["title"]."</option>";
}
?>
</select>        
        </td></tr></table>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
            <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("user/common_user_dangyuan");?>">返回列表</a>
        </td>
    </tr>
</table>
</form>
</body>
</html>
<script type="text/javascript">
var is_idcard = false;
function postform(){
	if($("#myform").Valid()) {
		return true;
	}
	else{	
		return false;
	}
}



function GetDzz(pid){
	var url = "/admin.php/user/getdzzjson.shtml?pid="+pid;
	$.getJSON(
		url,
		function(data) {				
	        var tt="<option value=''>请选择</option>";
	        var isdata = false;
	        for(i=0;i<data.length;i++){
		        isdata = true;
		        
	        	tt += "<option value='"+data[i].id+"'>"+data[i].title+"</option>";
	        }	        
	        $("#dzz_id2").html(tt);
	        //alert(isdata);
	        if(isdata && pid>0){
	        	$('#dzz_id2').attr("required","true");
	        	$('#dzz_id2').css("display","block");	
	        }
	        else{
	        	$('#dzz_id2').removeAttr("required");
	        	$('#dzz_id2').css("display","none");	
	        }
		}	
		);	
}
</script>

