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
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Css/style.css" />   
<link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>   
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>   
<script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>   
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


.sublist{
		moz-user-select: -moz-none;
		-moz-user-select: none;
		-o-user-select:none;
		-khtml-user-select:none;
		-webkit-user-select:none;
		-ms-user-select:none;
		user-select:none;
		cursor:pointer;
	display: inline-block;
    position: relative;
    margin: 10px;
    padding: 0 20px;
    text-align: center;
    text-decoration: none;
    font:12px/25px Arial, 宋体;
    text-shadow: 1px 1px 1px rgba(255,255,255, .22);
    -webkit-border-radius:10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    -webkit-box-shadow: 1px 1px 1px rgba(0,0,0, .29), inset 1px 1px 1px rgba(255,255,255, .44);
    -moz-box-shadow: 1px 1px 1px rgba(0,0,0, .29), inset 1px 1px 1px rgba(255,255,255, .44);
    box-shadow: 1px 1px 1px rgba(0,0,0, .29), inset 1px 1px 1px rgba(255,255,255, .44);
    -webkit-transition: all 0.15s ease;
    -moz-transition: all 0.15s ease;
    -o-transition: all 0.15s ease;
    -ms-transition: all 0.15s ease;
    transition: all 0.15s ease;		
	    

 
	border:1px #CCCCCC solid;
    background: #f3f3f3; /* Old browsers */

    background: -moz-linear-gradient(top,  #ffffff 0%, #f3f3f3 70%); /* FF3.6+ */

    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f3f3f3), color-stop(70%,#f3f3f3)); /* Chrome,Safari4+ */

    background: -webkit-linear-gradient(top,  #ffffff 0%,#f3f3f3 70%); /* Chrome10+,Safari5.1+ */

    background: -o-linear-gradient(top,  #ffffff 0%,#f3f3f3 70%); /* Opera 11.10+ */

    background: -ms-linear-gradient(top,  #ffffff 0%,#f3f3f3 70%); /* IE10+ */

    background: linear-gradient(top,  #ffffff 0%,#f3f3f3 70%); /* W3C */
	
}


    </style>
<script>
function selfun(obj){	
	$(obj)[0].checked = !$(obj)[0].checked;
	if(!$(obj)[0].checked){
		//刷新页面
		//window.location.href = 	window.location.href+"&delid="+$(obj).val();
		$(obj).next("span").css("font-weight","");
		$(obj).next("span").css("color","");
	}
	else{
		$(obj).next("span").css("font-weight","bold");		
		$(obj).next("span").css("color","blue");		
	}
}

//全选
function selsub(pid){	
	$("input[optid='pid"+pid+"']").each(function(index, element) {		
        $(this)[0].checked=true;
		$(this).next("span").css("font-weight","bold");		
		$(this).next("span").css("color","blue");							
    });
}
//反选
function selsub2(pid){
	$("input[optid='pid"+pid+"']").each(function(index, element) {		
		if($(this)[0].checked)
		{			
			$(this)[0].checked=false;
			$(this).next("span").css("font-weight","");		
			$(this).next("span").css("color","");							
		}
		else{
			$(this)[0].checked=true;
			$(this).next("span").css("font-weight","bold");		
			$(this).next("span").css("color","blue");										
		}
    });	
}
</script>   
</head>
<body>
<div class="form-inline definewidth m20">
<form method="post">
<div style="background:#cccccc;padding:3px;">
<?php
foreach($mainlist as $k=>$v){
	echo "<a href='#p".$v["id"]."' title='跳到：".$v["name"]."'>";

	echo $v["name"];		

	echo "</a>";
	echo "　";
}
?>
</div>

<div style="padding:3px;">

<?php
foreach($mainlist as $kk=>$vv){
	echo "<div style=\"background:#f3f3f3;padding:3px;\">";
	echo "<a id='p".$vv["id"]."'></a>";
	echo $vv["name"];
	
	echo "　　";	
	echo '<span style="cursor:pointer" onClick="selsub('.$vv["id"].')"';
	echo '>';
	echo "全选";
	echo "</span>";
	echo " ";
	echo '<span style="cursor:pointer" onClick="selsub2('.$vv["id"].')"';
	echo '>';	
	echo "反选";
	echo "</span>";
	echo "</div>\n";
	if(count($vv["sublist"])>0){
		foreach($vv["sublist"] as $k=>$v){
			echo "<div class='sublist' onClick=\"selfun($(this).find('input'));\">";
			if(is_array($sel) && in_array($v["id"],$sel)){		
				echo "<input type=\"checkbox\" optid='pid".$v["pid"]."' style='display:none;margin:0;'  name='id[]' checked value=\"".$v["id"]."\"/>";
			}
			else{
				echo "<input type=\"checkbox\" optid='pid".$v["pid"]."' style='display:none;margin:0;' name='id[]' value=\"".$v["id"]."\"/>";
			}			
			if(is_array($sel) && in_array($v["id"],$sel)){		
				//echo "<b>";
			}
			echo "<span";
			if(is_array($sel) && in_array($v["id"],$sel)){		
				echo " style=\"color:blue;font-weight:bold;\"";	
			}
			echo ">";
			echo " ".$v["name"];		
			echo "</span>";
			if(is_array($sel) && in_array($v["id"],$sel)){		
				//echo "</b>";		
			}	
			echo "</div>\n";		
		}
	}
	
}
?>
</div>

<div style="text-align:center; clear:both;">
<button class="button button-success" type="submit">保存选中</button>
</div>
</form>

</div>

</body>
</html>
<script>
	$("#btn_open_pro").on("click",function(){
			layer.open({
				type: 2,
				area: ['700px', '530px'],
				fix: false, //不固定
				maxmin: true,
				content: '<?php echo site_url("swj_admin/prolist");?>'
			});	
	});
</script> 	