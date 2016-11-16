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
    <script type="text/javascript" src="/home/views/static/js/validate/validator_plus.js"></script>
    <!--邮箱自动完成css-->
    <link href="/home/views/static/js/mailAutoComplete/mailAutoComplete.css" rel="stylesheet"
          type="text/css">
    <style>
        .pc_login label {
            width: 140px;
        }

        .way_table {
            border: 0px !important;
        }

        .mytable2 td {
            background: #fff;
            padding: 3px;
        }

        input {
            border-style: groove;
            background-color: #fff;
        }

        /*提交按钮*/
        .btn-submit {
            background: #00448c;
            width: auto;
            height: 33px;
            color: #fff;
            border: 1px solid #2f80d5;
            padding: 5px 30px;
            font-size: 16px;
            margin: 20px 0;
        }

        .btn-save:hover {
            background: #666666;
        }

        /*保存按钮*/
        .btn-save {
            width: auto;
            height: 33px;
            color: #000;
            border: 1px solid #cccccc;
            padding: 5px 30px;
            font-size: 16px;
            margin: 20px 0;
        }

        .xinxi {
            text-align: center;
        }

        body .xinxi span {
            font-family: 黑体, sans-serif;
            font-size: 16px;
            /*margin-bottom: 10px;*/
            line-height: 100%;
        }

        .pc_member_background_list table {
            margin-top: 5px;
        }

        /*返回按钮*/
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

        <div class="pc_member_background_list">
            <span class="pc_list_present">当前位置：<a href="<?php echo $admin_url; ?>">会员后台首页</a>-><a
                    href="<?php echo get_url();//$cur_url; ?>">对外投资联系表</a></span>
            <h3 class="pc_list_h3"><?php echo $title; ?></h3>

            <div class="search">
                <a href="<?php echo site_url($controller . "/lists"); ?>" class="back_btn">返回</a>
            </div>

            <form enctype="multipart/form-data" action="<?php echo $form_url; ?>" onSubmit="return postform()"
                  method="post" name="myform" id="myform">
                <?php
                if ($type == "edit") {
                    echo "<input type='hidden' name='id' value='{$model['id']}'>";
                }

                ?>

                <div
                    style="text-align:center; font-family: '楷体',sans-serif; font-size:20px; font-weight: bold; margin-bottom: 10px; line-height: 100%;">
                    对外投资联系表
                    <?php
                    if ($model['id'] != "") {
                        echo "编号:({$model['id']})";
                    }
                    ?>
                </div>
                <div class="xinxi">
                    <?php
                    switch ($model['check_status']) {
                        case '0':
                            $text = "[未提交]";
                            echo "<span style='color: #74cc40;'>{$text}</span>";
                            break;
                        case '1':
                            $text = "[未审核]";
                            echo "<span style='color: #6790cc;'>{$text}</span>";
                            break;
                        case '2':
                            $text = "[通过]";
                            echo "<span style='color: #8FCC33;'>{$text}</span>";
                            break;
                        case '3':
                            $text = "[退回]";
                            echo "<span style='color:red;' class='not-check'>{$text}</span>";
                            echo "<br>";
                            echo "<span style='color: darkslategrey;'>退回原因:{$model['check_content']}</span>";
                            break;
                    }
                    ?>
                </div>
                <table class="mytable2" style="font-size: 16px" width="100%" bgcolor="#cccccc" cellspacing="1"
                       cellpadding="0">

                    <tr>
                        <td width="120" class="tableleft">
                            国内企业名称：
                        </td>
                        <td colspan="5">
                            <input type="text" name="mysql_company_name" style="width:90%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['company_name']; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td width="100" class="tableleft">
                            境外企业名称：
                        </td>
                        <td colspan="5">
                            <input type="text" name="mysql_company_name2" style="width:90%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['company_name2']; ?>">
                        </td>

                    </tr>

                    <tr>
                        <td class="tablecenter"></td>
                        <td class="tablecenter">境外联系人</td>
                        <td class="tablecenter">项目联系人</td>
                        <td class="tablecenter">统计报表联系人</td>
                    </tr>
                    <tr>
                        <td class="tablecenter">姓名：</td>
                        <td>
                            <input type="text" name="mysql_sea_realname" style="width:80%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['sea_realname']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_pro_realname" style="width:80%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['pro_realname']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_stat_realname" style="width:80%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['stat_realname']; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td class="tablecenter">职务：</td>
                        <td>
                            <input type="text" name="mysql_sea_zhiwei" style="width:80%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['sea_zhiwei']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_pro_zhiwei" style="width:80%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['pro_zhiwei']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_stat_zhiwei" style="width:80%;"
                                   minLength="1" valtype="my_require"
                                   value="<?php echo $model['stat_zhiwei']; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td class="tablecenter">座机：</td>
                        <td>
                            <input type="text" name="mysql_sea_zuoji" style="width:80%;"
                                   minLength="1" valtype="telphone"
                                   value="<?php echo $model['sea_zuoji']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_pro_zuoji" style="width:80%;"
                                   minLength="1" valtype="telphone"
                                   value="<?php echo $model['pro_zuoji']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_stat_zuoji" style="width:80%;"
                                   minLength="1" valtype="telphone"
                                   value="<?php echo $model['stat_zuoji']; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td class="tablecenter">手机：</td>
                        <td>
                            <input type="text" name="mysql_sea_tel" style="width:80%;"
                                   minLength="1" valtype="mobile"
                                   value="<?php echo $model['sea_tel']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_pro_tel" style="width:80%;"
                                   minLength="1" valtype="mobile"
                                   value="<?php echo $model['pro_tel']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_stat_tel" style="width:80%;"
                                   minLength="1" valtype="mobile"
                                   value="<?php echo $model['stat_tel']; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td class="tablecenter">电子邮件：</td>
                        <td>
                            <input type="text" name="mysql_sea_email" style="width:80%;"
                                   minLength="1" valtype="email" validSettings="{tipStyle:'bottom'}"
                                   value="<?php echo $model['sea_email']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_pro_email" style="width:80%;"
                                   minLength="1" valtype="email" validSettings="{tipStyle:'bottom'}"
                                   value="<?php echo $model['pro_email']; ?>">
                        </td>
                        <td>
                            <input type="text" name="mysql_stat_email" style="width:80%;"
                                   minLength="1" valtype="email" validSettings="{tipStyle:'bottom'}"
                                   value="<?php echo $model['stat_email']; ?>">
                        </td>
                    </tr>

                    <!--添加按钮-->
                    <!--                    <tr>-->
                    <!--                        <td class="tableleft"></td>-->
                    <!--                        <td colspan="5">-->
                    <!--                            <input type="submit" class="btn button-warning" name="btn_save" value="保存"/>-->
                    <!--                        </td>-->
                    <!--                    </tr>-->

                </table>

                <?php if ($model['check_status'] == '3') { ?>
                    <div>
                        <!--                        <span style="color: #5f4e36;">退回原因：-->
                        <?php //echo $model['check_content']; ?><!--</span>-->
                    </div>
                <?php } ?>

                <div style="text-align: center">
                    <input type="submit" class="btn-save" value="保存" name="save" id="temp_save"/>
                    <?php
                    //新建模式
                    if ($type=="add"){
                        echo "<input type='submit' class='btn-submit' value='提交审核' name='submit'/>";
                    }
                    //编辑模式
                    if ($type == "edit") {
                        //echo "<input type='submit' class='btn-save' value='保存' name='save' id='temp_save'/>";
                        if ($model['check_status'] == 1) {
                            //未审核
                            echo "<input type='button' class='btn-submit' value='已提交审核' name='submit'>";
                        } elseif ($model['check_status'] == 3) {
                            //3退回
                            echo "<input type='submit' class='btn-submit' value='重新提交审核' name='submit'>";
                        } else {
                            echo "<input type='submit' class='btn-submit' value='提交审核' name='submit'>";
                        }
                    }
                    ?>
                </div>
            </form>

        </div>

    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>


