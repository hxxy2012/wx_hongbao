<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
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


    </style>
</head>
<body class="definewidth m20">
<form action="" method="post"   name="myform" id="myform" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $model["id"];?>">
<input type="hidden" name="backurl" value="<?php echo $ls;?>">

<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">*主  题</td>
        <td><input name="title" type="text" id="title" value="<?php echo $model['title'];?>" required /></td>
    </tr>
    <tr>
      <td class="tableleft">*报名时段</td>
      <td> 
           <input type="text" name="baoming_start" id="baoming_start" required="true" value="<?php echo date('Y-m-d H:i:s', $model['baoming_start']);?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss',choose: function(datas){$('#baoming_start').focus();}})" placeholder="报名开始时间"/>——
           <input type="text" name="baoming_end" id="baoming_end" required="true" value="<?php echo date('Y-m-d H:i:s', $model['baoming_end']);?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss',choose: function(datas){$('#baoming_end').focus();}})" placeholder="报名结束时间"/>
      </td>
    </tr>
    <tr>
      <td class="tableleft">*活动时段</td>
      <td> 
           <input type="text" name="starttime" id="starttime" required="true" value="<?php echo  date('Y-m-d H:i:s', $model['starttime']);?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss',choose: function(datas){$('#starttime').focus();}})" placeholder="活动开始时间"/>——
           <input type="text" name="endtime" id="endtime" required="true" value="<?php echo  date('Y-m-d H:i:s', $model['endtime']);?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss',choose: function(datas){$('#endtime').focus();}})" placeholder="活动结束时间"/>
      </td>
    </tr>
    <tr>
        <td class="tableleft">*限制人数</td>
        <td><input name="pnum" type="text" id="pnum" value="<?php echo $model['pnum'];?>" valType="number"  required /></td>
    </tr>
    <tr>
      <td class="tableleft">缩略图</td>
      <td> 
           <input type="file" title="格式：JPG|GIF|PNG|BMP" name="thumb" id="thumb" style="width:160px;" />&nbsp;
           <img src="/<?php echo $model['thumb'];?>" alt="" style="width:100px;">
      </td>
    </tr>
    <tr>
        <td class="tableleft">可视权限</td>
        <td>
        <input type="radio" name="isshow" value="1" <?php if($model['isshow']==1) echo 'checked';?>/> 公开
        &nbsp;
        <input type="radio" name="isshow" value="0" <?php if($model['isshow']==0) echo 'checked';?>/> 不公开
        <span class="hint">（选择不公开将不会在用户后台显示）</span>
        </td>
    </tr>
    <tr>
        <td valign="top" class="tableleft">活动介绍</td>
        <td>
            <textarea style="width:300px; height:100px;" id="content" name="content" placeholder="描述"><?php echo htmlspecialchars($model['content']);?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" id="btnSave">保存</button> &nbsp;&nbsp;
              <a  class="btn btn-warning" href="#" onClick="top.topManager.closePage();">关闭</a>         
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
      if ($('#baoming_start').val() > $('#baoming_end').val()) {
          layer.msg("报名开始时间不能大于报名结束时间！");
          $('#baoming_start').focus();
          return false;
      }
      if ($('#starttime').val() > $('#endtime').val()) {
          layer.msg("活动开始时间不能大于活动结束时间！");
          $('#starttime').focus();
          return false;
      }
      if ($('#baoming_start').val() > $('#endtime').val()) {
          layer.msg("活动结束时间不能小于活动报名开始时间！");
          $('#endtime').focus();
          return false;
      }
      if ($('#baoming_end').val() > $('#starttime').val()) {
          layer.msg("报名结束时间不能大于活动开始时间！");
          $('#starttime').focus();
          return false;
      }
		});

});
KindEditor.ready(function(K) {
       beizhu = K.create('#content',{
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
                    'cookie':''
                },
                uploadJson:"<?php echo site_url("website_category/upload");?>?action=upload&session_id==<?php echo $sess["session_id"];?>"
                        
       });
       // plan_beizhu.readonly(true);
});


function gettime(date){
	time = 0;
	$.ajax({
		url:"<?php echo site_url("swj_project/gettime");?>",
		dataType: "text",
		data:{"date":date},
		type: "GET",			
		async:false,
		success: function(data){
			time = data;
		},
		error:function(a,b,c){
			
		}
	});		
	return time;
}

//当选中或取消某项，同时勾选右边的会员组
function selpro(obj){
	guid = obj.attr("guid");
	$("input[name='usertype_"+guid+"[]']").each(function(index, element) {
    	$(this)[0].checked = $("#project_id_"+guid)[0].checked;    
    });		
}
</script>
