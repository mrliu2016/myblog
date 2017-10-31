<?php

namespace app\common\models;

use yii\base\Object;

class LoginUser extends Object
{
    public $userId;
    public $userName;
    public $applicationId;
    public $avatar;
    public $domain;
    public $role;
    public $environment;
}
