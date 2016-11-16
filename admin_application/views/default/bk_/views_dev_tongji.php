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
                项目名称：
                <select name="pro_id" id="pro_id">
                    <option value="all">全部项目</option>
                    <?php
                    foreach ($prolist as $k => $v) {
                        echo "<option value='{$v['id']}'>{$v['title']}</option>";
                    }
                    ?>
                </select>
                <!--<input type="text" name="search_title" id="search_title"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;-->  

            </div>


            <div class="top_nav_right">
                <table>  
                    <tr>
                        <td class="">实际时段</td>
                        <td>
<!--                            <input type="text" name="start_time" id="start_time" class="Wdate" placeholder="" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', isShowClear: true, readOnly: true})"  style="width:160px" readonly
                                   required errMsg="请选择开始时间" tip="请选择开始时间"
                                   value=""
                                   >-->
                             <input type="text" name="start_time" id="start_time" class="Wdate" placeholder="" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd', isShowClear: true, readOnly: true})"  style="width:160px" readonly
                                   required errMsg="请选择开始时间" tip="请选择开始时间"
                                   value=""
                                   >
                            至
                            <input type="text" name="end_time" id="end_time" class="Wdate" placeholder=""  onclick="WdatePicker({dateFmt: 'yyyy-MM-dd', isShowClear: true, readOnly: true})"  style="width:160px" readonly
                                   required errMsg="请选择结束时间" tip="请选择结束时间"
                                   value=""
                                   >
                        </td>
                    </tr> 
                </table>    
            </div>


            &nbsp;&nbsp;

            <button type="submit" class="btn btn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp;
        </div>

        <table class="table table-bordered table-hover definewidth m10 mystyle">
            <thead>
                <tr>
                    <th width="85">开发者</th>
                    <th width="85">任务总数</th>
                    <th width="85">未完成任务总数</th>
                    <th width="85">完成任务总数</th>
                    <th width="85">超出时间任务数</th>
                    <th width="85">超时任务数</th>
                    <th width="85">超时数</th>
                    <th width="85">计划用时</th>
                    <th width="85">实际工作时数</th>
                    <th width="85">完成时差</th>
                </tr>
            </thead>
            <tbody id="result_">
            </tbody>  

        </table>
        <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">

        </div>

        <div style="margin-left: 20px;font-size: 14px;font-weight: bold;">
            <div style="color:#13777e">以下为统计<span style="color:#dc322f">实际时段</span>的时数</div><br>
            任务总数：计划时段开始时间在统计时间段内的所有任务总数(已接收任务，没有开始的不算)<br>

            未完成任务总数：跨时间段的任务数用括号加红色显示，如：10（1）总共有10个未完成任务，其中1个是跨时间段的任务，跨时间段是指计划时间的开始或结束时间不在统计时间段之内<br>

            完成任务总数：状态为编码完成（不需要测试）、测试通过、任务结束的任务总数<br>

            超出时间任务数：超出指定完成日期时间任务数<br>

            超时任务数：完成时差小于0的任务总数<br>
            
            超时数：超出指定完成时间小时数<br>

            计划用时：计划时数总和<br>

            实际工作时数：所有实际总用时总和<br>

            完成时差：完成时差总和<br>
        </div>



    </body>
</html>



