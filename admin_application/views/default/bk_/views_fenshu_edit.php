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
        <td class="tableleft">姓名/身份证：</td>
        <td>

<?php 
echo $model["realname"];
echo "/";
echo $model["idcard"];
?>
        </td>
    </tr>
 

   <tr>
        <td class="tableleft">分数：</td>
        <td>

<input type="text" style="width:200px;" name="fenshu"
value="<?php echo $model["fenshu"];?>" id="fenshu"
required="true" 
valType="int"


        
        />*
修改分数会影响用户的排名
        </td>
    </tr>
 
 
    <tr>
        <td class="tableleft">备注：<br/>可以写写修改原因</td>
        <td>
<textarea name="beizhu" id="beizhu" style="width:300px; height:80px;"><?php echo $model["beizhu"];?></textarea>

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

