<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户查看__<?php echo isset($model['name'])?$model['name']:"";?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="/admin_application/views/static/Js/jquery-1.8.1.min.js"></script> 	
	<!--script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/bui-min.js"></script-->
    
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
<script src="/home/views/static/js/layer/layer.js?v=2.1"></script>    
<script type="text/javascript" src="/home/views/static/js/uploadfile/jquery.uploadify-3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/home/views/static/js/uploadfile/uploadify.css"/>
    
	<link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
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
function show_zj_div(opt){
	if(opt==1){
		$("#zj1").css("display","block");	
		$("#zj2").css("display","none");	
	}
	else{
		$("#zj1").css("display","none");	
		$("#zj2").css("display","block");			
	}	
}
</script> 
<link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />  
    
</head>
<body class="definewidth">
    <!-- <div class="container">
        <ul class="gallery1">
            <li><a href="/home/views/static/js/zoom/gallery/DSC_0008-660x441.jpg"><img src="/home/views/static/js/zoom/gallery/DSC_0008-69x69.jpg" /></a></li>
            <li><a href="/home/views/static/js/zoom/gallery/DSC_0014-660x441.jpg"><img src="/home/views/static/js/zoom/gallery/DSC_0014-69x69.jpg" /></a></li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="container">
        <ul class="gallery1">
            <li><a href="/home/views/static/js/zoom/gallery/DSC_0090-660x441.jpg"><img src="/home/views/static/js/zoom/gallery/DSC_0090-69x69.jpg" /></a></li>
            <li><a href="/home/views/static/js/zoom/gallery/DSC_0091-660x441.jpg"><img src="/home/views/static/js/zoom/gallery/DSC_0091-69x69.jpg" /></a></li>
            <li><a href="/home/views/static/js/zoom/gallery/DSC_0161-660x441.jpg"><img src="/home/views/static/js/zoom/gallery/DSC_0161-69x69.jpg" /></a></li>
        </ul>
        <div class="clear"></div>
    </div> -->
<div class="form-inline definewidth m20" >
<?php
if(count($model)==0){
	echo "<div style='text-align:center;color:red;line-height:200%;'>";
	echo "<b>暂无资料</b>";
	echo "</div>";
}
?>

   
   
</div>
<form enctype="multipart/form-data" action="<?php echo site_url("user/edit_qy");?>"  method="post" name="myform" id="myform">
<input type="hidden" name="action" value="doedit">
<input type="hidden" name="id" value="<?php echo isset($model['id'])?$model['id']:"";?>">
<input type="hidden" name="userid" value="<?php echo isset($userid)?$userid:"";?>">

<table class="table table-bordered table-hover definewidth">
  <tr>
        <td width="13%" class="tableleft">*企业名称</td>
        <td><?php  echo isset($model['name'])?$model['name']:""; ?></td>
    </tr>	
     <tr>
        <td class="tableleft">*电商企业类型</td>
        <td>
<?php
if(isset($model["company_type"])){
	$company_type_arr = explode(",",$model["company_type"]);
}
else{
	$company_type_arr  = array();
}
foreach($company_type as $v){
	echo "<input type='checkbox'  tname='{$v["name"]}'  readonly disabled name='company_type[]'";
	if(in_array($v["id"],$company_type_arr)){
		echo " checked ";	
	}
	echo " value='".$v["id"]."'/> ";
	echo $v["name"];
	echo "　";
}
?><br><br> 
<input type="text" readonly disabled style="display:none;margin:0;" name="company_type_other" placeholder="其他企业类型" id="company_type_other" value="<?php  echo isset($model['company_type_other'])?$model['company_type_other']:""; ?>">            
        </td>
    </tr> 	
    <tr>
	   <td class="tableleft">*电商交易模式</td>
	   <td><?php
if(isset($model["business_model"])){
	$business_model_arr = explode(",",$model["business_model"]);
}
else{
	$business_model_arr  = array();
}	   
foreach($jiaoyi as $v){
	echo "<input type='checkbox' tname='{$v["name"]}' readonly disabled name='jiaoyi[]' ";
	if(in_array($v["id"],$business_model_arr)){
		echo "checked";	
	}
	echo " value='".$v["id"]."'/> ";
	echo $v["name"];
	echo "　";
}
?>
<input type="text" readonly disabled style="display:none;margin:0;" name="business_model_other" placeholder="其他交易模式" id="business_model_other" value="<?php  echo isset($model['business_model_other'])?$model['business_model_other']:""; ?>">    
</td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">*主营产品</td>
        <td>
