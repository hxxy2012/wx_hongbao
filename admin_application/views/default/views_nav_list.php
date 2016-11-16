<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>后台导航列表</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />

        
            
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
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
<script>
function gostatus(stat){
	
	if($("input[name='id[]']:checked").length==0){
		parent.tip_show ("没有记录",2,3000);
		return false;	
	}
	id = "";
	$("input[name='id[]']:checked").each(
		function(){
			if(id==""){
				id = $(this).val();
			}
			else{
				id+=","+$(this).val();
			}
		}
	);	

	ajax_url = "<?php echo site_url("nav/dostatus");?>";
	$.ajax({
        type: "get",
        url: ajax_url,
        data: {"id":id,"status":stat},
        cache: false,
        dataType: "text",
        async:false,
        success: function(data) {   		                       
        	window.location.reload();
        },
        beforeSend: function() {
          
        },
        error: function() {
            alert('服务器繁忙请稍。。。。');
        }
    });				
	
}

var dodel = false;
function chkdel(){
	if($("input[name='id[]']:checked").length==0){
		parent.tip_show ("没有记录",2,3000);
		return false;	
	}
	id = "";
	$("input[name='id[]']:checked").each(
		function(){
			if(id==""){
				id = $(this).val();
			}
			else{
				id+=","+$(this).val();
			}
		}
	);
	var ajax_url = "<?php echo site_url("nav/chksubnav");?>";

	$.ajax({
        type: "get",
        url: ajax_url,
        data: {"id":id},
        cache: false,
        dataType: "text",
        async:false,
        success: function(data) {   
	                        
        	if(data>0 || data==-1){
				parent.tip_show ("您选的记录存在子项，请先删除子项。",2,10000);	
			}
			else{
				dodel = true;	
			}
        },
        beforeSend: function() {
          
        },
        error: function() {
            alert('服务器繁忙请稍。。。。');
        }
    });				
	if(!dodel){return false;}
	BUI.Message.Confirm("确认删除？",function(){    
	ajax_url = "<?php echo site_url("nav/dodel");?>";
	$.ajax({
        type: "get",
        url: ajax_url,
        data: {"id":id},
        cache: false,
        dataType: "text",
        async:false,
        success: function(data) {   		                       
        	window.location.reload();
        },
        beforeSend: function() {
          
        },
        error: function() {
            alert('服务器繁忙请稍。。。。');
        }
    });			
		
	});
	
}
</script>    
</head>
<body>
<div class="form-inline definewidth m20" >
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="left">
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("nav/add");?>">新增菜单<span class="glyphicon glyphicon-plus"></span></a>
</td>
<td align="right">

<input type="button"  class="btn btn-primary" name="btn_del" value="开放"
onclick="return gostatus(1);"
/>

<input type="button"  class="btn btn-primary" name="btn_del" value="关闭"
onclick="return gostatus(0);"
/>

<input type="button"  class="btn btn-primary" name="btn_del" value="删除"
onclick="return chkdel();"
/>

