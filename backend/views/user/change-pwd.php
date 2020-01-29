<?php

$this->title = '修改密码';

?>
<style type="text/css">
    tr td {
        padding-top: 20px;
        padding-left: 30px;
    }

    .input_list, .form-control {
        width: 200px;
        height: 40px;
        border: 1px solid #428bca;
        border-radius: 5px;

    }

    .input_list1 {
        width: 200px;
        height: 40px;
        border-top: 1px solid #428bca;
        border-left: 1px solid #428bca;
        border-right: 1px solid #428bca;
        border-radius: 5px;

    }

    .submit_but {
        width: 150px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 10px;
        background-color: #428bca;
        color: #fff;
        border: 1px solid #428bca;
        margin-bottom: 100px;
    }

    .pull_right_up {
        position: relative;
        margin-top: 30px;
        margin-left: 50px;
    }

    .profileButton1 {
        width: 90px;
        height: 30px;
        background-color: #337ab7;
        border-radius: 10px;
        color: #fff;
        line-height: 30px;
        display: inline-block;
        text-align: center;
    }

    #profileButton1 {
        position: absolute;
        width: 90px;
        height: 30px;
        opacity: 0;
        top: 0;
    }

    #selectImg1 {
        width: 200px;
        height: 200px;
    }

    #serverSelect {
        position: absolute;
        top: 58px;
        background-color: #fff;
        border: 1px solid #428bca;
        border-radius: 5px;
    }

    ul li {
        list-style: none;
    }

    .hidden-list {
        display: none;
    }

    #show-hid {
        display: none;
    }

    .hidden-list-1 {
        padding: 5px 20px;
        display: inline-block;
        background-color: #0d47a1;
        border-radius: 5px;
        color: #fff;
    }

</style>
<style type="text/css">

    input {
        float: left;
        font-size: 12px;
        width: 150px;
        font-family: arial;
        padding: 3px;
        border: 1px solid black;
    }

    input.error {
        border: 1px solid red;
    }

    #tips {
        float: left;
        margin: 2px 0 0 20px;
    }

    #tips span {
        float: left;
        width: 50px;
        height: 20px;
        color: white;
        background: green;
        margin-right: 2px;
        line-height: 20px;
        text-align: center;
    }
</style>
<section>
    <div class="content-heading bg-white">
        <div class="row">
            <div class="col-sm-9">
                <form method="post" role="form" onsubmit="return check()">
                    <table>
                        <tr>
                            <td>原密码</td>
                            <td><input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="请输入原密码"></td>
                        </tr>
                        <tr>
                            <td>新密码</td>
                            <td><input type="password" class="form-control" style="width:300px;" id="password" name="newPassword" placeholder="6-16位，同时包含大小写、数字或特殊字符">
                                <div id="tips"><span></span><span></span><span></span><span></span></div>
                            </td>
                        </tr>
                        <tr>
                            <td>确认新密码</td>
                            <td><input type="password" class="form-control" id="newPassword2" name="newPassword2" placeholder="请确认新密码"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><button type="submit" class="mb-sm btn btn-primary ripple">提交</button></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function(){
        var message = '<?=$message?>';
        if(message!=''){
            layer.msg(message);
        }
    });

    function check() {
        var oldPassword = $("#oldPassword").val();
        var password = $("#password").val();
        var newPassword2 = $("#newPassword2").val();
        if(oldPassword==''){
            layer.msg('请输入原密码');
            $("#oldPassword").focus();
            return false;
        }
        if(password==''){
            layer.msg('请输入新密码');
            $("#password").focus();
            return false;
        }
        if ($("#password").val() == '') {
            layer.msg("密码 不能为空")
            $("#password").focus();
            return false;
        }
        if ($("#password").val() != "") {
            if ($("#password").val().length < 6) {
                layer.msg("密码须由6-16个字符组成，同时包含大小写、数字或特殊字符")
                $("#password").focus();
                return false;
            }
            if (modes < 3) {
                layer.msg("密码安全程度必须在 中 以上，同时包含大小写、数字或特殊字符")
                $("#password").focus();
                return false;
            }
            if(newPassword2==''){
                layer.msg('请确认新密码');
                $("#newPassword2").focus();
                return false;
            }
            if ($("#newPassword2").val() != $("#password").val()) {
                layer.msg("密码不一致")
                $("#newPassword2").focus();
                return false;
            }
        }
        return true;
    }

</script>
<script type="text/javascript">
    var modes = 0;
    $(function () {
        var aStr = ["弱", "低", "中", "强"];

        function checkStrong(val) {
            modes = 0;
            if (val.length < 6) return 0;
            if (/\d/.test(val)) modes++; //数字
            if (/[a-z]/.test(val)) modes++; //小写
            if (/[A-Z]/.test(val)) modes++; //大写
            var regEn = /[`~!@#$%^&*()_+<>?:"{},.\/;'[\]]/im,
                regCn = /[·！#￥（——）：；“”‘、，|《。》？、【】[\]]/im;
            if (regEn.test(val) || regCn.test(val)) {
                modes++; //特殊字符
            }
            //if (val.length > 12) return 4;
            return modes;
        };
        $("#password").keyup(function () {
            var val = $(this).val();
            var num = checkStrong(val);
            switch (num) {
                case 0:
                    break;
                case 1:
                    $("#tips span").css('background', 'yellow').text('').eq(num - 1).css('background', 'red').text(aStr[num - 1]);
                    break;
                case 2:
                    $("#tips span").css('background', 'green').text('').eq(num - 1).css('background', 'red').text(aStr[num - 1]);
                    break;
                case 3:
                    $("#tips span").css('background', 'green').text('').eq(num - 1).css('background', 'red').text(aStr[num - 1]);
                    break;
                case 4:
                    $("#tips span").css('background', 'green').text('').eq(num - 1).css('background', 'red').text(aStr[num - 1]);
                    break;
                default:
                    break;
            }
        })
    })
</script>