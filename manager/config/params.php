<?php

$params = [

    'local'  => [
        'unifiedLoginAuthenticateURL' => 'http://accounts.meili-inc.com/appAuth',
        'enableUnifiedAuthorization' => false,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '2297907E1A',
    ],

    'dev'    => [
        'unifiedLoginAuthenticateURL' => 'http://mac.service.mogujie.org/appAuth',
        'enableUnifiedAuthorization' => false,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '2297907E1A',
    ],

    'pre'    => [
        'unifiedLoginAuthenticateURL' => 'http://mac.service.mogujie.org/appAuth',
        'enableUnifiedAuthorization' => true,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '4ACC088BF2',
    ],

    'online' => [
        'unifiedLoginAuthenticateURL' => 'http://mac.service.mogujie.org/appAuth',
        'enableUnifiedAuthorization' => true,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '4ACC088BF2',
    ],

];

return $params[YII_ENV];
