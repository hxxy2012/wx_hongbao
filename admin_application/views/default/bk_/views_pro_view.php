<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>项目详细</title>
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
<form action="<?php echo site_url("pro/edit");?>" method="post" enctype="multipart/form-data" class="definewidth m2"  name="myform" id="myform" >
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $info['id'];?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">项目名称</td>
        <td><?php echo $info["title"];?></td>
    </tr>
    <tr>
        <td class="tableleft">附件</td>
        <td><?php
		if($info["fujian"]!=""){
			echo "<a href='/".$info["fujian"]."' target='_blank'>查看附件</a>";				
		}
		else{
			echo "没有上传";	
		}
		?>
        </td>
    </tr> 
    <tr>
        <td class="tableleft">计划时段</td>
        <td>
<?php echo date("Y-m-d H:i:s",$info["jihua_start_time"]);?>
　
至
　
<?php echo date("Y-m-d H:i:s",$info["jihua_end_time"]);?>
        </td>
    </tr> 	
    <tr>
      <td class="tableleft">状态</td>
      <td>
<?php
echo $info["mokuai_status_title"];
?>
　
</td>
    </tr>
    <tr>
        <td class="tableleft">描述</td>
        <td>

<?php echo $info["content"];?>
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
