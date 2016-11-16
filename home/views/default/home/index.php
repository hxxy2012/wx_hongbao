<!doctype html>
<html>
<head>
    <title>首页-<?php echo $config["site_fullname"];?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/headerinc.php'); ?>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="content">
    <div>
        <div class="home_list">
            <div class="home_carousel_figure">
                <ul>
                    <?php foreach ($adv as $key => $value): ?>
                        <li><a href="<?php echo $value['pic_url']?>">
                            <img src="/data/upload/ad/<?php echo $value['pic']?>" alt="<?php echo $value['pic_des']?>"/>
                            <span><span><?php echo $value['pic_des']?></span></span>
                        </a></li>
                    <?php endforeach ?>
                </ul>
                <ol>
                    <?php foreach ($adv as $key => $value): ?>
                        <li><?php echo $key+1;?></li>
                    <?php endforeach ?>
                </ol>
                <script>
                    $(function () {
                        $(".home_carousel_figure").luara({
                            width: "480",
                            height: "334",
                            interval: 4500,
                            selected: "seleted",
                            deriction: "left"
                        });
                    });
                </script>
            </div>
            <div class="home_news_list">
                <h3>工作状态<a href="<?php echo site_url('home/transfer').'?pid=0&cid=48';?>">查看更多</a></h3>
                <ul>
                    <?php foreach ($gzzt as $key => $value): ?>
                        <li>
                            <p><span class="news_day"><?php echo date('m',$value['post'])?></span><span><?php echo date('d',$value['post'])?></span></p>
                            <a href="<?php echo site_url('home/content').'?pid=38&cid=48&id='.$value['id'];?>">
                                <h4><?php echo $value['title'];?></h4>
                                <p>
                                    <?php echo strip_tags($value['content']);?></p>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="home_op_list">
        <div class="home_list">
            <div class="home_notice_list">
                <span>N</span>
                <h3>通知公告<span>otification announcement</span></h3>
                <span><i></i></span>
                <ul>
                    <?php foreach ($tzgg as $key => $value): ?>
                        <li>
                            <a href="<?php echo site_url('home/content').'?pid=38&cid=49&id='.$value['id'];?>">
                                <p><?php echo date('Y-m', $value['post']);?><span><?php echo date('d', $value['post']);?></span></p>
                                <p><?php echo $value['title'];?>
                                </p>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
                <a href="<?php echo site_url('home/transfer').'?pid=38&cid=49';?>">查看更多</a>
            </div>
            <div class="home_notice_list home_p_list">
                <span>P</span>
                <h3>政策法规<span>olicies and regulations</span></h3>
                <span><i></i></span>
                <ul>
                    <?php foreach ($zcfg as $key => $value): ?>
                        <li>
                            <a href="<?php echo site_url('home/content').'?pid=39&cid=39&id='.$value['id'];?>">
                                <i></i>
                                <p><?php echo $value['title'];?></p>
                                <span><?php echo date('y-m-d', $value['post']);?></span>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
                <a href="<?php echo site_url('home/transfer').'?pid=0&cid=39';?>">查看更多</a>
            </div>
        </div>
    </div>
    <div class="home_service_guide">
        <div class="home_list">
            <h3>办事指南</h3>
            <img src="/home/views/static/zcq/images/service_guide.jpg" alt="办事指南"/>
            <ul>
                <?php foreach ($bszn as $key => $value): ?>
                    <li>
                        <a href="<?php echo site_url('home/content').'?pid=40&cid=40&id='.$value['id'];?>">
                            <h4><?php echo $value['title'];?></h4>
                            <p class="sg_omit">
                                <?php echo strip_tags($value['content']);?></p>
                        </a>
                        <span><?php echo '0' . ($key+1);?></span>
                    </li>
                <?php endforeach ?>
            </ul>
            <a href="<?php echo site_url('home/transfer').'?pid=0&cid=40';?>">查看更多</a>
        </div>
    </div>
    <div class="home_prefecture">
        <div class="home_list">
            <a href="<?php echo site_url('home/transfer').'?pid=74&cid=46';?>"><img src="/home/views/static/zcq/images/nigeria_prefecture.jpg" alt="尼日利亚专区"/></a>
            <a href="<?php echo site_url('home/transfer').'?pid=74&cid=47';?>"><img src="/home/views/static/zcq/images/south_africa_prefecture .jpg" alt="南非专区"/></a>
        </div>
    </div>
    <div class="home_venture fix">
        <div class="home_list">
            <h3>风险防范</h3>
            <img src="/home/views/static/zcq/images/venture.jpg" alt="风险防范"/>
            <ul>
                <?php foreach ($fxff as $key => $value): ?>
                    <li>
                        <a href="<?php echo site_url('home/content').'?pid=43&cid='.$value['category_id'].'&id='.$value['id'];?>"><?php echo $value['title'];?></a>
                        <span><?php echo date('Y-m-d',$value['post']);?></span>
                    </li>
                <?php endforeach ?>
            </ul>
            <a href="<?php echo site_url('home/transfer').'?pid=0&cid=43';?>">查看更多</a>
        </div>
    </div>
    <div class="home_facilitating_agency">
        <div class="home_list" id="home_f_a">
            <h3>服务机构</h3>
            <ul class="home_fa_ul" id="home_fa_ul">
                <?php foreach ($fwjg_next_cat as $key => $value): ?>
                    <?php if ($key==0): ?>
                        <li class="licur"><a href="<?php echo site_url('home/transfer').'?pid=45&cid='.$value['id'];?>"><?php echo $value['title'];?></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo site_url('home/transfer').'?pid=45&cid='.$value['id'];?>"><?php echo $value['title'];?></a></li>
                    <?php endif ?>
                <?php endforeach ?>
            </ul>
            <?php foreach ($fwjg_next_cat_arr as $key => $value): ?>
                <?php if ($key==0): ?>
                    <div id="hfa_div" class="hfa_div curdiv fix">
                <?php else: ?>
                    <div id="hfa_div" class="hfa_div">
                <?php endif ?>
                    <ul class="hfa_u">
                        <!-- 循环输出缩略 -->
                        <?php foreach ($value as $k => $v): ?>
                            <li>
                                <a href="<?php echo site_url('home/content').'?pid=45&cid='.$v['category_id'].'&id='.$v['id'];?>">
                                    <span class="jqthumb" style="display:block;width: 230px; height: 68px; position: relative; overflow: hidden; display: block; opacity: 1;">
                                        <span style="display:block;width: 100%; height: 100%; position: absolute; top: 0%; left: 0%; background-image: url(/<?php echo $v['thumb'];?>); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
                                            
                                        </span>
                                    </span>
                                    <!-- <img style="height:79px;" src="/<?php echo $v['thumb'];?>" alt="<?php echo $v['title']?>"/> -->
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
           
        </div>
        <script type="text/javascript">
            window.onload = function () {
                var mytab = document.getElementById("home_f_a");
                var myul = mytab.getElementsByTagName("ul")[0];
                var myli = myul.getElementsByTagName("li");
                var mydiv = mytab.getElementsByTagName("div");
                //  alert(mydiv.length)
                for (i = 0, len = myli.length; i < len; i++) {
                    myli[i].index = i;
                    myli[i].onmouseover = function () {
                        for (var n = 0; n < len; n++) {
                            myli[n].className = "";
                            mydiv[n].className = "hfa_div";
                        }
                        this.className = "licur";
                        mydiv[this.index].className = "";
                    }
                }
            }
        </script>
    </div>
</div>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>
</body>
</html>