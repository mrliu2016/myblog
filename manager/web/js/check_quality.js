// Aajax加载中
$(document).ajaxStart(function () {
    $(".loading").show();
});
$(document).ajaxSuccess(function () {
    $(".loading").hide();
});
$("#searchBtn").click(function() {
    $("#searchForm").submit();
});

// 议价修改接口
$(".charge_price").click(function () {
    $("#level_remark").html('');
    $("[name='selectReason']").html('');
    var id = $(this).attr("data-id");
    var type = $(this).attr("data-type");
    var title = "商品名称：" + $(this).attr("data-name");
    var dbPrice = "商品报价：￥" + $(this).attr("data-price");
    var html = "<p>" + title + "</p>";
    html += "<p>" + dbPrice + "</p>";
    html += "<p>预期报价：<input name='charge_price' id='charge_price' value=''></p>";
    $("[name='selectReason']").html(html);
    var content = $(".level_modal").html();
    $("#level_remark").html('');
    $("[name='selectReason']").html('');

    layer.open({
        type: 1,
        title: "商品议价",
        area: ['400px', '250px'], //宽高
        content: content,
        shade: [0.3, '#000'],
        btn: ['设置议价'],
        yes: function (index, layero) {
            var charge_price = $("input[name='charge_price']").val();
            if (charge_price == undefined || charge_price == '') {
                layer.msg('请输入预期报价', {time: 2000, icon: 0});
                return false;
            }
            $.ajax({
                type: 'post',
                async: false,
                url: '/quality-check/charges-price',
                data: {
                    id: id,
                    type: type,
                    charge_price: charge_price
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (data.code) {
                        layer.msg("成功！", {time: 5000, icon: 1});
                        location.reload();
                    } else {
                        layer.msg(data.msg, {time: 5000, icon: 5});
                    }
                }
            });
            layer.close(index);
        }
    });
});


// 审核不通过
function addLevelMark(ids, level, reason, status, orderId)
{
    var html = "";
    var idList = new Array();
    idList[1] = "商品质量问题";
    idList[2] = "商品描述与实物不符";
    idList[3] = "同款";
    idList[4] = "商家未寄样";
    idList[5] = "重复提报";
    idList[6] = "议价协商不一致";
    idList[7] = "性价比不足（贵）";
    idList[8] = "款式与平台不符（丑）";
    idList[9] = "舒适度不佳";
    idList[10] = "鞋型有问题";
    idList[11] = "其他原因";
    for(var i=1;i<idList.length;i++){
        html += '&nbsp;&nbsp;&nbsp;<input type="radio" name="reason" ';
        if(level == i){
            html += ' checked ';
        }
        html +=  'value="'+idList[i]+'" title="'+idList[i]+'">'+idList[i]+'<br/>';
    }
    html2 = '<textarea class="form-control" name="note" cols="40" rows="8"></textarea>';
    $("[name='selectReason']").html(html2);
    $("#level_remark").html(html);
    var content = $(".level_modal").html();
    $("#level_remark").html('');
    $("[name='selectReason']").html('');
    layer.open({
        type: 1,
        title:"质检不通过理由",
        area: ['500px','400px'], //宽高
        content: content,
        shade: [0.3, '#000'],
        btn:['保存'],
        yes:function(index, layero) {
            var note = $("textarea[name='note']").val();
            var reason = $('input[name="reason"]:checked').val();
            if (reason == undefined) {
                layer.msg('请拒绝理由', {time:2000, icon: 0});
                return false;
            }
            $.ajax({
                type: 'post',
                async: false,
                url: '/quality-check/quality-check',
                data: {
                    ids: ids,
                    status: status,
                    reason:reason,
                    note:note,
                    orderId:orderId
                },
                dataType: 'json',
                beforeSend:function() {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (data.code) {
                        layer.msg("成功！", {time:5000, icon: 1});
                        location.reload();
                    } else {
                        layer.msg(data.msg, {time:5000, icon: 5});
                    }
                }
            });
            layer.close(index);
        }
    });
}

