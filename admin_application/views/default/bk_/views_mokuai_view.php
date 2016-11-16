<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>浏览任务</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Css/style.css" />   
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/assets/js/jquery-1.8.1.min.js"></script> 	
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/assets/js/bui-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Js/validate/validator.js"></script>
<link href="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
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

#div_butongguo{
	width:700px;
	height:420px;	
	left:45%;
}

#div_tongguo{
	width:700px;
	height:420px;	
	left:45%;
}

    </style>
    <script>
        is_need=0;
        //表格样式兼容 2014年8月21日 10:35:05 xie
        $(function(){
            autoScreenWidth();
            $(window).resize(function() {
              autoScreenWidth();
            });
            function autoScreenWidth(){
                 if(document.body.offsetWidth<=1000){
                     
                     if(document.all){ 
                         //ie
                       var width='20%';
                        }else{
                          //not ie  
                        var width='23%';
                      }
                      $(".autowidth_head td:lt(2)").attr('width',width);
                 }else{
                     $(".autowidth_head td:lt(2)").attr('width','20%');
                       $(".autowidth td:lt(2)").attr('width','20%');
                 }
            }
        });
    </script>
</head>
<body>
    
<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo $url;?>">任务列表</a>
</div>
<form action="<?php echo site_url("mokuai/edit");?>" method="post" enctype="multipart/form-data" class="definewidth m2"  name="myform" id="myform" >
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $info['id'];?>">
<table class="table table-bordered m10">
<tr><td><table width="100%">
  <tr>
    <td style="border-left:0px; padding-left:0px; margin:0px;">
    <table class="table table-bordered  m10">
      <tr>
        <td class="tableleft">操作</td>
        <td>
 <?php
//开发者专用
if(role_id()=="8"){
?>
          <?php
    switch($info["mokuai_status"])
    {
        case "5"://显示接收
    ?>
          <input type="button" name="opt" onClick="jieshou()"  class="btn" value="接收任务"/>
          <?php
        break;
		case "20":	//显示 开始			
    ?>
          <input type="button" name="opt2" onClick="kaishi();this.disabled='disabled'" class="btn btn-success" value="开始工作"/>
          :其他任务会自动暂停
          <?php 	
		break;
		case "6":
	?>
          <input type="button" name="opt_zhanting" id="opt_zhanting" onClick="zhanting();this.disabled='disabled'" class="btn btn-warning" value="暂停工作"/>
          <!--<input type="button" name="opt_wancheng" id="opt_wancheng" onClick="wancheng()" class="btn btn-success" value="完成工作"/>-->
            <input type="button" name="opt_wancheng" data-toggle="modal" id="opt_wancheng"  data-target="#div_wancheng"   class="btn btn-success" value="完成工作"/>
            
<!--要写在这附近才行 开始-->
<!--点击完成弹出编辑器-->
<div class="modal hide fade" id="div_wancheng"  >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">完成任务描述</h4>
      </div>
      <div class="modal-body">
   <textarea style="width:100%; height:120px" id="wancheng_content" name="wancheng_content" placeholder=""></textarea>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" id="wancheng_submit" class="btn btn-primary" onClick="return wancheng()">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
    
    
	
                
function wancheng(){
//	BUI.Message.Confirm('确认完成工作？',function(){		
		document.getElementById("opt_wancheng").disabled='disabled';
		document.getElementById("opt_zhanting").disabled='disabled';
                
    var content = $("#wancheng_content").val();
	content = content.replace(/\s/ig,"");
	if(content==""){
		BUI.Message.Alert("请输入原因。" ,'error');	
		return false;
	}else{
                //不让再点击
            document.getElementById("wancheng_submit").disabled='disabled';
            $("#wancheng_submit").text("请稍等。。。");   
        }
                
		$.ajax({
			   type: "POST",
			   url: "<?php echo site_url('mokuai/status_switch');?>" ,
			   data: {"id":"<?php echo $info['id'];?>","statusid":"8","content":$("#wancheng_content").val()},
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){				   
				   if(msg.resultcode<0){
					   BUI.Message.Alert('没有权限执行此操作','error');
					   return false ; 
					}else if(msg.resultcode == 0 ){
						BUI.Message.Alert(msg.resultinfo.errmsg ,'error');						
						return false ;				
					}else{
						BUI.Message.Alert("工作完成，请关注测试结果。" ,'success');
						window.setTimeout("window.location.reload()","1000");
						return false;
					}
			   },
			   
			   beforeSend:function(){
				  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
			   },
			   error:function(a,b,c){
				   //alert(a);alert(b);alert(c);
				   BUI.Message.Alert('服务器繁忙请稍后','error');
			   }			  
			});	
			
//	},'question');		
	document.getElementById("opt_wancheng").disabled='';				
}    
    
    
    
    
    
//完成任务描述弹出的编辑器
KindEditor.ready(function(K) {
       window.editor = K.create('#wancheng_content',{
				width:'100%',
				height:'300px',
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
				uploadJson:"<?php echo site_url("editor/index");?>?action=mkview_upload&session=<?php echo session_id();?>"
						
       });
});
  </script>  
<!--要写在这附近才行 结束-->
  




            
          <?php		
		break;    
		case "7":	//显示 开始			
    ?>
          <input type="button" name="opt3" onClick="kaishi();this.disabled='disabled'" class="btn btn-success" value="重新开始"/>
          :其他任务会自动暂停
          <?php
		break;
	?>
    
    	<?php case "18": ?>
          <input type="button" name="opt_edit" onClick="kaishi();this.disabled='disabled'" class="btn btn-success" value="开始修改"/>
          :其他任务会自动暂停        
        <?php break;?>
          <?php
	}
}
?>
<?php
//测试人专用
if(role_id()=="10" && $info['mokuai_test_userid']==admin_id()){
?>
          <?php
    switch($info["mokuai_status"])
    {
        case "8"://显示接收
    ?>
          <!--<input type="button" name="opt_ceshi" id="opt_ceshi" onClick="ceshi();this.disabled='disabled'"  class="btn" value="开始测试"/>-->
          <input type="button" name="opt_ceshi" id="opt_ceshi" onClick="ceshi();this.disabled='disabled'"  class="btn" value="开始测试"/>
          <?php
        break;
		case "9":	//显示 开始			
    ?>
          <!--<input type="button" name="opt_tongguo" data-toggle="modal"  data-target="#div_tongguo" onClick="tongguo();this.disabled='disabled'"  class="btn btn-success" value="通过"/>-->
          <input type="button" name="opt_tongguo" data-toggle="modal"  data-target="#div_tongguo"  onclick="lock_zanting(1);"  class="btn btn-success" value="通过"/>

          <!--input type="button" name="opt_butongguo" onClick="butongguo();" class="btn btn-danger" value="不通过"/-->
          <a  class="btn btn-danger" data-toggle="modal"  data-target="#div_butongguo" onclick="lock_zanting(1);"  >不通过</a>
          <!--<input type="button" name="opt_test_zhanting" onClick="test_zhanting();this.disabled='disabled'" class="btn btn-warning" value="暂停测试"/>-->
          <input type="button" name="opt_test_zhanting" onClick="test_zhanting();" class="btn btn-warning" value="暂停测试"/>
          <?php 	
		break;		
        case "22"://暂停测试
    ?>
          <input type="button" name="opt_ceshi" id="opt_ceshi" onClick="ceshi();"  class="btn" value="开始测试"/>
          <?php
        break;    
    case "23"://任务完成做反测试
            ?>
            <a  class="btn btn-danger" data-toggle="modal"  data-target="#div_repeat_test">返测试</a>
         <?php
         break;
	}	
        ?>
          
 <?php         
}
?>

