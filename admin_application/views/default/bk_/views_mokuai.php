<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>任务列表</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css" />   
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" />   
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
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

            /*添加一个时间纠正icon*/
            .icon-jiuzheng{
                background:url('<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/glyphicons-halflings.png');
                width:15px;
                height:15px;
                display:block;
                background-position: -360px -145px;
            }
        </style>
    </head>
    <body>
        <div class="form-inline definewidth m20">    
            <span class="renwuname">任务名称：</span>
            <input type="text" name="search_title" id="search_title" class="abc input-default" placeholder="" value="">&nbsp;&nbsp;  
            <select name="search_pid" style="width:100px"   id="search_pid" class="abc input-default">
                <option value="0" selected>--选择项目--</option>
                <?php
                foreach ($pid_list as $v) {
                    echo "<option value='" . $v["id"] . "'>" . $v["title"] . "</option>";
                }
                ?>
            </select>
            &nbsp;&nbsp;
            <select name="search_mokuai_status"  style="width:100px"  id="search_mokuai_status" class="abc input-default">
                <option value="0" selected>--任务状态--</option>
                <?php
                foreach ($mokuai_status_list as $v) {
                    echo "<option value='" . $v["id"] . "'>" . $v["title"] . "</option>";
                }
                ?>
            </select>
            &nbsp;&nbsp; 
            <select name="search_mokuai_userid" style='width:95px'   id="search_mokuai_userid" class="abc input-default">
                <option value="0" selected>--相关人员--</option>    
                <?php
                foreach ($mokuai_userid_list as $v) {
                    echo "<option value='" . $v["id"] . "'>";
                    echo $v["gid"] == 8 ? "[开发]" : "[测试]";
                    echo $v["username"] . "</option>";
                }
                ?>
            </select>
            &nbsp;&nbsp;
            <select name="search_mokuai_create_time"  style="width:100px"  id="search_mokuai_create_time" class="abc input-default">
                <option value="0" selected>--创建时间--</option>    
                <?php
                foreach ($mokuai_date_list as $v) {
                    echo "<option value='" . $v["date"] . "'>";
                    echo $v["date"] . "</option>";
                }
                ?>
            </select>
            &nbsp;&nbsp;
            
            <select name="search_mokuai_jihua_time"  style="width:125px"  id="search_mokuai_jihua_time" class="abc input-default">
                <option value="0" selected>--计划开始时间--</option>    
                <?php
                foreach ($mokuai_date_list_jihua as $v) {
                    echo "<option value='" . $v["date"] . "'>";
                    echo $v["date"] . "</option>";
                }
                ?>
            </select>
            <?php if(is_super_admin()):?>
             &nbsp;&nbsp;
            <select name="search_mokuai_shiji_end_time"  style="width:100px"  id="search_mokuai_shiji_end_time" class="abc input-default">
                <option value="0" selected>--实际时间--</option>    
                <?php
                foreach ($mokuai_date_list_shiji as $v) {
                    echo "<option value='" . $v["date"] . "'>";
                    echo $v["date"] . "</option>";
                }
                ?>
            </select>
             <?php endif;?>
            &nbsp;&nbsp;
            
            <select name="search_jinji"  style="width:100px"   id="search_jinji" class="abc input-default">
                <option value="0" selected>--紧急程度--</option>
                <?php
                foreach ($mokuai_jinji_list as $v) {
                    echo "<option value='" . $v["id"] . "'>" . $v["title"] . "</option>";
                }
                ?>
            </select>  
            <br>
            &nbsp;&nbsp;
            <select name="search_orderby" id="search_orderby" style="display:none;">
                <option value="t1.id desc">默认</option>
                <option value="t1.id asc">ID升</option>
                <option value="t1.title asc">标题升</option>
                <option value="t1.title desc">标题降</option>
                <option value="t1.create_time asc">时间升</option>
                <option value="t1.create_time desc">时间降</option>
                <option value="t1.create_username asc">人名升</option>
                <option value="t1.create_username desc">人名降</option>
            </select>    
            
               <input  style='vertical-align:middle; margin-top:-2px; margin-bottom:1px; <?php if( role_id()==8 || role_id()==10) echo "display:none;"; ?>' type="checkbox" name="search_showme" id="search_showme" value="yes"/>
               <label id="showme2" for="search_showme" style=" <?php if(role_id()==8 || role_id()==10) echo "display:none;"; ?>">只显示自己任务</label>
         
               
            <button type="submit" id="chaxun" class="btn btn-primary mybtn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp; 
             <?php if(is_super_admin()): ?>
            <!--超级管理员才能看到-->
            <button type="submit" id="daochu" class="btn btn-warning mybtn-primary" onclick="dev_export()">导出</button>&nbsp;&nbsp; 
            <?php endif; ?>
            <a  class="btn btn-success mybtn-primary" id="addnew" href="<?php echo site_url("mokuai/add"); ?>?url=<?php echo urlencode(get_url()); ?>">添加任务<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp; 
            <a class="btn btn-inverse mybtn-primary" id="allrenwu" href="<?php echo site_url("mokuai/lists"); ?>" onClick="clearAll()">全部信息</a>&nbsp;&nbsp; 
            <!--<a class="btn btn-inverse" href="<?php echo site_url("mokuai/export"); ?>" >导出</a>-->
            
           
        </div>

        <table class="table table-bordered table-hover definewidth m10 sortable">
            <thead>
                <tr>
                    <th width="66">
                        <span style="text-decoration:underline; cursor:pointer;" onClick="orderby_set('t1.id asc');">编号</span>
                        <span id="orderby_id"></span>
                    </th>
                    <th width="156"><span style="text-decoration:underline;cursor:pointer;" onClick="orderby_set('t1.title asc');">任务简述</span>
                        <span id="orderby_title"></span>
                    </th>
                    <th width="150">

                        计划时段
                        <!--sapn class="icon-arrow-up"></span-->       
                    </th>  

                    <th  width="75"><span>计划用时</span>
                        <span id=""></span>
                    </th>

                    <th width="65"><span style="text-decoration:underline;cursor:pointer;" onClick="orderby_set('t1.create_time asc');">创建时间</span>
                        <span id="orderby_create_time"></span>
                    </th>
                    <th  width="46"><span style="text-decoration:underline;cursor:pointer;" onClick="orderby_set('t1.create_username asc');">创建人</span>
                        <span id="orderby_create_username"></span>
                    </th>	

                    <th  width="49"><span>测试人</span>
                        <span id=""></span>
                    </th>	
                    <th  width="130"><span>实际时段</span>
                        <span id=""></span>
                    </th>

                    <th  width="79"><span>实际总用时</span>
                        <span id=""></span>
                    </th>
                    <th  width="80"><span>完成时差</span>
                        <span id=""></span>
                    </th>
                    <th  width="53">操作</th>
                </tr>
            </thead>
            <tbody  id="result_">
            </tbody>  

        </table>
        <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">

        </div>
        <div style="float:none;clear:both;"></div>
        <div style="text-align:left;padding-left:10px; line-height:200%; color:#666;">
            <span class="label" title="未接收">未接收</span>：管理员和任务开发者可以修改时数，时段等信息<br/>
            <span class="label label-warning" title="已接收">已接收</span>：表示对任务已理解清楚，并接受计划时段和时数<br/>
            <span class="label label-info"  title="工作中">工作中</span>：开发者点击“开始”，任务进入“工作中”状态，其他工作中的任务，自动暂停并计算实际时数，任务不能修改，管理员没限制<br/>
            <span class="label label-zanting" title="测试暂停">测试暂停</span>：测试者点击“测试暂停”,任务暂停并自动计算时数，任务不能修改，管理员没限制<br/>
            <span class="label label-zanting" title="暂停">暂停</span>：开发者点击“暂停”,任务暂停并自动计算时数，任务不能修改，管理员没限制<br/>
            <span class="label label-wancheng"  title="编码完成">编码完成</span>：开发者完成编码时，及时点击“结束”，得出实际时数，任务不能修改，管理员没限制<br/>
            <span class="label label-test"  title="测试中">测试中</span>：测试员进行任务测试，任务不能修改，管理员没限制<br/>
            <span class="label label-success"  title="测试通过">测试通过</span>:测试通过点击“通过”，任务不能修改，管理员没限制<br/>
            <span class="label label-important" title="测试不通过">测试不通过</span>：测试不通过点击“不通过”并描述问题所在，任务不能修改，管理员没限制<br/>
            <span class="label label-jiesu"  title="任务结束">任务结束</span>：管理员核实，任务结束，任务不能修改，管理员没限制<br/>
            <span class="label label-inverse" title="任务取消">任务取消</span>：管理员取消任务，时数不作计算，任务不能修改，管理员没限制<br/>
            <span class="label label-wanchengrenwu" title="任务完成">任务完成</span>：不需要测试的，由编码完成自动改为任务完成，需要测试的任务，会由测试通过后转变为任务完成<br/>
        </div>


    </body>
