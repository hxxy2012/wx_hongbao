<!doctype html>
<html>
<head>
    <title><?php if (isset($model['title'])) echo $model['title'] . '-'; ?><?php echo $config["site_fullname"]; ?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
</head>
<body>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>
<div class="pc_list">
    <div>
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/zcq/front/com_left_menu.php'); ?>
        <!--news list-->
        <div id="" class="pc_news_list2">
            <span class="pc_list_present">当前位置：<a href="<?php echo site_url('home/index'); ?>">首页</a>
                >> <?php
                $url = site_url('home/transfer') . "?pid=0&cid={$pcateInfo['id']}";
                echo "<a href='{$url}'>{$pcateInfo['title']}</a>";
                //如果二级菜单大于0,继续打印位置
                if (count($cMenuInfo) > 0) {
                    $href = site_url('home/transfer') . "?pid={$pcateInfo['id']}&cid={$model['id']}&backurl=" . urlencode($ls);
                    echo ">><a href='{$href}'>{$model['title']}</a>";
                }
                ?>
                <!--                <a href="-->
                <?php //echo site_url('home/transfer') . '?pid=' . $pcateInfo['id'] . '&cid=' . $model['id'] . '&backurl=' . urlencode($ls); ?><!--">-->
                <?php //echo $model['title'] ?><!--</a>-->
            </span>
            <h3 class="pc_list_h3"><?php echo $model['title'] ?></h3>
            <ul>
                <?php foreach ($list['list'] as $key => $value): ?>
                    <li>
                        <a href="<?php echo site_url('home/content') . '?pid=' . $pcateInfo['id'] . '&cid=' . $model['id'] . '&id=' . $value['id'] . '&backurl=' . urlencode($ls); ?>">
                            <h4><?php echo date('d', $value['post']); ?>
                                <span><?php echo date('Y-m', $value['post']); ?></span></h4>
                            <h5><?php echo $value['title']; ?></h5>
                            <p><?php echo strip_tags($value['content']); ?></p>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
            <!-- 分页 -->
            <div id="" class="pc_mpage">
                <?php echo $list['pager']; ?>
            </div>
        </div>

    </div>
</div>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>