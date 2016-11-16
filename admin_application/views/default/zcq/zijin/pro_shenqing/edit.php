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
    <script>
        function chkform(){

            if($("#myform").Valid()){

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
<input type="hidden" name="id" value="<?php echo $model["id"];?>" />
        <table class="table table-bordered table-hover  m10">
            <tr>
                <td class="tableleft">
                    *申报企业名称：
                </td>
                <td>
                    <input type="text" style="width:200px;" maxlength="200" name="mysql_title" value="<?php echo $model["title"];?>" required/>
                </td>
                <td class="tableleft">
                    *法人姓名：
                </td>
                <td>
                    <input type="text" style="width:200px;" name="mysql_faren" value="<?php echo $model["faren"];?>" required/>

                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    *法人电话：
                </td>
                <td>
                    <input type="text" style="width:200px;" name="mysql_faren_tel" value="<?php echo $model["faren_tel"];?>" required />
                </td>
                <td class="tableleft">
                    *通讯地址：
                </td>
                <td>
                    <input type="text" style="width:200px;" maxlength="250" name="mysql_addr" required value="<?php echo $model["addr"];?>"/>

                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    *企业联系人：
                </td>
                <td>
                    <input type="text" style="width:200px;" maxlength="50" name="mysql_qiye_linkman" value="<?php echo $model["qiye_linkman"];?>"/>
                </td>
                <td class="tableleft">
                    *联系电话：
                </td>
                <td>
                    <input type="text" required style="width:200px;" maxlength="50" name="mysql_qiye_tel" value="<?php echo $model["qiye_tel"];?>"/>
                </td>

            </tr>
            <tr>
                <td class="tableleft">
                    *联系手机：
                </td>
                <td>
                    <input type="text" required style="width:200px;" maxlength="50" name="mysql_qiye_mobile" value="<?php echo $model["qiye_mobile"];?>"/>
                </td>
                <td class="tableleft">
                    电子邮件：
                </td>
                <td>
                    <input type="text" valType="email" style="width:200px;" maxlength="100" name="mysql_qiye_email" value="<?php echo $model["qiye_email"];?>"/>

                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    *开户银行名称：
                </td>
                <td>
                    <input type="text"  required style="width:200px;" maxlength="100" name="mysql_kaihu" value="<?php echo $model["kaihu"];?>"/>
                </td>
                <td class="tableleft">
                    *开户行地址：
                </td>
                <td>
                    <input type="text" required style="width:200px;" maxlength="200" name="mysql_kaihu_addr" value="<?php echo $model["kaihu_addr"];?>"/>
                </td>
            </tr>
            <tr>
                <td class="tableleft">
                   *银行账户账号：
                </td>
                <td>
                    <input type="text" required style="width:200px;" maxlength="100" name="mysql_kaihu_zhanghu" value="<?php echo $model["kaihu_zhanghu"];?>"/>
                </td>
                <td class="tableleft">
                    *银行账户户名：
                </td>
                <td>
                    <input type="text" required style="width:200px;" maxlength="100" name="mysql_kaihu_huming" value="<?php echo $model["kaihu_huming"];?>"/>
                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    申报补贴类型：
                </td>
                <td>
<?php echo $pro_type_model["title"];?>
<br/>
<?php echo "申请时段：".date("Y-m-d H:i",$pro_type_model["starttime"])."至".date("Y-m-d H:i",$pro_type_model["endtime"]);?>
                </td>
                <td class="tableleft">
                    状态：
                </td>
                <td>
<?php
echo isset($checkstatus[$model["check_status"]])?$checkstatus[$model["check_status"]]:"-";
?>
                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    附件：
                </td>
                <td colspan="3">

                    <?php
                    if(count($fujian)>0) {
                        echo '<table>';
                        foreach ($fujian as $k => $v) {
                            echo '<tr>';
                            echo '<td style="height: 40px; margin: 0px; padding:0px; border:0px;">';
                            echo ($k+1);
                            echo "、";
                            echo $v["title"];
                            echo '</td>';
                            echo '<td style="height: 40px; margin: 0px; padding:0px; border:0px;">';
                            echo "\n";
                            echo '<input type="hidden" id="fj_upload'.$id."_".$v["fujian_id"].'" name="fj_upload'.$id."_".$v["fujian_id"].'" value="'.$v["filepath2"].'"/>';
                            echo "\n";
                            echo '<span style="'.($v["filepath2"]!=""?"display:none;":'').'" id="upload'.$id.'_'.$v["fujian_id"].'_box">';
                            echo "\n";
                            echo '<input  type="file"  class="upload_box" id="upload'.$id.'_'.$v["fujian_id"].'" name="upload'.$id.'_'.$v["fujian_id"].'" />';
                            echo "</span>";
                            echo "\n";
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" style="'.($v["filepath2"]==""?"display:none;":"").'" class="button" id="upload'.$id.'_'.$v["fujian_id"].'_reload" onclick="reload_file(\'upload'.$id.'_'.$v["fujian_id"].'\')">重新上传</button>';
                            echo "</td>";

                            echo '<td style=" margin: 0px; padding:0px; border:0px;">';
                            if($v["filepath2"]!=""){
                                echo '&nbsp;&nbsp;<a href="/'.$v["filepath2"].'" target="_blank">查看附件</a>';
                            }
                            else{
                                echo '&nbsp;&nbsp;<a href="#">没有上传附件</a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                            echo "\n";
                        }
                        echo '</table>';
                    }
                    else{
                        echo "还未上传附件";
                    }

                    ?>

                </td>
            </tr>

            <tr>
                <td colspan="4" >
                    <div style="text-align: center;">
                        <input class="btn button-warning"  type="submit" name="btn_post" value="提交"/>
                        <input class="button"  type="button" value="返回" onclick="parent.flushpage('<?php echo $ls;?>');top.topManager.closePage();"/>
                    </div>
                </td>

            </tr>
        </table>


    </form>
</div>

<script>
    $.each($('.upload_box'),function(){
        uploadid = $(this).attr("id");
        $(this).uploadify({//"#"+$(this).attr("id")
            'debug': false,
            'auto': true,//关闭自动上传
            'removeTimeout':0,//文件队列上传完成1秒后删除
            'swf': '/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.swf',
            'uploader': '<?php echo site_url("swj_upload/upload2");?>?path=fujian&session_id=<?php echo $sess["session_id"];?>',
            'method': 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText': '选择附件',//设置按钮文本
            'multi': false,//允许同时上传多张图片
            'uploadLimit': 1,//一次最多只允许上传10张图片
            'fileTypeDesc': '*.xls,*.xlsx,*.doc,*.docx,*.ppt,*.pptx,*.txt',//只允许上传图像
            'fileTypeExts': '*.doc;*.docx;*.ppt;*.pptx;*.txt;*.xls;*.xlsx',//限制允许上传的图片后缀
            'fileSizeLimit': '5048KB',//限制上传的图片不得超过5M
            'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
            },
            'onUploadSuccess': function (file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
                id = this.settings.button_placeholder_id;
                $("#"+id+"_box").css("display","none");
                $("#"+id+"_reload").css("display", "");
                //alert(data);
                $("#fj_"+id).val(data);

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
            ,
        });
    });

function reload_file(id){
    $('#'+id+"_reload").css("display","none");
    $('#'+id+"_box").css("display","");
}
</script>

</body>
</html>