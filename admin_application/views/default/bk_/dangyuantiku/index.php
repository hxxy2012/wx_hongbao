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
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="主题" 
    value="<?php echo $search_val['search_title'];?>">     
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
<?php if($isadd){?>      
    <a  class="btn btn-success" id="addnew" href="<?php echo site_url("dangyuantiku/add")."?backurl=".urlencode(get_url());?>">新增主题<span class="glyphicon glyphicon-plus"></span></a>
<?php }?>     
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>主题</th>
        <th width="120">创建时间</th>
        <th width='60'>是否隐藏</th>
        <th width='60'></th>               
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
            	echo "<td>".$v["create_date"];
            	echo "</td>";
            	echo "<td>".($v["isshow"]==0?"是":"否")."</td>";
            	
            	echo "<td>";
            	echo "<a class='page-action icon-edit' data-href='".site_url('dangyuantiku/edit')."?id=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='".__CLASS__.$v["id"]."' id='open_edit_".$v["id"]."' title=\"编辑".$v["title"]."\"></a>";            	
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
<?php if($isdel){?>     
       <button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button>
     
       <button class="button button-danger" onclick="godel()">删除</button>
<?php }?>       
       
  
<?php 
if(!is_super_admin()){
?>  
<br/>
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
二级管理员不能删除，只有一级管理员才有权限。
</div>  
<?php 
}
?>  


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});

var chkyes=false;

function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{	

		/*
		检查删除的动态是否有答题记录
		*/
		var ajax_url2 = "<? echo site_url("dangyuantiku/chkdydt_history");?>?idlist="+$("#selid").val();
		$.ajax({
	        type: "get",
	        url: ajax_url2,
	        data: '',
	        cache: false,
	        dataType: "text",
	        async:false,
	        success: function(data) {                                
	           if(data!=""){
		           chkyes = false;
		           parent.parent.tip_show(data,2,20000);
	           }
	           else{
	        	   chkyes = true;
	           }
	        },
	        beforeSend: function() {
	            //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
	        },
	        error: function(a,b,c,d) {
	        	//alert(c);
	            alert('检查服务器繁忙请稍。。。。');
	        }

	    });				
		if(!chkyes){
			return false;
		}				

		
		var ajax_url = "/admin.php/dangyuantiku/del.shtml?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>admin.php/dangyuantiku/index.shtml";
		parent.parent.my_confirm(
				"确认删除选中试卷？",
				ajax_url,
				url);
	}	
}

</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>