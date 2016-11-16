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
<form action="<?php echo site_url("user/fanzhuanzheng");?>" onsubmit="return postform()" method="post" name="myform_fanzhuanzheng" id="myform_fanzhuanzheng">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">
党员姓名：        
        </td>
 
        
        <td>
<?php echo $model["realname"];?>  
&nbsp;编号:<?php echo $model["uid"];?>      
        </td>
	</tr> 
    <tr>
        <td class="tableleft">
身份证号：        
        </td>
        
        <td>
<?php echo $model["idcard"];?>        
        </td>
	</tr> 
    <tr>
        <td class="tableleft">
反转正原因：        
        </td>
        
        <td>
<textarea name="fanzhuanzheng_beizhu" id="fanzhuanzheng_beizhu" required errMsg="输入信息" style="width:300px;height:60px;"></textarea>
        </td>
	</tr> 		       

    <tr>
        <td class="tableleft"></td>
        <td>
<button type="button" class="btn btn-primary" type="button" id="btnSave" onclick="return postform()">提交</button> &nbsp;&nbsp;
<button  class="btn btn-primary" type="button"
onclick="parent.showpage_close();">关闭</button>
<input type="hidden" name="fanzhuanzheng_uid" id="fanzhuanzheng_uid" value="<?php echo $model["uid"];?>"/>
        </td>
    </tr>
</table>
<div class="alert alert-warning alert-dismissable">
  <strong>注意</strong> 
  反转正后，本区党员转为非本区党员。
</div>
<script type="text/javascript">
function postform(){
	var yy = $("#fanzhuanzheng_beizhu").val();
	yy = yy.replace(/\s/ig,"");
	if(yy==""){
		parent.tip_show ("请输入反转正原因",2,2000);
		$("#fanzhuanzheng_beizhu").focus();
		return false;
	}

	if($("#myform_fanzhuanzheng").Valid()) {

		save();
		return true ;
	}
	else{		
		return false;
	}
}

function save(){
	var ajax_url = "/admin.php/user/fanzhuanzheng.shtml";
	var uid = $("#fanzhuanzheng_uid").val();
	//alert(uid);
	$.ajax({
        type: "post",
        url: ajax_url,
        data: {"uid":uid,"yuanyin":$("#fanzhuanzheng_beizhu").val()},
        cache: false,
        dataType: "text",
        //async:false,
        success: function(data) {     
            //alert(data);                           
            if (data == "yes") {        
            	parent.tip_show('反转正成功。',0,2000);              	
                setTimeout("window.location.reload();parent.showpage_close();",2000);                
                
            }
            else if(data == "97"){
            	parent.tip_show('党员是二级管理员，请撤销管理员后再操作。',0,3000);
                return false; 
            } 
            else if (data == "98") {
            	parent.tip_show('用户不存在。',0,1000);
                return false;                
            }
            else if (data == "99") {
            	parent.tip_show('没有用户。',0,1000);
                return false;                
            }
             else {
            	parent.tip_show('其他问题，导致操作不成功。',0,1000);
                return false;                    
            }
        },
        beforeSend: function() {
            //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
        },
        error: function(a,b,c,d) {
            
            alert('服务器繁忙请稍。。。。');
        }

    });			
}



</script>
</form>
</body>
</html>


