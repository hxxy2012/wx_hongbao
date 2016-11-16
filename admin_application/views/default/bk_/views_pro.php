<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>项目列表</title>
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
    项目名称：
      <input type="text" name="search_title" id="search_title"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;  
	

    <button type="submit" class="btn btn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp; <a  class="btn btn-success" id="addnew" href="<?php echo site_url("pro/add");?>">添加项目<span class="glyphicon glyphicon-plus"></span></a>
</div>

<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th width="85">编号</th>
        <th>项目名称</th>
        <th width="150">计划开始</th>
        <th width="150">计划结束</th>
        <th width="150">创建时间</th>
		<th  width="130">创建人</th>		
        <th  width="150">操作</th>
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
	var url="<?php echo site_url("pro/prolist");?>?inajax=1";
	var data_ = {
		'page':page,
		'time':<?php echo time();?>,
		'action':'ajax_data',
		'search_title':$("#search_title").val()
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
					shtml+='<td>'+list[i]['id']+'</td>';
					shtml+='<td>'+list[i]['title']+'</td>';
					shtml+='<td>'+list[i]['jihua_start_time']+'</td>';
					shtml+='<td>'+list[i]['jihua_end_time']+'</td>';
					shtml+='<td>'+list[i]['create_time']+'</td>';
					shtml+='<td>'+list[i]['create_username']+'</td>';
					shtml+='<td><a href="<?php echo site_url('pro/edit');?>?id='+list[i]['id']+'" class="icon-edit" title="编辑"></a>&nbsp;&nbsp;<a href="<?php echo site_url('pro/proview');?>?id='+list[i]['id']+'" class="icon-file" title="浏览项目信息"></a>&nbsp;&nbsp;<a href="<?php echo site_url('mokuai/lists');?>?proid='+list[i]['id']+'" class="icon-list-alt" title="查看模块"></a></td>';
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
//
</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>