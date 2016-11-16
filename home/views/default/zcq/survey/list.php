<!doctype html>
<html>
<head>
    <title>调查问卷-<?php echo $config["site_fullname"];?></title>
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
    <script type="text/javascript" src="/home//views/static/js/layer/layer.js"></script>
    <style>
        .clear{clear: both;}

        .btn{width: 60px;font-size: 16px;border-radius: 5px;-webkit-border-radius: 5px;
    -moz-border-radius: 5px;line-height: 25px;text-align: center;margin-left: 5px;
    border: 1px solid #d5d5d5;color:#000;background: #fff;cursor: pointer;}
        .btn:hover{color:#2f80d5;}
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

                当前位置：<a href="<?php echo site_url("admin/index")?>">会员后台首页</a>-><a href="<?php echo site_url("adminx/zcq_survey/index")?>">调查问卷</a></span>
            <h3 class="pc_list_h3">调查问卷</h3>
            <div class="search" >
                <form method="get" >
                关键字：
                    <input type="text"  name="search_title" id="search_title"
                    class="abc input-default" 
                    placeholder="标题" 
                    value="<?php echo $search_val['title'];?>"
                    style="width:300px;height:28px"
                    />
                    <button type="submit" class="btn">查询</button>&nbsp;&nbsp; 
                </form>    
            </div>
            <?php if (is_array($list)&&count($list)>0): ?>
            <table cellspacing="0" cellpadding="0" style="width:100%;" class="mytable">
                <tr>
                    <th width='40px;'>编号</th>
                    <th>标题</th>
                    <th width='50px;'>已参与</th>
                    <th width='100px;'>发起日期</th>
                    <th class="membl_manipulate"></th>
                </tr>
                <tbody id="result_">
                    <?php
           
                    foreach($list as $v){
                        
                        echo "<tr onclick='seltr($(this))'>";
                        echo "<td style='text-align:center;'>".$v["id"]."</td>";
                        echo "<td>".$v["title"]."</td>";
                        echo "<td style='text-align:center;'>".($v["iscanyu"]?"是":"否")."</td>";
                        echo "<td style='text-align:center;'>".date("Y-m-d",$v["create_time"])."</td>";
                        echo "<td style='text-align:center;'>";
                        echo $v['iscanyu']?'<a href="'.site_url("adminx/zcq_survey/wt_result_list").'?backurl='.(urlencode(get_url())).'&diaocha_id='.$v['id'].'">查看结果</a>':'<a href="'.site_url("adminx/zcq_survey/dosurvey").'?backurl='.(urlencode(get_url())).'&id='.$v['id'].'">调查</a>';
                        echo "</td>";                                                           
                        echo "</tr>";
                        echo "\n";
                    }
                    ?> 
                </tbody>
            </table>
            <?php else:?>
            <p style="margin-top:10px;color:#ccc;">暂无问卷</p>
            <?php endif ?>
            <div class="membl_bot">
                <!-- <ul>
                    <li>
                        <input type="hidden" name="selid" id="selid" readonly value=""/> 
                        <a href="javascript:void(0);" onclick="selall()" class="membl_check_all ">全选</a>
                        <a href="javascript:void(0);" onclick="selall2()" class="membl_reverse_selection ">反选</a>
                    </li>
                    <li>
                        <a href="" class="membl_add">增加</a>
                        <a href="javascript:void(0);" onclick="godel()"  class="membl_del">删除</a>
                    </li>
                </ul> -->
                <input type="hidden" name="selid" id="selid" readonly value=""/>
                <div id="page_string" style="float:left ; text-align:left ; margin:-4px">
                <?php echo $pager;?>  
                </div>
            </div>
        </div>
    </div>
</div>

<div id="touch_right">
    <ul>
        <li class="tel"><i class="iconfont">&#xe60c;</i>
            <div class="floating_left hotline">咨询热线：<br> (86-760)89892378</div>
        </li>
        <li class="er"><i class="iconfont">&#xe60d;</i>
            <div class="floating_left floating_ewm">
                <img src="/home/views/static/zcq/images/two-dimension-code.jpg">扫一扫二维码<br />关注官方微信
            </div>
        </li>
        <li onClick="gotoTop();return false;" class="top"><i class="iconfont">&#xe622;</i>
            <div class="floating_left">返回頁首</div>
        </li>
    </ul>
</div>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>

<script src="/home/views/static/zcq/js/selall.js"></script>

<script>
$(function () {
    
});


function godel(){
    var ids = $("#selid").val();
    /*if(!chkdel()){
        return false;
    }*/
    if(ids==""){   
         layer.msg('没有选中，请点击某行信息。',
                    {time: 3000}
                   );
    } else {                        
        var ajax_url = "<?php echo site_url("adminx/zcq_survey/del");?>?idlist="+$("#selid").val();
        //var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
        var url = "<?php echo base_url();?>gl.php/zcq_survey/index";
        /*parent.parent.my_confirm(
                "确认删除选中问卷？",
                ajax_url,
                url);*/
        BUI.Message.Confirm("确认删除选中问卷？",function(){
            $.ajax({
                type: "get",
                url: ajax_url,
                data: '',
                cache: false,
                dataType: "text",
                async:false,
                success: function(data) {
                   window.location.reload();
                },
                beforeSend: function() {
                    //$("#result_").html('<font color="red"><img src="http://www.xdqywz.com:8086//admin_application//views/static/Images/progressbar_microsoft.gif"></font>');
                },
                error: function(a,b,c,d) {
                    //alert(c);
                    alert('服务器繁忙请稍。。。。');
                }

            });
        }
    ,'question');
    }   
}
          
</script>