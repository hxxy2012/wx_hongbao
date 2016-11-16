<!doctype html>
<html>

<head>
	<title><?php echo $config["site_fullname"];?></title>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/headerinc'); ?>
</head>
<body>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/menu_top'); ?>
	
	<div class="mobile_list_con mob_sear_list mob_marauto">
		<!-- <h3 class="mob_list_contil"><span>工作动态</span></h3> -->
		<form action="<?php echo site_url('home_wx/search_list');?>" method="get" name="search_list" class="mob_sear_form">
			<input type="text" name="keywords" value="<?php if(isset($search['title'])) echo $search['title'];?>" placeholder="请输入要搜索的关键字"/><input type="button" value="搜索" onclick="javascript:search_list.submit();" class="btn_al" />
		</form>
		<!-- 列表开始 -->
		<ul class="mob_list_ulil fix mob_sear_ulil">
			<?php foreach ($list['list'] as $key => $value): ?>
				<li class="fix">
					<a href="<?php echo site_url('home_wx/content')."?pid={$value['pid']}&cid={$value['cid']}&id={$value['id']}";?>">
						<h4><?php echo $value['title'];?></h4>
					</a>
				</li>
			<?php endforeach ?>
		</ul>
		<div class="mob_list_page">
			<?php echo $list['pager'];?>
		</div>
		<!-- 列表结束 -->
	</div>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front_wx/footer'); ?>
</body>
</html>