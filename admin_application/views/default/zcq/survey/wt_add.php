<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>添加问题</title>
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
<input type="hidden" name="action" value="doadd">
<input type="hidden" name="ls" value="<?php echo $ls;?>">
<table class="table table-bordered table-hover m10">

    <tr>
      <td class="tableleft">*调查问卷</td>
      <td> 
          <select name="diaocha_id" id="diaocha_id">
            <?php foreach ($suv_list as $key => $value): ?>
                <option value="<?php echo $value['id'];?>" <?php if($value['id']==$diaocha_id)echo 'selected';?>>
                  <?php echo $value['title'];?>
                </option>
            <?php endforeach ?>
          </select>
      </td>
    </tr>
    <tr class="add_wt">
        <td class="tableleft">*添加题目</td>
        <td>
            <ul class="add_wt_ul">
            </ul>
            <p>
                <input type="text"  name="wt_title" id="wt_title" placeholder="问题"/>
                <input type="radio" name="wt_itemtype" value='1' checked>单选&nbsp;
                <input type="radio" name="wt_itemtype" value='2'>多选&nbsp;
                <input type="radio" name="wt_itemtype" value='3'>问答题&nbsp;
                <input type="checkbox" name="wt_isother" value='1'>其他时增加输入框 <span class="hint">（如需使用其他输入框，请在问题框最后一个选项输入其他即可）</span>
            </p>
            <p>
                <textarea  name="wt_all_add" id="wt_all_add" cols="30" rows="6" placeholder="选项一行一个"></textarea>&nbsp;
                <input type="button"  name="add_wt" id="add_wt" class="btn vab" value="添加一个问题"> <span class="hint vab">（添加问题前请将问题和选项填写完整）</span>
            </p>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" id="btnSave">保存</button> &nbsp;&nbsp;
			<a  class="btn btn-warning" href="<?php echo $ls;?>">返回</a>            
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
            var wtx_length = $('.add_wt_ul').children('li').length;
            if (wtx_length <= 0) {
                layer.msg("请至少添加一道题目");
                return false;
            }
            //判断是否存在未提交的问题，存在提示先点击添加题目
            if ($('#wt_title').val()!=""||$('#wt_all_add').val()!="") {
                layer.msg("请先提交问题再点击保存");
                return false;
            }
		});

        //监听点击单选还是多选
        $('input[name="wt_itemtype"]').click(function(){
            var wt_itemtype = parseInt($(this).val());
            if (wt_itemtype == 3) {
                //选中为问答题的隐藏文本框
                $('#wt_all_add').hide();
            } else {
                $('#wt_all_add').show();
            }
        });
        //监听点击添加问题按钮
        $('#add_wt').click(function(){
            var wt_title = $('#wt_title').val();//问题名称
            if (wt_title=='') {
                layer.msg("请输入问题名称");
                $('#wt_title').focus();
                return false;
            }
            var  wt_itemtype = $('input[name="wt_itemtype"]:checked').val();//题目类型
            var  wt_all_add = $('#wt_all_add').val();//题目选项
            if ((wt_itemtype=='1'||wt_itemtype=='2')&&wt_all_add=='') {
                //是选择题且选项为空的提示
                layer.msg("请输入问题选项");
                $('#wt_all_add').focus();
                return false;
            }
            var isother = 0;//用于代表是否其他时增加输入框0为否1是
            var isother_str = '';
            if ($("input[name='wt_isother']").is(':checked')) {
                isother = 1;
                if (wt_itemtype!='3') 
                isother_str = ',选项为其他时增加输入框';
            } else {
                isother = 0;
                if (wt_itemtype!='3') 
                isother_str = ',选项为其他时不增加输入框';
            }
            var arr_type = ['单选','多选','问答题'];
            //进行添加题目到ul中
            var str  =  '<li>' + wt_title + '('+arr_type[wt_itemtype-1] + isother_str+')&nbsp;<a onclick="del_xx($(this));">删除</a>';
            if (wt_itemtype != '3') {
                //不为问答题时的，将选项显示出来
                // wt_all_add_arr = wt_all_add.split("\r\n")
                wt_all_add_gs = wt_all_add.replace(/\n/g,";");//转换成输出格式的选项
                str += '<br/>&nbsp;&nbsp;' + wt_all_add_gs;
            }
            //隐藏域，问题跟选项放到隐藏的input标签进行提交
            str +=  '<input type="hidden" name="wt_title_more[]" value="'+wt_title+'">';
            str +=  '<input type="hidden" name="wt_itemtype_more[]" value="'+wt_itemtype+'">';
            str +=  '<input type="hidden" name="wt_all_add_more[]" value="'+wt_all_add+'">';
            str +=  '<input type="hidden" name="isother_more[]" value="'+isother+'">';
            str +=  '</li>';
            $('.add_wt_ul').append(str);
            //清空输入框
            $('#wt_title').val('');
            $('#wt_all_add').val('');
        });
});
//删除动态添加的选项。移出li
function del_xx(obj) {
    obj.parents('li').remove();
}
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
