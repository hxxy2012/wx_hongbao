<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>添加项目</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/DatePicker/WdatePicker.js"></script>    
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
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("pro/prolist");?>">项目列表</a>
</div>
<form action="<?php echo site_url("pro/add");?>" method="post" enctype="multipart/form-data" class="definewidth m2"  name="myform" id="myform" >
<input type="hidden" name="action" value="doadd">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">项目名称</td>
        <td><input type="text" style="width:300px;" name="title" id="title" required errMsg="请输入项目名称" tip="请输入项目名称"/></td>
    </tr>
    <tr>
        <td class="tableleft">附件</td>
        <td>     
        <input type="file" name="fujian" id="fujian" />
        <span style="color:#666666">格式：doc|docx|wps|ppt</span>
        </td>
    </tr> 
    <tr>
        <td class="tableleft">计划时段</td>
        <td>
<input type="text" name="jihua_start_time" id="jihua_start_time" class="Wdate" placeholder="" value="<?php echo date("Y-m-d H:i:s",time());?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,readOnly:true})"  style="width:160px" readonly
required errMsg="请选择开始时间" tip="请选择开始时间"
>
至
<input type="text" name="jihua_end_time" id="jihua_end_time" class="Wdate" placeholder="" value="<?php echo date("Y-m-d H:i:s",time());?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,readOnly:true})"  style="width:160px" readonly
required errMsg="请选择结束时间" tip="请选择结束时间"
>
        </td>
    </tr> 	
    <tr>
      <td class="tableleft">状态</td>
      <td><?php
$i=0;
foreach($pro_status_list as $v){
	
	echo '<lable>';
	echo '<input type="radio" name="mokuai_status" value="'.$v["id"].'"';
	echo $i==0?"checked":"";
	echo ' />'.$v["title"];
	echo "</label>";
	$i++;
}
?>
　<span style="color:#666666">通过的项目才能被其他人看见</span>
</td>
    </tr>
    <tr>
        <td class="tableleft">描述</td>
        <td>
<textarea style="width:100%; height:150px" id="content" name="content" placeholder="描述"></textarea>        
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
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
$(function () {       
		$("#btnSave").click(function(){
			if($("#myform").Valid() == false || !$("#myform").Valid()) {
				return false ;
			}
		});
}); 


KindEditor.ready(function(K) {
       window.editor = K.create('#content',{
				width:'100%',
				height:'400px',
				allowFileManager:false ,
				allowUpload:false,
				afterCreate : function() {
					this.sync();
				},
				afterBlur:function(){
				      this.sync();
				},
				extraFileUploadParams:{
					'cookie':'<?php echo $_COOKIE['admin_auth'];?>'
				},
				uploadJson:"<?php echo site_url("pro/index");?>?action=upload&session=<?php echo session_id();?>"
						
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
