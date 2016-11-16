<div class="mobile_con_header porela fix">
	<div class="memu_con_top fix">
		<div class="memu_top_conl menu_top_icon"><i class="iconfont">&#xe616;</i></div>
		<!-- <h1 class="mobile_con_h1">中山市走出去综合服务信息平台</h1> -->
		<h2 class="mobile_con_toptil"><?php if(isset($cmodel))echo $cmodel['title'];?></h2>
		<div class="menu_top_conr menu_top_icon"><i class="iconfont">&#xf00ae;</i></div>
	</div>
	<div class="memu_con_sear memu_in_sear">
		<form action="<?php echo site_url('home_wx/search_list');?>" method="get" name="com_search" class="memu_con_searf">
			<input type="text" name="keywords" placeholder="请输入要搜索的关键字" class="boradius menu_con_seari" />
			<a href="javascript:com_search.submit();" class="iconfont">&#xf00ae;</a>
		</form>
	</div>
	<div class="memu_con_nav fix">
		<ul>
			<li><a href="<?php echo site_url('home_wx/index');?>" alt="首页">首页</a>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=38&cid=48';?>" alt="工作动态">工作动态</a></li>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=38&cid=49';?>" alt="通知公告">通知公告</a></li>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=0&cid=39';?>" alt="政策法规">政策法规</a></li>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=0&cid=40';?>" alt="办事指南">办事指南</a></li>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=0&cid=41';?>" alt="项目对接">项目对接</a></li>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=43';?>" alt="风险防范">风险防范</a></li>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=44';?>" alt="扶持政策">扶持政策</a></li>
			<li><a href="<?php echo site_url('home_wx/transfer').'?pid=45';?>" alt="服务机构">服务机构</a></li>
		</ul>
	</div>
</div>