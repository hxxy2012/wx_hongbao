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
        .member_list_way{
            /*display: block;*/
            overflow: hidden;
            width: 165px;
            height: 30px;
            float: left;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        /*选择接收人的*/
        .member_list_way li {
            float: left;
            margin: 1px;
            width: 220px;
        }

        .member_list_way label {
            float: none;
            width: inherit;
        }

        .member_list_way label span {
            position: absolute;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: pre;
            width: inherit;
        }

    </style>

</head>
<body class="definewidth">

<div class="form-inline definewidth m20" >
    <form method="get" >
        关键字：
        <input type="text"  name="sel_title" id="search_title"
               class="abc input-default"
               placeholder="公司简称"
               value="<?php echo $sel_title;?>"
               style="width:200px;"
        />
        <button type="submit" class="btn btn-primary" >查询</button>&nbsp;&nbsp;




<input type="hidden" name="idlist" id="idlist" value="<?php echo $idlist;?>" />
<input type="hidden" name="usertype" value="<?php echo $usertype;?>"/>
    </form>
</div>

<?php
if(count($list)>0){
    ?>


<form method="post" id="myform">

    <table class="table table-bordered table-hover  m10">
        <tbody>
<tr><td>

        <?php
        $arr = explode(",",$idlist);
        foreach($list as $v){
            echo '<div class="member_list_way" title="'.$v["company"].'">';
            echo '<label><input type="checkbox" name="id[]" value="'.$v["uid"].'"';
            if(in_array($v["uid"], $arr))
            {
                echo " checked ";
            }
            echo '/>';
            echo "<span>{$v["username"]}</span>";
            echo '</label></div>';
            echo "\n";
        }
        ?>

</td></tr>
        </tbody>

    </table>


    <table border="0" style="margin:0px; padding:0px;" width="100%"><tr><td style="border:0px;" align="left">
                <input type="hidden" name="selid" id="selid"  value="<?php echo $idlist;?>"/>

                <button class="button" type="button" onclick="selall()" >全选</button>
                <button class="button" type="button" onclick="selall2()" >反选</button>
                <button class="button button-danger"  type="button" onclick="return gosel()">添加选中</button>

                <button class="button button-danger"  type="button" onclick="parent.closefj()">关闭窗口</button>
            </td>
            <td style="border:0px;" align="right">



            </td>
        </tr></table>
    <input type="hidden" name="usertype" value="<?php echo $usertype;?>"/>
</form>
    <?php
}
else{
    echo "<div style='text-align:center;'>";
    echo "暂无会员";
    echo "</div>";
}
?>

</body>
</html>
<script>
   function selall(){
       $("input[name='id[]']").each(
           function(){
               $(this)[0].checked = true;
           }
       )
   }
   function selall2(){
       $("input[name='id[]']").each(
           function(){
               $(this)[0].checked = !$(this)[0].checked;
           }
       )
   }
   function gosel(){
       i = 0;
       $("input[name='id[]']").each(
           function(){
               if( $(this)[0].checked ){
                   i++;
               }
           }
       );
       if(i==0){
           parent.parent.tip_show ("没有选中会员",0,1000);
            return false;
       }
       $("#myform").submit();

   }


</script>


