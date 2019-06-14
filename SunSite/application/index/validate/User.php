<?php
namespace app\index\validate;
use think\Validate;
use think\captcha;
class User extends Validate
{
	protected $rule = [
		'name|用户名' => 'require|min:5',
		'password|密码' => 'require|min:6|confirm:repassword',
		'email|邮箱' => 'email',
		'code|验证码'=>'require|captcha',
	];
	protected $message = [
		'name.require' => '用户名不能为空',
		'name.min' => '用户名长度不能低于5位',
		'password.require' => '密码不能为空',
		'password.min' => '密码长度不能低于6位',
		'password.confirm' => '两次密码不一致',
		'email' => '邮箱格式不正确',
		'code'=>'验证码输入有误',
	];
}