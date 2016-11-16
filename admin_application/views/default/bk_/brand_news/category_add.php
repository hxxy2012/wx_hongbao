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
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/admin.js"></script>
	
<link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
	
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
<body>

<form onsubmit="return postform()" arction="<?php echo site_url("website_category/add");?>"  method="post" class="definewidth m10" id="myform">
<input type="hidden" id="id" name="id" value=""/>
<input type="hidden" name="pid" value="<?php echo $pid;?>"/>
<input type="hidden" id="model_id" name="model_id" value="1"> 
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">名称：</td>
        <td>
<?php 
for($i=0;$i<10;$i++)
{
?>        
<input 
type="text" name="title[]" 
placeholder="输入栏目名" 
<?php echo $i==0?'required="true" ':"";?>
style="width:300px" value=""/>
<?php 

?>
<br/>
<?php 
}
?>

</td>
    </tr>

<tr>
<td width="10%" class="tableleft">简述：</td>
<td>
<input 
type="text" name="beizhu" 
placeholder="输入简单描述" 
style="width:500px" value=""/>
</td>
</tr>
<tr>
<td width="10%" class="tableleft">描述：</td>
<td>
<input 
type="text" name="content" 
placeholder="输入描述" 
style="width:500px" value=""/>
</td>
    </tr>
    
	
	    	
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
        </td>
    </tr>
</table>
<script>
function postform(){
	if($("#myform").Valid()) {
		return true ;
	}
	else{	
		return false;
	}
	//return true;	
}


</script>


</form>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
</body>
</html>