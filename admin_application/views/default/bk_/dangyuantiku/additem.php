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
   <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
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
<body class="definewidth" style="padding: 0px;">


<form action="" onsubmit="return postform()" method="post" name="myform" id="myform">
<input type="hidden" name="id" id="id" value="" />
<input type="hidden" name="newitem" id="newitem" value="" />
<input type="hidden" name="olditem" id="olditem" value="" />
<input type="hidden" name="pid" id="pid" value="0"/>
<input type="hidden" name="guid" id="guid" value="<?php echo create_guid();?>"/>
<input type="hidden" name="subitem" id="subitem" value="0"/>
<input type="hidden" name="i" id="i" value="<?php echo $i;?>" />

<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">题目：</td>
        <td>

<input type="text" style="width:200px;" 
name="title"
id="title" 
value="<?php echo !isset($item["dati"])?"":$item["dati"];?>" required="true"       
        />        
*  
        </td>
    </tr>
   <tr>
        <td class="tableleft">方式：</td>
        <td>
<input type="radio" name="seltype" id="seltype1" value="0" <?php echo !isset($item["seltype"])?"checked":($item["seltype"]=="0"?"checked":"");?> />单选
<input type="radio" name="seltype" id="seltype2" value="1" <?php echo !isset($item["seltype"])?"":($item["seltype"]=="1"?"checked":"");?> />多选
        </td>
    </tr> 
</table>
<div id="grid">

</div>
<script type="text/javascript">
  BUI.use(['bui/grid','bui/data'],function (Grid,Data) {
	enumObj = {"1":"正确","0":"不正确"},	
    columns = [
{title : '选项',dataIndex :'title',
	editor : {
				xtype : 'text',
				rules:{required:true}
			  }
},
{title : '是否正确',
	dataIndex :'istrue',
	editor : 
		{xtype :'select',			
			items:enumObj,
			rules:{required : true}
		},		
		renderer:Grid.Format.enumRenderer(enumObj)
}                        
          ],
      //默认的数据
      data = 
<?php 
if(isset($item["subitem"]))
{
	echo json_encode($item["subitem"]);
}
else{
	echo "[]";	
}
?>		
    	,
      store = new Data.Store({
        data:data
      }),
      editing = new Grid.Plugins.CellEditing(),
      grid = new Grid.Grid({
        render : '#grid',
        columns : columns,
        width : 566,
        forceFit : true,
        store : store,
        plugins : [Grid.Plugins.CheckSelection,editing],
        tbar:{
            items : [{
              btnCls : 'button button-small',
              text : '<i class="icon-plus"></i>添加',
              listeners : {
                'click' : addFunction
              }
            },
            {
              btnCls : 'button button-small',
              text : '<i class="icon-remove"></i>删除',
              listeners : {
                'click' : delFunction
              }
            }]
        }

      });
    grid.render();

    function addFunction(){
      var newData = {title :'请输入选项',istrue:'0'};
      store.add(newData);
      editing.edit(newData,'title'); //添加记录后，直接编辑
    }

    function delFunction(){
      var selections = grid.getSelection();
      store.remove(selections);
    }
    //var logEl = $('#log');
    /*
    $('#btnSave').on('click',function(){
    	var records = store.getResult();
      if(editing.isValid()){
        
        //logEl.text(BUI.JSON.stringify(records));
        alert(BUI.JSON.stringify(records));
      }
      alert(BUI.JSON.stringify(records));
    });
    */
  });

  function showresult(){
	  if(editing.isValid()){
		  var records = store.getResult();
		  //alert(BUI.JSON.stringify(records));
		  //写入HIDDEN
		  $("#subitem").val(BUI.JSON.stringify(records));
		  return true;
	  }
	  else{
		  parent.parent.tip_show("请输入选项内容",2,2000);
		  return false;
	  }
  }

  function postform(){
	  
		if($("#myform").Valid()) {
									
			if(!showresult())
			{
				return false;
			}
			else{
				//判断标题有无重名
				var bigitem = parent.document.getElementById("bigitem").value;
				var title_repeate = 0;
				if(bigitem!=""){
					jsonobj = eval("["+bigitem+"]");
					for(i=0;i<jsonobj.length;i++){
						if(jsonobj[i].dati==$("#title").val()){
							title_repeate++;
						}
					}
				}
				//alert(title_repeate);
				if($("#i").val()>=0)
				{
					//修改
					if(title_repeate>1){
						parent.parent.tip_show("题目重名，请改名。",2,2000);
						$("#title").focus();
						return false;					
					}
				}
				else{
					//新增
					if(title_repeate>0){
						parent.parent.tip_show("题目重名，请改名。",2,2000);
						$("#title").focus();
						return false;					
					}
				}
				//0单选 1多选
				var seltype2 = $('input:radio[name=seltype]:checked').val();;
				//alert(seltype2);
				var subitem_json=$("#subitem").val();
				subitem_json = eval(subitem_json);

				//检查有无重名
				var isrepate = false;
				for(i=0;i<subitem_json.length;i++){
					for(j=0;j<subitem_json.length;j++){
						if(subitem_json[i].title==subitem_json[j].title && i!=j){
							//alert(subitem_json[i].title);
							//alert(subitem_json[j].title);
							isrepate = true;
							break;
						}
					}
				}
				//alert("subitem_json.length="+subitem_json.length+"is="+isrepate);
				if(isrepate){
					parent.parent.tip_show("选项有重复，请改名。",2,2000);
					return false;
				}				

				
				if(subitem_json.length<=1){
					parent.parent.tip_show("选择题至少要有两项。",2,2000);
					return false;				
				}
				else{

							
					
					var istrue = 0;					
					for(i=0;i<subitem_json.length;i++){
						//判断是否有正确答案	
						if(subitem_json[i]["istrue"]==1){
							istrue++;							
						}			
					}
					if(seltype2==0){
						if(istrue>1){
							parent.parent.tip_show("单选情况下，正确答案，只能设一个。",2,2000);
							return false;	
						}
						if(istrue==0){
							parent.parent.tip_show("没有正确答案，只能设一个。",2,2000);
							return false;	
						}						
					}
					if(seltype2==1){
						if(istrue==0){
							parent.parent.tip_show("没有正确答案，最少设一个。",2,2000);
							return false;	
						}				
					}
				}
			}	
			return true;
		}
		else{	
			return false;
		}
	}
  
</script>

<div style="padding-top:10px;text-align:center;">
<input class="btn btn-primary" type="submit" id="btnSave" value="确认" />
<input class="btn btn-warning" type="button" onclick="parent.dialog2.close();" value="取消" />
</div>


</body>
</html>



