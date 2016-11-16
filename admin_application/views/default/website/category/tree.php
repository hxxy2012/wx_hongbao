<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>添加广告</title>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css" />   
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" />   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>   
	<script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/admin.js"></script>
	
<link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>/<?php echo APPPATH ?>/views/static/assets/js/bui-min.js"></script>
	
   <style type="text/css">
        body {
            padding-bottom: 0px;
            padding-left:0px;
            padding-right:0px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }



    </style>
</head>
<body>

<form   method="post" class="definewidth m10" id="myform">

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
    store : store
  });
  tree.render();
  

  tree.on('itemclick',function(ev){
    var item = ev.item;
    //$('.log').text(item.text);
     //alert(item.listpage);
    //window.parent.frames["frameright"].location.href("infolist.shtml?typeid="+item.text);
    if(item.listpage==""){
    	parent.document.getElementById("frameright").src="infolist.shtml?typeid="+item.id+"&rnd="+Math.random();
    }
    else{
    	parent.document.getElementById("frameright").src=decodeURIComponent(item.listpage)+"?typeid="+item.id+"&rnd="+Math.random();
    }    	 
    //window.parent["frameright"].location.href();
         
  });
});

});
</script>

</form>
</body>
</html>