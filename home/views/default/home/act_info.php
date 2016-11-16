<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>中山市电子商务信息管理系统</title>
<link rel="stylesheet" type="text/css" href="/home/views/static/swj/images/swj.css">
<!--[if IE 6]>
        <script src="js/iepng.js" type="text/javascript"></script>
        <script type="text/javascript">
            EvPNG.fix('*');  //EvPNG.fix('包含透明PNG图片的标签'); 多个标签之间用英文逗号隔开。
        </script>
<![endif]-->
<script src="/home/views/static/js/jquery-1.8.1.min.js"></script>
<script src="/home/views/static/js/validate/validator.js"></script>
<script src="/home/views/static/js/laydate/laydate.js"></script>
</head>

<body>
<div class="logo"><img src="/home/views/static/swj/images/logo.png" title="中山市电子商务信息管理系统" alt="中山市电子商务信息管理系统"></div>
<div class="middle">
    <div class="title"><?php echo $actname;?></div>
    <div class="time"><?php echo $updatetime?></div>
    <div class="page">
        <?php echo $description;//htmlspecialchars($description)?>
        <!-- <img src="/home/views/static/swj/images/bg.jpg"> -->
    </div>
	<div class="list_back"><a href="<?php echo $backurl?>">【 返 回 】</a></div>
</div>
<div class="foot"><img src="/home/views/static/swj/images/index_03.png"></div>
</body>
</html>
