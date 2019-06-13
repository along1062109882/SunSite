<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//use think\facade\Route;
Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
//Route::get('/:lang', 'index/index');

//Route::group('admin', [
////    'login$'=>'admin/Login/login',                                         //登录
////    'editPassword'=>'admin/User/editPassword',                             //重置密码
////    'logout$'=>'admin/Login/logout',                                       //退出
////    'check$'=>'admin/User/check',                                          //验证用户是否存在
////    'unlock'=>'admin/Login/unlock',                                        //验证用户是否存在
//    'verify'=>'admin/Login/verify',                                        //获取验证码
//]);

Route::get('/zh-hans/about-us', 'index/about_us');
Route::get('/zh-hans/news/category/latest', 'index/news');
Route::get('/zh-hans/news/category/all', 'index/news');
Route::get('/zh-hans/news/category/:name', 'index/news_post');
Route::get('/zh-hans/news', 'index/news');

Route::get('/zh-hans/release', 'index/release');
Route::get('/zh-hans/grand', 'index/grand');
Route::get('/zh-hans/news-post', 'index/news_post');
Route::get('/zh-hans/jobs/:slug', 'index/jobs');
Route::get('/zh-hans/jobs', 'index/jobs');
Route::get('/zh-hans/jobs-vip', 'index/jobs_vip');
Route::get('/zh-hans/jobs-vip/:slug', 'index/jobs_vip');
Route::get('/zh-hans/duty', 'index/duty');
Route::get('/zh-hans/disclaimer', 'index/disclaimer');
Route::get('/zh-hans/companies/sun-vip', 'index/sun_vip');
Route::get('/zh-hans/companies/sun-entertainment', 'index/sun_entertainment');
Route::get('/zh-hans/companies/sun-entertainment-concert', 'index/sun_entertainment_concert');
Route::get('/zh-hans/companies/sun-travel', 'index/sun_travel');
Route::get('/zh-hans/companies/sun-food', 'index/sun_food');
Route::get('/zh-hans/companies/sun-automobile', 'index/sun_automobile');
Route::get('/zh-hans/companies/sunluxe', 'index/sunluxe');
Route::get('/zh-hans/companies/sun-sia', 'index/sun_sia');
Route::get('/zh-hans/companies/sun-financial', 'index/sun_financial');
Route::get('/zh-hans/companies/sun-century', 'index/sun_century');
Route::get('/zh-hans/companies/sun-management', 'index/sun_management');
Route::get('/zh-hans/companies/sun-resource', 'index/sun_resource');
Route::get('/zh-hans/events', 'index/event');
Route::get('/zh-hans/event_concert', 'index/event_concert');
Route::get('/zh-hans/event_grandprix', 'index/event_grandprix');

Route::get('/zh-hans$', 'index/index');

Route::get('/zh-hant/about-us', 'index/about_us');
Route::get('/zh-hant/news/category/latest', 'index/news');
Route::get('/zh-hant/news/category/all', 'index/news');
Route::get('/zh-hant/news/category/:name', 'index/news_post');
Route::get('/zh-hant/news', 'index/news');


Route::get('/zh-hant/release', 'index/release');
Route::get('/zh-hant/grand', 'index/grand');
Route::get('/zh-hant/getUrl', 'index/getUrl');


Route::get('/zh-hant/jobs/:slug', 'index/jobs');
Route::get('/zh-hant/jobs', 'index/jobs');
Route::get('/zh-hant/jobs-vip', 'index/jobs_vip');
Route::get('/zh-hant/jobs-vip/:slug', 'index/jobs_vip');
Route::get('/zh-hant/duty', 'index/duty');
Route::get('/zh-hant/disclaimer', 'index/disclaimer');
Route::get('/zh-hant/companies/sun-vip', 'index/sun_vip');
Route::get('/zh-hant/companies/sun-entertainment', 'index/sun_entertainment');
Route::get('/zh-hant/companies/sun-entertainment-concert', 'index/sun_entertainment_concert');
Route::get('/zh-hant/companies/sun-travel', 'index/sun_travel');
Route::get('/zh-hant/companies/sun-food', 'index/sun_food');
Route::get('/zh-hant/companies/sun-automobile', 'index/sun_automobile');
Route::get('/zh-hant/companies/sunluxe', 'index/sunluxe');
Route::get('/zh-hant/companies/sun-sia', 'index/sun_sia');
Route::get('/zh-hant/companies/sun-financial', 'index/sun_financial');
Route::get('/zh-hant/companies/sun-century', 'index/sun_century');
Route::get('/zh-hant/companies/sun-management', 'index/sun_management');
Route::get('/zh-hant/companies/sun-resource', 'index/sun_resource');
Route::get('/zh-hant/events', 'index/event');
Route::get('/zh-hant/event_concert', 'index/event_concert');
Route::get('/zh-hant/event_grandprix', 'index/event_grandprix');
Route::get('/zh-hant$', 'index/index');
Route::get('/', 'index/index');



