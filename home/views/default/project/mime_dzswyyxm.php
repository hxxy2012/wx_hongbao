<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>电子商务应用项目</title>
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
        <td class="tableleft" style="width:195px;">电子商务应用项目类型<span class="hint">*</span></td>
        <td>
            <?php foreach ($all_yyxmlx as $key => $value): ?>
                <input type="radio" name="yingyong" style="margin-top:0;" 
                <?php if(isset($yingyong)&&$yingyong==$value['id'])echo 'checked';?>
                tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" ><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <!-- <br><br>
            <span id="beizhu_box" style="display:none;margin:0;">
                <input type="text" style="width:333px;"  name="chanquan_other" placeholder="平台名称" id="chanquan_other" value="<?php if(isset($chanquan_other))echo $chanquan_other;?>">
            </span> -->
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请资助的金额<span class="hint">*</span></td>
        <td>
            <input type="text" name="shenqing" value="<?php if(isset($shenqing))echo $shenqing;?>" required/>&nbsp;元
        </td>
    </tr>
    <tr>
        <td class="tableleft">年网上销售额<span class="hint">*</span></td>
        <td>
            <input type="text" name="xiaoshou"  value="<?php if(isset($xiaoshou))echo $xiaoshou;?>" required/>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">网店网址<span class="hint">*</span></td>
        <td>
            <input type="text" name="url"  value="<?php if(isset($url))echo $url;?>" required/>
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
        <td class="tableleft">代运营企业数量</td>
        <td>
            <input type="text" name="qiyeshu"  value="<?php if(isset($qiyeshu))echo $qiyeshu;?>"/>&nbsp;家&nbsp;(代运营企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">线下体验店地址</td>
        <td>
            <input type="text" name="o2o_addr"  value="<?php if(isset($o2o_addr))echo $o2o_addr;?>"/>&nbsp;(O2O企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">网店是否有移动支付功能</td>
        <td>
            <input type="radio" name="o2o_ispay" <?php if(isset($o2o_ispay)&&$o2o_ispay=='0')echo 'checked';?> value="0">无&nbsp;&nbsp;
            <input type="radio" name="o2o_ispay" <?php if(isset($o2o_ispay)&&$o2o_ispay=='1')echo 'checked';?> value="1">有&nbsp;&nbsp;&nbsp;(O2O企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">网上销售额占年销售总额的比例</td>
        <td>
            <input type="text" name="o2o_bili"  value="<?php if(isset($o2o_bili))echo $o2o_bili;?>"/>&nbsp;%&nbsp;(O2O企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">电子商务新技术应用项目的基本情况</td>
        <td>
            <input type="text" name="content"  value="<?php if(isset($content))echo $content;?>"/>&nbsp;(电子商务新技术应用项目填写)
        </td>
    </tr>
     <tr>
        <td class="tableleft" style="width:250px;">网站后台销售数据或第三方平台出具销售额证明<span class="hint">*</span></td>
        <td>
           <div class="fujian_xszm_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_xszm)&&is_array($fujian_xszm)){?>
                     <?php foreach ($fujian_xszm as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_xszm' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_xszm[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_xszm" id="fujian_xszm" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_xszm')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
     <tr>
        <td class="tableleft" style="width:250px;">与委托方签订代理合同或协议<span class="hint">*</span></td>
        <td>
           <div class="fujian_htxy_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_htxy)&&is_array($fujian_htxy)){?>
                     <?php foreach ($fujian_htxy as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_htxy' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_htxy[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_htxy" id="fujian_htxy" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_htxy')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
     <tr>
        <td class="tableleft" style="width:250px;">项目投入证明<span class="hint">*</span></td>
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
        <td class="tableleft" style="width:250px;">市场覆盖证明<span class="hint">*</span></td>
        <td>
           <div class="fujian_scfg_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_scfg)&&is_array($fujian_scfg)){?>
                     <?php foreach ($fujian_scfg as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_scfg' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_scfg[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_scfg" id="fujian_scfg" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_scfg')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">业务增长证明<span class="hint">*</span></td>
        <td>
           <div class="fujian_ywzz_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_ywzz)&&is_array($fujian_ywzz)){?>
                     <?php foreach ($fujian_ywzz as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid='fujian_ywzz' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_ywzz[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_ywzz" id="fujian_ywzz" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_ywzz')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <?php $this->load->view(__TEMPLET_FOLDER__."/project/review") ?>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="button" class="btn btn-primary" id="btnSave" value="提交初审"> &nbsp;&nbsp;
    <!-- <input type="button" class="btn btn-primary" value="临时保存" onclick="javascript:lssave();">&nbsp;&nbsp; -->
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/edit")."?id=".$id."&sbid=".$sbid;?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script>
var beizhu;//备注
var describe;//活动简介
var flag_chanquan_other;//判断是否选中了其他产权选项,1为是
$(function () {
        /*var chanquan=document.getElementsByName('chanquan'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<chanquan.length; i++){ 
            // alert(chanquan[i].attributes['tname'].nodeValue);
            if(chanquan[i].attributes['tname'].nodeValue=='与国内其他电商平台共建'&&chanquan[i].checked) {
                $('#beizhu_box').show();
            }
        } */
        // document.getElementById('year').value = new Date().getFullYear();
        //提交表单   
		$('#btnSave').click(function(){
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            if (!$('input:radio[name="yingyong"]').is(":checked")) {
                layer.msg('请选择电子商务应用项目类型！！',{time: 1000});
                return false;
            }
            xszm_length  =  document.getElementsByName('fujian_xszm[]').length;
            htxy_length  =  document.getElementsByName('fujian_htxy[]').length;
            xmtr_length  =  document.getElementsByName('fujian_xmtr[]').length;
            scfg_length  =  document.getElementsByName('fujian_scfg[]').length;
            ywzz_length  =  document.getElementsByName('fujian_ywzz[]').length;
            if (xszm_length<=0) {
                layer.msg('请先上传第三方平台出具销售额证明！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (htxy_length<=0) {
                layer.msg('请先上传与委托方签订代理合同或协议！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (xmtr_length<=0) {
                layer.msg('请先上传项目投入证明！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (scfg_length<=0) {
                layer.msg('请先上传市场覆盖证明！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (ywzz_length<=0) {
                layer.msg('请先上传业务增长证明！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            //检查公用附件是否有上传
            if (!chk_fj()) {
                return false;
            }
            $('#checkstatus').val('0');//将状态改为未审核
            $('#myform').submit();
		});
        /*//监听园区产权情况点击事件
        $('input[name="chanquan"]').click(function(){
            var obj = $(this);
            var tname = obj.attr('tname');
            if (tname=='与国内其他电商平台共建') {
                flag_chanquan_other = 1;
                $('#beizhu_box').show();
            } else{
                flag_chanquan_other = 0;
                $('#beizhu_box').hide();
            }
        });*/
       //上传资料
      //第三方平台出具销售额证明
    $('#fujian_xszm').uploadify({
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
                            $('.fujian_xszm_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_xszm' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_xszm[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
//与委托方签订代理合同或协议
    $('#fujian_htxy').uploadify({
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
                            $('.fujian_htxy_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_htxy' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_htxy[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
//项目投入证明
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
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
//市场覆盖证明
    $('#fujian_scfg').uploadify({
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
                            $('.fujian_scfg_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_scfg' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_scfg[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });
//业务增长证明
    $('#fujian_ywzz').uploadify({
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
                            $('.fujian_ywzz_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_ywzz' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_ywzz[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（2M）！');
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
</script>
