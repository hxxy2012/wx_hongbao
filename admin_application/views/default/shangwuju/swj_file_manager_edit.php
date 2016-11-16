<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>编辑</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
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
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("Swj_file_manager/index");?>">列表</a>
</div>
<form action="<?php echo site_url("Swj_file_manager/edit");?>" method="post"  enctype="multipart/form-data" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $id;?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">名称</td>
        <td><input type="text" name="name" id="name" required value="<?php echo $name?>"  tip="请输入名称"/></td>
    </tr>
    <tr>
        <td class="tableleft">文件</td>
        <td>
            <input type="file" name="file" id="file"  tip="请上传文件"/>
           
            
             <?php
                if(!empty($filesrc)){
                    echo '<input type="button" onclick="window.open(\'/'.$filesrc.'\');"  id="btn_look" value="点击查看"/>';					
					echo " ";
                    echo '<input type="button"  id="buttondel" value="删除文件"/>';
                }
			?>
        
        </td>
        
        <input type="hidden" name="hidden_file" id="hidden_file" value="<?php echo $filesrc?>"/>
        <script>
            if($('#hidden_file').val()==''){
                $('#file').attr('required','true');
            }
            
            $('#buttondel').click(function(){
                $.ajax({
                 type: "POST",
                 url: "<?php echo site_url("Swj_file_manager/ajax_del_pic");?>",
                 data: {id:<?php echo $id;?>},
                 success: function(msg){
                     if(msg==1){
                        alert( '删除成功！' );
                          $('#file').attr('required','true');
                          $('#buttondel').hide();
                     }else{
                         alert('删除失败！');
                     }
                 }
              });
            });
        </script>
    </tr>
    <tr>
        <td class="tableleft">可视权限</td>
        <td>
            <input type="radio" name="audit" value="1" <?php if($audit==1) echo 'checked';else echo '';?>/> 公开
            <input type="radio" name="audit" value="2" <?php if($audit==2) echo 'checked';else echo '';?>/> 不公开
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
            <textarea style="width:300px; height:100px;" id="content" name="content" placeholder="描述"><?php echo htmlspecialchars($beizhu)?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
            <!-- <input type="button" class="btn btn-primary" value="返回" id="back" onclick="javascript:history.go(-1);">&nbsp;&nbsp; -->
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
// KindEditor.ready(function(K) {
//        window.editor = K.create('#content',{
//                 width:'100%',
//                 height:'400px',
//                 allowFileManager:false ,
//                 allowUpload:false,
//                 afterCreate : function() {
//                     this.sync();
//                 },
//                 afterBlur:function(){
//                       this.sync();
//                 },
//                 extraFileUploadParams:{
//                     'cookie':''
//                 },
//                 uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
//        });
// });

 
</script>
