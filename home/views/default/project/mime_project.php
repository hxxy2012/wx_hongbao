<?php 
if (! defined('BASEPATH')) {
  exit('Access Denied');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的申报列表</title>
	<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="/home/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="/home/views/static/assets/js/config-min.js"></script>   
 
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
<script>
BUI.use('common/page');
</script> 
<body>
<!-- <div class="form-inline definewidth m20" > 
  <input type="text" name="projectname" id="projectname"class="abc input-default" placeholder="项目名称" value="">&nbsp;&nbsp;
  <input type="text" name="badw" id="badw"class="abc input-default" placeholder="上报单位" value="">&nbsp;&nbsp;
  <select id="status" name="status">
    <option value="">项目状态</option>
    <option value="0" <?php //if($status==0) echo 'selected';?>>未开始</option>
    <option value="1" <?php //if($status==1) echo 'selected';?>>进行中</option>
    <option value="2" <?php //if($status==2) echo 'selected';?>>已过期</option>
    <option value="3" <?php //if($status==3) echo 'selected';?>>已申报</option>
  </select>&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp; 
    <a  class="btn btn-primary page-action" data-id='ckwqxm' id="ckwqxm" title="查看往期项目"
     data-href='<?php //echo site_url("Swj_mime_project/index")."?status=2";?>' href="#">查看往期项目</a>&nbsp;&nbsp; 
    <a  class="btn btn-primary page-action" data-id='wdsb2' id="wdsb2" title="我的上报"
     data-href='<?php //echo site_url("Swj_mime_project/index");?>' href="#">我的上报</a>
</div> -->
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <!-- <th style="vertical-align: middle;"><input type="checkbox" name="piliang[]" id="piliang" value="-1" /></th> -->
        <th style="width:80px">状态</th>
        <th style="width:40px">编号</th>
        <th>项目名称</th>
        <th style="width:110px">申请时间</th>
        <th style="width:150px">操作</th>
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
 /* $('#piliang').click(function(){
    var obj = $(this);
    if (obj.attr("checked")) {  
        $(":checkbox").attr("checked", true);  
    } else {  
        $(":checkbox").attr("checked", false);  
    }  
  });*/
});
function common_request(page){
  var url="<?php echo site_url("Swj_mime_project/index");?>?inajax=1";
  var data_ = {
    'page':page,
    'time':<?php echo time();?>,
    'action':'ajax_data',
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
      }else if (msg.resultcode == 99999){
        alert('请先登录');
        top.location.href = '<?php echo site_url('home/login');?>';
        return false ;   
      }else{
        if (list.length <= 0) {
          shtml = '<tr><td style="text-align:center;" colspan="5">暂无信息</td></tr>';
        }
        for(var i in list){
          shtml+='<tr>';
          // shtml+='<td><input type="checkbox" name="piliang[]" value="'+list[i].id+'"></td>'
          shtml+='<td>'+list[i]['ckstext']+'</td>';
          shtml+='<td>'+list[i]['id']+'</td>';
          shtml+='<td><a href="<?php echo site_url('Swj_mime_project/look')?>?id='+list[i].project_id+'&sbid='+list[i].id+'">'+list[i]['title']+'</a></td>';
          shtml+='<td>'+list[i]['create_time']+'</td>';
          if (list[i]['ckstext'] == '未审核'&&!list[i]['auditpart']) {
            //为未审核且项目在申报时间段内的可以修改
            shtml+='<td><a title="编辑" href="<?php echo site_url('Swj_mime_project/edit');?>?id='+list[i].project_id+'&sbid='+list[i].id+'">编辑</a>&nbsp;&nbsp;';
            shtml+='<a title="打印" target="_blank" href="<?php echo site_url('Swj_mime_project/printData');?>?id='+list[i].project_id+'&sbid='+list[i].id+'">打印</a>&nbsp;&nbsp;</td>';
          } else if (list[i]['ckstext'] == '初审不通过') {
            //初审不通过的可以编辑
            shtml+='<td><a title="编辑" href="<?php echo site_url('Swj_mime_project/edit');?>?id='+list[i].project_id+'&sbid='+list[i].id+'">编辑</a>&nbsp;&nbsp;';
            shtml+='<a title="打印" target="_blank" href="<?php echo site_url('Swj_mime_project/printData');?>?id='+list[i].project_id+'&sbid='+list[i].id+'">打印</a>&nbsp;&nbsp;</td>';
          } else if (list[i]['ckstext'] == '给予扶持') {
            //初审不通过的可以编辑
            shtml+='<td><a title="补充资料" href="<?php echo site_url('Swj_mime_project/sup');?>?id='+list[i].project_id+'&sbid='+list[i].id+'">补充资料</a>&nbsp;&nbsp;';
            shtml+='<a title="打印" target="_blank" href="<?php echo site_url('Swj_mime_project/printData');?>?id='+list[i].project_id+'&sbid='+list[i].id+'">打印</a>&nbsp;&nbsp;</td>';
          } else {
            shtml+='<td><a title="打印" target="_blank" href="<?php echo site_url('Swj_mime_project/printData');?>?id='+list[i].project_id+'&sbid='+list[i].id+'">打印</a>&nbsp;&nbsp;</td>';
          }
          shtml+='</tr>';
        }
        $("#result_").html(shtml);
        
        $("#page_string").html(msg.resultinfo.obj);
      }
       },
       beforeSend:function(){
        $("#result_").html('<font color="red"><img src="/<?php echo APPPATH?>/views/static/Images/progressbar_microsoft.gif"></font>');
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
    window.location.href = "<?php echo site_url('Swj_mime_project/del');?>?id=" + id;
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
    window.location.href = "<?php echo site_url('Swj_mime_project/del');?>?id=" + ids;
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
    window.location.href = "<?php echo site_url('Swj_mime_project/shenhe');?>?pl_audit_idea="+pl_audit_idea+"&audit_status="+audit_status+"&id=" + ids;
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