<script>
    $(function() {
        common_request(1);
    });
    function common_request(page) {
         pro_id = $("#pro_id").attr('value');
         start_time = $("#start_time").val();
         end_time = $("#end_time").val();
        //判断时间不能为空
        if ($('#start_time').val() != '' && $('#end_time').val() == '') {
            BUI.Message.Alert("结束时间不能为空", 'error');
            return false;
        }

        if ($('#end_time').val() != '' && $('#start_time').val() == '') {
            BUI.Message.Alert("开始时间不能为空", 'error');
            return false;
        }


        var url = "<?php echo site_url("mokuai/devlist"); ?>?inajax=1";
        var data_ = {
            'page': page,
            'time':<?php echo time(); ?>,
            'action': 'ajax_data',
            'search_title': $("#search_title").val(),
            'pro_id': $("#pro_id").val(),
            'start_time':start_time ,
            'end_time':end_time
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

                        //如果没有超时的任务就显示为0 
                        if (typeof (list[i]['overtime_total']) == 'undefined') {
                            overtime_total = 0;
                        } else {
                            overtime_total = list[i]['overtime_total'].toString();
                            overtime_total = overtime_total.substr(0, (overtime_total.indexOf('.') + 3))
                        }


                        shtml += '<tr>';

                        //切换这个可以采用列表的方式查看
                        shtml += '<td><a href="devlist_detail?uid=' + list[i]['id'] + '">' + list[i]['username'] + '</a></td>';
//                        
                        //   //这个是用弹出框的形式查看
                        //  //还要开启 mokuai.php中的devajax_detail_data方法 ( $page_string = $this->common_page->page_string($total, $per_page, $page, 'ajax_data2',$uid);)
//                        shtml += '<td><a href="javascript:void(0);" onclick="show_geren_detail(1,'+ list[i]['id'] +');">' + list[i]['username'] + '</a></td>';
                        shtml += '<td><a href="<?php echo site_url("tongji_detail/tongji_action") ?>?type=renwu_total&uid=' + list[i]['id'] + '&pro_id=' + pro_id + '&start_time='+start_time+'&end_time='+end_time+'">' + list[i]['renwu_total'] + '</a></td>';
                        shtml += '<td><a href="<?php echo site_url("tongji_detail/tongji_action") ?>?type=not_wancheng&uid=' + list[i]['id'] + '&pro_id=' + pro_id + '&start_time='+start_time+'&end_time='+end_time+'">' + list[i]['not_wancheng'] + '</a></td>';
                        shtml += '<td>' + list[i]['wancheng'] + '</td>';
                        shtml += '<td><a href="<?php echo site_url("tongji_detail/tongji_action") ?>?type=not_in_time&uid=' + list[i]['id'] + '&pro_id=' + pro_id + '&start_time='+start_time+'&end_time='+end_time+'">' + list[i]['not_in_time'] + '</a></td>';
                        shtml += '<td><a href="<?php echo site_url("tongji_detail/tongji_action") ?>?type=over_time&uid=' + list[i]['id'] + '&pro_id=' + pro_id + '&start_time='+start_time+'&end_time='+end_time+'">' + list[i]['over_time'] + '</a></td>';
                        shtml += '<td>' + overtime_total + '</td>';
                        shtml += '<td>' + list[i]['jihua_hours'] + '</td>';
                        shtml += '<td>' + list[i]['shiji_hours'] + '</td>';
                        shtml += '<td>' + list[i]['wancheng_shicha'] + '</td>';
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



<script type="text/javascript" src="<?php echo base_url(); ?><?php echo APPPATH ?>views/static/js/bootstrap.min.js"></script>
<script>



//显示任务详细页中状态变更记录备注详情
    function show_geren_detail(page, uid) {

        //隐藏一份uid
        $('#hidden_uid').val(uid);

        var userid = uid;
        var url = "<?php echo site_url("mokuai/devlist_detail"); ?>?inajax=1";
        var data_ = {
            'page': page,
            'action': 'ajax_data',
//            'uid': <?php // echo $_GET['uid'];   ?>,
            'uid': userid,
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
                    shtml += '';
                    shtml += '<div id="showdetail">';
                    shtml += '<table class="table table-bordered table-hover definewidth m10 mystyle">';
                    shtml += '       <thead>';
                    shtml += '               <tr>';
                    shtml += '                       <th width="85">开发者</th>';
                    shtml += '                       <th width="85">日期</th>';
                    shtml += '                       <th width="85">有效工时</th>';
                    shtml += '                       <th width="85">上岗率</th>';
                    shtml += '               </tr>';
                    shtml += '      </thead>';
                    shtml += '       <tbody id="result_">';

                    for (var i in list) {
                        shtml += '<tr>';
                        shtml += '<td>' + list[i]['name'] + '</td>';
                        shtml += '<td>' + list[i]['shiji_start_time'] + '</td>';
                        shtml += '<td>' + list[i]['shiji_time'] + '</td>';
                        shtml += '<td title="模块id:' + list[i]['mkids'] + '">' + list[i]['shangganglv'] + '</td>';
//                        shtml += '<td>' + list[i]['num'] + '</td>';
//                        shtml += '<td>' + list[i]['mkids'] + '</td>';
                        shtml += '</tr>';
                    }


                    shtml += '       </tbody>  ';
                    shtml += ' </table>';
                    shtml += '<div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">' + msg.resultinfo.obj + ' </div>';
                    shtml += ' </div>';

                    $("#geren_content_detail").html(shtml);
                    $('#geren_detail').modal('show');

                }
            },
            beforeSend: function() {
                $("#geren_content_detail").html('<font color="red"><div style="height:400px;"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></div></font>');
            },
            error: function() {
                BUI.Message.Alert("服务器繁忙", 'error');
            }
        });


        return false;


    }

    function ajax_data2(page, uid) {
        show_geren_detail(page, uid);
    }


</script>


<!--弹出的状态变更记录详细-->
<div class="modal hide fade" id="geren_detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">记录详细</h4>
            </div>
            <div class="form-inline definewidth m20" >    
                <div class="top_nav_left">
                    时间：
                    <select style='width:100px;' name="time" id="time">
                        <option value="all">全部</option>
                        <?php
                        foreach ($time_list as $k => $v) {
                            echo "<option value='{$v['time']}' >{$v['time']}</option>";
                        }
                        ?>
                    </select>
                </div>
                &nbsp;&nbsp;

                <button type="submit" class="btn btn-primary" onclick="show_geren_detail(1, $('#hidden_uid').val())">查询</button>&nbsp;&nbsp;
            </div>
            <div class="modal-body" id="geren_content_detail">




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>      
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dial -->
</div>
<input type='hidden' value=''  id='hidden_uid' name='hidden_uid'/>

