<?php
/**
 * 走出去 用户列表
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户管理</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css"/>
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet"
          type="text/css"/>

    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <!--layer js必须放在bui-min.js前面-->
    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
    <script type="text/javascript"
            src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
    <script type="text/javascript" src="/admin_application/views/static/Js/bootstrap.min.js"></script>

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

<!--搜索条件-->
<div class="form-inline definewidth m20">
    <form method="get">
        关键字：
        <input type="text" name="username" id="username"
               class="abc input-default"
               placeholder="用户名/手机号/单位/联系人"
               value="<?php echo $search_val['username']; ?>"
               style="width:200px;"
        />

        <!--审核状态-->
        <select name="selcheck" style="width:75px;<?php echo $isjichu ? 'display:none;' : ''; ?>">
            <option value="" <?php echo $search_val["selcheck"] == "" ? "selected" : ""; ?>>审核状态</option>
            <option value="0" <?php echo $search_val["selcheck"] == "0" ? "selected" : ""; ?>>未审</option>
            <option value="1" <?php echo $search_val["selcheck"] == "1" ? "selected" : ""; ?>>已审</option>
            <option value="99" <?php echo $search_val["selcheck"] == "99" ? "selected" : ""; ?>>审核不通过</option>
        </select>


        <select id="sel_usertype" name="usertype" style="width:75px;<?php echo $isjichu ? 'display:none;' : ''; ?>">
            <option selected>会员类型</option>
            <?php
            foreach ($usertype_list as $v) {
                echo "<option value='" . $v["id"] . "'
	 " . ($search_val['usertype'] == $v["id"] ? "selected" : "") . "
	>" . $v["name"] . "</option>";
            }
            ?>
        </select>

        <!--机构服务类型-->
        <label id="lab_servertype">
            <label>
                机构服务类型:
            </label>
            <?php
            foreach ($server_type_list as $item) {
                //是否选中
                $selected = "";
                if (is_array($search_val['server_type'])) {
                    $selected = in_array($item['id'], $search_val['server_type']) ? "checked" : "";
                }
                //echo "<option value='{$item['id']}' {$selected}>{$item['name']}</option>";
                echo "<label><input type='checkbox' name='server_type[]' value='{$item['id']}' {$selected}>{$item['name']}</label>";
            }
            ?>
        </label>


        <!--        <select name="server_type" style="width: 75px;">-->
        <!--            <option selected>服务类型</option>-->
        <!---->
        <!--        </select>-->


        <select name="status" style="width:75px;<?php echo $isjichu ? 'display:none;' : ''; ?>" id="status">
            <option value="-1" <?php echo $search_val["status"] == "" ? "selected" : ""; ?>>是否冻结</option>
            <option value="1" <?php echo $search_val["status"] == 1 ? "selected" : ""; ?>>否</option>
            <option value="0" <?php echo $search_val["status"] == 0 ? "selected" : ""; ?>>是</option>
        </select>
        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-warning" onclick="window.location.reload(true);">刷新</button>

        <?php if ($isadd) { ?>

            <a class="btn btn-success" id="add_new"
               data-href='<?php echo site_url("user/add") . "?backurl=" . urlencode(get_url()); ?>' data-id='356'
               title='新增会员'>
                新增会员<span class="glyphicon glyphicon-plus"></span>
            </a>
        <?php } ?>


    </form>
</div>

<!--列表-->
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>审核</th>
        <th>类型</th>
        <th>公司全称</th>
        <th>登录用户</th>
        <th>联系人/电话</th>
        <th width='132'>注册日期</th>
        <th width='60'></th>
    </tr>
    </thead>
    <tbody id="result_">


    <?php

    foreach ($list as $v) {

        echo "<tr onclick='seltr($(this))'>";
        //编号
        echo "<td>" . $v["uid"] . "</td>";
        //审核
        //如果审核不通过，就加这些属性
        $plus = $v['checkstatus']=="99"?"class='not-check' data-check-content='{$v['checkno_yuanyin']}'":"";
        echo "<td {$plus}><span style='color:{$v["audit_color"]};'>{$v["audit_title"]}</span></td>";
        //类型，企业或者机构
        if ($v['usertype'] == "45064") {
            //机构
            $str = "";
            foreach ($server_type_list as $item) {
                $pos = strpos($v["server_type"], $item["id"]);
                if ($pos !== false && $pos >= 0) {
                    $str .= $item['name'] . "/";
                }
            }
            //去掉最后一个逗号
            $str = substr($str, 0, strlen($str) - 1);
            if ($str == "") {
                $str = "未指定服务类型";
            }
            echo "<td>{$v["usertype_title"]}<span style='color: #3a4bfb'>[{$str}]</span></td>";
        } else {
            //企业
            echo "<td>{$v["usertype_title"]}</td>";
        }
        //公司全称
        echo "<td>{$v['company']}</td>";
        //登录用户 $v["status"]代表冻结
        echo "<td>{$v["username"]}{$v["status"]}</td>";
        //联系人/电话
        echo "<td>{$v['realname']}/{$v['tel']}</td>";
        //注册日期
        echo "<td>{$v["regdate"]}</td>";
        //操作
        echo "<td>";
        $view_url = site_url('user/view') . "?id={$v["uid"]}";
        //echo "<a class='page-action icon-check' data-href='{$view_url}' data-id='userlist_{$v["uid"]}' title='查看用户{$v["uid"]}'></a>";

        $edit_url = site_url('user/edit') . "?id={$v["uid"]}&backurl=" . urlencode(get_url());
        echo "<a class='page-action icon-edit' data-href='{$edit_url}' data-id='userlist_{$v["uid"]}' id='open_edit_{$v["uid"]}' title='编辑{$v["username"]}的信息'></a>";

        echo "<a class='icon-remove' onclick='deleteone(this);' delete_id='{$v['uid']}' title='删除用户{$v["uid"]}'></a>";

        echo "</td>";
        echo "</tr>";
        echo "\n";
    }
    ?>


    </tbody>

</table>
<!--分页条-->
<div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
    <?php echo $pager; ?>
</div>


<input type="hidden" name="selid" id="selid" style=" " value=""/>
<button class="button" onclick="selall()" style=" ">全选</button>
<button class="button" onclick="selall2()" style="">反选</button>
<button class="button button-success" onclick="goset_check_yes()" id="btn_mutil_ok" style=" ">批量通过</button>
<button class="button button-warning" id="btn_check_no" style=" ">批量不通过</button>

<?php if ($isdel) { ?>
    <button class="button button-danger" onclick="godel()" style=" ">删除</button>
<?php } ?>

</body>
</html>

<script src="/admin_application/views/static/Js/selall.js"></script>
<script type="text/javascript">
    /**
     * 显示审核结果
     */
    function showCheckResult(data) {
        layer.close(openbox);
        if (data == 0) {
            //layer.msg('操作成功！');
            top.tip_show('操作成功', 1, 1000);
            window.setTimeout("window.location.reload();", 1000);
        }
        else {
            //layer.msg('操作成功，但有' + data + '个用户设置失败！');
            top.tip_show('操作成功，但有' + data + '个用户设置失败！', 1, 2000);
            window.setTimeout("window.location.reload();", 2000);
        }
    }

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

    /**
     * 删除一个用户
     */
    function deleteone(obj) {
        var $this = $(obj);
        var id = $this.attr("delete_id");
        if (confirm("确认删除该项(ID:" + id + ")?")) {

            var url = "<?php echo site_url("user/deleteone");?>";
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

    function goedit(uid) {
        alert($("#open_edit_" + uid));
        //window.location.href="<?php echo site_url("user/edit");?>?";
        $("#open_edit_" + uid).click();
    }

    function godel() {
        var ids = $("#selid").val();

        if (ids == "") {
            top.tip_show('没有选中，请点击某行信息。', 2, 1000);
        }
        else {
            var ajax_url = "<?php echo site_url("user/del");?>?idlist=" + $("#selid").val();
            //var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
            var url = "<?php echo base_url();?>gl.php/user/index.shtml";
            parent.parent.my_confirm(
                "确认删除选中用户？",
                ajax_url,
                url);
        }
    }

    ///批量通过
    function goset_check_yes() {
        var ids = $("#selid").val();
        if (ids == "") {
            top.tip_show('没有选中，请点击某行信息。', 2, 1000);
            return;
        }

        var index = layer.confirm('确认设置审核通过（' + ids + ')', {
            btn: ['确认', '取消'] //按钮
        }, function () {
            //关闭layer
            layer.close(index);
            openload("正在提交...");

            //提交
            var url2 = "<?php echo site_url("user/set_check");?>?check=10&idlist=" + ids;
            $.ajax({
                url: url2,
                dataType: "text",
                type: "GET",
//                async: false,
                success: function (data) {
                    showCheckResult(data);
                }
            });
        }, function () {

        });

    }

    BUI.use('bui/overlay', function (Overlay) {
        var dialog = new Overlay.Dialog({
            title: '输入审核不通过原因',
            width: 500,
            height: 220,
            //配置文本
            bodyContent: '<textarea id="content" name="content" style="width:100%;height:150px;"></textarea>',
            success: function () {
                //输入完成调用
                var ids = $("#selid").val();
                if (ids == "") {
                    top.tip_show('没有选中，请点击某行信息。', 2, 1000);
                    return false;
                }
                var content = $("#content").val();

                var index = layer.confirm('确认设置审核不通过（' + ids + ')，理由：' + content, {
                    btn: ['确认', '取消'] //按钮
                }, function () {
                    //关闭layer
                    layer.close(index);
                    openload("正在提交...");

                    //提交
                    var url2 = "<?php echo site_url("user/set_check");?>";
                    $.ajax({
                        url: url2,
                        dataType: "text",
                        type: "GET",
//                        async: false,
                        data: {
                            idlist: ids,
                            content: content,
                            check: 20
                        },
                        success: function (data) {
                            showCheckResult(data);
                        }
                    });
                }, function () {
                    //取消
                });

            }
        });

        //批量不通过
        $("#btn_check_no").click("on", function () {
            var ids = $("#selid").val();
            if (ids == "") {
                top.tip_show('没有选中，请点击某行信息。', 2, 1000);
                return false;
            }
            $("#content").val("");
            dialog.show();
        });
    });

    $(document).ready(function () {
        //label服务类型
        var $lab_servertype = $('#lab_servertype');

        function toggleShowLab(value) {
            if (value == "45064") {
                //选择机构
                $lab_servertype.slideDown();
            } else {
                //企业或其它就隐藏起来
                $lab_servertype.slideUp();
            }
        }

        //用户类型选择
        var $sel_usertype = $('#sel_usertype');
        $sel_usertype.on("change", function () {
            var $this = $(this);
            console.log($this.val());
            toggleShowLab($this.val());
        });

        toggleShowLab($sel_usertype.val());

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

        //批量通过按钮
        $('#btn_mutil_ok').click(function () {
            goset_check_yes();
        });

        /**
         * 对不通过由显示提示
         */
        var layer_index = 0;
        $('td.not-check').hover(function () {
            //进入函数
            var that = $(this);
            //不通过理由
            var text = "不通过理由：" + that.attr("data-check-content");
            layer_index = layer.tips(text, that, {skin: "tips-class"});
            //console.log(that);
        }, function () {
            //离开函数
            //var that = $(this);
            layer.close(layer_index);
        });
    });
</script>
