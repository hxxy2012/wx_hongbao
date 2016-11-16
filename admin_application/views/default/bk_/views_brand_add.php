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
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
	<link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/DatePicker/WdatePicker.js"></script>
    
    <!--颜色拾取-->
 <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/ColorPicker_v1.0/jquery.colorpicker.js"></script> 
 <!--图片上传插件-->
 
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/diyUpload/css/diyUpload.css">
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/diyUpload/js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/diyUpload/js/diyUpload.js"></script>   
 
 

<!--在线编辑器-->
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>

 
 
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
        #colorpanel table{width:100%;}
#box{ margin:50px auto; width:540px; min-height:100px; background:#FF9}
#demo{ margin:50px auto; width:540px; min-height:800px; background:#CF9}
    </style>
</head>
<body class="definewidth">

<form action="<?php echo site_url("brand/add");?>" enctype='multipart/form-data' onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="action" value="doadd">


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">品牌名称：</td>
        <td><input type="text" style="width:200px;" name="mysql_title"  value="<?php echo !empty($model['title'])?$model['title']:''?>" id="title" required="true" 
valType="check"

        
        />
  
        </td>
    </tr>
    <tr>
        <td class="tableleft">全称：</td>
        <td><input type="text" style="width:200px;" name="mysql_fulltitle"  value="<?php echo !empty($model['fulltitle'])?$model['fulltitle']:''?>" id="fulltitle" 
                   required="true" 
valType="check"

        
        />
  
        </td>
    </tr>


  <?php 
if(isset($model)){
?>
    <tr>
        <td class="tableleft">品牌首页域名：</td>
        <td>
        http://
        <input type="text" style="width:100px;" 
        name="mysql_yuming" 
         value="<?php echo !empty($model['yuming'])?$model['yuming']:''?>"
         id="mysql_yuming"  required="true" valType="check"
remoteUrl="<?php echo site_url("brand/chkyuming")."?id=".$model["guid"];?>"                  
        />.zx9.cn  
        </td>
    </tr>
<?php 
}
?>  
    
    
    <tr>
        <td class="tableleft">简介：</td>
        <td> <textarea style="width:500px;" name="mysql_jianjie" id="jianjie"  required="true" 
valType="check"><?php echo !empty($model['jianjie'])?$model['jianjie']:''?></textarea>	           
  
        </td>
    </tr> 
    
    
      <tr>
        <td class="tableleft">详细介绍：</td>
         <td> 
  
<textarea style="width:100%;height:150px;" id="mysql_content" name="mysql_content"  ><?php echo !empty($model['content'])?$model['content']:''?></textarea>

<script>
    KindEditor.ready(function(K) {
window.editor = K.create('#mysql_content', {
width: '100%',
height: '200px',
allowFileManager: false,
allowUpload: false,
afterCreate: function() {
this.sync();
},
afterBlur: function() {
this.sync();
},
extraFileUploadParams: {
'cookie': "<?php echo $admin_auth_cookie;?>"
},
uploadJson: "<?php echo site_url("website_category/upload").'?action=upload&session='.$usersession;?>"

});
});

