<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>提交资料</title>
    <meta charset="UTF-8">
   <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/validate/validator.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/layer/layer.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/js/admin.js"></script>
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
   <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/laydate/laydate.js"></script>   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/uploadfile/jquery.uploadify-3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.css"/>
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
        .tips{color:#ccc;font-size: 12px;}
        .img_ul{padding: 0;border: 0;margin: 0;}
        .img_ul li{float: left;}
        .clear{clear: both;}
    </style>
 <link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />   
<script type="text/javascript">
$(function() {
    //中山市电子商务专项资金申请表
    $('#fujian_shenqing').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
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
                            $('.fujian_shenqing_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_shenqing[]' value='"+obj.id+"'></li>");
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
           // if(img_id_upload.length>0)
           // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));

          // alert('上传成功');
          // window.location.reload();
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
    //活动剪影上传
    $('#fujian_zuzhi').uploadify({
            'auto'     : false,//关闭自动上传
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
            'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
            'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText' : '选择文件',//设置按钮文本
            'multi'    : false,//允许同时上传多张图片
            'uploadLimit' : 1,//一次最多只允许上传10张图片
            'fileTypeDesc' : 'All Files',//只允许上传图像
            'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip',//限制允许上传的图片后缀
            'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
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
                            // $('.fujian_zuzhi_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'><img style='width:80px;height:60px;' src='/"+obj.filesrc+"' /></a><br/><input type='radio' name='themejyid' value='"+obj.id+"'>封面图&nbsp;&nbsp;<span tp='"+obj.id+"' style='cursor:pointer;'  class='img_ul_li_a'>删除</span><input type='hidden' name='fujian_zuzhi[]' value='"+obj.id+"'></li>");
                            $('.fujian_zuzhi_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_zuzhi[]' value='"+obj.id+"'></li>");
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
                // alert(jy_arr);
                // window.location.reload();
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
    //营业执照复印件
    $('#fujian_yingye').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
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
                            $('.fujian_yingye_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_yingye[]' value='"+obj.id+"'></li>");
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
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
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
    //企业完税证明复印件
    $('#fujian_wanshui').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
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
                            $('.fujian_wanshui_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_wanshui[]' value='"+obj.id+"'></li>");
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
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
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
    //企业年度财务报告
    $('#fujian_caiwu').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
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
                            $('.fujian_caiwu_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_caiwu[]' value='"+obj.id+"'></li>");
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
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
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
    //申请报告
    $('#fujian_shenqingbaogao').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
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
                            $('.fujian_shenqingbaogao_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_shenqingbaogao[]' value='"+obj.id+"'></li>");
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
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
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
    //申请报告
    $('#ny_chenghao_fj_box').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
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
                            $('.ny_chenghao_fj_box_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='ny_chenghao_fj_box[]' value='"+obj.id+"'></li>");
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
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
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
});
    //执行上传方法
    function upl(id){
        //$("#"+id).uploadify('settings', 'formData', {'test':''}); 
        $("#"+id).uploadify('upload');
    }
</script>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("Swj_mime_project/index");?>">我的申报</a>
</div>
<form action="<?php echo site_url("Swj_mime_project/review");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doreview">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="sbid" value="<?php echo $sbid;?>">
<input type="hidden" name="checkstatus" id="checkstatus" value="">
<?php if ($checkstatus == '-30') {?>
<div class="tips">
复审不通过原因：<?php echo strip_tags($second_audit);?>
</div>
<?php }?>
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft" style="width:250px;">中山市电子商务专项资金申请表<span class="hint">*</span></td>
        <td>
           <div class="fujian_shenqing_box">
                <ul class="img_ul">
                    <?php if(is_array($fujian_shenqing)){?>
                     <?php foreach ($fujian_shenqing as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_shenqing[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_shenqing" id="fujian_shenqing" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_shenqing')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">企业组织机构代码<span class="hint">*</span></td>
        <td>
          <div class="fujian_zuzhi_box container">
               <ul class="gallery1 img_ul">
                <?php if(is_array($fujian_zuzhi)){?>
                  <?php foreach ($fujian_zuzhi as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_zuzhi[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                <?php }?>
               </ul>
               <div class="clear"></div>
          </div>
          <input type="file" name="fujian_zuzhi" id="fujian_zuzhi" />&nbsp;&nbsp;
          <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
          <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_zuzhi')"/><br>
           &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">营业执照复印件<span class="hint">*</span></td>
        <td>
           <div class="fujian_yingye_box">
              <ul class="img_ul">
                 <?php if(is_array($fujian_yingye)){?>
                   <?php foreach ($fujian_yingye as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_yingye[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
          <input type="file" name="fujian_yingye" id="fujian_yingye" />&nbsp;&nbsp;
          <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
          <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_yingye')"/><br>
          &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">企业完税证明复印件<span class="hint">*</span></td>
        <td>
           <div class="fujian_wanshui_box">
              <ul class="img_ul">
                <?php if(is_array($fujian_wanshui)){?>
                   <?php foreach ($fujian_wanshui as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_wanshui[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
          <input type="file" name="fujian_wanshui" id="fujian_wanshui" />&nbsp;&nbsp;
          <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
          <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_wanshui')"/><br>
          &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">企业年度财务报告<span class="hint">*</span></td>
        <td>
           <div class="fujian_caiwu_box">
              <ul class="img_ul">
                <?php if(is_array($fujian_caiwu)){?>
                   <?php foreach ($fujian_caiwu as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_caiwu[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
          <input type="file" name="fujian_caiwu" id="fujian_caiwu" />&nbsp;&nbsp;
          <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
          <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_caiwu')"/><br>
          &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">申请报告<span class="hint">*</span></td>
        <td>
           <div class="fujian_shenqingbaogao_box">
              <ul class="img_ul">
                <?php if(is_array($fujian_shenqingbaogao)){?>
                   <?php foreach ($fujian_shenqingbaogao as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_shenqingbaogao[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
          <input type="file" name="fujian_shenqingbaogao" id="fujian_shenqingbaogao" />&nbsp;&nbsp;
          <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
          <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_shenqingbaogao')"/><br>
          &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">当年所获电子商务评奖或称号<span class="hint">*</span></td>
        <td>
           <div class="ny_chenghao_fj_box">
              <ul class="img_ul">
                <?php if(is_array($ny_chenghao_fj)){?>
                   <?php foreach ($ny_chenghao_fj as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                          <input type='hidden' name='ny_chenghao_fj[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
          <input type="file" name="ny_chenghao_fj" id="ny_chenghao_fj" />&nbsp;&nbsp;
          <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
          <input type="button" value="上传" class="button button-danger" onClick="upl('ny_chenghao_fj')"/><br>
          &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="button" class="btn btn-primary" value="提交" id="pass">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/index");?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/lang/zh_CN.js"></script>
<script>
var second_beizhu;//备注
var description;//活动简介
var flag_submit = 1;//防止重复提交
$(function () {
        //提交表单   
    $('#pass').click(function(){
            if (flag_submit != 1) {
                alert('请不要重复提交表单！！');
                return false;
            }
            sq_length  =  document.getElementsByName('fujian_shenqing[]').length;
            zz_length  =  document.getElementsByName('fujian_zuzhi[]').length;
            yy_length  =  document.getElementsByName('fujian_yingye[]').length;
            ws_length  =  document.getElementsByName('fujian_wanshui[]').length;
            cw_length  =  document.getElementsByName('fujian_caiwu[]').length;
            sb_length  =  document.getElementsByName('fujian_shenqingbaogao[]').length;
            cf_length  =  document.getElementsByName('ny_chenghao_fj[]').length;
            //活动签到表
            flag_submit = 0;
            if (sq_length<=0) {
                layer.msg('请先上传中山市电子商务专项资金申请表！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (zz_length<=0) {
               layer.msg('请先上企业组织机构代码！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (yy_length<=0) {
                layer.msg('请先上传营业执照复印件！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (ws_length<=0) {
                layer.msg('请先上传企业完税证明复印件！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (cw_length<=0) {
                layer.msg('请先上传企业年度财务报告！！',{time: 1000});
                flag_submit = 1;
                return false;
            }
            if (sb_length<=0) {
                layer.msg('请先上传申请报告！！',{time: 1000});
                flag_submit = 1;
                return false;
            }  
            if (cf_length<=0) {
                layer.msg('请先上传当年所获电子商务评奖或称号！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                flag_submit = 1;
                return false ;
            }
           
            myform.submit();
    });

});
// alert(DateDiff('2016-1-12 00:00:00', '2016-1-22 00:00:00'));
//sDate1和sDate2是2006-12-18格式 计算天数差
function  DateDiff(sDate1,  sDate2){

       var  aDate,  oDate1,  oDate2,  iDays  
       aDate  =  sDate1.split("-")  
       oDate1  =  new  Date(aDate[1]  +  '-'  +  aDate[2]  +  '-'  +  aDate[0])    //转换为12-18-2006格式  
       aDate  =  sDate2.split("-")  
       oDate2  =  new  Date(aDate[1]  +  '-'  +  aDate[2]  +  '-'  +  aDate[0])  
       iDays  =  parseInt((oDate1  -  oDate2)  /  1000  /  60  /  60  /24)    //Math.abs把相差的毫秒数转换为天数  
       return  iDays  
}
//获取当前时间
function getNowFormatDate() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes()
            + seperator2 + date.getSeconds();
    return currentdate;
} 
//删除宣传海报
function delxchb(fjid, field) {
    if (confirm('您确定要删除该附件吗？删除后将不能恢复！！')) {
        var id = <?php echo $id;?>;//备案id
        window.location.href = "<?php echo site_url("Swj_hdba/delfujian");?>?bak_method=sup&field="+field+"&fjid=" + fjid + '&id=' + id;
    }
}  
KindEditor.ready(function(K) {
   description = K.create('#description',{
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
            uploadJson:"<?php echo site_url("Swj_hdba/upload");?>?session_id=<?php echo $sess["session_id"];?>"
   });
});
</script>