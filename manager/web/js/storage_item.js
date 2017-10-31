// 新商品上架操作 170302
$(".item_on_shelf").click(function() {
    var ids = [];
    var platformIds = [];
    var singleItemId = $(this).attr('itemId');
    if(singleItemId != undefined){
        ids.push(singleItemId);
    }else{
        $(".card-body").find("input[name='item_ids']:checked").each(function() {
            var id = $(this).val();
            ids.push(id);
        });
    }
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time:2000, icon: 0});
        return false;
    }
    var content = $(".on-shelf-platform").html();
    layer.open({
        type: 1,
        title: "请选择上架的平台",
        area: ['500px'], //宽高
        content: content,
        shade: [0.3, '#000'],
        btn: ['保存'],
        yes: function (index, layero) {
            $(".platforms-div").find("input[name='platforms']:checked").each(function() {
                platformId = $(this).val();
                platformIds.push(platformId);
            });
            if (platformIds.length == 0) {
                layer.msg('未选要上线的平台', {time:2000, icon: 0});
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


// old保存商品立即上架
$(".item_on_shelf-bak").click(function() {
    var ids = [];
    var singleItemId = $(this).attr('itemId');
    if(singleItemId != undefined){
        ids.push(singleItemId);
    }else{
        $(".card-body").find("input[name='item_ids']:checked").each(function() {
            var id = $(this).val();
            ids.push(id);
        });
    }
    if (ids.length == 0) {
        layer.msg('未选中要处理的商品', {time:2000, icon: 0});
        return false;
    }
    layer.confirm('确定选中的商品立即上架？', {
            btn: ['确定','取消'] //按钮
        },
        function(){
            $.ajax({
                type: 'post',
                async: false,
                url: '/storage-item/on-shelf',
                data: {
                    itemIds: ids
                },
                dataType: 'json',
                beforeSend:function(data) {
                    layer.load(1);
                },
                success: function (data) {
                    layer.closeAll('loading');
                    if (data.errno) {
                        layer.msg(data.msg,{time:0, icon: 5,btn:['确定']});
                    } else {
                        layer.msg(data.msg);
                        location.reload();
                    }
                }
            });
        }, function(){
        });
});