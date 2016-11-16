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

   <!--script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/layer/layer.js"></script-->
 
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

</head>
<body class="definewidth">

<div class="form-inline definewidth m20" >
<form method="get" >
关键字：
    <input type="text"  name="sel_title" id="search_title"
    class="abc input-default" 
    placeholder="附件标题" 
    value="<?php echo $search_val['title'];?>"
    style="width:200px;"
    />
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
    


<a class="btn btn-success" id="addnew" href="<?php echo site_url("zcq_zijin/fujian_add")."?backurl=".urlencode(get_url());?>">新增<span class="glyphicon glyphicon-plus"></span></a>
  
</form>    
</div>

<?php
if(count($list)>0){
?>
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>  
        <th>附件名称</th> 
        <th width='60'>操作</th>     
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
            	echo "<td>";
			    echo "	
<a class='icon-edit page-action'
data-id='zijin_fujian_".$v["id"]."'   			 
data-href='".(site_url("zcq_zijin/fujian_edit")."?ls=".urlencode(get_url())."&id=".$v["id"])."'    			
   			href=\"#\" id='zijin_list_".$v["id"]."'  title=\"修改附件编号".$v["id"]."\"></a>";				
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
BUI.use('common/page');
function godel(){
	var ids = $("#selid").val();

	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{


        BUI.use('bui/overlay',function(Overlay){
            var dialog = new Overlay.Dialog({
                title:'提示信息',
                width:250,
                height:100,
                bodyContent:'<p>确认删除？</p>',
                success:function () {
                    $.ajax({
                        type: "GET",
                        url: "<?php echo site_url("zcq_zijin/fujian_del");?>",
                        data: {idlist:$("#selid").val()},
                        dataType: "json",
                        async:true,
                        success: function(data){
                            if(data.length>0){
                                var msg = ("申报类型【"+data[0].typetitle+"】引用了<br/>附件【"+data[0].fujian_title+"】<br/>请先删除【"+data[0].typetitle+"】附件");
                                //layer.msg(msg,{time:20000});
                               // layer.alert(msg);
                                BUI.Message.Alert(msg,'warning');
                                window.setTimeout("window.location.reload()",20000);
                            }
                            else{
                                BUI.Message.Alert("删除成功",'success');
                                window.setTimeout("window.location.reload()",1000);
                            }
                        }
                    });
                    this.close();
                }
            });
            dialog.show();
        });


	}	
}




 
 



 
		  
</script>
