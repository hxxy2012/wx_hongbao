<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>添加项目</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css" />   
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/validate/validator.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/DatePicker/WdatePicker.js"></script>    
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


        </style>
    </head>
    <body>

        <div class="form-inline definewidth m20" >
            <a  class="btn btn-primary" id="addnew" href="<?php echo $url; ?>">任务列表</a>
        </div>
        <form action="<?php echo site_url("mokuai/edit"); ?>" method="post" enctype="multipart/form-data" class="definewidth m2"  name="myform" id="myform" >
            <input type="hidden" name="action" value="doedit">
            <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
            <table class="table table-bordered table-hover m10">
                <?php
//print_r($info['need_test']);exit;
                ?>
                <tr>
                    <td class="tableleft">任务名称</td>
                    <td><input type="text" style="width:300px;" name="title" id="title" required errMsg="请输入任务名称" tip="请输入任务名称" value="<?php echo $info["title"]; ?>"/>
                        <!--<span><input type="checkbox" name="need_test" id="need_test" value="1" <?php echo ($info["need_test"] == 1) ? "checked" : ''; ?>  /> <label style="display:inline;" for="need_test">需要测试</label>  </span>-->
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">附件</td>
                    <td>     
                        <input type="file" name="fujian" id="fujian" />
                        <span style="color:#666666">格式：doc|docx|wps|ppt</span>
                        <?php
                        if ($info["fujian"] != "") {
                            echo "&nbsp;<a href='/" . $info["fujian"] . "' target='_blank'>查看附件</a>";
                            echo "&nbsp;<input type='checkbox' name='delfujian' id='delfujian' value='yes' />&nbsp;删除附件";
                        }
                        ?>        
                    </td>
                </tr> 
                <tr>
                    <td class="tableleft">计划时段</td>
                    <td>
                        <input type="text" name="jihua_start_time" id="jihua_start_time" class="Wdate" placeholder="" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', isShowClear: true, readOnly: true})"  style="width:160px" readonly
                               required errMsg="请选择开始时间" tip="请选择开始时间"
                               value="<?php echo date("Y-m-d H:i:s", $info["jihua_start_time"]); ?>"
                               >
                        至
                        <input type="text" name="jihua_end_time" id="jihua_end_time" class="Wdate" placeholder=""  onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', isShowClear: true, readOnly: true})"  style="width:160px" readonly
                               required errMsg="请选择结束时间" tip="请选择结束时间"
                               value="<?php echo date("Y-m-d H:i:s", $info["jihua_end_time"]); ?>"
                               >
　计划时数
                        <input name="jihua_hours" type="text" required id="jihua_hours" style="width:80px;" maxlength="8" errMsg="请输入计划时数" tip="请输入计划时数"
                               valType="number" value="<?php echo $info["jihua_hours"]; ?>"
                               />        
                        <span style="color:#CCC">
                            可以用小数
                        </span>
                    </td>
                </tr> 	
                <tr>
                    <td class="tableleft">开发者</td>
                    <td>
                        <?php
                        if (is_super_admin()) {
                            foreach ($mokuai_user_list as $v) {
                                ?>      

                                <input type="radio"  name="mokuai_userid" value="<?php echo $v["id"]; ?>"
                                <?php
                                if ($v["id"] == $info["mokuai_userid"]) {
                                    echo "checked";
                                }
                                ?>    
                                       />
                                       <?php
                                       echo $v["username"];
                                       echo "　";
                                       ?>

                                <?php
                            }
                            ?>
                            <?php
                        } else {
//只能安排给自己	
                            echo '<input type="radio" name="mokuai_userid" value="' . admin_id() . '" checked />' . login_name();
                        }
                        ?>
