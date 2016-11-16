<?php
/**
 * 服务咨询查看.[已废弃！！！]
 * User: 嘉辉
 * Date: 2016-08-18
 * Time: 10:44
 */
?>
<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
<!--    <link rel="stylesheet" type="text/css" href="/home/views/static/css/bootstrap.min.css">-->
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <style>
        .pc_login label {
            width: 80px;
        }

        .clear {
            clear: both;
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
            width: 100px;
            display: inline-block;
        }

        .pc_member_background_list span {
            font-size: inherit;
        }

        .mytable2 td {
            /*background: #fff;*/
            padding: 8px;
        }

        a.back_btn {
            width: 80px;
            font-size: 16px;
            padding: 5px 0;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            height: 25px;
            line-height: 25px;
            text-align: center;
            margin-right: 10px;
            border: 1px solid #d5d5d5;
        }
    </style>

</head>
<body>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div class="">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <!--news list-->
        <div id="" class="pc_member_background_list">
            <span class="pc_list_present">当前位置：<a href="<?php echo $admin_url; ?>">会员后台首页</a>-><a
                    href="<?php echo $cur_url; ?>">我的咨询</a></span>
            <h3 class="pc_list_h3">我的咨询</h3>
            <div class="search">
                <a href="<?php echo site_url($controller . "/lists"); ?>" class="back_btn">返回</a>
            </div>

            <table class="mytable2" width="100%" cellspacing="1" cellpadding="0">
                <tr>
                    <td style="width: 100px">咨询对象：</td>
                    <td><?php echo $model['username']; ?></td>
                </tr>
                <tr>
                    <td>咨询标题：</td>
                    <td><?php echo $model['title']; ?></td>
                </tr>
                <tr>
                    <td>咨询内容：</td>
                    <td><?php echo $model['content']; ?></td>
                </tr>
                <tr>
                    <td>状态：</td>
                    <td>
                        <?php
                        if ($model["receive_isread"] == "1") {
                            echo "<span style='color: crimson'>已回复</span>";
                        } else {
                            echo "<span style='color: darkorchid'>未回复</span>";
                        }
                        ?>
                    </td>
                </tr>
                <?php if ($model["receive_isread"] == "1") { ?>
                    <!--已经回复-->
                    <tr>
                        <td>回复时间：</td>
                        <td>
                            <?php
                            $time = date("Y-m-d H:i:s", $model['receive_time']);
                            echo $time;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>回复内容：</td>
                        <td>
                            <div><?php echo $model['receive_content']; ?></div>
                        </td>
                    </tr>
                <?php } ?>
            </table>

            <!--                        <div>-->
            <!--                            <input type="button" value="临时保存" id="temp_save"/>-->
            <!--                            <input type="submit" value="提交"/>-->
            <!--                        </div>-->
        </div>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>


<script src="/home/views/static/js/selall.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

    })
</script>
</body>
</html>