//单个质检通过
$(".check_pass").click(function() {
    var id = $(this).attr("data-id");
    var ids = [];
    ids.push(id);
    deal_quality_check(ids, $(this).parents(".card").parents(".col-lg-3"), 'pass');
});
//质检审核页面单个质检不通过
$(".no_pass").click(function() {
    var id = $(this).attr("data-id");
    var ids = [];
    ids.push(id);
    addLevelMark(ids, 0, 0, 6, 0);
});
//批量质检通过
$(".batch_check_pass").click(function() {
    var ids = [];
    $(".card-body").find("input[name='ids[]']:checked").each(function() {
        var raw_id = $(this).val();
        ids.push(raw_id);
    });
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time:2000, icon: 0});
        return false;
    }
    deal_quality_check(ids, '', 'pass');

});
//质检审核页面批量质检不通过
$(".batch_no_pass").click(function() {
    var ids = [];
    $(".card-body").find("input[name='ids[]']:checked").each(function() {
        var raw_id = $(this).val();
        ids.push(raw_id);
    });
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time:2000, icon: 0});
        return false;
    }
    addLevelMark(ids, 0, 0, 6, 0);
});

// 质检处理函数
function deal_quality_check(ids, op, flag) {
    var title = flag == "pass" ? "确定商品质检通过？" : "确定商品质检不合格？";
    var status = flag == "pass" ? 5 : 6;
    layer.confirm(title, {
            btn: ['确定','取消'] //按钮
        },
        function(){
            $.ajax({
                type: 'post',
                async: false,
                url: '/quality-check/quality-check',
                data: {
                    ids: ids, status:status
                },
                dataType: 'json',
                beforeSend:function(data) {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    //响应的内容处理
                    if (data.code) {
                        layer.msg(data.msg, {time:2000, icon: 1});
                    }
                    //响应的内容处理
                    $(".card-body").find("input[type='checkbox']:checked").prop("checked", false);
                    location.reload();
                }
            });
        }, function(){
        });
}

//质检订单页面单个不通过
$(".order_item_reject").click(function () {
    var id = $(this).attr("data-id");
    var orderId = $(this).attr("data-order-id");
    if (id == null || id == '' || orderId == null || orderId == '') {
        alert("订单id或商品id异常，请联系技术！");
        return false;
    }
    var ids = [];
    ids.push(id);
    addLevelMark(ids, 0, 0, 11, orderId);
});


//质检选品页面单个不通过
$(".reject").click(function() {
    var id = $(this).attr("data-id");
    var ids = [];
    ids.push(id);
    addLevelMark(ids, 0, 0, 10, 0);
});

//质检选品页面批量不通过
$(".batch_reject").click(function() {
    var ids = [];
    $(".card-body").find("input[name='ids[]']:checked").each(function() {
        var raw_id = $(this).val();
        ids.push(raw_id);
    });
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time:2000, icon: 0});
        return false;
    }
    addLevelMark(ids, 0, 0, 10, 0);
});

//单个需要寄样
$(".need_send").click(function() {
    var id = $(this).attr("data-id");
    var ids = [];
    ids.push(id);
    common_to_raw(ids, $(this).parents(".card").parents(".col-lg-3"), 'need');
});
//单个无需寄样
$(".no_need").click(function() {
    var id = $(this).attr("data-id");
    var ids = [];
    ids.push(id);
    common_to_raw(ids, $(this).parents(".card").parents(".col-lg-3"), 'no');
});

//议价页面单个需要寄样
$(".need_send_charges").click(function() {
    var id = $(this).attr("data-id");
    var ids = [];
    ids.push(id);
    common_to_raw(ids, '', 'need');
});
//议价页面单个无需寄样
$(".no_need_charges").click(function() {
    var id = $(this).attr("data-id");
    var ids = [];
    ids.push(id);
    common_to_raw(ids, '', 'no');
});

//批量需要寄样
$(".batch_need_send").click(function() {
    var ids = [];
    $(".card-body").find("input[name='ids[]']:checked").each(function() {
        var raw_id = $(this).val();
        ids.push(raw_id);
    });
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time:2000, icon: 0});
        return false;
    }
    common_to_raw(ids, '', 'need');

});
//批量无需寄样
$(".batch_no_need").click(function() {
    var ids = [];
    $(".card-body").find("input[name='ids[]']:checked").each(function() {
        var raw_id = $(this).val();
        ids.push(raw_id);
    });
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time:2000, icon: 0});
        return false;
    }
    common_to_raw(ids, '', 'no');
});

