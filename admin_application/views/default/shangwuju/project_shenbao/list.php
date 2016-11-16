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
    <input type="text"  name="search_title" id="search_title"
    class="abc input-default" 
    placeholder="项目名称" 
    value="<?php echo $search_val['title'];?>"
    style="width:200px; display:none;"
    />
<select name="proid">
<option value="0">选择一个栏目</option>
<?php foreach($prolist as $k=>$v){
	echo "<option value='".$v["id"]."'";
	echo $proid==$v["id"]?" selected ":"";
	echo ">\n";
	echo $v["title"];
	echo "\n</option>\n";
}
?>
</select>
<select name="checkstatus" style="<?php echo $isadmin?"":"display:none;";?>">
<option value="">审核状态</option>
<?php
foreach($checkstatus as $k=>$v){
	if($k!="99"){
		echo "<option value='".($k==0?'null':$k)."' ".(($k==0?"null":$k)==$checkstatus_value?" selected ":"").">";
		echo $v;
		echo "</option>";
	}
}
?>	
</select>
<?php echo $checkstatus_value;?>
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 




  
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th width="115">审核状态</th>  
        <th>项目名称</th> 
        <th width="120">申报模板</th> 
        <th width="150">申报单位</th>         
        <th width='100'>提交时间</th>
        <th width='60'>操作</th>     
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["statusname"]."</td>";
				echo "<td>".$v["proname"]."</td>";
				echo "<td>".($v["protemp"])."</td>";
				echo "<td>".$v["danwei"]."</td>";       				
            	echo "<td>".date("Y-m-d H:i",$v["create_time"]);       				
            	echo "</td>";
            	echo "<td>";
			    echo "	
<a class='page-action icon-check shenbao_btn' href=\"#\" id='check_shenbao_list_".$v["id"]."' 
data-href='".site_url("swj_shenbao/check_shenbao")."?id=".$v["id"]."'
 title=\"审核".$v["danwei"]."的申报资料\"></a>";				
				echo " ";
				if($isadmin){
				echo "
				<a class='page-action icon-random' data-href='".site_url("swj_project_shenbao_check_log/index")."?ls=".urlencode(get_url())."&id=".$v["id"]."' href=\"#\" data-id='open_swj_project_shenbao_check_log_".$v["id"]."' id='open_swj_project_shenbao_check_log_".$v["id"]."' title=\"为".$v["danwei"]."设置退回状态\"></a>";								              }
				echo "</td>";                          	            	             	
            	echo "</tr>";
            	echo "\n";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  
  
<table border="0" style="margin:0px; padding:0px;" width="100%"><tr><td style="border:0px;" align="left">

   <input type="hidden" name="selid" id="selid" style=" <?php echo $isjichu?'display:none;':'';?>" value=""/>
   <?php
   if($isadmin){
   ?>
       <button class="button" onclick="selall()" style=" <?php echo $isjichu?'display:none;':'';?>">全选</button>
       <button class="button" onclick="selall2()" style=" <?php echo $isjichu?'display:none;':'';?>">反选</button>           
       <button class="button button-danger" onclick="godel()" style=" <?php echo $isjichu?'display:none;':'';?>">删除</button>
<?php
   }
   ?>
</td>
<td style="border:0px;" align="right">  
    
       
  <div id="page_string" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>
</td>
</tr></table>  
<div style="text-align:right;">

<?php if($isadmin){?>
<span class="icon-check"></span>:审核&nbsp;&nbsp;
<span class="icon-random"></span>:退回
<?php
}
else{
echo '<span class="icon-check"></span>:查看&nbsp;&nbsp;';	
}
?>
<br/>
</div>
</body>
</html>


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
		if( !chkdel() ){
			return false;
		}	
		
		var ajax_url = "<?php echo site_url("swj_shenbao/del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/swj_shenbao/index";
		parent.parent.my_confirm(
				"确认删除选中申报？",
				ajax_url,
				url);
		
	}	
}

function chkdel(){
		var ajax_url = "<?php echo site_url("swj_shenbao/delcheck");?>?idlist="+$("#selid").val();
		i=0;
		name = "";
		$.ajax({
				url:ajax_url,
				dataType: "json",
				type: "GET",			
				async:false,
				success: function(data){
					i=data.length;	
					if(i>0){
						name = data[0].danwei;
					}
				},
				error:function(a,b,c){
					
				}
		});
		if(i>0){
			alert(name+"状态为给予扶持，不能删除，请先退回。");			
			return false;
		}
		return true;
}
</script>
