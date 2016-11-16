<?php
/**
 * 服务咨询查看.
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
            float:left
            font-weight: bold;
            width: 100px;
            display: inline-block;
        }

        .pc_member_background_list span {
            font-size: inherit;
        }

        .mytable2 td {
            background: #fff;
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
                <a href="<?php echo site_url($controller . "/lists"); ?>" class="back_btn">返回列表</a>
            </div>

            <table class="mytable2" width="100%" bgcolor="#cccccc" cellspacing="1" cellpadding="0">
                <tr>
                    <td style="width: 100px">咨询对象：</td>
                    <td><?php echo $model['username']; ?></td>
                </tr>
                <tr>
                    <td>咨询标题：</td>
                    <td><?php echo $model['title']; ?></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">咨询内容：</td>
                    <td>
                        <?php if ($model["receive_isread"] == "0") { ?>
                            <!--未回复的话才显示保存表单-->
                            <form method="post" action="<?php echo site_url($controller . "/doedit"); ?>">
                                <input type="hidden" name="id" value="<?php echo $model['id']; ?>"/>
                                <input type="button" value="修改" id="btn_edit" class="mybtn">
                                <input type="submit" value="保存" id="btn_save" class="mybtn" style="display: none">
                                <div id="div_content_edit" style="display: none;">
                                    <textarea id="content" name="content"><?php echo $model['content']; ?></textarea>
                                </div>
                            </form>
                        <?php } ?>
                        <!--普通内容-->
                        <div id="div_content">
                            <?php echo $model['content']; ?>
                        </div>
                    </td>
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
                        <td style="vertical-align: top;">回复内容：</td>
                        <td>
                            <div><?php echo $model['receive_content']; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">追问信息：</td>
                        <td>
                            <?php foreach ($zw_model['list'] as $key => $value): ?>
                                <div>
                                    <?php echo '问：'.$value['content'].'，答：'.($value['receive_content']?'<span style="color:green;">'.$value['receive_content'].'<span style="color:#ccc;">(回复时间：'.date('Y-m-d H:i:s', $value['receive_time']).')</span></span>':'<span style="color:red;">未回复</span>');?>
                                </div>    
                            <?php endforeach ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                <form action="<?php echo $form_url; ?>" method="post" id="myform" name="myform" enctype="multipart/form-data"
                  onsubmit="return chkform()" class="fix">
                <tr>
                    <td style="vertical-align: top;">追问：</td>
                    <td>
                        <div>
                            <textarea name="mysql_content" id="zw_content"></textarea>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan='2' align="center">
                    <div>
                        <input type="hidden" name="mysql_title" value="<?php echo $model['title']?>">
                        <input type="hidden" name="mysql_pid" value="<?php echo $model['id']?>">
                        <input type="hidden" name="mysql_receive_userid" value="<?php echo $model['receive_userid']?>">
                        <input type="button" value="临时保存" style="display:none;" id="temp_save"/>
                        <input type="submit" value="提交" class="mybtn"/>
                    </div>
                    </td>
                </tr>
                </form>
            </table>
            
        </div>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>


<!--kindeditor-->
<script type="text/javascript"
        src="/home/views/static/js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/home/views/static/js/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
    KindEditor.ready(function (K) {
        window.editor = K.create('#content', {
            width: '90%',
            height: '400px',
            allowFileManager: false,
            allowUpload: false,
            afterCreate: function () {
                this.sync();
            },
            afterBlur: function () {
                this.sync();
            },
            extraFileUploadParams: {
                'cookie': '<?php echo isset($_COOKIE['admin_auth']) ? $_COOKIE['admin_auth'] : "";?>'
            },
            uploadJson: "<?php echo site_url($controller . "/upload") . "?action=upload&session_id=" . $sess["session_id"];?>"
        });
        window.editor1 = K.create('#zw_content', {
            width: '90%',
            height: '400px',
            allowFileManager: false,
            allowUpload: false,
            afterCreate: function () {
                this.sync();
            },
            afterBlur: function () {
                this.sync();
            },
            extraFileUploadParams: {
                'cookie': '<?php echo isset($_COOKIE['admin_auth']) ? $_COOKIE['admin_auth'] : "";?>'
            },
            uploadJson: "<?php echo site_url($controller . "/upload") . "?action=upload&session_id=" . $sess["session_id"];?>"
        });
    });
    /**
     * 检查表单
     */
    function chkform() {

        var $textarea = $('#zw_content');
        if ($textarea.val() == "") {
            layer.msg("追问内容不能为空！！！");
            return false;
        }
        return true;
    }
</script>

<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<!--显示信息-->
<?php if (isset($message)) { ?>
    <script type="text/javascript">
        var msg = '<?php echo $message;?>';
        layer.msg(msg, {time: 3000});
    </script>
<?php } ?>

<script src="/home/views/static/js/selall.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        //保存
        var $btn_edit = $('#btn_edit');
        var $btn_save = $('#btn_save');
        var $div_content = $('#div_content');
        var $div_content_edit = $('#div_content_edit');
        $btn_edit.click(function () {
            $div_content.slideToggle();
            $div_content_edit.slideToggle();
            $btn_save.slideToggle();
        });
    })
</script>
</body>
</html>