<?php
if(is_super_admin()){
?>
     <?php
    switch($info["mokuai_status"])
    {
        default://取消任务
    ?>
    <?php
		if($info["mokuai_status"]!="19")
		{
	?>
			<a  class="btn btn-danger" data-toggle="modal"  data-target="#div_quxiao">取消任务</a>
	<?php
		}
		?>
            <input type="button" name="opt_admindel" id="opt_admindel" onClick="admindel();"  class="btn" value="删除任务"/>
	<?php
		break;	
	?>                
    <?php
	}
	?>
    
    <?php
	if($info["mokuai_status"]==10 || $info["mokuai_status"]==23){
	?>
<input type="button" name="opt_admin_jieshu" id="opt_admin_jieshu" onClick="admin_jieshu();"  class="btn" value="正常结束"/>    
    <?php	
	}
	?>

<a  class="btn"  href="edit.shtml?id=<?php echo $info["id"];?>">任务修改</a>


<?php	
}
?>
</td>
      </tr>
      <tr>
        <td class="tableleft " >任务名称：</td>
        <td><?php echo $info["title"];?><span style="padding-left:10px; font-size:14px; line-height:150%; color:#900; font-weight:bold;">编号：<?php echo $info["id"];?></span></td>
      </tr>
      <tr>
        <td class="tableleft ">附件：</td>
        <td><?php
		if($info["fujian"]!=""){
			echo "&nbsp;<a href='/".$info["fujian"]."' target='_blank'>查看附件</a>";	
			//echo "&nbsp;<input type='checkbox' name='delfujian' id='delfujian' value='yes' />&nbsp;删除附件";
		}
		else{
			echo "没上传";
		}
		?></td>
      </tr>
      <tr>
        <td class="tableleft ">计划时段：</td>
        <td><?php echo date("Y-m-d H:i:s",$info["jihua_start_time"]);?>　至　<?php echo date("Y-m-d H:i:s",$info["jihua_end_time"]);?> 　计划时数　<?php echo round($info["jihua_hours"],2);?></td>
      </tr>
      
