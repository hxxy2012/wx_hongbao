<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>添加</title>
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

<form onsubmit="return postform()" arction="<?php echo site_url("website_model/edit");?>"  method="post" class="definewidth m10" id="myform">
<input type="hidden" id="pid" name="pid" value="<?php echo $info["pid"];?>"/>
<input type="hidden" id="id" name="id" value="<?php echo $info["id"];?>"/> 
<table class="table table-bordered table-hover definewidth m10">
 <tr>
        <td width="10%" class="tableleft">模型名：</td>
        <td>
<?php echo $parent["title"];?>        
        </td>
</tr>        
    <tr>
        <td width="10%" class="tableleft">标题：</td>
        <td>
        
<input 
type="text" name="title" 
placeholder="输入标题" 
required="true" 
style="width:300px" value="<?php echo $info["title"];?>"/>

<select name="fieldtype" required="true" >
<option value="">--选择字段类型--</option>
<option value="1">1.文本框</option>
<option value="2">2.单选</option>
<option value="3">3.多选</option>
<option value="4" disabled="disabled">4.上传单个文件【未开发】</option>
<option value="5" disabled="disabled">5.上传单张图片【未开发】</option>
<option value="6" disabled="disabled">6.批量上传图片【未开发】</option>
<option value="7">7.文本编辑器</option>
<option value="8" disabled="disabled">8.多行文本框【未开发】</option>
<option value="9">9.日期</option>
<option value="10">10.日期时分</option>

</select>

是否必填：<input type="checkbox" name="isrequired" value="1" <?php echo $info["isrequired"]=="1"?"checked":"";?>/>
</td>
    </tr>

    <tr>
<td width="10%" class="tableleft">字段名：</td>
<td>
<input 
type="text" name="field"
placeholder="输入字段名" 
required="true" 
style="width:500px" value="<?php echo $info["field"];?>"
required="true"
valType="check"
remoteUrl="chkfield.shtml?pid=<?php echo $info["pid"];?>&field="+this.value
/>
</td>
    </tr>
    
    
    
    
<tr>
<td width="10%" class="tableleft">字段描述：</td>
<td>
<input 
type="text" name="field_comment" 
placeholder="输入描述" 
style="width:500px" value="<?php echo $info["content"];?>"/>
</td>
    </tr>
	
	   
    <tr>
<td width="10%" class="tableleft">单元格长度：</td>
<td>



        <input type="text" name="cell_width"
placeholder="输入长度" 
required="true" 
style="width:100px" value="<?php echo $info["cell_width"];?>"
required="true"
/>px
   
 

是否同一行：
<input type="radio" name="inline" value="1"  /> 是
<input type="radio" name="inline" value="0" checked/> 否
        
        
        
      
　　

</td>
    </tr>	   
	    	
	    	

<tr>
<td width="10%" class="tableleft">字段值：</td>
<td>
<input 
type="text" name="field_value" 
placeholder="多选、单选、默认值适用" 
style="width:500px" value="<?php echo $info["field_value"];?>"/>
选择的格式如：男=1,女=2
</td>
    </tr>	
    
<tr>
<td width="10%" class="tableleft">处理页：</td>
<td>
列表页：
<input 
type="text" name="listpage" 
placeholder="输入列表页地址" 
style="width:200px" value="<?php echo $info["listpage"];?>"/>
内容页：
<input 
type="text" name="contentpage" 
placeholder="输入内容页地址" 
style="width:200px" value="<?php echo $info["contentpage"];?>"/>

</td>
    </tr>
<tr>
<td width="10%" class="tableleft">排序（小到大）：</td>
<td>
<select name="orderby" >
<?php for($i=1;$i<=100;$i++){?>
<option value="<?php echo $i;?>" <?php echo $info["orderby"]==$i?"selected":""; ?>>
<?php echo $i;?>
</option>
<?php }
?>
</select>
</td>
    </tr>    
            	
	    	
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
        </td>
    </tr>
</table>
<script>
function postform(){
	if($("#myform").Valid()) {		
		return true ;
	}
	else{	
		return false;
	}
}


</script>


</form>
</body>
</html>