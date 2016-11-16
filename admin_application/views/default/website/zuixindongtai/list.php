<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css" />   
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" />   
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
        
<script>
BUI.use('common/page');
</script>     
    </head>
    
    
    <body>
    

    <div class="definewidth">
<table width="100%"><tr><td>
<?php 
echo "位置：<a href='infolist.shtml'>所有栏目</a>";
foreach($parent_list as $v){
	echo " > ";
	if($v["listpage"]==""){
		echo "<a href=\"infolist.shtml?typeid=".$v["id"]."\">";
	}
	else{
		echo "<a href=\"".site_url($v["listpage"])."?typeid=".$v["id"]."\">";
	}
	echo $v["title"];
	echo "</a>";
}
?>
</td>
<td>
<form method="get" >
<input type="hidden" name="typeid" value="<?php echo $category_id;?>"/>
<input type="text"  name="search_title" id="search_title"
    class="abc input-default" 
    placeholder="关键字" style="margin-top:10px;width:150px; height:26px;"
    value="<?php echo $search_val['search_title'];?>"> 
<button type="submit" class="btn btn-primary" >查询</button>
</form>       
</td>
<td align="right" valign="middle">
<?php 
if(is_array($category_model)){
?>
<a href="<?php echo site_url("website_category/edit");?>?id=<?php  echo $category_model["id"];?>&backurl=<?php echo urlencode(get_url()); ?>" class='button' title='编辑栏目:<?php echo $category_model["title"];?>'>管理栏目</a>
<?php 
}
?>
</td>
</tr>
</table>
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
删除信息涉及到已答题或已浏览加分的记录，建议信息分布后有浏览记录的信息尽量不要删除。
</div>
        <table class="table table-bordered table-hover m10 sortable">
            <thead>
                <tr>
                 <th width="10">ID</th>
                 <th width="30">最新</th>
                 <th width="80">期数</th>
                 <th >标题</th>
                 <th width="120">创建时间</th>
                 <th width="80">发布人</th>
                 <th width="120">操作
<a  class="btn btn-success page-action" id="addnew" href="#" title="添加<?php echo $modeltitle;?>" data-href="<?php if($addpage==""){?>/admin.php/Website_category/addinfo.shtml?typeid=<?php echo $category_id;?><?php } else{ echo site_url($addpage); }?>?typeid=<?php echo $category_id;?>" data-id='<?php echo __CLASS__.$category_id;?>'>添加信息</a>                 
                 </th>
                </tr>
            </thead>
            <tbody  id="result_">
            <?php
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".($v["isnew"]==1?"是":"否")."</td>"; 
            	echo "<td>".$v["qishu"]."</td>";
            	echo "<td>".$v["title"]."</td>";
            	echo "<td>".date("Y-m-d H:i",$v["post"])."</td>";
            	echo "<td>".$v["username"]."</td>";
            	echo "<td>";
            	echo "<a class='page-action icon-edit' title='编辑:".$v["title"]."' href='#'";
        		echo " data-href='".($editpage==""? site_url("Website_category/editinfo")."?id=".$v["id"]: site_url($editpage)."?id=".$v["id"])."' data-id='".__CLASS__.$v["id"]."' ></a>";
              	
            	 
            	echo "</td>";
            	echo "</tr>";
            }
            ?>
            
            </tbody>  

        </table>
       
        
        <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>
        </div>
        <div>
        <input type="hidden" name="selid" id="selid" value=""/>
       <button class="button" onclick="selall()">全选</button>
       <button class="button" onclick="selall2()">反选</button>
       <button class="button button-danger" onclick="return godel()">删除</button>
            
        </div>
        <div style="float:none;clear:both;"></div>    
</div>    
    </body>
    </html>
<script>
                
            
//alert(BUI.Tab.NavTabItem);
//alert(top.topManager.get);
</script>  
<script>
var chkyes = false;
function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{	

		/*
		检查删除的动态是否有答题记录
		*/
		var ajax_url2 = "<? echo site_url("zuixindongtai/chkdydt_history");?>?idlist="+$("#selid").val();
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
		var ajax_url = "/admin.php/zuixindongtai/delinfo.shtml?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>admin.php/zuixindongtai/infolist.shtml?typeid=<?php echo $category_id;?>";
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
