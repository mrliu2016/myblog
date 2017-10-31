// 修改库存比例操作 170307
$(".item_rate").click(function () {
    var ids = [];
    var platformIds = [];
    var singleItemId = $(this).attr('itemId');
    if (singleItemId != undefined) {
        ids.push(singleItemId);
    } else {
        $(".card-body").find("input[name='item_ids']:checked").each(function () {
            var id = $(this).val();
            ids.push(id);
        });
    }
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time: 2000, icon: 0});
        return false;
    }
    $("[name='pop-title']").html('请选择库存比例，美丽说:蘑菇街:淘宝');
    var content = $(".item-pop").html();
    $(".item-pop").html('');
    layer.open({
        type: 1,
        title: "修改库存比例",
        area: ['300px'], //宽高
        content: content,
        shade: [0.3, '#000'],
        btn: ['保存'],
        yes: function (index, layero) {
            var rate = $("[name='pop-div']").find('option:selected').val();
            $.ajax({
                type: 'post',
                async: false,
                url: '/shelf-item/update-rate',
                data: {
                    itemIds: ids,
                    rate: rate
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (!data.errno) {
                        layer.msg("成功！", {time: 5000, icon: 1});
                        location.reload();
                    } else {
                        layer.msg(data.msg, {time: 5000, icon: 5});
                    }
                    // 还原页面的选项
                    $(".item-pop").html(content);
                }
            });
            layer.close(index);
        }
    });

});

// 新商品上架操作 170302
$(".item_on_shelf").click(function () {
    var ids = [];
    var platformIds = [];
    var singleItemId = $(this).attr('itemId');
    //var mlsPlatform = $(this).attr('mlsItemShelf');
    //var mgjItemShelf = $(this).attr('mgjItemShelf');
    //var tbPlatform = $(this).attr('tbItemShelf');
    //var lazadaItemShelf = $(this).attr('lazadaItemShelf');
    //var superItemShelf = $(this).attr('superItemShelf');
    //$("#mls_platform").attr('checked', false);
    //$("#mgj_platform").attr('checked', false);
    //$("#tb_platform").attr('checked', false);
    //$("#lazada_platform").attr('checked', false);
    //$("#super_platform").attr('checked', false);
    //if (mlsPlatform == 1) {
    //    $("#1_platform").attr('checked', true);
    //}
    //if (mgjItemShelf == 1) {
    //    $("#mgj_platform").attr('checked', true);
    //}
    //if (tbPlatform == 1) {
    //    $("#tb_platform").attr('checked', true);
    //}
    //if (lazadaItemShelf == 1) {
    //    $("#lazada_platform").attr('checked', true);
    //}
    //if (superItemShelf == 1) {
    //    $("#super_platform").attr('checked', true);
    //}

    $("input[name=platforms]").attr('checked', false);

    var onshelfPlatforms = $(this).attr('onshelfPlatforms');
    json = eval(onshelfPlatforms)
    for(var i=0; i<json.length; i++)
    {
        var platform = json[i];
        $("#"+platform+"_platform").attr('checked', true);
    }

    if (singleItemId != undefined) {
        ids.push(singleItemId);
    } else {
        $(".card-body").find("input[name='item_ids']:checked").each(function () {
            var id = $(this).val();
            ids.push(id);
        });
    }
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time: 2000, icon: 0});
        return false;
    }
    var content = $(".on-shelf-platform").html();
    layer.open({
        type: 1,
        title: "请选择上架的平台",
        area: ['550px'], //宽高
        content: content,
        shade: [0.3, '#000'],
        btn: ['保存'],
        yes: function (index, layero) {
            $(".platforms-div").find("input[name='platforms']:checked").each(function () {
                platformId = $(this).val();
                platformIds.push(platformId);
            });
            if (platformIds.length == 0) {
                layer.msg('未选要上线的平台', {time: 2000, icon: 0});
                return false;
            }
            $.ajax({
                type: 'post',
                async: false,
                url: '/storage-item/platform-on-shelf',
                data: {
                    itemIds: ids,
                    platforms: platformIds
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (!data.errno) {
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

// 新商品下架操作 170306
$(".item_off_shelf").click(function () {
    var ids = [];
    var platformIds = [];
    var singleItemId = $(this).attr('itemId');
    if (singleItemId != undefined) {
        ids.push(singleItemId);
    } else {
        $(".card-body").find("input[name='item_ids']:checked").each(function () {
            var id = $(this).val();
            ids.push(id);
        });
    }
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time: 2000, icon: 0});
        return false;
    }
    $("input[name=platforms]").attr('checked', false);
    var content = $(".on-shelf-platform").html();
    layer.open({
        type: 1,
        title: "请选择下架的平台",
        area: ['550px'], //宽高
        content: content,
        shade: [0.3, '#000'],
        btn: ['保存'],
        yes: function (index, layero) {
            $(".platforms-div").find("input[name='platforms']:checked").each(function () {
                platformId = $(this).val();
                platformIds.push(platformId);
            });
            if (platformIds.length == 0) {
                layer.msg('未选要下架的平台', {time: 2000, icon: 0});
                return false;
            }
            $.ajax({
                type: 'post',
                async: false,
                url: '/storage-item/platform-off-shelf',
                data: {
                    itemIds: ids,
                    platforms: platformIds
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (!data.errno) {
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


// old保存商品立即下架
$(".item_off_shelf-bak").click(function () {
    var ids = [];
    var singleItemId = $(this).attr('itemId');
    if (singleItemId != undefined) {
        ids.push(singleItemId);
    } else {
        $(".card-body").find("input[name='item_ids']:checked").each(function () {
            var id = $(this).val();
            ids.push(id);
        });
    }
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time: 2000, icon: 0});
        return false;
    }
    layer.confirm('确定选中的商品立即下架？', {
            btn: ['确定', '取消'] //按钮
        },
        function () {
            $.ajax({
                type: 'post',
                async: false,
                url: '/storage-item/off-shelf',
                data: {
                    itemIds: ids
                },
                dataType: 'json',
                beforeSend: function (data) {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (data.errno) {
                        layer.msg(data.msg, {time: 0, icon: 5, btn: ['确定']});
                    } else {
                        layer.msg(data.msg);
                        location.reload();
                    }
                }
            });
        }, function () {
        });
});

// 展示全部sku
function showAllSku(itemId, platform, min, max) {
    if (undefined == itemId || undefined == platform) {
        layer.msg('商品id不能为空', {time: 2000, icon: 0});
        return false;
    }
    var temp = '';
    var html = '';
    $.ajax({
        type: 'post',
        async: false,
        url: '/shelf-item/show-all-sku',
        data: {
            itemId: itemId,
            platform: platform,
            min: min,
            max: max
        },
        dataType: 'json',
        beforeSend: function () {
            layer.load(1);
        },
        success: function (data) {
            layer.closeAll('loading');
            if (data.code) {
                html += data.msg;
            }
            $("div[name='show_content']").html(html);
            temp = $(".history_note_content").html();
            layer.open({
                type: 1,
                title: "sku信息",
                area: ['400px', '300px'], //宽高
                maxmin: true,
                content: temp
            });
        }
    });
}

//点击小图显示大图
$(".stockImg").fancybox({
    wrapCSS: 'fancybox-custom',
    closeClick: true,
    openEffect: 'none',
    helpers: {
        title: {
            type: 'inside'
        },
        overlay: {
            css: {
                'background': 'rgba(0,0,0,0.3)'
            }
        }
    }
});

