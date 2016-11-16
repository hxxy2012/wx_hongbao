<div id="" class="aside">
	<h3><?php echo $left_menu['title']?></h3>
	<?php if (isset($left_menu['nexcate'])&&count($left_menu['nexcate'])>0): ?>
	<ul>
	<?php foreach ($left_menu['nexcate'] as $key1 => $value1): ?>
		<li>
			<a href="<?php echo site_url('home/transfer').'?pid='.$left_menu['id'].'&cid='.$value1['id'];?>">
				<?php echo $value1['title']?>
			</a>
		</li>	
	<?php endforeach ?>
	</ul>
	<?php endif ?>
</div>