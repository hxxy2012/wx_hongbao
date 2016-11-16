<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>类目添加</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/laydate/laydate.js"></script>
	
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
<body class="definewidth m20">
<form action="" method="post"   name="myform" id="myform" onSubmit="return chkform()">
<input type="hidden" name="id" value="<?php echo $model["id"];?>">
<input type="hidden" name="ls" value="<?php echo $ls;?>">
<table class="table table-bordered table-hover m10">

    <tr>
        <td class="tableleft">项目</td>
        <td><?php echo $promodel["title"];?></td>
    </tr>
    <tr >
        <td class="tableleft">申报模板</td>
        <td><?php echo $model["template_title"];?></td>
    </tr>
    <tr>
        <td valign="top" class="tableleft">申报单位</td>
        <td><?php echo $model["danwei"];?></td>
    </tr>
    <tr>
      <td class="tableleft">当前状态</td>
      <td><?php echo $checkstatus[$model["checkstatus"]];?></td>
    </tr>
    <tr>
      <td class="tableleft">退回状态</td>
      <td>
      <?php
	  foreach($checkstatus as $k=>$v){
		  if($k=="99") continue;
		 echo "<input type='radio' name='tuihui_status' value='".$k."'";
		 if($k==$model["checkstatus"]){ echo " disabled='true' ";}
		 echo " />";
		 if($k==$model["checkstatus"]){ echo "<span style='color:#cccccc'>";}
		 echo $v;
		 if($k==$model["checkstatus"]){ echo "</span>";}
		 echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	  }
	  ?>
　</td>
    </tr>
    <tr>
        <td valign="top" class="tableleft">退回备注</td>
        <td>
            <textarea style="width:300px; height:100px;" id="checkbeizhu" name="checkbeizhu" placeholder="退回的原因"></textarea>
        </td>
    </tr>
    <tr>
      <td class="tableleft">恢复附件</td>
      <td>
<input type="checkbox" name="fj_back" value="yes" />恢到退回状态时的附件
      </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" id="btnSave">提交</button> &nbsp;&nbsp;
			<a class="btn btn-warning" href="#" onClick="top.topManager.closePage();">关闭</a>  
        </td>
    </tr>
</table>
</form>
<script>
function chkform(){
	if($("input[name='tuihui_status']:checked").length==0){
		parent.tip_show('请选择退回状态',1,1000);
		return false;	
	}	
}
</script>
</body>
</html>
