<?php

$params = [

    'local' => [
        'upload_dir'=>"/var/www/html/upload",
        'upload_download'=>"http://103.249.252.103:8083"
    ],

    'dev' => [
        'upload_dir'=>"/var/www/html/upload",
        'upload_download'=>"http://103.249.252.103:8083"
    ],

    'pre' => [
        'upload_dir'=>"/var/www/html/upload",
        'upload_download'=>"http://103.249.252.103:8083"
    ],

    'online' => [
        'upload_dir'=>"/var/www/html/upload",
        'upload_download'=>"http://103.249.252.103:8083"
    ],

];

return $params[YII_ENV];
