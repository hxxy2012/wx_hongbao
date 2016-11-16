<!doctype html>
<html>
<head>
    <title>会员后台-<?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
    <script type="text/javascript" src="/home/views/static/js/validate/validator_plus.js"></script>
    <style>
        .pc_login label {
            width: 80px;
        }

        .consult_checkbox {
            width: 100px;
        }

        /*选择接收人的*/
        #ul_admins li, #ul_consultants li {
            float: left;
            margin: 1px;
            width: 220px;
        }

        #ul_admins label, #ul_consultants label {
            float: none;
            width: inherit;
        }

        #ul_admins span, #ul_consultants span {
            position: absolute;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: pre;
            width: inherit;
        }

        .my_tooltip {
            width: auto;
            height: 30px;
            font-family: 'Microsoft Yahei', '微软雅黑', serif;
            font-size: 12px;
            position: absolute;
            border: solid #043d44 1px;
            border-radius: 3px;
            background-color: #2f80d5;
            color: #FFFFFF;
            line-height: 30px;
        }
    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div class="pc_login">
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <form action="<?php echo $form_url; ?>" method="post" id="myform" name="myform"
              enctype="multipart/form-data"
              onsubmit="return chkform()" class="fix">

            <span class="pc_list_present">当前位置：<a href="<?php echo $admin_url; ?>">会员后台首页</a>-><a
                    href="<?php echo $cur_url; ?>">我要咨询</a></span>
            <h3 class="pc_list_h3">我要咨询</h3>
            <!--            <table class="way_table" border="0" style="width:809px;">-->
            <!--            </table>-->

            <ul class="fix">
                <li>
                    <i>*</i><label>咨询对象：</label>
                    <input type="button" value="选服务机构" class="mybtn" onclick="openuser(event)"/>
                    <input type="button" value="选管理员" class="mybtn" onclick="openadmin(event)"/>
                    <br>
                    <div>
                        <span>已选服务机构:</span>
                        <ul id="ul_consultants">

                            <?php foreach ($culist as $item): ?>
                                <!--打印常用的会员-->
                                <li data-content="<?php echo "[常咨询机构]已经发送过他<span style='color: #00CC00'>{$item['total']}</span>条咨询"; ?>">
                                    <label>
                                        <input type="checkbox" name="receive_userid[]"
                                               value="<?php echo $item['receive_userid'] ?>">
                                        <span><?php echo $item['username']; ?></span>
                                    </label>
                                </li>
                            <?php endforeach ?>

                        </ul>
                    </div>
                    <div style="clear: both"></div>
                    <div>
                        <span>已选管理员:</span>
                        <ul id="ul_admins">
                            <?php foreach ($calist as $item): ?>
                                <!--打印常用的管理员-->
                                <li data-content="<?php echo "[常咨询管理员]已经发送过他<span style='color: #00CC00'>{$item['total']}</span>条咨询"; ?>">
                                    <label>
                                        <input type="checkbox" name="receive_sysuserid[]"
                                               value="<?php echo $item['receive_sysuserid'] ?>">
                                        <span><?php echo $item['username']; ?></span>
                                    </label>
                                </li>
                            <?php endforeach ?>

                        </ul>
                    </div>
                    <div style="clear: both"></div>
                </li>
                <li>
                    <i>*</i><label>咨询标题：</label>
                    <input type="text" id="zixun_title" name="mysql_title" placeholder="请输入咨询标题" valtype="my_require"
                           value=""
                           required>
                </li>
                <li>
                    <i>*</i><label>联系人：</label>
                    <input type="text" valtype="my_require"
                           value="<?php echo $sess['realname']; ?>">
                </li>

                <li>
                    <i>*</i><label>联系手机：</label>
                    <input type="text" valtype="mobile"
                           value="<?php echo $sess['tel']; ?>">
                </li>

                <li>
                    <i>*</i><label for="content">咨询内容：</label>
                    <textarea name="mysql_content" id="content"></textarea>
                </li>
            </ul>
            <div>
                <input type="button" value="临时保存" id="temp_save"/>
                <input type="submit" value="提交"/>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>



