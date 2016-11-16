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
   <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/DatePicker/WdatePicker.js"></script> 
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
        
.bui-dialog .bui-stdmod-body  {
	padding:0px;
}    


    </style>
</head>
<body>

<form onsubmit="return postform()" 
arction=""  
method="post" 
enctype="multipart/form-data"
id="J_Form" class="form-horizontal"
class="definewidth m10" style="padding-top: 10px;" >

<input type="hidden" id="id" name="id" value="<?php echo $main_model["id"];?>"/> 
<input type="hidden" id="common_model_id" name="common_model_id" value="<?php echo $main_model["common_model_id"];?>"/>


<div class="row">
     
  
          <div class="control-group span9">
            <label class="control-label">标题：</label>
            <div class="controls">
<input type="text" name="title" id="title" style="width:200px;"  value="<?php echo $main_model["title"];?>"/>              
            </div>
          </div>
          
          <div class="control-group span9">
            <label class="control-label">长标题：</label>
            <div class="controls">
<input type="text" name="fulltitle" id="fulltitle" style="width:200px;"  value="<?php echo $main_model["fulltitle"];?>"/>              
            </div>
          </div>          
</div>



<div class="row">
<?php 
/*
 * 栏目
 */
?>
          <div class="control-group span9">
            <label class="control-label">主栏目：</label>
            <div class="controls">
					<select required="true" name="category_id">
					<option>请选择</option>
					<?php 
					foreach($categorylist as $v){
						echo "<option value='".$v["id"]."'";
						echo ($main_model["category_id"]==$v["id"]?"selected":"");
						echo ($main_model["common_model_id"]!=$v["model_id"]?'disabled="disabled"':"").">";
						echo $v["tree"];
						echo $v["title"];
						echo "</option>\n";
					}
					?>
					</select>
			</div>
		 </div>
		 
          <div class="control-group span9">
            <label class="control-label">副栏目1：</label>
            <div class="controls">
					<select  name="category_id2">
					<option value="0">请选择</option>
					<?php 
					foreach($categorylist as $v){
						echo "<option value='".$v["id"]."'";
						echo ($main_model["category_id2"]==$v["id"]?"selected":"");
						echo ($main_model["common_model_id"]!=$v["model_id"]?'disabled="disabled"':"").">";
						echo $v["tree"];
						echo $v["title"];
						echo "</option>\n";
					}
					?>
					</select>
			</div>
		 </div>
		 
		<div class="control-group span9">
            <label class="control-label">副栏目2：</label>
            <div class="controls">
					<select  name="category_id3">
					<option value="0">请选择</option>
					<?php 
					foreach($categorylist as $v){
						echo "<option value='".$v["id"]."'";
						echo ($main_model["category_id3"]==$v["id"]?"selected":"");
						echo ($main_model["common_model_id"]!=$v["model_id"]?'disabled="disabled"':"").">";
						echo $v["tree"];
						echo $v["title"];
						echo "</option>\n";
					}
					?>
					</select>
			</div>
		 </div>
</div>


<div class="row">
   <div class="control-group span9">
         <label class="control-label">选择文章：</label>
            <div class="controls">
<span id="dati_title"><?php echo $dati_id!=""?"已选":"";?></span>
<input type="hidden" name="website_model_dangyuandati_id" id="website_model_dangyuandati_id" value="<?php echo $dati_id?>"/>            
<button type="button" class="button" onclick="showsel()">选择</button>			
			</div>
		 </div>
		 
   <div class="control-group span9">
         <label class="control-label">期数：</label>
            <div class="controls">
<input type="text" name="qishu" id="qishu" style="width:80px;" value="<?php echo $two_model["qishu"];?>"/>            	
			</div>
		 </div>
		 
   <div class="control-group span9">
         <label class="control-label">新一期：</label>
            <div class="controls">
<input type="checkbox" name="isnew" id="isnew" <?php echo $two_model["isnew"]=="1"?"checked":"";?> value="1"/>
往期自动下架            	
			</div>
		 </div>		             	 
</div>

<div class="row">
<?php 
/*
 * 发布日期
 */
?>
	<div class="control-group span9">
           <label class="control-label">发布日期：</label>
           <div class="controls">
           <input type="text" name="post" value="<?php echo date("Y-m-d H:i:s",$main_model["post"]);?>"
           onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,readOnly:false})"
        	   required="true"
        	   valType="date"
            />           
           </div>
	</div> 
	
	<div class="control-group span9">
	           <label class="control-label">审核：</label>
	           <div class="controls">
					<select name="arcrank" required="true" id="arcrank">
					<option>请选择</option>
					<option value="0" <?php echo $main_model["arcrank"]=="0"?"selected":"";?>>未审</option>
					<option value="99" <?php echo $main_model["arcrank"]=="99"?"selected":"";?>>审核通过</option>
