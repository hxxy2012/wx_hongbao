<!doctype html>
<html>

<head>
	<title><?php echo $config["site_fullname"];?></title>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/headerinc'); ?>
</head>
<body>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/menu_top'); ?>
	<div class="mobile_list_con mob_marauto fix">
		<!-- <h3 class="mob_list_contil"><span>工作动态</span></h3> -->
		<ul class="mob_list_ulil fix">
			<?php $flag_list=0;?>
			<?php foreach ($list['list'] as $key => $value): ?>
				<?php $flag_list=1;?>
				<li class="fix">
					<a href="<?php echo site_url('home_wx/content')."?pid={$pid}&cid={$cid}&id={$value['id']}";?>" class="fix">
						<img src="/<?php if($value['thumb'])echo $value['thumb'];else echo 'data/default/default.jpg';?>" />
						<h4><?php echo $value['title'];?></h4><span><?php echo date('Y-m-d', $value['post']);?></span>
					</a>
				</li>
			<?php endforeach ?>
			<?php if ($flag_list==0): ?>
				<li>暂无内容</li>
			<?php endif ?>
		</ul>
		<div class="mob_list_page">
			<!-- <a href="#" class="mob_list_pprev boradius">上一页</a>
			<p><span>1</span> / <span>5</span></p>
			<a href="#" class="mob_list_pnext boradius">下一页</a> -->
			<?php echo $list['pager'];?>
		</div>
	</div>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/footer'); ?>
</body>
</html>