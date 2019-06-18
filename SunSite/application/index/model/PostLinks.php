<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午4:40
 */

namespace app\index\model;

use think\Model;

class PostLinks extends Model
{
    public function linkDetail()
    {
        return $this->hasOne('Links','id','link_id')->where('deleted',0);
    }
//    public function catepost()
//    {
//        return $this->hasMany('Categories','category_id')->with('detail');
//    }
//    public function son()
//    {
//        return $this->hasMany('Categories','parent_id')->with('detail')->order('seq','asc');
//    }
//
//    public function post()
//    {
//        return $this->hasMany('Posts','category_id')->with('detail')->order('seq','asc');
//    }
}