</script>



        </td>
    </tr>  
      <tr>
        <td class="tableleft">上传LOGO：</td>
         <td> 
           <input type="file" name="mysql_logo" id="logo"  />

       <span id="show_logo"><?php echo  !empty($model['logo'])?"<img width=100 src='../../{$model['logo']}' />":'';?></span>
         <?php if(!empty($model['logo'])):?>  
    <a id="del_link_logo" href="javascript:;" onclick="delpic('show_logo','logo','<?php echo $model['guid'];?>')">删除图片</a>
        <?php endif;?>  

    
      长宽：160px X 160px
         </td>
    </tr>  
      <tr>
        <td class="tableleft">品牌页页头图片：</td>
         <td> 
           <input type="file" name="mysql_brand_pic" id="brand_pic"  />

           
      <span id="show_brand_pic"><?php echo  !empty($model['brand_pic'])?"<img width=100 src='../../{$model['brand_pic']}' />":'';?></span>
         <?php if(!empty($model['brand_pic'])):?>  
    <a id="del_link_brand_pic" href="javascript:;" onclick="delpic('show_brand_pic','brand_pic','<?php echo $model['guid'];?>')">删除图片</a>
        <?php endif;?>  
           

  长X宽：960px X 130px

        </td>
    </tr>  
      <tr>
        <td class="tableleft">用户页页头图片：</td>
         <td> 
           <input type="file" name="mysql_user_pic" id="brand_pic"  />

             <span id="show_user_pic"><?php echo  !empty($model['user_pic'])?"<img width=100 src='../../{$model['user_pic']}' />":'';?></span>
         <?php if(!empty($model['user_pic'])):?>  
    <a id="del_link_user_pic" href="javascript:;" onclick="delpic('show_user_pic','user_pic','<?php echo $model['guid'];?>')">删除图片</a>
        <?php endif;?>  
           
           
           

  长X宽：960px X 130px  

        </td>
    </tr>  
      <tr>
        <td class="tableleft">页头字体大小及颜色设置：
     
        </td>
         <td> 
           <input type="text" name="mysql_header_fontsize" id="header_fontsize" style="width:50px;" value="<?php echo !empty($model['header_fontsize'])?$model['header_fontsize']:'20px'?>" />
           <input type="text" name="mysql_header_color" id="cp3text" value="<?php echo !empty($model['header_color'])?$model['header_color']:''?>" style="width:70px;" /><img  src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/ColorPicker_v1.0/colorpicker.png" id="cp3" style="cursor:pointer"/>
           <script type="text/javascript">
    $(function(){
      
        $("#cp3").colorpicker({
            fillcolor:true,
            success:function(o,color){
                $("#cp3text").val(color);
                 $("#cp3text").css('color',color);
            }
        });
        
      //编辑时显示颜色  
     var mycolor ="<?php echo !empty($model['header_color'])?$model['header_color']:''?>";
    if(mycolor!=''){
          $("#cp3text").css('color',mycolor);
    }
    
    });
</script>
  
        </td>
    </tr>  
      <tr>
        <td class="tableleft">上传滚动图片：
<br/>按4比3长宽比上传，建议图片大小在200K以下           
        </td>
         <td> 
            
     <div id="box">
	<div id="test" ></div>
    </div>
    
             <?php
             
                //print_r($model['ad_pic_data']);exit;
             
             ?>
<?php if(!empty($model['ad_pic_data'])):?>             
    <ul id='show_piliang_pic'>
        <?php foreach($model['ad_pic_data'] as $k=>$v):?>
            <?php if(!empty($v['path'])):?>
                <li id="piliang_<?php echo $k;?>">
                    <center><img width='100' src='<?php echo base_url().$v['path'] ?>' /></center>
                    <center>链接<input type='text' value='<?php echo $v['link'];?>' name='ad_pic_link[<?php echo $k;?>]'></center>
                    <center><a href='javascript:;'  onclick='piliang_delpic("<?php echo $k;?>")'>删除图片</a></center>

                </li>
          <?php endif;?>
        <?php endforeach;?>
    </ul>
    <style>
       #show_piliang_pic{width:100%;}
       #show_piliang_pic img{width:100px;height:100px;}
       #show_piliang_pic,#show_piliang_pic li{list-style: none;float:left;}
       #show_piliang_pic li{margin:15px;}
       #show_piliang_pic a{font-size: 13px;}
    </style>  
<script>
    function piliang_delpic(item){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("brand/ajax_piliang_delpic")?>",
            data: {'index':item,'brandguid':'<?php echo $model['guid'];?>'},
            success: function(msg){
                if(msg==1){
                    $('#piliang_'+item).hide();
                    $("input[name='ad_pic_link["+item+"]']").val('');
                }
            }
        });
    }
