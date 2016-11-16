<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>打印</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="renderer" content="ie-stand" />
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
     .inheight15{height: 15px;}
     .inheight30{height: 30px;}
     .inheight80{height: 80px;}
     .inheight100{height: 100px;}
      @media print
            {
                body
                {

                    display: inherit; /*设置为none，则打印空白，即不能打印*/
                }
                
                .inheight30{height: 30px;}
                .inheight40{height:40px;}
                .inheight60{height:60px;}
                .inheight80{height: 120px;}
                .inheight100{height: 160px;}
                input{background: none;border:0;padding: 0;margin: 0;background-color: #fff;border-color: #000;
            }
    </style>
</head>
<body class="definewidth m20">
<div id="page1" style="width:50%;margin:0 auto;text-align:center;font-family:'微软雅黑';">
    <h1 style="text-align:center;">
        <span style="line-height:200%; font-size:20px;">中山市电子商务专项资金申请表</span>
        <br/>
        <span style="">(<?php echo $model["template_title"];?>)</span>
      </h1>
      
       <table border="0" cellpadding="0">
        <tr>
          <td width="10%"></td>
          <td width="80%">
            <h1 style="text-align:left;">
              申请单位:<?php echo $model["danwei"];?>
            </h1>
            <table class="m10" border="1" cellspacing="0" cellpadding="0" style="text-align:left;font-size:14px;">

                <tr >
                    <td width="20%"><br>交易平台名称<br><br></td>
                    <td colspan="3"><?php echo $fbmodel["title"];?></td>
                </tr>

                <tr>
                  <td width="100"><br>增值电信业务经营许可证号<br><br></td>
                  <td width="100"><?php echo $fbmodel["xukezheng"];?></td>
                  <td width="60">网站ICP备案证号</td>
                  <td width="100"><?php echo $fbmodel["icp"];?></td>
                </tr>
                <tr>
                  <td><br>平台销售的主要产品类别<br><br></td>
                  <td colspan="3">
            <?php
            foreach($fbmodel['product'] as $v){
            	echo $v["name"]." ";	
            }
            ?>      
                  </td>
                </tr>
                <tr>
                  <td><br>平台产权情况<br><br></td>
                  <td colspan="3">
            <?php
            foreach($fbmodel['all_ptqk'] as $v){
            	echo "<input type='checkbox' style='margin:0;padding:0;border:0;margin-left:1px;' disabled ";
            	
            	if($v["id"] == $fbmodel["chanquan"] ){
            		echo " checked ";
            	}	
            	echo " />";
            	echo $v["name"];
            	if($fbmodel["chanquan"]=="45172"){
            		//其他
            		echo "( ";
            		echo $fbmodel["chanquan_other"]?$fbmodel["chanquan_other"]:'&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';		
            		echo " )";	
            	}
            	echo "&nbsp;&nbsp;";
            }
            ?>      
                  </td>
                </tr>

                <tr>
                  <td><br>平台日均浏览量（UV）<br><br></td>
                  <td><?php echo $fbmodel["uv"];?></td>
                  <td>平台年度总成交额（万元）</td>
                  <td ><?php echo $fbmodel["chengjiao"];?></td>
                </tr> 
                <tr>
                  <td colspan="2"><br>拟申请资助的金额（元）<br><br></td>
                  <td colspan="2"><?php echo $fbmodel["shenqing"];?></td>
                </tr> 
                <tr>
                    <td style="text-align:left;" colspan="6">
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本企业对项目和申报材料的真实性负责（申报材料共&nbsp;&nbsp;&nbsp;&nbsp;页）。如有违反上述承诺的不诚实行为，本企业同意有关部门记录入相关的企业征信体系中，并承担相应责任。
                      <div class="inheight80"></div>
                      单位（盖章）<span style="margin-left:37%">法定代表人（签字）</span><br>
                      <span style="margin-left:52%">&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日</span>
                      <div class="inheight15"></div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left;">镇区商务主管部门意见</td>
                    <td style="text-align:left;" colspan="5">
                      <div class="inheight100"></div>
                      <span style="margin-left:50%">负责人签字（盖章）</span><br>
                      <span style="margin-left:49%">&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日</span>
                      <div class="inheight15"></div>
                    </td>
                </tr>     
            </table>
             </td>
          <td width="10%"></td>
        </tr>
      </table>
</div>
<?php $this->load->view(__TEMPLET_FOLDER__."/project/mime_print_foot") ?>
</body>
</html>
