<?php
if(is_super_admin()){
?>
<form method="post" id="check_form" >
<?php 
switch($model["checkstatus"]){
	case "0":
		echo '<tr>';
		echo '<td class="tableleft">';
		echo '审核操作';
		echo '</td>';		
		echo '<td colspan="3">';
		echo "<input type='radio' name='checkstatus' checked id='checkstatus1' value='10'/>";
		echo "初审通过";
		echo "&nbsp;&nbsp;";
		echo "<input type='radio' name='checkstatus' id='checkstatus2' value='-10'/>";
		echo "初审不通过";		
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="tableleft">';
		echo '审核意见';
		echo '</td>';
		echo '<td colspan="3">';
		echo '<textarea name="checkbeizhu" id="checkbeizhu" style="width:300px; height:80px;"></textarea>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="tableleft">';
		echo '是否发送短信';
		echo '</td>';
		echo '<td colspan="3">';
		echo '<input type="checkbox" name="sendmms" checked="checked" value="yes" />(发不出请检查：系统管理->系统参数设置->站点设置->短信开关在打开状态)';
		echo '</td>';
		echo '</tr>';				
	break;	
	default:
		echo '<tr>';
		echo '<td class="tableleft">';
		echo '审核操作';
		echo '</td>';		
		echo '<td colspan="3">';		
		echo @$checkstatus[$model["checkstatus"]];
		echo "<span style='font-weight:bold;'>(操作时间：".date("Y-m-d H:i",$model["checktime"]).")</span>";		
		if($model["checkstatus"]=="10"){
			echo "<span style='color:blue;'>    提醒：请到得分排名处补录分数</span>";
		}
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="tableleft">';
		echo '审核意见';
		echo '</td>';
		echo '<td colspan="3">';
		echo str_replace("\n","<br/>",($model["checkbeizhu"]==""?"-":$model["checkbeizhu"]));
		echo '</td>';
		echo '</tr>';			
	break;
	
	
	
}
?>
<?php
if($model["checkstatus"]=='0' && is_super_admin()){
?>
<tr>
<td colspan="4">
<div style="text-align:center;">
<input type="hidden" name="id" value="<?php echo $model["id"];?>">
<input type="button" class="btn btn-primary" onclick="return chkform()" value="保存" />&nbsp;&nbsp;
<a  class="btn btn-warning" href="#" onClick="top.topManager.closePage();">关闭</a>  
</div>
<script>
function chkform(){
  checkstatus = "<?php echo $model["checkstatus"];?>";  
  if(checkstatus=='0'){
	    content = $("#checkbeizhu").val();
		content = content.replace(/\s/ig,"");
		if($("#checkstatus2")[0].checked  && content==""){
			alert("请输入审核意见");
			$("#checkbeizhu").focus();
			return false;
		}
  }
  $("#check_form").submit();
}
</script>
</td>
</tr>  
<?php
}
else{
?>
<tr>
<td colspan="4">
<div style="text-align:center;">
<a  class="btn btn-warning" href="#" onClick="top.topManager.closePage();">关闭</a>  
</div>
</td>
</tr>
<?php
}
?>
</form>
<?php
}
?>