<span id="product_text">        
<?php
if(isset($model["product"])){
?>
<?php 
$product_list = "";
foreach($product2 as $v){
	if($product_list==""){
		$product_list = $v["name"];
	}
	else{
		$product_list .=",".$v["name"];
	}
}
echo $product_list;
?>
<?php	
}
?>      
</span>
<input type="hidden" name="mysql_product2" id="product" value="<?php echo isset($model["product2"])?$model["product2"]:"";?>" />
        </td>
    </tr>
	<tbody id="t_0" class="pp">
    <tr>
        <td width="10%" class="tableleft">*是否三证合一</td>
      <td>
      

      <input name="upload_paper_type" id="upload_paper_type2" type="radio" onClick=" show_zj_div(1)" value="2" checked />是


      <input type="radio" name="upload_paper_type" id="upload_paper_type1"  value="1"  onClick=" show_zj_div(2)" />否  

      </td>
    </tr> 
	 <tr>
        <td width="10%" class="tableleft">&nbsp;</td>
        <td>
        
        <div id="zj1" style="display:block;">
<table id="upload1"><tr><td style="border:none;">
三证合一：
</td>
<td style="border:none;">
<input type="hidden" name="mysql_business_licence_id" id="business_licence_id" value="<?php echo isset($model["business_licence_id"])?$model["business_licence_id"]:"";?>"/>
<input type="hidden" name="mysql_three_code_add_id" id="three_code_add_id" value="<?php echo isset($model["three_code_add_id"])?$model["three_code_add_id"]:"";?>"/>
<input type="hidden" name="mysql_organization_code_id"  id="organization_code_id" value="<?php echo isset($model["organization_code_id"])?$model["organization_code_id"]:"";?>" />
<input type="hidden" name="mysql_shuiwu_register_code_id"  id="shuiwu_register_code_id" value="<?php echo isset($model["shuiwu_register_code_id"])?$model["shuiwu_register_code_id"]:"";?>"/>
<?php
if($isedit){
?>  
<input type="file" name="file_three_code_add_id" 
id="file_three_code_add_id" 
value="<?php echo isset($model["three_code_add_id"])?$model["three_code_add_id"]:"";?>"
/>
<?php
}
else{
	echo "-";	
}
?>
</td>
</tr></table>
<table id="upload1_ok" style="display:none;"><tr><td style="border:none;">
<!-- <input type="button" value="查看三证合一"  class="button button-info" onClick="lookpic($('#three_code_add_id').val())"/> -->
    <?php if(isset($three_code_add_info['filesrc'])){?>
    <div class="container">
        <ul class="gallery1">

            <li  style="padding:0px;"><a href="/<?php echo $three_code_add_info['filesrc'];?>"><img  style='width:92px;height:70px;' src="/<?php echo $three_code_add_info['filesrc'];?>" /></a>
            <div style="text-align:center;">三证合一</div>
            </li>

        </ul>
        <div class="clear"></div>
    </div>
    <?php }?>
</td></tr></table>
        </div>
        
        <div id="zj2" style="display:none;">




<div class="container">
    <ul class="gallery1">
<?php if(isset($business_licence_info['filesrc'])){?>
        <li style="padding:5px;"><a href="/<?php echo $business_licence_info['filesrc'];?>"><img style='width:92px;height:70px;' alt="营业执照" src="/<?php echo $business_licence_info['filesrc'];?>" />
<div style="text-align:center;">营业执照</div>        
        </a>
         
        </li>
<?php }?>
<?php if(isset($organization_code_info['filesrc'])){?>
        <li style="padding:5px;"><a href="/<?php echo $organization_code_info['filesrc'];?>"><img style='width:92px;height:70px;' alt="组织机构代码证" src="/<?php echo $organization_code_info['filesrc'];?>" />
         <div style="text-align:center;">组织机构代码证</div>
        </a>
        
        </li>
<?php }?>
<?php if(isset($shuiwu_register_code_info['filesrc'])){?>
        <li style='padding:5px;'><a href="/<?php echo $shuiwu_register_code_info['filesrc'];?>"><img style='width:92px;height:70px;' alt="税务登记证" src="/<?php echo $shuiwu_register_code_info['filesrc'];?>" />
        <div style="text-align:center;">税务登记证</div>
        </a></li>

<?php }?>
    </ul>
    <div class="clear"></div>
