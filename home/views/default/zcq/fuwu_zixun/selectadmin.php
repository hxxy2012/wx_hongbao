<?php
/**
 * 服务咨询选择会员用户页面
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
    <title>服务咨询选择管理员用户</title>
    <meta charset="UTF-8">
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <link rel="stylesheet" type="text/css" href="/home/views/static/css/bootstrap.min.css">
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

<body style="padding-left:10px;font-family:'Microsoft Yahei', '微软雅黑'">

<!--搜索条件-->
<div style="margin-top:5px;">
    <!--关键字-->
    <form method="get">
        关键字：
        <input type="text" name="search_name"
               class="abc input-default"
               placeholder="名称"
               value="<?php echo $search_val['name']; ?>"
               style="width:150px;margin-bottom: 0"
        />

        <button type="submit" class="mybtn">查询</button>
        <button type="button" class="mybtn" onclick="window.location.reload(true);">刷新</button>

        <br>

    </form>
</div>

<!--//列表-->
<table class="pc_member_background_list table table-bordered"
       style="width: 100%;margin-left: 0;float: none;margin-top: -10px;">
    <thead>
    <tr>
        <!--        <th width='50'>编号</th>-->
        <!--        <th style="width: 150px;">类型</th>-->
        <th style="width: 250px;">名称</th>
        <!--        <th>联系人/电话</th>-->
        <!--        <th width='60'></th>-->
    </tr>
    </thead>

    <tbody id="result_">
    <?php
    foreach ($list as $item) {

        echo "<tr uid='{$item['id']}'>";
        //编号
        //echo "<td>" . $item["uid"] . "</td>";
        //名称
        echo "<td>{$item["username"]}</td>";
        //联系人/手机
        //echo "<td>{$item["realname"]}/{$item["tel"]}</td>";
        //修改按钮
        //echo "<td>";
        //echo "</td>";
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

<!--操作按钮-->
<div style="margin-bottom: 30px">
    <!--选中的tr的所有id-->
    <input type="hidden" name="selid" id="selid" style=" " value=""/>
    <button onclick="selall()" class="mybtn">全选</button>
    <button onclick="selall2()" class="mybtn">反选</button>
    <button onclick="mutil_add_json()" class="mybtn">添加</button>
    <button onclick="top.closeadmin();" class="mybtn">关闭窗口</button>
</div>


<script type="text/javascript">

    /**
     * 获得所选项的json
     */
    function getSelectedJson() {
        var arr = [];
        $("#result_").find("tr").each(function () {
            var $this = $(this);
            var selected = $this.data("selected");
            if (selected) {
                //被选中的
                var tdArr = $this.children();
                //新建对象保存数据
                var obj = {};
                obj.id = $this.attr("uid");
                //类型
                obj.name = tdArr.eq(0).text();
                //名称
                obj.type = "";
                arr.push(obj);
            } else {
                //未被选中

            }
        });
        var json = JSON.stringify(arr);
        console.log("选择的json：" + json);
        return json;
    }

    /**
     * 全选
     */
    function selall() {
        $("#result_").find("tr").each(function () {
            //tr
            var $this = $(this);
            $this.data("selected", true);
            $this.css('background-color', '#cccccc');
        });
    }

    /**
     * 反选
     */
    function selall2() {
        $("#result_").find("tr").each(function () {
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
    }

</script>
<script type="text/javascript">

    /**
     * 批量删除
     */
    function mutil_add_json() {
        var json = getSelectedJson();
        if (json == "[]") { //list_arr.length == 0
            top.layer.msg('没有选中，请点击某行信息。');
            return;
        }
        top.add_admin(json);
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
