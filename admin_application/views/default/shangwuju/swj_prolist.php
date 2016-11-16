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
<script type="text/javascript" src="/home/views/static/js/layer/layer.js"></script>   
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
	
    color: #515151;

 
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
		window.location.href = 	window.location.href+"&delid="+$(obj).val();
	}
}
</script>   
</head>
<body>
<div class="form-inline definewidth m20">
<form method="post">
<div style="background:#f3f3f3;padding:3px;">
<?php
foreach($mainlist as $k=>$v){
	echo "<a href='".site_url("user/prolist")."?pid=".$v["id"]."&sel=".$newid."'>";
	if($pid==$v["id"]){
		echo "<b>";
	}
	echo $v["name"];		
	if($pid==$v["id"]){		
		echo "</b>";		
	}
	echo "</a>";
	echo "　";
}
?>
</div>

<div style="padding:3px;">

<?php
foreach($sublist as $k=>$v){
	echo "<div class='sublist' onClick=\"selfun($(this).find('input'));\">";
	if(is_array($sel) && in_array($v["id"],$sel)){		
		echo "<input type=\"checkbox\" name='id[]' style='margin:0;' checked value=\"".$v["id"]."\"/>";
	}
	else{
		echo "<input type=\"checkbox\" name='id[]' style='margin:0;' value=\"".$v["id"]."\"/>";
	}
	
	if(is_array($sel) && in_array($v["id"],$sel)){		
		echo "<b>";
	}
	echo "<span>";
	echo " ".$v["name"];		
	echo "</span>";	
	if(is_array($sel) && in_array($v["id"],$sel)){		
		echo "</b>";		
	}	
	echo "</div>";		
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