<?php 
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>编辑电商园区或基地</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/js/bui-min.js"></script>        
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/js/config-min.js"></script>   
   <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/uploadfile/jquery.uploadify-3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.css"/>
    
<script src="/home/views/static/js/autocomplete/jquery.autocompleter.js" type="text/javascript"></script>
<link rel="stylesheet" href="/home/views/static/js/autocomplete/jquery.autocompleter.css">

   <script type="text/javascript">
    $(function(){
        //上传资料
        $('#yq_ziliao').uploadify({
            'auto'     : false,//关闭自动上传
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : '/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.swf',
            'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
            'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText' : '选择文件',//设置按钮文本
            'multi'    : true,//允许同时上传多张图片
            'uploadLimit' : 10,//一次最多只允许上传10张图片
            'fileTypeDesc' : 'All Files',//只允许上传图像
            'fileTypeExts' : '*.gif; *.jpg; *.png;*.doc;*.pdf;*.rar;*.xls;*.xlsx',//限制允许上传的图片后缀
            'fileSizeLimit' : '2048KB',//限制上传的图片不得超过200KB 
            'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
                //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('.zliao_img_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='yq_ziliao[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
            },
            'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数                 
                   
            },
            'onError' : function (event, ID, fileObj, errorObj) {
               if (errorObj.type === "File Size"){
                alert('超过文件上传大小限制（2M）！');
                return;
               }
               alert(errorObj.type + ', Error: ' + errorObj.info);
            },  
            // Put your options here
        });
        //上传剪影
        $('#yq_jianying').uploadify({
            'auto'     : false,//关闭自动上传
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : '/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.swf',
            'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
            'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText' : '选择文件',//设置按钮文本
            'multi'    : true,//允许同时上传多张图片
            'uploadLimit' : 10,//一次最多只允许上传10张图片
            'fileTypeDesc' : 'All Files',//只允许上传图像
            'fileTypeExts' : '*.gif; *.jpg; *.png;*.bmp',//限制允许上传的图片后缀
            'fileSizeLimit' : '2048KB',//限制上传的图片不得超过200KB 
            'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
                //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('.jingying_img_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'><img style='width:80px;height:60px;' src='/"+obj.filesrc+"' /></a>&nbsp;&nbsp;<span tp='"+obj.id+"' style='line-height:60px;cursor:pointer;'  class='img_ul_li_a'>删除</span>&nbsp;&nbsp;<input type='hidden' name='yq_jianying[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                               {time: 1000}
                               );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
            },
            'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数                 
                
            },
            'onError' : function (event, ID, fileObj, errorObj) {
               if (errorObj.type === "File Size"){
                alert('超过文件上传大小限制（2M）！');
                return;
               }
               alert(errorObj.type + ', Error: ' + errorObj.info);
            },  
            // Put your options here
        });
        
        //监听删除附件事件
        $("body").on("click", ".img_ul_li_a", function(){
            // alert($(this).attr("tp"));
            var obj = $(this);
            var id  = obj.attr("tp");
            //ajax 删除附件
            $.ajax({ 
                    type: "get", 
                    url: "<?php echo site_url("swj_upload/delfj");?>", 
                    data:{'id':id},
                    cache:false, 
                    async:true, 
                    success: function(data){ 
                        obj.parent('li').remove();
                        layer.msg('删除成功',
                        {time: 1000}
                        );      
                    } 
                });
        });
        $('.cqcondition').click(function(){
            tp_val = $(this).val();
            if (tp_val == '1') {
                $('.cq_year_box').hide();
            } else {
                $('.cq_year_box').show();
            }
        });


        //下拉搜索菜单
        $('#company').autocompleter({
            source: '<?php echo site_url("Swj_dsyq/getcompany");?>',
            template: '{{ label }}',
            // show hint
            hint:false,
            // abort source if empty field
            empty:false,       
            // max results
            limit: 10,
            highlightMatches: true,
            changeWhenSelect:false,  
            cache:false,      
            callback: function (value, index,selected) {
                
                $('#company').val(selected.label);
                $('#company_id').val(value);
            }        
        });
    });

    //执行上传方法
    function upl(id){
        //$("#"+id).uploadify('settings', 'formData', {'test':''}); 
        $("#"+id).uploadify('upload');
    }
   </script>
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
        .hint{color: red;}
        .tips_lk{color: #ccc;font-size: 12px;}
        .img_ul{padding: 0;border: 0;margin: 0;}
        .img_ul li{float: left;}
        .clear{clear: both;}
    </style>
    <link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />   
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("swj_dsyq/index");?>">园区或基地列表</a>
</div>
<form action="<?php echo site_url("swj_dsyq/edit");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $id;?>">
<?php if ($audit == 30) {?>
<div class="tips">
数据有误，原因：<?php echo strip_tags($audit_idea);?>
</div>
<?php }?>
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">园区或基地名称<span class="hint">*</span></td>
        <td>
            <input type="text" name="name" style="width:333px;" value="<?php echo $name;?>" required/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审核状态：<?php echo $audit_status;?>&nbsp;&nbsp;&nbsp;&nbsp;
                审核时间：<?php if($audit_time) echo $audit_time;else echo '无';?>
        </td>
    </tr>
    <tr>
      <td class="tableleft">所在单位：</td>
      <td>
     <span style=" position: relative;">
      <input name="company"　type="text" placeholder="请使用营业执照上的全称" id="company"
       value="<?php echo $companyname?>"     
      autocomplete="off"
      style="width:333px;border:1px solid #c3c3d6;line-height:20px;border-radius:4px;padding:1px 4px;"
      >
     </span>
      <input type="hidden" name="company_id" id="company_id" value="<?php echo $uid?>" />
      </td>
    </tr>
    <tr>
        <td class="tableleft">所属镇区<span class="hint">*</span></td>
        <td>
            <select name="town_id" required>
                <option value="">请选择</option>
                <?php
                foreach($town as $v){
                    if ($v["id"] == $town_id) {
                        echo "<option value='".$v["id"]."' selected>";
                    } else {
                        echo "<option value='".$v["id"]."'>";
                    }
                    echo $v["name"];
                    echo "</option>\n"; 
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">经营面积<span class="hint">*</span></td>
        <td>
            <input type="text" name="jymj" value="<?php echo $jymj;?>" required 
            onkeyup="if(isNaN(value))execCommand('undo')"  onafterpaste="if(isNaN(value))execCommand('undo')"/>&nbsp;平方米<span class="tips_lk">(保留两位小数)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">进驻企业总数<span class="hint">*</span></td>
        <td>
           <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" name="jzqynum" value="<?php echo $jzqynum;?>">&nbsp;个
        </td>
    </tr>
    <tr>
        <td class="tableleft">产权情况<span class="hint">*</span></td>
        <td>
           <input type="radio" class="cqcondition" name="cqcondition" value="1" <?php if($cqcondition==1) echo 'checked';?>>企业所有&nbsp;
           <input type="radio" class="cqcondition" name="cqcondition" value="2" <?php if($cqcondition==2) echo 'checked';?>>企业租赁&nbsp;
           <span class="cq_year_box" <?php if($cqcondition==1) echo 'style="display:none;"';?>><input type="text" style="margin-bottom:0;" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" name="cq_year" id="cq_year" value="<?php echo $cq_year;?>">&nbsp;年</span>
        </td>
    </tr>
     <tr>
        <td class="tableleft">建筑面积</td>
        <td>
            <input type="text" name="jzmj" value="<?php echo $jzmj;?>"
            onkeyup="if(isNaN(value))execCommand('undo')"  onafterpaste="if(isNaN(value))execCommand('undo')"/>&nbsp;平方米<span class="tips_lk">(保留两位小数)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">占地面积</td>
        <td>
            <input type="text" name="zdmj" value="<?php echo $zdmj;?>"
            onkeyup="if(isNaN(value))execCommand('undo')"  onafterpaste="if(isNaN(value))execCommand('undo')"/>&nbsp;平方米<span class="tips_lk">(保留两位小数)</span>
        </td>
    </tr>
     <tr>
        <td class="tableleft">电商企业数</td>
        <td>
           <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" name="dsqynum" value="<?php echo $dsqynum;?>">&nbsp;个
        </td>
    </tr>
    <tr>
        <td class="tableleft">当地扶持政策</td>
        <td>
           <input type="text" name="haspolicy" style="width:333px;" value="<?php echo $haspolicy;?>">&nbsp;<span class="tips_lk">(何年何月获得当地扶持政策,无则不填)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">所获称号</td>
        <td>
           <input type="text" name="chenghao" style="width:333px;" value="<?php echo $chenghao;?>">&nbsp;<span class="tips_lk">(何年何月获得市级、省级、国家级称号,无则不填)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">联系人姓名</td>
        <td>
           <input type="text" name="linkman_name" value="<?php echo $linkman_name;?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">联系人电话</td>
        <td>
           <input type="text" name="linkman_phone" value="<?php echo $linkman_phone;?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">联系人职务</td>
        <td>
           <input type="text" name="linkman_work" value="<?php echo $linkman_work;?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地地址</td>
        <td>
           <input type="text" name="yq_addr" style="width:333px;" value="<?php echo $yq_addr;?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地官方网站</td>
        <td>
           <input type="text" name="yq_website" style="width:333px;" value="<?php echo $yq_website;?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地简介</td>
        <td>
           <textarea style="width:100%; height:150px" id="yq_describe" name="yq_describe" placeholder="简介"><?php echo $yq_describe;?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft">相关资料</td>
        <td>
            <div class="zliao_img_box">
                <ul class="img_ul">
                     <?php foreach ($yq_ziliao_file as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='yq_ziliao[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="yq_ziliao" id="yq_ziliao" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">每个文件大小不超过2M,一次最多只允许上传10个文件</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('yq_ziliao')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、bmp、doc、docx、xls、xlsx、ppt、pptx、rar、zip)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地剪影</td>
        <td>
            <div class="jingying_img_box container">
                <ul class="gallery1 img_ul">
                    <?php foreach ($yq_jianying_file as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><img src="/<?php echo $value["filesrc"];?>" style="width:80px;height:60px;" alt=""></a>&nbsp;&nbsp;
                            <span style='line-height:60px;cursor:pointer;' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</span>&nbsp;&nbsp;
                            <input type='hidden' name='yq_jianying[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="yq_jianying" id="yq_jianying" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">每个文件大小不超过2M,一次最多只允许上传10个文件</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('yq_jianying')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式:gif、jpg、png、bmp)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
           <textarea style="width:300px; height:100px;" id="beizhu" name="beizhu" placeholder="备注"><?php echo $beizhu;?></textarea>
        </td>
    </tr>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="button" class="btn btn-primary" value="提交" id="btn-submit">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("swj_dsyq/index");?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
var beizhu;//备注
var yq_describe;//园区或基地简介
$(function () {
        //通过审核,将值设为1，提交表单   
        $('#btn-submit').click(function(){
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            var cqcondition = $("input[name='cqcondition']:checked").val();
            if (!cqcondition) {
                alert('请选择产权情况');
                return false;
            } else if (cqcondition == 2&&$('#cq_year').val()=='') {
                alert('请输入租赁年数');
                $('#cq_year').focus();
                return false;
            }
            myform.submit();
        });
});

KindEditor.ready(function(K) {
   // beizhu = K.create('#beizhu',{
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
   yq_describe = K.create('#yq_describe',{
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
}); 
</script>
