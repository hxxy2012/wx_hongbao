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
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/Css/style.css"/>

    <script type="text/javascript" src="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/js/jquery-1.8.1.min.js"></script>
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  base_url() ;?>/<?php echo APPPATH?>/views/static/assets/css/bui-min.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/uploadfile/jquery.uploadify-3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/<?php echo APPPATH?>/views/static/Js/uploadfile/uploadify.css"/>

    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/validate/validator.js"></script>
    <script type="text/javascript" src="/<?php echo APPPATH?>/views/static/Js/layer/layer.js"></script>

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

        a{
            cursor: pointer;
        }
    </style>

</head>
<body class="definewidth">

<div class="form-inline definewidth m20" >
    <form method="POST" name="myform" id="myform" >

        <table class="table table-bordered table-hover  m10">
            <tr>
                <td class="tableleft" width="100">
                    标题：
                </td>
                <td>
                    <?php echo $model["title"];?>
                </td>
            </tr>
            <tr>
                <td class="tableleft">
                    发送时间：
                </td>
                <td>
                    <?php echo date("Y-m-d H:i",$model["createtime"]);?>
                </td>
            </tr>
            <tr>
                <td class="tableleft">
                 内容：
                </td>
                <td>
                    <?php
                    /* 为了能够转换url
                    * 使用方法：只需填写【控制器/方法】，里面的内容将会转化成链接
                     * 例子：<a href='【zcq_mail/myview】?id=116'>点击此处直接查看</a> 【zcq_mail/myview】将转化为链接
                     */
                    $string = $model['content'];
                    $pattern = '/([\s\S]*)【(.*?)】([\s\S]*)/i';
                    preg_match($pattern, $string, $matches);
                    if (!empty($matches)) {
                        $control_method = $matches[2];//【zcq_mail/myview】
                        $string = $matches[1] . site_url($control_method) . $matches[3];
                    }
                    echo $string;
                    ?>
                </td>

            </tr>
            <tr>
                <td colspan="2" >
                    <div style="text-align: center;">
                        <input class="button"  type="button" value="返回" onclick="closewin();"/>
                    </div>
                </td>

            </tr>
        </table>
    </form>
</div>

<script>
    function closewin(){
        parent.flushpage('<?php echo $ls;?>');
        top.topManager.closePage();
    }

    $(document).ready(function () {
        //点击打开新页面
        $("a.page-action").click(function () {
            var $this = $(this);
            top.topManager.openPage({
                id: $this.attr("data-id"),
                href: $this.attr("data-href"),
//                title: $this.attr("title"),
                reload: true
            });
        });
    })
</script>

</body>
</html>