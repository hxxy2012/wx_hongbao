<?php
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
        关键字：
        <input type="text"  name="sel_title" id="search_title"
               class="abc input-default"
               placeholder="附件标题"
               value="<?php echo $search_val['title'];?>"
               style="width:200px;"
        />
        <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;




<input type="hidden" name="idlist" value="<?php echo $idlist;?>" />
    </form>
</div>

<?php
if(count($list)>0){
    ?>
    <table class="table table-bordered table-hover  m10">
        <thead>
        <tr>
            <th width='50'>编号</th>
            <th>附件名称</th>
            <th width='60'>操作</th>
        </tr>
        </thead>
        <tbody id="result_">


        <?php

        foreach($list as $v){

            echo "<tr >";
            echo "<td>".$v["id"]."</td>";
            echo "<td>".$v["title"]."</td>";
            echo "<td>";
            if(in_array($v["id"],$idlist_arr)){
                echo "	
<a class=' icon-minus '
   			href=\"javascript:void(0);\" onclick='minussel(\"".$v["id"]."\")' ></a>";
            }
            else {
                echo "	
<a class=' icon-plus '
   			href=\"javascript:void(0);\" onclick='addsel(\"".$v["id"]."\")' ></a>";
                }

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
    echo "暂无信息";
    echo "</div>";
}
?>

</body>
</html>


<script src="/admin_application/views/static/Js/selall.js"></script>
<script>
selval();
function minussel(id){
    idlist=$("#selid").val();
    arr = idlist.split(",");
    idlist = "";
    for(i=0;i<arr.length;i++){
        if(arr[i]!=id){
            idlist += (idlist==""?"":",") + arr[i];
        }
    }
    parent.upadtefujian(idlist);
    window.location.href = "<?php echo site_url("zcq_pro_type/getfjbox");?>?per_page=<?php echo $per_page;?>&sel_title=<?php echo urlencode($search_val["title"]);?>&idlist="+idlist;
}
function addsel(id){

    idlist=$("#selid").val();
    arr = idlist.split(",");
    idlist = "";
    for(i=0;i<arr.length;i++){
        if(arr[i]!=id){
            idlist += (idlist==""?"":",") + arr[i];
        }
    }
    if(idlist!=""){
        idlist += ","+id;
    }
    else{
        idlist = id;
    }

    parent.upadtefujian(idlist);
    window.location.href = "<?php echo site_url("zcq_pro_type/getfjbox");?>?per_page=<?php echo $per_page;?>&sel_title=<?php echo urlencode($search_val["title"]);?>&idlist="+idlist;
}
    function gosel(){
        parent.$("#pro_type_fujian").val($("#selid").val());
        //window.location.href="<?php echo site_url("zcq_pro_type/getfjbox");?>?idlist="+$("#selid").val();
    }
</script>