<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>类目添加</title>
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
<form action="" method="post"   name="myform" id="myform">
<input type="hidden" name="action" value="doadd">
<input type="hidden" name="ls" value="<?php echo $ls;?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">*项目名称</td>
        <td><input name="title" type="text" id="title" valType="chk" remoteurl="<?php echo site_url("swj_project/chk_pro_title");?>" maxlength="50" required /></td>
    </tr>
    <tr >
        <td class="tableleft">可视权限</td>
        <td>
        <input type="radio" name="isshow" value="1" checked /> 公开
        &nbsp;
        <input type="radio" name="isshow" value="0" /> 不公开
        
        </td>
    </tr>
    <tr>
        <td valign="top" class="tableleft">申报模板</td>
        <td>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="padding:0px; margin:0px;border-left:0px; border-right:0px;">名称</td>
<td style="padding:0px; margin:0px;border-left:0px; border-right:0px;">能上报的用户组</td>
</tr>
<?php
foreach($tmlist as $k=>$v){
	if(count($v["sublist"])>0){
?>
    <tr>
    <td style="padding:0px; margin:0px;border-left:0px; border-right:0px;">
		<?php echo "　&nbsp;".$v["title"];?>
    </td>
    <td style="padding:0px; margin:0px;border-left:0px; border-right:0px;">

    </td>
    </tr>
    <?php
	foreach($v["sublist"] as $kk=>$vv){
	?>
    <tr>
    <td style="padding:0px; margin:0px;border-left:0px; border-right:0px;">
		<?php
		echo "　&nbsp;├";
        echo '<input type="checkbox" onClick="selpro($(this))" title="'.$vv["title"].'" guid="'.$vv["guid"].'" name="project_id[]" checked id="project_id_'.$vv["guid"].'" value="'.$vv["guid"].'" /> '.$v["title"];		
		echo $vv["title"];
		?>		
    </td>    			
   	<td style="padding:0px; margin:0px;border-left:0px; border-right:0px;">
		<?php
		//读会员分组
        foreach($usertype as $uv){
            echo "&nbsp;<input type='checkbox'  name='usertype_".$vv["guid"]."[]' checked id='usertype_".$uv["id"]."' value='".$uv["id"]."'/>";
            echo $uv["name"];
            echo "　";
        }
        ?>    
    </td>
    </tr>         
<?php
	}
	?>
<?php
	}
	else{
?>
    <tr>
    <td style="border-left:0px; border-right:0px;padding:0px; margin:0px;">
       <input type="checkbox" onClick="selpro($(this))" title='<?php echo $v["title"];?>' checked guid="<?php echo $v["guid"];?>" name="project_id[]" id="project_id_<?php echo $v["guid"];?>" value="<?php echo $v["guid"];?>" /> <?php echo $v["title"];?>
    </td>
    <td style="border-left:0px; border-right:0px; padding:0px; margin:0px;">
		<?php
        foreach($usertype as $uv){
            echo "&nbsp;<input type='checkbox'   name='usertype_".$v["guid"]."[]' checked id='usertype_".$uv["id"]."' value='".$uv["id"]."'/>";
            echo $uv["name"];
            echo "　";
        }
        ?>     
    </td>
    </tr>
<?php		
		
	}
}
?>

</table>
<span style="color:red;">有勾选的申报模板，才会保存会员组，否则不作保存</span>
      </td>
    </tr>
    <tr>
      <td class="tableleft">*申报时段</td>
      <td>   
      <input type="text" style="width:90px;"  valType="date" placeholder="开始日期" name="starttime" id="starttime" required  onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#starttime').focus();}})" value=""/>
　　　　　　
      至
　　　　　　
      <input type="text" style="width:90px;" valType="date" placeholder="结束日期" name="endtime" id="endtime" required onClick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#endtime').focus();}})"  value=""/>
      </td>
    </tr>
    <!-- 隐藏审核时段2016年4月25日11:52:16 -->
    <tr style="display:none;">
      <td class="tableleft">*审核时段</td>
      <td><input type="text" style="width:90px;"  placeholder="开始日期" name="check_starttime" id="check_starttime" onClick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#check_starttime').focus();}})" value=""/>
　　　　　　
      至
　　　　　　
  <input type="text" style="width:90px;"  placeholder="结束日期" name="check_endtime" id="check_endtime" onClick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#check_endtime').focus();}})"  value=""/></td>
    </tr>
    <tr>
        <td valign="top" class="tableleft">项目简介</td>
        <td>
            <textarea style="width:300px; height:100px;" id="content" name="content" placeholder="描述"></textarea>
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
			var isok = true;
			//判断有无漏打勾，如漏了
			$("input[name='project_id[]']").each(function(index, element) {
                guid = $(this).attr("guid");
				if($(this)[0].checked && $("input[name='usertype_"+guid+"[]']:checked").length==0){
					layer.msg($(this).attr("title")+' 没有选用户组');
					$("input[name='usertype_"+guid+"[]']").each(function(index, element) {
                        $(this).focus();
						isok = false;						
						return false;
                    });
					return false;		
				}
            });
			if(!isok){
				//判断模板有无选好
				return false;	
			}
			//判断申报时间必须跟审核时间不重叠
			/*starttime = $("#starttime").val()==""?0:$("#starttime").val();
			endtime = $("#endtime").val()==""?0:$("#endtime").val();
			check_starttime = $("#check_starttime").val()==""?0:$("#check_starttime").val();
			check_endtime = $("#check_endtime").val()==""?0:$("#check_endtime").val();
			if(starttime!=0 && endtime!=0 && check_starttime!=0 && check_endtime!=0){
				starttime = gettime(starttime);
				endtime = gettime(endtime+" 23:59:59");
				check_starttime = gettime(check_starttime);
				check_endtime = gettime(check_endtime+" 23:59:59");
				tmp = 0;
				if(starttime>endtime){
					tmp = endtime;
					endtime = starttime;
					endtime = tmp;					
				}
				if(check_starttime>check_endtime){
					tmp = check_endtime;
					check_endtime = check_starttime;
					check_endtime = tmp;					
				}
				if(starttime>check_endtime || endtime < check_starttime){
					
				}
				else{
					layer.msg("申报时间段跟审核时间段有重叠，请更正");	
					return false;
				}
			}*/
	
			
			
			
			if($("#myform").Valid() == false || !$("#myform").Valid()) {
				return false ;
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
                uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
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
