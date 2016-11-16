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

    <ul class="ul_head">
        <li><a class="btn btn-success  page-action" id="" title='新增品牌'  data-href="<?php echo site_url("brand/add");?>" data-id=''  href="#">新增品牌</a></li>
        <li><a href="#" class="button button-warning" onclick="window.location.reload();" name="btn_flush">刷新页面</a></li>
        <li>
            <form method="get" >
                <input type="text"  name="search_title" id="search_title" class="abc input-default" placeholder="标题" style="width:150px; height:26px;" value="<?php echo $search_val['search_title']; ?>"> 
                <button type="submit" class="btn btn-primary" >查询</button>
            </form>  
        </li>
    </ul>
    <style>
        ul.ul_head li{float: left;margin-right:10px;}
    </style>
    
    


</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th width="100">标题</th>   
        <th>全称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="result_">
          <?php
               $total = count($list);
               $i=$total;
                foreach($list as $v){
                    echo "<tr onclick='seltr($(this))'>";
                    echo "<td style='display:none;'>".$v['guid']."</td>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$v["title"]."</td>";
                    echo "<td>".$v["fulltitle"]."</td>";
                    echo "<td>
                    <a class='page-action ' data-href='".site_url('brand/edit')."?id=".$v["guid"]."' href=\"#\" data-id='".__CLASS__.$v["guid"]."' title=\"编辑".$v["title"]."\">【编辑】</a>

                    <a  class='page-action' id='' href='#' data-href='".site_url('brand_proclass/index')."?brand_id=".$v["guid"]."'   title='品牌产品分类列表'>【品牌产品分类列表】</a>

                    <a  class='page-action' id='' href='#' data-href='".site_url('brand_product/add')."?brand_id=".$v["guid"]."'   title='添加产品'>【添加产品】</a>
                    <a  class='page-action' id='' href='#' data-href='".site_url('brand_product/index')."?brand_id=".$v["guid"]."'   title='产品列表'>【产品列表】</a>
					
   					<a  class='page-action' id='' href='#' data-href='".site_url('brand_news_category/category_list')."?brandid=".$v["guid"]."'  title='新闻分类，进入查看新闻列表'>【新闻分类】</a>
			   		<a  class='page-action' id='' href='#' data-href='".site_url('brand_news_category/category_add')."?brandid=".$v["guid"]."'   title='添加新闻分类'>【添加新闻分类】</a>					   	
   		
   		
                    ";
                    echo "</td>";                          	            	             	

                    echo "</tr>";
                    $i--;
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


<script src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/selall.js"></script>
<script>
function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{	
		
		
							
		var ajax_url = "<?php echo site_url("brand/del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		//var url = "<?php echo base_url();?>admin.php/Website_category/index.shtml";
		/*
		parent.parent.my_confirm(
				"确认删除选中信息？",
				ajax_url,
				url);
		*/
		BUI.Message.Confirm("确认删除选中信息",function(){ 				
			$.ajax({
		        type: "get",
		        url: ajax_url,
		        data: '',
		        cache: false,
		        dataType: "text",
		        async:false,
		        success: function(data) {                                
		           window.location.reload();
		        },
		        beforeSend: function() {
		            //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
		        },
		        error: function(a,b,c,d) {
		        	//alert(c);
		            alert('服务器繁忙请稍。。。。');
		        }
	
		    });
		}	
	,'question'); 			
	}	
}
function seltr(trobj){
	var tdArr = trobj.children();	
	var id = tdArr.eq(0).html();
	var ids = $("#selid").val();
	
	if(ids==""){
		ids = id;
		trobj.css('background-color','#cccccc');
	}
	else{
		var idarr = ids.split(",");
		var isexists = false;
		
		for(i=0;i<idarr.length;i++){
			if(idarr[i]==id){
				isexists=true;
				
				idarr.splice(i,1);
				break;
			}
		}		
		if(isexists){						
			trobj.css('background-color','');
			var tmpid = "";
			for(i=0;i<idarr.length;i++){
				tmpid += tmpid==""?idarr[i]:(","+idarr[i]);
			}
			ids = tmpid;
		}
		else{
			ids+=","+id;
			trobj.css('background-color','#cccccc');
		}
	}
	$("#selid").val(ids);
}
function selall(){
	$("#selid").val("");
	ids = "";
	$("#result_").find("tr").each(function(){
		$(this).css('background-color','#cccccc');
		 var tdArr = $(this).children();			 
		 id = tdArr.eq(0).html();
		 if(ids==""){
			ids = id;
		 }
		 else{
			ids += ","+id;
		 }
	});
	$("#selid").val(ids);	
}
function selall2(){
	var ids = $("#selid").val();
	var ids2 = "";//记录反选ID
	$("#result_").find("tr").each(function(){
		if(ids==""){
			selall();
		}
		else{
			ids=','+ids+',';
		}
		var tdArr = $(this).children();			 
		id = tdArr.eq(0).html();
		
		if(ids.indexOf(","+id+",")>-1){
			$(this).css('background-color','');			
		}
		else{
			$(this).css('background-color','#cccccc');		
			 if(ids2==""){
					ids2 = id;
				 }
				 else{
					ids2 += ","+id;
				 }
		}
	});	
	$("#selid").val(ids2);	
}
</script>




