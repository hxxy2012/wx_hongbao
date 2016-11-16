<?php
/**
 * 项目管理 ---- 列表
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
    <head>
        <title>项目管理列表</title>
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
               placeholder="国内投资主体名称/境外企业名称/联系人/手机"
               value="<?php echo $search_val['name']; ?>"
               style="width:300px;"
        />
        <label for="search_createtime">新增时间:</label>
        <input type="text" style="width:150px;" name="search_createtime_start" placeholder="开始时间"
               id="search_createtime_start"
               onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_createtime_start').focus();}})"
               value="<?php echo $search_val['createtime_start']; ?>"/>
        到
        <input type="text" style="width:150px;" name="search_createtime_end" placeholder="结束时间"
               id="search_createtime_end"
               onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_createtime_end').focus();}})"
               value="<?php echo $search_val['createtime_end']; ?>"/>
        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-warning" onclick="window.location.reload(true);">刷新</button>
        <a id="add_new" class='btn btn-info' data-href='<?php echo site_url("zcq_pro_guanli/add"); ?>' data-id='354'
           title='添加新的项目'>新增项目</a>
    </form>
</div>

<!--//列表-->
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th>序号</th>
        <th>国内投资主体名称</th>
        <th>境外企业名称</th>
        <th>联系人/手机</th>
        <th>新增时间</th>
        <th width='80'>操作</th>
    </tr>
    </thead>
    <tbody id="result_">
    <?php
    foreach ($list as $item) {

        echo "<tr>";
        //编号
        echo "<td>" . $item["id"] . "</td>";
        //国内投资主体名称
        echo "<td>{$item["companyname"]}</td>";
        //境外企业名称
        echo "<td>{$item["companyname2"]}</td>";
        //联系人/手机
        echo "<td>{$item["linkman"]}/{$item["mobile"]}</td>";
        //新增时间
        $time = date("Y-m-d", $item['createtime']);
        echo "<td>{$time}</td>";
        //修改按钮
        echo "<td>";
        $view_url = site_url('zcq_pro_guanli/view') . "?id={$item["id"]}";
        echo "<a class='page-action icon-list-alt' data-href='{$view_url}' data-id='userlist_{$item["id"]}' title='查看项目{$item["id"]}'></a>";

        $edit_url = site_url('zcq_pro_guanli/edit') . "?id={$item["id"]}&backurl=" . urlencode(get_url());
        echo "<a class='page-action icon-edit' data-href='{$edit_url}' data-id='pro_{$item["id"]}' title='修改项目{$item["id"]}'></a>";

        $zengzi_url = site_url('zcq_pro_guanli_zengzi/index') . "?pro_id={$item["id"]}";
        echo "<a class='zengziIndex icon-plus' data-href='{$zengzi_url}' data-id='zengziList_{$item["id"]}' title='项目{$item["id"]}增资列表'></a>";

        echo "<a class='icon-remove' onclick='deleteone(this);' delete_id='{$item['id']}' title='删除项目{$item["id"]}'></a>";

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
</div>

<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
<!--时间选择-->
<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/laydate/laydate.js"></script>
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
        if (confirm("确认删除该项(ID:" + id + ")?")) {

            var url = "<?php echo site_url("zcq_pro_guanli/deleteone");?>";
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
        }
    }

    /**
     * 批量删除
     */
    function mutil_delete() {
        var ids = $("#selid").val();
        if (ids == "") {
            top.tip_show('没有选中，请点击某行信息。', 2, 1000);
            return;
        }

        if (confirm("确认删除所选？")) {
            var url = "<?php echo site_url("zcq_pro_guanli/delete");?>";
            $.ajax({
                url: url,
                dataType: "text",
                type: "GET",
                async: false,
                data: {
                    idlist: ids
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
