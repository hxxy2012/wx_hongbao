按项目类型：
<?php
foreach($fuwu_list as $v){
?>
<input type="checkbox" name="project_type[]" onclick="gosearch()" value="<?php echo $v["id"];?>"
<?php
if(isset($search_fuwu)){
	if(is_array($search_fuwu)){
		echo in_array($v["id"],$search_fuwu)?" checked ":"";
	}
}
?>
/>
<?php echo $v["name"];?>
&nbsp;&nbsp;
<?php
}
?>
<script src="/admin_application/views/static/Js/urlopt.js"></script>
<script>
function gosearch(){
	par = "";
	$("input[name='project_type[]']:checked").each(function(index, element) {
        if(par == "" ){
			par = $(this).val();	
		}
		else{
			par += ","+$(this).val();
		}
    });
	//replaceParamVal("project_type",url);
	var myurl=new objURL(); //初始化。也可以自定义URL： var myurl=new objURL('http://www.111cn.net'); 
	myurl.remove("project_type"); //移除arg参数
	url = myurl.url();
	window.location.href=url+"&project_type="+par;
}

</script>
<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
    	<th style="display:none;"></th>
        <th width='30'>名次</th>
        <th>项目类型</th>
        <th>申报单位</th>
        <th width="150">状态</th>
        <th width='50'>详情</th>     
        <th width='50'>分数</th>     
        <th width='80'>设置分数</th>             
    </tr>
    </thead>
  <tbody id="result_">
  

           <?php
           
            foreach($list as $v){
            	
            	echo "<tr onclick='seltr($(this))'>";
				echo "<td style='display:none;'>".$v["id"]."</td>";
            	echo "<td>".$v["pm"]."</td>";
            	echo "<td>".$v["typename"]."</td>";
				echo "<td>".$v["danwei"]."</td>";
				echo "<td>".$checkstatus[$v["checkstatus"]]."</td>";
				echo "<td><a href='' class='page-action' id='check_shenbao_list_".$v["id"]."' 
data-href='".site_url("swj_shenbao/check_shenbao")."?id=".$v["id"]."'
 title=\"查看".$v["danwei"]."的申报资料\">查看</a></td>";
				echo "<td align='center'>".($v["fenshu"])."</td>";
				echo "<td><input type='text' maxlength='3' name='fenshu[]' valType='int' sbid='".$v["id"]."' style=' padding:0px; margin:0px; width:30px;' value=''/></td>";       				
            	echo "</tr>";
            	echo "\n";
            }
            ?>  
  
  
  </tbody>  
  
  </table>
