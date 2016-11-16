<?php
/**
 * 机构服务咨询回复.
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
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <style>
        .pc_login label {
            width: 100px;
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

        .btn_temp {
            background: #fff;
            color: #666;
            border: 1px solid #cacaca;
            padding: 5px 30px;
            font-size: 16px;
            margin: 20px 0;
        }

        .btn-submit {
            width: auto;
            height: 33px;
            background: #2f80d5;
            color: #fff;
            border: 1px solid #2f80d5;
            padding: 5px 30px;
            font-size: 16px;
            margin: 20px 0;
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

            <form action="<?php echo $form_url; ?>" method="post" id="myform" name="myform"
                  enctype="multipart/form-data"
                  onsubmit="return chkform()" class="fix">
                <!--本条咨询的id-->
                <input type="hidden" name="id" value="<?php echo $model['id']; ?>">

            <div class="pc_content">
                <ul class="fix">
                    <li>
                        <label>咨询单位：</label>
                        <span style=""><?php echo $model['username']; ?></span>
                    </li>
                    <li>
                        <label>咨询标题：</label>
                        <span><?php echo $model['title']; ?></span>
                    </li>
                    <li>
                        <label for="content">咨询内容：</label>
                        <div style="margin-left: 105px"><?php echo $model['content']; ?></div>
                    </li>
                    <li>
                        <label>状态：</label>
                        <?php
                        if ($model["receive_isread"] == "1") {
                            echo "<span style='color: crimson'>已回复</span>";
                        } else {
                            echo "<span style='color: darkorchid'>未回复</span>";
                        }
                        ?>
                    </li>
                    <?php if ($model["receive_isread"] == "1") { ?>
                        <!--已经回复-->
                        <li>
                            <label>回复时间：</label>
                            <span>
                            <?php
                            $time = date("Y-m-d H:i:s", $model['receive_time']);
                            echo $time;
                            ?>
                            </span>
                        </li>
                    <?php } ?>
                    <li>
                        <label>回复内容：</label>
                        <br>
                        <br>
                        <div>
                            <textarea name="mysql_receive_content" id="content">
                                <?php echo $model['receive_content']; ?>
                            </textarea>
                    </li>
                    <?php if ($model["receive_isread"] == "1") { ?>
                    <li>
                        <label>追问信息：</label>
                        <?php foreach ($zw_model['list'] as $key => $value): ?>
                            <div>
                                <?php echo $value['content']; ?>&nbsp;<span style='font-size:14px;color:#ccc;'>(追问时间：<?php echo date('Y-m-d H:i:s', $value['create_time'])?>)</span>
                                <a style="<?php if($value['receive_isread']==1)echo 'color:green;';else echo 'color:red;';?>display:inline;" href="javascript:void(0);" 
                                onclick="openwindow(event,'回复追问','<?php echo site_url($controller . "/zw_reply").'?id='.$value['id'].'&pid='.$value['pid']?>')">
                                    <?php if($value['receive_isread']==1)echo '已回复';else echo '未回复'; ?>
                                </a>
                            </div>
                        <?php endforeach ?>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div>
                <input type="button" value="读取临时数据" class="btn_temp" id="temp_read"/>
                <input type="button" value="临时保存" class="btn_temp" id="temp_save"/>
                <input type="submit" value="回复" class="btn-submit"/>
            </div>

            </form>
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
    });
</script>
<!--使用cookise-->
<script type="text/javascript" src="/home/views/static/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>

<script src="/home/views/static/js/selall.js"></script>
<script type="text/javascript">
    openbox;//弹框变量
    /**
     * 检查表单
     */
    function chkform() {

        var $textarea = $('#content');
        if ($textarea.val() == "") {
            layer.msg("咨询内容不能为空！！！");
            return false;
        }

        return $("#myform").Valid();
    }

    $(document).ready(function () {
        //临时保存
        var $btn_temp_save = $('#temp_save');
        var $btn_temp_read = $('#temp_read');
        var $textarea = $('#content');

        //点击事件
        $btn_temp_save.click(function () {
            $.cookie('zixun_reply', $textarea.val(), {expires: 7});
            layer.msg("临时数据保存成功！");
        });
        $btn_temp_read.click(function () {
            window.editor.html($.cookie('zixun_reply'));
            layer.msg("已读取临时数据");
        });

    })
    /**
     *获取鼠标位置
     */
    function getMousePos(ev) {
        if (!ev) {
            ev = this.getEvent();
        }
        if (ev.pageX || ev.pageY) {
            return {
                x: ev.pageX,
                y: ev.pageY
            };
        }

        if (document.documentElement && document.documentElement.scrollTop) {
            return {
                x: ev.clientX + document.documentElement.scrollLeft - document.documentElement.clientLeft,
                y: ev.clientY + document.documentElement.scrollTop - document.documentElement.clientTop
            };
        }
        else if (document.body) {
            return {
                x: ev.clientX + document.body.scrollLeft - document.body.clientLeft,
                y: ev.clientY + document.body.scrollTop - document.body.clientTop
            };
        }
    }
    function openwindow(event, title, url) {
        var offset = getMousePos(event);
        //触发事件的按钮
        var $btn = $(event.target);
        // offset: ['', offset.x + "px"],//top、left
        openbox = layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '50%'],
            content: url,
            offset: ['', "30%"],//top、left
            success: function (layero, index) {
                //创建完毕调用
                console.log(layero, index);
                //自适应高度
                layer.iframeAuto(index);

                //iframe的body
                var body = layer.getChildFrame('body', index);
                //高度
                var height = $(body).height() + 30;

            }
        });
    }
     /**
     * 关闭选择窗口
     */
    function close_open() {
        layer.close(openbox);
    }
    //提示信息
    function alertmsg(msg_tp) {
        layer.msg(msg_tp,{time: 3000});
    }
</script>
</body>
</html>