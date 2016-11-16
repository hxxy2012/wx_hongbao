<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <style>
        .pc_login label {
            /*width: 180px;*/
        }
    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <form action="<?php echo $form_url; ?>" method="post" id="myform" name="myform"
              enctype="multipart/form-data"
              onsubmit="return chkform()" class="fix">
            <span class="pc_list_present">当前位置：<a href="<?php echo $admin_url;?>">会员后台首页</a>-><a href="<?php echo $cur_url;?>">修改密码</a></span>
            <h3 class="pc_list_h3">修改信息</h3>
            <ul class="fix">
                <li class="fix">
                    <i>*</i><label for="org_pwd">原密码：</label>
                    <input type="password" id="org_pwd" name="old_pwd" valtype="mm"
                           remoteurl="<?php echo site_url("adminx/zcq_user/chkpwd"); ?>"
                    >
                </li>
                <li class="fix">
                    <i>*</i><label for="new_pwd">新密码：</label>
                    <input type="password" id="new_pwd" name="new_pwd" valtype="mm">
                </li>
                <li class="fix">
                    <i>*</i><label for="new_pwd2">确认密码：</label>
                    <input type="password" id="new_pwd2" name="new_pwd2" valtype="mm">
                </li>

            </ul>
            <div>
                <input type="submit" value="提交"/>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<script type="text/javascript">
    $(function () {
        <?php if (isset($message)) {
        echo "layer.msg('{$message}', {time: 3000})";
    }
        ?>
    });

    /**
     * 检查表单
     */
    function chkform() {
        var $org_pwd = $('#org_pwd');
        var $new_pwd = $('#new_pwd');
        var $new_pwd2 = $('#new_pwd2');

        if ($org_pwd.val() == $new_pwd.val()) {
            layer.msg("旧密码与新密码相同！", {time: 1000});
            return false;
        }

        if ($new_pwd.val() != $new_pwd2.val()) {
            layer.msg("两次输入的新密码不相同！", {time: 1000});
            return false;
        }

        return $("#myform").Valid();
    }

    $(document).ready(function () {

    });
</script>
</body>
</html>