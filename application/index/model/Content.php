<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午4:40
 */

namespace app\index\model;

use think\Model;

class Content extends Model
{
    public function detail()
    {
        return $this->hasMany('PostDetails','post_id')->where('deleted',0);
    }
    public function getCategory()
    {
        return $this->hasOne('PostCategories','post_id');
    }
//
//    public function son()
//    {
//        return $this->hasMany('Categories','parent_id')->with('detail')->order('seq','asc');
//    }
}
