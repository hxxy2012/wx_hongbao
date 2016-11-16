<?php 
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>补充申报资料</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/validate/validator.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/layer/layer.js"></script>
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/js/bui-min.js"></script>        
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/js/config-min.js"></script>   
   <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/uploadfile/jquery.uploadify-3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.css"/>
   <script type="text/javascript">
    $(function(){
        //上传资料
        $('#fuchi_ziliao').uploadify({
            'auto'     : false,//关闭自动上传
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : '/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.swf',
            'uploader' : '<?php echo site_url("swj_upload/upload");?>?session_id=<?php echo $sess["session_id"];?>',
            'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText' : '选择文件',//设置按钮文本
            'multi'    : true,//允许同时上传多张图片
            'uploadLimit' : 10,//一次最多只允许上传10张图片
            'fileTypeDesc' : 'All Files',//只允许上传图像
            'fileTypeExts' : '*.gif; *.jpg; *.png;*.doc;*.pdf;*.rar;*.xls;*.xlsx',//限制允许上传的图片后缀
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
                            $('.zliao_img_box .img_ul').append("<li><a href='/"+obj.filesrc+"' target='_blank'>"+obj.filesrc.substr(obj.filesrc.lastIndexOf('/')+1)+"</a>&nbsp;&nbsp;<a href='javascript:void(0);' tp='"+obj.id+"' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;<input type='hidden' name='fuchi_ziliao[]' value='"+obj.id+"'></li>");
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
        
        //监听删除附件事件
        $("body").on("click", ".img_ul_li_a", function(){
            // alert($(this).attr("tp"));
            var obj = $(this);
            var id  = obj.attr("tp");
            //ajax 删除附件
            $.ajax({ 
                    type: "get", 
                    url: "<?php echo site_url("swj_upload/delfj");?>", 
                    data:{'id':id},
                    cache:false, 
                    async:true, 
                    success: function(data){ 
                        obj.parent('li').remove();
                        layer.msg('删除成功',
                        {time: 1000}
                        );      
                    } 
                });
        });
    });

    //执行上传方法
    function upl(id){
        //$("#"+id).uploadify('settings', 'formData', {'test':''}); 
        $("#"+id).uploadify('upload');
    }
   </script>
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
        .tips_lk{color: #ccc;font-size: 12px;}
        .img_ul{padding: 0;border: 0;margin: 0;}
        .img_ul li{float: left;}
        .clear{clear: both;}
    </style>
    <link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />   
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("Swj_mime_project/index");?>">申报列表</a>
</div>
<form action="<?php echo site_url("Swj_mime_project/sup");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="dosup">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="sbid" value="<?php echo $sbid;?>">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft"><span class="hint">*</span>相关资料</td>
        <td>
            <div class="zliao_img_box">
                <ul class="img_ul">
                    <?php if (isset($fuchi_ziliao_file)&&is_array($fuchi_ziliao_file)) {?>
                     <?php foreach ($fuchi_ziliao_file as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                            <a href='javascript:void(0);' tp='<?php echo $value["id"]?>' class='img_ul_li_a'>删除</a>&nbsp;&nbsp;
                            <input type='hidden' name='fuchi_ziliao[]' value='<?php echo $value["id"]?>'>
                        </li>
                    <?php endforeach?>
                    <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="file" name="fuchi_ziliao" id="fuchi_ziliao" />&nbsp;&nbsp;
            <span style="color:#ccc;font-size:12px;">每个文件大小不超过10M,一次最多只允许上传10个文件</span>
            <input type="button" value="上传" class="button button-danger" onClick="upl('fuchi_ziliao')"/><br>
            &nbsp;&nbsp;<span style="color:#ccc;font-size:12px;">(文件支持格式：jpg、png、gif、bmp、doc、docx、xls、xlsx、ppt、pptx、rar、zip)</span>
        </td>
    </tr>

</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="button" class="btn btn-primary" value="保存" id="btn-submit">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/index");?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/lang/zh_CN.js"></script>
<script>
var beizhu;//备注
var yq_describe;//园区或基地简介
$(function () {
        //通过审核,将值设为1，提交表单   
        $('#btn-submit').click(function(){
            if($("#myform").Valid() == false || !$("#myform").Valid()) {
                return false ;
            }
            fz_length =  document.getElementsByName('fuchi_ziliao[]').length;
            flag = 1;
            if (fz_length<=0) {
                layer.msg('请先至少上传一个相关资料!',
                {time: 1000}
                );
                flag = 0;
            } 
            if (flag == 1) {
                myform.submit();
            } else {
                return false;
            }
        });
});

KindEditor.ready(function(K) {
   // beizhu = K.create('#beizhu',{
   //          width:'100%',
   //          height:'400px',
   //          allowFileManager:false ,
   //          allowUpload:false,
   //          afterCreate : function() {
   //              this.sync();
   //          },
   //          afterBlur:function(){
   //                this.sync();
   //          },
   //          extraFileUploadParams:{
   //              'cookie':''
   //          },
   //          uploadJson:"<?php echo site_url("Swj_hdba/upload");?>?session_id=<?php echo $sess["session_id"];?>"
   // });
   yq_describe = K.create('#yq_describe',{
            width:'100%',
            height:'400px',
            allowFileManager:false ,
            allowUpload:false,
            afterCreate : function() {
                this.sync();
            },
            afterBlur:function(){
                  this.sync();
            },
            extraFileUploadParams:{
                'cookie':''
            },
            uploadJson:"<?php echo site_url("Swj_hdba/upload");?>?session_id=<?php echo $sess["session_id"];?>"
   });
}); 
</script>
