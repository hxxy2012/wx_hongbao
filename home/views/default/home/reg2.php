<!doctype html>
<html>
<head>
    <title>注册</title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator_plus.js"></script>
    <!--邮箱自动完成css-->
    <link href="/home/views/static/js/mailAutoComplete/mailAutoComplete.css" rel="stylesheet"/>
    <style>
        body ul.emailist li {
            margin: auto;
            line-height: inherit;
        }

        .pc_login label {
            width: 140px;
        }
    </style>
</head>
<body>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/home/list_left.php'); ?>

        <form action="<?php echo site_url("home/doreg"); ?>" class="lr_spe" method="post" id="myform" name="myform"
              enctype="multipart/form-data"
              onsubmit="return chkform()" class="fix">
            <span class="pc_list_present">当前位置：<a href="">会员注册</a></span>
            <h3 class="pc_list_h3">会员注册</h3>
            <ul class="fix">
                <li>
                    <i>*</i><label>单位属于：</label>
                    <?php
                    foreach ($usertype_list as $item) {
                        $checked = $model['usertype'] == $item['id'] ? "checked" : "";
                        echo "<input type='radio' name='mysql_usertype' value='{$item['id']}' {$checked} />{$item['name']}";
                    }
                    ?>
                </li>
                <li class="form_45064" style="display: flex;">
                    <i>*</i><label>服务类型：</label>
                    <?php
                    foreach ($server_type_list as $item) {
                        echo "<label style='width: auto'><input type='checkbox' name='server_type[]' value='{$item['id']}' />{$item['name']}</label>";
                    }
                    ?>
                </li>
                <li style="clear: both;">
                    <i>*</i><label>登录用户：</label>
                    <input type="text" name="mysql_username" placeholder="请输入企业或者机构简称" valtype="my_require"
                           remoteurl="<?php echo site_url("home/chkusername"); ?>"
                           value="<?php echo $model['username']; ?>"
                           required>
                </li>
                <li>
                    <i>*</i><label>密码：</label>
                    <input type="password" id="pwd" name="pwd" placeholder="请输入密码" minLength="6" maxlength="18"
                           valtype="mm">
                </li>
                <li>
                    <i>*</i><label>确认密码：</label>
                    <input type="password" id="pwd2" name="pwd2" placeholder="请输入确认密码" minLength="6" maxlength="18"
                           equalTo="pwd"
                           valtype="mm">
                </li>
                <li>
                    <i>*</i><label>姓名：</label>
                    <input type="text" name="mysql_realname" placeholder="请输入真实姓名"
                           value="<?php echo $model['realname']; ?>" required>
                </li>
                <li>
                    <i>*</i><label>手机号码：</label>
                    <input type="text" name="mysql_tel" placeholder="请输入手机号码" valtype="mobile"
                           remoteurl="<?php echo site_url("home/chktel"); ?>"
                           value="<?php echo $model['tel']; ?>"
                           required>
                </li>
                <li style="position: relative">
                    <i>*</i><label>邮箱：</label>
                    <input type="text" name="mysql_email" placeholder="请输入邮箱" valtype="email"
                           remoteurl="<?php echo site_url("home/chkemail"); ?>"
                           value="<?php echo $model['email']; ?>"
                           required>
                </li>
                <li>
                    <i>*</i><label>公司全称：</label>
                    <input type="text" name="mysql_company" placeholder="请输入公司全称" valtype="my_require" minLength="3"
                           remoteurl="<?php echo site_url("home/chkfullname"); ?>"
                           value="<?php echo $model['company']; ?>"
                           required>
                </li>
                <li>
                    <i>*</i><label>公司地址：</label>
                    <input type="text" name="mysql_addr" placeholder="请输入公司地址" valtype="my_require" minLength="3"
                           value="<?php echo $model['addr']; ?>"
                           required>
                </li>
                <li>
                    <i>*</i>
                    <label>
                        是否三证合一
                    </label>
                    <input type="radio" name="mysql_sanzheng" value="1">是
                    <input type="radio" name="mysql_sanzheng" value="2">否
                </li>

                <li>
                    <i>*</i><label>三证合一：</label>
                    <input id="input_sanzheng" type="file" radiovalue="1" name="file_sanzheng" accept="image/*"
                           required>
                </li>

                <li>
                    <i>*</i><label>营业执照：</label>
                    <input id="file_yingye" type="file" radiovalue="2" name="file_yingye" accept="image/*" required>
                </li>

                <li>
                    <i>*</i><label>组织结构代码证：</label>
                    <input id="file_zuzhi" type="file" radiovalue="2" name="file_zuzhi" accept="image/*" required>
                </li>

                <li>
                    <i>*</i><label>税务登记证：</label>
                    <input id="file_shuiwu" type="file" radiovalue="2" name="file_shuiwu" accept="image/*" required>
                </li>

                <li class="form_45063" style="clear: both;margin-bottom: 30px">
                    <i style="visibility: hidden">*</i><label>企业境外投资证书：</label>
                    <input id="input_touzizhengshu" type="file" name="file_touzizhengshu" accept="image/*">
                </li>

                <li>
                    <i>*</i>
                    <label>验证码：</label>
                    <input type="text" maxlength="4" name="yzm"
                           style="width:80px;text-transform: uppercase"
                           tipstyle="bottom"
                           valType='my_require' remoteUrl="<?php echo site_url("home/chkcode"); ?>"
                           placeholder="请输入验证码" id="inputCode" class=""/>
                    <div class="code"
                         id="checkCode"
                         onclick="document.getElementById('codeimg').src='<?php echo site_url("home/code"); ?>?rnd='+Math.random();">
                        <img id="codeimg" src="<?php echo site_url("home/code"); ?>"/>
                    </div>
                </li>
            </ul>
            <br>
            <br>
            <div style="float: left">
                <input type="submit" value="提交">
                <input type="reset" value="重置"/>
                <!--                <input id="btn_test" type="button" value="测试"/>-->
            </div>
        </form>

    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<!--邮箱自动完成-->
