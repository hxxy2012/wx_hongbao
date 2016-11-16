<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>系统用户添加</title>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>

    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
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


        .bui-tree-item-checked{
            font-style: normal;
        }

    </style>
</head>
<body>
<div class="form-inline definewidth m20" >
   <a  class="btn btn-primary" id="addnew" href="<?php echo site_url("sys_admin/index");?>">系统用户列表</a>
</div>
<form action="<?php echo site_url("sys_admin/add");?>" method="post" class="definewidth m20" id="myform">
<input type="hidden" value="doadd" name="action">
<table width="100%"><tr>
<td width="70%" valign="top">
        <table style="padding-top: 0px; margin-top: 0px;" class="table table-bordered table-hover definewidth m10">
            <tr>
                <td width="30%" class="tableleft">登录名</td>
                <td><input type="text" name="username" placeholder="User name" required/></td>
            </tr>
            <tr>
                <td class="tableleft">密码</td>
                <td><input type="text" name="password" placeholder="password " required/></td>
            </tr>
            <tr>
                <td class="tableleft">状态</td>
                <td>
                    <input type="radio" name="status" value="1" checked/> 启用
                    <input type="radio" name="status" value="0"/> 禁用
                </td>
            </tr>
            <tr>
                <td class="tableleft">是否是超级管理员</td>
                <td>
                    <input type="radio" name="super_admin" value="1" onclick="super_admin_(1);"/> 是
                    <input type="radio" name="super_admin" value="0" onclick="super_admin_(0);" checked/> 不是

                </td>
            </tr>

            <tr>
                <td class="tableleft">邮箱</td>
                <td><input value="" type="text" name="email" placeholder="" required/></td>
            </tr>

            <tr>
                <td class="tableleft">是否接收邮件</td>
                <td>
                    <input type="radio" name="is_receive" value="1" checked  /> 是
                    <input type="radio" name="is_receive" value="0" /> 不是
                </td>
            </tr>

            <tr>
                <td class="tableleft">手机号：</td>
                <td>
                    <input type="text" name="tel" value="" valType="mobile" maxlength="11" />
                </td>
            </tr>

            <tr id="group_">
                <td class="tableleft">角色组</td>
                <td>
                    <select name="gid" >
                        <?php
                        if(isset($list) && $list){
                            foreach($list as $k_=>$v_){

                                ?>
                                <option value="<?php echo $v_['id'];?>"><?php echo $v_['rolename'];?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td class="tableleft"></td>
                <td><input type="hidden" name="website_category" id="website_category" value=""/>
                    <button type="submit" class="btn btn-primary" type="button" id="btnSave">保存</button> &nbsp;&nbsp;
                </td>
            </tr>
        </table>
</td>
<td valign="top">
    添加管理栏目(不选则开放所有栏目)
    <div id="t1">

    </div>
    <script type="text/javascript">
        BUI.use(['bui/tree','bui/data'],function (Tree,Data) {


//返回数据后创建tree
            $.getJSON('treelist.shtml?json=1&rnd='+Math.round(),function(data){

                var store = new Data.TreeStore({
                    pidField : 'pid', //设置pid的字段名称
                    root : {
                        id : '0',
                        text : '所有栏目',
                        expanded : true,
                        listpage:''
                    },
                    data : data
                });

                var tree = new Tree.TreeList({
                    render : '#t1',
                    showLine : true,
                    showRoot : true,
                    checkType: 'all', //checkType:勾选模式，提供了4中，all,onlyLeaf,none,custom
                    cascadeCheckd : false, //不级联勾选
                    store : store
                });
                tree.render();


                tree.on('checkedchange',function(ev){
                    var checkedNodes = tree.getCheckedNodes();
                    var str = '';
                    BUI.each(checkedNodes,function(node){

                        if(str==""){
                            str = node.id;
                        }
                        else {
                            str += ','+node.id;
                        }
                    });
                    $('#website_category').val(str);
                });

            });

        });
    </script>
</td>
</tr></table>

</form>
</body>
</html>
<script>
$(function () {       
		$("#btnSave").click(function(){
				if($("#myform").Valid() == false || !$("#myform").Valid()) {
					return false ;
				}
		});
});
function super_admin_(type){
	if(type == 1 ){
		$("#group_").hide();
	}else{
		$("#group_").show();
	}
}
</script>