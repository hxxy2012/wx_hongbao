//首页轮播图
jQuery.fn.extend({
    luara: function (a) {
        function s() {
            var a;
            switch (j) {
                case 'top':
                    a = h;
                    break;
                case 'left':
                    a = h * g;
                    break;
                default:
                    a = h
            }
            return a
        }

        function t() {
            var a = b.find('img').eq(0),
                c = {};
            return c.width = a.width(),
                c.height = a.height(),
                c
        }

        function u(b) {
            var b = b || a.speed || l / 6;
            return b > l ? b = l : l > b && 0 > b && (b = arguments.callee(-b)),
                b
        }

        function v() {
            q = setTimeout(function () {
                o++,
                    e.eq(o - 1).removeClass(n),
                o == g && (o = 0),
                    r(),
                    e.eq(o).addClass(n),
                    v()
            }, l)
        }

        var q,
            r,
            b = $(this).eq(0),
            c = $(this).find('ul').eq(0),
            d = c.find('li'),
            e = $(this).find('ol').eq(0).find('li'),
            f = b.find('img'),
            g = f.length,
            a = a || {},
            h = a.width || t().width,
            i = a.height || t().height,
            j = a.deriction || '',
            k = 'luara-' + j,
            l = (a.interval > 0 ? a.interval : -a.interval) || 3000,
            m = u(),
            n = a.selected,
            o = 0;
        b.width(h).height(i).addClass(k),
            c.width(s(j)).height(i),
            d.width(h).height(i),
            e.eq(0).addClass(n),
            function () {
                s = null,
                    t = null,
                    u = null
            }(),
            r = function () {
                switch (j) {
                    case 'top':
                        return function () {
                            c.animate({
                                top: -i * o + 'px'
                            }, m)
                        };
                    case 'left':
                        return function () {
                            c.animate({
                                left: -h * o + 'px'
                            }, m)
                        };
                    default:
                        return function () {
                            d.hide().eq(o).fadeIn(m)
                        }
                }
            }(),
            e.mouseover(function () {
                e.eq(o).removeClass(n),
                    o = e.index($(this)),
                    $(this).addClass(n),
                    r()
            }),
            b.mouseenter(function () {
                clearTimeout(q)
            }).mouseleave(function () {
                v()
            }),
            v()
    }
});

/*文本省略*/


/**/

/**
 返回页首
 **/
function gotoTop(acceleration, stime) {
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

    if (x > 0 || y > 0) {
        var run = "gotoTop(" + acceleration + ", " + stime + ")";
        window.setTimeout(run, stime);
    }
}


/*时间显示*/

function show_cur_times() {
//获取当前日期
    var date_time = new Date();
    //定义星期
    var week;
    //switch判断
    switch (date_time.getDay()) {
        case 1:
            week = "星期一";
            break;
        case 2:
            week = "星期二";
            break;
        case 3:
            week = "星期三";
            break;
        case 4:
            week = "星期四";
            break;
        case 5:
            week = "星期五";
            break;
        case 6:
            week = "星期六";
            break;
        default:
            week = "星期天";
            break;
    }
    //年
    var year = date_time.getFullYear();
    //判断小于10，前面补0
    if (year < 10) {
        year = "0" + year;
    }
    //月
    var month = date_time.getMonth() + 1;
    //判断小于10，前面补0
    if (month < 10) {
        month = "0" + month;
    }
    //日
    var day = date_time.getDate();
    //判断小于10，前面补0
    if (day < 10) {
        day = "0" + day;
    }
    //时
    var hours = date_time.getHours();
    //判断小于10，前面补0
    if (hours < 10) {
        hours = "0" + hours;
    }
    //分
    var minutes = date_time.getMinutes();
    //判断小于10，前面补0
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    //秒
    var seconds = date_time.getSeconds();
    //判断小于10，前面补0
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
    //拼接年月日时分秒
    var date_str = year + "年" + month + "月" + day + "日 " + hours + ":" + minutes + ":" + seconds + " " + week;
    //显示在id为showtimes的容器里
    //top.document.getElementById("top_time_see").innerHTML = date_str;
    var shtime = top.document.getElementById("top_time_see");
    if (shtime){
        shtime.innerHTML= date_str;
    }
}
//设置1秒调用一次show_cur_times函数
setInterval("show_cur_times()", 100);

/*后台页面导航*/

$(function () {
    // $(".member_background_aside li ul.mbs_in_ul").hide();
    $(".member_background_aside a.mbs_a").click(function () {
        $(this).toggleClass("curset");
        $(this).parent().siblings().find("a.mbs_a").removeClass("curset");
        $(this).parent().find("ul.mbs_in_ul").slideToggle("fast");
        $(this).parent().siblings().find("ul").slideUp("fast");
    });
});