<!doctype html>
<html>
<head>
    <title>活动列表-<?php echo $config["site_fullname"];?></title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="ie-comp">
    <meta name="keywords" content="<?php echo $config["site_keywd"];?>">
    <link rel="stylesheet" href="/home/views/static/zcq/css/style.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/home/views/static/zcq/iconfont/iconfont.css" />
    <!--[if lt IE 8]>
    <script>
        alert('本网站已不支持低于IE8的浏览器,请选用更好版本的IE浏览器或Google Chrome浏览器');
    </script>
    <![endif]-->
    <!--[if IE 8]>
    <style type="text/css">
        #touch_right .tel{ background:#434343;height:50px;line-height:50px;padding:0 8px;}
        #touch_right .er{ background:#656565;height:50px;line-height:50px;padding:0 8px;}
        #touch_right .top {height:50px;line-height:50px;padding:0 8px;}
        #touch_right .hotline{ line-height:170%;}
        #touch_right .floating_ewm{height:190px; top:-142px;line-height:150%;}
        .top{height:50px;line-height:50px;}
    </style>
    <![endif]-->
    <script type="text/javascript" src="/home/views/static/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/home/views/static/zcq/js/min.js"></script>
    <script type="text/javascript" src="/home//views/static/js/layer/layer.js"></script>
    <style>
        .clear{clear: both;}
        #page_string{float: left;font-size: 14px;}
        #page_string .pagination li{float: left;}
        .btn{width: 60px;font-size: 16px;border-radius: 5px;-webkit-border-radius: 5px;
    -moz-border-radius: 5px;line-height: 25px;text-align: center;margin-left: 5px;
    border: 1px solid #d5d5d5;color:#000;background: #fff;cursor: pointer;}
    	.btn:hover{color:#2f80d5;}
    </style>
</head>
<body>

<?php $this->load->view(__TEMPLET_FOLDER__ . '/header.php'); ?>

<div class="pc_list">
    <div>
        <?php $this->load->view(__TEMPLET_FOLDER__ . '/admin/menu.php'); ?>

        <!--news list-->
        <div id="" class="pc_member_background_list">
            <span class="pc_list_present">

                当前位置：<a href="<?php echo site_url("admin/index")?>">会员后台首页</a>-><a href="<?php echo site_url("adminx/zcq_huodong/index").$sec_curset=='mimemime_hd'?'?userid='.$sess['userid']:'';?>"><?php if($sec_curset=='hd_list') echo '列表';else echo '我的活动';?></a></span>
            <h3 class="pc_list_h3"><?php if($sec_curset=='hd_list') echo '列表';else echo '我的活动';?></h3>
            <div class="search" >
				<form method="get" >
				关键字：
				    <input type="text"  name="search_title" id="search_title"
				    class="abc input-default" 
				    placeholder="主题" 
				    value="<?php echo $search_val['title'];?>"
				    style="width:300px;height:28px;"
				    />
				状态：
					<select name="status" id="status">
						<option value="">状态</option>
						<option value="1" <?php if(isset($search_val['status'])&&$search_val['status']==1)echo 'selected';?>>未开始</option>
						<option value="2" <?php if(isset($search_val['status'])&&$search_val['status']==2)echo 'selected';?>>报名中</option>
                        <option value="5" <?php if(isset($search_val['status'])&&$search_val['status']==5)echo 'selected';?>>报名结束</option>
						<option value="3" <?php if(isset($search_val['status'])&&$search_val['status']==3)echo 'selected';?>>活动中</option>
						<option value="4" <?php if(isset($search_val['status'])&&$search_val['status']==4)echo 'selected';?>>活动结束</option>
					</select>
					<input type="hidden" name="userid" value="<?php if(isset($search_val['userid']))echo $search_val['userid']?>">
				    <button type="submit" class="btn">查询</button>
				</form>
			</div>
			<?php if (is_array($list)&&count($list)>0): ?>
            <table cellspacing="0" cellpadding="0" style="width:100%;" class="mytable">
                <tr>
                    <th style="min-width: 50px;">编号</th>
                    <th>主题</th>
                    <th>状态</th>
                    <th>是否报名</th>
                    <th>报名/总人数</th>
                    <th>报名时段</th>
                    <th>活动时段</th>
                    <th class="membl_manipulate">操作</th>
                </tr>
                <tbody id="result_">
                     <?php
			            $nowTime = time();//当前时间
			            foreach($list as $v){
			            	
			            	echo "<tr onclick='seltr($(this))'>";
			            	echo "<td style='text-align:center '>".$v["id"]."</td>";
			            	echo "<td>".$v["title"]."</td>";
			            	$status_txt = '未开始';
			            	if ($v['baoming_start']>$nowTime) {
			            		$status_txt = '<span style="color:#ccc;">未开始</span>';
			            	} else if ($v['baoming_start']<$nowTime&&$nowTime<$v['baoming_end']) {
			            		$status_txt = '<span style="color:#da4f49;">报名中</span>';
			            	} else if ($v['starttime']>$nowTime&&$nowTime>$v['baoming_end']) {
                                $status_txt = '<span style="color:#51a351;">报名结束</span>';
                            } else if ($v['starttime']<$nowTime&&$nowTime<$v['endtime']) {
			            		$status_txt = '<span style="color:#51a351;">活动中</span>';
			            	} else if ($v['endtime']<$nowTime) {
			            		$status_txt = '<span style="color:#da4f49;">活动结束</span>';
			            	}
			            	echo "<td>$status_txt</td>";
			            	echo "<td style='text-align:center;'>".$v["isbm"]."</td>";
							echo "<td style='text-align:center;'>".$v['bmnum'].'/'.$v['pnum']."</td>";
							echo "<td>".date("Y-m-d",$v["baoming_start"]).'&nbsp;至&nbsp;'.date("Y-m-d",$v["baoming_end"])."</td>";
							echo "<td>".date("Y-m-d",$v["starttime"]).'&nbsp;至&nbsp;'.date("Y-m-d",$v["endtime"])."</td>";      				
			            	echo "<td style='text-align:center;'>";
							echo "<a href='".site_url("adminx/zcq_huodong/look").'?backurl='.(urlencode(get_url())).'&hdid='.$v['id']."'>查看</a>&nbsp;";
							echo "</td>";                          	            	             	
			            	echo "</tr>";
			            	echo "\n";
			            }
			          ?>  
                </tbody>
            </table>
        	<?php else:?>
        	<p style="margin-top:10px;color:#ccc;">暂无活动</p>
            <?php endif ?>
            <div class="membl_bot">
                <!-- <ul>
                    <li>
                        <input type="hidden" name="selid" id="selid" readonly value=""/> 
                        <a href="javascript:void(0);" onclick="selall()" class="membl_check_all ">全选</a>
                        <a href="javascript:void(0);" onclick="selall2()" class="membl_reverse_selection ">反选</a>
                    </li>
                    <li>
                        <a href="" class="membl_add">增加</a>
                        <a href="javascript:void(0);" onclick="godel()"  class="membl_del">删除</a>
                    </li>
                </ul> -->
                <input type="hidden" name="selid" id="selid" readonly value=""/> 
                <div id="page_string" style="float:left ; text-align:left ; margin:-4px">
                <?php echo $pager;?>  
                </div>
            </div>
        </div>
    </div>
</div>

<div id="touch_right">
    <ul>
        <li class="tel"><i class="iconfont">&#xe60c;</i>
            <div class="floating_left hotline">咨询热线：<br> (86-760)89892378</div>
        </li>
        <li class="er"><i class="iconfont">&#xe60d;</i>
            <div class="floating_left floating_ewm">
                <img src="/home/views/static/zcq/images/two-dimension-code.jpg">扫一扫二维码<br />关注官方微信
            </div>
        </li>
        <li onClick="gotoTop();return false;" class="top"><i class="iconfont">&#xe622;</i>
            <div class="floating_left">返回頁首</div>
        </li>
    </ul>
</div>
<?php $this->load->view(__TEMPLET_FOLDER__ . '/footer.php'); ?>

</body>
</html>

<script src="/home/views/static/zcq/js/selall.js"></script>

<script>
$(function () {
    
});


function godel(){
    var ids = $("#selid").val();
    /*if(!chkdel()){
        return false;
    }*/ 
}
          
</script>