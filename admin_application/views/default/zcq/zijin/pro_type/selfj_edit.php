<?php
/*
* 编辑页面：项目类型 时 使用
*/
if (! defined('BASEPATH')) {

    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>

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

<div class="form-inline definewidth m20" >
    <form method="get" >
        待选附件名称关键字：
        <input type="text"  name="sel_title" id="search_title"
               class="abc input-default"
               placeholder="附件标题"
               value="<?php echo $search_val['title'];?>"
               style="width:200px;"
        />
        <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;
<span style="color:red;">
    *建议在<?php echo date("Y-m-d H:i",$typemodel["starttime"]);?> 至 <?php echo date("Y-m-d H:i",$typemodel["endtime"]);?>期间不要做已选附件的删除操作
</span>



        <input type="hidden" name="idlist" id="idlist" value="<?php echo $idlist;?>" />
        <input type="hidden" name="typeid" value="<?php echo $typeid;?>" />
    </form>
</div>

<?php
if(count($fjlist)>0){
?>
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
        <th width='50'>附件编号</th>
        <th><span style="color:#cd0a0a">已选附件名称</span></th>
        <th width='60' align="center">附件</th>
        <th width='60' align="center">排序/删</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($fjlist as $k=>$v){  ?>

    <tr>
        <td  align="center">
            <?php echo $v["id"];?>
        </td>
        <td>
            <?php echo $v["title"];?>
        </td>
        <td  align="center">
            <a href="/<?php echo $v["filepath"];?>" target="_blank">查看</a>
        </td>
        <td  align="center" title="<?php echo $v["orderby"];?>">
            <?php if($k>0){ ?>
            <a onclick="orderby('up','<?php echo $v["profj_id"];?>')" href="javascript:void(0);" class="icon-circle-arrow-up"></a>
            <?php } ?>

            <?php if($k<(count($fjlist)-1)){ ?>
            <a onclick="orderby('down','<?php echo $v["profj_id"];?>')" href="javascript:void(0);" class="icon-circle-arrow-down"></a>            <?php } ?>

            <?php
            echo "<a class=' icon-minus ' href=\"javascript:void(0);\" onclick='minussel(\"".$v["id"]."\",\"".$v["profj_id"]."\")' ></a>";
            ?>
        </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
<?php
}
?>

<?php
if(count($list)>0){
    ?>
    <table class="table table-bordered table-hover  m10">
        <thead>
        <tr>
            <th width='50'  align="center">编号</th>
            <th><span style="color:#00CC00">待选附件名称</span></th>
            <th width='60'>操作</th>
        </tr>
        </thead>
        <tbody id="result_">


        <?php

        foreach($list as $v){

            echo "<tr >";
            echo "<td  align=\"center\">".$v["id"]."</td>";
            echo "<td>".$v["title"]."</td>";
            echo "<td>";
            echo "<a class=' icon-plus ' href=\"javascript:void(0);\" onclick='addsel(\"".$v["id"]."\")' ></a>";
            echo "</td>";
            echo "</tr>";
            echo "\n";
        }
        ?>


        </tbody>

    </table>


    <table border="0" style="margin:0px; padding:0px;" width="100%"><tr><td style="border:0px;" align="left">
                <input type="hidden" name="selid" id="selid"  value="<?php echo $idlist;?>"/>
<!--
                <button class="button" onclick="selall()" >本页全选</button>
                <button class="button" onclick="selall2()" >本页反选</button>
                <button class="button button-danger" onclick="gosel()">添加选中</button>
-->
                <button class="button button-danger" onclick="parent.closefj()">关闭窗口</button>
            </td>
            <td style="border:0px;" align="right">


                <div id="page_string" style="float:right ; text-align:right ; margin:-4px">
                    <?php echo $pager;?>
                </div>
            </td>
        </tr></table>
    <?php
}
else{
    echo "<div style='text-align:center;'>";
    echo "暂无待选信息";
    echo "</div>";
}
?>

</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
selval();
/*
id:附件ID
profj_id:所属项目的附件自增ID
 */
function minussel(id,profj_id){
    setadd(0,profj_id,"<?php echo $typeid;?>",id);
}
    function addsel(id){
        setadd(1,0,"<?php echo $typeid;?>",id);
    }
    function gosel(){
        parent.$("#pro_type_fujian").val($("#selid").val());
        //window.location.href="<?php echo site_url("zcq_pro_type/getfjbox");?>?idlist="+$("#selid").val();
    }

    function orderby(ordertype,profjid){
        $.ajax({
            type:"POST",
            url:"<?php echo site_url("zcq_pro_type/pro_fujian_set_orderby");?>",
            data:{otype:ordertype,profjid:profjid,rnd:Math.random()},
            dataType:"text",
            error:function(a,b,c){

            },
            success:function (data) {
                //alert(data);
                window.location.reload();
            }
        });
    }
    //大于0为加
    function setadd(addtype,profjid,typeid,fjid){
        $.ajax({
            type:"POST",
            url:"<?php echo site_url("zcq_pro_type/pro_fujian_set_add");?>",
            data:{type:addtype,profjid:profjid,typeid:typeid,fjid:fjid,rnd:Math.random()},
            dataType:"text",
            error:function(a,b,c){

            },
            success:function (data) {
                window.location.reload();
            }
        });
    }
    parent.upadtefujian($("#idlist").val());
</script>