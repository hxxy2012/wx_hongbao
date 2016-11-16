<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>列表</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css" />   
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" />   
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/validate/validator.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/DatePicker/WdatePicker.js"></script>    
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
    <body>
        <div class="form-inline definewidth m20" >    
            <div class="top_nav_left">
                时间：
                <select style='width:100px;' name="time" id="time">
                    <option value="all">全部</option>
                    <?php
                    foreach ($time_list as $k => $v) {
                        echo "<option value='{$v['time']}'>{$v['time']}</option>";
                    }
                    ?>
                </select>
            </div>
            &nbsp;&nbsp;

            <button type="submit" class="btn btn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp;
            <!--<button type="button" class="btn btn-success" onclick="window.history.go(-1)">返回</button>&nbsp;&nbsp;-->
            <button type="button" class="btn btn-success" onclick="window.history.go(-1)">返回</button>&nbsp;&nbsp;
        </div>

        <table class="table table-bordered table-hover definewidth m10 mystyle">
            <thead>
                <tr>
                    <th width="85">开发者</th>
                    <th width="85">日期</th>
                    <th width="85">有效工时</th>
                    <th width="85">上岗率</th>
                </tr>
            </thead>
            <tbody id="result_">
            </tbody>  

        </table>
        <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">

        </div>





    </body>
</html>



<script>
    $(function() {
        common_request(1);
    });
    function common_request(page) {

        var url = "<?php echo site_url("mokuai/devlist_detail"); ?>?inajax=1";
        var data_ = {
            'page': page,
            'action': 'ajax_data',
            'uid': <?php echo $_GET['uid']; ?>,
            'time': $("#time").val()
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data_,
            cache: false,
            dataType: "json",
            //  async:false,
            success: function(msg) {
                var shtml = '';
                var list = msg.resultinfo.list;

                if (msg.resultcode < 0) {
                    BUI.Message.Alert("没有权限执行此操作", 'error');
                    return false;
                } else if (msg.resultcode == 0) {
                    BUI.Message.Alert("服务器繁忙", 'error');
                    return false;
                } else {
                    for (var i in list) {
                        shtml += '<tr>';
                        shtml += '<td>' + list[i]['name'] + '</td>';
                        shtml += '<td>' + list[i]['shiji_start_time'] + '</td>';
                        shtml += '<td>' + list[i]['shiji_time'] + '</td>';
                        shtml += '<td title="模块id:'+list[i]['mkids']+'">' + list[i]['shangganglv'] + '</td>';
//                        shtml += '<td>' + list[i]['num'] + '</td>';
//                        shtml += '<td>' + list[i]['mkids'] + '</td>';
                        shtml += '</tr>';
                    }
                    $("#result_").html(shtml);

                    $("#page_string").html(msg.resultinfo.obj);
                }
            },
            beforeSend: function() {
                $("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
            },
            error: function() {
                BUI.Message.Alert("服务器繁忙", 'error');
            }
        });


    }
    function ajax_data(page) {
        common_request(page);
    }



</script>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
