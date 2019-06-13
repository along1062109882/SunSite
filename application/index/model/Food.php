<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午4:40
 */

namespace app\index\model;

use think\Model;

class Food extends Model
{
    public function links()
    {
        return $this->hasOne('Links','id','link_id');
    }
    public function getContent()
    {
        return $this->hasMany('Content','owner_id')->where(['owner_type'=>'food']);
    }
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
