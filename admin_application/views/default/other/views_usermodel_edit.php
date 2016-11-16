<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户模型编辑</title>
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
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("usermodel/index");?>">模型列表</a>
</div>
<form action="<?php echo site_url("usermodel/add");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doadd">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">模型名称</td>
        <td><input type="text" name="name" id="name" required="true" errMsg="请输入名称，必须在3-16位数" tip="请输入名称，必须在3-16位数" value="<?php echo $info['name'];?>"/></td>
    </tr>
    <tr>
        <td class="tableleft">数据表</td>
        <td><input disabled type="text" name="table" id="table" required="true" errMsg="请输入数据表" tip="请输入数据表" value="<?php echo $info['table'];?>"/>备注:不可以进行修改</td>
    </tr> 
    <tr>
        <td class="tableleft">
		        模型字段配置：<br>
		信息索引类字段系统已经加入，<br>
		您只需要增加其它个性化字段即可。<br>
        </td>
        <td> <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("usermodel/edit");?>?action=addfield&modelid=<?php echo $info['id'];?>">添加新的字段</a></td>
    </tr> 
</table>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>提示文字</th>
        <th>数据字段名</th>
		<th>数据类型</th>
        <th>前台显示</th>
        <th>条件搜索</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
	<tbody id="result_">
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody> 
</table>
 <table class="table table-bordered table-hover m10">       
    <tr>
        <td class="tableleft">描述</td>
        <td>
        <textarea name="description" id="description"  style="width:300px"><?php echo $info['description'];?></textarea>
        </td>
    </tr> 	
    <tr>
        <td class="tableleft">状态</td>
        <td>
            <input type="radio" name="status" value="1" <?php if($info['status'] == 1 ){echo "checked" ; }?>/> 启用
            <input type="radio" name="status" value="0" <?php if($info['status'] == 0 ){echo "checked" ; }?>/> 禁用
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
        </td>
    </tr>
</table>
</form>
</body>
</html>
<script>
$(function () {       
		$("#btnSave").click(function(){
			if($("#myform").Valid() == false || !$("#myform").Valid()) {
				return false ;
			}
		});
}); 
</script>
<!-- script start-->
<script type="text/javascript">
	var Calendar = BUI.Calendar
	var datepicker = new Calendar.DatePicker({
	trigger:'#expire',
	showTime:true,
	autoRender : true
	});
</script>
<!-- script end -->