</div>






        </div>
        </td>
     </tr> 
    <tr>
        <td width="10%" class="tableleft">*证件号码：</td>
        <td>
        <?php echo isset($model["code"])?$model["code"]:"";?>
        </td>
    </tr> 
	</tbody>
	<tbody id="t_1" >
    <tr>
        <td width="10%" class="tableleft">*所属镇区：</td>
        <td>
        <select name="mysql_town_id" required readonly disabled>
        <option value="">请选择</option>
        <?php
		foreach($town as $v){
			echo "<option value='".$v["id"]."'";
			if(isset($model["town_id"])){
				echo $model["town_id"]==$v["id"]?" selected ":"";
			}
			echo ">";
			echo $v["name"];
			echo "</option>\n";	
		}
		?>
        </select>
        </td>
    </tr> 
	</tbody>
    <tr>
        <td width="10%" class="tableleft">*注册资金：</td>
        <td><?php echo isset($model["register_money"])?$model["register_money"]:"";?>
        万元</td>
    </tr> 
    <tr>
        <td width="10%" class="tableleft">常用开户银行：</td>
        <td><?php echo isset($model["open_account_bank"])?$model["open_account_bank"]:"";?></td>
    </tr> 	
    <tr>
      <td class="tableleft">常用对公银行账号：</td>
      <td><?php echo isset($model["public_bank_account"])?$model["public_bank_account"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">公司人数：</td>
      <td><?php echo isset($model["company_number"])?$model["company_number"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">电商部门人数：</td>
      <td><?php echo isset($model["electronic_number"])?$model["electronic_number"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">*注册地址：</td>
      <td><?php echo isset($model["register_address"])?$model["register_address"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">*企业固定电话：</td>
      <td><?php echo isset($model["guding_phone"])?$model["guding_phone"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">*企业移动电话：</td>
      <td><?php echo isset($model["mobilephone"])?$model["mobilephone"]:"";?></td>
    </tr>
    <tr>
      <td class="tableleft">企业传真：</td>
      <td><?php echo isset($model["faxphone"])?$model["faxphone"]:"";?></td>
    </tr>
    <tr>
        <td class="tableleft">*企业电子邮箱：</td>
        <td><?php echo isset($model["email"])?$model["email"]:"";?></td>
    </tr>
    	
    <tr>
      <td valign="top" class="tableleft"><p>*企业简介</p></td>
      <td>
      <pre style="border:none; background-color:transparent;">
     <?php echo isset($model["company_summary"])?$model["company_summary"]:"";?>
     </pre>
      </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>&nbsp;
        <!-- <button type="submit" class="btn btn-warning" id="btnSave">提交修改</button> -->
<a  class="btn btn-primary" id="addnew" onClick="parent.flushpage('<?php echo empty($_GET["backurl"])?"":$_GET["backurl"]?>');top.topManager.closePage();">关闭</a>        
        </td>
    </tr>
</table>

<input type="hidden" name="backurl" value="<?php echo empty($_GET["backurl"])?"":$_GET["backurl"]?>" />

</form>	   
</body>
</html>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
<script>
var flag_company_type_other = 0;//标记企业类型是否选中
var flag_business_model_other = 0;//标记电商模式是否选中
$(function(){
    var obj_company_type=document.getElementsByName('company_type[]'); //选择所有name="'type[]'"的对象，返回数组 
    //循环检测活动其他类型是否选中，如果选中则显示text 
    for(var i=0; i<obj_company_type.length; i++){ 
        // alert(obj_company_type[i].attributes['tname'].nodeValue);
        if(obj_company_type[i].attributes['tname'].nodeValue=='其他'&&obj_company_type[i].checked) {
            flag_company_type_other = 1;
            $('#company_type_other').show();
        }
    } 
    var obj_business_model=document.getElementsByName('jiaoyi[]'); //选择所有name="'type[]'"的对象，返回数组 
    //循环检测活动其他类型是否选中，如果选中则显示text 
    for(var i=0; i<obj_business_model.length; i++){ 
        // alert(obj_business_model[i].attributes['tname'].nodeValue);
        if(obj_business_model[i].attributes['tname'].nodeValue=='其他'&&obj_business_model[i].checked) {
            flag_business_model_other = 1;
            $('#business_model_other').show();
        }
    }
    //监听企业类型点击事件
    $('input[name="company_type[]"]').click(function(){
        var obj = $(this);
        var tname = obj.attr('tname');
        if (tname=='其他'&&obj.is(':checked')) {
            flag_company_type_other = 1;
            $('#company_type_other').show();
        } else if(tname=='其他'&&!obj.is(':checked')){
            flag_company_type_other = 0;
            $('#company_type_other').val('');
            $('#company_type_other').hide();
        }
    });
    //监听电商交易模式点击事件
    $('input[name="jiaoyi[]"]').click(function(){
        var obj = $(this);
        var tname = obj.attr('tname');
        if (tname=='其他'&&obj.is(':checked')) {
            flag_business_model_other = 1;
            $('#business_model_other').show();
        } else if(tname=='其他'&&!obj.is(':checked')){
            flag_business_model_other = 0;
            $('#business_model_other').val('');
            $('#business_model_other').hide();
        }
    });
});
	$("#btn_open_pro").on("click",function(){
		layer.open({
			title: "选择",
			type: 2,
			area: ['700px', '300px'],
			fix: false, //不固定
			maxmin: true,
			content: '<?php echo site_url("user/prolist2");?>?sel='+$("#product").val()
		});	
	});
	

	




<?php
if(isset($model["upload_paper_type"])){
	if($model["upload_paper_type"]=="2"){
		echo '$("#upload_paper_type2").click();';
	}
	else{
		echo '$("#upload_paper_type1").click();';		
	}
}
echo "$('#upload_paper_type2').attr('disabled',true);";
echo "$('#upload_paper_type1').attr('disabled',true);";
if(isset($model["three_code_add_id"])){
	if($model["three_code_add_id"]>0){
		echo '$("#upload1").css("display","none");';	
		echo '$("#upload1_ok").css("display","block");';			
	}
}



	
?>
</script> 	
