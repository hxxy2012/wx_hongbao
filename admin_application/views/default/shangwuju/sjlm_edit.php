<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>类目编辑</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/laydate/laydate.js"></script>   

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
        .hint{color: #ccc;font-size: 12px;}

    </style>
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("swj_sjsbgl/index");?>">类目列表</a>
</div>
<form action="<?php echo site_url("swj_sjsbgl/edit");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $id;?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">类目名称</td>
        <td><input type="text" name="name" id="name" value="<?php echo $name;?>" required="true" tip="请输入类目名称"/></td>
    </tr>
    <tr>
        <td class="tableleft">可视权限</td>
        <td>
            <input type="radio" name="isshow" value="1" <?php if($isshow == 1) echo 'checked';?>/> 公开
            <input type="radio" name="isshow" value="0"<?php if($isshow == 0) echo 'checked';?>/> 不公开&nbsp;&nbsp;
            <span class="hint">(不公开：只有管理员才能查看修改，公开：前台用户能查看)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">选择数据模板</td>
        <td>
            <select name="template" id="template" required="true">
                <option value="">请选择</option>
                <option value="1" <?php if($template == 1) echo 'selected';?>>双十一数据</option>
            </select>
        </td>
    </tr>
     <tr>
        <td class="tableleft">上报时段</td>
        <td>
            <input type="text" name="sb_stime" id="sb_stime" required="true" value="<?php echo $sb_stime;?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" placeholder="开始时间"/>——
            <input type="text" name="sb_etime" id="sb_etime" required="true" value="<?php echo $sb_etime;?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" placeholder="结束时间"/>
        </td>
    </tr>
     <tr>
        <td class="tableleft">审核时段</td>
        <td>
            <input type="text" name="sh_stime" id="sh_stime" required="true" value="<?php echo $sh_stime;?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" placeholder="开始时间"/>——
            <input type="text" name="sh_etime" id="sh_etime" required="true" value="<?php echo $sh_etime;?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" placeholder="结束时间"/>
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
		$('#backid').click(function(){
				window.location.href="<?php echo site_url("nav/index");?>";
		 });
		$("#btnSave").click(function(){
			if($("#myform").Valid() == false || !$("#myform").Valid()) {
				return false ;
			}
            var sb_stime = $('#sb_stime').val();
            var sb_etime = $('#sb_etime').val();
            var sh_stime = $('#sh_stime').val();
            var sh_etime = $('#sh_etime').val();
            // alert(sb_stime + ',' + sb_etime + ',' + sh_stime + ',' + sh_etime);
            if (sb_stime > sb_etime) {
                alert('上报开始时间不能大于结束时间！！');
                return false;
            }
            if (sh_stime > sh_etime) {
                alert('审核开始时间不能大于结束时间！！');
                return false;
            }
            if (sh_stime < sb_stime) {
                alert('审核开始时间不能小于上报开始时间！！');
                return false;
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