// 是否需要寄养提交函数
function common_to_raw(ids, op, flag) {
    var title = flag == "need" ? "确定商品需要寄样？" : "确定无需商品寄样？";
    var url = flag == "need" ? "/quality-check/need-sample" : "/quality-check/quality-check";
    layer.confirm(title, {
            btn: ['确定','取消'] //按钮
        },
        function(){
            $.ajax({
                type: 'post',
                async: false,
                url: url,
                data: {
                    ids: ids
                },
                dataType: 'json',
                beforeSend:function(data) {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    //响应的内容处理
                    if (data.code) {
                        layer.msg(data.msg, {time:5000, icon: 1});
                    }
                    //响应的内容处理
                    $(".card-body").find("input[type='checkbox']:checked").prop("checked", false);
                    if (op == "") {
                        location.reload();
                    } else {
                        op.remove();
                    }
                }
            });
        }, function(){
        });
}

//修改sku
$(".update_quality_check").click(function () {
    var id = $(this).attr("data-id");
    var title = "商品名称：" + $(this).attr("data-name");
    var html = '';
    $.ajax({
        type: 'post',
        async: false,
        url: '/quality-check/get-sku-list',
        data: {
            id: id
        },
        dataType: 'json',
        beforeSend: function () {
            layer.load(1);
        },
        success: function (data) {
            layer.closeAll('loading');
            if (data.code) {
                layer.msg("成功！", {time: 5000, icon: 1});
                var json = eval(data.skuList);
                html = "<tr><th>选择</th><th>规格</th><th>尺码</th><th>供货价</th></tr>";
                for (var i = 0; i < json.length; i++) {
                    html += "<tr><td><input type='radio' name='sku_goods_info_id' value='" + json[i].id + "'";
                    if (json[i].isQualityCheck == 1) {
                        html += " checked ";
                    }
                    html += "/></td>";
                    html += "<td>" + json[i].color + "</td>";
                    html += "<td>" + json[i].size + "</td>";
                    html += "<td>￥" + json[i].bdPrice / 100 + "</td>";
                    html += "</tr>";
                }
                $(".layui-form-label").html(title);
                $("#sku-table").html(html);
                show_pop(id);
            } else {
                layer.msg(data.msg, {time: 5000, icon: 5});
            }
        }
    });

});
// 修改规格弹框
function show_pop(itemId) {
    var content = $(".sku_modal").html();
    $(".layui-form-label").html('');
    $("#sku-table").html('');
    layer.open({
        type: 1,
        title: "修改规格",
        area: ['500px', '400px'], //宽高
        content: content,
        shade: [0.3, '#000'],
        btn: ['确认'],
        yes: function (index, layero) {
            var id = $('input[name="sku_goods_info_id"]:checked').val();
            if (id == undefined) {
                layer.msg('请选择sku', {time: 2000, icon: 0});
                return false;
            }
            if (itemId == undefined) {
                layer.msg('商品id不存在，数据异常！', {time: 2000, icon: 0});
                return false;
            }
            $.ajax({
                type: 'post',
                async: false,
                url: '/quality-check/update-check-sku',
                data: {
                    id: id,
                    itemId:itemId
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (data.code) {
                        layer.msg("成功！", {time: 5000, icon: 1});
                        var sku_html = "<span style='margin-right:10px;'>已选颜色：" + data.info.color + "</span>尺码：" + data.info.size + "<br />";
                        $("#item-sku-desc-" + data.info.bdId).html(sku_html);
                        $("#charges_price_id_"+itemId).attr("data-price",  data.info.bdPrice);
                    } else {
                        layer.msg(data.msg, {time: 5000, icon: 5});
                    }
                }
            });
            layer.close(index);
        }
    });
}

//点击小图显示大图
$(".stockImg").fancybox({
    wrapCSS    : 'fancybox-custom',
    closeClick : true,
    openEffect : 'none',
    helpers : {
        title : {
            type : 'inside'
        },
        overlay : {
            css : {
                'background' : 'rgba(0,0,0,0.3)'
            }
        }
    }
});