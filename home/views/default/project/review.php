
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
    flag_del =  new Array();//判断是否在页面删除了附件
    flag_del['fujian_shenqing'] = 0;//0为否1为是
    flag_del['fujian_zuzhi'] = 0;//0为否1为是
    flag_del['fujian_yingye'] = 0;//0为否1为是
    flag_del['fujian_wanshui'] = 0;//0为否1为是
    flag_del['fujian_caiwu'] = 0;//0为否1为是
    flag_del['fujian_shenqingbaogao'] = 0;//0为否1为是
    flag_del['ny_chenghao_fj'] = 0;//0为否1为是
    flag_del['fujian_htzm'] = 0;//0为否1为是
    flag_del['fujian_szzm'] = 0;//0为否1为是
    flag_del['fujian_xkba'] = 0;//0为否1为是
    flag_del['fujian_trzm'] = 0;//0为否1为是
    flag_del['fujian_lljy'] = 0;//0为否1为是
    flag_del['fujian_fwht'] = 0;//0为否1为是
    flag_del['fujian_fysz'] = 0;//0为否1为是
    flag_del['fujian_jzzm'] = 0;//0为否1为是
    flag_del['fujian_qyht'] = 0;//0为否1为是
    flag_del['fujian_ptzm'] = 0;//0为否1为是
    flag_del['fujian_otzm'] = 0;//0为否1为是
    flag_del['fujian_xszm'] = 0;//0为否1为是
    flag_del['fujian_htxy'] = 0;//0为否1为是
    flag_del['fujian_xmtr'] = 0;//0为否1为是
    flag_del['fujian_scfg'] = 0;//0为否1为是
    flag_del['fujian_ywzz'] = 0;//0为否1为是
    // 新增2016年7月14日09:56:40
    flag_del['fujian_zmcl'] = 0;//0为否1为是
    flag_del['fujian_mbjh'] = 0;//0为否1为是
    flag_del['fujian_wspt'] = 0;//0为否1为是
    flag_del['fujian_xmqk'] = 0;//0为否1为是
    flag_del['fujian_yyxse'] = 0;//0为否1为是
    flag_del['fujian_xmtr'] = 0;//0为否1为是
    flag_del['fujian_dlht'] = 0;//0为否1为是
    flag_del['fujian_htsf'] = 0;//0为否1为是
    status2 = 0;
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
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
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
                            $('.fujian_shenqing_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_shenqing' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_shenqing[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（10M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
    //企业组织机构代码
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
            'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
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
                            $('.fujian_zuzhi_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_zuzhi' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_zuzhi[]' value='"+obj.id+"'></li>");
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
                alert('超过文件上传大小限制（10M）！');
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
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
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
                            $('.fujian_yingye_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_yingye' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_yingye[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（10M）！');
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
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
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
                            $('.fujian_wanshui_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_wanshui' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_wanshui[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（10M）！');
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
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
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
                            $('.fujian_caiwu_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_caiwu' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_caiwu[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（10M）！');
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
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
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
                            $('.fujian_shenqingbaogao_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_shenqingbaogao' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_shenqingbaogao[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（10M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
    //当年所获电子商务评奖或称号
    $('#ny_chenghao_fj').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
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
                            $('.ny_chenghao_fj_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='ny_chenghao_fj' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='ny_chenghao_fj[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（10M）！');
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
        var uploadid = obj.attr("uploadid");//是哪个上传id
        // alert($("#"+uploadid).uploadify('settings','uploadLimit'));
        //ajax 删除附件
        $.ajax({ 
                type: "get", 
                url: "<?php echo site_url("swj_upload/delfj");?>", 
                data:{'id':id},
                cache:false, 
                async:true, 
                success: function(data){ 
                    obj.parent('li').remove();
                    //上传限制数+1
                    var uploadLimit = $("#"+uploadid).uploadify('settings','uploadLimit') + 1;
                    $("#"+uploadid).uploadify('settings','uploadLimit', uploadLimit)
                    // var swfuploadify = window['uploadify_file_upload'];
                    // $("#"+uploadid).uploadify('settings', 'uploadLimit', $("#"+uploadid).settings.uploadLimit + 1);
                    flag_del[uploadid] = 1;//已经删除了的可以上传
                    status2 = 1;
                    // alert(uploadid+','+flag_del[+uploadid]);
                    layer.msg('删除成功',
                    {time: 1000}
                    );      
                } 
            });
    });
});
    //执行上传方法
    function upl(id){
        // alert(flag_del[id]);
        //$("#"+id).uploadify('settings', 'formData', {'test':''}); 
        var content = '请先删除该附件';
        flag = 0;//判断是否可以上传是为1,0为否
        switch (id) {
            case "fujian_shenqing"://中山市电子商务专项资金申请表
                // content = "请先删除中山市电子商务专项资金申请表";
                flag = <?php if(isset($fujian_shenqing)&&is_array($fujian_shenqing)&&count($fujian_shenqing)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_shenqing"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_zuzhi"://企业组织机构代码
                // content = "请先删除企业组织机构代码";
                flag = <?php if(isset($fujian_zuzhi)&&is_array($fujian_zuzhi)&&count($fujian_zuzhi)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_zuzhi"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_yingye"://营业执照复印件
                // content = "请先删除营业执照及有关审批文件复印件";
                flag = <?php if(isset($fujian_yingye)&&is_array($fujian_yingye)&&count($fujian_yingye)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_yingye"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_wanshui"://企业完税证明复印件
                // content = "请先删除商事主体上一年度的完税证明文件复印件";
                flag = <?php if(isset($fujian_wanshui)&&is_array($fujian_wanshui)&&count($fujian_wanshui)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_wanshui"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_caiwu"://企业年度财务报告
                // content = "请先删除商事主体上年度财务报表";
                flag = <?php if(isset($fujian_caiwu)&&is_array($fujian_caiwu)&&count($fujian_caiwu)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_caiwu"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_shenqingbaogao"://申请报告
                // content = "请先删除商事主体负责人身份证复印件";
                flag = <?php if(isset($fujian_shenqingbaogao)&&is_array($fujian_shenqingbaogao)&&count($fujian_shenqingbaogao)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_shenqingbaogao"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "ny_chenghao_fj"://当年所获电子商务评奖或称号
                // content = "请先删除当年所获电子商务评奖或称号";
                flag = <?php if(isset($ny_chenghao_fj)&&is_array($ny_chenghao_fj)&&count($ny_chenghao_fj)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                // alert(flag_del["ny_chenghao_fj"]);
                if (flag_del["ny_chenghao_fj"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_htzm":
                // content = "请先删除开展服务合同证明";
                flag = <?php if(isset($fujian_htzm)&&is_array($fujian_htzm)&&count($fujian_htzm)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_htzm"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_szzm":
                // content = "请先删除费用收支证明";
                flag = <?php if(isset($fujian_szzm)&&is_array($fujian_szzm)&&count($fujian_szzm)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_szzm"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_xkba":
                // content = "请先删除网站增值电信业务许可证或ICP备案证复印件";
                flag = <?php if(isset($fujian_xkba)&&is_array($fujian_xkba)&&count($fujian_xkba)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_xkba"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_trzm":
                // content = "请先删除平台建设投入证明";
                flag = <?php if(isset($fujian_trzm)&&is_array($fujian_trzm)&&count($fujian_trzm)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_trzm"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_lljy":
                // content = "请先删除网站后台流量和交易额截图";
                flag = <?php if(isset($fujian_lljy)&&is_array($fujian_lljy)&&count($fujian_lljy)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_lljy"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_fwht":
                // content = "请先删除开展服务合同";
                flag = <?php if(isset($fujian_fwht)&&is_array($fujian_fwht)&&count($fujian_fwht)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_fwht"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_fysz":
                // content = "请先删除费用收支证明";
                flag = <?php if(isset($fujian_fysz)&&is_array($fujian_fysz)&&count($fujian_fysz)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_fysz"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_jzzm":
                // content = "请先删除园区土地及建筑物产权证明文件";
                flag = <?php if(isset($fujian_jzzm)&&is_array($fujian_jzzm)&&count($fujian_jzzm)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_jzzm"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_qyht":
                // content = "请先删除园区相关企业名称和简介、合同";
                flag = <?php if(isset($fujian_qyht)&&is_array($fujian_qyht)&&count($fujian_qyht)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_qyht"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_ptzm":
                // content = "请先删除园区配套设施的投入证明";
                flag = <?php if(isset($fujian_ptzm)&&is_array($fujian_ptzm)&&count($fujian_ptzm)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_ptzm"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_otzm":
                // content = "请先删除其他相关证明材料";
                flag = <?php if(isset($fujian_otzm)&&is_array($fujian_otzm)&&count($fujian_otzm)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_otzm"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_xszm":
                // content = "请先删除第三方平台出具销售额证明";
                flag = <?php if(isset($fujian_xszm)&&is_array($fujian_xszm)&&count($fujian_xszm)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_xszm"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_htxy":
                // content = "请先删除与委托方签订代理合同或协议";
                flag = <?php if(isset($fujian_htxy)&&is_array($fujian_htxy)&&count($fujian_htxy)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_htxy"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_xmtr":
                // content = "请先删除项目投入证明";
                flag = <?php if(isset($fujian_xmtr)&&is_array($fujian_xmtr)&&count($fujian_xmtr)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_xmtr"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_scfg":
                // content = "请先删除市场覆盖证明";
                flag = <?php if(isset($fujian_scfg)&&is_array($fujian_scfg)&&count($fujian_scfg)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_scfg"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_ywzz":
                // content = "请先删除业务增长证明";
                flag = <?php if(isset($fujian_ywzz)&&is_array($fujian_ywzz)&&count($fujian_ywzz)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_ywzz"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_zmcl":
                // content = "请先删除运营主体每年开展活动证明材料";
                flag = <?php if(isset($fujian_zmcl)&&is_array($fujian_zmcl)&&count($fujian_zmcl)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_zmcl"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_mbjh":
                // content = "请先删除平台三年以上的建设目标和工作计划";
                flag = <?php if(isset($fujian_mbjh)&&is_array($fujian_mbjh)&&count($fujian_mbjh)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_mbjh"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_wspt":
                // content = "请先删除完善平台管理服务功能和组织活动的证明材料";
                flag = <?php if(isset($fujian_wspt)&&is_array($fujian_wspt)&&count($fujian_wspt)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_wspt"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_xmqk":
                // content = "请先删除项目基本情况介绍证明材料";
                flag = <?php if(isset($fujian_xmqk)&&is_array($fujian_xmqk)&&count($fujian_xmqk)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_xmqk"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_yyxse":
                // content = "请先删除运营销售额的后台数据截图";
                flag = <?php if(isset($fujian_yyxse)&&is_array($fujian_yyxse)&&count($fujian_yyxse)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_yyxse"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_xmtr":
                // content = "请先删除项目建设投入的证明材料";
                flag = <?php if(isset($fujian_xmtr)&&is_array($fujian_xmtr)&&count($fujian_xmtr)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_xmtr"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_dlht":
                // content = "请先删除与委托方的代理合同或协议";
                flag = <?php if(isset($fujian_dlht)&&is_array($fujian_dlht)&&count($fujian_dlht)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_dlht"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            case "fujian_htsf":
                // content = "请先删除服务合同、服务收费票据证明材料";
                flag = <?php if(isset($fujian_htsf)&&is_array($fujian_htsf)&&count($fujian_htsf)>0)echo 0;else echo 1;?> + 0;//附件上传限制（申请表）
                if (flag_del["fujian_htsf"] == 1) {
                    flag = 1;//代表可以上传
                }
                break;
            default:
                content = '请先删除该附件';
                flag = 0;//判断是否可以上传是为1,0为否
                break;
        }
        if (flag == 1) {
            //可以上传
            $("#"+id).uploadify('upload');
        } else {
            //不能上传
            layer.msg(content,
                    {time: 1000}
                    );
            return false;
        }
        
    }
</script>

<tr>
    <td class="tableleft" style="width:250px;">中山市电子商务专项资金申请表<span class="hint">*</span></td>
    <td>
       <div class="fujian_shenqing_box">
            <ul class="img_ul">
                <?php if(isset($fujian_shenqing)&&is_array($fujian_shenqing)){?>
                 <?php foreach ($fujian_shenqing as $key => $value): ?>
                    <li>
                        <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                        <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_shenqing" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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
<!-- 隐藏组织机构代码2016年7月14日09:13:58 -->
<tr style="display:none;">
    <td class="tableleft">企业组织机构代码<span class="hint">*</span></td>
    <td>
      <div class="fujian_zuzhi_box container">
           <ul class="img_ul">
            <?php if(isset($fujian_zuzhi)&&is_array($fujian_zuzhi)){?>
              <?php foreach ($fujian_zuzhi as $key => $value): ?>
                    <li>
                        <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                        <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_zuzhi" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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
    <td class="tableleft">营业执照及有关审批文件复印件<span class="hint">*</span></td>
    <td>
       <div class="fujian_yingye_box">
          <ul class="img_ul">
             <?php if(isset($fujian_yingye)&&is_array($fujian_yingye)){?>
               <?php foreach ($fujian_yingye as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_yingye" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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
    <td class="tableleft">商事主体负责人身份证复印件<span class="hint">*</span><br>
       <!--  <span style="color:#ccc;text-align:left;">
       (内容包括：企业基本情况，电子商务项目的投入和经营情况，未来3年发展目标和具体计划，以及如果获得财政资助，将如何使用资金的计划；企业近3年内没有违法和严重违规行为的说明。)
       </span> -->
    </td>
    <td>
       <div class="fujian_shenqingbaogao_box">
          <ul class="img_ul">
            <?php if(isset($fujian_shenqingbaogao)&&is_array($fujian_shenqingbaogao)){?>
               <?php foreach ($fujian_shenqingbaogao as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <a href='javascript:void(0);' tp='<?php echo $value["id"]?>'  uploadid="fujian_shenqingbaogao" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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
    <td class="tableleft">商事主体上一年度的完税证明文件复印件<span class="hint">*</span><br>
        <!-- <span style="color:#ccc;text-align:left;">(税务部门提供的企业上一年度的完税证明复印件)</span> -->
    </td>
    <td>
       <div class="fujian_wanshui_box">
          <ul class="img_ul">
            <?php if(isset($fujian_wanshui)&&is_array($fujian_wanshui)){?>
               <?php foreach ($fujian_wanshui as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_wanshui" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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
    <td class="tableleft">商事主体上年度财务报表<span class="hint">*</span></td>
    <td>
       <div class="fujian_caiwu_box">
          <ul class="img_ul">
            <?php if(isset($fujian_caiwu)&&is_array($fujian_caiwu)){?>
               <?php foreach ($fujian_caiwu as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_caiwu" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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

<tr style="display:none;">
    <td class="tableleft">当年所获电子商务评奖或称号<span class="hint">*</span></td>
    <td>
       <div class="ny_chenghao_fj_box">
          <ul class="img_ul">
            <?php if(isset($ny_chenghao_fj)&&is_array($ny_chenghao_fj)){?>
               <?php foreach ($ny_chenghao_fj as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="ny_chenghao_fj" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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
<script src="/home/views/static/js/zoom/zoom.min.js"></script>

<script>
    //判断是否上传了附件
    function chk_fj() {
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
            return false;
        } 
        /*if (zz_length<=0) {
           layer.msg('请先上企业组织机构代码！！',{time: 1000});
            return false;
        } */
        if (yy_length<=0) {
            layer.msg('请先上传营业执照及有关审批文件复印件！！',{time: 1000});
            return false;
        } 
        if (sb_length<=0) {
            layer.msg('请先上传商事主体负责人身份证复印件！！',{time: 1000});
            return false;
        }  
        if (ws_length<=0) {
            layer.msg('请先上传商事主体上一年度的完税证明文件复印件！！',{time: 1000});
            return false;
        } 
        if (cw_length<=0) {
            layer.msg('请先上传商事主体上年度财务报表！！',{time: 1000});
            return false;
        }
        
        /*if (cf_length<=0) {
            layer.msg('请先上传当年所获电子商务评奖或称号！！',{time: 1000});
            return false;
        } */
        return true;
    }
</script>