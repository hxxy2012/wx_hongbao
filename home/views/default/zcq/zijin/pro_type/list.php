<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
    <script type="text/javascript" src="/home/views/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/home/views/static/js/validate/validator.js"></script>
    <style>
        .pc_login label {
            width: 180px;
        }
        .way_table{
            border:0px !important;
        }
        .pc_member_background_list tbody

    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>





<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <div class="pc_member_background_list">
            <span class="pc_list_present">当前位置：<a href="<?php echo site_url("admin/index");?>">会员后台首页</a>><a href="<?php echo site_url("adminx/zcq_pro_type/index");?>">资金申请</a></span>
            <h3 class="pc_list_h3">申请</h3>

            <!--form method="get" >
                关键字：
                <input type="text"  name="sel_title" id="search_title"
                       class="abc input-default"
                       placeholder="标题/发送者"
                       value="<?php echo $search_val['title'];?>"
                       style="width:200px;"
                />
                <button type="submit" class="mybtn" >查询</button>&nbsp;&nbsp;





            </form-->


            <?php
            if(count($list)>0){
                ?>
                <table class="mytable" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th width='90'>状态</th>
                        <th>申报项目</th>
                        <th width="150">时段</th>
                        <th width='60'>操作</th>
                    </tr>
                    </thead>
                    <tbody id="result_">


                    <?php

                    foreach($list as $v){

                        echo "<tr>";
                        echo "<td align='center'>".$v["status_name"]."</td>";
                        echo "<td>";

                        echo $v["title"];

                        echo "</td>";
                        echo "<td align='center'>".date("Y-m-d H:i",$v["starttime"]);
                        echo "<br/>";
                        echo date("Y-m-d H:i",$v["endtime"]);
                        echo "</td>";
                        echo "<td align='center'>";

                        echo "<a href='".(site_url("adminx/zcq_pro_type/add")."?ls=".urlencode(get_url())."&id=".$v["id"])."' title=\"查看申报编号".$v["id"]."\">";
                        echo $v["status_val"]=="0" || $v["status_val"]=="99"?"查看":"";
                        echo $v["status_val"]=="10"?"申请":"";
                        echo "</a>";

                        echo "</td>";
                        echo "</tr>";
                        echo "\n";
                    }
                    ?>


                    </tbody>

                </table>


                <table border="0" style="margin:0px; padding:0px;" width="100%"><tr>

                        <td style="border:0px;" align="right">


                            <div id="page_string"  style="float:right ; text-align:right ; margin:-4px">
                                <?php echo $pager;?>
                            </div>
                        </td>
                    </tr></table>
                <?php
            }
            else{
                echo "<div style='text-align:center;'>";
                echo "暂无申报";
                echo "</div>";
            }
            ?>
        </div>

    </div>
</div>




<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>