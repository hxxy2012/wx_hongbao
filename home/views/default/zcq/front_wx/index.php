<!doctype html>
<html>

<head>
	<title><?php echo $config["site_fullname"];?></title>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/headerinc'); ?>
</head>
<body>
	<!-- 导航开始 -->
	<div class="mobile_header fix">
		<div class="memu_in_top porela fix">
			<img src="/home/views/static/zcq/wx/images/memu_header_bg.jpg" class="menu_header_bg">
			<h1 class="mob_marauto"><a href="index.html"><img src="/home/views/static/zcq/wx/images/h1_bg.png" alt="" title="" /></a></h1>
		</div>
		<div class="memu_in_sear mob_marauto fix">
			<form action="<?php echo site_url('home_wx/search_list');?>" method="get" name="search_index" class="memu_in_searform porela ">
				<input type="text" name="keywords" placeholder="请输入要搜索的关键字" class="boradius menu_in_keytext" />
				<a href="javascript:search_index.submit();" class="iconfont">&#xf00ae;</a>
			</form>
		</div>
		<div class="memu_in_nav fix mob_marauto">
			<ul>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=38&cid=48';?>" alt="工作动态"><i class="iconfont">&#xe66a;</i>工作动态</a></li>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=38&cid=49';?>" alt="通知公告"><i class="iconfont">&#xe696;</i>通知公告</a></li>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=0&cid=39';?>" alt="政策法规"><i class="iconfont">&#xe6cd;</i>政策法规</a></li>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=0&cid=40';?>" alt="办事指南"><i class="iconfont">&#xe601;</i>办事指南</a></li>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=0&cid=41';?>" alt="项目对接"><i class="iconfont">&#xe6da;</i>项目对接</a></li>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=43';?>" alt="风险防范"><i class="iconfont">&#xe603;</i>风险防范</a></li>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=44';?>" alt="扶持政策"><i class="iconfont">&#xf0008;</i>扶持政策</a></li>
				<li><a href="<?php echo site_url('home_wx/transfer').'?pid=45';?>" alt="服务机构"><i class="iconfont">&#xe67e;</i>服务机构</a></li>
			</ul>

		</div>
	</div>
	<!-- 导航结束 -->
	<div class="mobile_in_con mob_marauto fix">
		<div class="mobile_lat_infor">
			<ul class="mobole_in_tit fix ">
				<li class="inticur">工作动态</li>
				<li>通知公告</li>
				<li>办事指南</li>
			</ul>
			<div class="mobile_in_cona">
				<div class="mobile_in_work mobile_in_show">
					<h3 style="display:none">工作动态</h3>
					<ul>
						<?php foreach ($gzzt as $key => $value): ?>
							<?php if ($key==0): ?>
								<li class="porela"><a href="content.html">
									<img src="/<?php if($value['thumb'])echo $value['thumb'];else echo 'data/default/default.jpg';?>" alt="<?php echo $value['title'];?>" />
									<h4><?php echo $value['title'];?></h4>
								</a></li>
							<?php else: ?>
								<li><a href="<?php echo site_url('home_wx/content')."?pid=38&cid=49&id={$value['id']}";?>">
									<span class="mob_con_num boradius btn_al">0<?php echo $key;?></span>
									<h4><?php echo $value['title'];?></h4>
								</a></li>
							<?php endif ?>
						<?php endforeach ?>
					</ul>
					<a href="<?php echo site_url('home_wx/transfer').'?pid=38&cid=48';?>" alt="工作动态" class="see_more boradius">查看更多</a>
				</div>
				<div class="mobile_in_nan mobile_in_show mobile_in_nanu">
					<h3 style="display:none">通知公告</h3>
					<ul>
						<?php foreach ($tzgg as $key => $value): ?>
							<li><a href="<?php echo site_url('home_wx/content')."?pid=38&cid=49&id={$value['id']}";?>" class="fix">
								<img src="/<?php if($value['thumb'])echo $value['thumb'];else echo 'data/default/default.jpg';?>" alt="<?php echo $value['title'];?>" />
								<h4><?php echo $value['title'];?></h4>
							</a></li>
						<?php endforeach ?>
					</ul>
					<a href="<?php echo site_url('home_wx/transfer').'?pid=38&cid=49';?>" alt="" class="see_more boradius">查看更多</a>
				</div>
				<div class="mobile_in_nan mobile_in_show mobile_in_servg">
					<h3 style="display:none">办事指南</h3>
					<ul>
						<?php foreach ($bszn as $key => $value): ?>
							<li><a href="<?php echo site_url('home_wx/content')."?pid=0&cid=40&id={$value['id']}";?>">
								<span class="mob_con_num boradius btn_al">0<?php echo $key+1;?></span>
								<h4><?php echo $value['title'];?></h4>
							</a></li>
						<?php endforeach ?>
					</ul>
					<a href="<?php echo site_url('home_wx/transfer').'?pid=0&cid=40';?>" alt="" class="see_more boradius">查看更多</a>
				</div>
			</div>
		</div>
		<!-- 风险防范开始 -->
		<div class="mobile_in_rifal mobile_in_risk fix">
			<h3 class="mob_in_ritil"><a href="<?php echo site_url('home_wx/transfer').'?pid=43';?>"><span>风险防范</span><img src="/home/views/static/zcq/wx/images/title_right_bg.png" /></a></h3>
			<ul>
				<?php foreach ($fxff as $key => $value): ?>
					<li><a href="<?php echo site_url('home_wx/content')."?pid=0&cid=43&id={$value['id']}";?>">
						<h4><?php echo $value['title'];?></h4></a>
					</li>
				<?php endforeach ?>
			</ul>
			<a href="<?php echo site_url('home_wx/transfer').'?pid=43';?>" alt="风险防范" class="see_more boradius">查看更多</a>
		</div>
		<!-- 风险防范结束 -->
		<!-- 服务机构开始 -->
		<div class="mobile_in_rifal fix">
			<h3 class="mob_in_ritil"><a href="<?php echo site_url('home_wx/transfer').'?pid=45';?>"><span>服务机构</span><img src="/home/views/static/zcq/wx/images/title_right_bg.png" /></a></h3>
			<ul class="mobile_fa_ul fix">
				<?php foreach ($fwjg_next_cat as $key => $value): ?>
                    <?php if ($key==0): ?>
                        <li class="mobile_falicur"><?php echo $value['title'];?></li>
                    <?php elseif($key==4): ?>
						<li class="mobile_fa_inve"><?php echo $value['title'];?></li>
                    <?php else: ?>
                        <li><?php echo $value['title'];?></li>
                    <?php endif ?>
                <?php endforeach ?>
			</ul>
			<div class="mobile_faci_ag fix ">
				<?php foreach ($fwjg_next_cat_arr as $key => $value): ?>
	                <?php if ($key==0): ?>
	                    <div class="mobile_fa_div mobile_fa_cur">
							<ul class="hfa_u">
								<?php foreach ($value as $k => $v): ?>
									<li><a href="<?php echo site_url('home_wx/content')."?pid=45&cid=45&id={$v['id']}";?>"><?php echo $v['title'];?></a></li>
								<?php endforeach ?>
							<a href="<?php echo site_url('home_wx/transfer').'?pid=45';?>" alt="" class="see_more boradius">查看更多</a>
						</div>
	                <?php else: ?>
	                    <div class="mobile_fa_div">
							<ul class="hfa_u">
								<?php foreach ($value as $k => $v): ?>
									<li><a href="<?php echo site_url('home_wx/content')."?pid=45&cid=45&id={$v['id']}";?>"><?php echo $v['title'];?></a></li>
								<?php endforeach ?>
							</ul>
							<a href="<?php echo site_url('home_wx/transfer').'?pid=45';?>" alt="" class="see_more boradius">查看更多</a>
						</div>
	                <?php endif ?>
	            <?php endforeach ?>
			</div>
		</div>
		<!-- 服务机构结束 -->
	</div>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/footer'); ?>
</body>
</html>