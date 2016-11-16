<div class="header">
    <div class="top">
        <ul>







            <li class="top_time">
                <p>欢迎光临<span id="top_time_see"></span></p>
            </li>
            <li class="top_ser membl_top_ser">







                <?php
                if(isset($sess["username"])){

                ?>



                    <p>欢迎&nbsp;<span><?php echo $sess["realname"]==""?$sess["username"]:$sess["realname"];?></span><span>(<?php echo $sess["usertype_name"];?>)</span></p>
                    <?php
                    echo "&nbsp;<a href='".site_url("admin/index")."' class=\"entry\">后台</a>";
                    echo "&nbsp;<a href='".site_url("login/logout")."' class=\"register\">退出</a>";

                    ?>
                    <?php
                }else {
                    ?>
                    <a href="<?php echo site_url("login/index"); ?>" class="entry">会员登录</a>
                    <a href="<?php echo site_url("home/reg"); ?>" class="register">会员注册</a>
                <?php
                }


                ?>
                <form method="get" action="<?php echo site_url('home/search_list')?>" name="searchForm">
                    <input type="text" placeholder="搜索" name="keywords"/>
                    <a href="javascript:searchForm.submit();"><i class="iconfont">&#xe620;</i></a>
                </form>

            </li>
        </ul>
    </div>
    <div class="banner">
        <div>
            <h1><?php //echo $config["site_fullname"];?></h1>
            <img src="/home/views/static/zcq/images/two-dimension-code.jpg" alt="二维码"/>
        </div>
    </div>
    <div class="nav">
        <ul class="nav_n_ul">
            <li><a href="<?php echo site_url('home/index');?>">首页</a></li>
            <?php foreach ($finfo['menu'] as $key => $value): ?>
                <?php if($value['id']==37) continue;?>
                <li <?php if($value['title']=='投资环境')echo 'class="nav_touz"';?>>
                    <a href="<?php echo site_url('home/transfer').'?pid=0&cid='.$value['id']?>"><?php echo $value['title'];?></a>
                    <?php if (!empty($value['nexcate'])&&count($value['nexcate'])>0): ?>
                        <!-- 存在二级栏目 -->
                        <ul>
                        <?php foreach ($value['nexcate'] as $key1 => $value1): ?>
                            <li>
                                <a href="<?php echo site_url('home/transfer').'?pid='.$value['id'].'&cid='.$value1['id']?>"><?php echo $value1['title'];?></a>
                            </li>
                        <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>