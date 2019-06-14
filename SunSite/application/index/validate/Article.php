<?php
namespace app\index\validate;
use think\Validate;
use think\captcha;
class Login extends Validate
{
	protected $rule = [
		'qq|QQ' => 'number',
		'huxing_shi|室' => 'number',
		'huxing_ting|厅' => 'number',
		'huxing_wei|卫' => 'number',
		'proportion|面积'=>'number',
		'floor|楼层'=>'number',
		'total_floor|总楼层'=>'number',
		'rent|租金'=>'number',
		'title|房源标题'=>'max:50',
		'tel|联系电话'=>'number',
		'code|验证码'=>'require|captcha',
	];
	protected $message = [
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'qq' => '只能输入数字',
		'code'=>'验证码输入有误',
	];
}