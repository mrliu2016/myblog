//=========异常管理 begin===========
function mark(type, id) {
    $.ajax({
        url: "/mark/mark-info?type=" + type + "&id=" + id,
        type: "get",
        cache: false,
        dataType: "json",
        success: function (data) {
            if (data != null) {
                var json = eval(data);
                var html = "<table border='0' bordercolor='#D3D3D3' width='100%'>";
                html += "<tr><td>&nbsp;&nbsp;选择异常类型</td></tr>"
                html += "<tr><td>"
                for (var i = 0, l = json['data']['code'].length; i < l; i++) {
                    if (json['data']['code'][i]['selected'] == 1) {
                        html += "&nbsp;&nbsp;<input type='button' value='" + json['data']['code'][i]['name'] + "' key='" + json['data']['code'][i]['value'] + "' class='status btn btn-danger' onclick='statusMark(" + json['data']['code'][i]['value'] + ")' id=" + json['data']['code'][i]['value'] + " >"
                    }
                    else {
                        html += "&nbsp;&nbsp;<input type='button' value='" + json['data']['code'][i]['name'] + "' key='" + json['data']['code'][i]['value'] + "' class='status btn btn-info' onclick='statusMark(" + json['data']['code'][i]['value'] + ")' id=" + json['data']['code'][i]['value'] + " >"
                    }
                }
                html += "</td></tr>"
                html += "<tr><td>&nbsp;&nbsp;添加异常备注</td></tr>"
                html += "<tr><td>&nbsp;&nbsp;<textarea id='remark4Mark' rows='5' cols='66'>" + json['data']['remark'] + "</textarea></td></tr>"
                html += "<tr><td align='center'>&nbsp;&nbsp;<input type='button' value='保存'  onclick='save(" + type + "," + id + ")' class='btn btn-info'/></td></tr>"
                html += "</table>"
            }
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['560px', '280px'], //宽高
                content: html
            });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('get issue');
        }
    });
}

function statusMark(id) {
    if ($("#" + id).attr('class') == 'status btn btn-danger') {
        $("#" + id).attr('class', 'status btn btn-info');
    }
    else {
        $("#" + id).attr('class', 'status btn btn-danger');
    }
}

function save(type, id) {
    var strStatus = "";
    $(".status").each(function (index, domEle) {
        if ($(domEle).attr("class") == 'status btn btn-danger') {
            strStatus = strStatus + $(domEle).attr("key") + ",";
        }
    });
    if (strStatus == '') {
        alert('未选择状态')
        return;
    }
    var remark = $("#remark4Mark").val();
    $.ajax({
        url: "/mark/add?type=" + type + "&id=" + id + "&status=" + strStatus + "&remark=" + remark,
        type: "get",
        cache: false,
        dataType: "json",
        success: function (data) {
            var json = eval(data);
            layer.closeAll();
            location.reload();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('get issue');
        }
    });
}

function handle(id) {
    $.ajax({
        url: "/mark/handle?id=" + id,
        type: "get",
        cache: false,
        dataType: "json",
        success: function (data) {
            alert('处理成功')
            location.reload();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('get issue');
        }
    });
}
//=========异常管理 end=============

//=========发送优惠券 begin===========
function coupon(type, id, buyerName, buyerId, platform,shop) {
    $.ajax({
        url: "/mark/coupon-list2?pageNo=1&pageSize=50&p=" + platform+"&shop="+shop,
        type: "get",
        cache: false,
        dataType: "text",
        success: function (data) {
            if (data != null) {
                //var json = eval(data);
                var html = "<table class='table' border='0' bordercolor='#D3D3D3'>";
                html += "<tr><td>&nbsp;&nbsp;选择金额:</td><td>&nbsp;&nbsp;" + data + "</td></tr>";
                html += "<tr><td>&nbsp;&nbsp;发送对象:</td><td>&nbsp;&nbsp;" + buyerName + "</td></tr>";
                html += '<tr><td>&nbsp;&nbsp;扣款帐户:</td><td>&nbsp;&nbsp;<input type="radio" checked="checked" name="compensationType" value="0" /> 美丽优选 <input type="radio" name="compensationType" value="1" />供货商</td></tr>';
                html += "<tr><td>&nbsp;&nbsp;补偿原因:</td><td>&nbsp;&nbsp;<select id='compensationReason'>";
                html += "<option value='质量问题补偿'>质量问题补偿</option>";
                html += "<option value='物流问题补偿'>物流问题补偿</option>";
                html += "<option value='发货延迟补偿'>发货延迟补偿</option>";
                html += "<option value='客服服务补偿'>客服服务补偿</option>";
                html += "<option value='用户体验补偿'>用户体验补偿</option>";
                html += "<option value='退换货补偿'>退换货补偿</option>";
                html += "<option value='错漏发补偿'>错漏发补偿</option>";
                html += "<option value='配件缺失补偿'>配件缺失补偿</option>";
                html += "<option value='描述不符补偿'>描述不符补偿</option>";
                html += "<option value='假货补偿'>假货补偿</option>";
                html += "<option value='三无不合格补偿'>三无不合格补偿</option>";
                html += "</option></select></td></tr>";
                html += "<tr><td>&nbsp;&nbsp;备注:</td><td>&nbsp;&nbsp;<textarea id='couponRemark' rows='4' cols='50'></textarea> </td></tr>";
                html += '<tr><td></td><td><input type="button" value="发送优惠券" onclick="couponSend(' + type + "," + id + "," + buyerId + ",'" + platform + "'" + ')"' + " class='btn btn-info'></td></tr>"
                html += "</table>"
            }
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['410px', '370px'], //宽高
                content: html
            });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('get issue');
        }
    });
}

