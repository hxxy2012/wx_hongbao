<?php
if (!defined('BASEPATH')) {
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>任务列表</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Css/style.css" />   
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" />   
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/Js/admin.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>        
        <script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/config-min.js"></script>
        
<script>
BUI.use('common/page');
</script>     
    </head>
    
    
    <body>
    

    <div class="definewidth">

        <table class="table table-bordered table-hover m10 sortable">
            <thead>
                <tr>
                 <th width="10">ID</th>
                 <th width="150">标题</th>
                 <th>备注</th>
                 <th width="120">操作</th>
                </tr>
            </thead>
            <tbody  id="result_">
            <?php
            foreach($list as $v){
            	
            	echo "<tr>";
            	echo "<td>".$v["id"]."</td>";
            	echo "<td>".$v["title"]."</td>";
            	echo "<td>".$v["content"]."</td>";
            	echo "<td>";
            	echo "<a class='page-action icon-edit' title='编辑:".$v["title"]."' href='#'
        		data-href='".site_url("Website_model/add")."?id=".$v["id"]."' data-id='".__CLASS__.$v["id"]."' >
        		</a>";
            	echo "&nbsp;&nbsp;";
            	echo "<a class='page-action icon-th' title='管理字段:".$v["title"]."' href='#'
        		data-href='".site_url("Website_model/edit")."?id=".$v["id"]."' data-id='".__CLASS__."man".$v["id"]."' >
        		</a>";
            	echo "&nbsp;&nbsp;";
            	echo "<a class='page-action icon-plus' title='添加字段:".$v["title"]."' href='#'
        		data-href='".site_url("Website_model/edit")."?pid=".$v["id"]."' data-id='".__CLASS__."field".$v["id"]."' >
        		</a>";            	
            	
            	/*
            	echo "  ";
            	echo "<a class='page-action icon-trash' title='添加字段:".$v["title"]."' href='#'
        		data-href='/admin.php/Website_model/add.shtml?id=".$v["id"]."' data-id='".__CLASS__.$v["id"]."' >
        		</a>";            	  
            	*/
            	echo "</td>";
            	echo "</tr>";
            }
            ?>
            
            </tbody>  

        </table>
       
        
        <div id="page_string" class="form-inline definewidth m1" style="float:right ; text-align:right ; margin:-4px">
<?php echo $pager;?>
        </div>
        <div style="float:none;clear:both;"></div>    
</div>    
    </body>
    </html>
<script>
                
            
//alert(BUI.Tab.NavTabItem);
//alert(top.topManager.get);
</script>    