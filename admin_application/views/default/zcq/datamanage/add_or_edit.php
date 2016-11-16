<?php
/**
 * 走出去数据管理
 * 添加或者修改页面
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
        <title><?php echo $title; ?>_走出去数据</title>
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

            /*加载窗口*/
            body .layer-load {
                background: transparent;
            }

            body .layer-load .layui-layer-content {
                text-align: center;
            }

            body .layer-load .layui-layer-content span {
                color: #FFFFFF;
            }

        </style>

    </head>
</head>

<body>

<form enctype="multipart/form-data" action="<?php echo $form_url; ?>" onsubmit="return postform()"
      method="post" name="myform" id="myform">

    <?php
    if ($type == "edit") {
        echo "<input type='hidden' name='id' value='{$model['id']}'>";
    }
    ?>

    <h1>
        该数据属于
        【<span id="select_name"><?php
            if ($model['username']!="") {
                echo $model['username'];
            }else{
                echo "请手动指定";
            }
            ?></span>】
        <input type="button" value="选择企业" class="btn btn-primary" onclick="select_user();"
               title="该数据默认为境内企业名称对应的企业，但您仍然可以手动指定">
        <input type="hidden" value="<?php echo $model['userid']; ?>" name="mysql_userid" id="select_userid">
        <input type="hidden" value="-1" name="change_userid" id="change_userid">
    </h1>


    <table width="99%" bgcolor="#FFFFFF" border="0" cellpadding="3" cellspacing="1"
           class="table table-bordered table-hover definewidth">
        <!-- 1.报表时间-->
        <!-- 4.地区-->
        <tr>
            <td width="100" class="tableleft">
                报表时间：
            </td>
            <td>
                <input type="text" style="width:150px;" valType="date" placeholder="报表时间" name="mysql_baobiao_time"
                       id="baobiao_time"
                       onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#baobiao_time').focus();}})"
                       value="<?php echo $model['baobiao_time']; ?>"/>
            </td>
            <!--YYYY-MM-DD hh:mm:ss-->
            <td width="100" class="tableleft">
                地区：
            </td>
            <td><input type="text" name="mysql_addr" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['addr']; ?>">
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
                       value="<?php echo $model['baobu_status']; ?>">
            </td>
            <td width="100" class="tableleft">
                报表状态：
            </td>
            <td><input type="text" name="mysql_baobiao_status" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['baobiao_status']; ?>">
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
                       value="<?php echo $model['company']; ?>">
            </td>
            <td width="100" class="tableleft">
                境外项目编码：
            </td>
            <td><input type="text" name="mysql_company2_code" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['company2_code']; ?>">
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
                       value="<?php echo $model['company2']; ?>">
            </td>
            <td width="100" class="tableleft">
                国别：
            </td>
            <td>
                <input type="text" name="mysql_guobie" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['guobie']; ?>">
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
                       value="<?php echo $model['shenqing']; ?>">
            </td>
            <td width="100" class="tableleft">
                <label for="mysql_yewu_type"> 业务类型：</label>
            </td>
            <td><input type="text" name="mysql_yewu_type" id="mysql_yewu_type" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['yewu_type']; ?>">
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
                       value="<?php echo $model['touzi_type']; ?>">
            </td>
            <td width="100" class="tableleft">
                投资方式：
            </td>
            <td colspan="3">
                <input type="text" name="mysql_touzi_fangshi" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['touzi_fangshi']; ?>">
            </td>
            <td></td>
        </tr>
        <!-- 13.中方实际投资总额-->
        <tr>
            <td width="100" class="tableleft">
                中方实际投资总额：
            </td>
            <td>
                <input id="input_zonge" type="text" name="mysql_zonge" style="width:400px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['zonge']; ?>">
            </td>
            <td colspan="3">
                <input id="btn_count" class="btn btn-warning" type="button" value="计算总额" style="margin-left: -102px;">
            </td>
        </tr>
        <!-- 14.其中：货币投资-->
        <!--15.其中：自有资金-->

        <tr>
            <td width="100" class="tableleft">
                其中：货币投资：
            </td>
            <td>
                <input type="text" name="mysql_huobi" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['huobi']; ?>">
            </td>
            <td width="100" class="tableleft">
                其中：自有资金：
            </td>
            <td>
                <input type="text" name="mysql_ziyou" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['ziyou']; ?>">
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
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['yinhang']; ?>">
            </td>
            <td width="100" class="tableleft">
                其中：其他：
            </td>
            <td>
                <input type="text" name="mysql_other" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['other']; ?>">
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
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['shiwu']; ?>">
            </td>
            <td width="100" class="tableleft">
                其中：无形资产投资：
            </td>
            <td>
                <input type="text" name="mysql_wuxing" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['wuxing']; ?>">
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
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['xinzeng']; ?>">
            </td>
            <td width="100" class="tableleft">
                其中：新增债务工具：
            </td>
            <td>
                <input type="text" name="mysql_xinzeng_zhai" style="width:200px;"
                       minLength="1" valtype="xiaoshu2"
                       value="<?php echo $model['xinzeng_zhai']; ?>">
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
                       value="<?php echo $model['linkman']; ?>">
            </td>
            <td width="100" class="tableleft">
                项目负责人：
            </td>
            <td>
                <input type="text" name="mysql_fuzeren" style="width:400px;"
                       minLength="1" valtype=""
                       value="<?php echo $model['fuzeren']; ?>">
            </td>
        </tr>
        <!--24.联系人手机-->
        <tr>
            <td width="100" class="tableleft">
                联系人手机：
            </td>
            <td>
                <input type="text" name="mysql_tel" style="width:400px;"
                       minLength="1" valtype="mobile"
                       value="<?php echo $model['tel']; ?>">
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
                       value="<?php echo $model['tianbaoren']; ?>">
            </td>
            <td width="100" class="tableleft">
                填报时间：
            </td>
            <td>
                <input type="text" style="width:150px;" valType="date" placeholder="填报时间" name="mysql_tianbao_time"
                       id="tianbao_time"
                       onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#tianbao_time').focus();}})"
                       value="<?php echo $model['tianbao_time']; ?>"/>
            </td>
        </tr>
        <!--27.备注-->
        <tr>
            <td width="100" class="tableleft">
                备注：
            </td>
            <td colspan="3">
                <textarea name="mysql_beizhu" style="width:400px;height: 60px"
                          minLength="1" valtype=""><?php echo $model['beizhu']; ?></textarea>
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
<!--layer-->
<script type="text/javascript" src="/admin_application/views/static/Js/layer/layer.js"></script>
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

    var openbox = null;
    /**
     * 选择企业用户
     */
    function select_user() {
        var url = '<?php echo site_url("zcq_datamanage/select");?>';
        openbox = layer.open({
            type: 2,
            title: '选择',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '75%'],
            content: url,
            success: function (layero, index) {
                //自适应高度
                //layer.iframeAuto(index);
            }
        });
    }

    /**
     * 关闭选择窗口
     */
    function closeuser() {
        layer.close(openbox);
    }

    /**
     * 设置选择的用户的data
     */
    function setSelectData(id, name) {
        $('#select_userid').val(id);
        $('#change_userid').val(1);
        $('#select_name').text(name).css("color", "red");
    }

    /**
     * 表单提交进行验证
     * @returns {*|jQuery}
     */
    function postform() {
        var result = $("#myform").Valid();
        if (result) {
            openload("提交中");
            return true;
        }
        return false;
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

        //总额的input
        var $input_zonge = $('#input_zonge');

        //计算总额
        $('#btn_count').click(function () {
            var total = 0;
            $input_xiaoshu.each(function () {
                var $this = $(this);
                //用is方法判断是否同一个jQuery对象
                if (!$this.is($input_zonge)) {
                    total = accAdd(total, parseFloat($this.val()));
                    //total += parseFloat($this.val());
                }
            });
            console.log("计算总额：" + total);
            $input_zonge.val(total);
        });
    });
</script>

</body>

</html>