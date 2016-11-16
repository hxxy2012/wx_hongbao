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
	seltel = parent.$("#zzb_dy_dati_id").val();
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
			$("#tongji_span").html("选中："+seltel.split(",").length+"条，<span style='cursor:pointer' onclick='cleartel()'>清空</span>，<span style='cursor:pointer' onclick='selall()'></span>");
		}
		else{
			$("#tongji_span").html("");
		}
	}
	else{
		$("#tongji_span").html("<span style='cursor:pointer' onclick='selall()'></span>");
	}
	
}
function cleartel(){
	//$("input[name='telbox']").checked=false;
	 $("input[name='telbox']").removeAttr("checked");
	parent.$("#zzb_dy_dati_id").val("");
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
	parent.$("#zzb_dy_dati_id").val(tel);
	parent.$("#dati_title").html(obj.title);	
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
       
        <th>编号</th>        
        <th>标题</th>
        <th>创建人</th> 
        <th>创建时间</th>      
		<th width='80'>有无被调用</th>
		<th width='30'>选中</th>		        
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr  onclick='seltr(".$v["id"].")'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";            	            	
            	echo "<td>".$v["username"];
            	echo "</td>";
            	echo "<td>".$v["create_date"]."</td>";
            	echo "<td>".($v["art_title"]==""?"无":"<span title='".$v["art_title"]."'>有</span>")."</td>";            	            	
            	echo "<td><input type='radio' name='idbox' id='id".$v["id"]."' value='".$v["id"]."' title=\"".$v["title"]."\" onclick=\"addone('".$v["id"]."',this);\">";
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