<?php
$this->title = '编辑礼物';
?>
<style>
    .gift-edit{
        padding-top: 20px;
        padding-left: 20px;
    }
</style>
<div class="gift-edit">
    <div class="img">

    </div>
    <div>
        <table class="table table-hover">
            <tbody>
                <tr>
                    <td>
                        ID
                    </td>
                    <td>
                        <?= $id ?>
                    </td>
                </tr>
                <tr>
                    <td>价格</td>
                    <td><input type="text" id="giftName"></td>
                </tr>

                <tr>
                    <td>是否可以连发</td>
                    <td>
                        <input type="radio" value="1">是
                        <input type="radio" value="0">否
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" value="确定" class="confirm">
                        <input type="button" value="取消" class="cancel">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