<?php
//测试通过 任务结束才能看
if($info["mokuai_status"]=="10" || $info["mokuai_status"]=="11")
{
?>      
      <tr>
        <td class="tableleft ">实际时段：</td>
        <td>
<?php echo date("Y-m-d H:i:s",$info["shiji_start_time"]);?>　至　<?php echo date("Y-m-d H:i:s",$info["shiji_end_time"]);?> 　实际时数　<?php echo round($info["shiji_hours"],2);?>        
        </td>
      </tr>
<?php
}
?>      
      
      <tr>
        <td class="tableleft ">开发者：</td>
        <td><?php echo $info["mokuai_username"];?> 　所属项目　<?php echo $info["protitle"];?></td>
      </tr>
      <tr>
        <td class="tableleft ">任务状态：</td>
        <td><?php echo $info["mokuai_status_ico"];?></td>
      </tr>
      <tr>
        <td class="tableleft ">紧急程度：</td>
        <td><?php echo $info["mokuai_jinji_ico"];?></td>
      </tr>
      <tr>
        <td class="tableleft ">属性：</td>
        <td><?php echo $info["mokuai_flag_name"];?></td>
      </tr>
      <tr>
        <td class="tableleft " id="desc">描述：</td>
        <td>
            <?php
             echo $info["content"];
            ?>
        </td>
      </tr>
      
      
      <tr>
        <td colspan="2" style="padding-left:0px; padding-top:0px;"><table width="100%">
          <tr>
			<td style="border-left:0px; padding-left:0px; margin:0px;"><table width="96%">
              <tr>
                <td colspan="4" bgcolor="#F2F2F2" style="background-color:#ccc;"><div style="text-align:center"><strong>开发时数累计</strong></div></td>
              </tr>
              <tr class="autowidth_head">
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
foreach($usertime_list as $v)
{
	$all_hours+=$v["userhours"];
	$all_sec+=$v["userseconds"];
        $beizhu = (strlen($v['beizhu'])>30)?mb_substr($v['beizhu'],0,24,'utf8').'...':$v['beizhu'];

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
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v["starttime"]).'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v['endtime']).'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.
		( round($v['userhours'],2) <= 0.01?$v['userseconds']."秒":round($v['userhours'],2).'小时').'</td>
		<td style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$beizhu.'</td>		
	</tr>
		';	
	$i++;	
}
echo '</table>';
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
            <td width="50%" style="border-left:0px;"><table width="96%">
              <tr>
                <td colspan="4" bgcolor="#F2F2F2" style="background-color:#ccc;"><div style="text-align:center"><strong>状态变更记录<?php echo  ($beizhu_total != 0)?"<a href='javascript:void(0);' onclick='show_renwu_detail_YouwBeiZhu()'>(有{$beizhu_total}条备注)</a>":'';?></strong></div></td>
              </tr>
             <tr class="autowidth_head">
                <td width="20%" style="background-color:#FCC">状态</td>
                <td width="20%"style="background-color:#FCC">操作人</td>
                <td width="20%"style="background-color:#FCC;">时间</td>
                <td style="background-color:#FCC">备注</td>
              </tr>
            </table>
              <div style="overflow:scroll; overflow-x:hidden; height:200px;">
                <?php
echo '<table width="100%">';
$i=1;
foreach($status_list as $v)
{
    $beizhu = (strlen($v['beizhu'])>1000)?mb_substr($v['beizhu'],0,30,'utf8').'...':$v['beizhu'];

	echo '
	<tr class="autowidth">
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$v["title"].'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$v['username'].'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v['createtime']).'</td>
		<td style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">上个状态:'.$v['pre_status_title'].'<br/>'.$beizhu.
($v["statusid"]=="19"?
'<a href="javascript:void(0);" onclick="showlog('.$v["id"].');">[查看]</a>':'').
            
            ($v["content"]!=""?
		"<a href=\"javascript:void(0);\" onclick=\"showbeizhu('".$v["id"]."');\">备注查看</a>":"")
            
		.'</td>
	</tr>
		';	
	$i++;	
}
echo '</table>';
?>
              </div></td>            
          </tr>
        </table></td>
      </tr>


      <tr>
        <td colspan="2" style="padding-left:0px; padding-top:0px;">
        <table width="100%">
          <tr>
			<td style="border-left:0px; padding-left:0px; margin:0px;"><table width="96%">
              <tr>
                <td colspan="4" bgcolor="#F2F2F2" style="background-color:#ccc;"><div style="text-align:center"><strong>测试时数累计<?php echo  ($beizhu_total_ceshi != 0)?"<a href='javascript:void(0);' onclick='show_renwu_detail_ceshiYouwBeiZhu()'>(有{$beizhu_total_ceshi}条备注)</a>":'';?></strong></div></td>
              </tr>
             <tr class="autowidth_head">
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
$all_hours = 0;
$all_sec = 0;
foreach($test_usertime_list as $v)
{
	$all_hours+=$v["userhours"];
	$all_sec+=$v["userseconds"];
          $beizhu = (strlen($v['beizhu'])>30)?mb_substr($v['beizhu'],0,24,'utf8').'...':$v['beizhu'];
	echo '
	<tr class="autowidth">
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v["starttime"]).'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v['endtime']).'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.
		($v['userhours']<0.01?$v['userseconds']."秒":round($v['userhours'],2)).'</td>		
		<td style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.
		$beizhu.
		($v["content"]!=""?
		"<a href=\"javascript:void(0);\" onclick=\"showbutongguo('".$v["id"]."');\">查看</a>":"")
		.'</td>		
	</tr>
		';	
	$i++;	
}
echo '</table>';

?>
              </div>
<?php
if($all_sec>0)
{
?>
              合计测试时数：<?php echo $all_hours<0.01?$all_sec."秒":round($all_hours,2)."小时";?>
              <?php
}
?></td>          
            <td width="50%" valign="top" style="border-left:0px;">
            
<?php
//测试完成、任务结束才能看
//if($info["mokuai_status"]==10 || $info["mokuai_status"]==11){
?>
<table width="96%" style=" border-bottom:1px #DDD solid; border-right:1px #DDD solid;">
              <tr>
                <td colspan="2" bgcolor="#F2F2F2" style="background-color:#ccc;"><div style="text-align:center"><strong>时数对比</strong></div></td>
              </tr>
<?php
$all_hours = $all_kaifa_hours+$all_edit_hours; // $kaifa_hours+$ceshi_hours;
$all_sec = $all_kaifa_sec+$all_edit_sec;
?>              
              <tr>
                <td width="20%">开发用时
                
                </td>
                <td >
<div class="progress progress-success progress-striped">
    <?php
        if(!$all_kaifa_hours){
            $width = 0;
            $show_width  = 0;
        }else{
           $width = ceil($all_kaifa_hours/$all_hours*100);
           $show_width = (round($all_kaifa_hours/$all_hours*100,1))."%";
        }
    ?>
  <div class="bar" style="width: <?php echo $width;?>%">
  <span style="color:#666666"><?php echo $show_width;?></span>
    </div>
  
   <!--<div class="bar" style="width: <?php //echo ceil($all_kaifa_hours/$all_hours*100);?>%">
 <span style="color:#666666"><?php // echo (round($all_kaifa_hours/$all_hours*100,1))."%";?></span>
  <!--</div>-->
