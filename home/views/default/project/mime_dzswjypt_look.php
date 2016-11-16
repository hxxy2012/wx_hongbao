<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看电子商务交易平台</title>
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
        <td class="tableleft" style="width:198px;">交易平台名称</td>
        <td>
            <?php if(isset($title))echo $title;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">交易平台网址</td>
        <td>
            <?php if(isset($url))echo $url;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">网站ICP备案证号</td>
        <td>
            <?php if(isset($icp))echo $icp;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">增值电信业务经营许可证号</td>
        <td>
            <?php if(isset($xukezheng))echo $xukezheng;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台销售的主要产品类型</td>
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
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台产权情况</td>
        <td>
            <?php foreach ($all_ptqk as $key => $value): ?>
                <input type="radio" name="chanquan" style="margin-top:0;" readonly disabled
                <?php if(isset($chanquan)&&$chanquan==$value['id'])echo 'checked';?>
                tname="<?php echo $value['name']?>" value="<?php echo $value['id']?>" ><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <br><br>
            <span id="beizhu_box" style="display:none;margin:0;">
                <?php if(isset($chanquan_other))echo $chanquan_other;?>
            </span>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台日均浏览量(UV)</td>
        <td>
            <?php if(isset($uv))echo $uv;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台年度总成交额</td>
        <td>
            <?php if(isset($chengjiao))echo $chengjiao;?>&nbsp;万元
        </td>
    </tr>
    <tr>
        <td class="tableleft">拟申请金额</td>
        <td>
            <?php if(isset($shenqing))echo $shenqing;?>&nbsp;元
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
        <td class="tableleft">平台三年以上的建设目标和工作计划</td>
        <td>
           <div class="fujian_xkba_box">
              <ul class="img_ul">
                <?php if(isset($fujian_mbjh)&&is_array($fujian_mbjh)){?>
                   <?php foreach ($fujian_mbjh as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_mbjh[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">网站增值电信业务许可证或ICP备案证复印件</td>
        <td>
           <div class="fujian_xkba_box">
              <ul class="img_ul">
                <?php if(isset($fujian_xkba)&&is_array($fujian_xkba)){?>
                   <?php foreach ($fujian_xkba as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_xkba[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台名称、链接、流量和交易额证明材料</td>
        <td>
           <div class="fujian_trzm_box">
              <ul class="img_ul">
                <?php if(isset($fujian_trzm)&&is_array($fujian_trzm)){?>
                   <?php foreach ($fujian_trzm as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_trzm[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台每年建设和管理费用投入及交易手续费证明材料</td>
        <td>
           <div class="fujian_lljy_box">
              <ul class="img_ul">
                <?php if(isset($fujian_lljy)&&is_array($fujian_lljy)){?>
                   <?php foreach ($fujian_lljy as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_lljy[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">平台运营技术团队证明材料</td>
        <td>
           <div class="fujian_fwht_box">
              <ul class="img_ul">
                <?php if(isset($fujian_fwht)&&is_array($fujian_fwht)){?>
                   <?php foreach ($fujian_fwht as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_fwht[]' value='<?php echo $value["id"]?>'>
                      </li>
                  <?php endforeach ?>
                <?php }?>
              </ul>
              <div class="clear"></div>
          </div>
        </td>
    </tr>
    <tr>
        <td class="tableleft">进驻的卖家名单及对应的网店网址证明材料</td>
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
    <tr>
        <td class="tableleft">完善平台管理服务功能和组织活动的证明材料</td>
        <td>
           <div class="fujian_xkba_box">
              <ul class="img_ul">
                <?php if(isset($fujian_wspt)&&is_array($fujian_wspt)){?>
                   <?php foreach ($fujian_wspt as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                          <input type='hidden' name='fujian_wspt[]' value='<?php echo $value["id"]?>'>
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
