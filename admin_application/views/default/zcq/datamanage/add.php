<?php
/**
 * 走出去数据管理增加页面
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
    <head>
        <title>新增数据管理</title>
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
</head>

<body>

<form enctype="multipart/form-data" action="<?php echo site_url("zcq_datamanage/doadd"); ?>" onsubmit="return postform()"
      method="post" name="myform" id="myform">

    <table width="99%" bgcolor="#FFFFFF" border="0" cellpadding="3" cellspacing="1"
           class="table table-bordered table-hover definewidth">
        <!-- 1.报表时间-->
        <!-- 4.地区-->
        <tr>
            <td width="100" class="tableleft">
                报表时间：
            </td>
            <td><input type="text" style="width:150px;" valType="date" placeholder="报表时间" name="mysql_baobiao_time"
                       id="baobiao_time"
                       onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#baobiao_time').focus();}})"
                       value="<?php echo date("Y-m-d", time()) ?>"/></td>
            <!--YYYY-MM-DD hh:mm:ss-->
            <td width="100" class="tableleft">
                地区：
            </td>
            <td><input type="text" name="mysql_addr" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
        </tr>
        <!-- 2.报部状态-->
        <!-- 3.报表状态-->
        <tr>
            <td width="100" class="tableleft">
                报部状态：
            </td>
            <td><input type="text" name="mysql_baobu_status" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                报表状态：
            </td>
            <td><input type="text" name="mysql_baobiao_status" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
        </tr>
        <!-- 5.境内企业名称-->
        <!-- 6.境外项目编码-->
        <tr>
            <td width="100" class="tableleft">
                境内企业名称：
            </td>
            <td><input type="text" name="mysql_company" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                境外项目编码：
            </td>
            <td><input type="text" name="mysql_company2_code" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
        </tr>
        <!-- 7.境外项目名称-->
        <!-- 10.国别-->
        <tr>
            <td width="100" class="tableleft">
                境外项目名称：
            </td>
            <td><input type="text" name="mysql_company2" style="width:400px;"
                                   minLength="1" valtype=""
                                   value="15361363220">
            </td>
            <td width="100" class="tableleft">
                国别：
            </td>
            <td>
                <input type="text" name="mysql_guobie" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
        </tr>
        <!-- 8.申请事项-->
        <!-- 9.业务类型-->
        <tr>
            <td width="100" class="tableleft">
                申请事项：
            </td>
            <td><input type="text" name="mysql_shenqing" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                <label for="mysql_yewu_type"> 业务类型：</label>
            </td>
            <td><input type="text" name="mysql_yewu_type" id="mysql_yewu_type" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
        </tr>
        <!-- 11.投资类型-->
        <!-- 12.投资方式-->
        <tr>
            <td width="100" class="tableleft">
                投资类型：</label>
            </td>
            <td>
                <input type="text" name="mysql_touzi_type" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                投资方式：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_touzi_fangshi" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td></td>
        </tr>
        <!-- 13.中方实际投资总额-->
        <tr>
            <td width="100" class="tableleft">
                中方实际投资总额：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_zonge" style="width:400px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
            <td></td>
        </tr>
        <!-- 14.其中：货币投资-->
        <!--15.其中：自有资金-->

        <tr>
            <td width="100" class="tableleft">
                其中：货币投资：
            </td>
            <td>
                <input type="text" name="mysql_huobi" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                其中：自有资金：
            </td>
            <td>
                <input type="text" name="mysql_ziyou" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
        </tr>
        <!--16.其中：银行贷款-->
        <!--17.其中：其他-->
        <tr>
            <td width="100" class="tableleft">
                其中：银行贷款：
            </td>
            <td>
                <input type="text" name="mysql_yinhang" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                其中：其他：
            </td>
            <td>
                <input type="text" name="mysql_other" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
        </tr>
        <!--18.其中：实物投资-->
        <!--19.其中：无形资产投资-->
        <tr>
            <td width="100" class="tableleft">
                其中：实物投资：
            </td>
            <td>
                <input type="text" name="mysql_shiwu" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                其中：无形资产投资：
            </td>
            <td>
                <input type="text" name="mysql_wuxing" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
        </tr>
        <!--20.其中：新增股权-->
        <!--21.其中：新增债务工具-->
        <tr>
            <td width="100" class="tableleft">
                其中：新增股权：
            </td>
            <td>
                <input type="text" name="mysql_xinzeng" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                其中：新增债务工具：
            </td>
            <td>
                <input type="text" name="mysql_xinzeng_zhai" style="width:200px;"
                       minLength="1" valtype="xiaoshu"
                       value="15361363220">
            </td>
        </tr>
        <!--22.项目负责人-->
        <!--23.联系人-->
        <tr>
            <td width="100" class="tableleft">
                联系人：
            </td>
            <td>
                <input type="text" name="mysql_linkman" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                项目负责人：
            </td>
            <td>
                <input type="text" name="mysql_fuzeren" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
        </tr>
        <!--24.联系人手机-->
        <tr>
            <td width="100" class="tableleft">
                联系人手机：
            </td>
            <td>
                <input type="text" name="mysql_tel" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td></td>
        </tr>
        <!--25.填报人-->
        <!--26.填报时间-->
        <tr>
            <td width="100" class="tableleft">
                填报人：
            </td>
            <td>
                <input type="text" name="mysql_tianbaoren" style="width:400px;"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
            <td width="100" class="tableleft">
                填报时间：
            </td>
            <td>
                <input type="text" style="width:150px;" valType="date" placeholder="填报时间" name="mysql_tianbao_time"
                       id="tianbao_time"
                       onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#tianbao_time').focus();}})"
                       value="<?php echo date("Y-m-d", time()) ?>"/>
            </td>
        </tr>
        <!--27.备注-->
        <tr>
            <td width="100" class="tableleft">
                备注：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_beizhu" style="width:400px;height: 30px"
                       minLength="1" valtype=""
                       value="15361363220">
            </td>
        </tr>
        <!--添加按钮-->
        <tr>
            <td class="tableleft"></td>
            <td>
                <input type="submit" class="btn button-warning" name="btn_save" value="15361363220"/>
                <a class="btn btn-primary" id="addnew"
                   onClick="top.topManager.closePage();">关闭</a>
            </td>
            <td></td>
        </tr>
    </table>
</form>


<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
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

    /**
     * 表单提交进行验证
     * @returns {*|jQuery}
     */
    function postform() {
        return $("#myform").Valid();
    }

    $(document).ready(function () {

    });
</script>

</body>

</html>