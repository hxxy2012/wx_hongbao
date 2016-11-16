<?php 
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>审核电商园区或基地</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <link href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
   <link href="/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="/<?php echo APPPATH ?>/views/static/js/bui-min.js"></script>        
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
</head>
<link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />  
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("swj_dsyq/index");?>">园区或基地列表</a>
</div>
<form action="<?php echo site_url("swj_dsyq/check");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="docheck">
<input type="hidden" name="id" id="id" value="<?php echo $id?>">
<input type="hidden" name="audit" id="audit" value="">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">园区或基地名称</td>
        <td>
            <?php echo $name;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">所在单位</td>
        <td>
            <?php echo $companyname;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">所属镇区</td>
        <td>
            <?php echo $zqname;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">经营面积</td>
        <td>
            <?php echo $jymj;?>&nbsp;平方米
        </td>
    </tr>
    <tr>
        <td class="tableleft">进驻企业总数</td>
        <td>
           <?php echo $jzqynum;?>&nbsp;个
        </td>
    </tr>
    <tr>
        <td class="tableleft">产权情况</td>
        <td>
            <?php if ($cqcondition == 1) echo '企业所有';else echo '企业租赁:'.$cq_year.' 年';?>
        </td>
    </tr>
     <tr>
        <td class="tableleft">建筑面积</td>
        <td>
            <?php echo $jzmj;?>&nbsp;平方米
        </td>
    </tr>
    <tr>
        <td class="tableleft">占地面积</td>
        <td>
            <?php echo $zdmj;?>&nbsp;平方米
        </td>
    </tr>
     <tr>
        <td class="tableleft">电商企业数</td>
        <td>
           <?php echo $dsqynum;?>&nbsp;个
        </td>
    </tr>
    <tr>
        <td class="tableleft">当地扶持政策</td>
        <td>
           <?php echo $haspolicy;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">所获称号</td>
        <td>
           <?php echo $chenghao;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">联系人姓名</td>
        <td>
           <?php echo $linkman_name;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">联系人电话</td>
        <td>
           <?php echo $linkman_phone;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">联系人职务</td>
        <td>
           <?php echo $linkman_work;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地地址</td>
        <td>
           <?php echo $yq_addr;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地官方网站</td>
        <td>
           <?php echo $yq_website;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地简介</td>
        <td>
           <textarea style="width:100%; height:150px" id="yq_describe" name="yq_describe" placeholder="简介"><?php echo $yq_describe;?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft">相关资料</td>
        <td>
            <div class="zliao_img_box">
                <ul class="img_ul">
                    <?php foreach ($yq_ziliao_file as $key => $value): ?>
                        <li><a href='/<?php echo $value["filesrc"]?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;</li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区或基地剪影</td>
        <td>
            <div class="jingying_img_box container">
                <ul class="gallery1 img_ul">
                    <?php foreach ($yq_jianying_file as $key => $value): ?>
                        <li><a href='/<?php echo $value["filesrc"]?>' target='_blank'><img style="width:80px;height:60px;" src="/<?php echo $value["filesrc"]?>" alt=""></a>&nbsp;&nbsp;</li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
            <?php echo $beizhu;?>
           <!-- <textarea style="width:100%; height:150px" readonly id="beizhu" name="beizhu" placeholder="备注"><?php //echo $beizhu;?></textarea> -->
        </td>
    </tr>
    <tr>
        <td class="tableleft">审核意见</td>
        <td>
           <textarea style="width:100%; height:150px" id="audit_idea" name="audit_idea" placeholder="审核意见"><?php echo $audit_idea;?></textarea>
        </td>
    </tr>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <input type="button" class="btn btn-primary" value="审核通过" id="btn-submit-pass">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="审核不通过" id="btn-submit-npass">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("swj_dsyq/index");?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
var beizhu;//备注
var yq_describe;//园区或基地简介
var audit_idea;//审核意见

$(function(){
    //审核通过
    $('#btn-submit-pass').click(function(){
        $('#audit').val(20);
        myform.submit();
    });
    //审核不通过
    $('#btn-submit-npass').click(function(){
        if ($('#audit_idea').val()=='') {
            alert('请输入审核意见');
            $('#audit_idea').focus();
            return false;
        }
        $('#audit').val(30);
        myform.submit(); 
    });
});

KindEditor.ready(function(K) {
   /*beizhu = K.create('#beizhu',{
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
   });*/
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
   audit_idea = K.create('#audit_idea',{
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
    beizhu.readonly(true);
    yq_describe.readonly(true);
}); 
</script>
