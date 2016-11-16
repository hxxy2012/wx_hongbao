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
<form method="get" >
关键字：
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="用户名/手机号/单位/联系人" 
    value="<?php echo $search_val['username'];?>"
    style="width:200px;"
    />
    
<select name="selcheck"  style="width:75px;" >
<option value="" <?php echo $search_val["selcheck"]==""?"selected":"";?>>审核状态</option>
<option value="1" <?php echo $search_val["selcheck"]=="1"?"selected":"";?>>未审</option>
<option value="2" <?php echo $search_val["selcheck"]=="2"?"selected":"";?>>已审</option>
<option value="3" <?php echo $search_val["selcheck"]=="3"?"selected":"";?>>不通过</option>
</select>
<button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>   
        <th>审核</th>     
      <th>用户名称</th>      
        <th>单位</th>
		<th width='132'>提交日期</th>
        <th width='40'>&nbsp;</th>
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>";
				echo "<span style='color:".$v["audit_color"]."'>";
            	echo $v["audit_title"];
				echo "</span>";
   				echo "</td>";
            	echo "<td>".$v["username"];       
				echo $v["status"];		
            	echo "</td>";
				echo "<td>".$v["companyname"]."</td>";
            	echo "<td>".date("Y-m-d H:i",$v["create_time"])."</td>";
            	echo "<td>";
				
				echo "<a class='page-action icon-list' data-href='".site_url('user/reg_edit_check_qy_details')."?id=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='userlist_".$v["id"]."' id='open_edit_".$v["id"]."' title=\"编号".$v["id"]."企业信息\"></a>";	
				
			  		
				            
				echo "</td>";                          	            	             	
            	echo "</tr>";
            	echo "\n";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>


   <input type="hidden" name="selid" id="selid" value=""/>
       <button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button>
       <button class="button button-success" onclick="goset_check_yes()">批量通过</button>
       <button class="button button-warning" id="btn_check_no">批量不通过</button>
       
<?php if($isdel){?>       
       <button class="button button-danger" onclick="godel()">删除</button>
<?php }?>       


       
  <div class="alert alert-warning alert-dismissable">
<strong>温馨提示</strong>
审核状态：
<br/>
未审：用户提交了单位资料，但超管未审核<br/>
 通过：超管已审核通过单位资料，用户的修改马上生效<br/>
不通过：超管审核不通过单位资料，用户后台能查看不通过原因可改单位资料 ，继续提交</div>

  


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});

function goedit(uid){
	alert($("#open_edit_"+uid));
	//window.location.href="<?php echo site_url("user/edit");?>?";
	$("#open_edit_"+uid).click();		
}

function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{						
		var ajax_url = "<?php echo site_url("user/set_check_qy_del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/user/reg_edit_check_qy.shtml";
		parent.parent.my_confirm(
				"确认删除选中？",
				ajax_url,
				url);
	}	
}
function goset_check_yes(){
		var ids = $("#selid").val();		
		if(ids==""){		
			parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
		}
		
		if(confirm("确认后修改的内容即生效\n确认操作？")){
			var url2 = "<?php echo site_url("user/set_check_qy_edit");?>?check=2&idlist="+ids;	
			$.ajax({
				url:url2,
				dataType: "text",
				type: "GET",			
				async:false,
				success: function(data){
					//alert(data);
					if(data==0){	
						parent.flushpage("<?php echo site_url("user/index");?>");									
						parent.tip_show('操作成功',1,1000);
						window.setTimeout("window.location.reload();",1000);
					}
					else{
						parent.tip_show('操作成功，但有部分[未完善资料]的会员不能审核',1,2000);
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
					var url2 = "<?php echo site_url("user/set_check_qy_edit");?>?check=3&idlist="+ids+"&content="+$("#content").val();	
					$.ajax({
						url:url2,
						dataType: "text",
						type: "GET",			
						async:false,
						success: function(data){
							//alert(data);
							if(data==0){
								parent.flushpage("<?php echo site_url("user/index");?>");					
								parent.tip_show('操作成功',1,1000);
								window.setTimeout("window.location.reload();",1000);
							}
							else{
								parent.tip_show('操作成功，但有部分[未完善资料]的会员不能审核',1,2000);
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
