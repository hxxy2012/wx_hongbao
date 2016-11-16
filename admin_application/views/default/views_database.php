<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>项目列表</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css" />   
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" />   
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />

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
            /*添加一个删除icon*/
            .icon-del{
                background:url('<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/glyphicons-halflings.png');
                width:15px;
                height:15px;
                display:block;
                float:left;
                background-position: -22px -95px;
            }
            /*添加一个导入icon*/
            .icon-import{
                background:url('<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/glyphicons-halflings.png');
                width:15px;
                height:15px;
                display:block;
                float:left;
                background-position: -310px -95px;
                margin:0 10px;
            }



        </style>
    </head>
    <body>
        <div class="form-inline definewidth m20" >    
            名称：
            <input type="text" name="search_title" id="search_title"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;  


            <button type="submit" class="btn btn-primary" onclick="common_request(1)">查询</button>&nbsp;&nbsp;
            <a  class="btn btn-success" id="addnew" href="<?php echo site_url("sysconfig/bakDatabase"); ?>">备份数据库<span class="glyphicon glyphicon-plus"></span></a>
<br/>            
<span style="color:red;">
数据库建议每周备份一次，并下载到本地电脑上，如需恢复可借助工作“Navicat Premium”恢复，或找开发公司恢复数据库！
</span>
        </div>

        <table class="table table-bordered table-hover definewidth m10">
            <thead>
                <tr>
                    <th width="85">编号</th>
                    <th>名称</th>
                    <th  width="150">操作</th>
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
        var url = "<?php echo site_url("sysconfig/databaselist"); ?>?inajax=1";
        var data_ = {
            'page': page,
            'time':<?php echo time(); ?>,
            'action': 'ajax_data',
            'search_title': $("#search_title").val()
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
                        shtml += '<td>' + list[i]['id'] + '</td>';
                        shtml += '<td>' + list[i]['title'] + '</td>';
                        //删除+还原shtml += '<td><a  onclick="adminDelDatabase('+ list[i]['id'] +',\'<?php echo site_url('sysconfig/delDatabase'); ?>\')"  href="#" class="icon-del" title="删除"></a>&nbsp;&nbsp;<a onclick="adminImportDatabase('+ list[i]['id'] +',\'<?php echo site_url('sysconfig/importDatabase'); ?>\')" href="#"  class="icon-import" title="导入数据库"></a></td>';
                        //删除 + 下载
						shtml += '<td><a  onclick="adminDelDatabase('+ list[i]['id'] +',\'<?php echo site_url('sysconfig/delDatabase'); ?>\')"  href="#" class="icon-del" title="删除"></a>&nbsp;&nbsp;<a href="'+list[i]['filepath']+'" target="blank"   class="icon-import" title="下载数据库"></a></td>';                        
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
//确认是否导入
    function adminImportDatabase(id,url) {

        BUI.Message.Confirm('确认导入数据库？导入后之前数据将永久删除。', function() {
          window.location=url+'?id='+id;
        }, 'question');

  return false;
    }


//确认是否删除
    function adminDelDatabase(id,url) {

        BUI.Message.Confirm('确认删除数据库备份？', function() {
          window.location=url+'?id='+id;
        }, 'question');

  return false;
    }


</script>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>