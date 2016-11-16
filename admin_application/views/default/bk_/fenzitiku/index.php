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
   <!--script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/copy/jquery.min.js"></script-->      
   <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/copy/ZeroClipboard.js"></script> 
 
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

$(document).ready(function(){
    ZeroClipboard.setMoviePath("/admin_application/views/static/Js/copy/ZeroClipboard.swf");     
    $("a[copyhttp]").each(function(){ 
        
        var clip = new ZeroClipboard.Client();      
        clip.setHandCursor(true);     
            
        var obj = $(this);    
        var id = $(this).attr("id");    
        var content = $(this).attr("copyhttp");         
        clip.setText(content);                       
        //这个是复制成功后的提示      
        clip.addEventListener( "complete", function(){      
        	parent.tip_show ("复制链接成功",1,1000);
        });
        clip.glue(id);
    });    	
	
});

function setcoptbtn(id){
	var url = "<?php echo  base_url() ;?>index.php/home/fztkview.shtml?id="+id;
	$("#copybtn").attr("dturl",url);			
    //window.setTimeout("$(\"#copybtn\").click();",1000);
	$("#copybtn").mousedown();

}
</script>        
</head>
<body class="definewidth">

<div class="form-inline definewidth m20" >
<form method="get" >       
    <input type="text"  name="username" id="username"
    class="abc input-default" 
    placeholder="试卷主题" 
    value="<?php echo $search_val['search_title'];?>">     
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
<?php if($isadd){?>      
    <a  class="btn btn-success" id="addnew" href="<?php echo site_url("fenzitiku/add")."?backurl=".urlencode(get_url());?>">新增试卷<span class="glyphicon glyphicon-plus"></span></a>
<?php }?>     
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>编号</th>
        <th>试卷主题</th>
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
            	echo "<a class='page-action icon-edit' data-href='".site_url('fenzitiku/edit')."?id=".$v["id"]."&backurl=".urlencode(get_url())."' href=\"#\" data-id='".__CLASS__.$v["id"]."' id='open_edit_".$v["id"]."' title=\"编辑".$v["title"]."\"></a>";
            	echo "<a class='icon-share' id=\"copy".$v["id"]."\" style='cursor:pointer'  copyhttp=\"".base_url()."index.php/home/fztkview.shtml?id=".$v["id"]."\" title='复制链接'></a>";            	
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


function godel(){
	var ids = $("#selid").val();
	
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	}
	else{	
		var ajax_url = "/admin.php/fenzitiku/del.shtml?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>admin.php/fenzitiku/index.shtml";
		parent.parent.my_confirm(
				"确认删除选中试卷？",
				ajax_url,
				url);
		
	}	
}

</script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>