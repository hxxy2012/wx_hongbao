<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看</title>
    <meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>   
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/uploadfile/jquery.uploadify-3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.css"/>
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


    </style>
</head>
<body>
<img src="<?php echo $img;?>"/>
</body>
</html>
