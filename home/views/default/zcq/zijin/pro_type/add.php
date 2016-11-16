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
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
    <script type="text/javascript" src="/home/views/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/home/views/static/js/validate/validator_plus.js"></script>
    <script type="text/javascript" src="/home/views/static/js/uploadfile/jquery.uploadify-3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/home/views/static/js/uploadfile/uploadify.css"/>
    <!--邮箱自动完成css-->
    <link href="/home/views/static/js/mailAutoComplete/mailAutoComplete.css" rel="stylesheet"/>
    <!--邮箱自动完成-->
    <script src="/home/views/static/js/mailAutoComplete/jquery.mailAutoComplete-4.0.js"></script>
    <style>
        /*邮箱自动完成css修正*/
        body ul.emailist li {
            margin: auto;
            line-height: inherit;
        }

        .pc_login label {
            width: 180px;
        }

        .way_table {
            border: 0px !important;
        }

        .mytable2 {
            margin-top: 0px !important;
        }

        .mytable2 td {
            background: #fff;
            padding: 3px;
        }
    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>


<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <div class="pc_member_background_list">
            <span class="pc_list_present">当前位置：<a href="<?php echo site_url("admin/index"); ?>">会员后台首页</a>-><a
                    href="<?php echo site_url("adminx/zcq_pro_type/index"); ?>">资金申请</a></span>
            <h3 class="pc_list_h3"><?php if ($status[2] == '99' || $status[2] == "0") {
                    echo "查看";
                }
                if ($status[2] == '10') {
                    echo "申请";
                } ?></h3>
            <div
                style="text-align:center; font-family: '楷体'; font-size:20px; font-weight: bold; margin-bottom: 10px; line-height: 100%;"><?php echo $model["title"]; ?></div>
            <form method="POST" name="myform" id="myform" onSubmit="return chkform()">
                <input type="hidden" name="id" value="<?php echo $model["id"]; ?>"/>
                <table class="mytable2" style="margin:auto auto" width="94%" bgcolor="#cccccc" cellspacing="1"
                       cellpadding="0">
                    <tr>
                        <td class="tableleft">
                            *申报企业名称：
                        </td>
                        <td>
                            <input type="text" style="width:200px;" maxlength="200" name="mysql_title" value=""
                                   valType="my_require" required/>
                        </td>
                        <td class="tableleft">
                            *法人姓名：
                        </td>
                        <td>
                            <input type="text" style="width:200px;" name="mysql_faren" value=""
                                   valType="my_require" required/>

                        </td>
                    </tr>
                    <tr>
                        <td class="tableleft">
                            *法人电话：
                        </td>
                        <td>
                            <input type="text" style="width:200px;" name="mysql_faren_tel" value="" required
                                   valType="telphone"
                            />
                        </td>
                        <td class="tableleft">
                            *通讯地址：
                        </td>
                        <td>
                            <input type="text" style="width:200px;" maxlength="250" name="mysql_addr" required
                                   valType="my_require" value=""/>

                        </td>
                    </tr>
                    <tr>
                        <td class="tableleft">
                            *企业联系人：
                        </td>
                        <td>
                            <input type="text" style="width:200px;" maxlength="50" name="mysql_qiye_linkman" required
                                   valType="my_require" value=""/>
                        </td>
                        <td class="tableleft">
                            *联系电话：
                        </td>
                        <td>
                            <input type="text" style="width:200px;" maxlength="50" name="mysql_qiye_tel"
                                   value=""
                                   valType="telphone" required
                            />
                        </td>

                    </tr>
                    <tr>
                        <td class="tableleft">
                            *联系手机：
                        </td>
                        <td>
                            <input type="text" required style="width:200px;" maxlength="11" name="mysql_qiye_mobile"
                                   valType="mobile" value=""/>
                        </td>
                        <td class="tableleft">
                            *电子邮件：
                        </td>
                        <td>
                            <input type="text"  style="width:200px;" maxlength="100"
                                   valType="email" name="mysql_qiye_email" autocomplete="off" value="" required/>

                        </td>
                    </tr>
                    <tr>
                        <td class="tableleft">
                            *开户银行名称：
                        </td>
                        <td>
                            <input type="text" required style="width:200px;" maxlength="100" name="mysql_kaihu"
                                   valType="my_require" value=""/>
                        </td>
                        <td class="tableleft">
                            *开户行地址：
                        </td>
                        <td>
                            <input type="text" required style="width:200px;" maxlength="200" name="mysql_kaihu_addr"
                                   valType="my_require" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td class="tableleft">
                            *银行账户账号：
                        </td>
                        <td>
                            <input type="text" required style="width:200px;" maxlength="100" name="mysql_kaihu_zhanghu"
                                   valType="my_require" value=""/>
                        </td>
                        <td class="tableleft">
                            *银行账户户名：
                        </td>
                        <td>
                            <input type="text" required style="width:200px;" maxlength="100" name="mysql_kaihu_huming"
                                   valType="my_require" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td class="tableleft">
                            申报时段：
                        </td>
                        <td>
                            <?php echo date("Y-m-d H:i", $model["starttime"]) . "至" . date("Y-m-d H:i", $model["endtime"]); ?>
                        </td>
                        <td class="tableleft">
                            状态：
                        </td>
                        <td>
                            <?php
                            echo $status[0];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>临时保存</td>
                        <td colspan="3">
                            <label>
                                <input type="checkbox" name="is_tmp" value="yes"/>
                                (先保存，不提交)
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="tableleft">
                            *申报所需附件：
                        </td>
                        <td colspan="3">

                            <?php
                            if (count($fujian) > 0) {
                                echo '<div>';
                                echo '<table>';
                                foreach ($fujian as $k => $v) {
                                    echo '<tr>';
                                    echo '<td style="height: 40px; margin: 0px; padding:0px; border:0px;">';
                                    echo($k + 1);
                                    echo "、";
                                    echo $v["title"];
                                    echo '</td>';
                                    echo '<td valign="top" style="height: auto; margin: 0px; padding:0px; border:0px;">';
                                    echo "\n";
                                    echo '<input type="hidden" title="' . $v["title"] . '" class="chk_fujian" id="fj_upload' . $id . "_" . $v["fujian_id"] . '" name="fj_upload' . $id . "_" . $v["fujian_id"] . '" value=""/>';
                                    echo "\n";
                                    if ($chk_count == 0 && strval($status[2]) == "10") {
                                        echo '<span style="" id="upload' . $id . '_' . $v["fujian_id"] . '_box">';
                                        echo "\n";
                                        echo '<input  type="file"  class="upload_box" id="upload' . $id . '_' . $v["fujian_id"] . '" name="upload' . $id . '_' . $v["fujian_id"] . '" />';
                                        echo "</span>";
                                    }
                                    echo "\n";
                                    echo '<input  type="button"  style="width:94px;display:none"  id="upload' . $id . '_' . $v["fujian_id"] . '_reload" onclick="reload_file(\'upload' . $id . '_' . $v["fujian_id"] . '\')" value="重新上传"/>';
                                    echo "</td>";
                                    echo '<td style=" margin: 0px; padding:0px; border:0px;" valign="top">';
                                    echo "\n";
                                    echo '<input  type="button" style="margin-left:3px;width:94px;height:32px;" onclick="window.open(\'/' . $v["filepath"] . '\')" value="下载模板"/>';
                                    echo "</td>";
                                    echo '</tr>';
                                    echo "\n";
                                }
                                echo '</table>';
                                echo '</div>';
                            } else {
                                echo "还未上传附件";
                            }

                            ?>

                        </td>
                    </tr>


                </table>

                <script type="text/javascript">
                    /**
                     * 查看模式
                     */
                    function view_mod() {
                        $(document).ready(function () {
                            var $myform = $('#myform');
                            $myform.find("input[type=text]").attr("required", "false").attr("notvalid", "true");
                        });
                    }
                </script>
                <div style="text-align: center!important;">
                    <?php if ($chk_count > 0) { ?>
                    <input class="mybtn" type="submit" name="btn_post" style="background: #cccccc;border:0;"
                           value="禁止提交(已提交过)" disabled/>
                        <script type="text/javascript">
                            view_mod();
                        </script>
                    <?php } else if ($status[2] == '99' or $status[2] == '0') { ?>
                        <script type="text/javascript">
                            view_mod();
                        </script>
                    <?php } else {
                    ?>
                    <input class="" type="submit" name="btn_post" value="提交"/>
                    <?php } ?>
                    <input class="" type="button" value="返回" onClick="window.location.href='<?php echo $ls; ?>'"/>
                </div>

            </form>

        </div>

    </div>
