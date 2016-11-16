<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>查看审核</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
	
    <style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        .img_ul{padding: 0;border: 0;margin: 0;}
        .img_ul li{float: left;list-style: none;}
        .clear{clear: both;}
    </style>
    <link rel="stylesheet"  href="/home/views/static/js/zoom/zoom.css" media="all" />   
</head>
<body>

<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("swj_xxbasp/index?caozuo=".$caozuo);?>">审核列表</a>
</div>
<form action="<?php echo site_url("swj_xxbasp/look?caozuo=".$caozuo);?>" method="post" class="definewidth m2"  name="myform" id="myform">
<input type="hidden" name="action" value="dolook">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="passornot" id="passornot" value="">
<input type="hidden" name="audit" value="<?php echo $audit_status;?>">
<?php if ($audit_status <= 30){?>
<table class="table table-bordered table-hover m10">
    <caption>活动计划</caption>
    <tr>
        <td class="tableleft">备案类目</td>
        <td>
            <?php echo $name;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审核状态：<?php echo $audit;?>&nbsp;&nbsp;&nbsp;&nbsp;
            审核时间：<?php if($audit_time) echo $audit_time;else echo '无';?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动名称</td>
        <td>
            <?php echo $actname?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动类型</td>
        <td>
             <?php foreach ($all_hdtype as $key => $value): ?>
                <input type="checkbox" readonly disabled="true" name="type[]" value="<?php echo $value['id']?>" tname="<?php echo $value['name']?>"  <?php if(in_array($value['id'], $type_arr)) echo "checked";?>><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <input type="text" readonly disabled style="display:none;margin:0;" name="type_other" placeholder="其他类型" id="type_other" value="<?php echo $type_other?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动性质</td>
        <td>
            <input type="radio" readonly disabled="true" name="nature" value="1" <?php if($nature==1) echo "checked";?>>对外公开&nbsp;&nbsp;
            <input type="radio" readonly disabled="true" name="nature" value="2" <?php if($nature==2) echo "checked";?>>
            只限内部&nbsp;&nbsp;
        (对外公开：在登录页“协会活动”位置显示)</td>
    </tr>
    <tr>
        <td class="tableleft">活动是否收费</td>
        <td>
            <input type="radio" readonly disabled="true" name="isfree" value="1" <?php if($isfree==1) echo "checked";?>>公益免费&nbsp;&nbsp;
            <input type="radio" readonly disabled="true" name="isfree" value="0" <?php if($isfree==0) echo "checked";?>>盈利收费&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动时间</td>
        <td>
            <?php echo $plan_stime."——".$plan_etime;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动场地</td>
        <td>
            <?php echo $plan_place;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动简介</td>
        <td>
            <textarea style="width:100%; height:150px" id="description" name="description" placeholder="描述"><?php echo htmlspecialchars($description)?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft">预期参加人数</td>
        <td>
            <?php echo $plan_join_num;?>&nbsp;&nbsp;人
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动宣传海报、文件</td>
        <td>
            <div class="zliao_img_box">
                <ul class="img_ul">
                    <?php foreach ($file as $key => $value): ?>
                        <li><a href='/<?php echo $value["filesrc"]?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;</li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
            <?php 
            /*$pic_arr = array('jpg','jpeg','gif','png');
            foreach ($file as $key => $value) {
                $filesrc = base_url().$value['filesrc'];//文件路径
                $title = $value['title'];//标题
                $lastname = substr(strrchr($filesrc, '.'), 1);//后缀名
                $lastname = strtolower($lastname);//转换为小写
                if (in_array($lastname, $pic_arr)) {//为图片类型，输出图片
                    echo "<a href='$filesrc' target='_blank'><img src='$filesrc'  style='width:80px;height:50px;'/></a>&nbsp;&nbsp;";
                } else {
                    echo "<a href='$filesrc' target='_blank'>$title</a>&nbsp;&nbsp;";
                }
            }*/?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动宣传网址</td>
        <td>
            <?php echo $xcwz;?>
        </td>
    </tr>
     <tr>
        <td class="tableleft">活动总预算</td>
        <td>
            <?php echo $plan_price;?>&nbsp;&nbsp;万元
        </td>
    </tr>
     <tr>
        <td class="tableleft">活动举办单位</td>
        <td>
            <?php echo $company;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">预期备注</td>
        <td>
            <?php echo htmlspecialchars($plan_beizhu)?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">初审意见</td>
        <td>
            <textarea style="width:100%; height:150px" id="plan_audit" name="plan_audit" placeholder="描述"><?php echo htmlspecialchars($plan_audit)?></textarea>
        </td>
    </tr>
<!--     <tr>
    <td class="tableleft"></td>
    <td>
        <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
        <input type="button" class="btn btn-primary" value="返回" id="back" onclick="javascript:history.go(-1);">&nbsp;&nbsp;
    </td>
</tr> -->
</table>
<?php } else {?>
<table class="table table-bordered table-hover m10">
    <caption>活动计划</caption>
    <tr>
        <td class="tableleft">备案类目</td>
        <td>
            <?php echo $name;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审核状态：<?php echo $audit;?>&nbsp;&nbsp;&nbsp;&nbsp;
            审核时间：<?php if($audit_time) echo $audit_time;else echo '无';?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动名称</td>
        <td>
            <?php echo $actname?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动类型</td>
        <td>
              <?php foreach ($all_hdtype as $key => $value): ?>
                <input type="checkbox" readonly disabled="true" name="type[]" value="<?php echo $value['id']?>" tname="<?php echo $value['name']?>"  <?php if(in_array($value['id'], $type_arr)) echo "checked";?>><?php echo $value['name']?>&nbsp;&nbsp;
            <?php endforeach ?>
            <input type="text" readonly disabled style="display:none;margin:0;" name="type_other" placeholder="其他类型" id="type_other" value="<?php echo $type_other?>">
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动性质</td>
        <td>
            <input type="radio" readonly disabled="true" name="nature" value="1" <?php if($nature==1) echo "checked";?>>对外公开&nbsp;&nbsp;
            <input type="radio" readonly disabled="true" name="nature" value="2" <?php if($nature==2) echo "checked";?>>只限内部&nbsp;&nbsp;
        (对外公开：在登录页“协会活动”位置显示)</td>
    </tr>
    <tr>
        <td class="tableleft">活动是否收费</td>
        <td>
            <input type="radio" readonly disabled="true" name="isfree" value="1" <?php if($isfree==1) echo "checked";?>>公益免费&nbsp;&nbsp;
            <input type="radio" readonly disabled="true" name="isfree" value="0" <?php if($isfree==0) echo "checked";?>>盈利收费&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动时间</td>
        <td>
            <?php echo $plan_stime."——".$plan_etime;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动场地</td>
        <td>
            <?php echo $plan_place;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动简介</td>
        <td>
            <textarea style="width:100%; height:150px" id="description" name="description" placeholder="描述"><?php echo $description?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft">预期参加人数</td>
        <td>
            <?php echo $plan_join_num;?>&nbsp;&nbsp;人
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动宣传海报、文件</td>
        <td>
            <div class="zliao_img_box">
                <ul class="img_ul">
                    <?php foreach ($file as $key => $value): ?>
                        <li><a href='/<?php echo $value["filesrc"]?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;</li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
            <?php 
            /*$pic_arr = array('jpg','jpeg','gif','png');
            foreach ($file as $key => $value) {
                $filesrc = base_url().$value['filesrc'];//文件路径
                $title = $value['title'];//标题
                $lastname = substr(strrchr($filesrc, '.'), 1);//后缀名
                $lastname = strtolower($lastname);//转换为小写
                if (in_array($lastname, $pic_arr)) {//为图片类型，输出图片
                    echo "<a href='$filesrc' target='_blank'><img src='$filesrc'  style='width:80px;height:50px;'/></a>&nbsp;&nbsp;";
                } else {
                    echo "<a href='$filesrc' target='_blank'>$title</a>&nbsp;&nbsp;";
                }
            }*/?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动宣传网址</td>
        <td>
            <?php echo $xcwz;?>
        </td>
    </tr>
     <tr>
        <td class="tableleft">活动总预算</td>
        <td>
            <?php echo $plan_price;?>&nbsp;&nbsp;万元
        </td>
    </tr>
     <tr>
        <td class="tableleft">活动举办单位</td>
        <td>
            <?php echo $company;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">预期备注</td>
        <td>
            <?php echo htmlspecialchars($plan_beizhu)?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">初审意见</td>
        <td>
            <textarea style="width:100%; height:150px" id="plan_audit" name="plan_audit" placeholder="描述"><?php echo htmlspecialchars($plan_audit)?></textarea>
        </td>
    </tr>
<!--     <tr>
    <td class="tableleft"></td>
    <td>
        <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
        <input type="button" class="btn btn-primary" value="返回" id="back" onclick="javascript:history.go(-1);">&nbsp;&nbsp;
    </td>
</tr> -->
</table>
    <table class="table table-bordered table-hover m10">
    <caption>活动完成</caption>
    <tr>
        <td class="tableleft">活动名称</td>
        <td>
            <?php echo $actname?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动实际地点</td>
        <td>
            <?php echo $second_place?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动实际开始日期</td>
        <td>
            <?php echo $second_stime;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动实际完成时间</td>
        <td>
            <?php echo $second_etime?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">实际参加人数</td>
        <td>
            <?php echo $second_join_num?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动签到表</td>
        <td>
            <div class="qdb_box">
                <ul class="img_ul">
                     <?php foreach ($file_second_qdb as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
           <?php 
            /*$pic_arr = array('jpg','jpeg','gif','png');
            foreach ($file_second_qdb as $key => $value) {
                $filesrc = base_url().$value['filesrc'];//文件路径
                $title = $value['title'];//标题
                $fjid = $value['id'];
                $lastname = substr(strrchr($filesrc, '.'), 1);//后缀名
                $lastname = strtolower($lastname);//转换为小写
                $field = 'second_actqd';//要操作的字段
                if (in_array($lastname, $pic_arr)) {//为图片类型，输出图片
                    echo "<a href='$filesrc' target='_blank'><img src='$filesrc' style='width:80px;height:50px;'/></a>&nbsp;&nbsp;";
                } else {
                    echo "<a href='$filesrc' target='_blank'>$title</a>&nbsp;&nbsp;";
                }
            }*/?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动剪影</td>
        <td>
            <div class="jy_box container">
               <ul class="gallery1 img_ul">
                  <?php foreach ($file_second_jy as $key => $value): ?>
                        <li>
                            <a href='/<?php echo $value["filesrc"];?>' target='_blank'><img src="/<?php echo $value["filesrc"];?>" style="width:80px;height:60px;"></a>&nbsp;&nbsp;
                            <br>
                            <input type='radio' readonly diaabled name='themejyid' <?php if($value["id"]==$themejyid) echo 'checked';?> value='<?php echo $value["id"]?>'>封面图&nbsp;&nbsp;
                            <span style='color:#fff;' tp='<?php echo $value["id"]?>'>占位</span>
                        </li>
                    <?php endforeach ?>
               </ul>
               <div class="clear"></div>
              </div>
            <?php 
            /*$pic_arr = array('jpg','jpeg','gif','png');
            foreach ($file_second_jy as $key => $value) {
                $filesrc = base_url().$value['filesrc'];//文件路径
                $title = $value['title'];//标题
                $fjid = $value['id'];
                $lastname = substr(strrchr($filesrc, '.'), 1);//后缀名
                $lastname = strtolower($lastname);//转换为小写
                $field = 'second_jy';//要操作的字段
                if (in_array($lastname, $pic_arr)) {//为图片类型，输出图片
                    echo "<a href='$filesrc' target='_blank'><img src='$filesrc' style='width:80px;height:50px;'/></a>&nbsp;&nbsp;";
                } else {
                    echo "<a href='$filesrc' target='_blank'>$title</a>&nbsp;&nbsp;";
                }
            }*/?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动实际开支</td>
        <td>
            <?php echo $second_pricekz?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动教师开支</td>
        <td>
            <?php echo $second_teacherkz?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动文印开支</td>
        <td>
            <?php echo $second_wykz;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动宣传开支</td>
        <td>
            <?php echo $second_xckz;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动其他杂项开支</td>
        <td>
            <?php echo $second_otherkz;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">其他开支备注</td>
        <td>
            <?php echo $second_kzbeizhu;?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">活动开支的合同或发票</td>
        <td>
             <div class="ht_box">
              <ul class="img_ul">
                   <?php foreach ($file_second_kz_hdfp as $key => $value): ?>
                      <li>
                          <a href='/<?php echo $value["filesrc"];?>' target='_blank'><?php echo substr($value["filesrc"], strripos($value["filesrc"], '/') + 1)?></a>&nbsp;&nbsp;
                      </li>
                  <?php endforeach ?>
              </ul>
              <div class="clear"></div>
          </div>
            <?php 
            /*$pic_arr = array('jpg','jpeg','gif','png');
            foreach ($file_second_kz_hdfp as $key => $value) {
                $filesrc = base_url().$value['filesrc'];//文件路径
                $title = $value['title'];//标题
                $fjid = $value['id'];
                $lastname = substr(strrchr($filesrc, '.'), 1);//后缀名
                $lastname = strtolower($lastname);//转换为小写
                $field = 'second_kz_hdfp';//要操作的字段
                if (in_array($lastname, $pic_arr)) {//为图片类型，输出图片
                    echo "<a href='$filesrc' target='_blank'><img src='$filesrc' style='width:80px;height:50px;'/></a>&nbsp;&nbsp;";
                } else {
                    echo "<a href='$filesrc' target='_blank'>$title</a>&nbsp;&nbsp;";
                }
            }*/?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">补充备注</td>
        <td>
            <?php echo htmlspecialchars($second_beizhu)?>
        </td>
    </tr>
    <tr>
        <td class="tableleft">终审意见</td>
        <td>
            <textarea style="width:100%; height:150px" id="second_audit" name="second_audit" placeholder="审核意见"><?php echo htmlspecialchars($second_audit)?></textarea>
        </td>
    </tr>
     <tr>
        <td class="tableleft">报销金额</td>
        <td>
            <?php echo $baoxiao_price;?>
        </td>
    </tr>
<!--     <tr>
    <td class="tableleft"></td>
    <td>
        <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
        <input type="button" class="btn btn-primary" value="返回" id="back" onclick="javascript:history.go(-1);">&nbsp;&nbsp;
    </td>
</tr> -->
</table>
<?php }?>
<div class="center" style="margin:0 auto;text-align:center;">
    <!-- <input type="button" class="btn btn-primary" value="审核通过" id="pass">&nbsp;&nbsp;
    <input type="button" class="btn btn-primary" value="审核不通过" id="nopass">&nbsp;&nbsp; -->
    <input type="button" class="btn btn-primary" value="返回" onclick="javascript:window.location.href='<?php echo site_url("swj_xxbasp/index?caozuo=".$caozuo);?>';">&nbsp;&nbsp;
</div>
</form>
</body>
</html>
<script src="/home/views/static/js/zoom/zoom.min.js"></script>
<script type="text/javascript" src="<?php echo  base_url();?>/<?php echo APPPATH?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
<script type="text/javascript" src="<?php echo  base_url();?>/<?php echo APPPATH?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>
<script>
var plan_beizhu;//第一次备注
var description;//活动简介
var second_beizhu;//第二次备注
var plan_audit;//第一次审核意见
var second_audit;//第二次审核意见
$(function () {
        var obj_type=document.getElementsByName('type[]'); //选择所有name="'type[]'"的对象，返回数组 
        //循环检测活动其他类型是否选中，如果选中则显示text 
        for(var i=0; i<obj_type.length; i++){ 
            // alert(obj_type[i].attributes['tname'].nodeValue);
            if(obj_type[i].attributes['tname'].nodeValue=='其他'&&obj_type[i].checked) {
                $('#type_other').show();
            }
        } 
        //通过审核,将值设为1，提交表单   
		$('#pass').click(function(){
			 $("#passornot").val(1);
             myform.submit();
		});
        //不通过审核,将值设为0，提交表单
		$("#nopass").click(function(){
            if (plan_beizhu.isEmpty()) {
                alert("审核意见不能为空！！");
                return false;
            }
			$("#passornot").val(0);
            myform.submit();
		});

});
KindEditor.ready(function(K) {
       // plan_beizhu = K.create('#plan_beizhu',{
       //          width:'100%',
       //          height:'400px',
       //          allowFileManager:false ,
       //          allowUpload:false,
       //          afterCreate : function() {
       //              this.sync();
       //          },
       //          afterBlur:function(){
       //                this.sync();
       //          },
       //          extraFileUploadParams:{
       //              'cookie':''
       //          },
       //          uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
       // });
       // second_beizhu = K.create('#second_beizhu',{
       //          width:'100%',
       //          height:'400px',
       //          allowFileManager:false ,
       //          allowUpload:false,
       //          afterCreate : function() {
       //              this.sync();
       //          },
       //          afterBlur:function(){
       //                this.sync();
       //          },
       //          extraFileUploadParams:{
       //              'cookie':''
       //          },
       //          uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
       // });
       plan_audit = K.create('#plan_audit',{
                width:'100%',
                height:'400px',
                allowFileManager:false ,
                allowUpload:false,
                afterCreate : function() {
                    this.sync();
                },
                afterBlur:function(){
                      this.sync();
                },
                extraFileUploadParams:{
                    'cookie':''
                },
                uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
       });
       second_audit = K.create('#second_audit',{
                width:'100%',
                height:'400px',
                allowFileManager:false ,
                allowUpload:false,
                afterCreate : function() {
                    this.sync();
                },
                afterBlur:function(){
                      this.sync();
                },
                extraFileUploadParams:{
                    'cookie':''
                },
                uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
       });
       description = K.create('#description',{
                width:'100%',
                height:'400px',
                allowFileManager:false ,
                allowUpload:false,
                afterCreate : function() {
                    this.sync();
                },
                afterBlur:function(){
                      this.sync();
                },
                extraFileUploadParams:{
                    'cookie':''
                },
                uploadJson:"<?php echo site_url("Swj_xxbasp/upload");?>?session_id=<?php echo $sess["session_id"];?>"
                        
       });
       description.readonly(true);
       // plan_beizhu.readonly(true);
       // second_beizhu.readonly(true);
       plan_audit.readonly(true);
       second_audit.readonly(true);
});

 
</script>
