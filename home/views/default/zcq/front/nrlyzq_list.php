<!doctype html>
<html>
	<head>
		<title><?php echo $config["site_fullname"];?>-尼日利亚专区</title>
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/headerinc_comzq.php'); ?>		
	</head>
	<body>
		<!--header-->
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/header_nrly.php'); ?>
		<!--header end-->
		<!--content-->
		<div class="pci_list ng_pci_list">
			<span class="current_location">当前位置：<a href="<?php echo site_url('home/transfer').'?pid='.$pcateInfo['id'].'&cid='.$model['id'].'&backurl='.urlencode($ls);?>"><?php echo $model['title']?></a></span>
			<h3>
				<p>
					<i class="iconfont">&#xe678;</i>
					<span class="pci_list_title">
						<?php echo $model['title']?>
						<span><?php echo $model['title_en']?></span>
					</span>
				</p>
			</h3>
			<ul class="pci_list_record">
				<?php foreach ($list['list'] as $key => $value): ?>
					<li>
						<a href="<?php echo site_url('home/content').'?pid='.$pcateInfo['id'].'&cid='.$model['id'].'&id='.$value['id'].'&backurl='.urlencode($ls);?>">
							<span class="pci_list_time"><span><?php echo date('d', $value['post']);?></span><?php echo date('Y-m', $value['post']);?></span>
							<h4><?php echo $value['title'];?></h4>
							<p><?php echo strip_tags($value['content']);?></p>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
			<div class="pci_page">
				<?php echo $list['pager'];?>
			</div>
		</div>
		<!--content end-->
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/footer_nrly.php'); ?>
	</body>
</html>