</div>


<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>


<script>


    $.each($('.upload_box'), function () {
        uploadid = $(this).attr("id");
        $(this).uploadify({//"#"+$(this).attr("id")
            'width': 94,
            'debug': false,
            'auto': true,//关闭自动上传
            'removeTimeout': 0,//文件队列上传完成1秒后删除
            'swf': '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
            'uploader': '<?php echo site_url("swj_upload/upload2");?>?path=fujian&session_id=<?php echo $sess["session_id"];?>',
            'method': 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText': '上传附件',//设置按钮文本
            'multi': false,//允许同时上传多张图片
            'uploadLimit': 1,//一次最多只允许上传10张图片
            'fileTypeDesc': '*.xls,*.xlsx,*.doc,*.docx,*.ppt,*.pptx,*.txt',//只允许上传图像
            'fileTypeExts': '*.doc;*.docx;*.ppt;*.pptx;*.txt;*.xls;*.xlsx',//限制允许上传的图片后缀
            'fileSizeLimit': '5048KB',//限制上传的图片不得超过5M
            'onUploadError': function (file, errorCode, errorMsg, errorString) {
                alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
            },
            'onUploadSuccess': function (file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
                id = this.settings.button_placeholder_id;
                $("#" + id + "_box").css("display", "none");
                $("#" + id + "_reload").css("display", "");
                //alert(data);
                $("#fj_" + id).val(data);

            },
            'onQueueComplete': function (queueData) {//上传队列全部完成后执行的回调函数

            },
            'onError': function (event, ID, fileObj, errorObj) {
                if (errorObj.type === "File Size") {
                    alert('超过文件上传大小限制（5M）！');
                    return;
                }
                alert(errorObj.type + ', Error: ' + errorObj.info);
            }

        });
    });

    function reload_file(id) {
        $('#' + id + "_reload").css("display", "none");
        $('#' + id + "_box").css("display", "");
        df("fj_" + id);
    }


    function df(id) {
        var tarid = id;
        var url = "<?php echo site_url("adminx/zcq_pro_type/delfujian");?>";
        $.ajax({
            url: url,
            type: "POST",
            data: {fjpath: $("#" + id).val()},
            success: function (data) {
                //alert(data);
                $('#' + tarid).val("");
            }
        });

    }
    function chkform() {
        if ($("#myform").Valid()) {
            var i = 0;
            var title = "";
            $(".chk_fujian").each(function () {
                if ($(this).val() == "") {
                    title = $(this).attr("title");
                    i++;
                    return false;
                }
            });
            if (i > 0) {
                layer.msg("附件【" + title + "】还未上传", {time: 2000});
                return false;
            }
        }
        else {
            return false;
        }

    }

    $(document).ready(function () {
        var $input_email = $("input[name='mysql_qiye_email']");
        $input_email.mailAutoComplete({zIndex: 10000});
    });
</script>
</body>
</html>