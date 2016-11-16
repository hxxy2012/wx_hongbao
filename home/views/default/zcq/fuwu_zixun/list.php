<?php
/**
 * 我的服务咨询页面
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
                                href="<?php echo $cur_url; ?>">我的咨询</a></span>
            <h3 class="pc_list_h3">我的咨询</h3>
            <div class="search">
                <form method="get">
                    关键字：
                    <input type="text" name="search_name"
                           class="abc input-default"
                           placeholder="标题/单位名称"
                           value="<?php echo $search_val['name']; ?>"
                           style="width:300px;height:28px"
                    />

                    <select name="search_isread" style="width:85px;">
                        <option value="" <?php echo $search_val["isread"] == "" ? "selected" : ""; ?>>是否回复</option>
                        <option value="0" <?php echo $search_val["isread"] == "0" ? "selected" : ""; ?>>未回复</option>
                        <option value="1" <?php echo $search_val["isread"] == "1" ? "selected" : ""; ?>>已回复</option>
                    </select>

                    <button type="submit" class="btn">查询</button>
                </form>
            </div>

            <?php if (is_array($list) && count($list) > 0): ?>
                <table cellspacing="0" cellpadding="0" style="width:100%;" class="mytable">
                    <tr>
                        <th>编号</th>
                        <th>标题</th>
                        <?php
                        if ($sess['usertype']=='45063') {
                            echo "<th>咨询对象</th>";
                        }else{
                            echo "<th>咨询单位</th>";
                        }?>
                        <th>回复</th>
                        <th>咨询时间</th>
                        <th class="membl_manipulate">操作</th>
                    </tr>
                    <tbody id="result_">
                    <?php
                    foreach ($list as $item) {
                        echo "<tr>";
                        //编号
                        echo "<td>" . $item["id"] . "</td>";
                        //标题
                        echo "<td>{$item["title"]}</td>";
                        //咨询对象
                        echo "<td style='text-align: center'>{$item["username"]}<span style='color: #6a750c;'>{$item["zixun_type"]}</span></td>";
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
                        echo "<td style='text-align: center'>{$time}</td>";
                        //回复按钮
                        if ($sess['usertype']=='45063') {
                            $text = "查看";
                            $url = site_url($controller . "/view") . "?id={$item["id"]}&backurl=" . urlencode(get_url());
                        }else{
                            $text = "回复";
                            $url = site_url($controller . "/reply") . "?id={$item["id"]}&backurl=" . urlencode(get_url());
                        }
                        echo "<td style='text-align:center;'>";
                        echo "<a href='{$url}'>{$text}</a>";
                        echo "</td>";
                        echo "</tr>";
                        echo "\n";
                    }
                    ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="margin-top:10px;color:#ccc;">暂无咨询</p>
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
                        <a href="javascript:void(0);" onClick="mutil_delete()" class="membl_del">删除</a>
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