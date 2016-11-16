<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css"/>

    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />




    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/uploadfile/jquery.uploadify-3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.css"/>

    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/laydate/laydate.js"></script>

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

        .selbtnn{margin-bottom: 10px !important;}


    </style>
    <script>
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
</head>
<body class="definewidth">

<div class="form-inline definewidth m20" >
    <form method="POST" name="myform" id="myform" onsubmit="return chkform()" >
        <input type="hidden" name="receive_userid" id="receive_userid"  value=""/>
        <!--服务机构-->
        <input type="hidden" name="receive_userid2" id="receive_userid2"  value=""/>
        <input type="hidden" name="receive_sysuserid" id="receive_sysuserid"  value=""/>
        <table class="table table-bordered table-hover  m10">
            <tr>
                <td class="tableleft">
                    标题：
                </td>
                <td>
                    <input type="text" style="width:60%;" maxlength="200" name="title" value="" required/>
                </td>

            </tr>
            <tr>
                <td class="tableleft" valign="top">
                   收信人：
                </td>
                <td>
                    <table>
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
                                <input type="button" class="button selbtnn" onclick="openqy('45063')" id="qiye_btn" value="选企业会员"/>

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
                                <input type="button" class="button selbtnn" id="jigou_btn" onclick="openqy('45064')"  value="选服务机构会员"/>

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
                            <input type="button" class="button selbtnn" id="sysuser_btn" onclick="openadmin()"  value="选管理员"/>
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
                <td class="tableleft" valign="top">
                    内容：
                </td>
                <td>
                    <textarea name="content" id="content"></textarea>

    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
                    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
                    <script>
                        KindEditor.ready(function(K) {
                            window.editor = K.create('#content', {
                                width: '100%',
                                height: '600px',
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
                        <input class="btn button-warning"  type="submit"  name="btn_post" id="btn_post" value="发送"/>

                    </div>
                </td>

            </tr>
        </table>

    </form>
</div>


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
        content: '<?php echo site_url("zcq_mail/selmember");?>?idlist='+(usertype=='45063'?idlist:idlist2)+"&usertype="+usertype
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
        content: '<?php echo site_url("zcq_mail/seladmin");?>?idlist='+idlist
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

</script>
</body>
</html>