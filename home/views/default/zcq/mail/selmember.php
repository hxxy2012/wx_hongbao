<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
    <style>
        .member_list_way {
            /*overflow: hidden;*/
            width: 160px;
            height: 30px;
            float: left;
            margin-left: 3px;
        }


    </style>

</head>
<body class="definewidth">

<div class="form-inline definewidth m20">
    <form method="get">
        <span style="margin-left: 10px;">关键字：</span>
        <input type="text" name="sel_title" id="search_title"
               class="abc input-default"
               placeholder="公司简称"
               value="<?php echo $sel_title; ?>"
               style="width:200px;"
        />
        <button type="submit" class="mybtn">查询</button>
        &nbsp;&nbsp;


        <input type="hidden" name="idlist" id="idlist" value="<?php echo $idlist; ?>"/>
        <input type="hidden" name="usertype" value="<?php echo $usertype; ?>"/>
    </form>
</div>

<?php
if (count($list) > 0) {
    ?>


    <form method="post" id="myform">

        <table class="table table-bordered table-hover  m10">
            <tbody>
            <tr>
                <td>

                    <?php
                    $arr = explode(",", $idlist);
                    foreach ($list as $v) {
                        echo '<div class="member_list_way" title="' . $v["company"] . '">';
                        echo '<table><tr><td>';
                        echo '<input type="checkbox" name="id[]" value="' . $v["uid"] . '"';
                        if (in_array($v["uid"], $arr)) {
                            echo " checked ";
                        }
                        echo '/>';
                        echo "</td>";
                        echo "<td>";
                        echo "<span style='white-space: pre;text-overflow:ellipsis;overflow: hidden;display:block;width: 140px'>{$v["username"]}</span>";
                        //echo $v["username"];
                        echo "</td></tr></table>";
                        echo '</div>';
                        echo "\n";
                    }
                    ?>

                </td>
            </tr>
            </tbody>

        </table>


        <table border="0" style="margin:0px; padding:0px;" width="100%">
            <tr>
                <td style="border:0px;" align="left">
                    <input type="hidden" name="selid" id="selid" value="<?php echo $idlist; ?>"/>

                    <button class="mybtn" type="button" style="width:120px ;" onclick="selall()">全选</button>
                    <button class="mybtn" type="button" style="width:120px ;" onclick="selall2()">反选</button>
                    <button class="mybtn" type="button" style="width:120px ;" onclick="return gosel()">添加选中</button>

                    <button class="mybtn" type="button" style="width:120px ;" onclick="parent.closefj()">关闭窗口</button>
                </td>
                <td style="border:0px;" align="right">


                </td>
            </tr>
        </table>
        <input type="hidden" name="usertype" value="<?php echo $usertype; ?>"/>
    </form>
    <?php
} else {
    echo "<div style='text-align:center;'>";
    echo "暂无会员";
    echo "</div>";
}
?>

</body>
</html>
<script>
    function selall() {
        $("input[name='id[]']").each(
            function () {
                $(this)[0].checked = true;
            }
        )
    }
    function selall2() {
        $("input[name='id[]']").each(
            function () {
                $(this)[0].checked = !$(this)[0].checked;
            }
        )
    }
    function gosel() {
        i = 0;
        $("input[name='id[]']").each(
            function () {
                if ($(this)[0].checked) {
                    i++;
                }
            }
        );
        if (i == 0) {
            parent.parent.tip_show("没有选中会员", 0, 1000);
            return false;
        }
        $("#myform").submit();

    }


</script>


