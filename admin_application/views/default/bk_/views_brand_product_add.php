<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css" />   
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script> 	
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/validate/validator.js"></script>
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />


        <!--在线编辑器-->
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/kindeditor-all-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/kindeditor/lang/zh_CN.js"></script>

        <!-- 时间控件 -->
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/DatePicker/WdatePicker.js"></script>
     
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
        </style>
    </head>
    <body class="definewidth">

        <form action="<?php echo site_url("brand_product/add")."?brand_id={$brand_id}"; ?>" enctype='multipart/form-data' onsubmit="return postform()" method="post" name="myform" id="myform">
            <input type="hidden" name="action" value="doadd">
            <table class="table table-bordered table-hover m10">
                <tr>
                    <td style="width:100px;" class="tableleft">产品名称：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_pro_name"  value="<?php echo!empty($model['pro_name']) ? $model['pro_name'] : '' ?>" id="pro_name" required="true"   valType="check" />
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">全称：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_fullname"  value="<?php echo!empty($model['fullname']) ? $model['fullname'] : '' ?>" id="fullname" required="true"   valType="check" />
                    </td>
                </tr>

                <tr>
                    <td class="tableleft">所在大类：</td>
                    <td>
                        <select name="mysql_proclass" id="proclass">
                            <?php
                            if(!empty($cat1)):?>
                                <?php  foreach($cat1 as $k=>$v):?>
                            <option <?php if (!empty($model['proclass'])):?><?php if($v['id']==$model['proclass']):?>selected<?php endif;?><?php endif;?> value="<?php echo  $v['id']?>"><?php echo $v['title']?></option>
                                <?php  endforeach?>
                            <?php endif?>
                        </select>
                    </td>
                    <script>
                        $('#proclass').change(function(){
                            $('#proclass2').html('');
                            var str='';
                            id=this.value;
                            $.ajax({
                                type: "POST",
                                url: "<?php echo site_url("brand_product/getsecondCategory"); ?>",
                                data: {'id':id},
                                dataType:'json',
                                success: function(data){
                                  $.each(data,function(k,v){
                                      str+="<option  value='"+v.id+"'>"+v.title+"</option>";
                                      $('#proclass2').html(str);
                                  });
                                }
                             });
                        });
                    </script>
                </tr>
                <tr>
                    <td class="tableleft">所在小类：</td>
                    <td>
                        <select name="mysql_proclass2" id="proclass2">
                             <?php
                            if(!empty($cat2)):?>
                                <?php  foreach($cat2 as $k=>$v):?>
                            <option <?php if (!empty($model['proclass2'])):?><?php if($v['id']==$model['proclass2']):?>selected<?php endif;?><?php endif;?> value="<?php echo  $v['id']?>"><?php echo $v['title']?></option>
                                <?php  endforeach?>
                            <?php endif?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">编号：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_bianhao"  value="<?php echo!empty($model['bianhao']) ? $model['bianhao'] : '' ?>" id="bianhao" required="true"  valType="check" />
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">规格：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_guige"  value="<?php echo!empty($model['guige']) ? $model['guige'] : '' ?>" id="guige" required="true"     valType="check" />
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">市场价：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_price"  value="<?php echo!empty($model['price']) ? $model['price'] : '' ?>" id="price" required="true"  valType="check" />
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">点击数：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_hits"  value="<?php echo!empty($model['hits']) ? $model['hits'] : '' ?>" id="hits" required="true"  valType="check" />
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">商品积分：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_pv"  value="<?php echo!empty($model['pv']) ? $model['pv'] : '' ?>" id="pv" required="true"  valType="check" />
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">产品简介：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_jianjie"  value="<?php echo!empty($model['jianjie']) ? $model['jianjie'] : '' ?>" id="jianjie" required="true"   valType="check" />
                    </td>
                </tr>
                <tr>
                    <td class="tableleft">排序：</td>
                    <td>
                        <input type="text" style="width:200px;" name="mysql_orderby"  value="<?php echo!empty($model['orderby']) ? $model['orderby'] : '' ?>" id="orderby" required="true"   valType="check" />
                    </td>
                </tr>
                
                
                
                
                <tr>
                    <td class="tableleft">产品详细介绍：</td>
                    <td> <textarea style="width:100%;height:150px;" id="mysql_content" name="mysql_content"  ><?php echo!empty($model['content']) ? $model['content'] : '' ?></textarea>
                        <script>
                            KindEditor.ready(function(K) {
                                window.editor = K.create('#mysql_content', {
                                    width: '100%',
                                    height: '200px',
                                    allowFileManager: false,
                                    allowUpload: false,
                                    afterCreate: function() {
                                        this.sync();
                                    },
                                    afterBlur: function() {
                                        this.sync();
                                    },
                                    extraFileUploadParams: {
                                        'cookie': "<?php echo $admin_auth_cookie; ?>"
                                    },
                                    uploadJson: "<?php echo site_url("website_category/upload") . '?action=upload&session=' . $usersession; ?>"

                                });
                            });
                        </script>



                    </td>
                </tr> 
                <tr>
                    <td class="tableleft">封面图：</td>
                    <td> 
                        <input type="file" name="mysql_thumb" id=""  />

                        <span id="show_user_pic"><?php echo!empty($model['thumb']) ? "<img width=100 src='../../{$model['thumb']}' />" : ''; ?></span>
                        <?php if (!empty($model['thumb'])): ?>  
                         <a id="del_link_user_pic" href="javascript:;" onclick="delpic('show_user_pic', 'user_pic', '<?php echo $model['thumb']; ?>')">删除图片</a>
                        <?php endif; ?>  


                    </td>
                <script>
                    function delpic(obj, field, brandguid) {
                        var update_field = field;
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url("brand/ajax_delpic") ?>",
                            data: {'field': update_field, 'brandguid': brandguid},
                            success: function(msg) {
                                if (msg == 1) {
                                    $('#' + obj).hide();
                                    $('#del_link_' + field).hide();
                                }
                            }
                        });
                    }
                </script>
                </tr>  
                <tr>
                    <td class="tableleft">置顶时间：</td>
                    <td> 
                        <?php $date = date("Y-m-d H:i:s", time()); ?>
                        <input type="text" readonly="" name="mysql_istop" value="<?php echo!empty($model['istop']) ? $model['istop'] : $date ?>   "
                               onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', isShowClear: true, readOnly: false})"
                               required="true"
                               valType="date"
                               />  
                    </td>
                </tr>  
                <tr>
                    <td class="tableleft">置顶描述：</td>
                    <td> 
                <input type="text" name="mysql_istop_str" value="<?php echo!empty($model['istop_str']) ? $model['istop_str'] : '' ?>" style="width:500px" id=""  />
                    </td>
                </tr>  

                <tr>
                    <td class="tableleft"></td>
                    <td>
                        <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
                        <a  class="btn btn-primary" id="addnew" onclick="golist()" href="javascript:;">返回列表</a>       
                        <input type="hidden" value="<?php echo isset($model['guid']) ? $model['guid'] : ''; ?>" name="guid" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>


<script>
    function golist() {
        top.topManager.closePage();
        window.location.href = "<?php echo site_url("brand_product/index"); ?>";
    }

    function postform() {

        return true;
    }
function postform(){

if(!$("#myform").Valid()){
	return false;
}
if($('#mysql_content').val()==''){
   parent.tip_show('产品详细介绍不能为空',1,1000);
    return false;
}
return true;
}
</script>
