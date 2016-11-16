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
<form action="" method="post"   name="myform" id="myform">
<input type="hidden" name="id" value="<?php echo $model["id"];?>">
<input type="hidden" name="backurl" value="<?php echo $ls;?>">

<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">*项目名称</td>
        <td><input name="title" type="text" id="title" valType="chk" remoteurl="<?php echo site_url("swj_project/chk_pro_title");?>?id=<?php echo $model["id"];?>" value="<?php echo $model["title"];?>" maxlength="50" required /></td>
    </tr>
    <tr >
        <td class="tableleft">可视权限</td>
        <td>
        <input type="radio" name="isshow" value="1" <?php echo $model["isshow"]?"checked":"";?> /> 公开
        &nbsp;
        <input type="radio" name="isshow" value="0" <?php echo !$model["isshow"]?"checked":"";?>/> 不公开
        
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
        echo '<input type="checkbox" onClick="selpro($(this))" title="'.$vv["title"].'" guid="'.$vv["guid"].'" name="project_id[]"  id="project_id_'.$vv["guid"].'" value="'.$vv["guid"].'"' ;
	  if(in_array($vv["guid"],$sel_project_tem)){
			echo "checked";
	  }	
		echo '/> '.$v["title"];		
		echo $vv["title"];
		?>		
    </td>    			
   	<td style="padding:0px; margin:0px;border-left:0px; border-right:0px;">
		<?php
		//读会员分组
        foreach($usertype as $uv){
            echo "&nbsp;<input type='checkbox'  name='usertype_".$vv["guid"]."[]' ";
		  	if(in_array($vv["guid"],$sel_project_tem)){
				if(in_array($uv["id"],explode(",",$sel_project_usertype[$vv["guid"]]))){
					echo "checked";
				}
		  	}
			echo " id='usertype_".$uv["id"]."' value='".$uv["id"]."'/>";
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
       <input type="checkbox" onClick="selpro($(this))" title='<?php echo $v["title"];?>' <?php
	  if(in_array($v["guid"],$sel_project_tem)){
			echo "checked";
	  }
	   ?> guid="<?php echo $v["guid"];?>" name="project_id[]" id="project_id_<?php echo $v["guid"];?>" value="<?php echo $v["guid"];?>" /> <?php echo $v["title"];?>
    </td>
    <td style="border-left:0px; border-right:0px; padding:0px; margin:0px;">
		<?php
        foreach($usertype as $uv){
            echo "&nbsp;<input type='checkbox'   name='usertype_".$v["guid"]."[]'  id='usertype_".$uv["id"]."' value='".$uv["id"]."'";
	  		if(in_array($v["guid"],$sel_project_tem)){
				if(in_array($uv["id"],explode(",",$sel_project_usertype[$v["guid"]]))){
					echo "checked";
				}
		  	}			
			echo "/>";
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
      <input type="text" style="width:90px;"  valType="date" placeholder="开始日期" name="starttime" id="starttime" required onClick="laydate()" value="<?php echo date("Y-m-d",$model["starttime"]);?>"/>
　　　　　　
      至
　　　　　　
      <input type="text" style="width:90px;" valType="date" placeholder="结束日期" name="endtime" id="endtime" required onClick="laydate()"  value="<?php echo date("Y-m-d",$model["endtime"]);?>"/>
      </td>
    </tr>
    <!-- 隐藏审核时段2016年4月25日11:52:46 -->
    <tr style="display:none;">
      <td class="tableleft">*审核时段</td>
      <td><input type="text" style="width:90px;" placeholder="开始日期" name="check_starttime" id="check_starttime"  onClick="laydate()" value="<?php echo date("Y-m-d",$model["check_starttime"]);?>"/>
　　　　　　
      至
　　　　　　
  <input type="text" style="width:90px;" placeholder="结束日期" name="check_endtime" id="check_endtime"  onClick="laydate()"  value="<?php echo date("Y-m-d",$model["check_endtime"]);?>"/></td>
    </tr>
    <tr>
        <td valign="top" class="tableleft">项目简介</td>
        <td>
            <textarea style="width:300px; height:100px;" id="content" name="content" placeholder="描述"><?php echo $model["content"];?></textarea>
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
			var isok = true;
			//chkpro_template()
			json_mb = "";//记录未保存前模板中间表
			$.ajax({
				url:"<?php echo site_url("swj_project/chkpro_template");?>",
				dataType: "json",
				data:{"proid":"<?php echo $id;?>"},
				type: "GET",			
				async:false,
				success: function(data){
					json_mb = data;
				},
				error:function(a,b,c){
					
				}
			});	
						
					
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
				if(!$(this)[0].checked){
					for(i=0;i<json_mb.length;i++){
						if(guid.toLowerCase() == String(json_mb[i]["guid"]).toLowerCase() && json_mb[i]["count"]>0){
							layer.msg($(this).attr("title")+" 已经有用户申报，不能取消选中，可以先删除申报再取消选中",{time: 5000});
							$(this)[0].checked = true;
							$(this).focus();
							isok = false;	
							break;
						}
					}
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
