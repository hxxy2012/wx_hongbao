<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看电子商务园区申报</title>
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
        <td class="tableleft"  style="width:198px;">园区名称</td>
        <td>
            <?php if(isset($yuanqu))echo $yuanqu;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">运营企业对园区的实际投入</td>
        <td>
            <?php if(isset($touru))echo $touru;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请资助的金额</td>
        <td>
            <?php if(isset($zizhu))echo $zizhu;?>&nbsp;元
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区产权情况</td>
        <td>
            <?php foreach ($all_yqqk as $key => $value): ?>
                <input type="radio" name="chanquan_id" style="margin-top:0;" disabled readonly 
                <?php if(isset($chanquan_id)&&$chanquan_id==$value['id']) echo 'checked';?>
                tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" ><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <br><br>
            <span id="beizhu_box" style="display:none;margin:0;">
                备注：<?php if(isset($chanquan_beizhu))echo $chanquan_beizhu;?>&nbsp;&nbsp;
                年限：<?php if(isset($chanquan_year))echo $chanquan_year;?>
            </span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区经营面积</td>
        <td>
            <?php if(isset($jingying_mianji))echo $jingying_mianji;?>&nbsp;平方米
        </td>
    </tr>
    <tr>
        <td class="tableleft">用于电子商务相关产业的经营面积</td>
        <td>
            <?php if(isset($dianshang_mianji)) echo $dianshang_mianji;?>&nbsp;平方米
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区进驻企业数</td>
        <td>
            <?php if(isset($qiyeshu))echo $qiyeshu;?>&nbsp;家
        </td>
    </tr>
    <tr>
        <td class="tableleft">电子商务应用企业数</td>
        <td>
            <?php if(isset($yingyong_qiyeshu))echo $yingyong_qiyeshu;?>&nbsp;家
        </td>
    </tr>
    <tr>
        <td class="tableleft">电子商务服务企业数</td>
        <td>
            <?php if(isset($fuwu_qiyeshu))echo $fuwu_qiyeshu;?>&nbsp;家
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
        <td class="tableleft">园区土地及建筑物产权证明文件</td>
        <td>
           <div class="fujian_jzzm_box">
              <ul class="img_ul">
                <?php if(isset($fujian_jzzm)&&is_array($fujian_jzzm)){?>
                   <?php foreach ($fujian_jzzm as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_jzzm[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区三年以上的电子商务发展目标和计划</td>
        <td>
           <div class="fujian_qyht_box">
              <ul class="img_ul">
                <?php if(isset($fujian_ptzm)&&is_array($fujian_ptzm)){?>
                   <?php foreach ($fujian_ptzm as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_ptzm[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区相关企业名称和简介、合同</td>
        <td>
           <div class="fujian_qyht_box">
              <ul class="img_ul">
                <?php if(isset($fujian_qyht)&&is_array($fujian_qyht)){?>
                   <?php foreach ($fujian_qyht as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_qyht[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">运营主体每年开展活动证明材料</td>
        <td>
           <div class="fujian_zmcl_box">
              <ul class="img_ul">
                <?php if(isset($fujian_zmcl)&&is_array($fujian_zmcl)){?>
                   <?php foreach ($fujian_zmcl as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_zmcl[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">园区建设和管理费用证明材料</td>
        <td>
           <div class="fujian_otzm_box">
              <ul class="img_ul">
                <?php if(isset($fujian_otzm)&&is_array($fujian_otzm)){?>
                   <?php foreach ($fujian_otzm as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_otzm[]' value='<?php echo $value["id"]?>'>
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
<!-- <input type="button" value="打印" onclick="print_r();"> -->
<div class="center" style="margin:0 auto;text-align:center;">
    <!-- <button class="btn btn-primary" type="button" id="btnSave">提交初审</button> &nbsp;&nbsp; -->
    <!-- <input type="button" class="btn btn-primary" value="临时保存" onclick="javascript:lssave();">&nbsp;&nbsp; -->
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("Swj_mime_project/index")?>';">&nbsp;&nbsp;
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
});
/* KindEditor.ready(function(K) {
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
});*/ 
 function print_r() {
    window.print();
 }
</script>
