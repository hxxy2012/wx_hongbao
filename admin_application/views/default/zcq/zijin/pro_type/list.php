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
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
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
关键字：
    <input type="text"  name="sel_title" id="search_title"
    class="abc input-default" 
    placeholder="类型标题"
    value="<?php echo $search_val['title'];?>"
    style="width:200px;"
    />
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
    


<a class="btn btn-success" id="addnew" href="<?php echo site_url("zcq_pro_type/add")."?backurl=".urlencode(get_url());?>">新增<span class="glyphicon glyphicon-plus"></span></a>
  
</form>    
</div>

<?php
if(count($list)>0){
?>
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>  
        <th>申报类型</th>
        <th width="80">是否可见</th>
        <th width="135">时间段</th>
        <th>备注</th>
        <th width='60'>操作</th>     
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
                echo "<td>".($v["isshow"]==1?"可见":"<b>否</b>")."</td>";
                echo "<td>";
                echo date("Y-m-d H:i",$v["starttime"])."<br/>";
                echo date("Y-m-d H:i",$v["endtime"]);
                echo "</td>";
                echo "<td>".$v["beizhu"]."</td>";
            	echo "<td>";
			    echo "	
<a class='icon-edit page-action'
data-id='zcq_pro_type_".$v["id"]."'   			 
data-href='".(site_url("zcq_pro_type/edit")."?ls=".urlencode(get_url())."&id=".$v["id"])."'    			
   			href=\"#\" id='zcq_pro_type_list_".$v["id"]."'  title=\"修改项目类型编号".$v["id"]."\"></a>";
				echo "</td>";                          	            	             	
            	echo "</tr>";
            	echo "\n";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  
  
<table border="0" style="margin:0px; padding:0px;" width="100%"><tr><td style="border:0px;" align="left">
<input type="hidden" name="selid" id="selid"  value=""/>

       <button class="button" onclick="selall()" >全选</button>
       <button class="button" onclick="selall2()" >反选</button>           
       <button class="button button-danger" onclick="godel()">删除</button>

</td>
<td style="border:0px;" align="right">  
    
       
  <div id="page_string" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>
</td>
</tr></table>  
<?php
}
else{
	echo "<div style='text-align:center;'>";
	echo "暂无信息";	
	echo "</div>";
}
?>

</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
function delchk(){
    pass = true;
    $.ajax({
        type: "GET",
        url: "<?php echo site_url("zcq_pro_type/chkdel");?>",
        data: {idlist:$("#selid").val()},
        cache: false,
        dataType: "text",
        async:false,
        success: function(msg) {
            if(msg>0){
                layer.msg(
                    "选中的类型中已有申报，不能删除，请先删除申报信息",
                        {
                            time:2000
                        }
                    );
                pass = false;
            }
        },
        beforeSend: function() {

        },
        error: function() {
            alert('服务器繁忙请稍。。。。');
        }

    });
    return pass;
}
function godel(){
	var ids = $("#selid").val();

	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{						
        if(!delchk()){
            return false;
        }
		var ajax_url = "<?php echo site_url("zcq_pro_type/del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/zcq_pro_type/index";
		parent.parent.my_confirm(
				"确认删除选中项目？",
				ajax_url,
				url);
	}	
}




 
 



 
		  
</script>
