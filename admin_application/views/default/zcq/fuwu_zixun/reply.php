<?php
/**
 * 回复咨询列表
 * User: 嘉辉
 * Date: 2016-08-06
 * Time: 9:46
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>

<!DOCTYPE html>
<html>
<head>
    <head>
        <title>回复咨询</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css"
              href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css"
              href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css"/>
        <link rel="stylesheet" type="text/css"
              href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css"/>
        <link rel="stylesheet" type="text/css"
              href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css"/>
        <script type="text/javascript"
                src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
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
<body class="definewidth">

<!--表单-->
<form enctype="multipart/form-data" action="<?php echo site_url("zcq_fuwu_zixun/doreply"); ?>"
      onsubmit="return postform()"
      method="post" name="myform" id="myform">
    <input type="hidden" name="id" value="<?php echo $model['id']; ?>">

    <table width="99%" bgcolor="#FFFFFF" border="0" cellpadding="3" cellspacing="1"
           class="table table-bordered table-hover definewidth">
        <!--标题-->
        <tr>
            <td width="120" class="tableleft">
                标题：
            </td>
            <td>
                <?php echo $model['title'] ?>
            </td>
        </tr>
        <!--咨询企业-->
        <tr>
            <td width="100" class="tableleft">
                咨询单位全称：
            </td>
            <td>
                <?php echo $user_model["company"]?>
            </td>
        </tr>
        <!--联系人/手机-->
        <tr>
            <td width="100" class="tableleft">
                单位联系人/手机：
            </td>
            <td>
                <?php echo "{$user_model["realname"]}/{$user_model["tel"]}"?>
            </td>
        </tr>
        <!--咨询时间-->
        <tr>
            <td width="100" class="tableleft">
                咨询时间：
            </td>
            <td>
                <?php
                $time = date("Y-m-d H:i:s", $model['create_time']);
                echo $time;
                ?>
            </td>
        </tr>
        <!--内容-->
        <tr>
            <td width="100" class="tableleft">
                内　　容：
            </td>
            <td>
                <?php echo $model['content']; ?>
            </td>
        </tr>
        <!--回复-->
        <tr>
            <td width="100" class="tableleft">
                回　　复：
            </td>
            <td>
                <textarea style="width:300px; height:100px;" id="text" name="mysql_receive_content"
                          placeholder="描述"><?php echo $model['receive_content']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="tableleft"></td>
            <td>
                <input type="submit" class="btn btn-primary" id="btnSave" value="保存"> &nbsp;&nbsp;
                <a class="btn btn-warning" href="javascript:top.topManager.closePage();">关闭</a>
            </td>
        </tr>
    </table>
</form>


<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
<script type="text/javascript" src="/admin_application/views/static/Js/bootstrap.min.js"></script>

<!--kindeditor-->
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>


<script type="text/javascript">
    BUI.use('common/page');
</script>
<script src="/admin_application/views/static/Js/selall.js"></script>
<script type="text/javascript">
    KindEditor.ready(function (K) {
        beizhu = K.create('#text', {
            width: '100%',
            height: '550px',
            allowFileManager: false,//true时显示文件上传按钮
            allowUpload: false,
            afterCreate: function () {
                this.sync();
            },
            afterBlur: function () {
                this.sync();
            },
            extraFileUploadParams: {
                'cookie': ''
            },
            uploadJson: "<?php echo site_url("zcq_fuwu_zixun/upload");?>"
        });
    });

    $(document).ready(function () {

        var $textarea = $('#text');

        //保存按钮
        $("#btnSave").click(function () {
            var $myform = $("#myform");
//            if ($myform.Valid() == false || !$myform.Valid()) {
//                return false;
//            }
            if ($textarea.val() == '') {
                layer.msg('回复内容不能为空!', {time: 1000});
                return false;
            }
            $myform.submit();
        });

    });
</script>

</body>
</html>