</html>



<script>
     curr_page = 1;
    var proid = "<?php echo verify_id($this->input->get_post("proid", true)); ?>";
//设置搜索条件
    function setlist(page) {
        if (proid > 0) {
            $("#search_pid").val(proid);
        }
      
           <?php if (!empty($not_wancheng_ids)): ?>
             initialise();//初始化值
            $("#search_title").val("<?php echo $not_wancheng_ids; ?>");
        <?php else: ?>
              setCookie("mokuai_page", page, 7200);
            setCookie("mokuai_search_title", $("#search_title").val(), 7200);
        <?php endif; ?>
        
        setCookie("mokuai_search_pid", $("#search_pid").val(), 7200);
        setCookie("mokuai_search_status", $("#search_mokuai_status").val(), 7200);
        setCookie("mokuai_search_showme", (document.getElementById("search_showme").checked ? "yes" : "no"), 7200);
        setCookie("mokuai_userid", $("#search_mokuai_userid").val(), 7200);
        setCookie("mokuai_jinji", $("#search_jinji").val(), 7200);
        setCookie("mokuai_orderby", $("#search_orderby").val(), 7200);
        setCookie("search_mokuai_create_time", $("#search_mokuai_create_time").val(), 7200);
        setCookie("search_mokuai_jihua_time", $("#search_mokuai_jihua_time").val(), 7200);
        setCookie("search_mokuai_shiji_end_time", $("#search_mokuai_shiji_end_time").val(), 7200);
    }
    
    function initialise(){
        $("#search_title").val('').hide();
        $("#search_pid").val(0).hide();
        $("#search_mokuai_status").val(0).hide();
        $("#search_mokuai_userid").val(0).hide();
        $("#search_jinji").val(0).hide();
        $("#search_mokuai_create_time").val(0).hide();
        $("#search_mokuai_jihua_time").val(0).hide();
        $("#search_mokuai_shiji_end_time").val(0).hide();
        $("#chaxun").hide();
        $("#addnew").hide();
        $("#allrenwu").hide();
        $(".renwuname").hide();
        $("#showme2").hide();
        $("#search_showme").hide();
        $(".m20").css('padding-top',0);
        $(".form-inline.definewidth.m20").css({marginLeft:'0',marginTop:'-10px'});

 
    }

    function clearAll() {
        clearCookie("mokuai_page");
        clearCookie("mokuai_search_title");
        clearCookie("mokuai_search_pid");
        clearCookie("mokuai_search_status");
        clearCookie("mokuai_search_showme");
        clearCookie("mokuai_userid");
        clearCookie("mokuai_jinji");
        clearCookie("mokuai_orderby");
        clearCookie("search_mokuai_create_time");
        clearCookie("search_mokuai_jihua_time");
        clearCookie("search_mokuai_shiji_end_time");
    }

    function getlist() {
        curr_page = getCookie("mokuai_page");
        title = getCookie("mokuai_search_title");
        pid = getCookie("mokuai_search_pid");
        mokuai_status = getCookie("mokuai_search_status");
        mokuai_jinji = getCookie("mokuai_jinji");
        mokuai_userid = getCookie("mokuai_userid");
        mokuai_orderby = getCookie("mokuai_orderby");
        showme = getCookie("mokuai_search_showme");
        search_mokuai_create_time = getCookie("search_mokuai_create_time");
        search_mokuai_jihua_time = getCookie("search_mokuai_jihua_time");
        search_mokuai_shiji_end_time = getCookie("search_mokuai_shiji_end_time");
        if (!title) {//空值返回false
            title = "";
        }
        if (!pid) {
            pid = 0;
        }
        if (!mokuai_status) {
            mokuai_status = "";
        }
        if (!showme) {
            showme = "";
        }
        if (!mokuai_userid) {
            mokuai_userid = "0";
        }
        if (!mokuai_jinji) {
            mokuai_jinji = "0";
        }
        if (!mokuai_orderby) {
            mokuai_orderby = "id asc";
        }
        $("#search_title").val(title);
        $("#search_pid").val(pid);
        $("#search_mokuai_status").val(mokuai_status);
        $("#search_mokuai_userid").val(mokuai_userid);
        $("#search_jinji").val(mokuai_jinji);
        $("#search_orderby").val(mokuai_orderby);
        $("#search_mokuai_create_time").val(search_mokuai_create_time);
        $("#search_mokuai_jihua_time").val(search_mokuai_jihua_time);
        $("#search_mokuai_shiji_end_time").val(search_mokuai_shiji_end_time);
        if (showme == "yes") {
            $("#search_showme").attr("checked", 'true')
        }



        clearCookie("mokuai_page");
        clearCookie("mokuai_search_title");
        clearCookie("mokuai_search_pid");
        clearCookie("mokuai_search_status");
        clearCookie("mokuai_search_showme");
        clearCookie("mokuai_userid");
        clearCookie("mokuai_jinji");
        clearCookie("search_mokuai_create_time");
        clearCookie("search_mokuai_jihua_time");
        clearCookie("search_mokuai_shiji_end_time");
    }

    $(function() {
        getlist();
          <?php if (!empty($not_wancheng_ids)): ?>
               curr_page = 1;   
          <?php endif;?>        
        common_request(curr_page);
    });

