<!doctype html>
<html>
<head>
    <title>活动列表-<?php echo $config["site_fullname"]; ?></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="renderer" content="ie-comp">
    <meta name="keywords" content="<?php echo $config["site_keywd"]; ?>">
    <link rel="stylesheet" href="/home/views/static/zcq/css/style.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/home/views/static/zcq/iconfont/iconfont.css"/>
    <!--[if lt IE 8]>
    <script>
        alert('本网站已不支持低于IE8的浏览器,请选用更好版本的IE浏览器或Google Chrome浏览器');
    </script>
    <![endif]-->
    <!--[if IE 8]>
    <style type="text/css">
        #touch_right .tel {
            background: #434343;
            height: 50px;
            line-height: 50px;
            padding: 0 8px;
        }

        #touch_right .er {
            background: #656565;
            height: 50px;
            line-height: 50px;
            padding: 0 8px;
        }

        #touch_right .top {
            height: 50px;
            line-height: 50px;
            padding: 0 8px;
        }

        #touch_right .hotline {
            line-height: 170%;
        }

        #touch_right .floating_ewm {
            height: 190px;
            top: -142px;
            line-height: 150%;
        }

        .top {
            height: 50px;
            line-height: 50px;
        }
    </style>
    <![endif]-->
    <script type="text/javascript" src="/home/views/static/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/home/views/static/zcq/js/min.js"></script>
    <script type="text/javascript" src="/home//views/static/js/layer/layer.js"></script>
    <style>
        .clear {
            clear: both;
        }

        .fl {
            float: left;
        }

        .pc_content {
            width: 100%;
        }

        .pc_content ul {
            margin: 0;
            padding: 0;
            list-style: none;
            width: 100%;
        }

        .pc_content ul li {
            margin-top: 10px;
            width: 100%;
        }

        .pc_content ul li label {
            font-weight: bold;
            float:left;
        }

        a.back_btn {
            width: 80px;
            font-size: 16px;
            padding: 5px 0;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            line-height: 25px;
            text-align: center;
            margin-right: 10px;
            border: 1px solid #d5d5d5;
        }

        .bm_btn_box {
            width: 100%;
            margin: 0 auto;
            text-align: center;
            margin-top: 23px;
        }

        .btn {
            font-size: 16px;
            padding: 5px;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            line-height: 25px;
            text-align: center;
            margin-left: 5px;
            border: 1px solid #d5d5d5;
            color: #000;
            background: #fff;
            cursor: pointer;
        }

        .btn:hover {
            color: #2f80d5;
        }

        .btn-col {
            color: green;
        }
    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div>
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <!--news list-->
        <div id="" class="pc_member_background_list">
            <span class="pc_list_present">

                当前位置：<a href="<?php echo site_url("admin/index") ?>">会员后台首页</a>-><a
                    href="<?php echo site_url("adminx/zcq_huodong/index") ?>">活动报名</a></span>
            <h3 class="pc_list_h3">活动报名</h3>
            <div class="search">
                <a href="<?php echo $ls; ?>" class="back_btn">返回</a>
            </div>
            <!-- 内容开始 -->
            <div class="pc_content">
                <ul class="fix">
                    <li class="fix">
                        <label>活动主题：</label>
                        <?php echo $model['title'] ?>
                    </li>
                    <li class="fix">
                        <label>报名时段：</label>
                        <?php echo date('Y-m-d H:i:s', $model['baoming_start']) ?>&nbsp;
                        至&nbsp;<?php echo date('Y-m-d H:i:s', $model['baoming_end']) ?>
                    </li>
                    <li class="fix">
                        <label>活动时段：</label>
                        <?php echo date('Y-m-d H:i:s', $model['starttime']) ?>&nbsp;
                        至&nbsp;<?php echo date('Y-m-d H:i:s', $model['endtime']) ?>
                    </li>
                    <li class="fix">
                        <label>活动介绍：</label>
                        <div style="margin-left:80px;">
                            <?php echo $model['content']; ?>
                        </div>
                    </li>
                    <li class="fix">
                        <div class="bm_btn_box">
                            <?php if (!$isbaoming) { //已经报名了的活动不再显示我要报名?>
                                <input type="button" class="btn btn-col" id="bm" name="bm" value="我要报名">
                            <?php } ?>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- 活动结束 -->
        </div>
    </div>
</div>

<div id="touch_right">
    <ul>
        <li class="tel"><i class="iconfont">&#xe60c;</i>
            <div class="floating_left hotline">咨询热线：<br> (86-760)89892378</div>
        </li>
        <li class="er"><i class="iconfont">&#xe60d;</i>
            <div class="floating_left floating_ewm">
                <img src="/home/views/static/zcq/images/two-dimension-code.jpg">扫一扫二维码<br/>关注官方微信
            </div>
        </li>
        <li onClick="gotoTop();return false;" class="top"><i class="iconfont">&#xe622;</i>
            <div class="floating_left">返回頁首</div>
        </li>
    </ul>
</div>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>

<script>
    $(function () {
        //ajax提交报名
        hdid = "<?php echo $model['id'];?>";//活动id
        flag_bm = 1;//标识是否进行ajax报名1是，0否
        //监听报名按钮
        $('#bm').click(function () {
            if (flag_bm == 1) {
                flag_bm = 0;
                $.post('<?php echo site_url("adminx/zcq_huodong/doBm");?>', {hdid: hdid}, function (data) {
                    flag_bm = 1;
                    var obj = eval('(' + data + ')');
                    //报名成功的返回列表页面
                    if (obj.code == 0) {
                        // layer.alert(obj.info);
                        layer.open({
                            content: obj.info
                            , btn: ['确定', '取消']
                            , yes: function (index, layero) { //或者使用btn1
                                window.location.href = '<?php echo $ls;?>';
                            }, cancel: function (index) { //或者使用btn2
                                window.location.href = '<?php echo $ls;?>';
                            }
                        });
                        // window.location.href = '<?php echo $ls;?>';
                    } else {
                        layer.msg(obj.info,
                            {time: 3000}
                        );
                    }
                });
            } else {
                layer.msg('请您不要重复点击报名按钮',
                    {time: 3000}
                );
            }

        });
    });
</script>