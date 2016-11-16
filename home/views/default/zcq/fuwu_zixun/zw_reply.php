<?php
/**
 * 服务咨询选择会员用户页面
 * User: 嘉辉
 * Date: 2016-08-17
 * Time: 9:24
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>回复追问</title>
    <meta charset="UTF-8">
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <link rel="stylesheet" type="text/css" href="/home/views/static/css/bootstrap.min.css">
    <style type="text/css">
        body {
            /*padding-bottom: 40px;*/
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

        .mybtn {
            font-family: 'Microsoft Yahei', '微软雅黑', serif;
        }
    </style>
</head>

<body style="padding-left:10px;font-family:'Microsoft Yahei', '微软雅黑'">

<!-- 搜索条件
<div style="margin-top:5px;">
    关键字
    <form method="get">
        关键字：
        <input type="text" name="search_name"
               class="abc input-default"
               placeholder="名称"
               value="<?php echo $search_val['name']; ?>"
               style="width:150px;margin-bottom: 0"
        />

        <button type="submit" class="mybtn">查询</button>
        <button type="button" class="mybtn" onclick="window.location.reload(true);">刷新</button>

        <br>

    </form>
</div> -->

<!--//列表-->

<div id="div_msg_list_empty">
    <textarea name="mysql_receive_content" id="content">
        <?php echo $model['receive_content']; ?>
    </textarea>
    <div style="margin:0 auto;text-align:center;margin-top:10px;margin-bottom:10px;">
        <input type="hidden" name="mysql_id" id="mysql_id" value="<?php echo $model['id']?>">
        <input type="button" value="回复" id="zw_hf_sub" class="mybtn"/>
    </div>
</div>
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
<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<script type="text/javascript">

    $(document).ready(function () {
        //点击提交回复按钮
        $('#zw_hf_sub').click(function(){
            // top.close_open();关闭弹窗
            var content = $('#content').val()
            if($('#content').val()=='') {
                layer.msg('请您填写回复内容', {time: 3000});
                $('#content').focus();
                return false;
            }
            var zxid = $('#mysql_id').val();
            //ajax提交回复内容
            $.post('<?php echo site_url($controller . "/dozwreply");?>',{zxid:zxid,content:content}
                ,function(data){
                    var obj = eval('(' + data + ')');
                    top.alertmsg(obj.msg);
                    top.close_open();
            });
        });

    });
</script>

</body>
</html>