</div>              
                </td>
              </tr>
              <tr>
                <td>测试修改用时</td>
                <td>
               
<div class="progress progress-danger progress-striped">
      <?php
        if(!$all_edit_hours){
            $width = 0;
            $show_width  = 0;
        }else{
           $width = ceil($all_edit_hours/$all_hours*100);
           $show_width = (round($all_edit_hours/$all_hours*100,1))."%";
        }
    ?>
    
    
    
<div class="bar" style="width: <?php echo $width;?>%">
  <span style="color:#666666"><?php echo $show_width;?></span>
  </div>
    
<!--      <div class="bar" style="width: <?php //echo ceil($all_edit_hours/$all_hours*100);?>%">
   <span style="color:#666666"><?php //echo round(($all_edit_hours/$all_hours*100),1)."%";?></span>
  </div>-->
    
</div> 
                </td>
              </tr>
              <tr>
                <td valign="top">总用时</td>
                <td><table width="300" style=" padding:0px;border:0px;">
                  <tr>
                    <td width="5" style="padding:0px;border:0px;">&nbsp;</td>
                    <td width="20" style="padding:0px;border:0px;">&nbsp;</td>
                    <td style="padding:0px;border:0px;"> 
<?php
if(round($all_hours,2)==0){
    echo $all_sec."秒";
}else{
   echo round($all_hours,2)."小时";
}
?>                    
                     </td>
                  </tr>
                </table><br/>
                  <table width="300" style=" padding:0px;border:0px;"><tr>
<td width="5" style="padding:0px;border:0px;">&nbsp;</td>
<td width="20" style="padding:0px;border:0px;">

<div class="progress progress-success progress-striped">
  <div class="bar" style="width: 100%"></div>
</div>  
</td><td style="padding:0px;border:0px;">
<?php

if(round($all_kaifa_hours,2)==0){
    echo $all_kaifa_sec."秒";
}else{
   echo round($all_kaifa_hours,2)."小时";
}

?>
</td></tr></table>


<table width="300" style="padding:0px;padding:0px;border:0px;"><tr>
<td width="5" style="padding:0px;border:0px;">&nbsp;</td>
<td width="20" style="padding:0px;border:0px;">

<div class="progress progress-danger progress-striped">
  <div class="bar" style="width: 100%"></div>
</div>   
</td><td style="padding:0px;border:0px;">
<?php
if(round($all_edit_hours,2)==0){
    echo $all_edit_sec."秒";
}else{
   echo round($all_edit_hours,2)."小时";
}

?>
</td></tr></table>


                
                </td>
                </tr>
            </table>
                
                
<?php
//}
//else{
//	echo "";
//}
?>
        
            
            </td>            
          </tr>
        </table>
        </td>
      </tr>
      
      
      
      
      
      <!--新的一行 开始-->
      <?php if(is_super_admin()):?>
      
       <tr>
        <td colspan="2" style="padding-left:0px; padding-top:0px;"><table width="100%">
          <tr>
              
              <!--第一个td 开始-->
              
			<td style="border-left:0px; padding-left:0px; margin:0px;"><table width="96%">
              <tr>
                <td colspan="4" bgcolor="#F2F2F2" style="background-color:#ccc;"><div style="text-align:center"><strong>修改时数详细表</strong></div></td>
              </tr>
              <tr class="autowidth_head">
                <td width="20%" style="background-color:#CC9">修改人</td>
                <td width="20%"style="background-color:#CC9">旧的计划时数</td>
                <td width="20%"style="background-color:#CC9">新的计划时数</td>
                <td style="background-color:#CC9;">修改时间</td>
              </tr>
            </table>
              <div style="overflow:scroll; overflow-x:hidden; height:200px; ">
                <?php
                
echo '<table width="100%">';
$i = 0;
foreach($change_hours_detail_list as $v)
{
	echo '
	<tr  class="autowidth">
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$v["username"].'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$v["org_jihua_hours"].'</td>
		<td width="20%" style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.$v["new_jihua_hours"].'</td>
		<td style="border-bottom:dotted 1px #CCC;background-color:'.($i%2==1?'':'').'">'.date("Y-m-d H:i",$v["update_time"]).'</td>		
	</tr>
		';	
	$i++;	
}
echo '</table>';
?>
              </div>
            </td> 
