
/**
返回页首
**/
function gotoTop(acceleration,stime) {
   acceleration = acceleration || 0.1;
   stime = stime || 10;
   var x1 = 0;
   var y1 = 0;
   var x2 = 0;
   var y2 = 0;
   var x3 = 0;
   var y3 = 0; 
   if (document.documentElement) {
       x1 = document.documentElement.scrollLeft || 0;
       y1 = document.documentElement.scrollTop || 0;
   }
   if (document.body) {
       x2 = document.body.scrollLeft || 0;
       y2 = document.body.scrollTop || 0;
   }
   var x3 = window.scrollX || 0;
   var y3 = window.scrollY || 0;
   var x = Math.max(x1, Math.max(x2, x3));
   var y = Math.max(y1, Math.max(y2, y3));
   var speeding = 1 + acceleration;
   window.scrollTo(Math.floor(x / speeding), Math.floor(y / speeding));
 
   if(x > 0 || y > 0) {
       var run = "gotoTop(" + acceleration + ", " + stime + ")";
       window.setTimeout(run, stime);
   }
}


/*时间显示*/

function show_cur_times(){
//获取当前日期
 var date_time = new Date();
 //定义星期
 var week;
 //switch判断
 switch (date_time.getDay()){
     case 1: week="星期一"; break;
     case 2: week="星期二"; break;
     case 3: week="星期三"; break;
     case 4: week="星期四"; break;
     case 5: week="星期五"; break;
     case 6: week="星期六"; break;
     default:week="星期天"; break;
 }
 //年
 var year = date_time.getFullYear();
    //判断小于10，前面补0
   if(year<10){
    year="0"+year;
 }
 //月
 var month = date_time.getMonth()+1;
    //判断小于10，前面补0
  if(month<10){
     month="0"+month;
 }
 //日
 var day = date_time.getDate();
    //判断小于10，前面补0
   if(day<10){
    day="0"+day;
 }
 //时
 var hours =date_time.getHours();
    //判断小于10，前面补0
    if(hours<10){
    hours="0"+hours;
 }
 //分
 var minutes =date_time.getMinutes();
    //判断小于10，前面补0
    if(minutes<10){
    minutes="0"+minutes;
 }
 //秒
 var seconds=date_time.getSeconds();
    //判断小于10，前面补0
    if(seconds<10){
    seconds="0"+seconds;
 }
 //拼接年月日时分秒
 var date_str = year+"年"+month+"月"+day+"日 "+hours+":"+minutes+":"+seconds+" "+week;
 //显示在id为showtimes的容器里
 document.getElementById("top_time_see").innerHTML= date_str;
}
 //设置1秒调用一次show_cur_times函数
setInterval("show_cur_times()",100);


