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


    </style>
<script>
BUI.use('common/page');
</script>        
</head>
<body class="definewidth">

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>栏目名</th>        
        <th width='80'>操作</th>
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr>";
            	echo "<td>".$v["id"]."</td>";  
            	echo "<td>".$v["title"];
            	echo "</td>";            	
            	echo "<td>";
            	//echo "<a class='page-action icon-edit' data-href='".site_url('website_category/edit')."?id=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='".__CLASS__.$v["id"]."' id='open_edit_".$v["id"]."' title=\"编辑".$v["title"]."的信息\"></a>";	           
            	echo "<a class='icon-edit' href='".site_url('website_category/edit')."?opener=no&id=".$v["id"]."&backurl=".urlencode(get_url())."' title=\"编辑".$v["title"]."的信息\"></a>";
            	echo "&nbsp;";
				echo "<a class='icon-th-list' href='".site_url('website_category/infolist')."?opener=no&typeid=".$v["id"]."&backurl=".urlencode(get_url())."' title='新闻列表'></a>";
				echo "&nbsp;";
				echo "<a class='page-action icon-plus-sign' data-href='".site_url('website_category/addinfo')."?opener=no&typeid=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='".__CLASS__.$v["id"]."' id='open_add_".$v["id"]."' title=\"新增".$v["title"]."的信息\"></a>";    	
				echo "</td>";                          	            	             	
            	echo "</tr>";
            }
            ?>  
  
  
  </tbody>  
  
  </table>



   <input type="hidden" name="selid" id="selid" value=""/>
       
       
  


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>

<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>