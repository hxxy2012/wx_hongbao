<!doctype html>
<html>
<head>
	<title><?php if(isset($art_model['title']))echo $art_model['title'].'-';?><?php echo $config["site_fullname"];?></title>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
</head>
<body>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>
	<div class="pc_content">
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/com_left_menu.php'); ?>
		<div class="pc_content_layout">
			<h3><?php echo $art_model['title'];?></h3>
			<h4><span>发布时间：<?php echo date('Y-m-d H:i:s',$art_model['post']);?></span><span>来源：<?php echo $config["site_fullname"];?></span></h4>
			<?php echo $art_model['content'];?>
			<a class="layout-return" href="javascript:history.go(-1);">【 返回 】</a>
		</div>
	</div>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>


</body>
</html>