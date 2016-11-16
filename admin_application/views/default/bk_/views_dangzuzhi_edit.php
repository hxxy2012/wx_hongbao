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


<form action="<?php echo site_url("dangzuzhi/edit");?>" onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="id" value="<?php echo $model["id"];?>">


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">党组织名称：</td>
        <td><input type="text" style="width:200px;" name="title"  value="<?php echo $model["title"];?>" id="title" required="true" 
valType="check"
remoteUrl="<?php echo site_url("dangzuzhi/chktitle");?>?id=<?php echo $model["id"];?>&pid=<?php echo $model["pid"];?>"

        
        />
  
        </td>
    </tr>
    <tr>
        <td class="tableleft">上级党组织：</td>
        <td>
<select name="pid" id="pid" onchange="changeremoteurl(this.value)">
<option value="0" selected>石岐区党工委</option>
<?php

foreach($dzz as $v){
	echo "<option value='".$v["id"]."'
		".($model["pid"]==$v["id"]?"selected":"").">".$v["title"]."</option>";
}

?>
</select>
<script>
function changeremoteurl(pid){
	$("#title").attr("remoteUrl","<?php echo site_url("dangzuzhi/chktitle");?>?id=0&pid="+pid);
}
</script>
        </td>
    </tr>  
    <tr>
        <td class="tableleft">地址：</td>
        <td>
<input type="text" style="width:200px;" name="addr"  value="<?php echo $model["addr"];?>" id="addr" />
        </td>
    </tr>      
    <tr>
        <td class="tableleft">排序：</td>
        <td>
<select name="orderby" id="orderby">
<?php for($i=1;$i<=100;$i++){
	echo "<option value='".$i."'".($i==$model["orderby"]?"selected":"")." >".$i."</option>";	
}?>
</select>
        从小到大        
        </td>
    </tr>

    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
            <button type="button" class="button button-warning" onclick="top.topManager.closePage();">关闭</button>
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

</script>

