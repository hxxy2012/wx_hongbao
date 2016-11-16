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
    <!--    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>-->
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
            background-color: #fff;
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
                    href="<?php echo $cur_url; ?>">资料核对</a></span>
            <h3 class="pc_list_h3">查看</h3>

            <table class="mytable2" width="100%" bgcolor="#cccccc" cellspacing="1" cellpadding="0">
                <caption style="font-size: x-large;">
                    走出去数据管理编号<?php echo $model['id']; ?>
                </caption>
                <tr>
                    <td style="width: 130px" class="tableleft">
                        企业简称：
                    </td>
                    <td><?php echo $user_model['username']?></td>
                    <td>企业全称:</td>
                    <td><?php echo $user_model['company']?></td>
                </tr>
                <!-- 1.报表时间-->
                <!-- 4.地区-->
                <tr>
                    <td class="tableleft">
                        报表时间：
                    </td>
                    <td>
                        <input type="text" style="width:150px;" valType="date" placeholder="报表时间"
                               name="mysql_baobiao_time"
                               id="baobiao_time"
                               value="<?php echo $model['baobiao_time']; ?>"/>
                    </td>
                    <!--YYYY-MM-DD hh:mm:ss-->
                    <td class="tableleft">
                        地区：
                    </td>
                    <td>
                        <input type="text" name="mysql_addr" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['addr']; ?>">
                    </td>
                </tr>
                <!-- 2.报部状态-->
                <!-- 3.报表状态-->
                <tr>
                    <td class="tableleft">
                        报部状态：
                    </td>
                    <td><input type="text" name="mysql_baobu_status" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['baobu_status']; ?>">
                    </td>
                    <td class="tableleft">
                        报表状态：
                    </td>
                    <td><input type="text" name="mysql_baobiao_status" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['baobiao_status']; ?>">
                    </td>
                </tr>
                <!-- 5.境内企业名称-->
                <!-- 6.境外项目编码-->
                <tr>
                    <td class="tableleft">
                        境内企业名称：
                    </td>
                    <td><input type="text" name="mysql_company" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['company']; ?>">
                    </td>
                    <td class="tableleft">
                        境外项目编码：
                    </td>
                    <td><input type="text" name="mysql_company2_code" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['company2_code']; ?>">
                    </td>
                </tr>
                <!-- 7.境外项目名称-->
                <!-- 10.国别-->
                <tr>
                    <td class="tableleft">
                        境外项目名称：
                    </td>
                    <td><input type="text" name="mysql_company2" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['company2']; ?>">
                    </td>
                    <td class="tableleft">
                        国别：
                    </td>
                    <td>
                        <input type="text" name="mysql_guobie" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['guobie']; ?>">
                    </td>
                </tr>
                <!-- 8.申请事项-->
                <!-- 9.业务类型-->
                <tr>
                    <td class="tableleft">
                        申请事项：
                    </td>
                    <td><input type="text" name="mysql_shenqing" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['shenqing']; ?>">
                    </td>
                    <td class="tableleft">
                        <label for="mysql_yewu_type"> 业务类型：</label>
                    </td>
                    <td><input type="text" name="mysql_yewu_type" id="mysql_yewu_type" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['yewu_type']; ?>">
                    </td>
                </tr>
                <!-- 11.投资类型-->
                <!-- 12.投资方式-->
                <tr>
                    <td class="tableleft">
                        投资类型：</label>
                    </td>
                    <td>
                        <input type="text" name="mysql_touzi_type" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['touzi_type']; ?>">
                    </td>
                    <td class="tableleft">
                        投资方式：
                    </td>
                    <td colspan="3">
                        <input type="text" name="mysql_touzi_fangshi" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['touzi_fangshi']; ?>">
                    </td>
                </tr>
                <!-- 13.中方实际投资总额-->
                <tr>
                    <td class="tableleft">
                        中方实际投资总额：
                    </td>
                    <td>
                        <input id="input_zonge" type="text" name="mysql_zonge" style="width:200px;"
                               minLength="1" valtype="xiaoshu2"
                               value="<?php echo $model['zonge']; ?>">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- 14.其中：货币投资-->
                <!--15.其中：自有资金-->

                <tr>
                    <td class="tableleft">
                        其中：货币投资：
                    </td>
                    <td>
                        <input type="text" name="mysql_huobi" style="width:200px;"
                               minLength="1" valtype="xiaoshu2"
                               value="<?php echo $model['huobi']; ?>">
                    </td>
                    <td class="tableleft">
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
                    <td class="tableleft">
                        其中：银行贷款：
                    </td>
                    <td>
                        <input type="text" name="mysql_yinhang" style="width:200px;"
                               minLength="1" valtype="xiaoshu2"
                               value="<?php echo $model['yinhang']; ?>">
                    </td>
                    <td class="tableleft">
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
                    <td class="tableleft">
                        其中：实物投资：
                    </td>
                    <td>
                        <input type="text" name="mysql_shiwu" style="width:200px;"
                               minLength="1" valtype="xiaoshu2"
                               value="<?php echo $model['shiwu']; ?>">
                    </td>
                    <td class="tableleft">
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
                    <td class="tableleft">
                        其中：新增股权：
                    </td>
                    <td>
                        <input type="text" name="mysql_xinzeng" style="width:200px;"
                               minLength="1" valtype="xiaoshu2"
                               value="<?php echo $model['xinzeng']; ?>">
                    </td>
                    <td class="tableleft">
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
                    <td class="tableleft">
                        联系人：
                    </td>
                    <td>
                        <input type="text" name="mysql_linkman" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['linkman']; ?>">
                    </td>
                    <td class="tableleft">
                        项目负责人：
                    </td>
                    <td>
                        <input type="text" name="mysql_fuzeren" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['fuzeren']; ?>">
                    </td>
                </tr>
                <!--24.联系人手机-->
                <tr>
                    <td class="tableleft">
                        联系人手机：
                    </td>
                    <td>
                        <input type="text" name="mysql_tel" style="width:200px;"
                               minLength="1" valtype="mobile"
                               value="<?php echo $model['tel']; ?>">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <!--25.填报人-->
                <!--26.填报时间-->
                <tr>
                    <td class="tableleft">
                        填报人：
                    </td>
                    <td>
                        <input type="text" name="mysql_tianbaoren" style="width:200px;"
                               minLength="1" valtype=""
                               value="<?php echo $model['tianbaoren']; ?>">
                    </td>
                    <td class="tableleft">
                        填报时间：
                    </td>
                    <td>
                        <input type="text" style="width:150px;" valType="date" placeholder="填报时间"
                               name="mysql_tianbao_time"
                               id="tianbao_time"
                               onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#tianbao_time').focus();}})"
                               value="<?php echo $model['tianbao_time']; ?>"/>
                    </td>
                </tr>
                <!--27.备注-->
                <tr>
                    <td class="tableleft">
                        备注：
                    </td>
                    <td>
                        <input type="text" name="mysql_beizhu" style="width:200px;height: 30px"
                               minLength="1" valtype=""
                               value="<?php echo $model['beizhu']; ?>">
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="4">
                        <form action="<?php echo site_url($controller."/doread")?>">

                        <div style="text-align: center;">
                            <input class="mybtn" type="button" value="返回"
                                   onclick="window.location.href='<?php echo site_url($controller . "/index"); ?>'"/>
<!--                            <input class="mybtn" type="button" value="设置已阅" id="btn_read">-->
                        </div>
                        </form>
                    </td>
                </tr>

            </table>


        </div>

    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

<script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
<script type="text/javascript" src="/home/views/static/js/laydate/laydate.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        var $inputs = $('input');
        $inputs.attr("readonly", true);
        //设置已经阅读
//        var $btn_read = $('#btn_read');
//        $btn_read.click(function () {
//
//        });


    });
</script>
</body>
</html>