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
    placeholder="问题名称" 
    value="<?php echo $search_val['title'];?>"
    style="width:200px;"
    />&nbsp;&nbsp;
<!-- 问题选项：
	<select name="pid" id="pid">
		<option value="">请选择</option>
		<option value="1" <?php if($search_val['pid']==1)echo 'selected'?>>问题</option>
		<option value="2" <?php if($search_val['pid']==2)echo 'selected'?>>选项</option>
	</select>&nbsp;&nbsp; -->
调查问卷：
    <select name="diaocha_id" id="diaocha_id">
    		<option value="">请选择</option>
    	<?php foreach ($suv_list as $key => $value): ?>
    		<option value="<?php echo $value['id'];?>" <?php if($search_val['diaocha_id']==$value['id'])echo 'selected';?>>
    			<?php echo $value['title'];?>
    		</option>
    	<?php endforeach ?>
    </select>
    <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp; 

<a class="btn btn-success" id="addnew" href="<?php echo site_url("zcq_surveywt/add")."?diaocha_id=".$search_val['diaocha_id']."&backurl=".urlencode(get_url());?>">新增<span class="glyphicon glyphicon-plus"></span></a>
  
</form>    
</div>

<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='20'>编号</th>  
        <th width="130">问题名称</th> 
        <th width="130">调查问卷</th>
        <th width="40">题目类型</th>
        <th width="30">排序</th> 
        <th width="60">创建日期</th>
        <th width="60">更新日期</th>            
        <th width='40'>操作</th>     
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           	$arr_type = array(1=>'单选',2=>'多选',3=>'问答题');
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
				echo "<td>".$v["diaochatitle"]."</td>";
				if ($v['itemtype']) {
					echo "<td>".$arr_type[$v['itemtype']]."</td>";	
				} else {
					echo "<td>".'未选类型'."</td>";	
				}
				echo "<td>".$v["orderby"]."</td>";
				echo "<td>".date("Y-m-d",$v["create_time"])."</td>";
				echo "<td>".date("Y-m-d",$v["update_time"])."</td>";      				
            	echo "<td>";
				echo "<a class='page-action icon-edit' data-href='".site_url("zcq_surveywt/edit")."?backurl=".(urlencode(get_url()))."&id=".$v["id"]."' href=\"#\" data-id='open_survey_edit_".$v["id"]."' id='open_survey_edit_".$v["id"]."' title=\"编辑".$v["title"]."的问题\"></a>&nbsp;";
				// echo "<a class='page-action icon-list-alt' data-href='".site_url("zcq_surveywt/list")."?backurl=".(urlencode(get_url()))."&diaocha_id=".$v["id"]."' href=\"#\" data-id='open_survey_wt_list_".$v["id"]."' id='open_survey_wt_list_".$v["id"]."' title=\"查看".$v["title"]."的调查问卷问题\"></a>";
				if ($v['pid']==0&&$v['itemtype']!=3) {
					echo "<a class='page-action icon-plus' data-href='".site_url("zcq_surveywt/addxx")."?backurl=".(urlencode(get_url()))."&diaocha_id=".$v["diaocha_id"]."&id=".$v["id"]."' href=\"#\" data-id='open_survey_add_".$v["id"]."' id='open_survey_add_".$v["id"]."' title=\"添加".$v["title"]."的选项\"></a>&nbsp;";
				}
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
		var ajax_url = "<?php echo site_url("zcq_surveywt/del");?>?idlist="+$("#selid").val();
		//var url = "<?php echo $_SERVER['REQUEST_URI'];?>";
		var url = "<?php echo base_url();?>gl.php/zcq_surveywt/index";
		/*parent.parent.my_confirm(
				"确认删除选中问卷？",
				ajax_url,
				url);*/
		BUI.Message.Confirm("确认删除选中问题？",function(){
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
		var ajax_url = "<?php echo site_url("zcq_surveywt/delcheck");?>?idlist="+$("#selid").val();
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