</td></tr>
</tr></table>   
   
   
</div>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th></th>
<th>菜单标题</th>
        <th>状态</th>
        <th>添加日期</th>
        <th>排序</th>
        <th>访问地址</th>
        <th>管理操作</th>
    </tr>
    </thead>
	<?php 
		if(isset($list) && $list){
			foreach($list as $k=>$v){
			
	?>
	     <tr>
            <td width="10"><input type="checkbox" name="id[]"  value="<?php echo $v['id'];?>" /></td>
       <td colspan="" style="font-weight:bold"><?php echo $v['id'];?>:<?php echo $v['name'];?></td>
			<td><?php echo ($v['status'] ==0)?"<font color='red'>关闭</font>":'开启'; ?></td>
			<td><?php echo $v['addtime'] ; ?></td>			
			<td><?php echo $v['disorder'] ; ?></td>
			<td>无需</td>
			
            <td><a href="<?php echo site_url("nav/edit/");?>?id=<?php echo $v['id'];?>" class="icon-edit"></a>&nbsp;<a href="<?php echo site_url("nav/add/");?>?id=<?php echo $v['id'];?>" class="icon-plus"></a></td>
        </tr>
		<?php 
			if(isset($v['childs']) && $v['childs']){
				foreach($v['childs'] as $child_key=>$child_val){
				
		?>
		<tr>
            <td width="10"><input type="checkbox" name="id[]"  value="<?php echo $child_val['id'];?>" /></td> 
  <td colspan=""><?php echo $child_val['id'];?>:<?php echo str_repeat("&nbsp;",4); ?><?php echo $child_val['name'];?></td>
            <td><?php echo ($child_val['status'] ==0)?"<font color='red'>关闭</font>":'开启'; ?></td>
			<td><?php echo $child_val['addtime'] ; ?></td>
			<td><?php echo $child_val['disorder'] ; ?></td>
			<td>无需</td>
		
			<td><a href="<?php echo site_url("nav/edit/");?>?id=<?php echo $child_val['id'];?>" class="icon-edit"></a>&nbsp;<a href="<?php echo site_url("nav/add/");?>?id=<?php echo $child_val['id'];?>" class="icon-plus"></a></td>
       </tr>
		<!--第三层关系开始-->
		<?php 
			if(isset($child_val['childs']) && $child_val['childs']){
				foreach($child_val['childs'] as $t_key=>$t_val){
				
		?>
		<tr>
        
<td width="10"><input type="checkbox" name="id[]"  value="<?php echo $t_val['id'];?>" /></td>
                <td><?php echo $t_val['id'];?>:<?php echo str_repeat("&nbsp;",10); ?><?php echo $t_val['name'];?></td>
                <td><?php echo ($t_val['status'] ==0)?"<font color='red'>关闭</font>":'开启'; ?></td>
                <td><?php echo $t_val['addtime'];?></td>
                <td><?php echo $t_val['disorder'];?></td>
                <td><?php echo $t_val['url'];?></td>
			
                <td><a href="<?php echo site_url("nav/edit/");?>?id=<?php echo $t_val['id'];?>" class="icon-edit"></a>&nbsp;<a href="<?php echo site_url("nav/add/");?>?id=<?php echo $t_val['id'];?>" class="icon-plus"></a></td>
        </tr>
		<!--第四层关系开始-->
		<?php
			if(isset($t_val['childs']) && $t_val['childs']){
				foreach($t_val['childs'] as $f_key=>$f_val){
	
		?>
		<tr>
         <td width="10"><input type="checkbox" name="id[]"  value="<?php echo $f_val['id'];?>" /></td>
                <td><?php echo $f_val['id'];?>:<?php echo str_repeat("&nbsp;",18); ?><?php echo $f_val['name'];?></td>
                <td><?php echo ($f_val['status'] ==0)?"<font color='red'>关闭</font>":'开启'; ?></td>
                <td><?php echo $f_val['addtime'];?></td>
                <td><?php echo $f_val['disorder'];?></td>
                <td><?php echo $f_val['url'];?></td>
			
                <td><a href="<?php echo site_url("nav/edit/");?>?id=<?php echo $f_val['id'];?>" class="icon-edit"></a>&nbsp;<a href="<?php echo site_url("nav/add/");?>?id=<?php echo $f_val['id'];?>" class="icon-plus"></a></td>
        </tr>		
		<?php 
			}
		}	
		?>
		<!--第四层关系结束-->
		<?php 
			}
		}	
		?>
		<!--第三层关系结束-->
		
	   <?php 
			}
		}	
	   ?>
	   <?php 
			}
		}	
	   ?>
	   </table>
<div class="alert alert-warning alert-dismissable">
  <strong>Tips!</strong> 此列表只会显示4层节点 其中第<font color='red'>4</font>层是功能节点,不会在整个框架的菜单显示的
</div>
</body>
</html>
