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
<body class="definewidth" style="padding-top:10px;">
<?php if($isadd){?>      
    <a  class="btn btn-success" id="addnew" href="<?php echo site_url("zzb_dengji/add")."?backurl=".urlencode(get_url());?>">新增等级<span class="glyphicon glyphicon-plus"></span></a>
<?php }?>   

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>等级名</th>
        <th>分数区间</th>
        <th>图标</th>
        <th width="150">创建时间</th>        
        <th></th>         	
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["dengji"]."</td>";
            	echo "<td>".$v["fenshu_start"];
            	echo "-".$v["fenshu_end"];
            	echo "</td>";
            	echo "<td>".$v["ico"]."</td>";
            	echo "<td>".$v["createdate"]."</td>";            	
            	echo "<td>";
            	echo "<a class='page-action icon-edit' data-href='".site_url('zzb_dengji/edit')."?id=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='".__CLASS__.$v["id"]."' id='open_edit_".$v["id"]."' title=\"编辑".$v["dengji"]."\"></a>";
    			echo "</td>";
            	echo "</tr>";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>



   <input type="hidden" name="selid" id="selid" value=""/>
       <button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button>
<?php if($isdel){?>       
       <button class="button button-danger" onclick="godel()">删除</button>
<?php }?>  

<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});


function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{	
					
			var ajax_url = "/admin.php/zzb_dengji/del.shtml?idlist="+$("#selid").val();
			//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
			var url = "<?php echo base_url();?>admin.php/zzb_dengji/index.shtml";
			parent.parent.my_confirm(
					"确认删除选中等级？",
					ajax_url,
					url);
		
	}	
}

</script>
       
  
<br/>
<br/>
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
党员等级选择第一个符合分数区间的等级。
</div>


</body>
</html>




<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>