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
    placeholder="标题/发送者"
    value="<?php echo $search_val['title'];?>"
    style="width:200px;"
    />
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;
    <button type="button" class="btn btn-warning" onclick="window.location.reload(true);">刷新</button>



  
</form>    
</div>

<?php
if(count($list)>0){
?>
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>  
        <th>标题</th>
        <th width="150">发送人</th>
        <th width="100">发送时间</th>
        <th width='60'>操作</th>     
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>";
                echo $v["isread"]=="0"?"<b>":"";
                echo $v["title"];
                echo $v["isread"]=="0"?"</b>":"";
                echo "</td>";
                echo "<td>".$v["username"]."</td>";
                echo "<td>".date("Y-m-d H:i",$v["createtime"])."</td>";
            	echo "<td>";
			    echo "	
<a class='icon-folder-open page-action'
data-id=''mail_list_".$v["id"]."'   			 
data-href='".(site_url("zcq_mail/myview")."?ls=".urlencode(get_url())."&id=".$v["id"])."'    			
   			href=\"#\" id='mail_list_".$v["id"]."'  title=\"查看站内信编号".$v["id"]."\"></a>";
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
	echo "暂无站内信";
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
                        url: "<?php echo site_url("zcq_mail/del");?>",
                        data: {idlist:$("#selid").val()},
                        dataType: "text",
                        async:true,
                        success: function(data){
                            if(data.length>0){
                                if(data=="ok"){
                                    BUI.Message.Alert("删除成功",'success');
                                    window.setTimeout("window.location.reload()",1000);
                                }
                                //window.setTimeout("window.location.reload()",20000);
                            }
                            else{

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
