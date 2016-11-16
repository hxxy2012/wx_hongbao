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
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>   
   	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
 
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
<form method="get" >
 <select name="proid">
<option value="0">选择一个栏目</option>
<?php foreach($prolist as $k=>$v){
	echo "<option value='".$v["id"]."'";
	echo $proid==$v["id"]?" selected ":"";
	echo ">\n";
	echo $v["title"];
	echo "\n</option>\n";
}
?>
</select>
<button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
</form>    
</div>

<br/>
<?php
echo "　选择模板：";
foreach($template_list as $v){
	echo "<a href='".site_url("swj_fenshu/index").("?proid=".$proid."&temid=".$v['guid'])."'>";
	if($temid==$v["guid"]){
		echo "<b>";	
	}
	echo $v["title"];
	if($temid==$v["guid"]){
		echo "</b>";	
	}	
	echo "</a>&nbsp;&nbsp;";
	
}
?>
<br/>
<?php
//include("views/".__TEMPLET_FOLDER__."/shangwuju/fenshu/".$moban.".php");
$this->load->view(__TEMPLET_FOLDER__."/shangwuju/fenshu/".$moban);
?>
  
  
<table border="0" style="margin:0px; padding:0px;" width="100%"><tr><td style="border:0px;" align="left">

   <input type="hidden" name="selid" id="selid" style=" <?php echo $isjichu?'display:none;':'';?>" value=""/>
       <button class="button" onclick="selall()" style=" <?php echo $isjichu?'display:none;':'';?>">全选</button>
       <button class="button" onclick="selall2()" style=" <?php echo $isjichu?'display:none;':'';?>">反选</button>  
      
 
</td>
<td style="border:0px;" align="right">  
    
       
  <div id="page_string" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>
</td>
</tr>
  <tr>
    <td colspan="2" align="left" style="border:0px;">
<button class="button button-danger"   style="margin-left:0px;margin-right:5px;margin-top:5px; margin-bottom:5px;<?php echo $isjichu?'display:none;':'';?>" onClick="editfen()">批量修改分数</button>
<button class="button button-primary" onclick="sendsms()" style="margin:5px;<?php echo $isjichu?'display:none;':'';?>">批量发送短信并将状态设为给予扶持</button>
<button class="button button-info"  onClick="sendmail()"  style="margin:5px;<?php echo $isjichu?'display:none;':'';?>">批量发送邮件通知并将状态设为给予扶持</button>    
    </td>
  </tr>
</table>  
     
</body>
</html>
<script>
function editfen(){
		var fs = "";
		$("input[name='fenshu[]']").each(function(index, element) {
            if($(this).val()>0){
				if(fs==""){
					fs= $(this).val()+"|"+$(this).attr("sbid");
				}
				else{
					fs+="_"+$(this).val()+"|"+$(this).attr("sbid");					
				}
			}
        });
		if(fs==""){
			parent.tip_show("没有要更新的分数");
			return false;
		}

	if(confirm("确认更新分数")){
		$.ajax({
				url:"<?php echo site_url("swj_fenshu/updatefen");?>",
				data:{fs:fs},
				dataType: "text",
				type: "POST",			
				async:false,
				success: function(data){
					if(data=="ok"){
						parent.tip_show("更新成功","1","1000");
						window.setTimeout("window.location.reload()","1000");
					}
					else{
						parent.tip_show(data);
						//alert(data);
					}
				},
				error:function(a,b,c){
	
				}
		});
	}
}

function sendsms(){
	selid = $("#selid").val();
	if(selid==""){
		parent.tip_show("请选中要操作的记录");
		return false;	
	}
	if(selid!=""){
		if(confirm("确认要发送短信？")){
			$.ajax({
					url:"<?php echo site_url("swj_fenshu/sendsms");?>",
					data:{idlist:selid},
					dataType: "text",
					type: "POST",			
					async:true,
					success: function(data){
						if(data=="ok"){
							parent.tip_show("发送成功","1","1000");
							window.setTimeout("window.location.reload()","1000");
						}
						else{
							parent.tip_show(data);
							//alert(data);
						}
					},
					error:function(a,b,c){
		
					}
			});			
		}
	}
}
function sendmail(){
	selid = $("#selid").val();
	if(selid==""){
		parent.tip_show("请选中要操作的记录");
		return false;	
	}
	if(selid!=""){
		if(confirm("确认要发送邮件？\n建议每次发送控制在5条记录以内，邮件不能频繁发送。")){
			$.ajax({
					url:"<?php echo site_url("swj_fenshu/sendmail");?>",
					data:{idlist:selid},
					dataType: "text",
					type: "POST",			
					async:true,
					success: function(data){
						if(data=="ok"){
							parent.tip_show("发送成功","1","1000");
							window.setTimeout("window.location.reload()","1000");
						}
						else{
							parent.tip_show(data);
							//alert(data);
						}
					},
					error:function(a,b,c){
		
					}
			});			
		}
	}
}

</script>

<script src="/admin_application/views/static/Js/selall.js"></script>

