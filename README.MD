##Redis 结构
####礼物队列（WSGiftOrder） 
######$order = array('giftId' => $giftId,'userId' => $userId,'userIdTo' => $userIdTo,'num' => $num,'price' => $price);
######$redis->lpush('WSGiftOrder', base64_encode(json_encode($order)));
####用户可用余额（WSUserBalance） 
######$redis->hset('WSUserBalance', $userId, $balance);
####房间观众数量(根据服务器ip及房间id拼接key)
######$wsIp = self::getWsIp($roomId);
######$keyWSRoomUser = 'WSRoomUser_' . $wsIp . '_' . $roomId;
######$num = RedisClient::getInstance()->hLen($keyWSRoomUser);