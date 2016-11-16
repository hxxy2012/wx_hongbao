<!doctype html>
<html>

<head>
	<title><?php echo $config["site_fullname"];?></title>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/headerinc'); ?>
</head>
<body>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/menu_top'); ?>
	<div class="mobile_con_in mob_marauto">
		<h3><?php echo $art_model['title'];?></h3>
		<hr/>
		<?php echo $art_model['content'];?>
		<a href="javascript:history.go(-1);" class="mob_con_return btn_al">返回</a>
	</div>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/footer'); ?>
</body>
</html>