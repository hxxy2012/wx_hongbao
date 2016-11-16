<!doctype html>
<html>
<head>
    <title>登录-<?php echo $config["site_fullname"];?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script src="/home/views/static/js/jquery-1.8.1.min.js"></script>
    <script src="/home/views/static/js/validate/validator_plus.js"></script>
    <script src="/home/views/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/home//views/static/js/layer/layer.js"></script>
    <script>
        function chkform(){
            if ($("#myform").Valid()) {
                //document.getElementById("myform").submit();
                return true;
            }
            else {
                return false;
            }
        }
    </script>
</head>
<body>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/home/list_left.php'); ?>
        <form action="<?php echo site_url("login/dologin");?>" class="lr_spe" id="myform" method="post" name="login_form" onsubmit="return chkform()" class="fix">
            <span class="pc_list_present">当前位置：<a href="#">会员登录</a></span>
            <h3 class="pc_list_h3">会员登录</h3>
            <ul class="fix">
                <li>
                   <label>用户名：</label>
                    <input type="text" name="user" required placeholder="请输入公司简称"/>
                </li>
                <li>
                    <label>密码：</label>
                    <input type="password" name="pwd" required placeholder="请输入密码"/>
                </li>
                <li>
                    <label>验证码：</label>
                    <input type="text" maxlength="4" name="code"
                           style="width:80px;text-transform: uppercase"
                           tipstyle="bottom"
                           valType='yzm'  remoteUrl="<?php echo site_url("login/chkcode");?>"
                           placeholder="请输入验证码" id="inputCode" class=""/>
                    <div class="code"
                         id="checkCode"
                         onclick="document.getElementById('codeimg').src='<?php echo site_url("login/code");?>?rnd='+Math.random();"
                    ><img id="codeimg" style="z-index: 10000" src="<?php echo site_url("login/code");?>"/></div>
                </li>
            </ul>
            <div><input type="submit" value="登录" ></div>
        </form>

    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>