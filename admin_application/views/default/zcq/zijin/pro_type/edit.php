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
                    申报类型名称：
                </td>
                <td>
                    <input type="text" style="width:200px;" maxlength="200" name="title" value="<?php echo $model["title"];?>" required/>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    时段：
                </td>
                <td>
                   <input type="text" required name="starttime" id="starttime" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm'})"  style="width:120px;" value="<?php echo date("Y-m-d H:i",$model["starttime"]);?>"/>
                    至
                   <input type="text" required name="endtime" id="endtime" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm'})"  style="width: 120px;" value="<?php echo date("Y-m-d H:i",$model["endtime"]);?>"/>

                </td>
                <td>
                    <input type="button" class="button" onclick="openfj()" id="selfujian" value="选附件<?php echo $fujian_count>0?"(已选".$fujian_count."个)":""?>"/>
                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    可见：
                </td>
                <td>
                    <input type="radio" name="isshow" value="1" <?php echo $model["isshow"]=='1'?"checked":"";?>/>是
                    <input type="radio" name="isshow" value="0" <?php echo $model["isshow"]=='0'?"checked":"";?> />否
                </td>
                <td>
                   对会员是否可见，如：未定稿，暂时隐藏类型
                </td>

            </tr>
            <tr>
                <td class="tableleft">
                    备注：
                </td>
                <td>
                    <input type="text" style="width:200px;" maxlength="250" name="beizhu" value="<?php echo $model["beizhu"];?>"/>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <div style="text-align: center;">
                        <input class="btn button-warning"  type="submit" name="btn_post" value="提交"/>
                        <input class="button"  type="button" value="返回" onclick="parent.flushpage('<?php echo $ls;?>');top.topManager.closePage();"/>
                    </div>
                </td>

            </tr>
        </table>
        <?php
        //用于记录选中的附件ID，保存时候写入数据库
        ?>
<input type="hidden" name="pro_type_fujian" id="pro_type_fujian" value=""/>
    </form>
</div>


<script>
    var openbox = "";
function openfj(){
    idlist = $("#pro_type_fujian").val();
    //iframe层
    openbox = layer.open({
        type: 2,
        title: '选择附件',
        shadeClose: true,
        shade: 0.8,
        area: ['90%', '90%'],
        content: '<?php echo site_url("zcq_pro_type/getfjbox_edit");?>?typeid=<?php echo $model["id"];?>&idlist='+idlist
    });
}

function upadtefujian(idlist) {
    if (idlist != "") {
        arr = idlist.split(",");
        len = arr.length;
        $("#pro_type_fujian").val(idlist);
        $("#selfujian").val("选附件(已选" + len + "个)");
    }
}

function closefj(){
    layer.close(openbox);
}

</script>
</body>
</html>