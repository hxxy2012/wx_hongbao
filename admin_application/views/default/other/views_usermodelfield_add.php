<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户模型字段添加</title>
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
<form action="<?php echo site_url("usermodel/edit");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doaddfield">
<input type="hidden" name="modelid" value="<?php echo $modelid ;?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">提示文字
		<br>
		
		</td>
        <td><input type="text" name="itemname" id="itemname" required="true" errMsg="请输入提示文字，必须在3-16位数" tip="请输入提示文字，必须在3-16位数"/>备注：修改的时候显示的提示文字</td>
    </tr>
    <tr>
        <td class="tableleft">字段名称
		</td>
        <td><input type="text" name="fieldname" id="fieldname" required="true" errMsg="请输入字段名称" tip="请输入字段名称"/>备注:只能用英文字母或数字，数据表的真实字段名</td>
    </tr> 
    <tr>
        <td class="tableleft">数据类型</td>
        <td>
			<select name="fieldtype">
			<?php 
				if(config_item("field_type")){
					foreach(config_item("field_type") as $field_key=>$field_val){
					
			?>
			
			<option value="<?php echo $field_val['type'] ; ?>"><?php echo $field_val['info'] ; ?></option>
			<?php 
				}
			}	
			?>
			</select>
        </td>
    </tr> 
    <tr>
        <td class="tableleft">默认值
		</td>
        <td>
		<textarea name="vdefault" style="width:300px"></textarea>备注:如果定义数据类型为select、radio、checkbox时，此处填写被选择的项目(用“,”分开，如“男,女,人妖”)。 
		</td>
    </tr> 	
    <tr>
        <td class="tableleft">最大长度
		</td>
        <td><input type="text" name="maxlength" id="maxlength"  errMsg="请输入最大长度" tip="请输入最大长度" value="250"/>备注:文本数据必须填写，大于255为text类型 </td>
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
