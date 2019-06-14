<?php
namespace app\index\validate;
use think\Validate;
use think\captcha;
class Login extends Validate
{
	protected $rule = [
		'name|用户名' => 'require|min:3',
		'password|密码' => 'require|min:6|confirm:password',
		'code|验证码'=>'require|captcha',
	];
	protected $message = [
		'name.require' => '用户名不能为空',
		'name.min' => '用户名长度不能低于3位',
		'password.require' => '密码不能为空',
		'password.min' => '密码长度不能低于6位',
		'code'=>'验证码输入有误',
	];
}