/*
 *
 * type  1 订单 2退款
 * id  refundId or subOrderId
 * userId 买家id
 * campaignId 优惠券活动id
 * compensationType 补偿类型
 * compensationReason 补偿原因
 * */
function couponSend(type, id, userId, platform) {
    var compensationReason = $('#compensationReason').val();
    var campaignId = $('#couponList').val();
    var price = $("#couponList").find("option:selected").attr("price");
    var sendStartTime = $("#couponList").find("option:selected").attr("sendStartTime");
    var sendEndTime = $("#couponList").find("option:selected").attr("sendEndTime");
    var remark = $('#couponRemark').val();
    var compensationType = $("input[name='compensationType']:checked").val();
    $.ajax({
        url: "/mark/coupon-send",
        type: "post",
        data: {
            "type": type,
            "id": id,
            "campaignId": campaignId,
            "userId": userId,
            "compensationType": compensationType,
            "compensationReason": compensationReason,
            "remark": remark,
            "price": price,
            "sendStartTime": sendStartTime,
            "sendEndTime": sendEndTime,
            "platform": platform
        },
        dataType: "json",
        success: function (data) {
            var json = eval(data);
            if (json['coupon']['data']['success']) {
                alert("成功");
                location.reload();
            }
            else {
                alert("异常" + json['coupon']['data']['message']);
            }
            layer.closeAll();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('get issue');
        }
    });

}
//=========发送优惠券 end=============
//=========发送短信 begin=============
function sms(type, id, name, mobile, platform) {
    var html = "<table border='0' bordercolor='#D3D3D3' width='100%'>";
    html += "<tr><td>&nbsp;&nbsp;发送对象</td><td>" + name + "</td></tr>"
    html += "<tr><td>&nbsp;&nbsp;手机号</td><td>" + mobile + "</td></tr>"
    html += "<tr><td>&nbsp;&nbsp;发送内容</td><td>&nbsp;&nbsp;<textarea id='content4SMS' rows='5' cols='40'></textarea></td></tr>"
    html += "<tr><td>&nbsp;&nbsp;发送原因</td><td>&nbsp;&nbsp;<textarea id='remark4SMS' rows='5' cols='40'></textarea></td></tr>"
    html += '<tr><td colspan="2" align="center">&nbsp;&nbsp;<input type="button" value="发送短信"  onclick="sendSMS(' + type + "," + id + "," + mobile + ",'" + platform + "'" + ')"' + ' class="btn btn-info"/></td></tr>'
    html += "</table>"
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['420px', '350px'], //宽高
        content: html
    });
}

function sendSMS(type, id, mobile, platform) {
    var content = $("#content4SMS").val();
    var remark = $("#remark4SMS").val();
    if (content.length > 60) alert('文字不能超过60')
    $.ajax({
        url: "/mark/sms",
        type: "post",
        data: {
            "type": type,
            "id": id,
            "mobile": mobile,
            "content": content,
            "remark": remark,
            "platform": platform
        },
        dataType: "json",
        success: function (data) {
            var json = eval(data);
            alert("发送成功");
            layer.closeAll();
            location.reload();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('get issue');
        }
    });
}
//=========发送短信 end=============



