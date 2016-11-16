<?php
/**
 * 导入走出去数据页面
 * User: 嘉辉
 * Date: 2016-08-09
 * Time: 14:49
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>走出去数据导入</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH ?>/views/static/Css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css"/>
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH ?>/views/static/Css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css"/>
    <link href="/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css"/>
    <link href="/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH ?>/views/static/Js/uploadfile/uploadify.css"/>

    <link rel="stylesheet" href="/home/views/static/js/autocomplete/jquery.autocompleter.css">

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

        .hint {
            color: red;
        }

        .tips_lk {
            color: #ccc;
            font-size: 12px;
        }

        .img_ul {
            padding: 0;
            border: 0;
            margin: 0;
        }

        .img_ul li {
            float: left;
        }

        .clear {
            clear: both;
        }

        /*加载层*/
        body .layer-load {
            background: transparent;
        }

        body .layer-load .layui-layer-content {
            text-align: center;
        }

        body .layer-load .layui-layer-content span {
            color: #FFFFFF;
        }
    </style>
    <link rel="stylesheet" href="/home/views/static/js/zoom/zoom.css" media="all"/>
</head>
<body>

<div class="form-inline definewidth m20">
    <a href="/admin_application/views/default/zcq/datamanage/zcqtpl.xls">下载excel模板</a>
</div>

<form action="<?php echo site_url("zcq_datamanage/doimport"); ?>" enctype="multipart/form-data" method="post"
      onsubmit="openload('处理中……请稍等。');"
      class="definewidth m2" name="myform" id="myform">

    <table class="table table-bordered table-hover m10">
        <tr>
            <td class="tableleft">导入的excel文件<span class="hint">*</span></td>
            <td>
                <input id="input_file" type="file" accept="application/vnd.ms-excel" name="excel" required>
            </td>
        </tr>
    </table>

    <div class="center" style="margin:0 auto;text-align:center;">
        <input type="submit" class="btn btn-primary" value="导入" id="btn-submit">
        <input type="button" class="btn btn-primary" value="关闭"
               onclick="top.topManager.closePage();">
    </div>

    <div class="alert alert-warning alert-dismissable">
        <strong>温馨提示</strong>
        请严格按照下载的excel模板进行填写数据<br>
        导入数据库可能比较大，请耐心等候
    </div>
</form>

<script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/Js/validate/validator.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/Js/layer/layer.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
<script type="text/javascript"
        src="/<?php echo APPPATH ?>/views/static/Js/uploadfile/jquery.uploadify-3.1.min.js"></script>
<script src="/home/views/static/js/autocomplete/jquery.autocompleter.js" type="text/javascript"></script>

<script type="text/javascript">
    /**
     * 打开加载窗口
     */
    var openbox = null;
    function openload(text) {
        var content = [
            '<img src="' + '/home/views/static/js/layer/skin/default/loading-2.gif' + '"/>',
            '<br>',
            '<span>' + text + '</span>'
        ].join("\n");

        openbox = layer.open({
            type: 1,
            title: false,//不显示
            shadeClose: true,
            shade: [0.8, '#393D49'],
            closeBtn: 0,//不显示
//            area: ['100%', '100%'],
            content: content,
            skin: "layer-load"
        });
    }

    $(document).ready(function () {

//        var $input_file = $('#input_file');
//        $input_file.uploadify({
//            'auto': false,//关闭自动上传
//            'removeTimeout': 1,//文件队列上传完成1秒后删除
//            'swf': '/home/views/static/js/uploadfile/uploadify.swf',
//            'uploader': '<?php //echo site_url("zcq_datamanage/doimport"); ?>//',
//            'method': 'post',//方法，服务端可以用$_POST数组获取数据
//            'buttonText': '选择文件',//设置按钮文本
//            'multi': false,//允许同时上传多张图片
////            'uploadLimit': 1,//一次最多只允许上传10张图片
//            'fileTypeDesc': 'All Files',//只允许上传图像
//            'fileTypeExts': '*.xlsx; *.xlsx;',//限制允许上传的图片后缀
//            'fileSizeLimit': '20000KB',//限制上传的图片不得超过200KB
//            'onUploadSuccess': function (file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
//                var result = $.parseJSON(data);
//                if (result.success) {
//                    //保存成功的图片路径
//                    var filepath = result.filepath;
//                    //$business_license_copy_real.val(filepath);
//                    layer.msg('文件上传成功，正在导入数据', {time: 1000});
//                    //uploadsuccess($fj1, filepath, true);
//                } else {
//                    layer.msg('上传失败,请检查上传文件类型或者刷新重试', {time: 1000});
//                }
//            },
//            'onQueueComplete': function (queueData) {
//                //上传队列全部完成后执行的回调函数
//            },
//            'onError': function (event, ID, fileObj, errorObj) {
//                if (errorObj.type === "File Size") {
//                    layer.msg('超过文件上传大小限制（2M）！', {time: 1000});
//                    //alert('超过文件上传大小限制（2M）！');
//                    return;
//                }
//                layer.msg(errorObj.type + ', Error: ' + errorObj.info, {time: 1000});
//                //alert(errorObj.type + ', Error: ' + errorObj.info);
//            }
//        });
    });

</script>

</body>
</html>
