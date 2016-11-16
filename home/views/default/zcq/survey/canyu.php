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
    <script src="/home/views/static/js/validate/validator_plus.js"></script>
    <script src="/home/views/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>
    <style>
        .clear{clear: both;}
        #page_string{float: left;font-size: 14px;}
        #page_string .pagination li{float: left;}
        /*返回按钮*/
        .pc_login form div input{background: #2f80d5;color: #fff;border: 1px solid #2f80d5;}
        .pc_login label{width: 100px;}
        .btn_back:hover{background: #00448c;}
        .question{font-weight: bold;}
        .anawer{margin-left:28px;}
        .dpyn{display: none;}
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
            <div class="pc_login">
            <form action="<?php echo site_url('adminx/zcq_survey/dosurvey');?>" method="post" name="myform" id="myform" class="fix">
                <ul class="fix">
                     <li>
                        <i>*</i><label>联系人：</label>
                        <input type="text" name="linkname" value="<?php echo $userInfo['realname']?>" required
                               valtype="my_require" placeholder="请输入联系人" />
                    </li>
                    <li>
                        <i>*</i><label>手机：</label>
                        <input type="text" name="tel" value="<?php echo $userInfo['tel']?>" required valType='mobile' placeholder="请输入手机" />
                    </li>
                    <li>
                        <i>*</i><label>单位：</label>
                        <input type="text" name="company" value="<?php echo $userInfo['company']?>" required valtype="my_require" placeholder="请输入联系人单位" />
                    </li>
                    <?php foreach ($wenti as $key => $value): ?>
                        <li style="margin-bottom:5px;margin-top:0;">
                            <p class="question"><?php echo $value['title'] . '(' . $value['itemtype_txt'] . ')';?></p>
                            <p class="anawer" wentiid="<?php echo $value['id'];?>" wenti="<?php echo $value['title'];?>" itemtype='<?php echo $value['itemtype']?>'>
                                <!-- 问题id -->
                                <input type="hidden" name="id[]" readonly value="<?php echo $value['id']?>">
                                <!-- 单选题 -->
                                <?php if ($value['itemtype']==1): ?>
                                    <input type="hidden" name="content[]" value="">
                                    <?php $isother=false;?>
                                    <?php foreach ($value['xx'] as $k => $v): ?>
                                    <input type="radio" name="item_id[<?php echo $key?>][]" value="<?php echo $v['id']?>"><?php echo $v['title']?>
                                    <?php if($v['title']=='其他') $isother=true;?>
                                    <?php endforeach ?>
                                    <!-- 为其他时增加其他输入框 -->
                                    <input type="text" style="width:100px;"  class="<?php if ($value['isother']!=1||!$isother) echo 'dpyn'; ?>" name="other[]" value="">
                                <?php endif ?>
                                <!-- 多选题 -->
                                <?php if ($value['itemtype']==2): ?>
                                    <input type="hidden" name="content[]" value="">
                                    <?php $isother=false;?>
                                    <?php foreach ($value['xx'] as $k => $v): ?>
                                    <input type="checkbox" name="item_id[<?php echo $key?>][]" value="<?php echo $v['id']?>"><?php echo $v['title']?>
                                    <?php if($v['title']=='其他') $isother=true;?>
                                    <?php endforeach ?>
                                    <!-- 为其他时增加其他输入框 -->
                                    <input type="text"  style="width:100px;"  class="<?php if ($value['isother']!=1||!$isother) echo 'dpyn'; ?>" name="other[]" value="">
                                <?php endif ?>
                                <!-- 问答题 -->
                                <?php if ($value['itemtype']==3): ?>
                                    <input type="hidden" name="item_id[<?php echo $key?>][]" value="">
                                    <input type="hidden" name="other[]" value="">
                                    <input type="text" name="content[]" id="content<?php echo $value['id']?>" value="">
                                <?php endif ?>
                            </p>
                        </li>
                    <?php endforeach ?>
                    
                </ul>
                <div>
                    <input type="hidden" name="diaocha_id" readonly value="<?php echo $model['id'];?>">
                    <input type="hidden" name="backurl" readonly value="<?php echo $ls;?>">
                    <input type="button" id="btn_submit" value="提交">
                    <input type="button" class="btn_back" style="margin-left:20px;"  onclick="goback();" value="返回" />
                </div>
            </form>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>


<script>
$(function () {
    $('#btn_submit').click(function(){
        if($("#myform").Valid() == false || !$("#myform").Valid()) {
            return false ;
        }
        //遍历问题,提示
        var obj = $('.anawer');
        flag = 0;//判断是否提交
        message = '';
        $('.anawer').each(function(){
            var itemtype = $(this).attr('itemtype');//题目类型
            var wenti = $(this).attr('wenti');//问题名称
            var wentiid = $(this).attr('wentiid');//问题id
            if (itemtype == '1') {
                //单选题
                if (!$(this).children("input[type='radio']").is(':checked')) {
                    message = '请选择问题为'+wenti+'的一个选项';
                    flag = 1;
                    return false;
                }
            } else if (itemtype == '2') {
                //多选题
                if (!$(this).children("input[type='checkbox']").is(':checked')) {
                    message = '请选择问题为'+wenti+'的选项';
                    flag = 1;
                    return false;
                }
            } else {
                //问答题
                if ($(this).children("input[name='content[]']").val()=='') {
                    message = '请输入问题为'+wenti+'的答案';
                    flag = 1;
                    return false;
                }
            }
        });
        //遍历问题结束
        if (flag == 1) {
            layer.msg(message,
                {time: 3000}
               );
            return false;
        } else {
            $('#myform').submit();
        }
    });
    //监听提交结束
});

function goback() {
    window.location.href = "<?php echo $ls;?>";
}

          
</script>