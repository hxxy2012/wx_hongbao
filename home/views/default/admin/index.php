<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"];?></title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="ie-comp">
    <meta name="keywords" content="<?php echo $config["site_keywd"];?>">
    <link rel="stylesheet" href="/home/views/static/zcq/css/style.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/home/views/static/zcq/iconfont/iconfont.css" />
    <!--[if lt IE 8]>
    <script>
        alert('本网站已不支持低于IE8的浏览器,请选用更好版本的IE浏览器或Google Chrome浏览器');
    </script>
    <![endif]-->
    <!--[if IE 8]>
    <style type="text/css">
        #touch_right .tel{ background:#434343;height:50px;line-height:50px;padding:0 8px;}
        #touch_right .er{ background:#656565;height:50px;line-height:50px;padding:0 8px;}
        #touch_right .top {height:50px;line-height:50px;padding:0 8px;}
        #touch_right .hotline{ line-height:170%;}
        #touch_right .floating_ewm{height:190px; top:-142px;line-height:150%;}
        .top{height:50px;line-height:50px;}
    </style>
    <![endif]-->
    <script type="text/javascript" src="/home/views/static/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/home/views/static/zcq/js/min.js"></script>
    <style>
        .pc_list_h3_ul{margin: 0;padding: 0;list-style: none;}
        .pc_list_h3_ul li{float: left;margin-left: 10px;margin-top:10px;width: 48%; }
        .pc_list_h3_ul li table{margin-top: 5px;}
        .pc_list_h3_ul li table th{text-align: center;}
        .fl{float: left;}
        .fr{float: right;}
        .dpb{display: block;margin-top: 2px;}
        .clear{clear:both;}
    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div>
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <!--news list-->
        <div id="" class="pc_member_background_list">
            <span class="pc_list_present">

                当前位置：<a href="<?php echo site_url("admin/index")?>">会员后台首页</a></span>
            <h3 class="pc_list_h3">会员后台首页</h3>
            <ul class="pc_list_h3_ul">
                <?php //企业用户有特殊的为我的申报
                if ($sess["usertype"] == "45063") { ?>
                    <li>
                        <h4 class="fl">我的申报</h4><a class="dpb fr" href="<?php echo site_url("adminx/zcq_pro_shenqing/index")."?ls=".urlencode(get_url());?>">更多</a>
                        <div class="clear"></div>
                        <table cellspacing="0" cellpadding="0" width="100%" class="mytable">
                            <tr>
                                <th>类型</th>
                                <th width="80">状态</th>
                                <th class="membl_manipulate">操作</th>
                            </tr>
                            <tbody>
                                <?php foreach ($list_mimesb['list'] as $key => $value): ?>
                                    <tr>
                                        <td><?php echo $value['typename']?></td>
                                        <td align="center"><?php echo $value['check_status_title']?></td>
                                        <td align="center">
                                            <a href="<?php echo site_url("adminx/zcq_pro_shenqing/edit")."?ls=".urlencode(get_url())."&id=".$value["id"];?>">查看</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </li>
                <?php } ?>
                <li>
                    <h4 class="fl">最新站内信</h4><a class="dpb fr" href="<?php echo site_url("adminx/zcq_mail/index")."?ls=".urlencode(get_url());?>">更多</a>
                    <div class="clear"></div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="mytable">
                        <tr>
                            <th>标题</th>
                            <th width="80">接收日期</th>
                            <th class="membl_manipulate">操作</th>
                        </tr>
                        <tbody>
                        <?php foreach ($list_znx['list'] as $key => $value): ?>
                        <tr>
                            <td><?php echo $value['title'];?></td>
                            <td align="center"><?php echo date('Y-m-d',$value['createtime']);?></td>
                            <td align="center">
                                <a href="<?php echo site_url("adminx/zcq_mail/myview")."?ls=".urlencode(get_url())."&id=".$value["id"]?>">查看</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </li>
                <li>
                    <h4 class="fl">最新服务咨询</h4><a class="dpb fr" href="<?php echo site_url("adminx/zcq_fuwu_zixun/lists")."?ls=".urlencode(get_url())?>">更多</a>
                    <div class="clear"></div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="mytable">
                        <tr>
                            <th>标题</th>
                            <th width="80">回复</th>
                            <th class="membl_manipulate">操作</th>
                        </tr>
                        <tbody>
                        <?php foreach ($list_fwzx['list'] as $key => $value): ?>
                        <tr>
                            <td><?php echo $value['title'];?></td>
                            <td align="center">
                                <?php
                                if ($value["receive_isread"] == "1") {
                                    echo "<span style='color: crimson'>是</span>";
                                } else {
                                    echo "<span style='color: darkorchid'>否</span>";
                                }
                                ?>
                            </td>
                            <td align="center">
                                <a href="<?php echo site_url("adminx/zcq_fuwu_zixun/view")."?ls=".urlencode(get_url())."&id=".$value["id"]?>">查看</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </li>
                <li>
                    <h4 class="fl">最新调查</h4><a class="dpb fr" href="<?php echo site_url("adminx/zcq_survey/index")."?ls=".urlencode(get_url())?>">更多</a>
                    <div class="clear"></div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="mytable">
                        <tr>
                            <th>调查主题</th>
                            <th width="80">完成</th>
                            <th class="membl_manipulate">操作</th>
                        </tr>
                        <tbody>
                        <?php foreach ($list_survey['list'] as $key => $value): ?>
                        <tr>
                            <td><?php echo $value['title'];?></td>
                            <td align="center"><?php echo $value['iscanyu']?'是':'否';?></td>
                            <td align="center">
                                <?php echo $value['iscanyu']?'<a href="'.site_url("adminx/zcq_survey/wt_result_list").'?backurl='.(urlencode(get_url())).'&diaocha_id='.$value['id'].'">查看结果</a>':'<a href="'.site_url("adminx/zcq_survey/dosurvey").'?backurl='.(urlencode(get_url())).'&id='.$value['id'].'">调查</a>'; ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </li>
                <div class="clear"></div>
            </ul>
            <!-- <div class="membl_bot">
                <ul>
                    <li>
                        <a href="" class="membl_check_all ">全选</a>
                        <a href="" class="membl_reverse_selection ">反选</a>
                    </li>
                    <li>
                        <a href="" class="membl_add">增加</a>
                        <a href="" class="membl_del">删除</a>
                    </li>
                </ul>
            </div> -->
        </div>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>