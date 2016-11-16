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


    /**内容超出 出现滚动条 **/
    .bui-stdmod-body{
      overflow-x : hidden;
      overflow-y : auto;
    }
  </style>      
</head>
<body class="definewidth">




<form action="<?php echo site_url("user/add2");?>" onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="action" value="doadd">


<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">用户名：</td>
        <td>
<?php echo $model["username"];?>        
        </td>
    </tr>

    <tr>
        <td class="tableleft">所属党组织:</td>
        <td>
<?php
foreach($dzz as $v)
{
	
	if($dzzid==$v["id"]){
		echo $v["title"];
		break;
	}

}
if(count($dzz2)>0)
{
	foreach($dzz2 as $v){
		if($v["id"]==$dzzid2){
			echo $v["title"];
			break;
		}
				
	}
}
?>
      
        </td>
    </tr>    

    <tr>
        <td class="tableleft">姓名:</td>
        <td>
<?php echo $model["realname"];?>
        </td>
    </tr>     
    <tr>
        <td class="tableleft">联系电话:</td>
        <td>        
<?php echo $model["tel"];?>        
        </td>
    </tr>
    <tr>
        <td class="tableleft">身份证:</td>
        <td>
<?php echo $model["idcard"];?>        
        </td>
    </tr>
    <tr>
        <td class="tableleft">身份:</td>
        <td>
        
<?php
if($model["reg_shenfen"]!=""){
	echo "用户注册时选了：";
	echo $model["reg_shenfen"];
} 
else{
	echo "-";	
}
?>        
        </td>
    </tr>         
    <tr>
        <td class="tableleft">不通过原因：</td>
        <td>
        <textarea name="checkno_yuanyin" id="checkno_yuanyin" style="width:95%;height:50px;:" ></textarea>
        
        </td>        
    </tr>      	
<?php
if(count($dylist)>0){
?>
    <tr>
        <td class="tableleft">党员资料库：</td>
        <td>
<?php 
foreach($dylist as $v){
	echo $v["realname"]."|";
	echo $v["idcard"]."|";
	echo $v["zzb_title"];
	if($v["realname"]==$model["realname"] && 
	   $v["idcard"]==$model["idcard"]){
		echo "<b>[完全匹配]</b>";
	}
	echo "<br/>";
	
}
?>       
        
        </td>        
    </tr>
<?php 
}
?>	
    <tr>   
        <td colspan="2">
<div style="text-align: center">        
<button class="btn button-danger" type="button" id="btn_dangyuan" onclick="showbox(1,'审核为党员？',this)">审为党员</button>
<button class="btn button-success" type="button" id="btn_rudangjijifenzi" onclick="showbox(2,'审核为非本区党员？',this)">审为非本区党员</button>
<button class="btn button-warning" type="button" id="btn_butongguo" onclick="showbox(3,'审核为不通过？',this)">审为不通过</button>
</div>
        </td>
    </tr>
</table>
</form>


</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>


function showbox(type,msg,btnobj){
	BUI.use('bui/overlay',function(overlay){	
	    BUI.Message.Show({
	        title : '确认操作',
	        msg : msg,
	        icon : 'question',
	        //mask:true,
	        buttons : [
	          {
	            text:'确认',
	            elCls : 'button button-primary',
	            handler : function(){
	            	btnobj.style.enabled=false;
	            	shenpi(type);
	            }
	          },
	          {
	            text:'取消',
	            elCls : 'button',
	            handler : function(){
	              this.close();
	            }
	          }
	
	        ]
	      });	
	});
}

function shenpi(type){
	
	if(type=="" || type==0){		
		parent.parent.tip_show('没选中。',2,1000);
	}
	else{
		if(type==3){
			//要填写不通过原因						
			if($("#checkno_yuanyin").val()==""){
				parent.parent.tip_show('填写不通过原因。',2,1000);
				$("#checkno_yuanyin").focus();
				return false;
			}				
		}			
		uid ="<?php echo $model["uid"];?>";			
		var ajax_url = "<?php echo site_url("user/regview");?>";
		$.ajax({
            type: "post",
            url: ajax_url,
            data: {'opt':type,'uid':uid,'checkno_yuanyin':$("#checkno_yuanyin").val()},
            cache: false,
            dataType: "text",
            //async:false,
            success: function(data) {                                
                if (data == "yes") {
                	parent.tip_show('操作成功。',0,2000);                       
                	setTimeout("window.location.reload();",2000);                	
                }
                 else {
                	parent.tip_show(data,0,2000);                
                	//setTimeout("window.location.reload();",2000);                	
                    return false;                    
                }
            },
            beforeSend: function() {
                //$("#result_").html('<font color="red"><img src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Images/progressbar_microsoft.gif"></font>');
            },
            error: function() {
                alert('服务器繁忙请稍。。。。');
            }
        });		
		
	}		
}
</script>
