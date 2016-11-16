<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator_plus.js"></script>
    <!--邮箱自动完成css-->
    <link href="/home/views/static/js/mailAutoComplete/mailAutoComplete.css" rel="stylesheet"/>
    <style>
        .pc_login label {
            width: 180px;
        }

        /*layer的tips*/
        body .tips-class .layui-layer-content {
            background-color: #7D571D;
            font-size: 15px;
            font-family: 'Microsoft Yahei', '微软雅黑', serif;
        }

        body .layer-load {
            background: transparent;
        }

        body .layer-load .layui-layer-content {
            text-align: center;
        }

        body .layer-load .layui-layer-content span {
            color: #FFFFFF;
        }

        body ul.emailist li {
            margin: auto;
            line-height: inherit;
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
              onsubmit="">
            <span class="pc_list_present">当前位置：<a href="<?php echo $admin_url; ?>">会员后台首页</a>-><a
                    href="<?php echo $cur_url; ?>">修改信息</a></span>
            <h3 class="pc_list_h3">修改信息</h3>
            <ul >
                <li  >
                    <i>*</i><label>单位属于：(不能更改)</label>
                    <?php
                    foreach ($usertype_list as $item) {
                        $checked = $model['usertype'] == $item['id'] ? "checked" : "";
                        echo "<input type='radio' value='{$item['id']}' {$checked} disabled/>{$item['name']}";
                    }
                    ?>
                </li>
                <li  >
                    <i>*</i><label>简称/用户名：(不能更改)</label>
                    <input type="text" name="mysql_username" placeholder="请输入简称或者用户名："
                           value="<?php echo $model['username']; ?>"
                           disabled>
                </li>
                <?php if ($sess['usertype'] == '45064') { ?>
                    <!--机构用户才显示服务类型-->
                    <li class="form_45064 fix" style="display: flex;">
                        <i>*</i><label>服务类型：</label>
                        <?php
                        foreach ($server_type_list as $item) {
                            $pos = strpos($model["server_type"], $item["id"]);
                            $selected = "";//是否选中
                            if ($pos !== false && $pos >= 0) {
                                $selected = "checked";
                            }
                            echo "<label style='width: auto'><input type='checkbox' name='server_type[]' value='{$item['id']}' {$selected} />{$item['name']}</label>";
                        }
                        ?>
                    </li>
                <?php } ?>
                <li  >
                    <i>*</i><label>公司全称：</label>
                    <input type="text" name="mysql_company" placeholder="请输入公司全称" valtype="my_require"
                           remoteurl="<?php echo site_url("home/chkfullname")."?id={$sess["userid"]}"; ?>"
                           remote_notValidValue="<?php echo $model['company']; ?>"
                           value="<?php echo $model['company']; ?>"
                           required>
                </li>
                <li  >
                    <i>*</i><label>公司地址：</label>
                    <input type="text" name="mysql_addr" placeholder="请输入公司地址" value="<?php echo $model['addr']; ?>"
                           valtype="my_require"
                           required>
                </li>
                <li  >
                    <i>*</i><label>姓名：</label>
                    <input type="text" name="mysql_realname" placeholder="请输入真实姓名"
                           valtype="my_require"
                           value="<?php echo $model['realname']; ?>" required>
                </li>
                <li  >
                    <i>*</i><label>手机号码：</label>
                    <input type="text" name="mysql_tel" placeholder="请输入手机号码" valtype="mobile"
                           remoteurl="<?php echo site_url("home/chktel")."?id={$sess["userid"]}"; ?>"
                           remote_notValidValue="<?php echo $model['tel']; ?>"
                           value="<?php echo $model['tel']; ?>"
                           required>
                </li>
                <li style="position: relative"  >
                    <i>*</i><label>邮箱：</label>
                    <input type="text" name="mysql_email" placeholder="请输入邮箱" valtype="email"
                           remoteurl="<?php echo site_url("home/chkemail")."?id={$sess["userid"]}"; ?>"
                           remote_notValidValue="<?php echo $model['email']; ?>"
                           value="<?php echo $model['email']; ?>"
                           required>
                </li>
                <li  >
                    <i>*</i>
                    <label>
                        是否三证合一
                    </label>
                    <input type="radio" name="mysql_sanzheng" value="1">是
                    <input type="radio" name="mysql_sanzheng" value="2">否
                </li>
                <li  >
                    <i>*</i><label>三证：</label>
                    <input id="input_sanzheng" type="file" radiovalue="1" name="file_sanzheng" accept="image/*"
                           pic_url="<?php echo $model['fujian_sanzheng'] ?>">
                </li>

                <li  >
                    <i>*</i><label>营业执照：</label>
                    <input id="file_yingye" type="file" radiovalue="2" name="file_yingye" accept="image/*"
                           pic_url="<?php echo $model['fujian_gongshang'] ?>">
                </li>

                <li  >
                    <i>*</i><label>组织结构代码证：</label>
                    <input id="file_zuzhi" type="file" radiovalue="2" name="file_zuzhi" accept="image/*"
                           pic_url="<?php echo $model['fujian_zuzhi'] ?>">
                </li>

                <li  >
                    <i>*</i><label>税务登记证：</label>
                    <input id="file_shuiwu" type="file" radiovalue="2" name="file_shuiwu" accept="image/*"
                           pic_url="<?php echo $model['fujian_shuiwu'] ?>">
                </li>

                <?php if ($sess['usertype'] == '45063') { ?>
                    <!--只有企业用户才显示-->
                    <li style="clear: both;margin-bottom: 30px"  >
                        <i style="visibility: hidden">*</i><label>企业境外投资证书：</label>
                        <input id="file_touzizhengshu" type="file" name="file_touzizhengshu" accept="image/*"
                               pic_url="<?php echo $model['fujian_touzizhengshu'] ?>">
                    </li>
                <?php } ?>

                <!--验证码-->
                <li  >
                    <i>*</i>
                    <label>验证码：</label>
                    <!--valType='yzm'-->
                    <input type="text" maxlength="4" name="yzm"
                           style="width:80px;text-transform: uppercase"
                           tipstyle="bottom"
                           valType='yzm'
                           remoteUrl="<?php echo site_url("home/chkcode"); ?>"
                           placeholder="请输入验证码" id="inputCode" class="" required/>
                    <div class="code"
                         id="checkCode"
                         onclick="document.getElementById('codeimg').src='<?php echo site_url("home/code"); ?>?rnd='+Math.random();">
                        <img id="codeimg" src="<?php echo site_url("home/code"); ?>"/>
                    </div>
                </li>
            </ul>
            <br>
            <br>
            <div style="float: left;">
                <input type="hidden" name="reupload_pic" value="-1"/>
                <input type="button" id="btn_submit" value="提交"/>
                <input type="reset" id="btn_reset" value="重置"/>
            </div>
            <div style="float: left;clear: both;">
                <span style="color: #5f4e36;">注意：重新修改资料，需要审核通过才能使用系统的其它功能，审核期间禁止登录</span>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

<!--邮箱自动完成-->
<script src="/home/views/static/js/mailAutoComplete/jquery.mailAutoComplete-4.0.js"></script>
<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<script type="text/javascript">
    $(function () {
        <?php if (isset($message)) {
        echo "layer.msg('{$message}', {time: 3000})";
    }
        ?>
    });

    /**
     * 打开加载窗口
     */
    function openload(text) {
        var content = [
            '<img src="' + '/home/views/static/js/layer/skin/default/loading-2.gif' + '"/>',
            '<br>',
            '<span>' + text + '</span>'
        ].join("\n");

        openbox = layer.open({
            type: 1,
            title: false,//不显示
            shadeClose: true,
            shade: [0.8, '#393D49'],
            closeBtn: 0,//不显示
//            area: ['100%', '100%'],
            content: content,
            skin: "layer-load"
        });
    }

    /**
     * 检查表单
     */
    function chkform() {

        var $myform = $("#myform");


        //服务类型选择,只有机构用户
        var obj = document.getElementsByName("server_type[]");
        //console.log(obj);
        if (obj.length > 0) {
            var num = 0;
            for (var i = 0; i < obj.length; i++) {
                if (obj[i].checked) {
                    num++;
                }
            }
            if (num == 0) {
                layer.msg('请选择至少选中一个服务类型!', {time: 1000});
                return;
            }
        }

        //继续表单验证
        var valid_result = $myform.Valid();
        console.log("表单验证结果：" + valid_result);


        if (valid_result) {
            chkSelectedFile();
        }

    }

    /**
     * 提示上传文件
     */
    function chkSelectedFile() {
        var $myform = $("#myform");

        //文件input元素的合集
        var $inputs = $("input[type=file]");
        //表示是否重新上传图片的input元素
        var $reupload = $('input[name=reupload_pic]');

        //是否选择重新上传文件
        var selectedFile = false;

        //检验是否有无选择重新选择文件
        $inputs.each(function () {
            if ($(this).val() != "") {
                selectedFile = true;
            }
        });


        var index = layer.confirm('重新修改资料，需要审核通过才能使用系统，审核期间禁止登录', {
            btn: ['确认', '取消'] //按钮
        }, function () {
            //表示重新上传了图片
            $reupload.val(1);

            //关闭layer
            layer.close(index);
            openload("正在提交...");
            //提交表单
            $myform.submit();
        }, function () {

        });

//        if (selectedFile) {
//            //选择重新上传文件就提示
//
//        } else {
//            //表示没有重新上传了图片
//            $reupload.val(-1);
//
//            //提交表单
//            openload("正在提交...");
//            $myform.submit();
//        }
    }


    $(document).ready(function () {
        //表单
        var $myform = $('#myform');

        //邮箱自动完成
        var $input_email = $("input[name='mysql_email']");
        var $email_ul = $input_email.mailAutoComplete({zIndex: 10000}).siblings("ul.emailist");
        //console.log($email_ul);
        $email_ul.css("marginLeft", $input_email.position().left + "px");

        //文件input元素的合集
        var $inputs = $("input[type=file]");

        /**
         * 上传文件的tips显示
         */
        var layer_index = 0;
        $inputs.hover(function () {
            //进入函数
            var that = $(this);
            //不通过理由
            var text = "请选择jpg,jpeg,png类型的图片";
            layer_index = layer.tips(text, that, {skin: "tips-class", tips: 3});
        }, function () {
            //离开函数
            var that = $(this);
            layer.close(layer_index);
        });

        /**
         * 改变输入框的状态
         */
        function changeInputsState(value) {


            $sanzheng_inputs.each(function () {
                var $this = $(this);
                if ($this.attr("radiovalue") == value) {
                    //显示
                    $this.parent().slideDown(function () {
                        //回调函数
                        //validate插件重新显示结果
                        $myform.showResult();
                    });
                    //表单验证
                    //$this.attr("required", true);
                } else {
                    //隐藏
                    $this.parent().slideUp(function () {
                        //回调函数
                        //validate插件重新显示结果
                        $myform.showResult();
                    });
                    //取消表单验证
                    //$this.attr("required", false);
                }
            });

            //也要隐藏validate插件带来的错误显示
            $("div.succ,div.error").slideUp();
        }

        //三证上传
        var $sanzheng_inputs = $("input[type=file][radiovalue]");
        var $input_sanzheng = $('#input_sanzheng');

        /**
         * 三证选择box
         */
        var $sanzhengbox = $('input[type=radio][name=mysql_sanzheng]');
        $sanzhengbox.click(function () {
            var $this = $(this);
            changeInputsState($this.val());
        });

        //一开始选择不是三证合一
        var value = '<?php echo $model['sanzheng']?>';
        $sanzhengbox.each(function () {
            var $this = $(this);
            if ($this.val() == value) {
                $this.attr("checked", true);
            }
        });
        changeInputsState(value);

        //创建图片
        $inputs.each(function () {
            var $this = $(this);
            var pic_url = $this.attr("pic_url");
            if (pic_url != "") {
                var html =
                    ['<a href="/' + pic_url + '" target="_blank" style="float: left">',
                        '<img src="/' + pic_url + '" style="width: 100px;">',
                        '</a>'].join("");
                $(html).appendTo($this.parent().css("height", "100px"));
            }
        });

        //重置按钮
        var $btn_reset = $('#btn_reset');
        $btn_reset.click(function () {
            changeInputsState(value);
        });

        //提交按钮
        var $btn_submit = $('#btn_submit');
        $btn_submit.click(function () {
            chkform();
        });

//        layer.load(1, {content: "",shade: [0.8, '#393D49']});
    });
</script>
</body>
</html>