<!--第一个td 结束-->


     <!--第二个td 开始-->
            <td width="50%" style="border-left:0px;">
               
              <table width="96%">
                <tr>
                  <td colspan="4" bgcolor="#F2F2F2" style="background-color:#ccc;"><div style="text-align:center"><strong>工作图表</strong></div></td>
                </tr>
                  <tr class="autowidth_head">
                    <td width="6%" class='headstyle'>日期</td>
                    <td width="40%" colspan='3'   class='headstyle'>全天</td>
                   </tr>
             </table>
                 <div style="overflow:scroll; overflow-x:hidden; height:200px; ">
                    <table  width="100%">
                           
                    <tr><td colspan="3">红色为开发者时间段,绿色为测试时间段，白色为没有任务，紫色为开发者在其它任务的工作时间段</td></tr>
                  
                        <?php // print_r($workdata);exit; ?>
                <?php foreach ($workdata as $date => $detailtime): ?>
                    <tr>
                        <td class='tdcenter' width="20%"><?php echo $date; ?></td>
                        <td >
                            <div class="morning">
                                <div class="progress progress-success progress-striped work">
                                        <?php
                                            $i = 1;
                                            $j = 1;
                                            $spaceWidthcolor = '';
                                            $spaceWidth = '';
                                            $space_time = "";
                                            $space2Width = 0;
                                            $is_have_work = false;
                                            // print_r($detailtime);
                                            $detailtimeCount = count($detailtime);
                                            foreach ($detailtime as $k => $v) :
                                                    $space_search_work[] = $v['H'];
                                              //00:00到任务开始的时间段
                                                    if ($j == 1 && strtotime($v['shiji_start_time']) - strtotime($date . ' 00:00:00') != 0) {
                                                            $j++;
                                                            $space_starttime = strtotime($date . ' 00:00:00');
                                                            $space_endtime = strtotime($v['shiji_start_time']);
                                                            $arr = get_space_work($space_starttime, $space_endtime, $info['id']);
                                                    //  print_r($arr);exit;
                                                            //找其它任务
                                                            if (!empty($arr)) {
                                                                    get_work_tiao($arr, $date . ' 00:00:00', $detailtime[$k]['shiji_start_time']);
                                                            } else {
                                                       //没有就显示空白
                                                                    $starttime = strtotime($date . ' 00:00:00');
                                                                    $endtime = strtotime($v['shiji_start_time']);
                                                                    $space_time = ($endtime - $starttime) / 3600;
                                                    //小于00:00:00点的就当是00:00:00点开始，所以为0 
                                                                    if ($space_time <= 0)
                                                                            $space_time = 0;
                                                                    $spaceWidth = floor($space_time / 24 * 100) . '%';
                                                                    $spaceWidthcolor = '#FFF';
                                                                    echo "<div  style='float:left;background-color:" . $spaceWidthcolor . ";width: " . $spaceWidth . "'>&nbsp;</div>";
                                                                    echo "<span style='display:none'>{$v['shiji_start_time']}----{$date} 00:00:00 </span>";
                                                            }
                                                    }

                                                    if ($i >= 2) {
                                                            $space_starttime = strtotime($detailtime[$k - 1]['shiji_end_time']);
                                                            $space_endtime = strtotime($detailtime[$k]['shiji_start_time']);
                                                            $arr = get_space_work($space_starttime, $space_endtime, $info['id']);
                                                      // print_r($arr);
                                                      //任务与任务之间的空白找其它任务
                                                            if (!empty($arr)) {
                                                                    get_work_tiao($arr, $detailtime[$k - 1]['shiji_end_time'], $detailtime[$k]['shiji_start_time']);
                                                            } else {
                                                                            //任务与任务之间的空白
                                            $space2_time = (strtotime($detailtime[$k]['shiji_start_time']) - strtotime($detailtime[$k - 1]['shiji_end_time'])) / 3600;
                                                                    $space2 = round($space2_time / 24 * 100, 1) . '%';
                                                                    $spaceWidthcolor = '#FFF';
                                                                    echo "<div  style='float:left;background-color:" . $spaceWidthcolor . ";width: " . $space2 . "'>&nbsp;</div>";
                                            echo "<span style='display:none'>{$detailtime[$k]['shiji_start_time']}----{$detailtime[$k - 1]['shiji_end_time']}</span>";
                                                            }
                                                    }
                                                      //任务时间
                                                    $width = (round($v['shiji_hours'] / 24 * 100,2)) . "%";
                                            echo "<div  style='float:left;background-color:" . (($v['gid'] == 8) ? "#FF4500" : "green") . ";width: " . $width . "'>&nbsp;</div>";
                                                    echo "<span style='display:none'>{$v['shiji_end_time']}----{$v['shiji_start_time']}</span>";

                                            //任务最后一个的结束时间到12点
                                                    if ($i == $detailtimeCount) {
                                                            $space_starttime = strtotime($detailtime[$k]['shiji_end_time']);
                                                            $space_endtime = strtotime($date . ' 23:59:59');
                                                            $arr = get_space_work($space_starttime, $space_endtime, $info['id']);
                                            //                           print_r($arr);
                                                    //任务与任务之间的空白找其它任务
                                                            if (!empty($arr)) {
                                                                    get_work_tiao($arr, $detailtime[$k]['shiji_end_time'], $date . ' 23:59:59');
                                                            }
                                                    }
                                                    $i++;
                                            endforeach;
                                            ?>
                                </div> 
                                <ul class="time" >
                                    <?php
                                        for($i=0;$i<=24;$i++){
                                            echo "<li class='one'>|$i</li>";
                                        }
                                    ?>
                                </ul>
                                
                            </div>
                              <!--这里本来有个下午的div-->
                        </td>
                    </tr>
                <?php endforeach;?>

                    </table>
                     
                   
                </div>
           
            </td> 
            <!--第二个td 结束-->
            
          </tr>
        </table></td>
      </tr>
      <?php endif;?>
      <!--新的一行 结束-->
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
    </table>
    
    </td>
    </tr>
</table></td></tr>
</table>
<script>
    /* .desc 高度处理*/
   var desc = document.getElementById('desc');
   desc.style.width='75px';
    
function jieshou(){
	BUI.Message.Confirm('表示您已对任务基本理解，并接受计划时段和时数。',function(){
		$.ajax({
			   type: "POST",
			   url: "<?php echo site_url('mokuai/status_switch');?>" ,
			   data: {"id":"<?php echo $info['id'];?>","statusid":"20"},
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){				   
				   if(msg.resultcode<0){
					   BUI.Message.Alert('没有权限执行此操作','error');
					   return false ; 
					}else if(msg.resultcode == 0 ){
						BUI.Message.Alert(msg.resultinfo.errmsg ,'error');						
						return false ;				
					}else{
						window.location.reload();
					}
			   },
			   beforeSend:function(){
				  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
			   },
			   error:function(a,b,c){
				   //alert(a);alert(b);alert(c);
				   BUI.Message.Alert('服务器繁忙请稍后','error');
			   }
			  
			});		
	},'question');	
	

}


