<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
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
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet"
          type="text/css"/>

    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>

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

        /*加载层*/
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
    <script>
        BUI.use('common/page');
    </script>
</head>
<body class="definewidth">

<div class="form-inline definewidth m20">
    <form method="get" id="search_form">
        关键字：
        <input type="text" name="sel_title" id="search_title"
               class="abc input-default"
               placeholder="公司全称/联系人/电话/手机"
               value="<?php echo $search_val['title']; ?>"
               style="width:200px;"
        />
        <select name="sel_cs">
            <option value="">状态</option>
            <?php foreach ($checkstatus as $k => $v) { ?>
                <option
                    value="<?php echo $k; ?>" <?php echo strval($search_val["check_status"]) == strval($k) ? " selected " : ""; ?>><?php echo $v; ?></option>
            <?php } ?>
        </select>
        <input type="hidden" name="isport" id="isport" value=""/>
        <button type="submit" onclick="$('#isport').val('no');" class="btn btn-primary">查询</button>
        <button class="button" onclick="$('#isport').val('yes');$('#search_form').submit();">导出EXCEL</button>


    </form>
</div>

<?php
if (count($list) > 0) {
    ?>
    <table class="table table-bordered table-hover  m10">
        <thead>
        <tr>
            <th width='50'>编号</th>
            <th>申报类型</th>
            <th>联系人/电话/手机</th>
            <th width="80">状态</th>
            <th>公司全称</th>
            <th>提交时间</th>
            <th width='60'>操作</th>
        </tr>
        </thead>
        <tbody id="result_">


        <?php

        foreach ($list as $v) {

            echo "<tr onclick='seltr($(this))'>";
            echo "<td>" . $v["id"] . "</td>";
            echo "<td>" . $v["typename"] . "</td>";
            echo "<td>" . ($v["qiye_linkman"] == "" && $v["qiye_linkman"] == "" ? "-" : $v["qiye_linkman"] . "/" . $v["qiye_tel"] . "/" . $v["qiye_mobile"]) . "</td>";
            echo "<td>" . ($v["check_status_title"]) . "</td>";
            echo "<td>";
            echo $v["title"];
            echo "</td>";
            echo "<td>" . date("Y-m-d H:i", $v["createtime"]) . "</td>";
            echo "<td>";
            echo "	
<a class='icon-edit page-action'
data-id='zcq_pro_shenqing_" . $v["id"] . "'   			 
data-href='" . (site_url("zcq_pro_shenqing/edit") . "?ls=" . urlencode(get_url()) . "&id=" . $v["id"]) . "'    			
   			href=\"#\" id='zcq_pro_shenqing_" . $v["id"] . "'  title=\"申请编号" . $v["id"] . "\"></a>";
            echo "</td>";
            echo "</tr>";
            echo "\n";
        }
        ?>


        </tbody>

    </table>


    <table border="0" style="margin:0px; padding:0px;" width="100%">
        <tr>
            <td style="border:0px;" align="left">
                <input type="hidden" name="selid" id="selid" value=""/>

                <button class="button" onclick="selall()">全选</button>
                <button class="button" onclick="selall2()">反选</button>
                <button class="button button-danger" onclick="godel()">删除</button>
                <div style="height:5px;"></div>
                <button class="button button-success" onclick="return setcheck(1)">批量审核通过</button>
                <button class="button button-warning" id="btn_check_no" onclick="//return setcheck(0)">批量审核不通过</button>
                <!--                审核意见：-->
                <!--                <textarea style="" placeholder="不通过时必填" name="check_content" id="check_content"></textarea>-->
                <!--                <input type="text" placeholder="不通过时必填" name="check_content" id="check_content" value=""/>-->
            </td>
            <td style="border:0px;" align="right">


                <div id="page_string" style="float:right ; text-align:right ; margin:-4px">
                    <?php echo $pager; ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
} else {
    echo "<div style='text-align:center;'>";
    echo "暂无信息";
    echo "</div>";
}
?>

</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
    /**
     * 打开加载窗口
     */
    var openbox = null;
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

    //批量不通过
    BUI.use('bui/overlay', function (Overlay) {
        var dialog = new Overlay.Dialog({
            title: '输入审核不通过原因',
            width: 500,
            height: 220,
            //配置文本
            bodyContent: '<textarea id="check_content" name="content" style="width:100%;height:150px;"></textarea>',
            success: function () {
                //输入完成调用

                var ids = $("#selid").val();
                if (ids == "") {
                    top.tip_show('没有选中，请点击某行信息。', 2, 1000);
                    return false;
                }

                var $textarea = $("#check_content");
                var content = $textarea.val();
                content = content.replace(/\s/ig, "");
                //检查备注
                if (content == "") {
                    $textarea.focus();
                    //layer.msg("请填写不通过原因",{time:3000});
                    BUI.Message.Show({
                        msg: '请填写不通过原因',
                        icon: 'error',
                        buttons: [],
                        autoHide: true,
                        autoHideDelay: 3000
                    });
                }else{
                    //检查通过,设置内容
                    var index = layer.confirm('确认设置审核不通过（' + ids + ')，理由：' + content, {
                        btn: ['确认', '取消'] //按钮
                    }, function () {
                        //关闭layer
                        layer.close(index);
                        openload("正在提交...");

                        //ajax提交
                        var url = "<?php echo site_url("zcq_pro_shenqing/set_check");?>";
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {content: content, idlist: ids, cs: 0},//0为不通过
                            success: function (data) {
                                console.log(data);

                                layer.close(openbox);
                                layer.msg("操作成功！");

                                setTimeout(function () {
                                    window.location.reload();
                                }, 1200);
                            }
                        });

                    }, function () {
                        //取消
                    });
                }

            }
        });

        //批量不通过
        $("#btn_check_no").click("on", function () {
            var ids = $("#selid").val();
            if (ids == "") {
                top.tip_show('没有选中，请点击某行信息。', 2, 1000);
                return false;
            }
            $("#check_content").val("");
            dialog.show();
        });
    });

    function godel() {
        var ids = $("#selid").val();

        if (ids == "") {
            parent.parent.tip_show('没有选中，请点击某行信息。', 2, 1000);
        }
        else {

            var ajax_url = "<?php echo site_url("zcq_pro_shenqing/del");?>?idlist=" + $("#selid").val();
            //var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
            var url = "<?php echo base_url();?>gl.php/zcq_pro_shenqing/index";
            parent.parent.my_confirm(
                "确认删除选中项目？",
                ajax_url,
                url);
        }
    }

    /**
     * 设置审核通过
     * @param type
     * @returns {boolean}
     */
    function setcheck(type) {

        var ids = $("#selid").val();

        if (ids == "") {
            parent.parent.tip_show('没有选中，请点击某行信息。', 2, 1000);
            return false;
        }

        var url = "<?php echo site_url("zcq_pro_shenqing/set_check");?>";


        openload("处理中");
        $.ajax({
            url: url,
            type: "POST",
            data: {content: "", idlist: ids, cs: type},
            success: function (data) {
                console.log(data);

                layer.close(openbox);
                layer.msg("操作成功！");

                setTimeout(function () {
                    window.location.reload();
                }, 1200);
            }
        });

    }


</script>
