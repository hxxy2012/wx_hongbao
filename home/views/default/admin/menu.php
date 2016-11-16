<div id="" class="member_background_aside">
    <h3>会员功能</h3>
    <ul>
        <?php //服务机构
        if ($sess["usertype"] == "45064") { ?>
            <li><a href="javascript:void(0);" class="mbs_a <?php if(isset($curset)&&$curset=='user') echo 'curset';?>">机构信息管理<i></i></a>
                <ul class="mbs_in_ul" style="display: none">
                    <li><a href="<?php echo site_url("adminx/zcq_user/editpwd");?>" >修改登录密码</a></li>
                    <li><a href="<?php echo site_url("adminx/zcq_user/editinfo");?>" >注册资料</a></li>
                </ul>
            </li>
            <li><a href="<?php echo site_url('adminx/zcq_survey/index');?>" class="mbs_a <?php if(isset($curset)&&$curset=='survey') echo 'curset';?>">调查问卷</a></li>
            <li><a href="<?php echo site_url("adminx/zcq_fuwu_zixun/lists");?>" class="mbs_a <?php if(isset($curset)&&$curset=='zi_xun') echo 'curset';?>">服务咨询</a></li>
               <li><a href="javascript:void(0);"  class="mbs_a <?php if(isset($curset)&&$curset=='zcq_mail') echo 'curset';?>">站内信<i></i></a>
                <ul class="mbs_in_ul" id="menu_zcq_mail" style="display: none">
                    <li><a href="<?php echo site_url('adminx/zcq_mail/add');?>" class="<?php if(isset($sec_curset)&&$sec_curset=='add'&&$curset=="zcq_mail") echo 'curset';?>" >写信</a></li>
                    <li><a href="<?php echo site_url('adminx/zcq_mail/index');?>" class="<?php if(isset($sec_curset)&&$sec_curset=='index'&&$curset=="zcq_mail") echo 'curset';?>">收信箱</a></li>
                </ul>
            </li>
            <li><a href="<?php echo site_url('admin/index');?>" class="mbs_a <?php if(isset($curset)&&$curset=='admin_index') echo 'curset';?>">系统首页</a></li>
        <?php } ?>

        <?php //企业用户
        if ($sess["usertype"] == "45063") { ?>
            <li><a href="javascript:void(0);" class="mbs_a <?php if(isset($curset)&&$curset=='user') echo 'curset';?>">企业信息管理<i></i></a>
                <ul class="mbs_in_ul" style="display: none">
                    <li><a href="<?php echo site_url("adminx/zcq_user/editpwd");?>">修改登录密码</a></li>
                    <li><a href="<?php echo site_url("adminx/zcq_user/editinfo");?>">注册资料</a></li>
                </ul>
            </li>
            <li><a href="<?php echo site_url('adminx/zcq_survey/index');?>" class="mbs_a <?php if(isset($curset)&&$curset=='survey') echo 'curset';?>">调查问卷</a></li>
            <li><a href="javascript:void(0);" class="mbs_a <?php if(isset($curset)&&$curset=='zi_xun') echo 'curset';?>">服务咨询<i></i></a>
                <ul class="mbs_in_ul" style="display: none">
                    <li><a href="<?php echo site_url("adminx/zcq_fuwu_zixun/start");?>">我要咨询</a></li>
                    <li><a href="<?php echo site_url("adminx/zcq_fuwu_zixun/lists");?>">我的咨询</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0);" class="mbs_a <?php if(isset($curset)&&$curset=='touzi') echo 'curset';?>">对外投资联系表<i></i></a>
                <ul class="mbs_in_ul" id="menu_touzi" style="display: none">
                    <li><a href="<?php echo site_url("adminx/zcq_duiwaitouzi/add");?>">新增</a></li>
                    <li><a href="<?php echo site_url("adminx/zcq_duiwaitouzi/lists");?>">列表</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0);" class="mbs_a  <?php if(isset($curset)&&($curset=='zcq_pro_type' || $curset=='zcq_pro_shenqing')) echo 'curset';?>">资金申请<i></i></a>
                <ul class="mbs_in_ul" id="menu_zcq_pro_type" style="display: none">
                    <li><a href="<?php echo site_url('adminx/zcq_pro_type/index');?>" class="<?php if(isset($sec_curset)&&($sec_curset=='index' || $sec_curset=='add')&&$curset=="zcq_pro_type") echo 'curset';?>" >申请</a></li>
                    <li><a href="<?php echo site_url('adminx/zcq_pro_shenqing/index');?>" class="<?php if(isset($sec_curset)&&($sec_curset=='index' || $sec_curset=='edit')&&$curset=="zcq_pro_shenqing") echo 'curset';?>">我的申请</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0);" class="mbs_a <?php if(isset($curset)&&$curset=='huodong') echo 'curset';?>">活动报名<i></i></a>
                <ul class="mbs_in_ul" id="menu_huodong" style="display: none">
                    <li><a class="<?php if(isset($sec_curset)&&$sec_curset=='hd_list') echo 'curset';?>" href="<?php echo site_url('adminx/zcq_huodong/index');?>">列表</a></li>
                    <li><a class="<?php if(isset($sec_curset)&&$sec_curset=='mime_hd') echo 'curset';?>" href="<?php echo site_url('adminx/zcq_huodong/index').'?userid='.$sess['userid'];?>">我的活动</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0);"  class="mbs_a <?php if(isset($curset)&&$curset=='zcq_mail') echo 'curset';?>">站内信<i></i></a>
                <ul class="mbs_in_ul" id="menu_zcq_mail" style="display: none">
                    <li><a href="<?php echo site_url('adminx/zcq_mail/add');?>" class="<?php if(isset($sec_curset)&&$sec_curset=='add'&&$curset=="zcq_mail") echo 'curset';?>" >写信</a></li>
                    <li><a href="<?php echo site_url('adminx/zcq_mail/index');?>" class="<?php if(isset($sec_curset)&&$sec_curset=='index'&&$curset=="zcq_mail") echo 'curset';?>">收信箱</a></li>
                </ul>
            </li>
            <li><a href="<?php echo site_url('adminx/zcq_datamanage/index');?>" class="mbs_a <?php if(isset($curset)&&$curset=='datamanage') echo 'curset';?>">资料核对</a></li>
            <li><a href="<?php echo site_url('admin/index');?>" class="mbs_a <?php if(isset($curset)&&$curset=='admin_index') echo 'curset';?>">系统首页</a></li>
        <?php } ?>

        <li><a href="<?php echo site_url("login/logout") . "?ls=" . urlencode(site_url("login/index")); ?>"
               class="mbs_a">退出<i></i></a>
        </li>
    </ul>
