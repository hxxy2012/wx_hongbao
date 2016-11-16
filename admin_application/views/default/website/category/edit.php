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
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/admin.js"></script>
	
<link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
	
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
<body>

<form onsubmit="return postform()" arction=""  method="post" class="definewidth m10"
enctype="multipart/form-data"
id="myform">
<input type="hidden" id="id" name="id" value="<?php echo $model["id"];?>"/>
<input type="hidden" id="backurl" name="backurl" value="<?php echo $backurl;?>"/> 
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">名称：</td>
        <td>
       
<input 
type="text" name="title" 
placeholder="输入栏目名" 
required="true"
style="width:300px" value="<?php echo $model["title"];?>"/>


</td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">全称：</td>
        <td>
       
<input 
type="text" name="fulltitle" 
placeholder="输入栏目名" 
style="width:300px" value="<?php echo $model["fulltitle"];?>"/>

栏目在前台：
<input type="radio" name="isshow" value="1" <?php echo $model["isshow"]=="1"?"checked":"";?> />显示
<input type="radio" name="isshow" value="0" <?php echo $model["isshow"]=="0"?"checked":"";?> />隐藏

</td>
    </tr>
<tr>
<td width="10%" class="tableleft">
缩略图：
</td>
<td>
<?php if($model["thumb"]==""){?>	           	           
<input type="file" tips="格式：JPG|GIF|PNG|BMP" name="thumb" id="thumb" style="width:160px;" />
<?php }
else{
?>
<img style="border:#000 1px solid;cursor:pointer;"   src="/<?php echo $model["thumb"];?>" id="thumb_img" name="thumb_img" tips="点击图片即删除" width="100" height="60" />
<input type="hidden" name="thumb" value="<?php echo $model["thumb"];?>" />
<?php 
}?>
</td>
</tr>
<tr>
<td width="10%" class="tableleft">
模型：
</td>
<td>
<?php 
foreach($modellist as $v)
{
	echo "<input type='radio' id='model_id' name='model_id'
		".($v["id"]==$model["model_id"]?"checked":"")."	value='".$v["id"]."'/>".$v["title"]." ";	
}
	?>
栏目：
<select required="true" name="pid">
<option value="0">@@作为父栏目@@</option>
<?php 
foreach($categorylist as $v){
	echo "<option value='".$v["id"]."'";
	echo ($v["id"]==$model["pid"]?"selected":"")." >";
	echo $v["tree"];
	echo $v["title"];
	echo "</option>\n";
}
?>
</select>
</td>
</tr>
<tr>
<td width="10%" class="tableleft">
外链：
</td>
<td>
<input 
type="text" name="url" 
placeholder="输入链接" 
style="width:300px" value="<?php echo $model["url"];?>"/>
</td>
</tr>
<tr>
<td width="10%" class="tableleft">简述：</td>
<td>
<input 
type="text" name="beizhu" 
placeholder="输入简单描述" 
style="width:500px" value="<?php echo $model["beizhu"];?>"/>
</td>
</tr>
<tr>
<td width="10%" class="tableleft">描述：</td>
<td>
<textarea style="width:100%; height:150px" id="content" name="content" placeholder="描述"><?php echo $model["content"];?></textarea>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K) {
    window.editor = K.create('#content', {
        width: '100%',
        height: '400px',
        allowFileManager: false,
        allowUpload: false,
        afterCreate: function() {
            this.sync();
        },
        afterBlur: function() {
            this.sync();
        },
        extraFileUploadParams: {
            'cookie': '<?php echo $_COOKIE['admin_auth']; ?>'
        },
        uploadJson: "<?php echo site_url("website_category/upload"); ?>?action=upload&session=<?php echo session_id(); ?>"

                });
            });
</script>
</td>
    </tr>
    
	
	    	
    <tr>
        <td class="tableleft"></td>
        <td>
<input type="hidden" name="opener" id="opener" value="<?php echo $opener;?>"/>        
<button type="submit" class="btn btn-primary" id="btnSave">保存</button> &nbsp;&nbsp;

<button class="button button-danger" type="button" onclick="delcategory()">删除栏目</button>&nbsp;&nbsp;


<button class="button button-info" type="button" onclick="window.location.href='<?php echo $backurl;?>';">返回</button>
        </td>
    </tr>
