<div id="touch_right">
	<ul>
		<li class="tel"><i class="iconfont">&#xe60c;</i>
			<div class="floating_left hotline">咨询热线：<br> (86-760)89892378</div>
		</li>
		<li class="er"><i class="iconfont">&#xe60d;</i>
			<div class="floating_left floating_ewm">
				<img src="/home/views/static/zcq/images/two-dimension-code.jpg">扫一扫二维码<br/>关注官方微信
			</div>
		</li>
		<li onClick="gotoTop();return false;" class="top"><i class="iconfont">&#xe622;</i>
			<div class="floating_left">返回页首</div>
		</li>
	</ul>
</div>

<div class="footer">
	<div class="friendly_link">
		<form method="get">
			<select onchange="MM_o(this)" name="select"> <!--onchange="jumpMenu(this)"-->
				<option value="">政府网站</option>
				<?php foreach ($finfo['zfwz'] as $key => $value): ?>
					<option value="<?php echo $value['pic_url']?>"><?php echo $value['name'];?></option>
				<?php endforeach ?>
			</select>
			<select onchange="MM_o(this)">
				<option value="">各省市商务主管部门</option>
				<?php foreach ($finfo['zgbm'] as $key => $value): ?>
					<option value="<?php echo $value['pic_url']?>"><?php echo $value['name'];?></option>
				<?php endforeach ?>
			</select>
			<select onchange="MM_o(this)">
				<option value="">友情链接一</option>
				<?php foreach ($finfo['link1'] as $key => $value): ?>
					<option value="<?php echo $value['pic_url']?>"><?php echo $value['name'];?></option>
				<?php endforeach ?>
			</select>
			<select onchange="MM_o(this)">
				<option value="">友情链接二</option>
				<?php foreach ($finfo['link2'] as $key => $value): ?>
					<option value="<?php echo $value['pic_url']?>"><?php echo $value['name'];?></option>
				<?php endforeach ?>
			</select>
		</form>
		<script type="text/javascript">
			function MM_o(selObj) {
				if (selObj.options[selObj.selectedIndex].value != '') {
					window.open(selObj.options[selObj.selectedIndex].value);
				}
			}
		</script>
	</div>
	<div class="contact_address">
		<p>版权所有：中山市商务局 &nbsp;&nbsp;电话：(86-760)89892378，12345&nbsp;&nbsp; 传真：(86-760)89892800<br/>
			地址：中国广东省中山市中山二路57号&nbsp;&nbsp; E-MAIL：<a href="mailto:zs@zsboc.gov.cn">zs@zsboc.gov.cn</a> 粤ICP备14097211号
		</p>
	</div>
</div>