<!--使用cookise-->
<script type="text/javascript" src="/home/views/static/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/home/views/static/js/layer/layer.js?v=2.1"></script>
<!--kindeditor-->
<script type="text/javascript"
        src="/home/views/static/js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/home/views/static/js/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
    KindEditor.ready(function (K) {
        window.editor = K.create('#content', {
            width: '90%',
            height: '400px',
            allowFileManager: false,
            allowUpload: false,
            afterCreate: function () {
                this.sync();
            },
            afterBlur: function () {
                this.sync();
            },
            extraFileUploadParams: {
                'cookie': '<?php echo isset($_COOKIE['admin_auth']) ? $_COOKIE['admin_auth'] : "";?>'
            },
            uploadJson: "<?php echo site_url($controller . "/upload") . "?action=upload&session_id=" . $sess["session_id"];?>"
        });
    });
</script>
<script type="text/javascript">

    /**
     * 增加checkbox
     * input_field_name 字段名字
     */
    function create_checkbox(item_obj, input_field_name) {
        //创建新元素
        var html = [
            '<li>',
            '<label ><input type="checkbox" name="' + input_field_name + '" value="' + item_obj.id + '"/>',
            '<span>' + item_obj.type + item_obj.name + '</span>',
            '</label>',
            '</li>'
        ].join("\n");
        var $ele = $(html);
        $ele.find("input").attr("checked", true);
        return $ele;
    }

    /**
     * 添加管理员
     */
    function handle_list(json_list, type) {
        var $ul, input_field_name;
        if (type == "admin") {
            $ul = $('#ul_admins');
            input_field_name = "receive_sysuserid[]";
        } else if (type == "user") {
            $ul = $('#ul_consultants');
            input_field_name = "receive_userid[]";
        }
        //list
        var list_arr = $.parseJSON(json_list);
        //已经添加了的checkbox
        var $inputs = $ul.find("input[type=checkbox]");

        //
        var repeat_list = [];

        //遍历list
        $.each(list_arr, function (n, item_obj) {

            var repeat = false;
            //检查是否有重复的
            $inputs.each(function () {
                var $this = $(this);
                if ($this.val() == item_obj.id) {
                    repeat = true;
                }
            });

            if (!repeat) {
                //不重复
                var $ele = create_checkbox(item_obj, input_field_name);
                $ul.append($ele);
            } else {
                //重复
                repeat_list.push(item_obj.name);
            }

        });

        //显示信息
        if (repeat_list.length > 0) {
            var str = "";
            for (var i = 0; i < repeat_list.length; i++) {
                str += repeat_list[i] + ", ";
            }
            layer.msg(str + "已经添加过了！", {time: 5000});
        }

        //也要隐藏validate插件带来的错误显示
        $("div.succ,div.error").slideUp(function () {
            //回调
            //validate插件重新显示结果
            var $myform = $('#myform');
            $myform.showResult();
        });

    }

    function add_admin(json_list) {
        handle_list(json_list, "admin");
    }

    /**
     * 添加用户
     */
    function add_consultants(json_list) {
        handle_list(json_list, "user");
//        //显示的ul
//        var $ul = $('#ul_consultants');
//        //list
//        var list_arr = $.parseJSON(json_list);
//        //已经添加了的checkbox
//        var $inputs = $ul.find("input");
//
//        //
//        var repeat_list = [];
//
//        //遍历list
//        $.each(list_arr, function (n, item_obj) {
//
//            var repeat = false;
//            //检查是否有重复的
//            $inputs.each(function () {
//                var $this = $(this);
//                if ($this.val() == item_obj.id) {
//                    repeat = true;
//                }
//            });
//
//            if (!repeat) {
//                //不重复
//                $ul.append(create_checkbox(item_obj));
//            } else {
//                //重复
//                repeat_list.push(item_obj.name);
//            }
//
//        });
//
//        //显示信息
//        if (repeat_list.length > 0) {
//            var str = "";
//            for (var i = 0; i < repeat_list.length; i++) {
//                str += repeat_list[i] + ", ";
//            }
//            layer.msg(str + "已经添加", {time: 5000});
//        }
    }


    /**
     *获取鼠标位置
     */
    function getMousePos(ev) {
        if (!ev) {
            ev = this.getEvent();
        }
        if (ev.pageX || ev.pageY) {
            return {
                x: ev.pageX,
                y: ev.pageY
            };
        }

        if (document.documentElement && document.documentElement.scrollTop) {
            return {
                x: ev.clientX + document.documentElement.scrollLeft - document.documentElement.clientLeft,
                y: ev.clientY + document.documentElement.scrollTop - document.documentElement.clientTop
            };
        }
        else if (document.body) {
            return {
                x: ev.clientX + document.body.scrollLeft - document.body.clientLeft,
                y: ev.clientY + document.body.scrollTop - document.body.clientTop
            };
        }
    }

    //打开的窗口
    var openbox_user = null;
    var openbox_admin = null;

    function openwindow(event, title, url) {
        var offset = getMousePos(event);
        //触发事件的按钮
        var $btn = $(event.target);
        // offset: ['', offset.x + "px"],//top、left
        openbox = layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '50%'],
            content: url,
            offset: ['', "30%"],//top、left
            success: function (layero, index) {
                //创建完毕调用
                console.log(layero, index);
                //自适应高度
                layer.iframeAuto(index);

                //iframe的body
                var body = layer.getChildFrame('body', index);
                //高度
                var height = $(body).height() + 30;

            }
        });
        return openbox;
    }
    /**
     * 选择会员用户
     */
    function openuser(event) {
        var url = '<?php echo site_url($controller . "/seluser");?>';
        openbox_user = openwindow(event, '选择服务机构', url);
    }
    /**
     * 选择管理员
     */
    function openadmin(event) {
        var url = '<?php echo site_url($controller . "/seladmin");?>';
        openbox_admin = openwindow(event, '选择管理员接收人', url);
    }

    /**
     * 关闭选择窗口
     */
    function closeuser() {
        layer.close(openbox_user);
    }
    function closeadmin() {
        layer.close(openbox_admin);
    }

    /**
     * 显示message
     */
    $(function () {
        <?php if (isset($message)) {
        echo "layer.msg('{$message}', {time: 3000})";
    }
        ?>
    });

    /**
     * 检查表单
     */
    function chkform() {

        var $textarea = $('#content');
        if ($textarea.val() == "") {
            layer.msg("咨询内容不能为空！！！");
            return false;
        }

        //checkbox input元素的合集
        var $inputs_checkbox = $("input[type=checkbox]");
        //是否选中了一个checkbox
        var onecheck = false;
        $inputs_checkbox.each(function () {
            if ($(this).attr("checked")) {
                onecheck = true;
            }
        });
        if (!onecheck) {
            layer.msg("请选择一个咨询接收人！！！");
            return false;
        }
        if (!$("#myform").Valid()) {
            return false;
        }
        //验证成功，删除cookie,bylk2016年9月9日09:02:13
        var arr = ['zixun_list_user','zixun_list_admin','zixun_title','zixun_text','zixun_flag'];
        clearcookie(arr);
        return true;
    }

    //删除cookie，arr为cookie的名称数组
    function clearcookie(arr) {
        for (var i = arr.length - 1; i >= 0; i--) {
            // setCookie(arr[i], "", -1); 
            $.cookie(arr[i], '', {expires: -1});
        };
    }
    $(document).ready(function () {
        //临时保存
        var $btn_temp_save = $('#temp_save');
        var $ul_consultants = $('#ul_consultants');
        var $ul_admins = $('#ul_admins');

        var $zixun_title = $("#zixun_title");
        var $textarea = $('#content');

        /**
         * 提取obj
         * @param $ele
         * @returns {{}}
         */
        function getObjs($ele) {
            var $checkbox = $ele.find("input[type=checkbox]");
            var $span = $ele.find("span");
            var obj = {};
            obj.id = $checkbox.val();
            obj.type = "";
            obj.name = $span.text();
            return obj;
        }

        /**
         * 临时保存资料
         */
        function savedata() {
            //会员
            var arr = [];
            $ul_consultants.find("li").each(function () {
                //li
                var $this = $(this);
                var obj = getObjs($this);
                arr.push(obj);
            });
            //7天过期的json
            $.cookie('zixun_list_user', JSON.stringify(arr), {expires: 7});

            //管理员
            arr = [];
            $ul_admins.find("li").each(function () {
                //li
                var $this = $(this);
                var obj = getObjs($this);
                arr.push(obj);
            });
            //
            $.cookie('zixun_list_admin', JSON.stringify(arr), {expires: 7});

            //咨询内容
            //咨询标题
            $.cookie('zixun_title', $zixun_title.val(), {expires: 7});
            var content = $.cookie('zixun_text', $textarea.val(), {expires: 7});
            console.log(content);
            $.cookie('zixun_flag', '1', {expires: 7});//标识是否保存cookie
            layer.msg("临时数据保存成功！");
        }

        /**
         * 读取临时资料
         */
        function readdata() {

            //用户
            var str_list_user = $.cookie('zixun_list_user');
            //console.log($.parseJSON(str_list_user));

            add_consultants(str_list_user);
            //console.log($.parseJSON(str_list_user));

            //管理员
            var str_list_admin = $.cookie('zixun_list_admin');
            add_admin(str_list_admin);

            $zixun_title.val($.cookie('zixun_title'));
            //console.log($.cookie('zixun_text'));
            window.editor.html($.cookie('zixun_text'));
            //出现验证咨询内容不为空的提示bug，先让他获得焦点解决bylk2016年9月8日17:56:22
            window.editor.focus();
        }

        /**
         * 清除临时数据
         */
        function cleardata() {
            return $.removeCookie("zixun_list_user") && $.removeCookie("zixun_list_admin")
                && $.removeCookie("zixun_title") && $.removeCookie("zixun_text");
        }

        //点击事件
        $btn_temp_save.click(function () {
            savedata();
        });

        var have_temp = $.cookie("zixun_text");
        if (typeof (have_temp) != "undefined"&&$.cookie("zixun_flag")=='1') {
            //询问框
            layer.confirm('检测到有临时保存的数据，是否读取？', {
                btn: ['读取', '不读取'] //按钮
            }, function () {
                //读取数据
                readdata();
                layer.msg("已读取临时数据");
            }, function () {
                //不读取数据
                layer.confirm('是否清楚临时保存的数据', {btn: ['保留', '清除']},
                    function () {
                        //保留
                        layer.msg("已保留临时数据");
                    }, function () {
                        //清除
                        if (cleardata()) {
                            layer.msg("已清除临时数据");
                        } else {
                            layer.msg("临时数据清除失败！");
                        }
                    });
            });
        }
    });

    $(document).ready(function () {

        var $li = $("#ul_admins li,#ul_consultants li");

        //tooltip插件
        var $tooltip = null;
        $li.mouseover(function (e) {
            var $this = $(this);

            //创建tooltip
            var html = "<div id='tooltip' class='my_tooltip'>" + $this.attr("data-content") + "</div>";

            $tooltip = $(html).appendTo($('body'));
            $tooltip.css({
                "top": (e.pageY + 10) + "px",
                "left": ( e.pageX + 10) + "px"
            });

        });

        $li.mouseout(function () {
            $tooltip.remove();
        });

        $li.mousemove(function (e) {
            $tooltip.css({
                "top": (e.pageY + 10) + "px",
                "left": (e.pageX + 10) + "px"
            });
        });

    });

</script>
</body>
</html>