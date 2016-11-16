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
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/laydate/laydate.js"></script>


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

<form onsubmit="return postform()" 
arction=""  
method="post" 
enctype="multipart/form-data"
id="J_Form" class="form-horizontal"
class="definewidth m10" style="padding-top: 10px;" >

<input type="hidden" id="id" name="id" value=""/> 
<input type="hidden" id="common_model_id" name="common_model_id" value="<?php echo $category_model_id;?>"/>


<div class="row">
     
  
          <div class="control-group span9">
            <label class="control-label">标题：</label>
            <div class="controls">
<input type="text" name="title" id="title" style="width:200px;" required='true'  value=""/>*              
            </div>
          </div>
          
          <div class="control-group span9">
            <label class="control-label">长标题：</label>
            <div class="controls">
<input type="text" name="fulltitle" id="fulltitle" style="width:200px;"  value=""/>              
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
						echo ($typeid==$v["id"]?"selected":"");
						echo ($category_model_id!=$v["model_id"]?'disabled="disabled"':"").">";
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
						echo ($category_model_id!=$v["model_id"]?'disabled="disabled"':"").">";
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
						echo ($category_model_id!=$v["model_id"]?'disabled="disabled"':"").">";
						echo $v["tree"];
						echo $v["title"];
						echo "</option>\n";
					}
					?>
					</select>
			</div>
		 </div>
         
         
		<div class="control-group span9">
            <label class="control-label">会员可见：</label>
            <div class="controls">
					<select  name="jibie">
					<option value="0" selected>公开</option>
					<option value="1">仅限会员可见</option>
					</select>
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
           <input type="text" name="post" value="<?php echo date("Y-m-d H:i:s",time());?>"
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
					<option value="0">未审</option>
					<option value="99" selected>审核通过</option>
					<option value="-1">审核不通过</option>
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
					<option value="一天">一天</option>
					<option value="一周">一周</option>
					<option value="两周">两周</option>
					<option value="一个月">一个月</option>
					<option value="三个月">三个月</option>
					<option value="半年">半年</option>
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
	           <textarea style="width:500px;" name="beizhu" id="beizhu"></textarea>	           
	           </div>
	</div>	
	<div class="control-group span8">
	           <label class="control-label">缩略图：</label>
	           <div class="controls control-row4">
	           	           
<input type="file" title="格式：JPG|GIF|PNG|BMP" name="thumb" id="thumb" style="width:160px;" />
	           
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

//使用模板右边显示
var t2 = new Tooltip.Tip({
  trigger : '#thumb',
  alignType : 'bottom',
  offset : 10,
  title : '右边的提示信息',
  elCls : 'tips tips-success',
  titleTpl : '<span class="x-icon x-icon-small x-icon-success"><i class="icon icon-white icon-question"></i></span>\
  <div class="tips-content">'+document.getElementById("thumb").title+'</div>'
});
t2.render();
t2.show();
});

</script>


</form>
<br/><br/><br/><br/><br/>
<br/><br/><br/><br/><br/>
</body>
</html>