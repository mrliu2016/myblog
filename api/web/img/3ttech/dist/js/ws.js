var reconnFlag = false;
var timerID = 0;
var modefontcolor = [];
//modefontcolor数组中一维代表登录模式
//1电脑直播手机观看
//2手机直播手机观看
//3电脑观看
//数组二维代表字体颜色(0聊天1礼物)
modefontcolor[1] = ['#ffffff', '#ff9900', 'yellow'];
modefontcolor[2] = ['#717171', '#ff9900', 'red'];
modefontcolor[3] = ['#717171', '#ff9900', 'red'];
//socket数据
var SocketIO = {

    _firstLogin: false,
    _initConnect: function () {
        try {
            socket = new WebSocket(webSocket);
        } catch (e) {
            console.log('连接异常 ： ' + e);
            return;
        }
        SocketIO._wbSocket = socket;

        socket.onclose = function () {
            if (!reconnFlag) {
                timerID = setInterval(SocketIO._initConnect, 2000);
                reconnFlag = true;
            }
        }
        socket.onopen = function () {
            if (reconnFlag) {
                window.clearInterval(window.timerID);
                timerID = 0;
                reconnFlag = false;
            }
            if (!SocketIO._firstLogin) {
                var message = {
                    data: {
                        message: "",
                        balance: userInfo.balance,
                        userId: userInfo.userId,
                        avatar: userInfo.avatar,
                        level: userInfo.level,
                        nickName: userInfo.nickName,
                        role: userInfo.role,
                        roomId: masterUserInfo.roomId,
                        masterNickName: masterUserInfo.nickName,
                        masterAvatar: masterUserInfo.avatar,
                        masterLevel: masterUserInfo.masterLevel,
                        masterUserId: masterUserInfo.userId,
                    },
                    messageType: MESSAGE_TYPE_JOIN_REQ
                };
                SocketIO._wbSocket.send(JSON.stringify(message));
                SocketIO._firstLogin = true;
            }
        };
        socket.onmessage = SocketIO._msgReceive;
        /**
         * 心跳
         */
        setInterval(function () {
            var message = {
                data: {
                    isMaster: userInfo.role,
                    roomId: masterUserInfo.roomId,
                    streamId: streamId,
                    userId: userInfo.userId
                },
                messageType: MESSAGE_TYPE_HEARTBEAT_REQ
            };
            SocketIO._wbSocket.send(JSON.stringify(message));
        }, 10000);
    },

    _chatMessage: function (msg) {
        var data = {};
        data._method_ = "SendPubMsg";
        data._type_ = "flyMsg";
        data.fly = fly;
        data.levelid = User.level;
        data.content = msg;
        data.client_name = User.nickname;
        SocketIO._sendMsg(JSON.stringify(data));
        console.log(data.levelid);
    },

    _sendMsg: function (msgBuf) {
        if (msgBuf != null && msgBuf != 'undefined') {
            SocketIO._wbSocket.send(msgBuf);
        } else {
            console.log('发送消息为空!');
        }
    },

    /**
     * 响应 webSocket 消息
     *
     * @param response
     * @private
     */
    _msgReceive: function (response) {
        var message = JSON.parse(response.data);
        console.log(message);
        switch (message.messageType) {
            case MESSAGE_TYPE_JOIN_RES:
                break;
            case MESSAGE_TYPE_JOIN_NOTIFY_RES:
                break;
            case MESSAGE_TYPE_QUIT_RES:
                break;
            case MESSAGE_TYPE_KICK_RES:
                break;
            case MESSAGE_TYPE_BLACKLIST_RES:
                break;
            case MESSAGE_TYPE_GAG_RES:
                break;
            case MESSAGE_TYPE_BARRAGE_RES:
                break;
            case MESSAGE_TYPE_GIFT_RES:
                break;
            case MESSAGE_TYPE_GIFT_NOTIFY_RES:
                break;
            case MESSAGE_TYPE_HEARTBEAT_RES:
                break;
        }

    }
}

