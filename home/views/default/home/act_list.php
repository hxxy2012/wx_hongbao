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
</head>
<script src="/home/views/static/js/jquery-1.8.1.min.js"></script>
<script src="/home/views/static/js/validate/validator.js"></script>
<script src="/home/views/static/js/laydate/laydate.js"></script>
<script type="text/javascript">
    $(function(){
        //点击跳转
        $('.fy_btn').click(function(){
           var page = parseInt($('.fy_box').val());//填写的页数
            if (page <= 0) {
                alert('请输入大于0的页数');
            } else {
                window.location.href = "<?php echo site_url('home/act_list');?>?per_page="+page;
            } 
        });
    });  
</script>
<style>
    .act_list h5{color:#FFF100;text-align: right;}
</style>
<body>
<div class="logo"><img src="/home/views/static/swj/images/logo.png" title="中山市电子商务信息管理系统" alt="中山市电子商务信息管理系统"></div>
<div class="middle">
	<h1>协会活动</h1>
	<div class="act_list">
    	<ul>
             <?php foreach ($list as $key => $value): ?>
                <li>
                    <a href="<?php echo site_url('home/act_info').'?id='.$value['id']?>">
                        <img src="/<?php echo $value['img']?>">
                        <p>
                            <h4><?php echo msubstr($value['actname'], 0, 50, mb_strlen($value['actname'], 'utf8'))?></h4>
                            <h5>--<?php echo msubstr($value['xiehuiname'], 0, 50, mb_strlen($value['xiehuiname'], 'utf8'))?></h5>
                            <?php echo msubstr(stripslashes(strip_tags($value['description'])), 0, 215, mb_strlen(stripslashes(strip_tags($value['description'])), 'utf8'))?>
                        </p>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
     <div class="list_fy"><?php echo $pager;?>跳转至<input type="text" class="fy_box"><input type="submit" value=" GO " class="fy_btn"></div>
</div>
<div class="foot"><img src="/home/views/static/swj/images/index_03.png"></div>
</body>
</html>
