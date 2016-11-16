<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>电子商务园区申报</title>
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
        <td class="tableleft">园区名称<span class="hint">*</span></td>
        <td>
            <input type="text" name="yuanqu" style="width:333px;" value="<?php if(isset($yuanqu))echo $yuanqu;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">运营企业对园区的实际投入<span class="hint">*</span></td>
        <td>
            <input type="text" name="touru"  value="<?php if(isset($touru))echo $touru;?>" required/>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请资助的金额<span class="hint">*</span></td>
        <td>
            <input type="text" name="zizhu"  value="<?php if(isset($zizhu))echo $zizhu;?>" required/>&nbsp;元
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区产权情况<span class="hint">*</span></td>
        <td>
            <?php foreach ($all_yqqk as $key => $value): ?>
                <input type="radio" name="chanquan_id" style="margin-top:0;" 
                <?php if(isset($chanquan_id)&&$chanquan_id==$value['id']) echo 'checked';?>
                tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" ><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <br><br>
            <span id="beizhu_box" style="display:none;margin:0;">
                备注：<input type="text" name="chanquan_beizhu" placeholder="其他产权备注" id="chanquan_beizhu" value="<?php if(isset($chanquan_beizhu))echo $chanquan_beizhu;?>">&nbsp;&nbsp;
                年限：<input type="text" name="chanquan_year"
                onkeyup="if(isNaN(value))execCommand('undo')"  onafterpaste="if(isNaN(value))execCommand('undo')"
                 placeholder="年限" id="chanquan_year" value="<?php if(isset($chanquan_year))echo $chanquan_year;?>">
            </span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区经营面积<span class="hint">*</span></td>
        <td>
            <input type="text" name="jingying_mianji"  value="<?php if(isset($jingying_mianji))echo $jingying_mianji;?>" required/>&nbsp;平方米
        </td>
    </tr>
    <tr>
        <td class="tableleft">用于电子商务相关产业的经营面积<span class="hint">*</span></td>
        <td>
            <input type="text" name="dianshang_mianji"  value="<?php if(isset($dianshang_mianji)) echo $dianshang_mianji;?>" required/>&nbsp;平方米
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区进驻企业数<span class="hint">*</span></td>
        <td>
            <input type="text" name="qiyeshu"  value="<?php if(isset($qiyeshu))echo $qiyeshu;?>" required/>&nbsp;家
        </td>
    </tr>
    <tr>
        <td class="tableleft">电子商务应用企业数<span class="hint">*</span></td>
        <td>
            <input type="text" name="yingyong_qiyeshu"  value="<?php if(isset($yingyong_qiyeshu))echo $yingyong_qiyeshu;?>" required/>&nbsp;家
        </td>
    </tr>
    <tr>
        <td class="tableleft">电子商务服务企业数<span class="hint">*</span></td>
        <td>
            <input type="text" name="fuwu_qiyeshu"  value="<?php if(isset($fuwu_qiyeshu))echo $fuwu_qiyeshu;?>" required/>&nbsp;家
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
        <td class="tableleft" style="width:250px;">园区土地及建筑物产权证明文件<span class="hint">*</span><br>
            <span style="color:#ccc;text-align:left;">(属于租赁的需提供租赁合同，属于委托运营的提供委托双方的合同)</span>
        </td>
        <td>
           <div class="fujian_jzzm_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_jzzm)&&is_array($fujian_jzzm)){?>
                     <?php foreach ($fujian_jzzm as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_jzzm" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_jzzm[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_jzzm" id="fujian_jzzm" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_jzzm')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">园区三年以上的电子商务发展目标和计划<span class="hint">*</span></td>
        <td>
           <div class="fujian_ptzm_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_ptzm)&&is_array($fujian_ptzm)){?>
                     <?php foreach ($fujian_ptzm as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_ptzm" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_ptzm[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_ptzm" id="fujian_ptzm" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_ptzm')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">园区相关企业名称和简介、合同<span class="hint">*</span></td>
        <td>
           <div class="fujian_qyht_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_qyht)&&is_array($fujian_qyht)){?>
                     <?php foreach ($fujian_qyht as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_qyht" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_qyht[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_qyht" id="fujian_qyht" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_qyht')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">运营主体每年开展活动证明材料<span class="hint">*</span></td>
        <td>
           <div class="fujian_zmcl_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_zmcl)&&is_array($fujian_zmcl)){?>
                     <?php foreach ($fujian_zmcl as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_zmcl" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_zmcl[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_zmcl" id="fujian_zmcl" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_zmcl')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">园区建设和管理费用证明材料<span class="hint">*</span></td>
        <td>
           <div class="fujian_otzm_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_otzm)&&is_array($fujian_otzm)){?>
                     <?php foreach ($fujian_otzm as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_otzm" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_otzm[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_otzm" id="fujian_otzm" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_otzm')"/><br>
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
var flag_chanquan_other;//判断是否选中了其他产权选项,1为是
$(function () {
        var chanquan_id=document.getElementsByName('chanquan_id'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<chanquan_id.length; i++){ 
            // alert(chanquan_id[i].attributes['tname'].nodeValue);
            if(chanquan_id[i].attributes['tname'].nodeValue=='其他'&&chanquan_id[i].checked) {
                $('#beizhu_box').show();
            }
        } 
        // document.getElementById('year').value = new Date().getFullYear();
        //提交表单   
		$('#btnSave').click(function(){
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            if (!$('input:radio[name="chanquan_id"]').is(":checked")) {
                layer.msg('请选择园区产权情况！！',{time: 1000});
                return false;
            }
            jzzm_length  =  document.getElementsByName('fujian_jzzm[]').length;
            qyht_length  =  document.getElementsByName('fujian_qyht[]').length;
            ptzm_length  =  document.getElementsByName('fujian_ptzm[]').length;
            otzm_length  =  document.getElementsByName('fujian_otzm[]').length;
            zmcl_length  =  document.getElementsByName('fujian_zmcl[]').length;
            if (jzzm_length<=0) {
                layer.msg('请先上传园区土地及建筑物产权证明文件！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (ptzm_length<=0) {
                layer.msg('请先上传园区三年以上的电子商务发展目标和计划！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (qyht_length<=0) {
                layer.msg('请先上传园区相关企业名称和简介、合同！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (zmcl_length<=0) {
                layer.msg('请先上传运营主体每年开展活动证明材料！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (otzm_length<=0) {
                layer.msg('请先上传园区建设和管理费用证明材料！！',{time: 1000});
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
        //监听园区产权情况点击事件
        $('input[name="chanquan_id"]').click(function(){
            var obj = $(this);
            var tname = obj.attr('tname');
            if (tname=='其他') {
                flag_chanquan_other = 1;
                $('#beizhu_box').show();
            } else{
                flag_chanquan_other = 0;
                $('#beizhu_box').hide();
            }
        });
        //上传资料
    //园区土地及建筑物产权证明文件
    $('#fujian_jzzm').uploadify({
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
                            $('.fujian_jzzm_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_jzzm' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_jzzm[]' value='"+obj.id+"'></li>");
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
//园区相关企业名称和简介、合同
    $('#fujian_qyht').uploadify({
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
                            $('.fujian_qyht_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_qyht' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_qyht[]' value='"+obj.id+"'></li>");
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
//园区配套设施的投入证明
    $('#fujian_ptzm').uploadify({
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
                            $('.fujian_ptzm_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_ptzm' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_ptzm[]' value='"+obj.id+"'></li>");
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
//运营主体每年开展活动证明材料
    $('#fujian_zmcl').uploadify({
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
                            $('.fujian_zmcl_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_zmcl' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_zmcl[]' value='"+obj.id+"'></li>");
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
//其他相关证明材料
    $('#fujian_otzm').uploadify({
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
                            $('.fujian_otzm_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_otzm' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_otzm[]' value='"+obj.id+"'></li>");
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
</script>