<!--邮箱自动完成-->
<script src="/home/views/static/js/mailAutoComplete/jquery.mailAutoComplete-4.0.js"></script>

<script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
<script type="text/javascript" src="/home/views/static/js/laydate/laydate.js"></script>
<script type="text/javascript">
    /**
     * 表单提交进行验证
     * @returns {*|jQuery}
     */
    function postform() {

        //检测座机，手机，邮箱至少填一个
        var list1 = ['mysql_sea_', 'mysql_stat_', 'mysql_pro_'];
        var list2 = ['tel', 'email', 'zuoji'];
        var error_hint = ["境外联系人", "统计报表联系人", "项目联系人"];
        for (var i = 0; i < list1.length; i++) {
            //没有填的input的数量
            var blank_count = 0;

            for (var j = 0; j < list2.length; j++) {
                //检测input元素的是否为空
                if ($('input[name=' + list1[i] + list2[j] + ']').val() == "") {
                    blank_count++;
                }
            }

            if (blank_count == list2.length) {
                layer.msg(error_hint[i] + "的联系方式（座机，手机，邮箱）至少填一个!");
                return false;
            }
        }

        return $("#myform").Valid();
    }

    $(document).ready(function () {
        //邮箱自动完成
        $("input[name='mysql_stat_email'],input[name='mysql_sea_email'],input[name='mysql_pro_email']").mailAutoComplete({zIndex: 10000});
    });
</script>

<!--查看模式的代码-->
<?php if ($type == "view") { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var $inputs = $('input,select');
            $inputs.attr("readonly", true);
            $inputs.attr("disabled", true);
            $inputs.attr("required", false);
            $("input[type=submit],input[type=button]").slideUp();
        });
    </script>
<?php } ?>

</body>
</html>