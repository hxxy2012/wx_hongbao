<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
    <script type="text/javascript" src="/home/views/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <style>
        .pc_login label {
            width: 180px;
        }

        .way_table {
            border: 0px !important;
        }

        .mytable2 td {
            background: #fff;
            padding: 3px;
        }
    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>


<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <div class="pc_member_background_list">
            <span class="pc_list_present">当前位置：<a href="<?php echo site_url("adminx/admin_index/index"); ?>">会员后台首页</a>-><a
                    href="<?php echo site_url("adminx/zcq_mail/index"); ?>">站内信</a></span>
            <h3 class="pc_list_h3">查看</h3>


            <table class="mytable2" width="100%" bgcolor="#cccccc" cellspacing="1" cellpadding="0">
                <tr>
                    <td class="tableleft" width="100">
                        标题：
                    </td>
                    <td>
                        <?php echo $model["title"]; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">
                        发送时间：
                    </td>
                    <td>
                        <?php echo date("Y-m-d H:i", $model["createtime"]); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tableleft" valign="top">
                        内容：
                    </td>
                    <td>
                        <?php
                        /* 为了能够转换url
                        * 使用方法：只需填写【控制器/方法】，里面的内容将会转化成链接
                         * 例子：<a href='【zcq_mail/myview】?id=116'>点击此处直接查看</a> 【zcq_mail/myview】将转化为链接
                         */
                        $string = $model['content'];
                        $pattern = '/([\s\S]*)【(.*?)】([\s\S]*)/i';
                        preg_match($pattern, $string, $matches);
                        if (!empty($matches)) {
                            $control_method = $matches[2];//【zcq_mail/myview】
                            $string = $matches[1] . site_url($control_method) . $matches[3];
                        }
                        echo $string;
                        ?>
                    </td>

                </tr>
                <tr>
                    <td colspan="2">
                        <div style="text-align: center;">
                            <input class="mybtn" type="button" value="返回"
                                   onclick="window.location.href='<?php echo $ls; ?>'"/>
                        </div>
                    </td>

                </tr>
            </table>


        </div>

    </div>
</div>


<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>