//消息逻辑处理
var _chat = {
    remove_msg: function () {
        // while ($("#chat_hall>p").length >= 10) {
        //         $("#chat_hall").children().first().remove();
        //     }
        if ($("#chat_hall>p").length > 100) {
            $("#chat_hall>p").slice(0, 50).remove()
        }
    },
    gift_msg: function (count, html) {
        if (count != 0) {

            $(".msg-box .msg-con").append(html);
            _chat.remove_msg();
            setTimeout("_chat.gift_msg(" + (count - 1) + ",'" + html + "')", 500);
            var scrojh = $("#upchat_hall")[0].scrollHeight;
            $("#chat_hall").scrollTop($("#upchat_hall").scrollTop(scrojh));
        }
    },

    _func_login: function (data) {
        _chat.show_message(data.client_name, "来到了直播间", data.levelid, 1, 0, data.user_id);
    },

    _func_sysmsg: function (data) {
        _chat.show_message("系统提示", data.content, "", "", 2, '');
    },

    _func_sendGift: function (data) {
        msgHtml = '<p><label class ="user_nickname" user_id=' + data.from_user_id + '><img  src="/stylebak/wushuang/dist/images/level/public_icon_vip' + data.levelid + '@2x.png" style="margin-right:2px;" width="20" height="15">' + data.from_client_name + '</label>：送了1个<font style="' + modefontcolor[mode][1] + '";> ' + data.giftName + '</font></p>';
        setTimeout("_chat.gift_msg(" + (data.giftCount) + ",'" + msgHtml + "')", 500);

        if (data.isred == 2 || data.isred == 5 || data.isred == 6) {
            Ctrfn.bigshowgift(data.giftId);
        }
        Ctrfn.sendShow(data.giftCount, data.from_client_name, data.from_client_avatar, data.giftPath, data.giftName);

    },
    _func_onLineClient: function (data) {
        $(".userinfo .unum").html(data.all_num_weixin);
        var onLineUserhtml = '';
        if (data != '') {
            var tmpLength = data.client_list_weixin.length;
            if (tmpLength >= 10) {
                tmpLength = 10;
            }
            for (var i = 0; i < tmpLength; i++) {
                if (parseInt(data.client_list_weixin[i].user_id) != -1)
                    onLineUserhtml += '<li><img user_id="' + data.client_list_weixin[i].user_id + '" src="' + data.client_list_weixin[i].avatar + '"></li>';
            }
        }
        $("#userpic").html(onLineUserhtml);
    },

    _isHasImg: function (pathImg) {
        var ImgObj = new Image();
        ImgObj.src = pathImg;
        if (ImgObj.fileSize > 0 || (ImgObj.width > 0 && ImgObj.height > 0)) {
            return true;
        } else {
            return false;
        }
    },
    _func_error: function (data) {
        console.log(data);
        _chat.show_message("系统提示", data.content, "", "", 2, '');
    },

    _func_SendPubMsg: function (data) {
        if (data.type == "SendPubMsg") {
            _chat.show_message(data.from_client_name, data.content, data.levelid, 1, 1, data.from_user_id);
            if (data.fly == "FlyMsg") {
                var div = '<div><div style="margin-right:5px;"><img onerror="this.src=\'/stylebak/avatar/0/0_big.jpg\'"  src="' + data.avatar + '"></div><div><p class="nickname">"' + data.from_client_name + '":</p><p>' + data.content + '</p></div></div>';
                $(".chat_barrage_box").append(div);
                Ctrfn.init_screen();
            }
        }
    },

    _func_ping: function (data) {
        console.log(data);

        var msg = {};
        msg._method_ = "pong";

        SocketIO._sendMsg(JSON.stringify(msg));
    },

    _func_logout: function (data) {
        console.log(data);
    },
    //showType表示消息在不同聊天环境下的样式 eg:
    //1:PC端直播 手机直播 手机观看 
    //2:pc端直播 手机直播 PC端观看
    //msg_type消息种类 0:聊天2:系统提示
    show_message: function (nickName, msg, level, showType, msg_type, user_id) {
        var color = modefontcolor[mode][0];
        var _msg = '';
        if (msg_type == 0) {
            _msg = '<p><label class ="user_nickname" user_id=' + user_id + '><img src="/stylebak/wushuang/dist/images/level/public_icon_vip' + level + '@2x.png" style="margin-right:2px;" width="20" height="15" >' + nickName + '</label> : <font color="' + color + '">' + msg + '</font></p>'
        } else if (msg_type == 1) {
            _msg = '<p><label class ="user_nickname" user_id=' + user_id + '><img src="/stylebak/wushuang/dist/images/level/public_icon_vip' + level + '@2x.png" style="margin-right:2px;" width="20" height="15" >' + nickName + '</label> : <font color="' + color + '">' + msg + '</font></p>'
        } else if (msg_type == 2) {
            _msg = '<p><font color="' + modefontcolor[mode][2] + '" class="firstfont">' + msg + '</font></p>'
        } else {
            if (showType == 0) {
                _msg = '<p><label class ="user_nickname" user_id=' + user_id + '><img src="/stylebak/wushuang/dist/images/level/public_icon_vip' + level + '@2x.png" style="margin-right:2px;" width="20" height="15" >' + nickName + '</label> : <font color="' + color + '">' + msg + '</font></p>'
            }
        }

        $('#chat_hall').append(_msg);
        _chat.remove_msg();
        var scrojh = $("#upchat_hall")[0].scrollHeight;
        $("#chat_hall").scrollTop($("#upchat_hall").scrollTop(scrojh));
    }
}


function sysmsg(msg) {
    $("#chat_hall").append("<p><font color='greenyellow'>" + msg + "<br /></font></p>");
}

function onLineClient(data) {
    $(".userinfo .unum").html(data.all_num);
    console.log("dddd" + data);

    var onLineUserhtml = '';
    for (var i = 0; i < 5; i++) {
        // onLineUserhtml += '<li><img src="'+avatarPath(data.client_list[i].user_id)+'"></li>';
        onLineUserhtml += '<li><img src="' + data.client_list_weixin[i].avatar + '"></li>';
    }

    $("#userpic").html(onLineUserhtml);
}

function onUserLogin(data) {
    $("#chat_hall").append("<p><font color='greenyellow'>" + msg + "<br /></font></p>");
}

function avatarPath(path) {
    return IMG_PATH + "/avatar/" + $.md5(path.toString()) + "/" + path + "_small.jpg"
}

SocketIO._initConnect();
