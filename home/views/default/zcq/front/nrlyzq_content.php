<!doctype html>
<html>
	<head>
		<title><?php if(isset($art_model['title']))echo $art_model['title'].'-';?><?php echo $config["site_fullname"];?>-南非专区</title>
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/headerinc_comzq.php'); ?>		
	</head>
	<body>
		<!--header-->
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/header_nrly.php'); ?>
		<!--header end-->
		<!--content-->
		<div class="pci_content ng_pci_content">
			<h3><?php echo $art_model['title'];?></h3>
			<p class="time-site">
				<span class="release_time">发布时间：<span><?php echo date('Y-m-d H:i:s',$art_model['post']);?></span></span>
				<span class="source">来源：<span><?php if(isset($art_model['title']))echo $art_model['title'].'-';?><?php echo $config["site_fullname"];?></span></span>
			</p>
			<hr />
			<div class="pci_primary_coverage">
				<?php echo $art_model['content'];?>
				<a href="javascript:history.go(-1);" class="pci_content_return">【 返回 】</a>
			</div>
		</div>
		<!--content end-->
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/footer_nrly.php'); ?>
	</body>
</html>