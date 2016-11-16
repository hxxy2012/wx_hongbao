<?php
/**
 * 走出去数据管理选择企业用户
 * User: 嘉辉
 * Date: 2016-08-17
 * Time: 9:24
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>选择</title>
    <meta charset="UTF-8">


    <link rel="stylesheet" type="text/css" href="/admin_application/views/static/Css/bootstrap.min.css">
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
            /*padding-bottom: 40px;*/
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

        .mybtn {
            font-family: 'Microsoft Yahei', '微软雅黑', serif;
        }
    </style>
</head>

<body class="definewidth">

<!--搜索条件-->
<div>
    <!--关键字-->
    <form method="get" class="form-inline">
        关键字：
        <input type="text" name="search_name"
               class="abc input-default"
               placeholder="名称/"
               value="<?php echo $search_val['name']; ?>"
               style="width:180px;"
        />

        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-warning" onclick="window.location.reload(true);">刷新</button>

        <br>

    </form>
</div>

<!--//列表-->
<table class="pc_member_background_list table table-bordered"
       style="width: 100%;margin-left: 0;float: none;">
    <thead>
    <tr>
        <!--        <th width='50'>编号</th>-->
        <th style="width: 25%;">编号</th>
        <th style="">简称</th>
        <!--        <th>联系人/电话</th>-->
        <th width='60'></th>
    </tr>
    </thead>

    <tbody id="result_">
    <?php
    foreach ($list as $item) {

        echo "<tr uid='{$item['uid']}'>";
        //编号
        echo "<td>" . $item["uid"] . "</td>";
        //简称
        echo "<td>{$item["username"]}</td>";
        //联系人/手机
        //echo "<td>{$item["realname"]}/{$item["tel"]}</td>";
        //按钮
        echo "<td data-uid='{$item["uid"]}' data-name='{$item["username"]}'>";
        echo "<input type='button' class='btn btn-info' onclick='selectx(this);' value='选择'>";
        echo "</td>";
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
<div id="page_string" style="text-align:right ; margin:-4px">
    <?php echo $pager; ?>
</div>

<script type="text/javascript" src="/admin_application/views/static/Js/jquery-1.8.1.min.js"></script>
<script type="text/javascript">
    /**
     * 选择按钮触发事件
     */
    function selectx(ele) {
        var $this = $(ele);
        var $td = $this.parent();
        parent.setSelectData($td.attr("data-uid"), $td.attr("data-name"));
        parent.closeuser();
    }

    $(document).ready(function () {

        var $trs = $("tr");
        $trs.css("background-color", '');
        //每一行被点击
        $trs.click(function () {
            //seltr($(this));//选中该行
            var $this = $(this);
            var selected = $this.data("selected");
            if (selected) {
                $this.css('background-color', '');
                $this.data("selected", false);
            } else {
                $this.css('background-color', '#cccccc');
                $this.data("selected", true);
            }
        });

        //暂无信息
        if ($('tbody tr').length == 0) {
            $('#div_msg_list_empty').slideDown();
        }

        /**
         * 点击按钮打开新页面
         * @param $ele
         */
        function btnOpenPage($ele) {
            console.log($ele.attr("data-id"), $ele.attr("data-href"), $ele.attr("title"));
            top.topManager.openPage({
                id: $ele.attr("data-id"),
                href: $ele.attr("data-href"),
                title: $ele.attr("title"),
                reload: true
            });
        }

        //新增按钮
        var $add_new = $('#add_new');
        $add_new.click(function () {
            var $this = $(this);
            btnOpenPage($this);
        });

        //每一个点击增资列表的按钮
        $('a.zengziIndex').click(function () {
            var $this = $(this);
            btnOpenPage($this);
        });

    });
</script>

</body>
</html>
