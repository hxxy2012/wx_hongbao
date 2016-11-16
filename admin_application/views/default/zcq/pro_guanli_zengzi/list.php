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
        <title>项目管理增资列表</title>
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

<div>
    <h1>项目id：<?php echo $pro_id; ?>的增资列表</h1>
</div>

<!--搜索条件-->
<div class="form-inline definewidth m20">
    <!--关键字-->
    <form method="get">
        <input type="hidden" name="pro_id" value="<?php echo $pro_id; ?>">
        关键字：
        <input type="text" name="search_name"
               class="abc input-default"
               placeholder="国内投资主体名称/境外企业名称"
               value="<?php echo $search_val['name']; ?>"
               style="width:300px;"
        />
        <label for="search_zengzi_date">增资时间:</label>
        <input type="text" style="width:150px;" name="search_zengzi_date_start" placeholder="开始时间"
               id="search_zengzi_date_start"
               onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_zengzi_date_start').focus();}})"
               value="<?php echo $search_val['zengzi_date_start']; ?>"/>
        到
        <input type="text" style="width:150px;" name="search_zengzi_date_end" placeholder="结束时间"
               id="search_zengzi_date_end"
               onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_zengzi_date_end').focus();}})"
               value="<?php echo $search_val['zengzi_date_end']; ?>"/>
        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-warning" onclick="window.location.reload(true);">刷新</button>
        <a id="add_new" class='btn btn-info'
           data-href='<?php echo site_url("zcq_pro_guanli_zengzi/add") . "?pro_id={$pro_id}"; ?>' data-id='356'
           title='添加新的增资'>添加增资</a>
    </form>
</div>

<!--//列表-->
<table class="table table-bordered table-hover m10 list_table">
    <thead>
    <tr>
        <th>序号</th>
        <th>国内投资主体名称</th>
        <th>境外企业名称</th>
        <th>增资协议投资额</th>
        <th>增资中方投资额</th>
        <th>增资时间</th>
        <th width='60'>操作</th>
    </tr>
    </thead>
    <tbody id="result_">
    <?php
    foreach ($list as $item) {
        echo "<tr data-id={$item['id']}>";
        //编号
        echo "<td>" . $item["id"] . "</td>";
        //国内投资主体名称
        echo "<td>{$item["companyname"]}</td>";
        //境外企业名称
        echo "<td>{$item["companyname2"]}</td>";
        //增资协议投资额
        echo "<td class='show_detail'>{$item["zengzi_touzi"]}</td>";
        //增资中方投资额
        echo "<td>{$item["zengzi_touzi2"]}</td>";
        //增资时间
        $time = date("Y-m-d", $item['createtime']);
        echo "<td>{$time}</td>";
        //修改按钮
        echo "<td>";
        $view_url = site_url('zcq_pro_guanli_zengzi/view') . "?id={$item["id"]}";
        echo "<a class='page-action icon-list-alt' data-href='{$view_url}' data-id='userlist_{$item["id"]}' title='查看增资{$item["id"]}'></a>";
        $edit_url = site_url('zcq_pro_guanli_zengzi/edit') . "?id={$item["id"]}&backurl=" . urlencode(get_url());
        echo "<a class='page-action icon-edit' data-href='{$edit_url}' data-id='zengzi_{$item["id"]}' title='修改增资{$item["id"]}'></a>";

        echo "<a class='icon-remove' onclick='deleteone(this);' delete_id='{$item['id']}' title='删除增资{$item["id"]}'></a>";
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

<!--打印hover时候显示的详细信息-->
<?php foreach ($list as $model) : ?>
    <div style="position: absolute;display: none" id="tips_<?php echo $model['id']; ?>">
        <table class="table table-bordered table-hover definewidth">
            <tr>
                <th>序号"<?php echo $model['id']; ?>"</th>
                <th>中方持股比例</th>
                <th>协议投资额</th>
                <th>中方投资额</th>
            </tr>
            <tr>
                <th>增资前</th>
                <td>
                    <!-- 增资前_中方持股比例：-->
                    <input type="text" name="mysql_bili" style="width:200px;"
                           minLength="1"
                           value="<?php echo $model['bili']; ?>">
                </td>
                <td>
                    <!--增资前_协议投资额（万美元）：-->
                    <input type="text" name="mysql_xieyi_touzi" style="width:200px;"
                           minLength="1" valtype="xiaoshu2"
                           value="<?php echo $model['xieyi_touzi']; ?>">
                </td>
                <td>
                    <!--增资前_中方投资额（万美元）：-->
                    <input type="text" name="mysql_zhongfang_touzi" style="width:200px;"
                           minLength="1"
                           value="<?php echo $model['zhongfang_touzi']; ?>">
                </td>
            </tr>
            <tr>
                <th>增资后</th>
                <td>
                    <!--增资后_中方持股比例：-->
                    <input type="text" name="mysql_bili2" style="width:200px;"
                           minLength="1"
                           value="<?php echo $model['bili2']; ?>">
                </td>
                <td>
                    <!--增资后_协议投资额（万美元）：-->
                    <input type="text" name="mysql_xieyi_touzi2" style="width:200px;"
                           minLength="1"
                           value="<?php echo $model['xieyi_touzi']; ?>">
                </td>
                <td>
                    <!--增资后_中方投资额（万美元）：-->
                    <input type="text" name="mysql_zhongfang_touzi2" style="width:200px;"
                           minLength="1"
                           value="<?php echo $model['zhongfang_touzi2']; ?>">
                </td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>


<script type="text/javascript"
        src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
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
     * 删除一条
     */
    function deleteone(obj) {
        var $this = $(obj);
        var id = $this.attr("delete_id");
        if (confirm("确认删除该项(ID:" + id + ")?")) {

            var url = "<?php echo site_url("zcq_pro_guanli_zengzi/deleteone");?>";
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
            var url = "<?php echo site_url("zcq_pro_guanli_zengzi/delete");?>";
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

    });

    $(document).ready(function () {
        var $tds = $("td.show_detail");

        var $tips;
        //hover提示
        $tds.mouseover(function (e) {
            //进入函数

            var $this = $(this);
            var $tr = $this.parent("tr");

            $tips = $("#tips_" + $tr.attr("data-id")).slideDown('100');
            //正中显示
            var offset = $tr.offset();
            var left = offset.left + ($tr.width() - $tips.width()) / 2;
            var top = offset.top + $tr.height();
            $tips.css({
                "top": top + "px",
                "left": left + "px"
            });

        });
        $tds.mouseout(function () {
            $tips.hide();
        });

    })
</script>

</body>
</html>
