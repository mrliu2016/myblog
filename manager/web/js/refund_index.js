$("#searchBtn").click(function () {
    $("#searchForm").submit();
});
$("#cleanBtn").click(function () {
    $(this).closest('form').find("input[type=text]").val("");
    $(this).closest('form').find("select").val("all");
});
$(document).ready(function () {
    choose($('#refundType').val(), $('#selectStatus').val());
});
function choose(value, selectValue) {
    var selObj = $("#selectStatus");
    if (value == "return_goods") {
        $("#selectStatus option[value=REFUND_CREATED]").remove()
        $("#selectStatus option[value=REFUND_CANCELLED]").remove()
        $("#selectStatus option[value=REFUND_REFUSED]").remove()
        $("#selectStatus option[value=RETURN_AGREED]").remove()
        $("#selectStatus option[value=REFUND_AGREED]").remove()
        $("#selectStatus option[value=RETURN_SHIPPED]").remove()
        $("#selectStatus option[value=REFUND_COMPLETED]").remove()
        $("#selectStatus option[value=RETURN_NOT_RECEIVED]").remove()

        selObj.append("<option value='REFUND_COMPLETED'>退货/退款完成</option>");
        selObj.append("<option value='REFUND_REFUSED'>买家退款或退货被卖家拒绝</option>");
        selObj.append("<option value='REFUND_CREATED'>买家发起退货，等待卖家同意</option>");
        selObj.append("<option value='RETURN_AGREED'>卖家同意，等待买家退货</option>");
        selObj.append("<option value='RETURN_SHIPPED'>买家发货，等待卖家收货</option>");
        selObj.append("<option value='RETURN_NOT_RECEIVED'>买家发货后，卖家未收到货</option>");
        selObj.append("<option value='REFUND_CANCELLED'>退款或退货取消</option>");
        if (selectValue != '')$('#selectStatus').val(selectValue);
    }
    if (value == "refund_money") {
        $("#selectStatus option[value=REFUND_CREATED]").remove()
        $("#selectStatus option[value=REFUND_CANCELLED]").remove()
        $("#selectStatus option[value=REFUND_REFUSED]").remove()
        $("#selectStatus option[value=RETURN_AGREED]").remove()
        $("#selectStatus option[value=RETURN_SHIPPED]").remove()
        $("#selectStatus option[value=REFUND_COMPLETED]").remove()
        $("#selectStatus option[value=RETURN_NOT_RECEIVED]").remove()

        selObj.append("<option value='REFUND_CREATED'>买家发起退款，等待卖家同意</option>");
        selObj.append("<option value='REFUND_COMPLETED'>退货/退款完成</option>");
        selObj.append("<option value='REFUND_REFUSED'>买家退款或退货被卖家拒绝</option>");
        selObj.append("<option value='REFUND_CANCELLED'>退款或退货取消</option>");
        if (selectValue != '')$('#selectStatus').val(selectValue);
    }

    if (value == "all") {
        $("#selectStatus option[value=REFUND_CREATED]").remove()
        $("#selectStatus option[value=REFUND_CANCELLED]").remove()
        $("#selectStatus option[value=REFUND_REFUSED]").remove()
        $("#selectStatus option[value=RETURN_AGREED]").remove()
        $("#selectStatus option[value=RETURN_SHIPPED]").remove()
        $("#selectStatus option[value=REFUND_COMPLETED]").remove()
        $("#selectStatus option[value=RETURN_NOT_RECEIVED]").remove()

        selObj.append("<option value='REFUND_CREATED'>买家发起退款或退货，等待卖家同意</option>");
        selObj.append("<option value='REFUND_COMPLETED'>退货/退款完成</option>");
        selObj.append("<option value='REFUND_REFUSED'>买家退款或退货被卖家拒绝</option>");
        selObj.append("<option value='RETURN_AGREED'>卖家同意，等待买家退货</option>");
        selObj.append("<option value='RETURN_SHIPPED'>买家发货，等待卖家收货</option>");
        selObj.append("<option value='RETURN_NOT_RECEIVED'>买家发货后，卖家未收到货</option>");
        selObj.append("<option value='REFUND_CANCELLED'>退款或退货取消</option>");


        if (selectValue != '')$('#selectStatus').val(selectValue);
    }
}
function overShow(shipExpressName, shipExpressId) {
    if (shipExpressName.length == 0 || shipExpressId.length == 0) {
        layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['800px', '300px'], //宽高
            content: ''
        });
    }
    else {
        $.ajax({
            url: "/refund/delivery-info?expressId=" + shipExpressId + "&expressName=" + shipExpressName,
            type: "get",
            cache: false,
            dataType: "json",
            success: function (data) {
                if (data != null) {
                    var json = eval(data);
                    var html = "<table border='1' bordercolor='#D3D3D3' width='100%'>";
                    for (var i = 0, l = json.length; i < l; i++) {
                        html += "<tr><td style='vertical-align:middle; text-align:center;'>" + json[i].nodeTime + "</td><td>&nbsp;&nbsp;" + json[i].details + "</td></tr>"
                    }
                    html += "</table>"
                } else {
                    html = "&nbsp;&nbsp;:(该单号暂无物流进展，请稍后再试，或检查公司和单号是否有误";
                }
                layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['850px', '300px'], //宽高
                    content: html
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }
}
//时间控件
$(".form_datetime").datetimepicker({
    autoclose: true,//选中之后自动隐藏日期选择框
    format: "yyyy-mm-dd hh",
    language: 'zh-CN',
    minView: 1
});
//导出BD池中选中的商品
$(".export_to_excel").click(function () {
    layer.confirm('确定导出数据到Excel中', {
            btn: ['确定', '取消'] //按钮
        },
        function () {
            var url = window.location.href;
            var params = "";
            if (url.indexOf("?") >= 0) {
                params = "?" + url.substring(url.lastIndexOf('?') + 1);
            }
            $("#data_iframe").attr("src", "/refund/export-excel3" + params);
            layer.closeAll();
        }, function () {
        });
});
function addNote(obj, shopOrderId, real, orderId) {
    if (real == 0) {
        $(obj).parents('td').find("[name='noteContent']").html("<input type='text' name='notemsg' /><button onclick='addNote(this," + shopOrderId + ",1," + orderId + ")'>确定</button>");
    } else {
        var note = $(obj).parents('td').find("[name='noteContent']").find('input').val();
        $.ajax({
            type: 'post',
            async: false,
            url: '/customer-service-order/add-order-note',
            data: {
                shopOrderId: shopOrderId,
                note: note,
                orderId: orderId
            },
            dataType: 'json',
            success: function (data) {
                if (data.errno) {
                    $(obj).parents('td').find("[name='moreTag']").html("<a href='javascript:showNoteMore(" + orderId + ",1)'>更多</a>");
                    $(obj).parents('td').find("[name='noteContent']").html(data.msg);
                    layer.msg('添加成功', {time: 2000, icon: 1});
                } else {
                    layer.msg(data.msg, {time: 2000, icon: 5});
                }
            }
        });
    }
}
function showNoteMore(orderId, type) {
    var html = $("[name='showNote']").html();
    $.ajax({
        type: 'POST',
        url: '/customer-service-order/get-order-note',
        dataType: 'json',
        data: {
            orderId: orderId,
            type: type
        },
        beforeSend: function (data) {
            layer.load(1);
        },
        success: function (data) {
            layer.closeAll('loading');
            if (!data.errno) {
                html = html + data.msg;
            } else {
                html = html + data.msg;
            }
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['850px', '400px'], //宽高
                content: html
            });
        }
    });
}
function delNote(obj, id) {
    layer.confirm('确定删除？', {
            btn: ['确定', '取消'] //按钮
        },
        function () {
            $.ajax({
                type: 'post',
                async: false,
                url: '/customer-service-order/del-order-note',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function (data) {
                    if (data.errno) {
                        $(obj).parent().remove();
                        if (data.msg.msg == 0) {
                            $("[name='note_td_" + data.msg.shopOrderId + "']").find("[name='noteContent']").html('无');
                            $("[name='note_td_" + data.msg.shopOrderId + "']").find("[name='moreTag']").html('');
                            layer.closeAll();
                        } else {
                            $("[name='note_td_" + data.msg.shopOrderId + "']").find("[name='noteContent']").html(data.msg.msg);
                        }
                        layer.msg("删除成功", {time: 2000, icon: 1});
                    } else {
                        layer.msg(data.msg, {time: 2000, icon: 5});
                    }

                }
            });
        }, function () {
        });
}



