<?php 
if (! defined('BASEPATH')) {
  exit('Access Denied');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>电商园区或基地</title>
	<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />

   <script type="text/javascript" src="/home/views/static/js/bui-min.js"></script>        
   <script type="text/javascript" src="/home/views/static/js/config-min.js"></script>  
 
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
  <input type="text" name="name" id="name"class="abc input-default" placeholder="园区或基地名称" value="">&nbsp;&nbsp;
  <input type="text" name="qyname" id="qyname"class="abc input-default" placeholder="所属企业" value="">&nbsp;&nbsp;
  <select name="town_id" id="town_id">
      <option value="">请选择镇区</option>
      <?php
      foreach($town as $v){
          echo "<option value='".$v["id"]."'>";
          echo $v["name"];
          echo "</option>\n"; 
      }
      ?>
  </select>
  <select id="audit" name="audit">
    <option value="">审核状态</option>
    <option value="10" <?php if($audit==10) echo 'selected';?>>未审核</option>
    <option value="20" <?php if($audit==20) echo 'selected';?>>审核通过</option>
    <option value="30" <?php if($audit==30) echo 'selected';?>>审核不通过</option>
  </select>&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp; 
    <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("swj_dsyq/add");?>">新增园区或基地</a>&nbsp;&nbsp; 
    <a  class="btn btn-primary" id="importexcel" href="<?php echo site_url("swj_dsyq/importExcel");?>">导入excel</a>&nbsp;&nbsp;
</div>
<div class="form-inline definewidth m10">
  <a  class="btn btn-info" id="pldel" href="javascript:void(0)" onclick="pldel();">
      批量删除<span class="glyphicon glyphicon-plus"></span>
  </a>&nbsp;&nbsp;
  <select id="pl_audit_status" name="pl_audit_status">
    <option value="">设置审核</option>
    <option value="10">未审核</option>
    <option value="20">审核通过</option>
    <option value="30">审核不通过</option>
  </select>&nbsp;&nbsp;
   <a  class="btn btn-info" id="plshenhe" href="javascript:void(0)" onclick="plshenhe();">
      批量审核<span class="glyphicon glyphicon-plus"></span>
  </a>&nbsp;&nbsp;
  <input type="text" name="pl_audit_idea" style="width:189px;" id="pl_audit_idea"class="abc input-default" placeholder="审核意见（不通过时必填）" value="">&nbsp;&nbsp;
</div>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th style="vertical-align: middle;width:17px;"><input type="checkbox" name="piliang[]" id="piliang" value="-1" /></th>
        <th style="width:60px;">状态</th>
        <th>名称</th>
        <th style="width:330px;">所属企业</th>
        <th style="width:70px;">所属镇区</th>
        <th style="width:70px;">操作</th>
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
  var url="<?php echo site_url("swj_dsyq/index");?>?inajax=1";
  var data_ = {
    'page':page,
    'time':<?php echo time();?>,
    'name':$('#name').val(),
    'qyname':$('#qyname').val(),
    'town_id':$('#town_id').val(),
    'audit':$('#audit').val(),
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
        window.location.href = '<?php echo site_url('home/login');?>';
        return false ;   
      }else{
        if (list.length <= 0) {
          shtml = '<tr><td style="text-align:center;" colspan="6">暂无信息</td></tr>';
        }
        for(var i in list){
          var temp_status = list[i]['audit_status'];//审核状态0未审核，10审核通过，20审核不通过
          shtml+='<tr>';
          shtml+='<td><input type="checkbox" name="piliang[]" value="'+list[i].id+'"></td>';
          shtml+='<td>'+list[i]['audit_status']+'</td>';
          shtml+='<td>'+list[i]['name']+'</td>';
          shtml+='<td>'+list[i]['qyname']+'</td>';
          shtml+='<td>'+list[i]['zqname']+'</td>';
          if (temp_status == '未审核') {
             shtml+='<td><a title="审核" href="<?php echo site_url('swj_dsyq/check');?>?id='+list[i].id+'" class="icon-check"></a>';
             shtml+='&nbsp;&nbsp;<a title="编辑" href="javascript:edit_dsyq('+list[i].id+')" class="icon-edit"></a>';
             shtml+='&nbsp;&nbsp;<a title="删除" href="javascript:del_dsyq('+list[i].id+')" class="icon-remove"></a></td>';
          } else if (temp_status == '审核通过') {
             shtml+='<td><a title="审核" href="<?php echo site_url('swj_dsyq/check');?>?id='+list[i].id+'" class="icon-check"></a>';
             shtml+='&nbsp;&nbsp;<a title="编辑" href="javascript:edit_dsyq('+list[i].id+')" class="icon-edit"></a>';
             // shtml+='&nbsp;&nbsp;<a title="查看" href="<?php echo site_url('swj_dsyq/look');?>?id='+list[i].id+'" class="icon-list-alt"></a>';
             shtml+='&nbsp;&nbsp;<a title="删除" href="javascript:del_dsyq('+list[i].id+')" class="icon-remove"></a></td>';
          } else if (temp_status == '审核不通过') {
             shtml+='<td><a title="审核" href="<?php echo site_url('swj_dsyq/check');?>?id='+list[i].id+'" class="icon-check"></a>';
             shtml+='&nbsp;&nbsp;<a title="编辑" href="javascript:edit_dsyq('+list[i].id+')" class="icon-edit"></a>';
             shtml+='&nbsp;&nbsp;<a title="删除" href="javascript:del_dsyq('+list[i].id+')" class="icon-remove"></a></td>';
          } else {
             shtml+='<td><a title="查看" href="<?php echo site_url('swj_dsyq/look');?>?id='+list[i].id+'" class="icon-list-alt"></a>';
             shtml+='&nbsp;&nbsp;<a title="编辑" href="javascript:edit_dsyq('+list[i].id+')" class="icon-edit"></a></td>';
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

//批量删除
function pldel() {
  var ids = getIds();
  /*alert(ids);
  return;*/
  if (!ids) {
    alert('请选择要删除的园区或基地');
    return false;
  }
  if (confirm('您确定要删除选中的园区或基地吗？删除后将不能恢复')) {
    window.location.href = "<?php echo site_url('swj_dsyq/del');?>?id=" + ids;
  }
}
//批量审核
function plshenhe() {
  var audit_status = $('#pl_audit_status').val();//审核状态
  var pl_audit_idea = $('#pl_audit_idea').val();//批量审核意见
  var ids = getIds();//批量操作的id
  if (!ids) {
    alert('请选择要审核的园区或基地');
    return false;
  }
  if (!audit_status) {
    alert("请选择审核的操作!");
    $('#pl_audit_status').focus();
    return false;
  }
  if (audit_status=='30'&&pl_audit_idea=="") {
    alert("请填写审核意见!");
    $('#pl_audit_idea').focus();
    return false;
  }
  // return false;
  if (confirm('您确定要审核选中的园区或基地吗？')) {
    window.location.href = "<?php echo site_url('swj_dsyq/shenhe');?>?pl_audit_idea="+pl_audit_idea+"&audit_status="+audit_status+"&id=" + ids;
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
function del_dsyq(id) {
  if (confirm('您确定要删除该园区或基地吗，删除后将不能恢复')) {
    window.location.href = "<?php echo site_url('swj_dsyq/del');?>?id="+id;
  }
}
//编辑
function edit_dsyq(id) {
  window.location.href = "<?php echo site_url('swj_dsyq/edit');?>?id="+id;
}
</script>

