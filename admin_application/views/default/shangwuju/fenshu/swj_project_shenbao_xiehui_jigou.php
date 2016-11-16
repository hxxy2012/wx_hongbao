<table class="table table-bordered table-hover  m10">
    <thead>
    <tr>
    	<th style="display:none;"></th>
        <th width='30'>名次</th>
        <th >项目或活动名称</th>
        <th width="150">申报单位</th>
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
            	echo "<td>".$v["hdname"]."</td>";
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