</table>
<script>
function postform(){
	chkmodel();
	if(artcount>0){
		parent.parent.tip_show('栏目下有数据，请先删除数据，再选其他模型。',2,3000);
		return false;
	}
	if($("#myform").Valid()) {
		return true ;
	}
	else{	
		return false;
	}
	//return true;	
}
function delcategory(){
	var id = "<?php echo $model["id"];?>";
	
	 	BUI.Message.Confirm("删除栏目，先删除栏目下的新闻信息?",function(){                	
	 		var ajax_url = "<?php echo site_url("Website_category/delcategory");?>?id="+id;
			$.ajax({
	            type: "get",
	            url: ajax_url,
	            data: '',
	            cache: false,
	            dataType: "text",
	            //async:false,
	            success: function(data) {                                
	                //window.location.href="<?php echo $_SERVER['REQUEST_URI'];?>";
	                if(data=="news"){
		                //栏目下有记录，请先删除
	                	parent.parent.tip_show("删除失败:栏目下有新闻记录，请先删除新闻记录再删除栏目",2,5000);
	                	
	                }
	                else{
		                if($("#opener").val()=="no"){
		                	parent.parent.tip_show("栏目删除成功",1,2000);
	                		setTimeout("window.location.href='"+$("#backurl").val()+"';",2000);
		                }
		                else{
	                		parent.parent.tip_show("栏目删除成功",1,2000);
	                		setTimeout("parent.window.location.reload();",2000);
		                }
	                }
	            },
	            beforeSend: function() {
	                //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
	            },
	            error: function(a,b,c,d) {
	            	//alert(c);
	                alert('服务器繁忙请稍。。。。');
	            }

	        });	

	        	       		 
          },'question'); 	
}
var artcount = 0;
function chkmodel(){
	var old_model = "<?php echo $model["model_id"];?>";
	var new_model = $('input[name="model_id"]:checked').val();// $("input[name='model_id'][checked]").val();
	
	if(old_model!=new_model){
		//判断栏目下边有无文章，有就提示先删除文章，再转模型。
 		var ajax_url = "<?php echo site_url("Website_category/artcount");?>?id="+$("#id").val();
		$.ajax({
            type: "get",
            url: ajax_url,
            data: '',
            cache: false,
            dataType: "text",
            async:false,
            success: function(data) {                                           
               if(data>0){
            	  artcount = data;
               }
            },
            beforeSend: function() {
                //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
            },
            error: function(a,b,c,d) {
            	//alert(c);
                alert('服务器繁忙请稍。。。。');
            }

        });		
	}
	
}


BUI.use('bui/tooltip',function (Tooltip) {
	<?php if($model["thumb"]==""){?>	
	var t2 = new Tooltip.Tip({
	  trigger : '#thumb',
	  alignType : 'bottom',
	  offset : 10,
	  title : '-',
	  elCls : 'tips tips-success',
	  titleTpl : '<span class="x-icon x-icon-small x-icon-success"><i class="icon icon-white icon-question"></i></span>\
	  <div class="tips-content">'+$("#thumb").attr("tips")+'</div>'
	});
	t2.render();
	t2.show();
	<?php }
	else {
	?>
	var t2 = new Tooltip.Tip({
	  trigger : '#thumb_img',
	  alignType : 'bottom',
	  offset : 10,
	  title : '-',
	  elCls : 'tips tips-success',
	  titleTpl : '<span class="x-icon x-icon-small x-icon-success"><i class="icon icon-white icon-question"></i></span>\
	  <div class="tips-content">'+$("#thumb_img").attr("tips")+'</div>'
	});
	t2.render();
	t2.show();
	<?php }?>
	});

	<?php if($model["thumb"]!=""){?>
	$("#thumb_img").click(
	function(){

	 	BUI.Message.Confirm("是否删除缩略图?",function(){                	
	 		var ajax_url = "<?php echo site_url("Website_category/delcategorythumb");?>?id="+$("#id").val();
			$.ajax({
	            type: "get",
	            url: ajax_url,
	            data: '',
	            cache: false,
	            dataType: "text",
	            //async:false,
	            success: function(data) {                                
	                //window.location.href="<?php echo $_SERVER['REQUEST_URI'];?>";
	                window.location.reload();
	            },
	            beforeSend: function() {
	                //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
	            },
	            error: function(a,b,c,d) {
	            	//alert(c);
	                alert('服务器繁忙请稍。。。。');
	            }

	        });	

	        	       		 
          },'question'); 
		

			

				
	});
	<?php 

	}
	?>
</script>
<br/><br/><br/><br/>
<br/><br/><br/><br/>

</form>
</body>
</html>