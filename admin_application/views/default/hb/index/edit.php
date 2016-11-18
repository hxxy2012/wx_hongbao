<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>添加红包</title>
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
<input type="hidden" name="id" value="<?php echo $model['id'];?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">*红包标题</td>
        <td><input name="title" type="text" id="title" value="<?php echo $model['title'];?>" required /></td>
    </tr>
    <tr>
      <td class="tableleft">正在进行</td>
      <td> 
          <input type="radio" name="curr" value="0" <?php if($model['curr']=='0')echo 'checked';?>>否&nbsp;
          <input type="radio" name="curr" value="1" <?php if($model['curr']=='1')echo 'checked';?>>是&nbsp;
          <span class="hint">（所有轮红包中只能选择一轮作为正在进行红包活动）</span>
      </td>
    </tr>
    <tr>
      <td class="tableleft">随机金额</td>
      <td> 
          <input type="radio" name="suiji" value="0" <?php if($model['suiji']=='0')echo 'checked';?>>随机&nbsp;
          <input type="radio" name="suiji" value="1" <?php if($model['suiji']=='1')echo 'checked';?>>固定&nbsp;
      </td>
    </tr>
    <tr>
        <td class="tableleft">红包总数</td>
        <td>
        <input name="hongbao_shu" class="cantedit" style="width:80px;" type="text" id="hongbao_shu" required value="<?php echo $model['hongbao_shu'];?>" valType="int"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">总金额</td>
        <td>
        <input name="jine" class="cantedit" style="width:80px;" type="text" id="jine" required value="<?php echo $model['jine'];?>" valType="number"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">红包最小金额</td>
        <td>
        <input name="qibu_jine" class="cantedit" style="width:80px;" type="text" id="qibu_jine" required value="<?php echo $model['qibu_jine'];?>" valType="number" />
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" id="btnSave">保存</button> &nbsp;&nbsp;
			      <a  class="btn btn-warning" href="#" onClick="top.topManager.closePage();">关闭</a> 
            <span class="hint">（正在进行红包活动只能修改为标题和是否结束红包活动，其他内容不能修改）</span>      
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
    var curr = '<?php echo $model["curr"]?>';
    if (curr == '1') {
      //修改正在进行的红包活动，使其他输入框变灰
      $('.cantedit').attr('readony',true);
      $('.cantedit').attr('disabled',true);
      $('input[name="suiji"]').attr('readony',true);
      $('input[name="suiji"]').attr('disabled',true);
    }
		$("#btnSave").click(function(){
			if($("#myform").Valid() == false || !$("#myform").Valid()) {
				return false ;
			}
      var flag_ing = false;//判断是否可以添加正在进行的红包
      var tishi = '';
      if ($('input[name="curr"]:checked').val()=='1') {
          $.ajax({
              url:"<?php echo site_url("hb_index/checkhasing")?>",
              dataType: "text",
              data:{"id":'<?php echo $model["id"]?>'},
              type: "GET",            
              async:false,
              success: function(data){
                 var obj = eval('(' + data + ')');
                 tishi = obj.info;
                 if (obj.code != '0') {
                    flag_ing = true;
                 } 
              },
              error:function(a,b,c){
                flag_ing = true;
                tishi = '出错，请刷新重试';
              }
          }); 
      }
      //存在正在进行的红包活动
      if (flag_ing) {
          layer.msg(tishi);
          return false;
      }
      if (curr=='0'&&!confirm('您确定要修改吗，修改后中奖记录将清空!')) {
        return false;
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
