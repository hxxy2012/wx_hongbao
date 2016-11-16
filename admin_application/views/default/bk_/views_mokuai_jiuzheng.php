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
   <a  class="btn btn-primary" id="addnew" href="<?php echo $url;?>">任务列表</a>
</div>
<form action="<?php echo site_url("mokuai/jiuzheng");?>" method="post" enctype="multipart/form-data" class="definewidth m2"  name="myform" id="myform" >
<input type="hidden" name="action" value="dojiuzheng">
<input type="hidden" name="id" value="<?php echo $info['id'];?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">任务名称</td>
        <td><input type="text" style="width:300px;"  readonly  value="<?php echo $info["title"];?>"/></td>
    </tr>
      
      <tr>
        <td colspan="2" style="padding-left:0px; padding-top:0px;"><table width="100%">
          <tr>
			<td style="border-left:0px; padding-left:0px; margin:0px;"><table width="96%">
              <tr>
                <td colspan="5" bgcolor="#F2F2F2" style="background-color:#ccc;"><div style="text-align:center"><strong>开发时数累计</strong></div></td>
              </tr>
              <tr class="autowidth_head">
                <td width="20%" style="background-color:#CC9">ID</td>
                <td width="20%" style="background-color:#CC9">开始时间</td>
                <td width="20%"style="background-color:#CC9">结束时间</td>
                <td width="20%"style="background-color:#CC9">时数</td>
                <td style="background-color:#CC9;">备注</td>
              </tr>
            </table>
              <div style="overflow:scroll; overflow-x:hidden; height:200px; ">
                <?php
echo '<table width="100%">';
$i=1;
$all_kaifa_hours = 0;
$all_kaifa_sec = 0;

$all_edit_hours = 0;
$all_edit_sec = 0;

$all_hours = 0;//全部开发时间
$all_sec = 0;
$ids = array(); 
foreach($usertime_list as $v)
{
    $ids[]=$v['id'];
	$all_hours+=$v["userhours"];
	$all_sec+=$v["userseconds"];
	if($v["isedit"]=="1"){
		$all_edit_hours += $v["userhours"];
		$all_edit_sec += $v["userseconds"];
	}
	else{
		$all_kaifa_hours += $v["userhours"];
		$all_kaifa_sec += $v["userseconds"];
	}
	echo '
	<tr  class="autowidth">
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$v["id"].'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v["starttime"]).'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v['endtime']).'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.
		( round($v['userhours'],2) <= 0.01?$v['userseconds']."秒":round($v['userhours'],2).'小时').'</td>		
		<td style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$v['beizhu'].'</td>		
	</tr>
		';	
	$i++;	
}
echo '</table>';
echo '<input type="hidden" name="hidden_id" value='.implode(",",$ids).'>';
?>
              </div>
              <?php
if($all_sec>0)
{
?>
              合计开发时数：<?php echo $all_hours<0.01?$all_sec."秒":round($all_hours,2)."小时";?>
              <?php
}
?></td>          
                    
          </tr>
        </table></td>
      </tr>
    <tr></tr>
    <tr>
        <td class="tableleft">时段</td>
        <td>
<input type="text" name="start_time" id="start_time" class="Wdate" placeholder="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,readOnly:true})"  style="width:160px" readonly
required errMsg="请选择开始时间" tip="请选择开始时间"
value=""
>
至
<input type="text" name="end_time" id="end_time" class="Wdate" placeholder=""  onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,readOnly:true})"  style="width:160px" readonly
required errMsg="请选择结束时间" tip="请选择结束时间"
value=""
>
　要修改的开发时数累计ID
        <input name="jilu_id" type="text" required id="jilu_id" style="width:80px;" maxlength="8" errMsg="请输入ID" tip="请输入ID"
      valType="number" value=""
        />        
        <span style="color:#CCC">
        请输入ID
        </span>
        </td>
    </tr> 
    
  
    <tr>
        <td class="tableleft">纠正时间原因</td>
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
			else{
				
//				
//				//记录id
//				var pid_id = $("#pid").val();
//				
//				if(pid_id<=0){
//					BUI.Message.Alert("请选择所属项目",'error');
//					return false;
//				}								
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
