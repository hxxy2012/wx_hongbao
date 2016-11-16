<?php 
if (! defined('BASEPATH')) {
  exit('Access Denied');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据上报审核</title>
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>   
 
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


    /**内容超出 出现滚动条 **/
    .bui-stdmod-body{
      overflow-x : hidden;
      overflow-y : auto;
    }
  </style>      
</head>

<body>
<div class="form-inline definewidth m20" > 
  <input type="text" name="lmname" id="lmname"class="abc input-default" placeholder="类目名称" value="">&nbsp;&nbsp;
  <input type="text" name="badw" id="badw"class="abc input-default" placeholder="上报单位" value="">&nbsp;&nbsp;
  <select id="audit_status" name="audit_status">
    <option value="">审核状态</option>
    <option value="0" <?php if($audit==10) echo 'selected';?>>未审</option>
    <option value="10">审核通过</option>
    <option value="20">审核不通过</option>
  </select>&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp; 
</div>
<div class="form-inline definewidth m10">
  <a  class="btn btn-info" id="pldel" href="javascript:void(0)" onclick="pldel();">
      批量删除<span class="glyphicon glyphicon-plus"></span>
  </a>&nbsp;&nbsp;
  <select id="pl_audit_status" name="pl_audit_status">
    <option value="">设置审核</option>
    <option value="0">未审</option>
    <option value="10">审核通过</option>
    <option value="20">审核不通过</option>
  </select>&nbsp;&nbsp;
   <a  class="btn btn-info" id="plshenhe" href="javascript:void(0)" onclick="plshenhe();">
      批量审核<span class="glyphicon glyphicon-plus"></span>
  </a>&nbsp;&nbsp;
  <input type="text" name="pl_audit_idea" style="width:189px;" id="pl_audit_idea"class="abc input-default" placeholder="审核意见（不通过时必填）" value="">&nbsp;&nbsp;
</div>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th style="vertical-align: middle;width:10px;"><input type="checkbox" name="piliang[]" id="piliang" value="-1" /></th>
        <th width="51">编号</th>
        <th width="75">审核状态</th>
        <th>类目</th>
        <th>上报单位</th>
        <th width="123">提交时间</th>
        <th width="59">操作</th>
    </tr>
    </thead>
  <tbody id="result_">
  </tbody> 
</table>
<div id="page_string" class="form-inline definewidth m10">
  
</div>
</body>
</html>
<script>
$(function () {
  common_request(1);
  //全选反选事件
  $('#piliang').click(function(){
    var obj = $(this);
    if (obj.attr("checked")) {  
        $(":checkbox").attr("checked", true);  
    } else {  
        $(":checkbox").attr("checked", false);  
    }  
  });
});
function common_request(page){
  var url="<?php echo site_url("swj_sjsbcx/index");?>?inajax=1";
  var data_ = {
    'page':page,
    'time':<?php echo time();?>,
    'action':'ajax_data',
    'lmname':$("#lmname").val(),
    'badw':$("#badw").val(),
    'audit_status':$("#audit_status").val(),
  };
  $.ajax({
       type: "POST",
       url: url ,
       data: data_,
       cache:false,
       dataType:"json",
     //  async:false,
       success: function(msg){
      var shtml = '' ;
      var list = msg.resultinfo.list;
      var message = msg.resultinfo.errmsg;
      if(msg.resultcode<0){
        alert("没有权限执行此操作");
        return false ;
      }else if(msg.resultcode == 0 ){
        var s = '<div class="alert alert-warning alert-dismissable"><strong>Tips!</strong>'+message+'</div> ' ;
        $("#result_").html(s);
        return false ;        
      }else{
        if (list.length <= 0) {
          shtml = '<tr><td style="text-align:center;" colspan="7">暂无信息</td></tr>';
        }
        for(var i in list){
          shtml+='<tr>';
          shtml+='<td><input type="checkbox" name="piliang[]" value="'+list[i].id+'"></td>'
          shtml+='<td>'+list[i].id+'</td>';
          shtml+='<td>'+list[i]['audit_status']+'</td>';
          shtml+='<td>'+list[i]['name']+'</td>';
          shtml+='<td>'+list[i]['company']+'</td>';
          shtml+='<td>'+list[i]['createtime']+'</td>';
          shtml+='<td><a title="审核" href="<?php echo site_url('swj_sjsbcx/check');?>?id='+list[i].id+'" class="icon-check"></a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="del_sp('+list[i].id+',this)" class="icon-remove"></a></td>';
          shtml+='</tr>';
        }
        $("#result_").html(shtml);
        
        $("#page_string").html(msg.resultinfo.obj);
      }
       },
       beforeSend:function(){
        $("#result_").html('<font color="red"><img src="<?php echo base_url();?>/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
       },
       error:function(){
         
       }
      
    });   
  

}
function ajax_data(page){
  common_request(page); 
}
//预览sql
function previewSql(id){
    var content = $("#preview_sql_"+id).html();
    var Overlay = BUI.Overlay
    var dialog = new Overlay.Dialog({
    title:'sql语句如下：',
    width:500,
    height:300,
    mask:true,
    buttons:[],
    bodyContent:'<div style="word-break:break-all;width:'+(500-40)+'px;height:'+(300-50)+'px;overflow:auto;">'+content+'</div>'
    });
    dialog.show();  
}
//删除审批
function del_sp(id, object) {
  if (confirm('您确定要删除该备案吗？')) {
    window.location.href = "<?php echo site_url('swj_sjsbcx/del');?>?id=" + id;
  }
}
//批量删除
function pldel() {
  var ids = getIds();
  /*alert(ids);
  return;*/
  if (!ids) {
    alert('请选择要删除的备案');
    return false;
  }
  if (confirm('您确定要删除选中的备案吗？')) {
    window.location.href = "<?php echo site_url('swj_sjsbcx/del');?>?id=" + ids;
  }
}
//批量审核
function plshenhe() {
  var audit_status = $('#pl_audit_status').val();//审核状态
  var pl_audit_idea = $('#pl_audit_idea').val();
  var ids = getIds();//批量操作的id
  if (!ids) {
    alert('请选择要审核的备案');
    return false;
  }
  if (!audit_status&&audit_status!=='0') {
    alert("请选择审核的操作!");
    $('#pl_audit_status').focus();
    return false;
  }
  if ((audit_status=='20')&&pl_audit_idea=="") {
    alert("请填写审核意见!");
    $('#pl_audit_idea').focus();
    return false;
  }
  // return false;
  if (confirm('您确定要审核选中的备案吗？')) {
    window.location.href = "<?php echo site_url('swj_sjsbcx/shenhe');?>?pl_audit_idea="+pl_audit_idea+"&audit_status="+audit_status+"&id=" + ids;
  }
}
//获取所有选中的复选框的id,以逗号分割
function getIds() {
  var ids = '';//初始化变量
  var flag = 1;//判断是否为第一次进入，1代表是
  var obj = document.getElementsByName('piliang[]'); //选择所有name="'test'"的对象，返回数组 
  for(var i = 0; i < obj.length; i++){
    //选中，且其值不为-1（不是点击的那个checkbox）
    if(obj[i].checked&&obj[i].value != -1) {
      if (flag == 1) {
        //第一次进入
        flag = 0;//改变其值
        ids = obj[i].value;//添加到ids变量中
      } else {
        ids = ids + ',' + obj[i].value;//如果选中且值不为-1，将value添加到变量ids中 
      }
    }
  }
  return ids;
}
</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>
