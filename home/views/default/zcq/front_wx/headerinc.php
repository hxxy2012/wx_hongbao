<meta charset="utf-8" />
<meta name="keywords" content="<?php echo $config["site_keywd"];?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<link type="text/css" rel="stylesheet" href="/home/views/static/zcq/wx/css/style.css"/>
<link type="text/css" rel="stylesheet" href="/home/views/static/zcq/wx/iconfont/iconfont.css" />
<script type="text/javascript" src="/home/views/static/zcq/wx/js/jquery1.42.min.js"></script>
<script type="text/javascript" src="/home/views/static/zcq/wx/js/min.js"></script>
<script type="text/javascript">
    $(function(){
         $(".mobole_in_tit li").click(function(){
             $(this).addClass("inticur").siblings().removeClass("inticur"); //切换选中的按钮高亮状态
             var index=$(this).index(); //获取被按下按钮的索引值，需要注意index是从0开始的
             $(".mobile_in_show").eq(index).show().siblings().hide(); //在按钮选中时在下面显示相应的内容，同时隐藏不需要的框架内容
         });

         $(".mobile_fa_ul li").click(function(){
             $(this).addClass("mobile_falicur").siblings().removeClass("mobile_falicur"); //切换选中的按钮高亮状态
             var ind=$(this).index(); //获取被按下按钮的索引值，需要注意index是从0开始的
             $(".mobile_fa_div").eq(ind).show().siblings().hide(); //在按钮选中时在下面显示相应的内容，同时隐藏不需要的框架内容
         });
    });
</script>