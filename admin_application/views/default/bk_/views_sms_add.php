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
	
	.bui-dialog .bui-stdmod-body{
		padding:0px;
	}
    </style>
</head>
<body class="definewidth">
<br/>
<div class="alert alert-warning alert-dismissable">
<strong>注意</strong> 
注意：禁发营销短信，有封号风险。
</div>
<form action="<?php echo site_url("sms/add");?>" onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="selid" id="selid" value="" />


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">发送方式：</td>
        <td>
     
<button type="button" class="button button-danger" onclick="show_seluser2();">选择本区党员或非本区党员</button>  
        </td>
    </tr>
<tr>
<td class="tableleft">手机号码：
<br/>
用半角逗号分隔“，”
</td>
<td>
<textarea name="tel" id="tel" style="width:300px; height:80px;" required="true"><?php echo isset($tel)?$tel:"";?></textarea>
</td>
</tr>
    <tr>
        <td class="tableleft">短信内容：<br/>
建议70字以内        
        </td>
        <td>
短信前辍：【石岐党建】<br/>        
<textarea name="sms"  onkeyup="checkLength(this);" id="sms" style="width:300px; height:80px;" required="true"><?php echo isset($sms)?$sms:"";?></textarea>
<br /><small>文字最大长度:64，还剩: <span id="chLeft">64</span>。</small>
        </td>
    </tr>  
    <tr>
        <td class="tableleft">备注：</td>
        <td>
<textarea name="beizhu" id="beizhu" style="width:300px; height:80px;"><?php echo isset($beizhu)?$beizhu:"";?></textarea>
        </td>
    </tr>      


    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" id="btnSave">发送</button>            
        </td>
    </tr>
</table>

</form>
</body>
</html>
<script type="text/javascript">
function postform(){
	if($("#myform").Valid()) {
		return true;
	}
	else{	
		return false;
	}
}
function show_seluser2(){
	//alert(document.getElementById("sendtype2").checked);
	//if(document.getElementById("sendtype2").checked){
		var w = 600;
		var h = 400;
		 BUI.use('bui/overlay',function(Overlay){
	         var dialog2 = new Overlay.Dialog({
	           title:'选择本区党员或非本区党员',
	           width:w,
	           height:h,
	           //配置文本
	           bodyContent:'<iframe src="<?php echo site_url("sms/seluser")?>" width="'+(w-10)+'"  height="'+(h-60)+'" frameborder="0" marginwidth="0" marginheight="0"  ></iframe>',
	           buttons:[
	                    {
	                      text:'确定',
	                      elCls : 'button button-primary',
	                      handler : function(){
	                        var tel = $("#selid").val();
	                        var bujige = 0;
	                        var tmp = "";
	                        var arr = tel.split(",");
	                        for(i=0;i<arr.length;i++){
	                        	var reg = /^(130|131|132|133|134|135|136|137|138|139|150|153|157|158|159|180|187|188|189)\d{8}$/;
	            				if(!reg.test(arr[i]))
	            				{
	            					bujige++;
	            				}
	            				else{
									if(tmp==""){
										tmp=arr[i];
									}
									else{
										tmp+=","+arr[i];
									}
	            				}		                        								
	                        }
	                        if(bujige>0){
								parent.tip_show ("只接受11位的手机号码。",2,2000);
	                        }
	                        $("#tel").val(tmp);
	                        $("#selid").val(tmp);
	                        		                        
	                        this.close();
	                      }
	                    },
	                    {
		                  text:'<?php if(!is_super_admin()){echo "所有单位党员";}?><?php if(is_super_admin()){ echo "全体党员"; }?>',
		                  elCls:'button button-success',
		                  handler:function(){
		                	  selalldy();
							 this.close();
		                  }
		                }
	                    ,{
	                      text:'取消',
	                      elCls : 'button',
	                      handler : function(){
		                    //清空选中
		                    $("#tel").val("");
	                        this.close();
	                      }
	                    }
	                  ]
	         });
	       dialog2.show();
	       	
		});
	//}
}

function checkLength(which) {  
var maxChars = 64;  
if (which.value.length > maxChars)  
which.value = which.value.substring(0,maxChars);  
var curr = maxChars - which.value.length;  
document.getElementById("chLeft").innerHTML = curr.toString();  
}  

function selalldy(){
	var ajax_url = "<?php echo site_url("sms/selall");?>";
	$.ajax({
        type: "get",
        url: ajax_url,
        data: '',
        cache: false,
        dataType: "text",
        //async:false,
        success: function(data) { 
            //alert(data);                               
    		$("#tel").val(data);
        },
        beforeSend: function() {
            //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
        },
        error: function() {
            alert('服务器繁忙请稍。。。。');
        }

    });			
}
var errmsg = "<?php echo !isset($err)?"":$err;?>";
if(errmsg!=""){
	parent.tip_show(errmsg,2,3000);	
}
</script>