//

    function common_request(page) {
        setlist(page);//用于进入添加页面后返回时使用，目的：记录条件
        var url = "<?php echo site_url("mokuai/lists"); ?>?inajax=1";

        var data_ = {
            'page': page,
            'time':<?php echo time(); ?>,
            'action': 'ajax_data',
            'search_title': $("#search_title").val(),
            'search_mokuai_userid': $("#search_mokuai_userid").val(),
            'search_mokuai_status': $("#search_mokuai_status").val(),
            'search_jinji': $("#search_jinji").val(),
            'search_pid': $("#search_pid").val(),
            'search_orderby': $("#search_orderby").val(),
            'search_mokuai_create_time': $("#search_mokuai_create_time").val(),
            'search_mokuai_jihua_time': $("#search_mokuai_jihua_time").val(),
            'search_mokuai_shiji_end_time': $("#search_mokuai_shiji_end_time").val(),
            'search_showme': (document.getElementById("search_showme").checked ? "yes" : "no")
        };
        show_orderby();
        //alert($("#search_orderby").val());
        $.ajax({
            type: "POST",
            url: url,
            data: data_,
            cache: false,
            dataType: "json",
            //async:true,
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
                        shtml += '<td>' + list[i]['id'] + '</td>';
                        shtml += '<td>' + list[i]['title'];
                        shtml += '<br/><span style="color:#666666">项目:' + list[i]['pro_title'] + "</span>";
                        shtml += '<br/><span style="color:#666666">开发者:' + list[i]['mokuai_username'] + "</span>";
                        shtml += list[i]['mokuai_status_ico'];
                        shtml += '</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['jihua_start_time'] + '<br/>' + list[i]['jihua_end_time'];
                        shtml += '<br/><span style="color:#666666">紧急：</span>' + list[i]['jinji_ico'] + '</td>';
//					shtml+='<td align="center" valign="middle">'++'</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['jihua_hours'] + '</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['create_time'] + '</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['create_username'] + '</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['mokuai_test_username'] + '</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['shiji_start_time'] + ' ' + list[i]['shiji_end_time'] + '</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['all_time'] + '</td>';
                        shtml += '<td align="center" valign="middle">' + list[i]['wancheng_shicha'] +  '</td>';
                        shtml += '<td align="center" valign="middle"><a href="<?php echo site_url('mokuai/edit'); ?>?id=' + list[i]['id'] + '&url=<?php echo urlencode(get_url()); ?>" onclick="goedit()" class="icon-edit" title="编辑"></a>&nbsp;&nbsp;<a href="<?php echo site_url('mokuai/mkview'); ?>?id=' + list[i]['id'] + '" class="icon-file" title="浏览任务信息"></a>&nbsp;&nbsp;<a href="<?php echo site_url('mokuai/jiuzheng'); ?>?id=' + list[i]['id'] + '" class="icon-jiuzheng" title="时间纠正"></a></td>';
                        shtml += '</tr>';
                    }
                    $("#result_").html(shtml);

                    $("#page_string").html(msg.resultinfo.obj);
                }
            },
            beforeSend: function() {
                $("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
            },
            error: function(a, b, c) {
                alert(a);
                alert(b);
                alert(c);
                BUI.Message.Alert("服务器繁忙", 'error');
            }
        });


    }
    function ajax_data(page) {
        common_request(page);
    }
    function goedit() {
        setlist(curr_page);//记录搜索条件，方便返回列表
        //window.location.href=url;		
    }
