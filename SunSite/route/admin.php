<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

//define('APP_PATH',__DIR__.'/../application/admin');
//
////define('BIND_MODULE','admin');
//
//require __DIR__ . '/../thinkphp/base.php';
//// 支持事先使用静态方法设置Request对象和Config对象
//// 执行应用并响应
// Container::get('app')->bind('admin')->run()->send();

//// 加载基础文件
//require __DIR__ . '/../thinkphp/base.php';
//
//// 支持事先使用静态方法设置Request对象和Config对象
//// 执行应用并响应
//Container::get('app')->run()->send();
//// 定义应用目录
//define('APP_PATH', __DIR__ . '/../app/');
//
//require __DIR__ . '/../thinkphp/base.php';// 加载框架基础文件
////开启域名部署后
//switch ($_SERVER['HTTP_HOST']) {
//    case '172.21.71.22:88':
//        $model = 'index';// home模块
//        $route = true;// 开启路由
//        break;
//    case '172.21.71.22:81':
//        $model = 'admin';// admin模块
//        $route = true;// 关闭路由
//        break;
//}
//\think\Route::bind($model ?: 'index');// 绑定当前入口文件到模块
//\think\App::route($route ?: true);// 路由
//\think\App::run()->send();// 执行应用