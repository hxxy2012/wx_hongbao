<!DOCTYPE HTML>
<html>
 <head>
  <title>失败页面</title>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script>
  var timeout = "<?php echo $timeout ;?>";
  if(timeout>0){
	window.setTimeout("window.location.href='<?php echo $gourl;?>'",($timeout*1000));
  }
  </script> 
  <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
 </head>
 <body>
  
  <div class="container">
    <div class="row">
      <div class="span10">
        <div class="tips tips-large tips-warning">
          <span class="x-icon x-icon-error">×</span>
          <div class="tips-content">
            <h2><?php echo $message ;?></h2>
            <p class="auxiliary-text">
              页面将在 <span class="wait"><?php echo  $timeout ;?></span> 秒后自动关闭，如果不想等待请
                            <?php							
			  if(substr($url,0,4)=="http"){
				  echo '<a href="'.$url.'">这里</a> ';
			  }
			  else{
			  ?>
              <a target="_top" href="<?php echo site_url($url);?><?php echo $params ;?>">这里</a> 
              <?php
			  }
			  ?>
               关闭
            </p>
            <p>
			<!--
              <a class="page-action" data-type="setTitle" title="编辑用户个性化功能权限" href="userFunc.html">编辑用户个性化功能权限</a>
              <a class="page-action" data-type="setTitle" title="配置用户数据权限" href="userData.html">配置用户数据权限</a>
              <a class="page-action" data-type="setTitle" title="返回用户管理" href="userManage.html">返回用户管理</a>
            -->
			</p>
          </div>
        </div>
      </div> 
    </div>
  </div>


<body>
</html>  