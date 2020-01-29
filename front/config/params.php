<?php

$params = [

    'local' => [
        'enableUnifiedAuthorization' => false,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '2297907E1A',
    ],

    'dev' => [
        'enableUnifiedAuthorization' => false,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '2297907E1A',
    ],

    'pre' => [
        'enableUnifiedAuthorization' => true,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '4ACC088BF2',
    ],

    'online' => [
        'enableUnifiedAuthorization' => true,
        'enableUnifiedAccessLog' => false,
        'enableUnifiedActionLog' => false,
        'unifiedLogSecret' => '4ACC088BF2',
    ],

];

return $params[YII_ENV];
