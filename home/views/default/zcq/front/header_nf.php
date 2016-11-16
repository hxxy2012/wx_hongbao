<!--header-->
<div class="header">
    <div class="top">
        <ul>
            <li class="top_return sa_top_return">
                <a href="<?php echo site_url('home/index'); ?>">返回平台</a>
                <?php
                //在这里，我们打印从一个专区到另一个专区的按钮
                //$finfo是首页菜单，包含所有栏目
                //$model['id']是当前栏目的id
                foreach ($finfo['menu'] as $pitem) {
                    //确认当前父菜单是国家专区，id是74
                    if ($pitem['id'] == 74 ) {
                        //遍历它的子菜单栏目
                        foreach ($pitem['nexcate'] as $citem) {
                            //如果是当前专区就不打印按钮,$citem['id']=47
                            if ( $citem['id'] != $model['pid'] && $citem['id'] != $model['id']) {
                                $url = site_url('home/transfer') . "?pid={$pitem['id']}&cid={$citem['id']}";
                                echo "<a href='{$url}'>{$citem['title']}</a>";//<li class='top_return sa_top_return'></li>
                            }
                        }
                    }
                }
//                ?>

            </li>
            <li class="top_wel">欢迎来到南非专区 <span id="top_time_see"></span></li>
        </ul>
    </div>
    <div class="banner sa_banner">
        <div>
            <img src="/home/views/static/zcq/zq/images/sa_banner_alt.png" alt="南非专区"/>
        </div>
    </div>
    <div class="nav sa_nav">
        <ul>
            <li><a href="<?php echo site_url('home/transfer') . '?pid=0&cid=47' ?>"><span class="nav_home"><i
                            class="iconfont">&#xe604;</i></span>首 页</a></li>
            <li><a href="<?php echo site_url('home/transfer') . '?pid=47&cid=70'; ?>"><span class="nav_invest"><i
                            class="iconfont">&#xe600;</i></span>投资环境</a></li>
            <li><a href="<?php echo site_url('home/transfer') . '?pid=47&cid=71'; ?>"><span class="nav_investment"><i
                            class="iconfont">&#xe678;</i></span>招商项目</a></li>
            <li><a href="<?php echo site_url('home/transfer') . '?pid=47&cid=72'; ?>"><span class="nav_preferential"><i
                            class="iconfont">&#xe620;</i></span>优惠政策</a></li>
            <li><a href="<?php echo site_url('home/transfer') . '?pid=47&cid=73'; ?>"><span class="nav_work"><i
                            class="iconfont">&#xe601;</i></span>办事指南</a></li>
        </ul>
    </div>
</div>
<!--header end-->