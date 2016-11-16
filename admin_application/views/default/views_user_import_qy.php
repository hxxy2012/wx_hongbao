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
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("user/jichu_dianshang");?>">返回列表</a>&nbsp;&nbsp;
   <a href="/data/download/exceltpl/dsqytpl.xls">下载excel模板</a>&nbsp;&nbsp;
</div>

<form action="<?php echo site_url("user/import_qy");?>" enctype="multipart/form-data" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doimport_qy">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">导入的excel文件<span class="hint">*</span></td>
        <td>
            <input type="file" accept="application/vnd.ms-excel" name="excel">
        </td>
    </tr>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="submit" class="btn btn-primary button-success" value="导入" id="btn-submit">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("user/jichu_dianshang");?>';">&nbsp;&nbsp;
</div>
<div class="alert alert-warning alert-dismissable">
<strong>温馨提示</strong>
请严格按照下载的excel模板进行填写数据(从excel中的第3行开始填写)<br>
excel模板中的电商企业类型、电商交易模式和主营产品都为系统里的数据，如果有多个请以逗号分割例如:B2C,O2O<br>
导入数据库可能比较大，请耐心等候
</div>
</form>
</body>
</html>
