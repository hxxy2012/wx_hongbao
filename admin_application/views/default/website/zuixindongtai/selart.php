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
var seltel = "";

function GetNewTel(){
	seltel = parent.$("#website_model_dangyuandati_id").val();
}
GetNewTel();
function chktel(tel){
	GetNewTel();
	//alert(","+seltel+",");
	if(tel!=""){
		if((","+seltel+",").indexOf(","+tel+",")>=0){
			return true;
		}
		else{
			return false;
		}
	}
}
function tongji(){
	GetNewTel();	
	if(seltel!="")
	{
		if(seltel.split(",").length>0){
			$("#tongji_span").html("选中："+seltel.split(",").length+"条，<span style='cursor:pointer' onclick='cleartel()'>清空</span>，<span style='cursor:pointer' onclick='selall()'>全选</span>");
			parent.$("#dati_title").html("已选");	
		}
		else{
			$("#tongji_span").html("");
			parent.$("#dati_title").html("");	
		}
		
	}
	else{
		parent.$("#dati_title").html("");	
		$("#tongji_span").html("<span style='cursor:pointer' onclick='selall()'>全选</span>");
	}
	
}
function cleartel(){
	//$("input[name='telbox']").checked=false;
	 $("input[name='idbox']").removeAttr("checked");
	parent.$("#website_model_dangyuandati_id").val("");
	parent.$("#dati_title").html("");	
	tongji();
}

function selall(){	
	$("input[name='idbox']").attr("checked","checked");
	$("input[name='idbox']").each(function(){
				
	    addone($(this).attr('value'),document.getElementById($(this).attr("id")));
		});	
	tongji();	
}

function seltr(id){
	//document.getElementById("tel"+id).checked = !document.getElementById("tel"+id).checked;
	//addtel(document.getElementById("tel"+id).value,document.getElementById("tel"+id));
}

function addone(tel,obj){

	GetNewTel();		
	if(obj.checked)
	{
		if((","+seltel+",").indexOf(','+tel+',')>=0){
			
		}
		else{
			if(seltel==""){
				seltel = tel;
			}
			else{
				seltel += ","+tel;
			}
			parent.$("#website_model_dangyuandati_id").val(seltel);
		}
		parent.$("#dati_title").html("已选");	
	}
	else{
		var arr = seltel.split(",");
		var tmp = "";
		for(i=0;i<arr.length;i++){
			if(arr[i]!=tel){
				if(tmp==""){
					tmp  = arr[i];
				}
				else{	
					tmp += ","+arr[i];
				}
			}			
		}
		seltel = tmp;
		//alert(seltel);
		parent.$("#website_model_dangyuandati_id").val(seltel);
		if(seltel==""){
			parent.$("#dati_title").html("");	
		}
	}	
	tongji();	
}





</script>    
<script>
BUI.use('common/page');
</script>        
</head>
<body class="definewidth" style="padding: 0px;">


<div class="form-inline definewidth m20" style="padding: 0px;" >
<form method="get" >       
    <input type="text"  name="title" id="title"
    class="abc input-default" 
    placeholder="标题" style="width:80px;"
    value="<?php echo $search_val['title'];?>">    

    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;
    
    <span id="tongji_span"></span> 
  
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
       
        <th width="30">编号</th>        
        <th>标题</th>       
        <th width="120">创建时间</th>      		
		<th width='30'>选中</th>		        
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr(".$v["id"].")'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";            	            	            	
            	echo "<td>".date("Y-m-d H:i:s",$v["post"])."</td>";            	            	           
            	echo "<td><input type='checkbox' name='idbox' id='id".$v["id"]."' value='".$v["id"]."' title=\"".$v["title"]."\" onclick=\"addone('".$v["id"]."',this);\">";
            	echo "<script>
    		if(chktel('".$v["id"]."')){
    			document.getElementById('id".$v["id"]."').checked=true;
    		}    		
    		</script>";
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

   
       

<script>
tongji();
</script>

</body>
</html>




<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>