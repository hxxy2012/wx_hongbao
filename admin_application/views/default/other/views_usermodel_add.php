<?php
if (! defined('BASEPATH')) {
exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>用户模型添加</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />
<script type="text/javascript" src="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
<link href="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
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
<a class="btn btn-primary" id="addnew" href="<?php echo site_url("usermodel/index");?>">模型列表</a>
</div>
<form action="<?php echo site_url("usermodel/add");?>" method="post" class="definewidth m2" name="myform" id="myform">
<input type="hidden" name="action" value="doadd">
<table class="table table-bordered table-hover m10">
<tr>
<td class="tableleft">模型名称</td>
<td><input type="text" name="name" id="name" required="true" errMsg="请输入名称，必须在3-16位数" tip="请输入名称，必须在3-16位数"/></td>
</tr>
<tr>
<td class="tableleft">数据表</td>
<td><input type="text" name="table" id="table" required="true" errMsg="请输入数据表" tip="请输入数据表"/>备注:请不要加数据表的前缀,系统会自动的加上</td>
</tr>
<tr>
<td class="tableleft">描述</td>
<td>
<textarea name="description" id="description" style="width:300px"></textarea>
</td>
</tr>
<tr>
<td class="tableleft">状态</td>
<td>
<input type="radio" name="status" value="1" checked/> 启用
<input type="radio" name="status" value="0"/> 禁用
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