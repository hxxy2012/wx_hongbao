<?php
/**
 * 重点项目管理 ----  添加或者修改页面
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
    <title><?php echo $title; ?>_重点项目管理 </title>
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
    <link href="/admin_application/views/static/Js/mailAutoComplete/mailAutoComplete.css" rel="stylesheet"
          type="text/css">

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
                所在镇区：
            </td>
            <td>
                <select name="mysql_zhenqu_id" id="zhenqu_id" required>
                    <option value="-1">请选择镇区</option>
                    <?php
                    foreach ($town as $item) {
                        $selected = $item['id'] == $model['zhenqu_id'] ? "selected" : "";
                        echo "<option value='{$item['id']}' $selected>{$item['name']}</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                企业名称：
            </td>
            <td>
                <input type="text" name="mysql_companyname" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['companyname']; ?>">
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                项目名称：
            </td>
            <td>
                <input type="text" name="mysql_pro_name" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['pro_name']; ?>">
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                拟投资国家（地区）：
            </td>
            <td>
                <input type="text" name="mysql_guojia" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['guojia']; ?>">
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                预计投资规模(万美元) ：
            </td>
            <td>
                <input type="text" name="mysql_guimo" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['guimo']; ?>">
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                联系人：
            </td>
            <td >
                <input type="text" name="mysql_linkman" style="width:200px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['linkman']; ?>">
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                联系手机：
            </td>
            <td>
                <input type="text" name="mysql_tel" style="width:200px;"
                       minLength="1" valtype="mobile"
                       value="<?php echo $model['tel']; ?>">
            </td>
        </tr>

        <tr>
            <td width="100" class="tableleft">
                备注：
            </td>
            <td>
                <input type="text" name="mysql_beizhu" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['beizhu']; ?>">
            </td>
        </tr>

        <!--添加按钮-->
        <tr>
            <td class="tableleft"></td>
            <td>
                <input type="submit" class="btn button-warning" name="btn_save" value="保存"/>
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

<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
<!--表单验证-->
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<!--时间选择-->
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/laydate/laydate.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
<script type="text/javascript" src="/admin_application/views/static/Js/bootstrap.min.js"></script>
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


    /**
     * 表单提交进行验证
     * @returns {*|jQuery}
     */
    function postform() {
        var $select_town = $('#zhenqu_id');
        //选择一个镇区
        if ($select_town.val()==-1) {
            layer.msg("请选择一个镇区",{time:2000});
            return false;
        }

        return $("#myform").Valid();
    }

    $(document).ready(function () {

        //表单中需要输入小数的input
        var $input_xiaoshu = $("input[valtype='xiaoshu2']");

        //小数验证
        $input_xiaoshu.keyup(function () {
            var $this = $(this);
            //正则控制只能输入数字和小数点
            var value = $this.val();
            value = value.replace(/[^\d.]/g, '');
            $this.val(value);
        });

        $input_xiaoshu.blur(function () {
            //当输入框失去焦点时
            var $this = $(this);
            var str = $this.val();
            var m = str.length - str.replace(/\./g, "").length;//m为小数点个数
            if (m > 1) {//小数点个数大于1时提示错误
                //layer.msg('小数输入有误!', {time: 1000});
            } else if (m = 1) {
                //有1位小数点时判断小数点在最前和最后的情况（如果觉得没必要那么删除这整个else就行了）
                var str0 = str.substring(0, 1);
                var str1 = str.substring(str.length - 1, str.length);
                if (str0 == ".") {//如果小数点在最前，则在前面加上0
                    str = "0" + str;
                    $this.val(str);
                }
                if (str1 == ".") {//如果小数点在最后，则去除小数点
                    str = str.substring(0, str.length - 1);
                    $this.val(str);
                }
            }
        });

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
            $("input[type='submit']").slideUp();
        });
    </script>
<?php } ?>

</body>
</html>