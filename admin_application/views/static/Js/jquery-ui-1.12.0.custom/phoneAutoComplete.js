/**
 * Created by Administrator on 2016-09-07.
 * 需要先导入jquery ui
 */

/**
 *
 * @param $ele jq对象，input元素
 */
function phoneAuto($ele) {
    var availableTags = [
        "133", "153", "180", "181", "189", "177", "173", "130", "131", "132", "155", "156", "145", "185", "186", "176", "185", "134", "135", "136", "137", "138", "139", "150", "151", "152", "158", "159", "182", "183", "184", "157", "187", "188", "147", "178", "184"
    ];
    var p1 = ['1'];
    var p2 = ['3', '4', '5', '7', '8'];
//var p3 = ['0', '1', '2', '3', '4', '5', '7', '8', '9'];
    var exceed = false;


    $.widget( "custom.phoneauto", $.ui.autocomplete, {
        _renderItem: function (ul, item) {
            //渲染
            var str_plus = "";
            if (exceed) {
                str_plus += "<span style='float: right'>位数超过11位</span>"
            }
            return $("<li>")
                .attr("data-value", item.value)
                .append(item.label + str_plus)
                .appendTo(ul);
        }
    } );

    $ele.phoneauto({
        source: function (request, response) {
            //获取用户输入的内容
            var text = request.term;
            //最终返回的结果
            var result = [];
            var matcher = null;

            var exceed = false;

            if (text.length == 1) {
                //长度为1
                result = $.map(p2, function (value, index) {
                    return 1 + value;
                });
                response(result);
            } else if (text.length == 2) {
                //长度为2

                //
                matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(text), "i");
                result = $.grep(availableTags, function (value, index) {
                    //判断是否符合数组
                    return matcher.test(value);
                });

                if (result.length > 0) {
                    //符合
                    response(result);
                } else {
                    //不符合
                    result = $.map(p2, function (value, index) {
                        return 1 + value;
                    });
                    response(result);
                }

            } else {
                //长度大于等于3

                //取其前三位
                var pre = text.substr(0, 3);

                if ($.inArray(pre, availableTags) > -1) {
                    //符合
                    response([text.substr(0, 11)]);
                } else {
                    //不符合
                    pre = text.substr(0, 2);//取前两位
                    //后面的数字
                    var suf = text.substr(3, 8);
                    matcher = new RegExp("^" + pre, "i");
                    var finded = $.grep(availableTags, function (value, index) {
                        //判断是否符合数组
                        return matcher.test(value);
                    });
                    result = $.map(finded, function (value, index) {
                        return value + suf;
                    });
                    if (result.length > 0) {
                        response(result);
                    } else {
                        result = $.map(p2, function (value, index) {
                            return 1 + value;
                        });
                        response(result);
                    }
                }
                //response([text]);
            }

            if (text.length > 11) {
                exceed = true;
            }
        },
        minLength: 1
    });
}

