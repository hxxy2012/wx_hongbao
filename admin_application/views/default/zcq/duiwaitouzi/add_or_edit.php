<?php
/**
 * 对外投资管理 ----  添加或者修改页面
 * User: 嘉辉
 * Date: 2016-08-08
 * Time: 15:16
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?>_对外投资管理 </title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css"/>
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css"
          rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css"
          rel="stylesheet"
          type="text/css"/>
    <!--邮箱自动完成-->
    <link href="/admin_application/views/static/Js/mailAutoComplete/mailAutoComplete.css" rel="stylesheet"
          type="text/css">
    <!--自动完成-->
    <link type="text/css" rel="stylesheet"
          href="/admin_application/views/static/Js/jquery-ui-1.12.0.custom/jquery-ui.min.css">

    <style type="text/css">
        body {
            padding-bottom: 40px;
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

        #myform .tablecenter {
            text-align: center;
            padding-left: 5px;
            background-color: #f5f5f5;
        }

        input:disabled {
            cursor: default;
            /*color:#ACA899;*/
            /*border-style: groove;*/
            /*background-color: #fff;*/
        }
    </style>

</head>

<body>

<form enctype="multipart/form-data" action="<?php echo $form_url; ?>" onsubmit="return postform()"
      method="post" name="myform" id="myform">

    <?php
    if ($type == "edit") {
        echo "<input type='hidden' name='id' value='{$model['id']}'>";
    }
    ?>

    <table width="99%" bgcolor="#FFFFFF" border="0" cellpadding="3" cellspacing="1"
           class="table table-bordered table-hover definewidth">

        <tr>
            <td width="100" class="tableleft">
                国内企业名称：
            </td>
            <td colspan="5">
                <input type="text" name="mysql_company_name" style="width:80%;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['company_name']; ?>">
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                境外企业名称：
            </td>
            <td colspan="5">
                <input type="text" name="mysql_company_name2" style="width:80%;"
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
                <input type="text" name="mysql_sea_realname" style="width:70%;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['sea_realname']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_pro_realname" style="width:70%;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['pro_realname']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_stat_realname" style="width:70%;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['stat_realname']; ?>">
            </td>
        </tr>

        <tr>
            <td class="tablecenter">职务：</td>
            <td>
                <input type="text" name="mysql_sea_zhiwei" style="width:70%;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['sea_zhiwei']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_pro_zhiwei" style="width:70%;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['pro_zhiwei']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_stat_zhiwei" style="width:70%;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['stat_zhiwei']; ?>">
            </td>
        </tr>

        <tr>
            <td class="tablecenter">座机：</td>
            <td>
                <input type="text" name="mysql_sea_zuoji" style="width:70%;"
                       minLength="1" valtype="telphone"
                       value="<?php echo $model['sea_zuoji']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_pro_zuoji" style="width:70%;"
                       minLength="1" valtype="telphone"
                       value="<?php echo $model['pro_zuoji']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_stat_zuoji" style="width:70%;"
                       minLength="1" valtype="telphone"
                       value="<?php echo $model['stat_zuoji']; ?>">
            </td>
        </tr>

        <tr>
            <td class="tablecenter">手机：</td>
            <td>
                <input type="text" name="mysql_sea_tel" style="width:70%;"
                       minLength="1" valtype="mobile"
                       value="<?php echo $model['sea_tel']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_pro_tel" style="width:70%;"
                       minLength="1" valtype="mobile"
                       value="<?php echo $model['pro_tel']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_stat_tel" style="width:70%;"
                       minLength="1" valtype="mobile"
                       value="<?php echo $model['stat_tel']; ?>">
            </td>
        </tr>

        <tr>
            <td class="tablecenter">电子邮件：</td>
            <td>
                <input type="text" name="mysql_sea_email" style="width:70%;"
                       minLength="1" valtype="email"
                       value="<?php echo $model['sea_email']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_pro_email" style="width:70%;"
                       minLength="1" valtype="email"
                       value="<?php echo $model['pro_email']; ?>">
            </td>
            <td>
                <input type="text" name="mysql_stat_email" style="width:70%;"
                       minLength="1" valtype="email"
                       value="<?php echo $model['stat_email']; ?>">
            </td>
        </tr>


        <!--添加按钮-->
        <tr>
            <td class="tableleft"></td>
            <td colspan="5">
                <?php if ($type == "edit") { ?>
                <input type="submit" class="btn button-warning" name="btn_save" value="保存"/>
                <?}?>
                <a class="btn btn-primary" id="addnew"
                   onClick="top.topManager.closePage();">关闭</a>
            </td>
        </tr>
    </table>
</form>


<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<!--邮箱自动完成-->
<script src="/admin_application/views/static/Js/mailAutoComplete/jquery.mailAutoComplete-4.0.js"></script>

<!--手机自动完成-->
<script src="/admin_application/views/static/Js/jquery-ui-1.12.0.custom/jquery-ui.min.js"></script>

<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>

<?php if ($type != "view") { ?>
    <!--表单验证,查看模式不用-->
    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/validate/validator.js"></script>
<?php } ?>

<script src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<!--时间选择-->
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/laydate/laydate.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
<script type="text/javascript" src="/admin_application/views/static/Js/bootstrap.min.js"></script>
<script type="text/javascript" src="/admin_application/views/static/Js/jquery-ui-1.12.0.custom/phoneAutoComplete.js"></script>
<script type="text/javascript">

    //小数相加有关
    function accAdd(arg1, arg2) {
        var r1, r2, m;
        try {
            r1 = arg1.toString().split(".")[1].length
        } catch (e) {
            r1 = 0
        }
        try {
            r2 = arg2.toString().split(".")[1].length
        } catch (e) {
            r2 = 0
        }
        m = Math.pow(10, Math.max(r1, r2))
        return (arg1 * m + arg2 * m) / m
    }
    //给Number类型增加一个add方法，调用起来更加方便，以下类似
    Number.prototype.add = function (arg) {
        return accAdd(arg, this);
    };

    //手机自动完成
    $(function () {
        var $phone = $("input[name=mysql_sea_tel],input[name=mysql_pro_tel],input[name=mysql_stat_tel]");
        phoneAuto($phone);
    });

    /**
     * 表单提交进行验证
     * @returns {*","jQuery}
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
            //$inputs.attr("readonly", true);
            $inputs.attr("disabled", true);
            $inputs.attr("required", false);
            // 注释，不需要用到js代码bylk2016年9月9日16:48:59
            // $("input[type=submit]").slideUp();
        });
    </script>
<?php } ?>

</body>
</html>