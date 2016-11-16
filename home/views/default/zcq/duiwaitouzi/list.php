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
    <script type="text/javascript" src="/home/views/static/js/validate/validator_plus.js"></script>
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
                                href="<?php echo get_url();//$cur_url; ?>">对外投资联系表</a></span>
            <h3 class="pc_list_h3">列表</h3>
            <div class="search">
                <form method="get">
                    关键字：
                    <input type="text" name="search_name"
                           class="abc input-default"
                           placeholder="国内企业名称/国外企业名称"
                           value="<?php echo $search_val['name']; ?>"
                           style="width:200px;"
                    />

                    <!--0未提交 1未审核 2通过 3退回-->
                    <select name="search_status" style="width:75px;">
                        <option value="-1" <?php echo $search_val["status"] == "-1" ? "selected" : ""; ?>>状态</option>
                        <option value="0" <?php echo $search_val["status"] == "0" ? "selected" : ""; ?>>未提交</option>
                        <option value="1" <?php echo $search_val["status"] == "1" ? "selected" : ""; ?>>审核中</option>
                        <option value="2" <?php echo $search_val["status"] == "2" ? "selected" : ""; ?>>通过</option>
                        <option value="3" <?php echo $search_val["status"] == "3" ? "selected" : ""; ?>>退回</option>
                    </select>

                    <button type="submit" class="btn">查询</button>

<!--                    <br>-->
<!--                    <label for="search_createtime">新增时间:</label>-->
<!--                    <input type="text" style="width:150px;" name="search_createtime_start" placeholder="开始时间"-->
<!--                           id="search_createtime_start"-->
<!--                           onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_createtime_start').focus();}})"-->
<!--                           value="--><?php //echo $search_val['createtime_start']; ?><!--"/>-->
<!--                    到-->
<!--                    <input type="text" style="width:150px;" name="search_createtime_end" placeholder="结束时间"-->
<!--                           id="search_createtime_end"-->
<!--                           onclick="laydate({istime: true, format: 'YYYY-MM-DD',choose: function(datas){$('#search_createtime_end').focus();}})"-->
<!--                           value="--><?php //echo $search_val['createtime_end']; ?><!--"/>-->

                </form>
            </div>

            <?php if (is_array($list) && count($list) > 0): ?>
                <table cellspacing="0" cellpadding="0" style="width:100%;" class="mytable">
                    <tr>
                        <th>编号</th>
                        <th>状态</th>
                        <th>国内企业名称</th>
                        <th>国外企业名称</th>
                        <th>创建日期</th>
                        <th class="membl_manipulate">操作</th>
                    </tr>
                    <tbody id="result_">
                    <?php
                    foreach ($list as $item) {

                        echo "<tr >";
                        //编号
                        echo "<td style='text-align:center;width: 50px '>" . $item["id"] . "</td>";
                        //状态
                        $text = "";
                        switch ($item['check_status']) {
                            case '0':
                                $text = "未提交";
                                echo "<td style='color: #74cc40;'>{$text}</td>";
                                break;
                            case '1':
                                $text = "审核中";//未审核
                                echo "<td style='color: #6790cc;'>{$text}</td>";
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
                        //国内企业名称
                        echo "<td>{$item['company_name']}</td>";
                        //境外企业名称
                        echo "<td>{$item['company_name2']}</td>";
                        //创建日期
                        $time = date("Y-m-d H:i:s", $item['create_time']);
                        echo "<td style='text-align: center'>{$time}</td>";
                        //回复按钮
                        $view_url = site_url($controller . "/view") . "?id={$item["id"]}&backurl=" . urlencode(get_url());
                        $edit_url = site_url($controller . "/edit") . "?id={$item["id"]}&backurl=" . urlencode(get_url());

                        echo "<td style='text-align:center;'>";

                        if ($item['check_status'] == '2' || $item['check_status']==1) {
                            //未审核也就是审核中也不能修改
                            //注意：审核通过后，不能修改，只能由管理员修改
                            echo "<a href='{$view_url}'>查看</a>";
                        } else {
                            echo "<a href='{$edit_url}'>修改</a>";
                        }

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
<!--                        <a href="javascript:void(0);" onclick="selall()" class="membl_check_all ">全选</a>-->
<!--                        <a href="javascript:void(0);" onclick="selall2()" class="membl_reverse_selection ">反选</a>-->
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

            <!--提示信息-->
            <br>
            <div>
                <span style="color: #5f4e36;">注意：审核通过后，不能修改，只能由管理员修改</span>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>



<!--时间选择-->
<script type="text/javascript"
        src="/home/views/static/js/laydate/laydate.js"></script>

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
        //注释掉，不需要js动作bylk,2016年9月9日16:58:46
        //暂无信息
        /*if ($('tbody tr').length == 0) {
            console.log("暂无信息");
            $('div.membl_bot').slideUp(1000);
        }*/
    })
</script>
</body>
</html>