</div>
<script>
    $(function(){
        //根据点击的菜单显示二级菜单
        var menu = "<?php if(isset($curset)) echo $curset;?>";
        if (menu != '') {
            $('#menu_'+menu).slideDown();
        }

        var $div = $("div.member_background_aside");

        //另一种显示的方法[辉]
        <?php if (isset($controller)){?>

        var controller = '<?php echo $controller;?>';
        var method = '<?php echo $method;?>';
        $div.find("a").each(function () {
            var $this = $(this);
            var href = $this.attr("href");
            if (href.indexOf(method) >= 0 && href.indexOf(controller) >= 0) {
                $this.addClass("curset");
                $this.parents("ul").slideDown();
            }
        });

        <?php }?>

        //用户审核不通过时隐藏菜单
        <?php if ($sess['checkstatus']==99) { ?>

        //找到可用的那个菜单
        var cur_menu;
        $div.find("a").each(function () {
            var $this = $(this);
            var href = $this.attr("href");
            if (href.indexOf("zcq_user") >=0) {
                //最外层的li
                cur_menu = $this.parents("ul").parents("li");
            }
        });

        //遍历删除无权限的菜单
        $div.children("ul").children("li").each(function () {
            var $this = $(this);
            if (!$this.is(cur_menu)) {
                $this.remove();
            }
        });

        <?php }?>
    });
</script>