"?"selected":"";?>>审核不通过</option>
					</select>               
	           </div>
	</div> 	
	
	
	<div class="control-group span9">
	           <label class="control-label">置顶：</label>
	           <div class="controls">
					<select name="istop_str" id="istop_str">
					<option value="">请选择</option>
					<!-- option value="<?php echo (time()+(60*60*24));?>">一天</option>
					<option value="<?php echo (time()+(60*60*24*7));?>">一周</option>
					<option value="<?php echo (time()+(60*60*24*14));?>">两周</option>
					<option value="<?php echo (time()+(60*60*24*30));?>">一个月</option>
					<option value="<?php echo (time()+(60*60*24*90));?>">三个月</option>
					<option value="<?php echo (time()+(60*60*24*180));?>">半年</option-->
					<option value="一天" <?php echo $main_model["istop_str"]=="一天"?"selected":"";?>>一天</option>
					<option value="一周" <?php echo $main_model["istop_str"]=="一周"?"selected":"";?>>一周</option>
					<option value="两周" <?php echo $main_model["istop_str"]=="两周"?"selected":"";?>>两周</option>
					<option value="一个月" <?php echo $main_model["istop_str"]=="一个月"?"selected":"";?>>一个月</option>
					<option value="三个月" <?php echo $main_model["istop_str"]=="三个月"?"selected":"";?>>三个月</option>
					<option value="半年" <?php echo $main_model["istop_str"]=="半年"?"selected":"";?>>半年</option>
					</select>               
	           </div>
	</div>	
	
	          
</div>
   
  
   
<div class="row">
<?php 
/*
 * 备注
 */
?>
	<div class="control-group span18">
	           <label class="control-label">简述：</label>
	           <div class="controls control-row4">
	           <textarea style="width:500px;" name="beizhu" id="beizhu"><?php echo $main_model["beizhu"];?></textarea>	           
	           </div>
	</div>	
	<div class="control-group span8">
	           <label class="control-label">缩略图：</label>
	           <div class="controls control-row4">
<?php if($main_model["thumb"]==""){?>	           	           
<input type="file" tips="格式：JPG|GIF|PNG|BMP" name="thumb" id="thumb" style="width:160px;" />
<?php }
else{
?>
<img style="width:100px; height:60px;border:#000 1px solid;cursor:pointer;"   src="/<?php echo $main_model["thumb"];?>" id="thumb_img" name="thumb_img" tips="点击图片即删除"  />
<input type="hidden" name="thumb" value="<?php echo $main_model["thumb"];?>" />
<?php 
}?>
	           
	           </div>
	</div>	           
</div>        
        

<div class="row">
<?php 
echo $field;
?>  
</div>


 <div class="row">
<div class="span13 offset3 ">
<button type="submit" 
class="btn btn-primary" 
type="button" 
id="btnSave"
style="width:100px;"
>保　　存</button>
</div>
</div>

<script>
function postform(){
	if($("#myform").Valid()) {
		return true ;
	}
	else{	
		return false;
	}
}

BUI.use('bui/tooltip',function (Tooltip) {
<?php if($main_model["thumb"]==""){?>	
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

<?php if($main_model["thumb"]!=""){?>
$("#thumb_img").click(
function(){
	parent.my_confirm(
			"是否删除缩略图?",
			"/admin.php/Website_category/delinfothumb.shtml?id="+$("#id").val(),
			"<?php echo base_url();?>admin.php/zuixindongtai/edit.shtml?id=<?php echo $main_model["id"];?>"
	);		
});
/*
 *  echo $_SERVER['REQUEST_URI'];?>
 
 
function delthumb(){
    $.ajax({
        type: "GET",
        url: ,
        data: {id:$("#id").val()},
        dataType: "html",
        error:function(a,b,c,d){},
        success: function(data){            
          			if(data=="yes"){
						window.location.reload();
          			}
          			else{
						
          			}
                 }
    });	
}
*/
<?php 

}
?>


function showsel(){
	

	var w = 700;
	var h = 400;
	 BUI.use('bui/overlay',function(Overlay){
         var dialog2 = new Overlay.Dialog({
           title:'选择题目',
           width:w,
           height:h,
           //配置文本
           bodyContent:'<iframe src="<?php echo site_url("zuixindongtai/selart");?>" width="'+(w-10)+'"  height="'+(h-60)+'" frameborder="0" marginwidth="0" marginheight="0"  ></iframe>',
           buttons:[
                    {
                      text:'确定',
                      elCls : 'button button-primary',
                      handler : function(){
                        
                    
                        		                        
                        this.close();
                      }
                    }
                    
                    ,{
                      text:'取消',
                      elCls : 'button',
                      handler : function(){	                  
	                  	selquxiao();
                        this.close();
                      }
                    }
                  ]
         });
       dialog2.show();
       	
	});	


	
	
    	
}

function selone(title,id){
	
}

function selquxiao(){
	$("#website_model_dangyuandati_id").val("");
	$("#dati_title").html("");
}

</script>

<br/><br/><br/><br/><br/>
<br/><br/><br/><br/><br/>
</form>

</body>
</html>