function kaishi(){
		$.ajax({
			   type: "POST",
			   url: "<?php echo site_url('mokuai/status_switch');?>" ,
			   data: {"id":"<?php echo $info['id'];?>","statusid":"6"},
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){				   
				   if(msg.resultcode<0){
					   BUI.Message.Alert('没有权限执行此操作','error');
					   return false ; 
					}else if(msg.resultcode == 0 ){
						BUI.Message.Alert(msg.resultinfo.errmsg ,'error');						
						return false ;				
					}else{
						window.location.reload();
					}
			   },
			   beforeSend:function(){
				  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
			   },
			   error:function(a,b,c){
				   //alert(a);alert(b);alert(c);
				   BUI.Message.Alert('服务器繁忙请稍后','error');
			   }			  
			});			
}


function zhanting(){
		$.ajax({
			   type: "POST",
			   url: "<?php echo site_url('mokuai/status_switch');?>" ,
			   data: {"id":"<?php echo $info['id'];?>","statusid":"7"},
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){				   
				   if(msg.resultcode<0){
					   BUI.Message.Alert('没有权限执行此操作','error');
					   return false ; 
					}else if(msg.resultcode == 0 ){
						BUI.Message.Alert(msg.resultinfo.errmsg ,'error');						
						return false ;				
					}else{
						window.location.reload();
					}
			   },
			   
			   beforeSend:function(){
				  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
			   },
			   error:function(a,b,c){
				   //alert(a);alert(b);alert(c);
				   BUI.Message.Alert('服务器繁忙请稍后','error');
			   }			  
			});			
}

function ceshi(){
		$.ajax({
			   type: "POST",
			   url: "<?php echo site_url('mokuai/status_switch');?>" ,
			   data: {"id":"<?php echo $info['id'];?>","statusid":"9"},
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){				   
				   if(msg.resultcode<0){
					   BUI.Message.Alert('没有权限执行此操作','error');
					   return false ; 
					}else if(msg.resultcode == 0 ){
						BUI.Message.Alert(msg.resultinfo.errmsg ,'error');	
						document.getElementById("opt_ceshi").disabled="";					
						return false ;				
					}else{
						window.location.reload();
					}
			   },
			   
			   beforeSend:function(){
				  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
			   },
			   error:function(a,b,c){
				   //alert(a);alert(b);alert(c);
				   BUI.Message.Alert('服务器繁忙请稍后','error');
			   }			  
			});			
}


function test_zhanting(){
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/status_switch');?>" ,
		   data: {"id":"<?php echo $info['id'];?>","statusid":"22"},
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){				   
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');	
					//document.getElementById("opt_ceshi").disabled="";					
					return false ;				
				}else{
					window.location.reload();
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});			
}

//查看不通过日志
function showbutongguo(id){	
	$("#butongguo_content").val("");
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/mokuai_butongguo_view');?>" ,
		   data: {"id":id},
		   cache:false,
		   async:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){				   
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');										
					return false ;				
				}else{
					//window.location.reload();
					var list = msg.resultinfo.list;
					//alert(list[0]["content"]);
					$("#butongguo_content").html(list[0]["content"]).addClass('huanhang');					
					$('#div_butongguo_show').modal('show');
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});					
}





function showlog(id){
	$("#butongguo_content").val("");
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/mokuai_log_view');?>" ,
		   data: {"id":id},
		   cache:false,
		   async:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){				   
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');										
					return false ;				
				}else{
					//window.location.reload();
					var list = msg.resultinfo.list;
					//alert(list[0]["content"]);
					$("#log_content").html(list[0]["content"]);					
					$('#div_log_show').modal('show');
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});					
}





function showbeizhu(id){
	$("#butongguo_content").val("");
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/mokuai_log_view');?>" ,
		   data: {"id":id},
		   cache:false,
		   async:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){	
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');										
					return false ;				
				}else{
					//window.location.reload();
					var list = msg.resultinfo.list;
					//alert(list[0]["content"]);
					$("#log_content").html(list[0]["content"]).addClass('huanhang');					
					$('#div_log_show').modal('show');
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});					
}




//显示任务详细页中状态变更记录备注详情
function show_renwu_detail_YouwBeiZhu(){
           <?php
            $show_renwu_detail_str = '';
            $show_renwu_detail_str .="<table width='100%' >";
            foreach($show_renwu_detail_YouwBeiZhu as $k=>$v){
                $show_renwu_detail_str .="<tr>";
                $show_renwu_detail_str .= "<td width='20%'>操作人：{$v['username']}</td>";
                $show_renwu_detail_str .= "<td>操作时间： ".date('Y-m-d H:i:s',$v['createtime'])."</td>";
                $show_renwu_detail_str .="</tr>";
                $show_renwu_detail_str .= "<tr style='border-bottom:1px #000 solid;'>";
                $show_renwu_detail_str .= "<td colspan='2'><div class='huanhang'>备注：{$v['content']}</div></td>";
                $show_renwu_detail_str .= "</tr >";
            }
            $show_renwu_detail_str .="</table>";
        ?>
        var show_renwu_detail_str = "<?php echo $show_renwu_detail_str;?>";
        $("#log_content_detail").html(show_renwu_detail_str);					
        $('#div_log_show_detail').modal('show');

}