//
//排序
    function show_orderby_clear() {
        document.getElementById("orderby_id").innerHTML = "";
        document.getElementById("orderby_title").innerHTML = "";
        document.getElementById("orderby_create_time").innerHTML = "";
        document.getElementById("orderby_create_username").innerHTML = "";

    }
    function orderby_set(orderval) {
        //alert(orderval);
        orderby_field = $("#search_orderby").val();
        orderby_field = orderby_field.replace("desc", "");
        orderby_field = orderby_field.replace("asc", "");
        new_orderby_field = orderval.replace("desc", "");
        new_orderby_field = new_orderby_field.replace("asc", "");
        if (orderby_field == new_orderby_field) {
            orderby_box = $("#search_orderby").val();
            if (orderby_box.indexOf("desc") >= 0) {
                orderby_box = orderby_box.replace("desc", "asc");
            }
            else {
                orderby_box = orderby_box.replace("asc", "desc");
            }
            $("#search_orderby").val(orderby_box);
        }
        else {
            $("#search_orderby").val(orderval);
        }
        //alert($("#search_orderby").val());
        //getlist();
        common_request(curr_page);
    }
    function show_orderby() {
        show_orderby_clear();
        //alert($("#search_orderby").val());
        if ($("#search_orderby").val() == "t1.id desc")
        {
            //$("#orderby_id").innerHTML="▼";
            document.getElementById("orderby_id").innerHTML = "▼";
        }
        if ($("#search_orderby").val() == "t1.id asc")
        {
            //$("#orderby_id").innerHTML="▲";
            document.getElementById("orderby_id").innerHTML = "▲";
        }

        if ($("#search_orderby").val() == "t1.title desc")
        {
            document.getElementById("orderby_title").innerHTML = "▼";
        }
        if ($("#search_orderby").val() == "t1.title asc")
        {
            document.getElementById("orderby_title").innerHTML = "▲";
        }

        if ($("#search_orderby").val() == "t1.create_time desc")
        {
            document.getElementById("orderby_create_time").innerHTML = "▼";
        }
        if ($("#search_orderby").val() == "t1.create_time asc")
        {
            document.getElementById("orderby_create_time").innerHTML = "▲";
        }

        if ($("#search_orderby").val() == "t1.create_username desc")
        {
            document.getElementById("orderby_create_username").innerHTML = "▼";
        }
        if ($("#search_orderby").val() == "t1.create_username asc")
        {
            document.getElementById("orderby_create_username").innerHTML = "▲";
        }

    }


