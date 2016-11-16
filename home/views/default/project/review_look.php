
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/js/uploadfile/uploadify.css"/>
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
    .tips{color:#ccc;font-size: 12px;}
    .img_ul{padding: 0;border: 0;margin: 0;}
    .img_ul li{float: left;}
    .clear{clear: both;}
</style>
 <link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />   

<tr>
    <td class="tableleft" style="width:198px;">中山市电子商务专项资金申请表</td>
    <td>
       <div class="fujian_shenqing_box">
            <ul class="img_ul">
                <?php if(isset($fujian_shenqing)&&is_array($fujian_shenqing)){?>
                 <?php foreach ($fujian_shenqing as $key => $value): ?>
                    <li>
                        <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                        <input type='hidden' name='fujian_shenqing[]' value='<?php echo $value["id"]?>'>
                    </li>
                <?php endforeach ?>
                <?php }?>
            </ul>
            <div class="clear"></div>
        </div>
    </td>
</tr>
<!-- 隐藏组织机构代码2016年7月14日10:42:22 -->
<tr style="display:none;">
    <td class="tableleft">企业组织机构代码</td>
    <td>
      <div class="fujian_zuzhi_box container">
           <ul class="gallery1 img_ul">
            <?php if(isset($fujian_zuzhi)&&is_array($fujian_zuzhi)){?>
              <?php foreach ($fujian_zuzhi as $key => $value): ?>
                    <li>
                        <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                        <input type='hidden' name='fujian_zuzhi[]' value='<?php echo $value["id"]?>'>
                    </li>
                <?php endforeach ?>
            <?php }?>
           </ul>
           <div class="clear"></div>
      </div>
    </td>
</tr>
<tr>
    <td class="tableleft">营业执照及有关审批文件复印件</td>
    <td>
       <div class="fujian_yingye_box">
          <ul class="img_ul">
             <?php if(isset($fujian_yingye)&&is_array($fujian_yingye)){?>
               <?php foreach ($fujian_yingye as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <input type='hidden' name='fujian_yingye[]' value='<?php echo $value["id"]?>'>
                  </li>
              <?php endforeach ?>
            <?php }?>
          </ul>
          <div class="clear"></div>
      </div>
    </td>
</tr>
<tr>
    <td class="tableleft">商事主体负责人身份证复印件</td>
    <td>
       <div class="fujian_shenqingbaogao_box">
          <ul class="img_ul">
            <?php if(isset($fujian_shenqingbaogao)&&is_array($fujian_shenqingbaogao)){?>
               <?php foreach ($fujian_shenqingbaogao as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <input type='hidden' name='fujian_shenqingbaogao[]' value='<?php echo $value["id"]?>'>
                  </li>
              <?php endforeach ?>
            <?php }?>
          </ul>
          <div class="clear"></div>
      </div>
    </td>
</tr>
<tr>
    <td class="tableleft">商事主体上一年度的完税证明文件复印件</td>
    <td>
       <div class="fujian_wanshui_box">
          <ul class="img_ul">
            <?php if(isset($fujian_wanshui)&&is_array($fujian_wanshui)){?>
               <?php foreach ($fujian_wanshui as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <input type='hidden' name='fujian_wanshui[]' value='<?php echo $value["id"]?>'>
                  </li>
              <?php endforeach ?>
            <?php }?>
          </ul>
          <div class="clear"></div>
      </div>
    </td>
</tr>
<tr>
    <td class="tableleft">商事主体上年度财务报表</td>
    <td>
       <div class="fujian_caiwu_box">
          <ul class="img_ul">
            <?php if(isset($fujian_caiwu)&&is_array($fujian_caiwu)){?>
               <?php foreach ($fujian_caiwu as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <input type='hidden' name='fujian_caiwu[]' value='<?php echo $value["id"]?>'>
                  </li>
              <?php endforeach ?>
            <?php }?>
          </ul>
          <div class="clear"></div>
      </div>
    </td>
</tr>

<tr style="display:none;">
    <td class="tableleft">当年所获电子商务评奖或称号</td>
    <td>
       <div class="ny_chenghao_fj_box">
          <ul class="img_ul">
            <?php if(isset($ny_chenghao_fj)&&is_array($ny_chenghao_fj)){?>
               <?php foreach ($ny_chenghao_fj as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <input type='hidden' name='ny_chenghao_fj[]' value='<?php echo $value["id"]?>'>
                  </li>
              <?php endforeach ?>
            <?php }?>
          </ul>
          <div class="clear"></div>
      </div>
    </td>
</tr>
<tr>
    <td class="tableleft">给予扶持后补充资料</td>
    <td>
       <div class="fuchi_ziliao_box">
          <ul class="img_ul">
            <?php if(isset($fuchi_ziliao_file)&&is_array($fuchi_ziliao_file)){?>
               <?php foreach ($fuchi_ziliao_file as $key => $value): ?>
                  <li>
                      <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      <input type='hidden' name='fuchi_ziliao_file[]' value='<?php echo $value["id"]?>'>
                  </li>
              <?php endforeach ?>
            <?php }?>
          </ul>
          <div class="clear"></div>
      </div>
    </td>
</tr>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>