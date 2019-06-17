<?php
/**
 * Created by originThink
 * Author: 原点 467490186@qq.com
 * Date: 2016/9/9
 * Time: 15:39
 */

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'user' => 'require|max:25',
        'password' => 'require|length:6,25',
//        'code|验证码'=>'require|captcha',
    ];

    protected $message = [
        'user.require' => '用戶名不能為空',
        'user.length' => '用戶名長度2-25位',
        'password.require' => '密碼不能為空',
        'password.length' => '密碼長度6-25位',
//        'code.require' => '验证码不能为空',
//        'code.captcha' => '验证码错误'
    ];

    public function sceneAdd()
    {
        return $this->only(['user', 'password'])->append('user', 'require|length:2,25|token');
    }

    public function sceneCaptcha()
    {
        return $this->append('code', 'require|captcha');
    }
}