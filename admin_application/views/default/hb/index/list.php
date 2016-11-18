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
关键字：
    <input type="text"  name="search_title" id="search_title"
    class="abc input-default" 
    placeholder="本轮标题" 
    value="<?php echo $search_val['title'];?>"
    style="width:200px;"
    />
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 
    


<a class="btn btn-success" id="addnew" href="<?php echo site_url("hb_index/add")."?backurl=".urlencode(get_url());?>">新增<span class="glyphicon glyphicon-plus"></span></a>
  
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='30'>编号</th>  
        <th width="270">红包标题</th> 
        <th width="60">正在进行</th> 
        <th width="80">随机金额</th>
        <th width='60'>红包总数</th>
        <th width="60">总金额</th>            
        <th width='80'>红包最小金额</th> 
        <th width='60'>操作</th>     
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
            if (count($list)<=0) {
            	echo "<tr><td style='text-align:center;' align='center' colspan='8'>暂无内容</td></tr>";
            }
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
				echo "<td>".($v["curr"]?"是":"否")."</td>";
				echo "<td>".($v["suiji"]?"固定":"随机")."</td>";
				echo "<td>".$v["hongbao_shu"]."</td>"; 
				echo "<td>".$v["jine"]."</td>"; 
				echo "<td>".$v["qibu_jine"]."</td>";      				
            	echo "<td>";
				echo "<a class='page-action icon-edit' data-href='".site_url("hb_index/edit")."?backurl=".(urlencode(get_url()))."&id=".$v["id"]."' href=\"#\" data-id='open_hb_edit_".$v["id"]."' id='open_hb_edit_".$v["id"]."' title=\"编辑".$v["title"]."的红包\"></a>&nbsp;";
				echo "</td>";                          	            	             	
            	echo "</tr>";
            	echo "\n";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
  
  
<table border="0" style="margin:0px; padding:0px;" width="100%"><tr><td style="border:0px;" align="left">
<input type="hidden" name="selid" id="selid" style=" <?php echo $isjichu?'display:none;':'';?>" value=""/> 
<button class="button" onclick="selall()" style=" <?php echo $isjichu?'display:none;':'';?>">全选</button>
<button class="button" onclick="selall2()" style=" <?php echo $isjichu?'display:none;':'';?>">反选</button>           
<button class="button button-danger" onclick="godel()" style=" <?php echo $isjichu?'display:none;':'';?>">删除</button>
</td>
<td style="border:0px;" align="right">  
    
       
  <div id="page_string" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>  
  </div>
</td>
</tr></table>  


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
$(function () {
	
});

function goedit(uid){
	alert($("#open_edit_"+uid));
	//window.location.href="<?php echo site_url("user/edit");?>?";
	$("#open_edit_"+uid).click();		
}

function godel(){
	var ids = $("#selid").val();
	/*if(!chkdel()){
		return false;
	}*/
	if(ids==""){		
		parent.parent.tip_show('没有选中，请点击某行信息。',2,1000);
	} else {						
		var ajax_url = "<?php echo site_url("hb_index/del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/hb_index/index";
		/*parent.parent.my_confirm(
				"确认删除选中红包？",
				ajax_url,
				url);*/
		BUI.Message.Confirm("确认删除选中红包活动？",function(){
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
		            //$("#result_").html('<font color="red"><img src="http://www.xdqywz.com:8086//admin_application//views/static/Images/progressbar_microsoft.gif"></font>');
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

function chkdel(){
		var ajax_url = "<?php echo site_url("hb_index/delcheck");?>?idlist="+$("#selid").val();
		i=0;
		name = "";
		$.ajax({
				url:ajax_url,
				dataType: "json",
				type: "GET",			
				async:false,
				success: function(data){
					if(data.err=="no"){
						i=1;
						name = data.title;
					}

				},
				error:function(a,b,c){
					
				}
		});
		if(i>0){
			alert(name+"有申报信息，请删除申报信息再删除项目。");
			return false;
		}
		return true;
}


 
 

	function opentab(id){
		/*
		if(top.topManager){
		  //打开左侧菜单中配置过的页面
		  top.topManager.openPage({
			moduleId:'224',
			id : 238,
			search : 'id='+id,
			reload : true
		  });
		}
		*/
	}

 
		  
</script>
