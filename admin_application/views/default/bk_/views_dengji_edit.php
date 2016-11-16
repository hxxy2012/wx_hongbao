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
<input type="hidden" name="id" id="id" value="<?php echo $model["id"];?>" />
<input type="hidden" name="backurl" value="<?php echo $backurl;?>"/>


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">等级名：</td>
        <td>

<input type="text" style="width:200px;" 
name="dengji"
id="dengji" 
value="<?php echo $model["dengji"];?>" required="true" 
valType="check"
remoteUrl="/admin.php/zzb_dengji/chktitle.shtml?id=<?php echo $model["id"];?>"

        
        />        
*  
        </td>
    </tr>
 

   <tr>
        <td class="tableleft">分数区间：</td>
        <td>

<input type="text" style="width:200px;" name="fenshu_start"
value="<?php echo $model["fenshu_start"];?>" id="fenshu_start"
required="true" 
valType="int"  
placeholder="分数开始值"       
        />
至        
<input type="text" style="width:200px;"
name="fenshu_end"
id="fenshu_end"
value="<?php echo $model["fenshu_end"];?>" 
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
<option value="1" <?php echo $model["count"]==1?"selected":"";?>>1</option>
<option value="2" <?php echo $model["count"]==2?"selected":"";?>>2</option>
<option value="3" <?php echo $model["count"]==3?"selected":"";?>>3</option>
<option value="4" <?php echo $model["count"]==4?"selected":"";?>>4</option>
<option value="5" <?php echo $model["count"]==5?"selected":"";?>>5</option>
</select>

<input type="radio" name="pngpath" value="admin_application/views/static/Images/dengji/star.png" <?php echo $model["pngpath"]=="admin_application/views/static/Images/dengji/star.png"?"checked='checked'":"";?> /><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/star.png"/>
<input type="radio" name="pngpath" value="admin_application/views/static/Images/dengji/zuan.png" <?php echo $model["pngpath"]=="admin_application/views/static/Images/dengji/zuan.png"?"checked='checked'":"";?>/><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/zuan.png"/>
<input type="radio" name="pngpath" value="admin_application/views/static/Images/dengji/guan2.png" <?php echo $model["pngpath"]=="admin_application/views/static/Images/dengji/guan2.png"?"checked='checked'":"";?>/><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/guan2.png"/>
<input type="radio" name="pngpath" value="admin_application/views/static/Images/dengji/guan.png" <?php echo $model["pngpath"]=="admin_application/views/static/Images/dengji/guan.png"?"checked='checked'":"";?>/><img src="<?php echo base_url();?>/admin_application/views/static/Images/dengji/guan.png"/>
        </td>
    </tr> 
  


    <tr>
        <td class="tableleft"></td>
        <td>
<button type="submit" class="btn btn-primary"  id="btnSave">保存</button>            
<button type="button" class="btn btn-primary" onclick="top.topManager.closePage();" >关闭</button>
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