//显示测试时数累计详情
function show_renwu_detail_ceshiYouwBeiZhu(){
        <?php
            $show_ceshi_renwu_detail_str = '';
            $show_ceshi_renwu_detail_str .="<table width='100%' >";
            foreach($show_renwu_detail_ceshiYouwBeiZhu as $k1=>$v1){
                $show_ceshi_renwu_detail_str .="<tr>";
                $show_ceshi_renwu_detail_str .= "<td>开始时间： ".date('Y-m-d H:i:s',$v1['starttime'])."</td>";
                $show_ceshi_renwu_detail_str .= "<td>结束时间： ".date('Y-m-d H:i:s',$v1['endtime'])."</td>";
                $show_ceshi_renwu_detail_str .="</tr>";
                $show_ceshi_renwu_detail_str .= "<tr style='border-bottom:1px #000 solid;'>";
                $show_ceshi_renwu_detail_str .= "<td colspan='2'><div class='huanhang'>备注：{$v1['content']}</div></td>";
                $show_ceshi_renwu_detail_str .= "</tr >";
            }
            $show_ceshi_renwu_detail_str .="</table>";
        ?>
        var show_ceshi_renwu_detail_str = "<?php echo $show_ceshi_renwu_detail_str;?>";
        $("#ceshi_log_content_detail").html(show_ceshi_renwu_detail_str);					
        $('#div_ceshi_log_show_detail').modal('show');
}	


//通过，点击提交
function tonggou(){
var content = $("#test_content_tonggou").val();
	content = content.replace(/\s/ig,"");
	if(content==""){
		BUI.Message.Alert("请输入原因。" ,'error');	
		return false;
	}else{
           //不让再点击
            document.getElementById("test_tongguo").disabled='disabled';
            $("#test_tongguo").text("请稍等。。。"); 
        }
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/status_switch');?>" ,
		   data: {"id":"<?php echo $info['id'];?>","statusid":"10","content":$("#test_content_tonggou").val()},
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){				   
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');	
					//document.getElementById("opt_ceshi").disabled="";					
					return false ;				
				}else{
					window.location.reload();
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});			
}



//反测试点击提交
function repeat_test_submit(){
var content = $("#repeat_test_content").val();
	content = content.replace(/\s/ig,"");
	if(content==""){
		BUI.Message.Alert("请输入原因。" ,'error');	
		return false;
	}else{
           //不让再点击
            document.getElementById("repeat_test_submit").disabled='disabled';
            $("#repeat_test_submit").text("请稍等。。。"); 
        }
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/status_switch');?>" ,
		   data: {"id":"<?php echo $info['id'];?>","statusid":"12","content":$("#repeat_test_content").val()},
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){				   
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');	
					return false ;				
				}else{
                                    window.location.reload();
                                     // ceshi();
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});			
}

</script>

<!--弹出日志-->
<div class="modal hide fade" id="div_butongguo_show">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">日志</h4>
      </div>
      <div class="modal-body" id="butongguo_content">
   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>      
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--弹出的状态变更记录-->
<div class="modal hide fade" id="div_log_show">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">状态变更记录</h4>
      </div>
      <div class="modal-body" id="log_content">
   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>      
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--弹出的状态变更记录详细-->
<div class="modal hide fade" id="div_log_show_detail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">状态变更记录详细</h4>
      </div>
      <div class="modal-body" id="log_content_detail">
   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>      
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dial -->
</div>
<!--弹出的测试时数累计详细-->
<div class="modal hide fade" id="div_ceshi_log_show_detail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">状态变更记录详细</h4>
      </div>
      <div class="modal-body" id="ceshi_log_content_detail">
   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>      
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dial -->
</div>        
        


        
</form>

<?php
if(is_super_admin()){
//管理员删除任务
?>
<div class="modal hide fade" id="div_quxiao">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">取消原因</h4>
      </div>
      <div class="modal-body">
<label>
<input type="checkbox" name="admin_delusertime" id="admin_delusertime"  value="yes"/>保留时数
</label>
   <textarea style="width:100%; height:120px" id="test_admin_cancel" name="test_admin_cancel" placeholder=""></textarea>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" onClick="return fun_admin_cancel()">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
function admin_jieshu(){
	BUI.Message.Confirm('确认结束任务？',function(){		
		//document.getElementById("opt_admindel").disabled='disabled';
		$.ajax({
			   type: "POST",
			   url: "<?php echo site_url('mokuai/status_switch');?>" ,
			   data: {"id":"<?php echo $info['id'];?>","statusid":"11"},
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){				   
				   if(msg.resultcode<0){
                                       
					   BUI.Message.Alert('没有权限执行此操作','error');
					   return false ; 
					}else if(msg.resultcode == 0 ){
						BUI.Message.Alert(msg.resultinfo.errmsg ,'error');						
						return false ;				
					}else{
						BUI.Message.Alert("操作成功。" ,'success');
						//window.setTimeout("window.location.href='/admin.php/mokuai/lists.shtml'","1000");
						window.location.reload();
						return false;
					}
			   },
			   
			   beforeSend:function(){
				  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
			   },
			   error: function(a, b, c) {
//                                alert(a);
//                                alert(b);
//                                alert(c); 
                                BUI.Message.Alert("服务器繁忙", 'error');
                            }		  
			});	
			
	},'question');
		
}
function admindel(){

	BUI.Message.Confirm('确认删除任务？该任务和时数将永久删除。',function(){		
		//document.getElementById("opt_admindel").disabled='disabled';
		$.ajax({
			   type: "POST",
			   url: "<?php echo site_url('mokuai/status_switch');?>" ,
			   data: {"id":"<?php echo $info['id'];?>","statusid":"9999999"},
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){				   
				   if(msg.resultcode<0){
					   BUI.Message.Alert('没有权限执行此操作','error');
					   return false ; 
					}else if(msg.resultcode == 0 ){
						BUI.Message.Alert(msg.resultinfo.errmsg ,'error');						
						return false ;				
					}else{
						BUI.Message.Alert("任务删除成功。" ,'success');
						window.setTimeout("window.location.href='/admin.php/mokuai/lists.shtml'","1000");
						return false;
					}
			   },
			   
			   beforeSend:function(){
				  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
			   },
			   error:function(a,b,c){
				   //alert(a);alert(b);alert(c);
				   BUI.Message.Alert('服务器繁忙请稍后','error');
			   }			  
			});	
			
	},'question');
	
	//document.getElementById("opt_admindel").disabled='';
}

