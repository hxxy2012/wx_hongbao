<?php
/**
 * 项目管理 ----  添加或者修改页面
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
    <title><?php echo $title; ?>_项目管理 </title>
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
                国内投资主体名称：
            </td>
            <td>
                <input type="text" name="mysql_companyname" style="width:360px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['companyname']; ?>">
            </td>
            <td></td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                境外企业名称：
            </td>
            <td>
                <input type="text" name="mysql_companyname2" style="width:360px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['companyname2']; ?>">
            </td>
            <td width="100" class="tableleft">
                国别（地区）：
            </td>
            <td>
                <input type="text" name="mysql_guojia" style="width:200px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['guojia']; ?>">
            </td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                批准设立日期：
            </td>
            <td>
                <input type="text" style="width:150px;" valType="date" placeholder="批准设立日期" name="mysql_sheli_date"
                       id="sheli_date"
                       onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#sheli_date').focus();}})"
                       value="<?php echo $model['sheli_date']; ?>"/>
            </td>
            <td width="100" class="tableleft">
                设立方式：
            </td>
            <td>
                <input type="text" name="mysql_sheli_fangshi" style="width:200px;"
                       minLength="1" valtype="my_require" tipstyle="bottom"
                       value="<?php echo $model['sheli_fangshi']; ?>">
            </td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                中方持股比例：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_bili" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['bili']; ?>">
            </td>
            <td></td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                注册资本（万美元）：
            </td>
            <td>
                <input type="text" name="mysql_reg_ziben" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['reg_ziben']; ?>">
            </td>
            <td width="100" class="tableleft">
                协议投资额（万美元）：
            </td>
            <td>
                <input type="text" name="mysql_xieyi_touzi" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['xieyi_touzi']; ?>">
            </td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                中方投资额（万美元）：
            </td>
            <td>
                <input type="text" name="mysql_zhongfang_touzi" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['zhongfang_touzi']; ?>">
            </td>
            <td width="100" class="tableleft">
                中方实际投资额（万美元）：
            </td>
            <td>
                <input type="text" name="mysql_zhongfang_shiji_touzi" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['zhongfang_shiji_touzi']; ?>">
            </td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                经营范围：
            </td>
            <td>
                <input type="text" name="mysql_jingyin_fanwei" style="width:360px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['jingyin_fanwei']; ?>">
            </td>
            <td width="100" class="tableleft">
                所属行业：
            </td>
            <td colspan="2">
                <input type="text" name="mysql_hangye" style="width:360px;"
                       minLength="1" valtype="my_require" tipstyle="bottom"
                       value="<?php echo $model['hangye']; ?>">
            </td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                批准文号：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_wenhao" style="width:200px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['wenhao']; ?>">
            </td>
            <td></td>
        </tr>
        <tr>

        </tr>
        <tr>
            <td width="100" class="tableleft">
                地址：
            </td>
            <td>
                <input type="text" name="mysql_addr" style="width:360px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['addr']; ?>">
            </td>
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
                性质：
            </td>
            <td>
                <input type="text" name="mysql_xingzhi" style="width:360px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['xingzhi']; ?>">
            </td>
            <td width="100" class="tableleft">
                经营情况：
            </td>
            <td>
                <input type="text" name="mysql_jingyin_qingkuang" style="width:360px;"
                       minLength="1" valtype="my_require" tipstyle="bottom"
                       value="<?php echo $model['jingyin_qingkuang']; ?>">
            </td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                再投资记录：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_touzi_jilu" style="width:360px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['touzi_jilu']; ?>">
            </td>
            <td></td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                联系人：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_linkman" style="width:200px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['linkman']; ?>">
            </td>
            <td></td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                联系手机：
            </td>
            <td>
                <input type="text" name="mysql_mobile" style="width:200px;"
                       minLength="1" valtype="mobile"
                       value="<?php echo $model['mobile']; ?>">
            </td>
            <td width="100" class="tableleft">
                联系电话：
            </td>
            <td>
                <input type="text" name="mysql_tel" style="width:200px;"
                       minLength="1" valtype="telphone" placeholder="例如0755-88880000"
                       value="<?php echo $model['tel']; ?>">
            </td>
        </tr>
        <tr>
            <td width="100" class="tableleft">
                传真：
            </td>
            <td>
                <input type="text" name="mysql_fax" style="width:200px;"
                       minLength="1" valtype="my_require"
                       value="<?php echo $model['fax']; ?>">
            </td>
            <td width="100" class="tableleft">
                邮箱：
            </td>
            <td>
                <input type="email" name="mysql_email" style="width:200px;"
                       minLength="1" valtype="email"
                       value="<?php echo $model['email']; ?>">
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
            <td></td>
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

        //邮箱自动完成
        $("input[type=email]").mailAutoComplete();//使用方法
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