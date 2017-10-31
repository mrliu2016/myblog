<?php
use yii\widgets\LinkPager;

$this->title = '修改优惠卷';
?>
<form action="/index/coupon-update" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>优惠券金额</td>
            <td>
                <input class="input_list" type="text" name="price" value="<?= $itemList['price'] ?>">
            </td>
        </tr>
        <tr>
            <td>类型</td>
            <td>
                <select class="form-control" name="type">
                    <option value="0">新人券</option>
                    <option value="1">其他</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><input type="hidden" value="<?= $id ?>" name="id"></td>
            <td><input type="submit" value="提交" class="submit_but"></td>
        </tr>
    </table>
</form>
