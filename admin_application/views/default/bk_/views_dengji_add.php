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
	
	.bui-dialog .bui-stdmod-body{
		padding:0px;
	}
    </style>
</head>
<body class="definewidth">


<form action="" onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="selid" id="selid" value="" />


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">等级名：</td>
        <td>

<input type="text" style="width:200px;" 
name="dengji"
id="dengji" 
value="" required="true" 
valType="check"
remoteUrl="/admin.php/zzb_dengji/chktitle.shtml?id=0"

        
        />        
*  
        </td>
    </tr>
 

   <tr>
        <td class="tableleft">分数区间：</td>
        <td>

<input type="text" style="width:200px;" name="fenshu_start"
value="" id="fenshu_start"
required="true" 
valType="int"  
placeholder="分数开始值"       
        />
至        
<input type="text" style="width:200px;"
name="fenshu_end"
id="fenshu_end"
value="" 
required="true" 
valType="int" 
placeholder="分数结束值"       
        />                
        *         
        
        </td>
    </tr>
 
   <tr>
        <td class="tableleft">图标：</td>
        <td>
<select name="count" required="true">
<option value="">数量</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
</select>
<input type="radio" name="pngpath" checked="checked" value="admin_application/views/static/Images/dengji/star.png" /><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/star.png"/>
<input type="radio" name="pngpath" value="admin_application/views/static/Images/dengji/zuan.png" /><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/zuan.png"/>
<input type="radio" name="pngpath" value="admin_application/views/static/Images/dengji/guan2.png" /><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/guan2.png"/>
<input type="radio" name="pngpath" value="admin_application/views/static/Images/dengji/guan.png" /><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/guan.png"/>
        </td>
    </tr> 
  


    <tr>
        <td class="tableleft"></td>
        <td>
<button type="submit" class="btn btn-primary"  id="btnSave">保存</button>            
<button type="button" class="btn btn-primary" onclick="window.location.href='<?php echo $backurl;?>';" >返回列表</button>
        </td>
    </tr>
</table>

</form>
</body>
</html>
<script type="text/javascript">
function postform(){
	//
	var fenshu_start = $("#fenshu_start").val();
	var fenshu_end = $("#fenshu_end").val();
	if(fenshu_start<0){
		
		parent.parent.tip_show('开始分数不能少于零。',2,3000);
		$("#fenshu_start").focus();
		return false;	
	}
	if(fenshu_end<=0){
		parent.parent.tip_show('结束分数不能少于零。',2,3000);
		$("#fenshu_end").focus();
		return false;		
	}
	
	if(parseInt(fenshu_start)>=parseInt(fenshu_end)){
		parent.parent.tip_show('开始分数必须少于结束分数。',2,3000);
		$("#fenshu_start").focus();				
		return false;
	}
	
	if($("#myform").Valid()) {
		return true;
	}
	else{	
		return false;
	}
}



var errmsg = "<?php echo !isset($err)?"":$err;?>";
if(errmsg!=""){
	parent.tip_show(errmsg,2,3000);	
}
</script>

