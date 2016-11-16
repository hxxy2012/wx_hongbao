<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>电子商务综合服务平台</title>
    <meta charset="UTF-8">
   <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/validate/validator.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/layer/layer.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>   
	
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
        .hint{color: red;}

    </style>
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("Swj_mime_project/edit")."?id=".$id."&sbid=".$sbid;?>">返回上一步</a>
</div>
<form action="<?php echo site_url("Swj_mime_project/edit_step");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="sbid" value="<?php echo $sbid;?>">
<input type="hidden" name="tplguid" value="<?php echo $tplguid;?>">
<input type="hidden" name="checkstatus" id="checkstatus" value="">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">综合服务平台名称<span class="hint">*</span></td>
        <td>
            <input type="text" name="title" style="width:333px;" value="<?php if(isset($title))echo $title;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台提供的服务（不得少于3项）<span class="hint">*</span></td>
        <td>
            <?php foreach ($all_ptfw as $key => $value): ?>
                <input type="checkbox" name="fuwu_type[]" style="margin-top:0;" 
                <?php if(isset($fuwu_type)&&stripos($fuwu_type, $value['id'])!==false)echo 'checked';?>
                tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" >&nbsp;<?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <br><br>
            <span id="beizhu_box" style="display:none;margin:0;">
                <input type="text" style="width:333px;"  name="fuwu_type_other" placeholder="其他服务" id="fuwu_type_other" value="<?php if(isset($fuwu_type_other))echo $fuwu_type_other;?>">
            </span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台服务的企业数<span class="hint">*</span></td>
        <td>
            <input type="text" name="qiyeshu"  value="<?php if(isset($qiyeshu))echo $qiyeshu;?>" required/>&nbsp;家
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台的营业收入<span class="hint">*</span></td>
        <td>
            <input type="text" name="shouru"  value="<?php if(isset($shouru))echo $shouru;?>" required/>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请资助的金额<span class="hint">*</span></td>
        <td>
            <input type="text" name="shenqing"  value="<?php if(isset($shenqing))echo $shenqing;?>" required/>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度电子商务营业收入<span class="hint">*</span></td>
        <td>
            <input type="text" name="year_in"  value="<?php if(isset($year_in))echo $year_in;?>" required/>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度电子商务营业收入占比<span class="hint">*</span></td>
        <td>
            <input type="text" name="year_in_bl"  value="<?php if(isset($year_in_bl))echo $year_in_bl;?>" required/>&nbsp;%
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度企业纳税金额<span class="hint">*</span></td>
        <td>
            <input type="text" name="year_out"  value="<?php if(isset($year_out))echo $year_out;?>" required/>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">项目基本情况介绍证明材料<span class="hint">*</span></td>
        <td>
           <div class="fujian_xmqk_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_xmqk)&&is_array($fujian_xmqk)){?>
                     <?php foreach ($fujian_xmqk as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_xmqk' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_xmqk[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_xmqk" id="fujian_xmqk" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_xmqk')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">运营销售额的后台数据截图<span class="hint">*</span></td>
        <td>
           <div class="fujian_yyxse_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_yyxse)&&is_array($fujian_yyxse)){?>
                     <?php foreach ($fujian_yyxse as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_yyxse' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_yyxse[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_yyxse" id="fujian_yyxse" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_yyxse')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">项目建设投入的证明材料<span class="hint">*</span></td>
        <td>
           <div class="fujian_xmtr_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_xmtr)&&is_array($fujian_xmtr)){?>
                     <?php foreach ($fujian_xmtr as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_xmtr' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_xmtr[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_xmtr" id="fujian_xmtr" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_xmtr')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
   
    <tr>
        <td class="tableleft" style="width:250px;">与委托方的代理合同或协议<br>
        <span style="color:#ccc;text-align:left;">(代运营项目需要提交)</span>
        </td>
        <td>
           <div class="fujian_dlht_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_dlht)&&is_array($fujian_dlht)){?>
                     <?php foreach ($fujian_dlht as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_dlht' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_dlht[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_dlht" id="fujian_dlht" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_dlht')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr style="display:none;">
        <td class="tableleft" style="width:250px;">费用收支证明<span class="hint">*</span></td>
        <td>
           <div class="fujian_fysz_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_fysz)&&is_array($fujian_fysz)){?>
                     <?php foreach ($fujian_fysz as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_fysz' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_fysz[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_fysz" id="fujian_fysz" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_fysz')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <?php $this->load->view(__TEMPLET_FOLDER__."/project/review") ?>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <button class="btn btn-primary" type="button" id="btnSave">提交初审</button> &nbsp;&nbsp;
    <!-- <input type="button" class="btn btn-primary" value="临时保存" onclick="javascript:lssave();">&nbsp;&nbsp; -->
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/edit")."?id=".$id."&sbid=".$sbid;?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script>
var beizhu;//备注
var describe;//活动简介
var flag_fuwu_type_other;//判断是否选中了其他产权选项,1为是
$(function () {
        var fuwu_type=document.getElementsByName('fuwu_type[]'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<fuwu_type.length; i++){ 
            // alert(fuwu_type[i].attributes['tname'].nodeValue);
            if(fuwu_type[i].attributes['tname'].nodeValue=='其他'&&fuwu_type[i].checked) {
                $('#beizhu_box').show();
            }
        } 
        // document.getElementById('year').value = new Date().getFullYear();
        //提交表单   
		$('#btnSave').click(function(){
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            var check_num = get_check_num();//获取服务类型选择了的个数
            if (check_num < 3) {
                layer.msg('请至少选择3项服务！！',{time: 1000});
                return false;
            }
            xmqk_length  =  document.getElementsByName('fujian_xmqk[]').length;
            xmtr_length  =  document.getElementsByName('fujian_xmtr[]').length;
            yyxse_length  =  document.getElementsByName('fujian_yyxse[]').length;
            dlht_length  =  document.getElementsByName('fujian_dlht[]').length;
            fysz_length  =  document.getElementsByName('fujian_fysz[]').length;
            if (xmqk_length<=0) {
                layer.msg('请上传项目基本情况介绍证明材料',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (yyxse_length<=0) {
                layer.msg('请先上传运营销售额的后台数据截图！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (xmtr_length<=0) {
                layer.msg('请先上传项目建设投入的证明材料！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
           /* if (dlht_length<=0) {
                layer.msg('请先上传开展服务合同！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (fysz_length<=0) {
                layer.msg('请先上传费用收支证明！！',{time: 1000});
                flag_submit = 1;
                return false;
            } */
            //检查公用附件是否有上传
            if (!chk_fj()) {
                return false;
            }
            $('#checkstatus').val('0');//将状态改为未审核
            $('#myform').submit();
		});
        //监听平台服务点击事件
        $('input[name="fuwu_type[]"]').click(function(){
            var obj = $(this);
            var tname = obj.attr('tname');
            if (tname=='其他'&&obj.is(":checked")) {
                flag_fuwu_type_other = 1;
                $('#beizhu_box').show();
            } else if (tname=='其他'&&!obj.is(":checked")){
                flag_fuwu_type_other = 0;
                $('#beizhu_box').hide();
            }
        });
          //上传资料
     //网站增值电信业务许可证或ICP备案证复印件
    $('#fujian_xmqk').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
               //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('.fujian_xmqk_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_xmqk' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_xmqk[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（10M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
    //平台建设投入证明
    $('#fujian_xmtr').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
               //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('.fujian_xmtr_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_xmtr' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_xmtr[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（10M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
//网站后台流量和交易额截图
    $('#fujian_yyxse').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
               //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('.fujian_yyxse_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_yyxse' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_yyxse[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（10M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
//开展服务合同
    $('#fujian_dlht').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
               //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('.fujian_dlht_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_dlht' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_dlht[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（10M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
//费用收支证明
    $('#fujian_fysz').uploadify({
        'auto'     : false,//关闭自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
        'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择文件',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 1,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//只允许上传图像
        'fileTypeExts' : '*.jpg;*.png;*.gif;*.bmp;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.pdf',//限制允许上传的图片后缀
        'fileSizeLimit' : '10240KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
               //通过ajax获取图片id(data)对应的路径
                $.ajax({ 
                    type: "post", 
                    url: "<?php echo site_url("swj_upload/getUrl");?>", 
                    data:{'id':data},
                    cache:false, 
                    async:false, 
                    success: function(data){ 
                        obj = eval('(' + data + ')');
                        if (obj.code == 0) {
                            $('.fujian_fysz_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_fysz' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_fysz[]' value='"+obj.id+"'></li>");
                            layer.msg('上传成功',
                            {time: 1000}
                           );
                        } else {
                           layer.msg('上传失败,请检查上传文件类型或者刷新重试',
                            {time: 1000}
                           ); 
                        }
                    } 
                });
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
            // alert(ht_arr);
            // if(img_id_upload.length>0)
            // window.location.reload();
        },
        'onError' : function (event, ID, fileObj, errorObj) {
           if (errorObj.type === "File Size"){
            alert('超过文件上传大小限制（10M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
});
 
 //点击临时保存按钮，将checkstatus设置为99代表临时保存
 function lssave() {
    $('#checkstatus').val('99');
    $('#myform').submit();
 }
 //遍历对象获取选择了平台服务的个数
 function get_check_num() {
    var obj = document.getElementsByName("fuwu_type[]");
    var num = 0;
    for(var i=0; i<obj.length; i++) {
        if (obj[i].checked) {
            num++;
        }
    }
    return num;
 }
</script>
