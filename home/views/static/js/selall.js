function seltr(trobj){
	var tdArr = trobj.children();	
	var id = tdArr.eq(0).html();
	var ids = $("#selid").val();
	
	if(ids==""){
		ids = id;
		trobj.css('background-color','#cccccc');
	}
	else{
		var idarr = ids.split(",");
		var isexists = false;
		
		for(i=0;i<idarr.length;i++){
			if(idarr[i]==id){
				isexists=true;
				
				idarr.splice(i,1);
				break;
			}
		}		
		if(isexists){						
			trobj.css('background-color','');
			var tmpid = "";
			for(i=0;i<idarr.length;i++){
				tmpid += tmpid==""?idarr[i]:(","+idarr[i]);
			}
			ids = tmpid;
		}
		else{
			ids+=","+id;
			trobj.css('background-color','#cccccc');
		}
	}
	$("#selid").val(ids);
}
function selall(){
	$("#selid").val("");
	ids = "";
	$("#result_").find("tr").each(function(){
		$(this).css('background-color','#cccccc');
		 var tdArr = $(this).children();			 
		 id = tdArr.eq(0).html();
		 if(ids==""){
			ids = id;
		 }
		 else{
			ids += ","+id;
		 }
	});
	$("#selid").val(ids);	
}
function selall2(){
	var ids = $("#selid").val();
	var ids2 = "";//记录反选ID
	$("#result_").find("tr").each(function(){
		if(ids==""){
			selall();
		}
		else{
			ids=','+ids+',';
		}
		var tdArr = $(this).children();			 
		id = tdArr.eq(0).html();
		
		if(ids.indexOf(","+id+",")>-1){
			$(this).css('background-color','');			
		}
		else{
			$(this).css('background-color','#cccccc');		
			 if(ids2==""){
					ids2 = id;
				 }
				 else{
					ids2 += ","+id;
				 }
		}
	});	
	$("#selid").val(ids2);	
}

/*
根据selid中的值将选的ID所在行显示灰色
 */
function selval(){
	var ids = $("#selid").val();
	//arr = ids.split(",");
	ids = ","+ids+",";
	$("#result_").find("tr").each(function(){
		var tdArr = $(this).children();
		id = tdArr.eq(0).html();
		if(ids.indexOf(","+id+",")>=0){
			$(this).css('background-color','#cccccc');
		}
	});
}