<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
    <script type="text/javascript" src="/home/views/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <style>
        .pc_login label {
            width: 180px;
        }
        .way_table{
            border:0px !important;
        }
        .pc_member_background_list tbody{}

    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>





<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <div class="pc_member_background_list">
            <span class="pc_list_present">当前位置：<a href="<?php echo site_url("adminx/admin_index/index");?>">会员后台首页</a>-><a href="<?php echo site_url("adminx/zcq_mail/index");?>">站内信</a></span>
            <h3 class="pc_list_h3">写信</h3>

        <form method="POST" name="myform" id="myform" onSubmit="return chkform()" >
            <input type="hidden" name="receive_userid" id="receive_userid"  value=""/>
            <!--服务机构-->
            <input type="hidden" name="receive_userid2" id="receive_userid2"  value=""/>
            <input type="hidden" name="receive_sysuserid" id="receive_sysuserid"  value=""/>
            <table class="way_table" border="0" style="width:809px;">
                <tr>
                    <td class="tableleft">
                        标题：
                    </td>
                    <td>
                        <input type="text" style="width:60%;" maxlength="200" name="title" value="" required/>
                    </td>

                </tr>
                <tr>
                    <td class="tableleft">
                        收信人：
                    </td>
                    <td>
                        <table align="left">
                            <tr>
                                <td style="border:0px; padding:0px; margin: 0px;">
                                    <?php
                                    if(count($qiye)>0) {
                                        ?>
                                        企业会员：
                                        <?php
                                        foreach ($qiye as $v) {
                                            echo '<input type="checkbox" name="jieshou[]" value="' . $v["uid"] . '" />' . $v["username"];
                                            echo "&nbsp;&nbsp;";
                                        }
                                        ?>

                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="border:0px; padding:0px; margin: 0px;">
                                    <input type="button" style="width:auto;" class="mybtn selbtnn" onClick="openqy('45063')" id="qiye_btn" value="选企业会员"/>

                                </td>
                            </tr>
                            <tr>
                                <td style="border:0px; padding:0px; margin: 0px;">
                                    <?php
                                    if(count($jigou)>0) {
                                        ?>
                                        机构会员：
                                        <?php
                                        foreach ($jigou as $v) {
                                            echo '<input type="checkbox" name="jieshou[]" value="' . $v["uid"] . '" />' . $v["username"];
                                            echo "&nbsp;&nbsp;";
                                        }
                                        ?>

                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="border:0px; padding:0px; margin: 0px;">
                                    <input type="button" class="mybtn selbtnn" style="width:auto;" id="jigou_btn" onClick="openqy('45064')"  value="选服务机构会员"/>

                                </td>
                            </tr>
                            <tr>
                                <td style="border:0px; padding:0px; margin: 0px;">
                                    <?php
                                    if(count($sysuser_list)>0) {
                                        ?>
                                        管理员：
                                        <?php

                                        foreach ($sysuser_list as $v) {
                                            echo '<input type="checkbox" name="jieshou2[]" value="' . $v["receive_sysuserid"] . '" />' . $v["username"];
                                            echo "&nbsp;&nbsp;";
                                        }
                                        ?>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="border:0px; padding:0px; margin: 0px;">
                                    <input type="button" style="" class="mybtn selbtnn" id="sysuser_btn" onClick="openadmin()"  value="选管理员"/>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
                <!--tr>
                    <td class="tableleft">
                       已选：
                    </td>
                    <td>
                       <div style="" id="user_sel_list">

                       </div>
                    </td>


                </tr-->
                <tr>
                    <td class="tableleft">
                        内容：
                    </td>
                    <td>
                        <textarea name="content" id="content"></textarea>

                        <script type="text/javascript" src="/home/views/static/js/kindeditor/kindeditor-all-min.js"></script>
                        <script type="text/javascript" src="/home/views/static/js/kindeditor/lang/zh_CN.js"></script>
                        <script>
                            KindEditor.ready(function(K) {
                                window.editor = K.create('#content', {
                                    width: '90%',
                                    height: '400px',
                                    allowFileManager: false,
                                    allowUpload: false,
                                    afterCreate: function() {
                                        this.sync();
                                    },
                                    afterBlur: function() {
                                        this.sync();
                                    },
                                    extraFileUploadParams: {
                                        'cookie': '<?php echo isset($_COOKIE['admin_auth'])?$_COOKIE['admin_auth']:"";?>'
                                    },
                                    uploadJson:"<?php echo site_url("website_category/upload")."?action=upload&session_id=".$sess["session_id"];?>"

                                });
                            });
                        </script>

                    </td>

                </tr>
                <tr>
                    <td colspan="2" >
                        <div style="text-align: center;">
                            <input class="btn button-warning" style="width:100px;"  type="submit" name="btn_post" id="btn_post" value="发送"/>

                        </div>
                    </td>

                </tr>
            </table>

        </form>

        </div>

    </div>
</div>




<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

<script>
    var openbox = "";
function openqy(usertype){
    idlist = $("#receive_userid").val();
    idlist2 = $("#receive_userid2").val();
    //iframe层
    openbox = layer.open({
        type: 2,
        title: '选择接收人',
        shadeClose: true,
        shade: 0.8,
        area: ['90%', '90%'],
        content: '<?php echo site_url("adminx/zcq_mail/selmember");?>?idlist='+(usertype=='45063'?idlist:idlist2)+"&usertype="+usertype,
        success: function (layero, index) {
            //自适应高度
            //layer.iframeAuto(index);
        }
    });
}

function openadmin(){
    idlist = $("#receive_sysuserid").val();

    openbox = layer.open({
        type: 2,
        title: '选择管理员接收人',
        shadeClose: true,
        shade: 0.8,
        area: ['90%', '90%'],
        content: '<?php echo site_url("adminx/zcq_mail/seladmin");?>?idlist='+idlist,
        success: function (layero, index) {
            //自适应高度
            //layer.iframeAuto(index);
        }
    });
}



function closefj(){
    layer.close(openbox);
}

function updatesel(target_id,idlist){
    //$("#"+target_id).val("选企业会员");
    arr = idlist.split(",");
    if(arr.length>0) {
        if (target_id == "qiye_btn") {
            $("#" + target_id).val("选企业会员" + "(已选" + arr.length + "个)");
            $("#receive_userid").val(idlist);
        }
        if (target_id == "jigou_btn") {
            $("#" + target_id).val("选服务机构会员" + "(已选" + arr.length + "个)");
            $("#receive_userid2").val(idlist);
        }
        if (target_id == "sysuser_btn") {
            $("#" + target_id).val("选管理员" + "(已选" + arr.length + "个)");
            $("#receive_sysuserid").val(idlist);
        }

        //$("#idlist").val(idlist);
    }
}

    function chkform(){
        if($("#myform").Valid()){





            if($("#receive_userid2").val()=="" &&
                $("#receive_userid").val()=="" &&
                $("#receive_sysuserid").val()=="" &&
                $("input[name='jieshou[]']:checked").length==0 &&
                $("input[name='jieshou2[]']:checked").length==0){
                layer.msg("请选择收信人",{time:1000});
                return false;
            }
            if(window.editor.html()==""){
                layer.msg("请输入内容",{time:1000});
                return false;
            }

            $("#btn_post").attr('disabled','disabled');
        }
        else{
            return false;
        }

    }

</script>
</body>
</html>