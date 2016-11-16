<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看电子商务综合服务平台</title>
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

<form action="<?php echo site_url("Swj_mime_project/edit_step");?>" method="post" class="definewidth m2"  name="myform" id="myform">
<caption>项目基础信息</caption>
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft" style="width:198px;">项目名称</td>
        <td>
            <?php echo $title;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">申报时段</td>
        <td>
            <?php echo $timepart;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">项目简介</td>
        <td>
          <?php echo $content;?>
            <!-- <textarea style="width:100%; height:150px" id="describe" name="describe" placeholder="简介"><?php //echo $content;?></textarea> -->
        </td>
    </tr>
    
</table>
<caption>审核信息(模板：<?php echo $template_title?>)</caption>
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft" style="width:198px;">综合服务平台名称</td>
        <td>
            <?php if(isset($title))echo $title;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台提供的服务（不得少于3项）</td>
        <td>
            <?php foreach ($all_ptfw as $key => $value): ?>
                <input type="checkbox" name="fuwu_type[]" style="margin-top:0;" readonly disabled
                <?php if(isset($fuwu_type)&&stripos($fuwu_type, $value['id'])!==false)echo 'checked';?>
                tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" >&nbsp;<?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <br><br>
            <span id="beizhu_box" style="display:none;margin:0;">
                <?php if(isset($fuwu_type_other))echo $fuwu_type_other;?>
            </span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台服务的企业数</td>
        <td>
            <?php if(isset($qiyeshu))echo $qiyeshu;?>&nbsp;家
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台的营业收入</td>
        <td>
            <?php if(isset($shouru))echo $shouru;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请资助的金额</td>
        <td>
            <?php if(isset($shenqing))echo $shenqing;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度电子商务营业收入</td>
        <td>
            <?php if(isset($year_in))echo $year_in;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度电子商务营业收入占比</td>
        <td>
            <?php if(isset($year_in_bl))echo $year_in_bl;?>&nbsp;%
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度企业纳税金额</td>
        <td>
            <?php if(isset($year_out))echo $year_out;?>&nbsp;万元
        </td>
    </tr>
     <tr>
        <td class="tableleft">项目基本情况介绍证明材料</td>
        <td>
           <div class="fujian_xmqk_box">
              <ul class="img_ul">
                <?php if(isset($fujian_xmqk)&&is_array($fujian_xmqk)){?>
                   <?php foreach ($fujian_xmqk as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_xmqk[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">运营销售额的后台数据截图</td>
        <td>
           <div class="fujian_yyxse_box">
              <ul class="img_ul">
                <?php if(isset($fujian_yyxse)&&is_array($fujian_yyxse)){?>
                   <?php foreach ($fujian_yyxse as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_yyxse[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">项目建设投入的证明材料</td>
        <td>
           <div class="fujian_xmtr_box">
              <ul class="img_ul">
                <?php if(isset($fujian_xmtr)&&is_array($fujian_xmtr)){?>
                   <?php foreach ($fujian_xmtr as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_xmtr[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">与委托方的代理合同或协议</td>
        <td>
           <div class="fujian_dlht_box">
              <ul class="img_ul">
                <?php if(isset($fujian_dlht)&&is_array($fujian_dlht)){?>
                   <?php foreach ($fujian_dlht as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_dlht[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr style="display:none;">
        <td class="tableleft">费用收支证明</td>
        <td>
           <div class="fujian_fysz_box">
              <ul class="img_ul">
                <?php if(isset($fujian_fysz)&&is_array($fujian_fysz)){?>
                   <?php foreach ($fujian_fysz as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_fysz[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <?php $this->load->view(__TEMPLET_FOLDER__."/project/review_look") ?>
</table>
<div class="center" style="margin:0 auto;text-align:center;">
    <!-- <button class="btn btn-primary" type="button" id="btnSave">提交初审</button> &nbsp;&nbsp; -->
    <!-- <input type="button" class="btn btn-primary" value="临时保存" onclick="javascript:lssave();">&nbsp;&nbsp; -->
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/index");?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/js/kindeditor/lang/zh_CN.js"></script>
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
});
  /*KindEditor.ready(function(K) {
   describe = K.create('#describe',{
            width:'100%',
            height:'300px',
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
   describe.readonly(true);
}); */
</script>
