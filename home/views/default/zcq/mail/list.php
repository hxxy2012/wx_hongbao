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
            <span class="pc_list_present">当前位置：<a href="<?php echo site_url("adminx/admin_index/index");?>">会员后台首页</a>-><a href="<?php echo site_url("adminx/zcq_mail/index");?>">站内信</a></span>
            <h3 class="pc_list_h3">收信箱</h3>

            <form method="get" >
                关键字：
                <input type="text"  name="sel_title" id="search_title"
                       class="abc input-default"
                       placeholder="标题/发送者"
                       value="<?php echo $search_val['title'];?>"
                       style="width:300px;height:28px"
                />
                <button type="submit" class="mybtn" >查询</button>&nbsp;&nbsp;





            </form>


            <?php
            if(count($list)>0){
                ?>
                <table class="mytable" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th width='10'></th>
                        <th>标题</th>
                        <th width="150">发送人</th>
                        <th width="130">发送时间</th>
                        <th width='60'>操作</th>
                    </tr>
                    </thead>
                    <tbody id="result_">


                    <?php

                    foreach($list as $v){

                        echo "<tr>";
                        echo "<td><input type='checkbox' name='id[]' value='".$v["id"]."'/></td>";
                        echo "<td>";
                        echo $v["isread"]=="0"?"<b>":"";
                        echo $v["title"];
                        echo $v["isread"]=="0"?"</b>":"";
                        echo "</td>";
                        echo "<td>".$v["username"]."</td>";
                        echo "<td>".date("Y-m-d H:i",$v["createtime"])."</td>";
                        echo "<td align='center'>";
                        echo "<a href='".(site_url("adminx/zcq_mail/myview")."?ls=".urlencode(get_url())."&id=".$v["id"])."' title=\"查看站内信编号".$v["id"]."\">查看</a>";
                        echo "</td>";
                        echo "</tr>";
                        echo "\n";
                    }
                    ?>


                    </tbody>

                </table>


                <table border="0" style="margin:20px 0 40px 0; padding:0px;width: 100%" width="100%"><tr><td style="border:0px;" align="left">
                            <input type="hidden" name="selid" id="selid"  value=""/>
                            <button class="mybtn" onclick="selall()" >全选</button>
                            <button class="mybtn" onclick="selall2()" >反选</button>
                            <button class="mybtn" onclick="godel()">删除</button>

                        </td>
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
                echo "暂无站内信";
                echo "</div>";
            }
            ?>
        </div>

    </div>
</div>




<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>
<script>
    function selall(){
        $("input[name='id[]']").each(
            function(){
                $(this)[0].checked = true;
            }
        )
    }
    function selall2(){
        $("input[name='id[]']").each(
            function(){
                $(this)[0].checked = !$(this)[0].checked;
            }
        )
    }
    function godel(){
        if($("input[name='id[]']:checked").length==0){
            layer.msg("请选中要删除的站内信",{time:1000});
            return false;
        }
        idlist = "";
        $("input[name='id[]']:checked").each(function(){

            if(idlist=="") {
                idlist = $(this).val();
            }
            else{
                idlist += "," + $(this).val();
            }
        });


        layer.confirm('确认删除选中的站内信？', {
            btn: ['确认','取消'] //按钮
        }, function(){

            url = "<?php echo site_url("adminx/zcq_mail/del");?>";
            $.ajax({
                url:url,
                type:"POST",
                data:{idlist:idlist},
                success:function(data){
                    window.location.reload();
                }
            });


        }, function(){

        });


    }
</script>
</body>
</html>