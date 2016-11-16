<?php
/**
 * 对外投资联系 ---- 列表
 * User: 嘉辉
 * Date: 2016-08-08
 * Time: 17:18
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>对外投资联系表</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css"/>
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
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }

        /*layer的tips*/
        body .tips-class .layui-layer-content {
            background-color: #7D571D;
            font-size: 15px;
            font-family: 'Microsoft Yahei', '微软雅黑', serif;
        }

        /*body .tips-class .layui-layer-title{background:#c00; color:#fff; border: none;}*/
        /*body .tips-class .layui-layer-btn{border-top:1px solid #E9E7E7}*/
        /*body .tips-class .layui-layer-btn a{background:#333;}*/
        /*body .tips-class .layui-layer-btn .layui-layer-btn1{background:#999;}*/

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

<body class="definewidth">

<!--搜索条件-->
<div class="form-inline definewidth m20">
    <!--关键字-->
    <form method="get">
        关键字：
        <input type="text" name="search_name"
               class="abc input-default"
               placeholder="境内企业名称/境外企业名称/提交人姓名/用户名"
               value="<?php echo $search_val['name']; ?>"
               style="width:300px;"
        />

        <!--0未提交 1未审核 2通过 3退回-->
        <select name="search_status" style="width:75px;">
            <option value="-1" <?php echo $search_val["status"] == "-1" ? "selected" : ""; ?>>状态</option>
            <option value="1" <?php echo $search_val["status"] == "1" ? "selected" : ""; ?>>未审核</option>
            <option value="2" <?php echo $search_val["status"] == "2" ? "selected" : ""; ?>>通过</option>
            <option value="3" <?php echo $search_val["status"] == "3" ? "selected" : ""; ?>>退回</option>
        </select>

        <!--        <label for="search_createtime">新增时间:</label>-->
        <!--        <input type="text" style="width:150px;" name="search_createtime_start" placeholder="开始时间"-->
        <!--               id="search_createtime_start"-->
        <!--               onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_createtime_start').focus();}})"-->
        <!--               value="--><?php //echo $search_val['createtime_start']; ?><!--"/>-->
        <!--        到-->
        <!--        <input type="text" style="width:150px;" name="search_createtime_end" placeholder="结束时间"-->
        <!--               id="search_createtime_end"-->
        <!--               onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_createtime_end').focus();}})"-->
        <!--               value="--><?php //echo $search_val['createtime_end']; ?><!--"/>-->

        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-warning" onclick="window.location.reload(true);">刷新</button>
        <a id="add_new" class='btn btn-info' data-href='<?php echo site_url($controller . "/add"); ?>' data-id='357'
           title='添加新的对外投资联系项'>新增</a>

    </form>
</div>

