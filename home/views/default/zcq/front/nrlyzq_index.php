<!doctype html>
<html>
	<head>
		<title><?php echo $config["site_fullname"];?>-尼日利亚专区</title>
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/headerinc_comzq.php'); ?>
	</head>
	<body>
		<!--header-->
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/header_nrly.php'); ?>
		<!--content-->
		<div class="index_content index_inves_cli ng_index_con" style="overflow: hidden">
			<div>
				<h3>
					<p>
						<i class="iconfont">&#xe600;</i>
						<span class="index_h3_title">投资环境<span>investment environment</span></span>
					</p>
				</h3>
				<?php foreach ($tzhj['list'] as $key => $value): ?>
					<img src="/<?php echo $value['thumb'];?>" alt="<?php echo $value['title']?>" />
					<h4><?php echo $value['title']?></h4>
					<p class="index_introduction"><?php echo strip_tags($value['content']);?></p>
					<a href="<?php echo site_url('home/content').'?pid=46&cid=66&id='.$value['id'].'&backurl='.urlencode($ls);?>" class="see_more sa_see_more">了解详细</a>
					<?php break; ?>
				<?php endforeach ?>
			</div>
		</div>
		<div class="index_content index_inves_pro ng_index_con ng_index_inpro">
			<div>
				<h3>
					<p>
						<i class="iconfont">&#xe678;</i>
						<span class="index_h3_title">招商项目<span>investment projects</span></span>
					</p>
				</h3>
				<ul>
					<?php foreach ($zsxm['list'] as $key => $value): ?>
						<li>
							<a href="<?php echo site_url('home/content').'?pid=46&cid=67&id='.$value['id'].'&backurl='.urlencode($ls);?>">
								<span class="index_serial_time"><span><?php echo date('d',$value['post']);?></span><?php echo date('Y-m',$value['post']);?></span>
								<h4><?php echo $value['title'];?> </h4>
								<p><?php echo strip_tags($value['content']);?></p>
							</a>
						</li>
					<?php endforeach ?>
				</ul>
				<a href="<?php echo site_url('home/transfer').'?pid=46&cid=67'.'&backurl='.urlencode($ls);?>" class="see_more">查看更多</a>
			</div>
		</div>
		<div class="index_content ng_index_con index_content_prosg">
			<div>
				<div class="index_pref_pol">
					<h3>
						<p>
							<i class="iconfont">&#xe620;</i>
							<span class="index_h3_title">优惠政策<span>preferential policy</span></span>
						</p>
					</h3>
					<ul>
						<?php foreach ($yhzc['list'] as $key => $value): ?>
							<li>
								<a href="<?php echo site_url('home/content').'?pid=46&cid=68&id='.$value['id'].'&backurl='.urlencode($ls);?>">
									<span class="index_serial_number"><?php echo '0'.($key+1);?></span>
									<h4><?php echo $value['title'];?></h4>
									<p><?php echo strip_tags($value['content']);?></p>
								</a>
							</li>
						<?php endforeach ?>
					</ul>
					<a href="<?php echo site_url('home/transfer').'?pid=46&cid=68'.'&backurl='.urlencode($ls);?>" class="see_more">查看更多</a>
				</div>
				<div class="index_service_guide ng_index_sg">
					<h3>
						<p>
							<i class="iconfont">&#xe601;</i>
							<span class="index_h3_title">办事指南<span>service guide</span></span>
						</p>
					</h3>
					<ul>
						<?php foreach ($bszn['list'] as $key => $value): ?>
							<li>
								<a href="<?php echo site_url('home/content').'?pid=46&cid=69&id='.$value['id'].'&backurl='.urlencode($ls);?>">
									<span><?php echo date('Y-m', $value['post']);?><span><?php echo date('d', $value['post']);?></span></span>
									<h4><?php echo $value['title'];?></h4>
								</a>
							</li>
						<?php endforeach ?>
					</ul>
					<a href="<?php echo site_url('home/transfer').'?pid=46&cid=69'.'&backurl='.urlencode($ls);?>" class="see_more">查看更多</a>
				</div>
			</div>
		</div>
		<!--content end-->
		<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/footer_nrly.php'); ?>
	</body>
</html>