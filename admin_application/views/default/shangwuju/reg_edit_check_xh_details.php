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
BUI.use('common/page');
</script>        
</head>
<body class="definewidth">
<div class="form-inline definewidth m20" >
</div>
<div class="alert alert-warning alert-dismissable">
<?php
echo "<b>";
echo $usermodel["realname"];
echo "</b>";
echo "于";
echo date("Y-m-d H:i",$editmodel["create_time"]);
echo "提交了<b>";
echo $oldmodel["name"];
echo "</b>修改";

if($editmodel["check_status"]=="3"){
	if($editmodel["check_content"]!=""){
		echo "<br/><br/>审核人：".$editmodel["system_username"]."&nbsp;审核意见：";	
		echo $editmodel["check_content"];	
	}
}
?>
</div>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
<tr>
  <td width="280" align="center" bgcolor="#CCCCCC">字段</td>
  <td align="center" bgcolor="#CCCCCC">修改前</td>
  <td align="center" bgcolor="#CCCCCC">修改后</td>
</tr>
<?php
foreach($newmodel as $k=>$v){
?>
<tr>
    <td align="center" style="border-bottom:1px solid #cccccc;" bgcolor="#FFFFFF">
<?php
foreach($field as $row){
	if(strtolower($k)==strtolower($row["COLUMN_NAME"])){
		echo $row["COLUMN_COMMENT"];
		break;	
	}
}
?>    
    </td>
    <td align="center" style="border-bottom:1px solid #cccccc;" bgcolor="#FFFFFF"><?php
    echo $oldmodel[$k]=="0"?"":$oldmodel[$k];
	?></td>
    <td align="center" style="border-bottom:1px solid #cccccc;" bgcolor="#FFFFFF"><?php echo $v=="0"?"":$v;?></td>
</tr>
<?php
}
?>
</table>  

<?php
if($editmodel["check_status"]=="1"){
?>
<div style="text-align:center;">
<br/>
<button class="button button-success" onclick="goset_check_yes()">审核通过</button>
<button class="button button-warning" id="btn_check_no">审核不通过</button>
<input type="hidden" name="selid"  id="selid" value="<?php echo $editmodel["id"];?>"/>
</div>       
<script>
function goset_check_yes(){
		var ids = $("#selid").val();		
		if(ids==""){		
			parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
		}
		
		if(confirm("确认后修改的内容即生效\n确认操作？")){
			var url2 = "<?php echo site_url("user/set_check_xh_edit");?>?check=2&idlist="+ids;	
			$.ajax({
				url:url2,
				dataType: "text",
				type: "GET",			
				async:false,
				success: function(data){
					//alert(data);
					if(data==0){	
						parent.flushpage("<?php echo $ls;?>");									
						parent.tip_show('操作成功',1,1000);
						window.setTimeout("window.location.reload();",1000);
					}
					else{						
						window.setTimeout("window.location.reload();",2000);					
					}
				},
				error:function(a,b,c){
					
				}
			});		
		}
}

 BUI.use('bui/overlay',function(Overlay){
          var dialog = new Overlay.Dialog({
            title:'输入审核不通过原因',
            width:500,
            height:220,
            //配置文本
            bodyContent:'<textarea id="content" name="content" style="width:100%;height:150px;"></textarea>',
            success:function () {                            
			  var ids = $("#selid").val();		
				if(ids==""){		
					parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
					return false;
				}
				if(confirm("确认操作？")){
					var url2 = "<?php echo site_url("user/set_check_xh_edit");?>?check=3&idlist="+ids+"&content="+$("#content").val();	
					$.ajax({
						url:url2,
						dataType: "text",
						type: "GET",			
						async:false,
						success: function(data){
							//alert(data);
							if(data==0){
								parent.flushpage("<?php echo $ls;?>");					
								parent.tip_show('操作成功',1,1000);
								window.setTimeout("window.location.reload();",1000);
							}
							else{
								window.setTimeout("window.location.reload();",2000);					
							}
						},
						error:function(a,b,c){
							
						}
					});		
				}							  
				
            }
          });
		  
	
	$("#btn_check_no").click("on",function(){
			var ids = $("#selid").val();		
			if(ids==""){		
				parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
				return false;
			}				
			$("#content").val("");
			dialog.show();	
	});	  
 });


</script>


<?php
}
?>

<div>

</div>

</body>
</html>