<!--//列表-->
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th>序号</th>
        <th>国内企业名称/境外企业名称</th>
        <th>状态</th>
        <th>联系人/手机</th>
        <th>新增时间/修改时间</th>
        <th width='80'>操作</th>
    </tr>
    </thead>
    <tbody id="result_">
    <?php
    foreach ($list as $item) {

        echo "<tr>";
        //编号
        echo "<td>" . $item["id"] . "</td>";
        //国内企业名称/境外企业名称
        echo "<td>{$item['company_name']}<br>{$item['company_name2']}</td>";
        //状态
        $text = "";
        switch ($item['check_status']) {
            case '1':
                $text = "未审核";
                echo "<td style='color: #CCCCCC;'>{$text}</td>";
                break;
            case '2':
                $text = "通过";
                echo "<td style='color: #8FCC33;'>{$text}</td>";
                break;
            case '3':
                $text = "退回";
                echo "<td style='color:red;' class='not-check' data-check-content='{$item['check_content']}'>{$text}</td>";
                break;
        }
        //联系人/手机
        if (isset($item["realname"]) && isset($item['username'])) {
            echo "<td>{$item["realname"]}/{$item["username"]}</td>";
        } else {
            echo "<td>该条记录由管理员添加</td>";
        }
        //新增时间/修改时间
        $time_add = date("Y-m-d h:m:s", $item['create_time']);
        $time_update = date("Y-m-d h:m:s", $item['update_time']);
        echo "<td>{$time_add}<br>{$time_update}</td>";
        //修改按钮
        echo "<td>";
        $view_url = site_url($controller . "/view") . "?id={$item["id"]}";
        echo "<a class='page-action icon-list-alt' data-href='{$view_url}' data-id='userlist_{$item["id"]}' title='查看对外投资联系{$item["id"]}'></a>";

        $edit_url = site_url($controller . "/edit") . "?id={$item["id"]}&backurl=" . urlencode(get_url());
        echo "<a class='page-action icon-edit' data-href='{$edit_url}' data-id='pro_{$item["id"]}' title='修改对外投资联系{$item["id"]}'></a>";

        echo "<a class='icon-remove' onclick='deleteone(this);' delete_id='{$item['id']}' title='删除对外投资联系{$item["id"]}'></a>";

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
    <button class="button button-info" onclick="mutil_ok()">批量通过</button>
    <button class="button button-danger" onclick="mutil_notok()">批量退回</button>

</div>

<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
<!--时间选择-->
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/laydate/laydate.js"></script>
<!--layer-->
<script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/Js/layer/layer.js"></script>
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
     * 批量删除显示结果
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
     * 删除一条
     */
    function deleteone(obj) {
        var $this = $(obj);
        var id = $this.attr("delete_id");

        layer.confirm("确认删除该项(ID:" + id + ")?", {
            btn: ['确认', '取消'] //按钮
        }, function () {
            var url = "<?php echo site_url($controller . "/deleteone");?>";
            $.ajax({
                url: url,
                dataType: "json",
                type: "POST",
                async: false,
                data: {
                    id: id
                },
                success: function (data) {
                    top.tip_show(data.msg, 1, 1000);
                    window.setTimeout("window.location.reload();", 1000);
                }
            });
        }, function () {

        });
    }

    /**
     * 多个操作
     */
    function mutil(text, url) {

        var ids = $("#selid").val();
        if (ids == "") {
            top.tip_show('没有选中，请点击某行信息。', 2, 1000);
            return;
        }

        var dialog = layer.confirm(text, {
            btn: ['确认', '取消'] //按钮
        }, function () {
            layer.close(dialog);
            $.ajax({
                url: url,
                dataType: "text",
                type: "GET",
                data: {
                    idlist: ids
                },
                success: function (data) {
                    showResult(data);
                }
            });
        }, function () {

        });
    }

    /**
     * 批量删除
     */
    function mutil_delete() {
        var url = '<?php echo site_url($controller . "/delete");?>';
        mutil("确认删除所选？", url);
    }

    /**
     * 批量审核通过
     */
    function mutil_ok() {
        var url = '<?php echo site_url($controller . "/ok");?>';
        mutil("确认审核通过所选？", url);
    }

    /**
     * 批量审核不通过
     */
    function mutil_notok() {
        var ids = $("#selid").val();
        if (ids == "") {
            top.tip_show('没有选中，请点击某行信息。', 2, 1000);
            return;
        }

        var url = '<?php echo site_url($controller . "/notok");?>';
        layer.open({
            title: '填写退回理由',
            scrollbar: false,
            type: 0,
            shade: [0.8, '#393D49'],
            btn: ['确定', '取消'],
            area: ['500px', '300px'],
            content: '<textarea id="content" name="content" style="width:95%;height:95%;"></textarea>',
            success: function (layero, index) {
                //创建窗口完毕
                //layer.iframeAuto(index);
            },
            yes: function (index, layero) {
                //确定

                //do something
                var content = $('#content').val();

                layer.close(index); //如果设定了yes回调，需进行手工关闭
                var load_index = layer.load(2);

                $.ajax({
                    url: url,
                    dataType: "text",
                    type: "POST",
                    data: {
                        idlist: ids,
                        content: content
                    },
                    success: function (data) {
                        layer.close(load_index);
                        showResult(data);
                    }
                });
                //console.log($('#content').val());
            },
            btn2: function (index, layero) {
                //取消
            }
        });
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

        //表格垂直居中
        $('tbody tr td').css("vertical-align", "middle");


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

        /**
         * 对退回理由显示提示
         */
        $('td.not-check').hover(function () {
            //进入函数
            var that = $(this);
            //不通过理由
            var text = "退回理由：" + that.attr("data-check-content");
            var index = layer.tips(text, that, {skin: "tips-class"});
            that.data("tip_index", index);
            //console.log(that);
        }, function () {
            //离开函数
            var that = $(this);
            var index = that.data("tip_index");
            layer.close(index);
        });

        //新增按钮
        var $add_new = $('#add_new');
        $add_new.click(function () {
            var $this = $(this);
            btnOpenPage($this);
        });

    });
</script>

</body>
</html>
