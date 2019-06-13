<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午4:40
 */

namespace app\index\model;

use think\Model;

class PostCategories extends Model
{
    public function detail()
    {
        return $this->hasOne('Posts','id','post_id')->where('deleted',0)->order('publish_time','desc')->with('detail,coverLink');
    }
    public function cateDetail()
    {
        return $this->hasMany('CategoryDetails','category_id','category_id')->with('category');
    }
    public function category()
    {
        return $this->hasOne('Categories','id','category_id')->with('detail,getContentDetail')->order('seq','asc');
    }
//
//    public function post()
//    {
//        return $this->hasMany('Posts','category_id')->with('detail')->order('seq','asc');
//    }
}
