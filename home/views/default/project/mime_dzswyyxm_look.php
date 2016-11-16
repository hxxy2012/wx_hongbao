<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看电子商务应用项目</title>
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
        <td class="tableleft" style="width:198px;">电子商务应用项目类型<span class="hint">*</span></td>
        <td>
            <?php foreach ($all_yyxmlx as $key => $value): ?>
                <input type="radio" name="yingyong" style="margin-top:0;" readonly disabled 
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
            <?php if(isset($shenqing))echo $shenqing;?>&nbsp;元
        </td>
    </tr>
    <tr>
        <td class="tableleft">年网上销售额<span class="hint">*</span></td>
        <td>
            <?php if(isset($xiaoshou))echo $xiaoshou;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">网店网址<span class="hint">*</span></td>
        <td>
            <?php if(isset($url))echo $url;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度电子商务营业收入<span class="hint">*</span></td>
        <td>
            <?php if(isset($year_in))echo $year_in;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度电子商务营业收入占比<span class="hint">*</span></td>
        <td>
            <?php if(isset($year_in_bl))echo $year_in_bl;?>&nbsp;%
        </td>
    </tr>
    <tr>
        <td class="tableleft">年度企业纳税金额<span class="hint">*</span></td>
        <td>
            <?php if(isset($year_out))echo $year_out;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">代运营企业数量</td>
        <td>
            <?php if(isset($qiyeshu))echo $qiyeshu;?>&nbsp;家&nbsp;(代运营企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">线下体验店地址</td>
        <td>
            <?php if(isset($o2o_addr))echo $o2o_addr;?>&nbsp;(O2O企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">网店是否有移动支付功能</td>
        <td>
            <input type="radio" readonly disabled name="o2o_ispay" <?php if(isset($o2o_ispay)&&$o2o_ispay=='0')echo 'checked';?> value="0">无&nbsp;&nbsp;
            <input type="radio" readonly disabled name="o2o_ispay" <?php if(isset($o2o_ispay)&&$o2o_ispay=='1')echo 'checked';?> value="1">有&nbsp;&nbsp;&nbsp;(O2O企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">网上销售额占年销售总额的比例</td>
        <td>
            <?php if(isset($o2o_bili))echo $o2o_bili;?>&nbsp;%&nbsp;(O2O企业填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">电子商务新技术应用项目的基本情况</td>
        <td>
            <?php if(isset($content))echo $content;?>&nbsp;(电子商务新技术应用项目填写)
        </td>
    </tr>
    <tr>
        <td class="tableleft">网站后台销售数据或第三方平台出具销售额证明</td>
        <td>
           <div class="fujian_xszm_box">
              <ul class="img_ul">
                <?php if(isset($fujian_xszm)&&is_array($fujian_xszm)){?>
                   <?php foreach ($fujian_xszm as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_xszm[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">与委托方签订代理合同或协议</td>
        <td>
           <div class="fujian_htxy_box">
              <ul class="img_ul">
                <?php if(isset($fujian_htxy)&&is_array($fujian_htxy)){?>
                   <?php foreach ($fujian_htxy as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_htxy[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">项目投入证明</td>
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
        <td class="tableleft">市场覆盖证明</td>
        <td>
           <div class="fujian_szzm_box">
              <ul class="img_ul">
                <?php if(isset($fujian_scfg)&&is_array($fujian_scfg)){?>
                   <?php foreach ($fujian_scfg as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_scfg[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">业务增长证明</td>
        <td>
           <div class="fujian_szzm_box">
              <ul class="img_ul">
                <?php if(isset($fujian_ywzz)&&is_array($fujian_ywzz)){?>
                   <?php foreach ($fujian_ywzz as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_ywzz[]' value='<?php echo $value["id"]?>'>
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
    <!-- <input type="button" class="btn btn-primary" id="btnSave" value="提交初审"> &nbsp;&nbsp; -->
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
var flag_chanquan_other;//判断是否选中了其他产权选项,1为是
 
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