function fun_admin_cancel(){
	var content = $("#test_admin_cancel").val();
	content = content.replace(/\s/ig,"");
	if(content==""){
		BUI.Message.Alert("请输入原因。" ,'error');	
		return false;
	}
	//alert($("#admin_delusertime").attr("checked")=="checked"?"no":"yes");
	//return false;
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/status_switch');?>" ,
		   data: {"id":"<?php echo $info['id'];?>","statusid":"19","saveshishu":($("#admin_delusertime").attr("checked")=="checked"?"yes":"no"),"content":$("#test_admin_cancel").val()},
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){				   
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');										
					return false ;				
				}else{
					window.location.reload();
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});			
	
}



</script>
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
       window.editor = K.create('#test_admin_cancel',{
				width:'100%',
				height:'300px',
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
				uploadJson:"<?php echo site_url("editor/index");?>?action=mkview_upload&session=<?php echo session_id();?>"
						
       });
});
</script>
<?php		
}
?>
<?php //if($info["mokuai_status"]=="9"){ ?>

<!--弹出编辑器-->
<div class="modal hide fade" id="div_butongguo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"  onclick="lock_zanting(0);">×</button>
        <h4 class="modal-title">不通过测试的原因</h4>
      </div>
      <div class="modal-body">
   <textarea style="width:100%; height:120px" id="test_content" name="test_content" placeholder=""></textarea>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="lock_zanting(0);"  >关闭</button>
        <button type="button" id='test_butonggou' class="btn btn-primary" onClick="return butonggou()">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--弹出编辑器-->
<div class="modal hide fade" id="div_tongguo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"  onclick="lock_zanting(0);">×</button>
        <h4 class="modal-title">通过测试描述</h4>
      </div>
      <div class="modal-body">
   <textarea style="width:100%; height:120px" id="test_content_tonggou" name="test_content_tonggou" placeholder=""></textarea>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="lock_zanting(0);" >关闭</button>
        <button type="button" id="test_tongguo" class="btn btn-primary" onClick="return tonggou()">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!--弹出编辑器-->
<div class="modal hide fade" id="div_repeat_test">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">返测试的原因</h4>
      </div>
      <div class="modal-body">
   <textarea style="width:100%; height:120px" id="repeat_test_content" name="repeat_test_content" placeholder=""></textarea>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" id='repeat_test_submit' class="btn btn-primary" onClick="return repeat_test_submit()">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?><?php echo APPPATH?>views/static/Js/kindeditor/lang/zh_CN.js"></script>




  <script>
    //不通过弹出的编辑器
KindEditor.ready(function(K) {
       window.editor = K.create('#test_content',{
				width:'100%',
				height:'300px',
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
				uploadJson:"<?php echo site_url("editor/index");?>?action=mkview_upload&session=<?php echo session_id();?>"
						
       });
});

//反测试弹出的编辑器
KindEditor.ready(function(K) {
       window.editor = K.create('#repeat_test_content',{
				width:'100%',
				height:'300px',
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
				uploadJson:"<?php echo site_url("editor/index");?>?action=mkview_upload&session=<?php echo session_id();?>"
						
       });
});


//通过弹出的编辑器
KindEditor.ready(function(K) {
       window.editor = K.create('#test_content_tonggou',{
				width:'100%',
				height:'300px',
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
				uploadJson:"<?php echo site_url("editor/index");?>?action=mkview_upload&session=<?php echo session_id();?>"
						
       });
});



//不通过，点击提交
function butonggou(){

	var content = $("#test_content").val();
	content = content.replace(/\s/ig,"");
	if(content==""){
		BUI.Message.Alert("请输入原因。" ,'error');	
		return false;
	}else{
            //不让再点击
            document.getElementById("test_butonggou").disabled='disabled';
            $("#test_butonggou").text("请稍等。。。");
        }
	//alert($("#test_content").val());
	//return false;
	$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('mokuai/status_switch');?>" ,
		   data: {"id":"<?php echo $info['id'];?>","statusid":"18","content":$("#test_content").val()},
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){				   
			   if(msg.resultcode<0){
				   BUI.Message.Alert('没有权限执行此操作','error');
				   return false ; 
				}else if(msg.resultcode == 0 ){
					BUI.Message.Alert(msg.resultinfo.errmsg ,'error');										
					return false ;				
				}else{
					window.location.reload();
				}
		   },
		   
		   beforeSend:function(){
			  $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
		   },
		   error:function(a,b,c){
			   //alert(a);alert(b);alert(c);
			   BUI.Message.Alert('服务器繁忙请稍后','error');
		   }			  
		});			
	
}

 //先锁定，不用系统主动暂停
 function lock_zanting(value){
 is_need = 1;
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('mokuai/status_switch');?>" ,
            data: {"id":"<?php echo $info['id'];?>","statusid":"13","is_lock":value},
            cache:false,
            dataType:"json"
            });	
 }
 
  $('.modal-backdrop').live('click',function(){
        if(is_need==1){
            lock_zanting(0);  
            is_need = 0;
        }
         
  });
</script>
<?php //} ?>
</body>
</html>