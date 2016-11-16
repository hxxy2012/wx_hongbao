<?php
/**
 * 管理员服务咨询列表
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
        <title>咨询列表</title>
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

<!--搜索条件-->
<div class="form-inline definewidth m20">
    <!--关键字-->
    <form method="get">
        关键字：
        <input type="text" name="search_name"
               class="abc input-default"
               placeholder="标题/全称/简称/联系人/手机"
               value="<?php echo $search_val['name']; ?>"
               style="width:200px;"
        />

        <select name="search_isread" style="width:75px;">
            <option value="" <?php echo $search_val["isread"] == "" ? "selected" : ""; ?>>是否回复</option>
            <option value="0" <?php echo $search_val["isread"] == "0" ? "selected" : ""; ?>>未回复</option>
            <option value="1" <?php echo $search_val["isread"] == "1" ? "selected" : ""; ?>>已回复</option>
        </select>

        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-warning" onclick="window.location.reload(true);">刷新</button>
    </form>
</div>

<!--//列表-->
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th>编号</th>
        <th>标题</th>
        <th>咨询企业</th>
        <th>联系人/手机</th>
        <th>回复</th>
        <th>咨询时间</th>
        <th width='40'></th>
    </tr>
    </thead>
    <tbody id="result_">
    <?php
    foreach ($list as $item) {

        echo "<tr>";
        //编号
        echo "<td>" . $item["id"] . "</td>";
        //标题
        echo "<td>{$item["title"]}</td>";
        //咨询企业
        echo "<td>{$item["username"]}</td>";
        //联系人/手机
        echo "<td>{$item["realname"]}/{$item["tel"]}</td>";
        //回复
        echo "<td>";
        if ($item["receive_isread"] == "1") {
            echo "<span style='color: crimson'>是</span>";
        } else {
            echo "<span style='color: darkorchid'>否</span>";
        }
        echo "</td>";
        //咨询时间
        $time = date("Y-m-d H:i:s", $item['create_time']);
        echo "<td>{$time}</td>";
        //回复按钮
        $edit_url = site_url('zcq_fuwu_zixun/reply') . "?id={$item["id"]}&backurl=" . urlencode(get_url());
        echo "<td><a class='page-action icon-edit' data-href='{$edit_url}' data-id='userlist_{$item["id"]}' id='open_edit_{$item["uid"]}' title='回复{$item["title"]}'></a></td>";
        echo "</tr>";
        echo "\n";
    }
    ?>
    </tbody>
</table>
<div id="div_msg_list_empty" style='text-align:center; display: none'>
    暂无信息
</div>

<!--分页条-->
<div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
    <?php echo $pager; ?>
</div>

<!--操作按钮-->
<div>
    <!--选中的tr的所有id-->
    <input type="hidden" name="selid" id="selid" style=" " value=""/>
    <button class="button" onclick="selall()" style=" ">全选</button>
    <button class="button" onclick="selall2()" style="">反选</button>
    <button class="button button-warning" onclick="mutil_delete()">批量删除</button>
</div>

<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
<script type="text/javascript" src="/admin_application/views/static/Js/bootstrap.min.js"></script>

<script type="text/javascript">
    BUI.use('common/page');
</script>
<script src="/admin_application/views/static/Js/selall.js"></script>
<script type="text/javascript">

    /**
     * 显示结果
     */
    function showResult(data) {
        if (data == 0) {
            top.tip_show('操作成功', 1, 1000);
            window.setTimeout("window.location.reload();", 1000);
        }
        else {
            top.tip_show('操作成功，但有' + data + '个数据设置失败！', 1, 2000);
            window.setTimeout("window.location.reload();", 2000);
        }
    }

    /**
     * 批量删除
     */
    function mutil_delete() {
        var ids = $("#selid").val();
        if (ids == "") {
            top.tip_show('没有选中，请点击某行信息。', 2, 1000);
        }

        if (confirm("确认删除所选？")) {
            var url = "<?php echo site_url("zcq_fuwu_zixun/delete_admin");?>";
            $.ajax({
                url: url,
                dataType: "text",
                type: "GET",
                async: false,
                data:{
                    idlist:ids
                },
                success: function (data) {
                    showResult(data);
                }
            });
        }
    }

    $(document).ready(function () {

        var $trs = $("tr");
        //每一行被点击
        $trs.click(function () {
            seltr($(this));//选中该行
        });

        //暂无信息
        if ($('tbody tr').length == 0) {
            $('#div_msg_list_empty').slideDown();
        }

    });
</script>

</body>
</html>
