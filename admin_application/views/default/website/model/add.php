<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>添加广告</title>
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

<form onsubmit="return postform()" arction="<?php echo site_url("website_model/add");?>"  method="post" class="definewidth m10" id="myform">
<input type="hidden" id="id" name="id" value="<?php echo $info["id"];?>"/> 
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">名称：</td>
        <td>
<input 
type="text" name="title" 
placeholder="输入模型名称" 
required="true" 
valType="check"
remoteUrl="<?php echo site_url("Website_model/chkmodelname");?>?id=<?php echo $info["id"];?>"
style="width:300px" value="<?php echo $info["title"];?>"/>
</td>
    </tr>
<tr>
<td width="10%" class="tableleft">
表名：
</td>
<td>
website_model_
<input 
type="text" name="tablename" 
placeholder="输入表名" 
style="width:100px"
required="true"    
value="<?php echo $info["tablename"];?>"
<?php echo $info["id"]>0?"readonly":"";?>
/>
</td>
</tr>
<tr>
<td width="10%" class="tableleft">描述：</td>
<td>
<input 
type="text" name="content" 
placeholder="输入描述" 
style="width:500px" value="<?php echo $info["content"];?>"/>
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