/**
 * 后台管理路由
 */
//Route::get('/admin/login', 'admin/login/login');

///**
// * 免权限验证路由
// */
Route::group('admin', [
    'login$'=>'admin/Login/login',                                         //登录
    'editPassword'=>'admin/User/editPassword',                             //重置密码
    'logout$'=>'admin/Login/logout',                                       //退出
    'check$'=>'admin/User/check',                                          //验证用户是否存在
    'unlock'=>'admin/Login/unlock',                                        //验证用户是否存在
    'verify'=>'admin/Login/verify',                                        //获取验证码

    'news'=>'admin/category/news',
    'get_news_data'=>'admin/category/get_news_data',
    'newsEdit'=>'admin/category/newsEdit',
    'newsDelete'=>'admin/category/newsDelete',

    'business'=>'admin/category/business',
    'get_business_data'=>'admin/category/get_business_data',
    'businessEdit'=>'admin/category/businessEdit',
    'businessDelete'=>'admin/category/businessDelete',

    'jobs'=>'admin/category/jobs',
    'get_jobs_data'=>'admin/category/get_jobs_data',
    'jobsEdit'=>'admin/category/jobsEdit',
    'jobsDelete'=>'admin/category/jobsDelete',

    'publish'=>'admin/category/publish',
    'get_publish_data'=>'admin/category/get_publish_data',
    'publishEdit'=>'admin/category/publishEdit',
    'publishDelete'=>'admin/category/publishDelete',
    'publishCommit'=>'admin/category/publishCommit',


    'photoList'=>'admin/link/photoList',
    'get_photo_data'=>'admin/link/get_photo_data',
    'get_choice_data'=>'admin/link/get_choice_data',
    'get_unchoice_data'=>'admin/link/get_unchoice_data',
    'choiceCommit'=>'admin/link/choiceCommit',
    'photoEdit'=>'admin/link/photoEdit',
    'photoDelete'=>'admin/link/photoDelete',
    'uploadPhoto'=>'admin/link/uploadPhoto',
    'multiUploadPhoto'=>'admin/link/multiUploadPhoto',
    'album'=>'admin/link/album',
    'news_album_delete'=>'admin/link/news_album_delete',

    'event_album'=>'admin/link/event_album',
    'event_choice_delete'=>'admin/link/event_choice_delete',
    'get_event_choice_data'=>'admin/link/get_event_choice_data',
    'event_choiceCommit'=>'admin/link/event_choiceCommit',

    'videoList'=>'admin/link/videoList',
    'get_video_data'=>'admin/link/get_video_data',
    'uploadVideo'=>'admin/link/uploadVideo',
    'uploadCommit'=>'admin/link/uploadCommit',


    'classify'=>'admin/category/classify',
    'get_classify_data'=>'admin/category/get_classify_data',
    'classifyEdit'=>'admin/category/classifyEdit',
    'classifyDelete'=>'admin/category/classifyDelete',

    'concertList'=>'admin/concerts/concertList',
    'get_concert_data'=>'admin/concerts/get_concert_data',
    'concertEdit'=>'admin/concerts/concertEdit',
    'concert_basic'=>'admin/concerts/concert_basic',
    'concert_desc'=>'admin/concerts/concert_desc',
    'concert_ticket'=>'admin/concerts/concert_ticket',
    'concert_intro'=>'admin/concerts/concert_intro',
    'concert_pic'=>'admin/concerts/concert_pic',
    'concertDelete'=>'admin/concerts/concertDelete',
    'concert_commit'=>'admin/concerts/concert_commit',

    'grandList'=>'admin/grand/grandList',
    'get_grand_data'=>'admin/grand/get_grand_data',
    'grandEdit'=>'admin/grand/grandEdit',
    'grand_basic'=>'admin/grand/grand_basic',
    'grand_video'=>'admin/grand/grand_video',
    'grand_cover'=>'admin/grand/grand_cover',
    'grand_ticket'=>'admin/grand/grand_ticket',
    'grand_tricks'=>'admin/grand/grand_tricks',
    'grandDelete'=>'admin/grand/grandDelete',
    'grand_commit'=>'admin/grand/grand_commit',


    'annualList'=>'admin/annuals/annualList',
    'get_annual_data'=>'admin/annuals/get_annual_data',
    'annualEdit'=>'admin/annuals/annualEdit',
    'annualDelete'=>'admin/annuals/annualDelete',
    'annual_commit'=>'admin/annuals/annual_commit',


    'sun_entertainment'=>'admin/manage/sun_entertainment',
    'sun_entertainment_data'=>'admin/manage/sun_entertainment_data',
    'sun_entertainment_edit'=>'admin/manage/sun_entertainment_edit',
    'sun_entertainment_edit_commit'=>'admin/manage/sun_entertainment_edit_commit',
    'sun_entertainment_delete'=>'admin/manage/sun_entertainment_delete',

    'sun_banner_edit'=>'admin/manage/sun_banner_edit',
    'sun_banner_commit'=>'admin/manage/sun_banner_commit',
    'sun_banner_data'=>'admin/manage/sun_banner_data',
    'sun_banner_delete'=>'admin/manage/sun_banner_delete',


    'sun_travel'=>'admin/manage/sun_travel',
    'sun_travel_data'=>'admin/manage/sun_travel_data',
    'sun_travel_edit'=>'admin/manage/sun_travel_edit',
    'sun_travel_delete'=>'admin/manage/sun_travel_delete',

    'sun_food'=>'admin/manage/sun_food',
    'sun_food_data'=>'admin/manage/sun_food_data',
    'sun_food_edit'=>'admin/manage/sun_food_edit',
    'sun_food_edit_commit'=>'admin/manage/sun_food_edit_commit',
    'sun_food_delete'=>'admin/manage/sun_food_delete',

    'sun_luxe'=>'admin/manage/sun_luxe',
    'sun_luxe_data'=>'admin/manage/sun_luxe_data',
    'sun_luxe_edit'=>'admin/manage/sun_luxe_edit',
    'sun_luxe_delete'=>'admin/manage/sun_luxe_delete',

    'sun_resort'=>'admin/manage/sun_resort',
    'sun_resort_data'=>'admin/manage/sun_resort_data',
    'sun_resort_edit'=>'admin/manage/sun_resort_edit',
    'sun_resort_delete'=>'admin/manage/sun_resort_delete',

]);
///**
// * 需要权限验证路由
// */
Route::group('admin', [

    //首页
    'index$'=>'admin/Index/index',                                           //首页
    'home'=>'admin/Index/home',                                              //系统信息

    //用户管理
    'userList$'=>'admin/User/userList',                                      //用户列表
    'userInfo$'=>'admin/User/userInfo',                                      //用户信息
    'edit$'=>'admin/User/edit',                                              //添加/编辑用户
    'delete$'=>'admin/User/delete',                                          //删除用户
    'groupList$'=>'admin/User/groupList',                                    //用户组列表
    'editGroup$'=>'admin/User/editGroup',                                    //添加编辑用户组
    'disableGroup$'=>'admin/User/disableGroup',                              //禁用用户组
    'ruleList$'=>'admin/User/ruleList',                                      //用户组规则列表
    'editRule$'=>'admin/User/editRule',                                      //修改用户组规则

    //系统管理
    'cleanCache$'=>'admin/System/cleanCache',                                //清除缓存
    'log$'=>'admin/System/loginLog',                                         //登录日志
    'downlog$'=>'admin/System/downLoginLog',                                 //下载登录日志
    'menu$'=>'admin/System/menu',                                            //系统菜单
    'editMenu$'=>'admin/System/editMenu',                                    //编辑菜单
    'deleteMenu$'=>'admin/System/deleteMenu',                                //删除菜单
    'config'=>'admin/System/config',                                         //系统配置
    'siteConfig'=>'admin/System/siteConfig',                                 //站点配置
    //上传管理
    'upload'=>'admin/Upload/index',                                    //上传图片



    'news'=>'admin/category/news',                                        //
    'newsEdit'=>'admin/category/newsEdit',                                        //
    'newsDelete'=>'admin/category/newsDelete',                                        //

    'business'=>'admin/category/business',                                        //
    'jobs'=>'admin/category/jobs',
])->middleware(app\admin\middleware\CheckAuth::class);//->ext('html');          //使用中间件验证
/**
 * miss路由
 * 没有定义的路由全部使用该路由
 */
Route::miss('admin/Login/login');
return [

];
