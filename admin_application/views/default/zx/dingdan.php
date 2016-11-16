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
        <th>商品数量</th>
        <th>所属用户</th>
        <th>联系人</th>
        <th>手机</th>
        <th>地址</th>
        <th width="165">留言</th>
        <th width="150">创建时间</th>        
        <th></th>         	
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr($(this))'>";
            	echo "<td>".$v["dingdan_hao"]."</td>";
            	echo "<td>".$v["dd"]."</td>";
				echo "<td>".($v["username"]==""?"直接提交到管理后台":$v["username"])."</td>";
            	echo "<td>".$v["linkman"];
            	echo "</td>";
            	echo "<td>".$v["tel"]."</td>";
            	echo "<td>".$v["addr"]."</td>";
            	echo "<td>".$v["content"]."</td>";
            	echo "<td>".$v["create_time"]."</td>";            	
            	echo "<td>";
            	echo "<a class='icon-file'
				onclick='show_additem(\"".$v["dingdan_hao"]."\")'
				href=\"#\" data-id='".__CLASS__.$v["guid"]."' id='open_edit_".$v["guid"]."' title=\"查看".$v["dingdan_hao"]."\"></a>";
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
					
			var ajax_url = "<?php echo site_url("zx_dingdan/del");?>?idlist="+$("#selid").val();
			//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
			var url = "<?php echo site_url("zx_dingdan/index");?>";
			parent.parent.my_confirm(
					"确认删除？",
					ajax_url,
					url);
		
	}	
}


var dialog2
function show_additem(id){	
	
		var w = 800;
		var h = 500;
		 BUI.use('bui/overlay',function(Overlay){
	         dialog2 = new Overlay.Dialog({
	           title:'查看订单内容',
	           width:w,
	           height:h,
	           //配置文本
	           bodyContent:'<iframe id="ifr_additem" name="ifr_additem" src="<?php echo site_url("zx_dingdan/view")?>?id='+id+'" width="'+(w-10)+'"  height="'+(h-60)+'" frameborder="0" marginwidth="0" marginheight="0"  ></iframe>',
	           buttons:[
	                    
	                  ]
	         });
	       dialog2.show();
	       	
		});
	
}
</script>
       
  
<br/>
<br/>
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
在品牌商城提交的订单提交到后台，在用户商城提交的订单提交到用户后台，管理员可以查看
</div>


</body>
</html>




<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>