<script src="/home/views/static/js/mailAutoComplete/jquery.mailAutoComplete-4.0.js"></script>
<!--选择图片的提示-->
<div class="layui-layer layui-anim layui-layer-dialog layui-layer-border layui-layer-msg layui-layer-hui"
     id="layui-selectpic" style="display:none; z-index: 19891020;">
    <div class="layui-layer-content">请选择jpg,jpeg,png类型的图片</div>
</div>


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
        //注册机构需要选择服务类型
        //获取项目服务类型选择的数量
        var obj = document.getElementsByName("server_type[]");
        var num = 0;
        for (var i = 0; i < obj.length; i++) {
            if (obj[i].checked) {
                num++;
            }
        }

        var check_radio = $('input[name=mysql_usertype]:checked').val();
        //console.log(check_radio);
        if (num == 0 && check_radio == 45064) {//== 45064
            layer.msg('请选择至少选中一个服务类型!', {time: 1000});
            return false;
        }


        var $pwd = $('#pwd');
        var $pwd2 = $('#pwd2');

        if ($pwd.val() != $pwd2.val()) {
            layer.msg('两次输入密码不相同', {time: 1000});
            return false;
        }
        if ($pwd.val().length < 6) {
            layer.msg('密码长度至少6位', {time: 1000});
            return false;
        }

        //验证成功了就返回true
        var result = $("#myform").Valid();
        console.log(result);
        return result;
    }

    $(document).ready(function () {
        //表单
        var $myform = $('#myform');

        //邮箱自动完成
        var $input_email = $("input[name='mysql_email']");
        var $email_ul = $input_email.mailAutoComplete({zIndex: 10000}).siblings("ul.emailist");
        //console.log($email_ul);
        $email_ul.css("marginLeft", $input_email.position().left + "px");


        //input元素的合集
        var $inputs = $("input[type=file][radiovalue]");
        //三证上传
        var $input_sanzheng = $('#input_sanzheng');


        //选择图片的提示
        var $selectpic = $('#layui-selectpic');

        function showTips() {
            var top = ($(window).height() - $($selectpic).height()) / 2;
            var left = ($(window).width() - $($selectpic).width()) / 2;

            $selectpic.css({top: top + "px", left: left + "px"});
            $selectpic.slideDown(200);
        }

        function hideTips() {
            $selectpic.slideUp(200);
        }

        var timer;
        $inputs.parent().hover(function () {
            //showTips($(this));
            //layer.msg("请选择jpg,jpeg,png类型的图片", {time: 50000});
            clearTimeout(timer);
            timer = setTimeout(function () {
                showTips();
            }, 350);

        }, function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                hideTips();
            }, 1000);
        });


        var $form_45064 = $(".form_45064");
        var $form_45063 = $(".form_45063");
        //改变表单
        function changeForm(value) {
            if (value == 45063) {
                //企业
                $form_45064.slideUp();
                $form_45063.slideDown(function () {
                    //回调函数
                    //validate插件重新显示结果
                    $myform.showResult();
                });
                //$form_45064.find("input").attr("required",false);
            } else {
                //机构
                $form_45064.slideDown(function () {
                    //回调函数
                    //validate插件重新显示结果
                    $myform.showResult();
                });
                $form_45063.slideUp();
                //$form_45064.find("input").attr("required",true);
            }
        }

        //表单切换
        $('input[name=mysql_usertype]').click(function () {
            //console.log("表单切换");
            //用户类型切换
            var $this = $(this);
            changeForm($this.val());
        });

        //默认表单的显示
        changeForm(<?php echo $model['usertype'];?>);


        /**
         * 三证选择box
         */
        var $sanzhengbox = $('input[type=radio][name=mysql_sanzheng]');

        /**
         * 改变输入框的状态
         */
        function changeInputsState(value) {

            $inputs.each(function () {
                var $this = $(this);
                if ($this.attr("radiovalue") == value) {
                    //显示
                    $this.parent().slideDown(function () {
                        //回调函数
                        //validate插件显示结果
                        $myform.showResult();
                    });
                    //表单验证
                    $this.attr("required", true);
                } else {
                    //隐藏
                    $this.parent().slideUp(function () {
                        //回调函数
                        //validate插件显示结果
                        $myform.showResult();
                    });
                    //取消表单验证
                    $this.attr("required", false);
                }
            });

            //也要隐藏validate插件带来的错误显示
            $("div.succ,div.error").slideUp();
        }

        $sanzhengbox.click(function () {
            var $this = $(this);
            changeInputsState($this.val());
        });

        //一开始选择不是三证合一
        var value = <?php echo $model['sanzheng']?>;
        $sanzhengbox.each(function () {
            var $this = $(this);
            if ($this.val() == value) {
                $this.attr("checked", true);
            }
        });
        changeInputsState(value);

        //测试
        $inputs.each(function () {
            var $this = $(this);

        });
    });

    $(document).ready(function () {
        $('#btn_test').click(function () {

        });
    })
</script>
</body>
</html>