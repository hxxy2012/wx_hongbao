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
        .add_wt textarea{margin-bottom:0;}
        .add_wt input[type="radio"]{margin-top: 0;}
        .add_wt_ul{margin: 0;padding: 0;list-style-type: none;margin-bottom: 5px;}
        .add_wt_ul a{cursor: pointer;}
        .add_wt_ul li{border-bottom: 1px dashed #ccc;}
        .vab{vertical-align:bottom;}
        .hint{color: #ccc;}

    </style>
</head>
<body class="definewidth m20">
<form action="" method="post"   name="myform" id="myform">
<input type="hidden" name="id" value="<?php echo $model["id"];?>">
<input type="hidden" name="backurl" value="<?php echo $ls;?>">

<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">调查问卷</td>
        <td><span style="color:#bd362f;"><?php echo $model_suv['title'];?></span></td>
    </tr>
    <tr>
      <td class="tableleft">*<?php if($model['pid']==0)echo '问题';else echo '选项';?>名称</td>
      <td> 
          <input name="title" type="text" id="title" value="<?php echo $model['title'];?>"  required />&nbsp;
      </td>
    </tr>
    <!-- 是问题而不是选项的显示问题设置 -->
    <tr <?php if ($model['pid']!=0) echo "style='display:none';";?>>
        <td class="tableleft">*问题设置</td>
        <td>
            <input type="radio" name="itemtype" value='1' <?php if($model['itemtype']==1)echo 'checked';?>>单选&nbsp;
            <input type="radio" name="itemtype" value='2' <?php if($model['itemtype']==2)echo 'checked';?>>多选&nbsp;
            <input type="radio" name="itemtype" value='3' <?php if($model['itemtype']==3)echo 'checked';?>>问答题&nbsp;
            <input type="checkbox" name="isother" value='1'  <?php if($model['itemtype']==1)echo 'checked';?>>其他时增加输入框 <span class="hint">（如需使用其他输入框，请在问题框最后一个选项输入其他即可）</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">排序</td>
        <td>
          <input name="orderby" type="text" id="orderby" value="<?php echo $model['orderby'];?>"  required />
        </td>
    </tr>
    <tr>
        <td valign="top" class="tableleft">描述</td>
        <td>
            <textarea style="width:300px; height:100px;" id="content" name="content" placeholder="描述"><?php echo $model['content'];?></textarea>
        </td>
    </tr>
    <tr class="add_wt" id="add_wt" <?php if($model['itemtype']==3)echo "style='display:none;'";?>>
        <td class="tableleft">题目选项</td>
        <td>
              <textarea style="width:300px; height:100px;"  name="wt_all_add" id="wt_all_add" cols="30" rows="6" placeholder="选项一行一个"><?php $length_xx=count($model_xx); ?><?php foreach ($model_xx as $key => $value): ?><?php if ($key < $length_xx-1): ?><?php echo $value['title'].';';?><?php else: ?><?php echo $value['title'];?><?php endif ?><?php endforeach ?></textarea>&nbsp;
            <!-- <textarea style="width:300px; height:150px;"  name="wt_all_add" id="wt_all_add" cols="30" rows="6" placeholder="选项一行一个"><?php foreach ($model_xx as $key => $value): ?><?php echo $value['title'];?><?php endforeach ?></textarea>&nbsp; -->
            <span class="hint">(选项一行一个)</span>
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
		});
    //转成换行符
    var xx = $('#wt_all_add').val();
    // alert(xx);
    temp_xx = xx.replace(/;/g,"\n");//转换成输出格式的选项
    $('#wt_all_add').val(temp_xx);

    //监听点击单选还是多选
    $('input[name="itemtype"]').click(function(){
        var itemtype = parseInt($(this).val());
        if (itemtype == 3) {
            //选中为问答题的隐藏文本框
            $('#add_wt').hide();
        } else {
            $('#add_wt').show();
        }
    });
});

KindEditor.ready(function(K) {
       // beizhu = K.create('#content',{
       //          width:'100%',
       //          height:'400px',
       //          allowFileManager:false ,
       //          allowUpload:false,
       //          afterCreate : function() {
       //              this.sync();
       //          },
       //          afterBlur:function(){
       //                this.sync();
       //          },
       //          extraFileUploadParams:{
       //              'cookie':''
       //          },
       //          uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
       // });
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
