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
<body style="padding-top:10px;">

<form onsubmit="return postform()" 
arction="<?php echo site_url("website_model/add");?>"  
method="post" 
id="J_Form" class="form-horizontal"
class="definewidth m10" >
<input type="hidden" id="id" name="id" value=""/> 

  
  <div class="row">
<?php 
echo $field;
?>  
</div>

 <div class="row form-actions actions-bar">
            <div class="span13 offset3 ">
<button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button>
</div>
</div>

<script>
function postform(){
	/*
	$.getJSON("http://localhost:1005/admin.php/Website_model/chkmodelname.shtml?param=111&" + Math.random(),function(data){				   	
        if(data.result != 1)
        {

            alert("no");
        }
        else
        {
            alert("yes");
        }
        
    });
    */	    
    //alert($("#myform").Valid());
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

</body>
</html>