//导出
    function dev_export() {
        var url = "<?php echo site_url("mokuai/php_export"); ?>";
        var data_ = {
            'time':<?php echo time(); ?>,
            'search_title': $("#search_title").val(),
            'search_mokuai_userid': $("#search_mokuai_userid").val(),
            'search_mokuai_status': $("#search_mokuai_status").val(),
            'search_jinji': $("#search_jinji").val(),
            'search_pid': $("#search_pid").val(),
            'search_mokuai_create_time': $("#search_mokuai_create_time").val(),
            'search_mokuai_jihua_time': $("#search_mokuai_jihua_time").val(),
            'search_mokuai_shiji_end_time': $("#search_mokuai_shiji_end_time").val(),
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data_,
            dataType: 'json',
            success: function() {
                document.getElementById('download_iframe').src = '<?php echo site_url("mokuai/output"); ?>';
            }
        });

    }

//判断是否显示自己的任务
    var isshow = <?php echo (is_super_admin()) ? "1" : "2"; ?>;
    if (isshow == 1) {
        clearCookie("mokuai_search_showme");
        $('#search_showme').attr('checked', false);
        clearCookie("mokuai_search_showme");
    } else {
        clearCookie("mokuai_search_showme");
        $('#search_showme').attr('checked', true);
    }
//alert(document.getElementById("search_orderby").selectedIndex);

//alert($("#search_orderby").val());
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
<iframe style="display:none;" target='_blank' src='' id='download_iframe'></iframe>