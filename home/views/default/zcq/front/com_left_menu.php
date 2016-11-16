<div id="" class="aside">
    <h3><?php echo $pcateInfo['title']; ?></h3>
    <?php if (is_array($cMenuInfo) && count($cMenuInfo) >= 0): ?>
        <ul>
            <?php if (count($cMenuInfo) == 0) {
                //add by 辉 2016/8/31，多输出一下li
                $url = site_url("home/transfer") . "?pid={$pcateInfo['pid']}&cid={$pcateInfo['id']}";
                echo "<li><a href='{$url}'>{$pcateInfo['title']}</a></li>";
            }
            ?>
            <?php foreach ($cMenuInfo as $key => $value): ?>
                <li>
                    <a <?php if ($value['id'] == $model['id']) echo 'class="lk_front_menu_on"'; ?>
                        href="<?php echo site_url('home/transfer') . '?pid=' . $pcateInfo['id'] . '&cid=' . $value['id'] ?>">
                        <?php echo $value['title']; ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>
<!--当前一级栏目高亮-->
<script type="text/javascript">
    $(document).ready(function () {

        var curText = '<?php echo $pcateInfo['title']?>';
        $('div.nav ul.nav_n_ul').find('li a').each(function () {
            var $this = $(this);
            var text = $this.text();
            if (curText.indexOf(text) >= 0) {
                $this.addClass("cur");
            }
        })
    })
</script>