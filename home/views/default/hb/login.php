<!DOCTYPE html>
<html>
<head>
    <title>手机摇一摇抢红包-<?php echo $config["site_fullname"];?></title>
    <?php $this->load->view(__TEMPLET_FOLDER__ . '/hb/headerinc.php'); ?>
    <style>
        body{background-color: #a40000;}
        table td{color:#000; font-size: 24px; line-height: 120%;}
        table td input{color:blue;}
    </style>
</head>
<body>
<form>

    <table>
        <tr><td>


                <img width="100%" src="<?php echo $this->config->item("home_img")==""?"/home/views/static/hb/images":$this->config->item("home_img");?>/hb.jpg"/>

            </td></tr>
        <tr><td>
                <br/>手机号
            <input type="text" name="tel" id="tel" required placeholder="请输入手机号" value="" maxlength="11" style="height:30px;width: 99%"/>
        </td>
        </tr>
        <tr>
            <td><input type="hidden" name="openid" id="openid" value="<?php echo $openid;?>"/>
                <input type="button" name="btn_login" onclick="return gologin()"   id="btn_login" style="height: 28px; width: 100%" value="　　确　　认　　"/>
            </td>

        </tr>
    </table>
    <script>
        function gologin() {
            tel = $("#tel").val();
            if(tel==""){
                alert("请输入11位的手机号，再点击确认");
                return false;
            }
            tel = tel.replace(/\d/ig,"");

            if(tel!=""){
                alert("手机格式不对");
                return false;
            }

            $.ajax({
                url: "<?php echo site_url("home/dologin");?>",
                data:{tel:$("#tel").val(),openid:$("#openid").val()},
                dataType:"text",
                type: "post",
                beforeSend: function () {
                    $("#btn_login").attr("disabled", "disabled");
                },
                success: function (msg) {
                   msg = msg.replace(/\s/ig,"");
                    if (msg == "ok") {
                       window.location.href = "<?php echo site_url("home/index");?>";

                    }
                    else {
                       alert(msg);
                       $("#btn_login").removeAttr("disabled");
                    }
                }
            });
        }
    </script>
</form>
</body>
</html>
