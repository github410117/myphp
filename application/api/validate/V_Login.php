<?php
/**
 * Created by PhpStorm.
 * User: xh
 * Date: 2018/1/4
 * Time: 17:53
 */

namespace app\api\validate;

use think\validate;

class V_Login extends Validate
{
    protected $rule = [
        'username' => 'require|length:3,10',
        'password' => 'require|length:6,18',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'username.length' => '用户名的长度为3-10位',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度为6-18位',
    ];

}