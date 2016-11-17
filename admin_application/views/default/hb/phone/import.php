<?php
/**
 * 导入手机号页面
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
    <title>手机号导入</title>
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
    <a href="/admin_application/views/default/hb/phone/phone.xls">下载excel模板</a>
</div>

<form action="<?php echo site_url("hb_phone/doimport"); ?>" enctype="multipart/form-data" method="post"
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
        <input type="hidden" name="backurl" value="<?php echo $ls;?>">
        <input type="submit" class="btn btn-primary" value="导入" id="btn-submit">
        <input type="button" class="btn btn-primary" value="关闭"
               onclick="top.topManager.closePage();">
    </div>

    <div class="alert alert-warning alert-dismissable">
        <strong>温馨提示</strong>
        请严格按照下载的excel模板进行填写数据(备注一列可不填)<br>
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


    });

</script>

</body>
</html>
