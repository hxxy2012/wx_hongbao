<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/laydate/laydate.js"></script>
	
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

 .tableleft{
	text-align:right!important;
	padding-left:5px;
	background-color:#f5f5f5;
	width:100px;
	}
 .tabletd td{
	 padding:5px;
	 margin:0px;	 	
 }

    </style>
</head>
<body class="definewidth m20">
<input type="hidden" name="backurl" value="<?php echo $ls;?>">
<form method="post" id="check_form" >
<table class="table table-bordered table-hover m10">

    <tr>
      <td colspan="4">
      <div style="text-align:center;">
      <span style="line-height:200%; font-size:20px; font-weight:bold;">中山市电子商务专项资金申请表</span>
      <br/>
      (<?php echo $model["template_title"];?>)
      </div>
      </td>
    </tr>
    <tr>
      <td class="tableleft">状态</td>
      <td colspan="3"><?php echo $checkstatus[$model["checkstatus"]];?></td>
    </tr>
    <tr>
      <td class="tableleft">项目</td>
      <td colspan="3"><?php echo $promodel["title"];?></td>
    </tr>
    <tr>
        <td class="tableleft">申请单位</td>
        <td colspan="3"><?php echo $model["danwei"];?></td>
    </tr>
    <tr >
        <td class="tableleft">电子商务应用项目类型</td>
        <td colspan="3"><?php
foreach($yingyong_xiangmu as $k=>$v){
	echo "<input type='checkbox' disabled ";
	
	if( in_array($v["id"],explode(",",$model2["yingyong"])) ){
		echo " checked ";
	}	
	echo " />";
	echo $v["name"];
	echo "&nbsp;&nbsp;";		
}
?></td>
    </tr>

    <tr>
      <td class="tableleft">拟申请资助的金额（元）</td>
      <td colspan="3"><?php echo $model2["shenqing"];?></td>
    </tr>
    <tr>
      <td class="tableleft">年网上销售额（万元）</td>
      <td><?php echo $model2["xiaoshou"];?></td>
      <td class="tableleft">网店网址</td>
      <td ><?php echo $model2["url"];?></td>
    </tr> 
    <tr>
      <td class="tableleft">代运营企业数量（家）</td>
      <td colspan="3"><?php echo $model2["qiyeshu"];?></td>
    </tr>
    <tr>
      <td class="tableleft">O2O企业填写</td>
      <td colspan="3"><table border="0" cellpadding="0" cellspacing="0" style="border:0px;padding:0px;margin:0px;">
        <tr>
          <td style="border:0px;padding:0px;margin:0px;">线下体验店地址</td>
          <td style="border:0px;padding:0px;margin:0px;"><?php echo $model2["o2o_addr"];?></td>
        </tr>
        <tr>
          <td style="border:0px;padding:0px;margin:0px;">网店是否有移动支付功能</td>
          <td style="border:0px;padding:0px;margin:0px;"><?php echo $model2["o2o_ispay"]=="1"?"有":"无";?></td>
        </tr>
        <tr>
          <td style="border:0px;padding:0px;margin:0px;">网上销售额占年销售总额的比例（%）</td>
          <td style="border:0px;padding:0px;margin:0px;"><?php echo $model2["o2o_bili"];?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td class="tableleft">电子商务新技术应用项目的基本情况</td>
      <td colspan="3"><?php echo $model2["content"];?></td>
    </tr>       
    <tr>
      <td colspan="4" valign="top" style="margin:0px; padding:0px;" ><table class="tabletd" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="tableleft"  style="width:105px;">年度电子商务营业收入（万元）</td>
          <td><?php echo $model2["year_in"];?></td>
          <td class="tableleft">年度电子商务营业收入占比（%）</td>
          <td><?php echo $model2["year_in_bl"];?></td>
          <td class="tableleft">年度企业纳税金额（万元）</td>
          <td><?php echo $model2["year_out"];?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td valign="top" class="tableleft" >附件</td>
      <td colspan="3" valign="top" >
      <?php
	  $i=1;
foreach($fujian as $k=>$v){
  echo $i.".";
  echo $k;
  echo "&nbsp;";
  if ($k == "给予扶持后补充资料") {
    if (count($v) <= 0) {
      echo "<span style='color:red;'>未上传</span>";
    } else {
      foreach ($v as $k1 => $v1) {
        echo "<a href='/".$v1['filesrc']."' target='_blank'>";
        echo "【查看".($k1+1)."】";
        echo "</a>";
      }
    }
    
  } else{
    if($v!=""){
      echo "<a href='/".$v."' target='_blank'>";
      echo "【查看】";
      echo "</a>";
    }
    else{
      echo "<span style='color:red;'>未上传</span>";
    }
  }
  
  echo "<br/>";
  $i++;
}
	  ?>
      </td>
    </tr>

<?php $this->load->view(__TEMPLET_FOLDER__."/shangwuju/check/form");?> 
</table>

</body>
</html>
