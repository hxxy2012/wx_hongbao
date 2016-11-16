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
    placeholder="主题/公司名称/联系人/手机" 
    value="<?php echo $search_val['title'];?>"
    style="width:200px;"
    />
    <input type="hidden" id="action" name="action" value="">
    <select name="huodong_id" id="huodong_id">
    	<?php foreach ($list_hdzt as $key => $value): ?>
    		<option value="<?php echo $value['id']?>" <?php if($value['id']==$search_val['huodong_id']) echo 'selected';?>><?php echo $value['title']?></option>
    	<?php endforeach ?>
    </select>
    <button type="submit" id="query" class="btn btn-primary" >查询</button>
    <button type="submit" id="export" class="btn btn-success" >导出EXCEL</button>
    <!-- <a class="btn btn-success" id="addnew" href="<?php echo site_url("zcq_hdbaoming/export")."?backurl=".urlencode(get_url());?>">导出EXCEL<span class="glyphicon glyphicon-plus"></span></a> -->


<!-- <a class="btn btn-success" id="addnew" href="<?php echo site_url("zcq_hdbaoming/add")."?backurl=".urlencode(get_url());?>">新增<span class="glyphicon glyphicon-plus"></span></a> -->
  
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='10'>编号</th> 
        <th width="100">活动主题</th>   
        <th width="100">公司名称</th> 
        <th width="20">联系人</th> 
        <th width="30">手机</th>
        <th width='30'>报名时间</th>     
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
				echo "<td>".$v["company"]."</td>";
				echo "<td>".$v["realname"]."</td>";
				echo "<td>".$v["tel"]."</td>";
				echo "<td>".date('Y-m-d H:i:s', $v['createtime'])."</td>";       				                         	            	             	
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
	//点击导出excel将action值赋值为export
	$("#export").click(function(){
		$('#action').val('export');
	});
    $('#query').click(function () {
        $('#action').val('');
    });
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
		var ajax_url = "<?php echo site_url("zcq_hdbaoming/del");?>?idlist="+$("#selid").val();
		// alert(ajax_url);return false;
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/zcq_hdbaoming/index";
		/*parent.parent.my_confirm(
				"确认删除选中活动？",
				ajax_url,
				url);*/
		BUI.Message.Confirm("确认删除选中报名信息？",function(){
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
		var ajax_url = "<?php echo site_url("zcq_hdbaoming/delcheck");?>?idlist="+$("#selid").val();
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
