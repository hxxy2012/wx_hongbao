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
<form method="get" >       
    <input type="text"  name="typename" id="typename"
    class="abc input-default" 
    placeholder="类别名" 
    value="<?php echo $search_val['typename'];?>">    
   	

    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;
    
<?php if($isadd){?>      
    <a  class="btn btn-success" id="addnew" href="<?php echo site_url("fenshu_leibie/add")."?backurl=".urlencode(get_url());?>">新增类别<span class="glyphicon glyphicon-plus"></span></a>
<?php }?>       
   
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>类别名</th>
        <th>分数</th>
        <th>备注</th>
        <th width="150">创建时间</th>
        <th width="72">可删除</th>
        <th></th>         	
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["typename"]."</td>";
            	echo "<td>".$v["fenshu"];
            	echo "</td>";
            	echo "<td>".$v["beizhu"]."</td>";
            	echo "<td>".$v["createdate"]."</td>";
            	echo "<td>".($v["issystem"]==0?"可以":"不可以")."</td>";
            	echo "<td>";
            	echo "<a class='page-action icon-edit' data-href='".site_url('fenshu_leibie/edit')."?id=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='".__CLASS__.$v["id"]."' id='open_edit_".$v["id"]."' title=\"编辑".$v["typename"]."\"></a>";
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
		chkdel();
		if(!isdel){
			parent.parent.tip_show('您所选的类别有不能删除项。',2,2000);
		}
		else{				
			var ajax_url = "/admin.php/fenshu_leibie/del.shtml?idlist="+$("#selid").val();
			//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
			var url = "<?php echo base_url();?>admin.php/fenshu_leibie/index.shtml";
			parent.parent.my_confirm(
					"确认删除选中类别？",
					ajax_url,
					url);
		}
	}	
}
var isdel = false;
function chkdel(){
	var ids = $("#selid").val();
	var ajax_url = "/admin.php/fenshu_leibie/count.shtml?ids="+ids;
	$.ajax({
        type: "get",
        url: ajax_url,
        data: '',
        cache: false,
        dataType: "text",
        async:false,
        success: function(data) {                                      
        	isdel = !(data>0);
        },
        beforeSend: function() {
            //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
        },
        error: function() {
            alert('服务器繁忙请稍。。。。');
        }

    });			
}
</script>
       
  



</body>
</html>




<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>