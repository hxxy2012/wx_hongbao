<?php
/**
 * 资料核对-走出去数据管理-页面
 * User: 嘉辉
 * Date: 2016-08-18
 * Time: 9:04
 */
?>

<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <style>
        .pc_login label {
            width: 80px;
        }

        .clear {
            clear: both;
        }

        #page_string {
            float: left;
            font-size: 14px;
        }

        #page_string .pagination li {
            float: left;
        }

        .btn {
            width: 60px;
            font-size: 16px;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            line-height: 25px;
            text-align: center;
            margin-left: 5px;
            border: 1px solid #d5d5d5;
            color: #000;
            background: #fff;
            cursor: pointer;
        }

        .btn:hover {
            color: #2f80d5;
        }
    </style>

</head>
<body>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div>
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <!--news list-->
        <div id="" class="pc_member_background_list">
                        <span class="pc_list_present">当前位置：<a href="<?php echo $admin_url; ?>">会员后台首页</a>-><a
                                href="<?php echo $cur_url; ?>">资料核对</a></span>
            <h3 class="pc_list_h3">资料核对</h3>
            <!--            <div class="search">-->
            <!--                <form method="get">-->
            <!--                    关键字：-->
            <!--                    <input type="text" name="search_name"-->
            <!--                           class="abc input-default"-->
            <!--                           placeholder="标题/单位名称"-->
            <!--                           value="--><?php //echo $search_val['name']; ?><!--"-->
            <!--                           style="width:200px;"-->
            <!--                    />-->
            <!--                    -->
            <!---->
            <!--                    <button type="submit" class="btn">查询</button>-->
            <!--                </form>-->
            <!--            </div>-->

            <?php if (is_array($list) && count($list) > 0): ?>
                <table cellspacing="0" cellpadding="0" style="width:100%;" class="mytable">
                    <tr>
                        <th>编号</th>
                        <th>导入日期</th>
                        <th class="membl_manipulate">操作</th>
                    </tr>
                    <tbody id="result_">
                    <?php
                    foreach ($list as $item) {
                        //未阅读就加粗
                        $css = "";
                        if ($item['user_isread'] == '0') {
                            $css = "font-weight: bold;";
                        }

                        echo "<tr style='{$css}'>";
                        //编号
                        echo "<td style='text-align:center;width: 50px '>" . $item["id"] . "</td>";
                        //导入日期
                        $time = date("Y-m-d H:i:s", $item['createtime']);
                        echo "<td style='text-align: center'>{$time}</td>";
                        //回复按钮
                        $url = site_url($controller . "/view") . "?id={$item["id"]}&backurl=" . urlencode(get_url());
                        echo "<td style='text-align:center;'>";
                        echo "<a href='{$url}'>查看</a>";
                        echo "</td>";
                        echo "</tr>";
                        echo "\n";
                    }
                    ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center;margin-top:10px;color:#ccc;">暂无数据</p>
            <?php endif ?>

            <div class="membl_bot">
                <ul>
                    <li>
                        <input type="hidden" name="selid" id="selid" readonly value=""/>
                        <a href="javascript:void(0);" onClick="selall()" class="membl_check_all ">全选</a>
                        <a href="javascript:void(0);" onClick="selall2()" class="membl_reverse_selection ">反选</a>
                    </li>
                    <li>
                        <!--                        <a href="" class="membl_add">增加</a>-->
                        <!--                        <a href="javascript:void(0);" onclick="mutil_delete()" class="membl_del">删除</a>-->
                    </li>
                </ul>
                <input type="hidden" name="selid" id="selid" readonly value=""/>
                <br>
                <br>
                <div id="page_string" style="float:left ; text-align:left ; margin:-4px">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<script src="/home/views/static/js/selall.js"></script>
<script type="text/javascript">
    /**
     * 批量删除显示结果
     */
    function showResult(data) {
        if (data == 0) {
            layer.msg("操作成功!!!");
        }
        else {
            layer.msg('操作成功，但有' + data + '个数据设置失败！');
        }
        window.setTimeout("window.location.reload();", 2000);
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

        layer.confirm('确认删除所选？(ids:' + ids + ')', {
            btn: ['确认', '取消'] //按钮
        }, function () {
            var url = "<?php echo site_url($controller . "/delete");?>";
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
        }, function () {

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
            console.log("暂无信息");
            $('div.membl_bot').slideUp(1000);
        }
    })
</script>
</body>
</html>