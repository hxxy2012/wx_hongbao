<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css"/>

    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet"
          type="text/css"/>

    <script type="text/javascript" src="/admin_application/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/admin_application/views/static/Js/admin.js"></script>
    <script type="text/javascript" src="/admin_application/views/static/assets/js/bui-min.js"></script>
    <script type="text/javascript" src="/admin_application/views/static/assets/js/config-min.js"></script>
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

        a.page-action {
            cursor: pointer;
        }

        /**内容超出 出现滚动条 **/
        .bui-stdmod-body {
            overflow-x: hidden;
            overflow-y: auto;
        }

        .lk_ul {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .lk_ul li {
            float: left;
            width: 46%;
            margin: 10px;
        }

        .fl {
            float: left;
        }

        .fr {
            float: right;
        }

        .clear {
            clear: both;
        }

        .index-table ul li div {
            position: absolute;
            left: 56px;
            top: 2px;
            background: #FF0000;
            color: #FFFFFF;
            font-weight: bold;
            width: 20px;
            border-radius: 8px;
        }

        .index-table ul {
            padding: 0px;
            margin: 0px;
        }

        .index-table ul li {
            position: relative;
            margin-top: 10px;
            width: 80px;
            height: 80px;
            float: left;
            margin-left: 2.5%;
            text-align: center;
            border-radius: 10px;
            border: 1px #999999 solid;
            background: -moz-linear-gradient(top, #cccccc 0%, #ffffff 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #cccccc), color-stop(100%, #ffffff));
            background: -webkit-linear-gradient(top, #cccccc 0%, #ffffff 100%);
            background: -o-linear-gradient(top, #cccccc 0%, #ffffff 100%);
            background: -ms-linear-gradient(top, #cccccc 0%, #ffffff 100%);
            background: linear-gradient(to bottom, #cccccc 0%, #ffffff 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#cccccc, endcolorstr=#ffffff, gradientType=0);
            cursor: pointer;
            list-style: none;
        }

        .index-table ul li a {
            display: block;
            padding: 0px;
        }

        .index-table ul li img {
            margin-top: 8%;
            height: 80%;
            border: none;
        }

        .index-table ul li a span {
            display: block;
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }


    </style>


</head>

<body>
<!-- <h1>
存放未审核信息
</h1> -->


<div style="clear:both">
</div>
<ul class="lk_ul">
    <li>
        <h2 class="fl">申报待审核(<?php echo $list_mimesb['sql_total'];?>条未审核)</h2>
        <h2 class="fr"><a id="pro_shenqing" title="资金申请审批"
                          href="<?php echo site_url("zcq_pro_shenqing/index") . "?ls=" . urlencode(get_url()) . "&isport=no"; ?>">更多</a>
        </h2>
        <div class="clear"></div>
        <table class="table table-bordered table-hover" style="width:100%;">
            <tr>
                <th width="80">类型</th>
                <th>申报单位</th>
                <th width="60">操作</th>
            </tr>
            <?php foreach ($list_mimesb['list'] as $key => $value): ?>
                <tr>
                    <td><?php echo $value['typename'] ?></td>
                    <td align="center"><?php echo $value['title'] ?></td>
                    <td align="center">
                        <?php
                        $url = site_url("zcq_pro_shenqing/edit") . "?id={$value["id"]}&ls=" . urlencode(get_url());
                        echo "<a class='page-action' data-href='{$url}' data-id='shenbaolist_{$value['id']}' title='审核申报{$value['title']}'>审核</a> ";
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </li>
    <li>
        <h2 class="fl">未读站内信(<?php echo $list_znx['sql_total'];?>条未读)</h2>
        <h2 class="fr"><a id="zn_mail" title="收件箱"
                          href="<?php echo site_url("zcq_mail/index") . "?ls=" . urlencode(get_url()); ?>">更多</a></h2>
        <div class="clear"></div>
        <table class="table table-bordered table-hover" style="width:100%;">
            <tr>
                <th>标题</th>
                <th width="80">接收日期</th>
                <th width="60">操作</th>
            </tr>
            <?php foreach ($list_znx['list'] as $key => $value): ?>
                <tr>
                    <td><?php echo $value['title']; ?></td>
                    <td align="center"><?php echo date('Y-m-d', $value['createtime']); ?></td>
                    <td align="center">
                        <?php
                        $url = site_url("zcq_mail/myview") . "?id={$value["id"]}&ls=" . urlencode(get_url());
                        echo "<a class='page-action' data-href='{$url}' data-id='znmiallist_{$value['id']}' title='查看站内信{$value['title']}'>查看</a> ";
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </li>
    <li>
        <h2 class="fl">投资联系表待审核(<?php echo $list_touzi['sql_total'];?>条未审核)</h2>
        <h2 class="fr"><a id="tzlx" title="投资联系表"
                          href="<?php echo site_url("zcq_duiwaitouzi/index") . "?ls=" . urlencode(get_url()); ?>">更多</a>
        </h2>
        <div class="clear"></div>
        <table class="table table-bordered table-hover" style="width:100%;">
            <tr>
                <th>提交人</th>
                <th width="80">提交日期</th>
                <th width="60">操作</th>
            </tr>
            <?php foreach ($list_touzi['list'] as $key => $value): ?>
                <tr>
                    <td><?php echo $value['username']; ?></td>
                    <td align="center"><?php echo date('Y-m-d', $value['create_time']); ?></td>
                    <td align="center">
                        <?php
                        $url = site_url("zcq_duiwaitouzi/edit") . "?id={$value["id"]}&ls=" . urlencode(get_url());
                        echo "<a class='page-action' data-href='{$url}' data-id='touzilist_{$value['id']}' title='编辑投资联系表{$value['id']}'>编辑</a> ";
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </li>
    <li>
        <h2 class="fl">服务咨询解答(<?php echo $list_fwzx['sql_total'];?>条未回)</h2>
        <h2 class="fr"><a id="fwzxjd" title="服务咨询解答"
                          href="<?php echo site_url("zcq_fuwu_zixun/index") . "?ls=" . urlencode(get_url()); ?>">更多</a>
        </h2>
        <div class="clear"></div>
        <table class="table table-bordered table-hover" style="width:100%;">
            <tr>
                <th>标题</th>
                <th width="80">留言者</th>
                <th width="60">操作</th>
            </tr>
            <?php foreach ($list_fwzx['list'] as $key => $value): ?>
                <tr>
                    <td><?php echo $value['title']; ?></td>
                    <td align="center">
                        <?php echo $value['username']; ?>
                    </td>
                    <td align="center">
                        <?php
                        $url = site_url("zcq_fuwu_zixun/reply") . "?id={$value["id"]}&ls=" . urlencode(get_url());
                        echo "<a class='page-action' data-href='{$url}' data-id='zixunlist_{$value['id']}' title='回复咨询{$value['title']}'>回复</a> ";
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </li>
    <li>
        <h2 class="fl">注册审核(<?php echo $list_user['sql_total'];?>个未审核)</h2>
        <h2 class="fr"><a id="userlist" title="用户列表"
                          href="<?php echo site_url("user/index") . "?ls=" . urlencode(get_url()); ?>">更多</a></h2>
        <div class="clear"></div>
        <table class="table table-bordered table-hover" style="width:100%;">
            <tr>
                <th width="100">类型</th>
                <th>单位/联系人</th>
                <th width="60">操作</th>
            </tr>
            <?php foreach ($list_user['list'] as $key => $value): ?>
                <tr>
                    <td>
                        <?php
                        //遍历用户类型列表
                        foreach ($usertype_list as $item) {
                            if ($value['usertype'] == $item['id']) {
                                echo $item['name'];

                                //服务类型
                                if ($item['id'] == "45064") {
                                    $str = "";
                                    foreach ($server_type_list as $each) {
                                        $pos = strpos($value["server_type"], $each["id"]);
                                        if ($pos !== false && $pos >= 0) {
                                            $str .= $each['name'] . "/";
                                        }
                                    }
                                    //去掉最后一个‘/’
                                    $str = substr($str, 0, strlen($str) - 1);
                                    if ($str == "") {
                                        $str = "未指定服务类型";
                                    }
                                    echo "<br><span style='color: #3a4bfb'>[{$str}]</span>";
                                }

                            }
                        }
                        ?>
                    </td>
                    <td align="center" style="vertical-align: middle">
                        <?php echo "{$value['company']}/{$value['username']}";; ?>
                    </td>
                    <td align="center" style="vertical-align: middle">
                        <?php
                            $url = site_url("user/edit") . "?id={$value["uid"]}&ls=" . urlencode(get_url());
                            echo "<a class='page-action' data-href='{$url}' data-id='userlist_{$value['uid']}' title='编辑用户{$value['username']}'>编辑</a> ";
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </li>
</ul>
<div class="clear"></div>

<span id="a_user"></span>
<script type="text/javascript">
    BUI.use('common/page');

    /**
     * 点击按钮打开新页面
     * @param $ele
     */
    function btnOpenPage($ele) {
        top.topManager.openPage({
            id: $ele.attr("data-id"),
            href: $ele.attr("data-href"),
            title: $ele.attr("title"),
            reload: true
        });
    }

    $(function () {
        //用户列表
        $('#userlist').click(function (e) {
            e.preventDefault();
            if (top.topManager) {
                //打开左侧菜单中配置过的页面
                top.topManager.openPage({
                    moduleId: '224',
                    id: '141',
                    search: 'selcheck=0',//未审核
                    reload: true
                });
            }
        });

        //服务咨询解答
        $('#fwzxjd').click(function (e) {
            e.preventDefault();
            if (top.topManager) {
                //打开左侧菜单中配置过的页面
                top.topManager.openPage({
                    moduleId: '224',
                    id: '234',
                    search: 'search_isread=0',//未回复
                    reload: true
                });
            }
        });

        //对外投资联系
        $('#tzlx').click(function (e) {
            e.preventDefault();
            if (top.topManager) {
                //打开左侧菜单中配置过的页面
                top.topManager.openPage({
                    moduleId: '224',
                    id: '245',
                    search: 'search_status=1',//未审核
                    reload: true
                });
            }
        });


        //站内信
        $('#zn_mail').click(function (e) {
            e.preventDefault();
            if (top.topManager) {
                //打开左侧菜单中配置过的页面
                top.topManager.openPage({
                    moduleId: '224',
                    id: '271',
                    search: 'isport=no',//非excel导出
                    reload: true
                });
            }
        });

        //申报待审核更多
        $('#pro_shenqing').click(function (e) {
            e.preventDefault();
            if (top.topManager) {
                //打开左侧菜单中配置过的页面
                top.topManager.openPage({
                    moduleId: '224',
                    id: '226',
                    search: 'sel_cs=0&isport=',//未审核+非excel导出
                    reload: true
                });
            }
        });

//        var hdxx_count = "<?php //echo $beian['total'];?>//";
//        //信息备案审批
//        $('#xxbasp').click(function (e) {
//            e.preventDefault();
//            if (top.topManager) {
//                //打开左侧菜单中配置过的页面
//                top.topManager.openPage({
//                    moduleId: '224',
//                    id: (hdxx_count > 0 ? '242' : '241'),
//                    search: '',
//                    reload: true
//                });
//            }
//        });
//        //数据上报审核
//        var shuju_shangbao = "<?php //echo $sjsb['total']?>//";
//        $('#sjsbsh').click(function (e) {
//            e.preventDefault();
//            if (top.topManager) {
//                //打开左侧菜单中配置过的页面
//                top.topManager.openPage({
//                    moduleId: '224',
//                    id: '246',
//                    search: (shuju_shangbao > 0 ? '' : "audit_status=10"),
//                    reload: true
//                });
//            }
//        });

    });
    //audit_status
</script>

<script type="text/javascript">
    //优先查看未审,并显示提示条数
    //    var a_user_check = "<?php //echo $user['total'];?>//";
    //    $('#a_user').click(function (e) {
    //        e.preventDefault();
    //        if (top.topManager) {
    //            //打开左侧菜单中配置过的页面
    //            top.topManager.openPage({
    //                moduleId: '224',
    //                id: '141',
    //                search: (a_user_check > 0 ? 'selcheck=-1' : ''),
    //                reload: true
    //            });
    //        }
    //    });
    //
    //    var a_yuanqu_jidi_check = "<?php //echo count($yuanqu_jidi);?>//";
    //    $('#yuanqu_jidi').click(function (e) {
    //        e.preventDefault();
    //        if (top.topManager) {
    //            //打开左侧菜单中配置过的页面
    //            top.topManager.openPage({
    //                moduleId: '224',
    //                id: '227',
    //                search: (a_yuanqu_jidi_check > 0 ? 'audit=10' : ''),
    //                reload: true
    //            });
    //        }
    //    });

    $('#xinxifabu').click(function (e) {
        e.preventDefault();
        if (top.topManager) {
            //打开左侧菜单中配置过的页面
            top.topManager.openPage({
                moduleId: '224',
                id: '232',
                search: '',
                reload: true
            });
        }
    });

    $('#pmguanli').click(function (e) {
        e.preventDefault();
        if (top.topManager) {
            //打开左侧菜单中配置过的页面
            top.topManager.openPage({
                moduleId: '224',
                id: '237',
                search: '',
                reload: true
            });
        }
    });

    $('#adminlist').click(function (e) {
        e.preventDefault();
        if (top.topManager) {
            //打开左侧菜单中配置过的页面
            top.topManager.openPage({
                moduleId: '73',
                id: '59',
                search: '',
                reload: true
            });
        }
    });

    $('#dbbackup').click(function (e) {
        e.preventDefault();
        if (top.topManager) {
            //打开左侧菜单中配置过的页面
            top.topManager.openPage({
                moduleId: '73',
                id: '126',
                search: '',
                reload: true
            });
        }
    });

    $('#open_shenbao_pro').click(function (e) {
        e.preventDefault();
        if (top.topManager) {
            //打开左侧菜单中配置过的页面
            top.topManager.openPage({
                moduleId: '224',
                id: '238',
                search: '',
                reload: true
            });
        }
    });

</script>


</body>
</html>