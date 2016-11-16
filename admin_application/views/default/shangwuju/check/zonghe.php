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
        <td class="tableleft">综合性服务平台名称</td>
        <td colspan="3"><?php echo $model2["title"];?></td>
    </tr>

    <tr>
      <td class="tableleft">平台提供的服务（不得少于3项）</td>
      <td colspan="3">
<?php
foreach($pingtaifuwu as $k=>$v){
	echo "<input type='checkbox' disabled ";
	
	if( in_array($v["id"],explode(",",$model2["fuwu_type"])) ){
		echo " checked ";
	}	
	echo " />";
	echo $v["name"];
	if($v["id"]=="45180"){
		//其他
		echo "(";
		echo "请列出具体情况:";
		echo $model2["fuwu_type_other"];
		echo ")";	
	}
	echo "&nbsp;&nbsp;";		
}
?>      
      </td>
    </tr>
    <tr>
      <td class="tableleft">平台服务的企业数（家））</td>
      <td><?php echo $model2["qiyeshu"];?></td>
      <td class="tableleft">平台的营业收入（万元）</td>
      <td ><?php echo $model2["shouru"];?></td>
    </tr> 
    <tr>
      <td class="tableleft">拟申请资助的金额（元）</td>
      <td colspan="3"><?php echo $model2["shenqing"];?></td>
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