　所属项目
                        <select name="pid" id="pid">
                            <option value="0" selected>--选择项目--</option>
                            <?php
                            foreach ($pro_list as $v) {
                                echo '<option value="' . $v["id"] . '"';
                                echo ($v["id"] == $info["pid"] ? " selected " : "") . '>' . $v["title"] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>




                <tr>
                    <td class="tableleft">测试者</td>
                    <td>
                        <?php
                        foreach ($testers as $v) {
                            ?>      
                            <input type="radio" <?php if ($info["mokuai_test_userid"] == $v["id"]) echo 'checked'; ?>  name="mokuai_test_userid" value="<?php echo $v["id"]; ?>,<?php echo $v["username"] ?>" />
                            <?php
                            echo $v["username"];
                            echo "　";
                            ?>

                            <?php
                        }
                        ?>
                             <input type="radio"  name="mokuai_test_userid" value="0" />无
                    </td>
                </tr>




                <?php
//只有管理员才能修改
                if (is_super_admin()) {
                    ?>    
                    <tr>
                        <td class="tableleft">任务状态</td>
                        <td>



                            <select name="mokuai_status" id="mokuai_status">
                                <option value="0" selected>--选择状态--</option>
                                <?php
                                $i = 0;
                                foreach ($mokuai_status_list as $v) {

                                    //echo '<label>';
                                    //echo '<input type="radio" name="mokuai_status" value="'.$v["id"].'"';
                                    //echo $v["id"]==$info["mokuai_status"]?"checked":"";
                                    //echo ' />'.$v["title"];
                                    //echo "　";
                                    //原来的
//	if($v["id"]==$info["mokuai_status"]){
//		echo $v["title"];
//		echo "<input type='hidden' name='mokuai_status' id='mokuai_status' value='".$v["id"]."' />";	
//	}

                                    echo '<option value="' . $v["id"] . '"';
                                    echo ($v["id"] == $info["mokuai_status"] ? " selected " : "") . '>' . $v["title"] . '</option>';

                                    //echo "</label>";
                                    $i++;
                                }
                                ?>    
                            </select>   



                        </td>
                    </tr>
                    <?php
                }
                ?>    
                <tr>
                    <td class="tableleft">紧急程度</td>
                    <td>
                        <?php
                        $i = 0;
                        foreach ($mokuai_jinji_list as $v) {

                            //echo '<label>';
                            echo '<input type="radio" name="jinji" value="' . $v["id"] . '"';
                            echo $v["id"] == $info["jinji"] ? "checked" : "";
                            echo ' />' . $v["title"];
                            echo "　";
                            //echo "</label>";
                            $i++;
                        }
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="tableleft">属性</td>
                    <td>
                        <?php
                        $i = 0;
                        foreach ($mokuai_flag_list as $v) {

                            //echo '<label>';
                            echo '<input type="radio" name="mokuai_flag" value="' . $v["id"] . '"';
                            echo $v["id"] == $info["mokuai_flag"] ? "checked" : "";
                            echo ' />' . $v["title"];
                            echo "　";
                            //echo "</label>";
                            $i++;
                        }
                        ?>      
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">描述</td>
                    <td>
                        <textarea style="width:100%; height:150px" id="content" name="content" placeholder="描述"><?php echo $info["content"]; ?></textarea>        
                    </td>
                </tr>
                <tr>
                    <td class="tableleft"></td>
                    <td>
                        <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
                            $(function() {
                                $("#btnSave").click(function() {
                                    if ($("#myform").Valid() == false || !$("#myform").Valid()) {
                                        return false;
                                    }
                                    else {
                                        //判断radio
                                        var mkuserid = document.getElementsByName("mokuai_userid");
                                        var ischecked = false;
                                        for (i = 0; i < mkuserid.length; i++)
                                        {
                                            if (mkuserid[i].checked) {
                                                ischecked = true;
                                                break;
                                            }
                                        }
                                        if (!ischecked) {
                                            BUI.Message.Alert("请选择开发者", 'error');
                                            return false;
                                        }

                                        //验证需要测试时，有没有选测试者
                                        if ($('#need_test').attr('checked') == 'checked') {
                                            //判断radio
                                            var testids = document.getElementsByName("mokuai_test_userid");
                                            var ischecked = false;
                                            for (i = 0; i < testids.length; i++)
                                            {
                                                if (testids[i].checked) {
                                                    ischecked = true;
                                                    break;
                                                }
                                            }
                                            if (!ischecked) {
                                                BUI.Message.Alert("请选择测试者", 'error');
                                                return false;
                                            }
                                        }


                                        //判断所属项目
                                        var pid_id = $("#pid").val();

                                        if (pid_id <= 0) {
                                            BUI.Message.Alert("请选择所属项目", 'error');
                                            return false;
                                        }
                                    }
                                });
                            });


                            KindEditor.ready(function(K) {
                                window.editor = K.create('#content', {
                                    width: '100%',
                                    height: '400px',
                                    allowFileManager: false,
                                    allowUpload: false,
                                    afterCreate: function() {
                                        this.sync();
                                    },
                                    afterBlur: function() {
                                        this.sync();
                                    },
                                    extraFileUploadParams: {
                                        'cookie': '<?php echo $_COOKIE['admin_auth']; ?>'
                                    },
                                    uploadJson: "<?php echo site_url("pro/index"); ?>?action=upload&session=<?php echo session_id(); ?>"

                                            });
                                        });

</script>
<!-- script start-->
<script type="text/javascript">
    var Calendar = BUI.Calendar
    var datepicker = new Calendar.DatePicker({
        trigger: '#expire',
        showTime: true,
        autoRender: true
    });
</script>
<!-- script end -->
