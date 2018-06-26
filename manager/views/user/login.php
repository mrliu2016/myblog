<?php
$this->context->layout=false;
$this->title = '用户登录';
?>
<style>
.s-user-login{

}
</style>
<div class="s-user-login">
    <form>
        <table>
            <tr>
                <td>用户名：</td>
                <td><input type="text" style="width:100px;"></td>
            </tr>
            <tr>
                <td>密码：</td>
                <td><input type="text" style="width:100px;"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button>登录</button>
                </td>
            </tr>
        </table>
    </form>
</div>