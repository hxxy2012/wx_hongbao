<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>电子商务交易平台</title>
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
        <td class="tableleft">交易平台名称<span class="hint">*</span></td>
        <td>
            <input type="text" name="title" style="width:333px;" value="<?php if(isset($title))echo $title;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">交易平台网址<span class="hint">*</span></td>
        <td>
            <input type="text" name="url"  value="<?php if(isset($url))echo $url;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">网站ICP备案证号<span class="hint">*</span></td>
        <td>
            <input type="text" name="icp"  value="<?php if(isset($icp))echo $icp;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">增值电信业务经营许可证号<span class="hint">*</span></td>
        <td>
            <input type="text" name="xukezheng"  value="<?php if(isset($xukezheng))echo $xukezheng;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台销售的主要产品类型<span class="hint">*</span></td>
        <td>
            <span id="product_text">        
            <?php
                if(isset($product)&&is_array($product)){
            ?>
                    <?php 
                        $product_list = "";
                        foreach($product as $v){
                            if($product_list==""){
                                $product_list = $v["name"];
                            }
                            else{
                                $product_list .=",".$v["name"];
                            }
                        }
                        echo $product_list;
                    ?>
                <?php   
                    }
                ?>      
            </span>
            <input type="hidden" name="protype" id="product" value="<?php echo isset($protype)?$protype:"";?>" />
            <button type="button" id="btn_open_pro" class="btn btn-warning" >选择</button>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台产权情况<span class="hint">*</span></td>
        <td>
            <?php foreach ($all_ptqk as $key => $value): ?>
                <input type="radio" name="chanquan" style="margin-top:0;" 
                <?php if(isset($chanquan)&&$chanquan==$value['id'])echo 'checked';?>
                tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" ><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <br><br>
            <span id="beizhu_box" style="display:none;margin:0;">
                <input type="text" style="width:333px;"  name="chanquan_other" placeholder="平台名称" id="chanquan_other" value="<?php if(isset($chanquan_other))echo $chanquan_other;?>">
            </span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台日均浏览量(UV)<span class="hint">*</span></td>
        <td>
            <input type="text" name="uv"  value="<?php if(isset($uv))echo $uv;?>" required/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台年度总成交额<span class="hint">*</span></td>
        <td>
            <input type="text" name="chengjiao"  value="<?php if(isset($chengjiao))echo $chengjiao;?>" required/>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请金额<span class="hint">*</span></td>
        <td>
            <input type="text" name="shenqing"  value="<?php if(isset($shenqing))echo $shenqing;?>" required/>&nbsp;元
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
        <td class="tableleft" style="width:250px;">平台三年以上的建设目标和工作计划<span class="hint">*</span></td>
        <td>
           <div class="fujian_mbjh_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_mbjh)&&is_array($fujian_mbjh)){?>
                     <?php foreach ($fujian_mbjh as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_mbjh" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_mbjh[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_mbjh" id="fujian_mbjh" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_mbjh')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">网站增值电信业务许可证或ICP备案证复印件<span class="hint">*</span></td>
        <td>
           <div class="fujian_xkba_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_xkba)&&is_array($fujian_xkba)){?>
                     <?php foreach ($fujian_xkba as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_xkba" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_xkba[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_xkba" id="fujian_xkba" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_xkba')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">平台名称、链接、流量和交易额证明材料<span class="hint">*</span></td>
        <td>
           <div class="fujian_trzm_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_trzm)&&is_array($fujian_trzm)){?>
                     <?php foreach ($fujian_trzm as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_trzm" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_trzm[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_trzm" id="fujian_trzm" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_trzm')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">平台每年建设和管理费用投入及交易手续费证明材料<span class="hint">*</span></td>
        <td>
           <div class="fujian_lljy_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_lljy)&&is_array($fujian_lljy)){?>
                     <?php foreach ($fujian_lljy as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_lljy" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_lljy[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_lljy" id="fujian_lljy" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_lljy')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">平台运营技术团队证明材料<span class="hint">*</span><br>
        <span style="color:#ccc;text-align:left;">(服务外包提供服务合同复印件)</span>
        </td>
        <td>
           <div class="fujian_fwht_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_fwht)&&is_array($fujian_fwht)){?>
                     <?php foreach ($fujian_fwht as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_fwht" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_fwht[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_fwht" id="fujian_fwht" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_fwht')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、pdf)</span>
        </td>
    </tr>
    <tr>
        <td class="tableleft" style="width:250px;">进驻的卖家名单及对应的网店网址证明材料<br>
            <span style="color:#ccc;text-align:left;">(自建开放平台需提供)</span>
        </td>
        <td>
           <div class="fujian_fysz_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_fysz)&&is_array($fujian_fysz)){?>
                     <?php foreach ($fujian_fysz as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_fysz" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
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
    <tr>
        <td class="tableleft" style="width:250px;">完善平台管理服务功能和组织活动的证明材料<span class="hint">*</span>
        </td>
        <td>
           <div class="fujian_wspt_box">
                <ul class="img_ul">
                    <?php if(isset($fujian_wspt)&&is_array($fujian_wspt)){?>
                     <?php foreach ($fujian_wspt as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' uploadid="fujian_wspt" class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fujian_wspt[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach ?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fujian_wspt" id="fujian_wspt" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">只允许上传一个文件，文件大小不超过10M</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fujian_wspt')"/><br>
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
        var chanquan=document.getElementsByName('chanquan'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<chanquan.length; i++){ 
            // alert(chanquan[i].attributes['tname'].nodeValue);
            if(chanquan[i].attributes['tname'].nodeValue=='与国内其他电商平台共建'&&chanquan[i].checked) {
                $('#beizhu_box').show();
            }
        } 
        // document.getElementById('year').value = new Date().getFullYear();
        //提交表单   
		$('#btnSave').click(function(){
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            if ($('#product').val()=='') {
                layer.msg('请选择平台销售的主要产品类型！！',{time: 1000});
                return false;
            }
            if (!$('input:radio[name="chanquan"]').is(":checked")) {
                layer.msg('请选择平台产权情况！！',{time: 1000});
                return false;
            }
            xkba_length  =  document.getElementsByName('fujian_xkba[]').length;
            trzm_length  =  document.getElementsByName('fujian_trzm[]').length;
            lljy_length  =  document.getElementsByName('fujian_lljy[]').length;
            fwht_length  =  document.getElementsByName('fujian_fwht[]').length;
            fysz_length  =  document.getElementsByName('fujian_fysz[]').length;
            // 新增2016年7月14日10:54:36
            wspt_length  =  document.getElementsByName('fujian_wspt[]').length;
            mbjh_length  =  document.getElementsByName('fujian_mbjh[]').length;
            if (mbjh_length<=0) {
                layer.msg('请先上传平台三年以上的建设目标和工作计划！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (xkba_length<=0) {
                layer.msg('请先上传网站增值电信业务许可证或ICP备案证复印件！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (trzm_length<=0) {
                layer.msg('请先上传平台名称、链接、流量和交易额证明材料！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (lljy_length<=0) {
                layer.msg('请先上传平台每年建设和管理费用投入及交易手续费证明材料！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            if (fwht_length<=0) {
                layer.msg('请先上传平台运营技术团队证明材料！！',{time: 1000});
                flag_submit = 1;
                return false;
            } 
            /*if (fysz_length<=0) {
                layer.msg('请先上传进驻的卖家名单及对应的网店网址证明材料！！',{time: 1000});
                flag_submit = 1;
                return false;
            } */
            if (wspt_length<=0) {
                layer.msg('请先上传完善平台管理服务功能和组织活动的证明材料！！',{time: 1000});
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
        });
        //主营产品
        $("#btn_open_pro").on("click",function(){
        layer.open({
            title: "选择",
            type: 2,
            area: ['700px', '300px'],
            fix: false, //不固定
            maxmin: true,
            content: '<?php echo site_url("swj_admin/prolist2");?>?sel='+$("#product").val()
        }); 
    });
            //目标计划
    $('#fujian_mbjh').uploadify({
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
                            $('.fujian_mbjh_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_mbjh' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_mbjh[]' value='"+obj.id+"'></li>");
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
    //网站增值电信业务许可证或ICP备案证复印件
    $('#fujian_xkba').uploadify({
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
                            $('.fujian_xkba_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_xkba' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_xkba[]' value='"+obj.id+"'></li>");
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
    //平台建设投入证明
    $('#fujian_trzm').uploadify({
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
                            $('.fujian_trzm_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_trzm' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_trzm[]' value='"+obj.id+"'></li>");
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
//网站后台流量和交易额截图
    $('#fujian_lljy').uploadify({
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
                            $('.fujian_lljy_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_lljy' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_lljy[]' value='"+obj.id+"'></li>");
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
//开展服务合同
    $('#fujian_fwht').uploadify({
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
                            $('.fujian_fwht_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_fwht' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_fwht[]' value='"+obj.id+"'></li>");
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
            alert('超过文件上传大小限制（2M）！');
            return;
           }
           alert(errorObj.type + ', Error: ' + errorObj.info);
        },  
        // Put your options here
    });

    //网站增值电信业务许可证或ICP备案证复印件
    $('#fujian_wspt').uploadify({
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
                            $('.fujian_wspt_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' uploadid='fujian_wspt' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fujian_wspt[]' value='"+obj.id+"'></li>");
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