</script>             
<?php endif;?>               
             
             
        
                    
<div id="hidden_pics" style="display:none"></div>
<input id="mysql_ad_pic" name="mysql_ad_pic" type="hidden"  />
<script type="text/javascript">

/*
* 服务器地址,成功返回,失败返回参数格式依照jquery.ajax习惯;
* 其他参数同WebUploader
*/

$('#test').diyUpload({
	url:'<?php echo site_url("brand/piliangUploadPic"); ?>',
	success:function( data ) {
//		alert( data.result );
                $("#hidden_pics").append(data.result+'###');
              $('#mysql_ad_pic').val( $("#hidden_pics").html());
	},
	error:function( err ) {
		console.info( err );	
	}
        /*
        ,buttonText : '选择文件',
	chunked:true,
	// 分片大小
	chunkSize:512 * 1024,
	//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
	fileNumLimit:50,
	fileSizeLimit:500000 * 1024,
	fileSingleSizeLimit:50000 * 1024,
	accept: {}
        */
});

 
</script>
        </td>
    </tr>  
    <tr>
        <td class="tableleft">上传单个视频：</td>
         <td> 
           <input type="file" name="flv" id="flv"  />
         <?php if(!empty($model['flv'])):?>  
    <a id="del_link_flv" href="javascript:;" onclick="delpic('show_flv','flv','<?php echo $model['guid'];?>')">删除视频</a>
        <?php endif;?>  
           
  视频文件在10M以下的FLV文件     
        </td>
    </tr>  
    
     <tr>
        <td class="tableleft">置顶描述：</td>
         <td> 
           <input type="text" name="mysql_istop_str" value="<?php echo !empty($model['istop_str'])?$model['istop_str']:''?>" style="width:500px" id=""  />
    
  
        </td>
    </tr>  
     <tr>
		<td class="tableleft">置顶时间：</td>
         <td> 
               <?php $date = date("Y-m-d H:i:s",time());?>
             <input type="text" readonly="" name="mysql_istop" value="<?php echo !empty($model['istop'])?$model['istop']:$date?>   "
              onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,readOnly:false})"
        	   required="true"
        	   valType="date"
            />  
           
           
        </td>
    </tr>  
    
    
    
    <?php if(!empty($model['guid'])):?>
     <tr>
        <td class="tableleft">新闻栏目：</td>
         <td> 
             <select name="mysql_website_category">
                 <?php foreach($news_data as $k=>$v):?>
                 <option  <?php if($model['website_category']==$v['id']):?>selected<?php endif;?>   value="<?php echo $v['id'];?>"><?php echo $v['title']?></option>
                 <?php endforeach?>
             </select>
        </td>
    </tr>  
    
    
    
    
    <?php endif;?>
    
    
    
    
    
    
    
    
    
    
    <!--
      <tr>
        <td class="tableleft">修改时间：</td>
         <td> 
          <input type="text" name="mysql_edit_time" value="<?php echo date("Y-m-d H:i:s",time());?>"
           onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:true,readOnly:false})"
        	   required="true"
        	   valType="date"
            />  
  
        </td>
    </tr>  
     -->

  

    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
<a  class="btn btn-primary" id="addnew" onclick="golist()" href="javascript:;">返回列表</a>       
            <input type="hidden" value="<?php echo isset($model['guid'])?$model['guid']:'';?>" name="guid" />
        </td>
    </tr>
</table>
</form>
</body>
</html>




  
 <script>
 function golist(){
        top.topManager.closePage();
     window.location.href="<?php echo site_url("brand/index");?>";
 }
function delpic(obj,field,brandguid){
        var update_field = field;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("brand/ajax_delpic")?>",
            data: {'field':update_field,'brandguid':brandguid},
            success: function(msg){
                if(msg==1){
                    $('#'+obj).hide();
                    $('#del_link_'+field).hide();
                }
            }
        });
}
function postform(){

if(!$("#myform").Valid()){
	return false;
}
if($('#mysql_content').val()==''){
   parent.tip_show('详细介绍不能为空',1,1000);
    return false;
}
return true;
}

</script>