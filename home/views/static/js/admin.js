function selectAll()
{
  var obj = document.getElementsByName("checkAll[]");
  if(document.getElementById("selAll").checked == false)
  {
  for(var i=0; i<obj.length; i++)
  {
    obj[i].checked=false;
  }
  }else
  {
  for(var i=0; i<obj.length; i++)
  {  
    obj[i].checked=true;
  }
  }
 
}

//当选中所有的时候，全选按钮会勾上
function setSelectAll()
{
	var obj=document.getElementsByName("checkAll[]");
	var count = obj.length;
	var selectCount = 0;
	
	for(var i = 0; i < count; i++)
	{
		if(obj[i].checked == true)
		{
			selectCount++;
		}
	}
	if(count == selectCount)
	{
		document.all.selAll.checked = true;
	}
	else
	{
		document.all.selAll.checked = false;
	}
}

//反选按钮
function inverse() {
	var checkboxs=document.getElementsByName("checkAll[]");
	for (var i=0;i<checkboxs.length;i++) {
	  var e=checkboxs[i];
	  e.checked=!e.checked;
	  setSelectAll();
	}
}
//通过js获取参数
function getQueryStringValue(name) { 
	var str_url, str_pos, str_para;
	var arr_param = new Array();
	str_url = window.location.href;
	str_pos = str_url.indexOf("?");
	
	str_para = str_url.substring(str_pos + 1);
	if (str_pos > 0) {
		//if contain # ----------------------begin
		str_para = str_para.split("#")[0];
		//-----------------------------------end
		arr_param = str_para.split("&");
		for (var i = 0; i < arr_param.length; i++) {
		var temp_str = new Array()
		temp_str = arr_param[i].split("=")
		if (temp_str[0].toLowerCase() == name.toLowerCase()) {
		return temp_str[1];
		}
		}
	}
	return "";
} 


function getCookie(name) {    
	 var nameEQ = name + "=";    
	 var ca = document.cookie.split(';');    //把cookie分割成组    
	 for(var i=0;i < ca.length;i++) {    
	 var c = ca[i];                      //取得字符串    
	 while (c.charAt(0)==' ') {          //判断一下字符串有没有前导空格    
	 c = c.substring(1,c.length);      //有的话，从第二位开始取    
	 }    
	 if (c.indexOf(nameEQ) == 0) {       //如果含有我们要的name    
	 return unescape(c.substring(nameEQ.length,c.length));    //解码并截取我们要值    
	 }    
	 }    
	 return false;    
}    
    
//清除cookie    
function clearCookie(name) {    
 	setCookie(name, "", -1);    
}    
    
//设置cookie    
function setCookie(name, value, seconds) {    
	 seconds = seconds || 0;   //seconds有值就直接赋值，没有为0，这个根php不一样。    
	 var expires = "";    
	 if (seconds != 0 ) {      //设置cookie生存时间    
	 var date = new Date();    
	 date.setTime(date.getTime()+(seconds*1000));    
	 expires = "; expires="+date.toGMTString();    
	 }    
	 document.cookie = name+"="+escape(value)+expires+"; path=/";   //转码并赋值    
} 
