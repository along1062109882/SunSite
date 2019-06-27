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



Route::get('/zh-hans/about-us', 'index/about_us');                                                          //集團介紹
Route::get('/zh-hans/news/category/latest', 'index/news');                                                  //最新新聞
Route::get('/zh-hans/news/category/all', 'index/news');                                                     //全部新聞
Route::get('/zh-hans/news/category/:name', 'index/news_post');                                              //集團介紹
Route::get('/zh-hans/news', 'index/news');
Route::post('/zh-hans/post_news', 'index/post_news');
Route::get('/zh-hans/getParam', 'index/getParam');

Route::get('/zh-hans/release', 'index/release');
Route::post('/zh-hans/post_release', 'index/post_release');
Route::get('/zh-hans/grand', 'index/grand');
Route::get('/zh-hans/news-post', 'index/news_post');
Route::get('/zh-hans/jobs/:slug', 'index/jobs');
Route::get('/zh-hans/jobs', 'index/jobs');
Route::get('/zh-hans/jobs-vip', 'index/jobs_vip');
Route::post('/zh-hans/jobUpload', 'index/jobUpload');
Route::post('/zh-hans/jobCommit', 'index/jobCommit');
Route::get('/zh-hans/jobs-application', 'index/jobs_application');
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
Route::post('/zh-hant/post_news', 'index/post_news');
Route::get('/zh-hant/getParam', 'index/getParam');


Route::get('/zh-hant/release', 'index/release');
Route::post('/zh-hant/post_release', 'index/post_release');
Route::get('/zh-hant/grand', 'index/grand');
Route::get('/zh-hant/getUrl', 'index/getUrl');


Route::get('/zh-hant/jobs/:slug', 'index/jobs');
Route::get('/zh-hant/jobs', 'index/jobs');
Route::get('/zh-hant/jobs-vip', 'index/jobs_vip');
Route::get('/zh-hant/jobs-application', 'index/jobs_application');
Route::get('/zh-hant/jobs-vip/:slug', 'index/jobs_vip');
Route::post('/zh-hant/jobUpload', 'index/jobUpload');
Route::post('/zh-hant/jobCommit', 'index/jobCommit');
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
 * miss路由
 * 没有定义的路由全部使用该路由
 */
Route::miss('index/index/unknown');
return [

];
