<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户模型</title>
    <meta charset="UTF-8">
   	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
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
<body>
<div class="form-inline definewidth m20" >    
<a  class="btn btn-success" id="addnew" href="<?php echo site_url("usermodel/add");?>">添加用户模型<span class="glyphicon glyphicon-plus"></span></a>
</div>

<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>ID号</th>
        <th>名称</th>
        <th>表名</th>
        <th>状态</th>
		<th>添加日期</th>
		<th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
  <tbody id="result_">
  </tbody>  
  
  </table>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
  
  </div>




</body>
</html>



<script>
$(function () {
	common_request(1);
});
function common_request(page){
	var url="<?php echo site_url("usermodel/index");?>?inajax=1";
	var data_ = {
		'page':page,
		'time':<?php echo time();?>,
		'action':'ajax_data',
		'username':$("#username").val(),
		'condition':$("#condition").val(),
		'status':$("#status").val()
	} ;
	$.ajax({
		   type: "POST",
		   url: url ,
		   data: data_,
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){
			var shtml = '' ;
			var list = msg.resultinfo.list;
			if(msg.resultcode<0){
				BUI.Message.Alert("没有权限执行此操作",'error');
				return false ;
			}else if(msg.resultcode == 0 ){
				BUI.Message.Alert("服务器繁忙",'error');
				return false ;				
			}else{				
				for(var i in list){
					shtml+='<tr>';
					shtml+='<td>'+list[i].id+'</td>';
					shtml+='<td><a href="javascript:void(0)" onclick="jump(\'<?php echo site_url('user/index');?>\')" title="点击查看用户">'+list[i]['name']+'</a>&nbsp;&nbsp;'+list[i].issystem+'</td>';
					shtml+='<td>'+list[i]['table']+'</td>';
					shtml+='<td>'+list[i]['status']+'</td>';
					shtml+='<td>'+list[i]['addtime']+'</td>';
					shtml+='<td>'+list[i]['description']+'</td>';
					shtml+='<td><a href="<?php echo site_url('usermodel/edit');?>?id='+list[i].id+'">编辑</a></td>';
					shtml+='</tr>';
				}
				$("#result_").html(shtml);
				
				$("#page_string").html(msg.resultinfo.obj);
			}
		   },
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(){
			   BUI.Message.Alert("服务器繁忙",'error');
		   }
		  
		});		
	

}
function ajax_data(page){
	common_request(page);	
}
function jump(url){//主要是用于跳转到另外的一个页面的
	top.topManager.openPage({
		 id : '33',
		 href : url,
		 title : '用户列表'
	});
}
//
</script>
