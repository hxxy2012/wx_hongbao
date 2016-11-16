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
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
   <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>   
 
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

.bui-dialog .bui-stdmod-body{
		padding:0px;
	}
    </style>
<script>
BUI.use('common/page');
</script>        
</head>
<body class="definewidth" style="padding-top:10px;">


<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>订单号</th>
        <th>数量</th>
        <th>品牌</th>
        <th width="138">产品名</th>
        <th>单价（元）</th>
        <th>PV</th>
        <th width="150">创建时间</th>
        <th>小计PV</th>
        <th>小计总价</th>              	
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
$all_price = 0;
$all_pv = 0;           
            foreach($list as $v){
           $all_price+=($v["order_count"]*$v["price"]);
           $all_pv+=($v["order_count"]*$v["pv"]);
            	echo "<tr  onclick='seltr($(this))'>";
            	echo "<td>".$v["dingdan_hao"]."</td>";
            	echo "<td>".$v["order_count"]."</td>";
            	echo "<td>".$v["brandname"];
            	echo "</td>";
            	echo "<td>".$v["product_name"]."</td>";
            	echo "<td>".$v["price"]."</td>";
            	echo "<td>".$v["pv"]."</td>";
            	echo "<td>".$v["create_time"]."</td>";
            	echo "<td>".($v["order_count"]*$v["pv"])."</td>";
            	echo "<td>".($v["order_count"]*$v["price"])."</td>";
            	echo "</tr>";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  <div style="text-align:right;">
  合计：<?php echo $all_price;?>元
 &nbsp;&nbsp;&nbsp;&nbsp;
 PV：<?php echo $all_pv;?>
  &nbsp;&nbsp;&nbsp;&nbsp;
  </div>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">

  </div>




  
<br/>
<br/>



</body>
</html>




<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>