/*
  搭配弹窗组件 by 肃丘
  引入js后初始化：
  new DapeiModal(外层搭配列表容器id,外层列表最大显示数)，如：
  var dm = new DapeiModal("collocation",5);
  此时窗口不可见
  需要显示弹窗时调用：
  dm.show()
 */
(function(win,$){
  function DapeiModal(bodyListWrapId,num){
    this.prdtList = [];
    this.bodyListWrapNode = $('#'+bodyListWrapId);
    this.num = num;
    this.init()
  }

  DapeiModal.prototype={
    init:function () {
      this.mountMainNode();
      this.bindEvents();
      this.getList();
    },
    mountMainNode:function(){
      var str = this.genMainTmpl();
      var $mainNode = $(str);
      $('body').append($mainNode)
    },
    bindEvents:function(){
      var self = this;
      $('#dapeiSearchButton').click(function(){
        var skuid = $('#dpSearchInput').val()
        if(!skuid){
          alert("请填写SKU编码");
          return;
        }
        $.ajax({
          url:"/collocation/purchase-goods",
          data:{
            skuNo:skuid
          },
          dataType:'JSON',
          success:function(res){
            if (res.code == 0) {
              self.addDapeiItemToBody(res.data);
            } else {
              alert(res.msg);
            }
          },
          error:function(){
            alert("获取数据失败，请重试")
          }
        })
      });
      $('#dapeiListSearchBtn').click(function(){
        var title = $('#prdtNameInput').val();
        var code = $('#spuidInput').val();
        self.getList(title,code)
      });
      $('#dpListTable').delegate('.dp-list-add-btn','click',function(e){
        var index = $(e.target).parent().parent().index()-1;
        var item = self.prdtList[index];
        self.addDapeiItemToBody(item);
      });
      $('.dp-close').click(function(){
        self.hide();
      })
    },
    getList:function(title,code){
      var self = this;
      this.showLoading();
      $.ajax({
        url:"/collocation/item-list",
        data:{
          title:title,
          code:code
        },
        dataType:'JSON',
        success:function(res){
          if (res.code == 0) {
            self.prdtList = res.data;
            self.refreshPrdtList();
          } else {
            alert(res.msg);
            self.hideLoading();
          }
        },
        error:function(){
          alert("获取数据失败，请重试")
          self.hideLoading();
        }
      })
    },
    showLoading:function(){
      $('#dpListTable').html(
        '<tr>\
          <th>SPU</th>\
          <th colspan="2">商品名称</th>\
          <th style="white-space: nowrap;">剩余库存</th>\
          <th>操作</th>\
        </tr>\
        <tr>\
          <td colspan="5" style="text-align:center;">加载中...</td>\
        </tr>'
      );
    },
    hideLoading:function(){
      $('#dpListTable').html(
        '<tr>\
          <th>SPU</th>\
          <th colspan="2">商品名称</th>\
          <th style="white-space: nowrap;">剩余库存</th>\
          <th>操作</th>\
        </tr>\
        <tr>\
          <td colspan="5" style="text-align:center;">没有数据</td>\
        </tr>'
      );
    },
    refreshPrdtList:function(){
      var listStr = '<tr>\
          <th>SPU</th>\
          <th colspan="2">商品名称</th>\
          <th style="white-space: nowrap;">剩余库存</th>\
          <th>操作</th>\
        </tr>';
      for (var i = 0; i < this.prdtList.length; i++) {
        var item = this.prdtList[i];
        listStr += '<tr>\
          <td>' + item.itemCode + '</td>\
          <td class="no-r-b">\
            <a class="dp-tb-img-a" '+ (item.url == '#' ? '':'target="_blank"') +' href="' + item.url + '">\
              <img class="dp-tb-img" src="' + item.coverImage + '" />\
            </a>\
          </td>\
          <td><p style="text-align: left;"><a '+ (item.url == '#' ? '':'target="_blank"') +' href="' + item.url + '" style="color: #666;">' + item.title + '</a></p></td>\
          <td><p>' + item.stock + '</p></td>\
          <td><p style="white-space: nowrap;cursor: pointer;color: #e30047" class="dp-list-add-btn">加入搭配</p></td>\
        </tr>';
      }
      $('#dpListTable').html(listStr);
    },
    addDapeiItemToBody:function(item){
      var list = this.bodyListWrapNode.children();
      for (var i = 0; i < list.length; i++) {
        if (item.itemCode == list.eq(i).attr('itemCode')) {
          alert("已添加相同SPU商品，请勿重复添加");
          return;
        }
      }
      var itemStr = '<div class="card-div col-lg-3 col-sm-6 col-xs-12 item-block" itemid="' + item.itemId + '" itemcode="' + item.itemCode + '" skucode="' + item.skuCode + '" style="position: relative; padding: 2px;">\
                            <div class="card-item card-imgs" style="border:1px solid #BBB;background-image: url(' + item.coverImage + ')">\
                            </div>\
                            <div class="card-body" style="height: 60px;padding: 2px !important;padding-top: 2px;">\
                                <span>SPU：' + item.itemCode + '</span>\
                                <a class="red delC" style="float: right" >[删除]</a>\
                                <p>'+ (item.isShelf == 0 ? '￥' + item.salePrice : item.shelfStatus) + '</p>\
                            </div>\
                        </div>';
      
      var addlinode = this.bodyListWrapNode.children().last();console.log(addlinode)
      addlinode.remove();

      this.bodyListWrapNode.append($(itemStr))
      if (this.bodyListWrapNode.children().length < this.num) {
        this.bodyListWrapNode.append(addlinode)
      }
      this.hide();
    },
    show:function(){
      $('#dapeiModalContainer').css('display','block');
      // this.getList();
    },
    hide:function(){
      $('#dapeiModalContainer').css('display','none');
    },
    genMainTmpl:function(){
      return '<div id="dapeiModalContainer">\
        <style type="text/css">\
          #dapeiModalContainer{\
            position: fixed;\
            z-index: 1000;\
            left: 0;\
            top: 0;\
            bottom: 0;\
            right: 0;\
            background: rgba(0,0,0,0.5);\
            overflow: auto;\
            padding: 50px 0;\
            display: none;\
          }\
          .dp-banner-wrap{\
            overflow: hidden;\
            background: #ddd;\
            margin: -30px -20px 0;\
          }\
          .dp-banner{\
            height: 50px;\
            text-align: left;\
            font-size: 20px;\
            line-height: 50px;\
            padding-left: 20px;\
            float: left;\
            margin: 0;\
          }\
          .dp-close-x {\
            background: #ddd;\
            color: #e30047;\
            cursor: pointer;\
            line-height: 20px;\
            text-align: center;\
            margin: 0;\
            width: 50px;\
            font-size: 26px;\
            padding-top: 15px;\
            float: right;\
          }\
          .dp-close-x:before {\
              content: "×";\
          }\
          .dp-content{\
            background: white;\
            margin: 0 auto;\
            padding: 30px 20px 10px;\
            width: 600px;\
            font-size:14px;\
            border-top-left-radius:4px;\
            border-top-right-radius:4px;\
            overflow: hidden;\
          }\
          .dp-search-wrap{\
            padding: 5px;\
          }\
          #dpSearchInput{\
            width: 300px;\
            height: 20px;\
          }\
          .dp-search-btn{\
            background:#e30047;\
            color: white;\
            padding: 4px;\
            margin-left: 10px;\
            border-radius: 4px;\
            cursor: pointer;\
          }\
          .dp-list-wrap{\
            padding: 5px;\
            border-top: 1px dotted #ddd;\
            margin-top: 5px;\
          }\
          .dp-table-wrap{\
            max-height: 500px;\
            overflow: auto;\
            margin-top: 10px;\
          }\
          .dp-table{\
            width: 100%;\
            border-top: 1px solid #eee;\
            border-left: 1px solid #eee;\
          }\
          .dp-table th{\
            background: #eee;\
          }\
          .dp-table th,.dp-table td{\
            padding: 4px;\
            border-bottom: 1px solid #eee;\
            border-right: 1px solid #eee;\
            text-align: center;\
            vertical-align: middle;\
          }\
          .dp-table .no-r-b{\
            border-right: none;\
          }\
          .dp-tb-img-a{\
            width: 60px;\
            height: 60px;\
            display: block;\
            overflow: hidden;\
          }\
          .dp-tb-img{\
            width: 60px;\
          }\
          .dp-detail-con{\
            padding-left: 30px;\
            background: #eee;\
            padding: 20px;\
          }\
          .dp-btn{\
            background:#e30047;\
            color: white;\
            width: 60px;\
            height: 30px;\
            text-align: center;\
            line-height: 30px;\
            margin: 20px auto 10px;\
            border-radius: 4px;\
            cursor: pointer;\
          }\
        </style>\
        \
        <div class="dp-content">\
          <div class="dp-banner-wrap">\
            <p class="dp-banner">关联搭配商品</p> \
            <span class="dp-close-x dp-close"></span>\
          </div>\
          <div class="dp-search-wrap">\
            <label>直接录入SKU编码：</label>\
            <input id="dpSearchInput"></input>\
            <span class="dp-search-btn" id="dapeiSearchButton" >加入搭配</span>\
          </div>\
          <div class="dp-list-wrap">\
            <div>选择在架商品</div>\
            <div style="margin-top: 10px;">\
              <label>SPUid</label>\
              <input id="spuidInput" class=""></input>\
              <label style="margin-left: 5px;">商品名称</label>\
              <input id="prdtNameInput" class=""></input>\
              <span class="dp-search-btn" id="dapeiListSearchBtn" >搜索</span>\
            </div>\
            <div class="dp-table-wrap">\
              <table class="dp-table" id="dpListTable">\
                <tr>\
                  <th>SPU</th>\
                  <th colspan="2">商品名称</th>\
                  <th style="white-space: nowrap;">剩余库存</th>\
                  <th>操作</th>\
                </tr>\
              </table>\
            </div>\
          </div>\
          <div class="dp-btn dp-close" id="dapeiCloseBtn">关闭</div>\
        </div>\
      </div>';
    }
  }
  win.DapeiModal = DapeiModal;
})(window,$)