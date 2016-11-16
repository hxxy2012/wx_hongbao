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
<div class="form-inline definewidth m20" >
<?php
if(!empty($_GET["pid"]))
{
?>
<a class="btn btn-primary" id="addnew" href="<?php echo site_url("dangzuzhi/index"); ?>">
<?php echo $dangzuzhi["title"];?>
</a>
<a class="btn btn-warning" href="<?php echo site_url("dangzuzhi/index"); ?>">返回</a>
<?php 
}
?> 
<a class="btn btn-success" id="addnew" href="<?php echo site_url("dangzuzhi/add");?>?pid=<?php echo empty($_GET["pid"])?"":$_GET["pid"];?>">新增党组织</a>
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>党组织</th>   
        <th>创建时间</th>
        <th width="50">顺序</th>     
        <th width='80'>操作</th>
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
            	echo "<td>".$v["create_date"];            	
            	echo "<td>".$v["orderby"]."</td>";            	
            	echo "<td>
				<a class='page-action icon-edit' data-href='".site_url('dangzuzhi/edit')."?id=".$v["id"]."' href=\"#\" data-id='".__CLASS__.$v["id"]."' title=\"编辑".$v["title"]."\"></a>
    		";
            	if($v["subcount"]>0){
    				echo "<a href=\"".site_url('dangzuzhi/index')."?pid=".$v["id"]."\"  title='查看下级' class='icon-tasks' ></a>";
				}			            	          
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
     
       <button class="button button-danger" onclick="godel()">删除</button>
     


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});
var dycount = 0;
function chkcount(){
	var ids = $("#selid").val();
	var ajax_url = "/admin.php/dangzuzhi/count.shtml?dzzid="+ids;
	$.ajax({
        type: "get",
        url: ajax_url,
        data: '',
        cache: false,
        dataType: "text",
        async:false,
        success: function(data) {                                
        	dycount = data;
        },
        beforeSend: function() {
            //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
        },
        error: function() {
            alert('服务器繁忙请稍。。。。');
        }

    });			
}

function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{
		chkcount();
		if(dycount==0){						
			var ajax_url = "/admin.php/dangzuzhi/del.shtml?idlist="+$("#selid").val();
			//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
			var url = "<?php echo base_url();?>admin.php/dangzuzhi/index.shtml?pid=<?php echo empty($_GET["pid"])?"0":$_GET["pid"];?>";
			parent.parent.my_confirm(
					"确认删除选中党组织？",
					ajax_url,
					url);
		}
		else{
			parent.parent.tip_show('操作失败：党组织下有党员，不能删除党组织，请先删除党员。',2,3000);		
		}
			
	}	
}

</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>