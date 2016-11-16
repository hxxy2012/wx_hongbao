<!doctype html>
<html>
<head>
	<title>搜索-<?php echo $config["site_fullname"];?></title>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
</head>
<body>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>
	<div class="pc_list">
		<div>
			<?php $this->load->view(__TEMPLET_FOLDER__ . '/home/list_left.php'); ?>
		<!--About yongyou end-->

		<!--news list-->
			<div id="" class="pc_search_list">
				<span class="pc_list_present">当前位置：<a href="<?php echo site_url('home/index');?>">首页</a> >> <a href="javascript:void(0);">搜索结果</a></span>
				<h3 class="pc_list_h3">搜索结果</h3>
				<div class="pc_search_sou">
					<form action="<?php echo site_url('home/search_list')?>" method="get" name="searchForm1">
			            <label>关键字：</label>
			            <input name="keyword_list" type="text" placeholder="请输入您想查询的关键字" value="<?php if(isset($search['title']))echo $search['title'];?>" />
			            <input type="submit" onclick="javascript:searchForm1.submit();" value="搜索" />
			        </form>
					<ul>
						<?php if (count($list['list'])>0): ?>
						<?php foreach ($list['list'] as $key => $value): ?>
							<li>
								<a href="<?php echo site_url('home/content').'?pid='.$value['pid'].'&cid='.$value['category_id'].'&id='.$value['id'].'&backurl='.urlencode($ls);?>">
									<h4><?php echo $value['title'];?></h4>
									<span><?php echo date('Y-m-d',$value['post']);?></span>
								</a>
							</li>
						<?php endforeach ?>
						<?php else: ?>
						<li style="margin-top:10px;">暂无数据</li>
						<?php endif ?>
					</ul>
				</div>
				<div id="" class="pc_mpage">
					<?php echo $list['pager']?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>
</body>
</html>