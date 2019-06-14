<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午4:40
 */

namespace app\index\model;

use think\Model;

class GrandPrix extends Model
{
    public function current()
    {
        return $this->hasOne('CurrentRootCategory','owner_id')->where(['owner_type'=>'grand_prix'])->with('detail');
    }
    public function getContent()
    {
        return $this->hasMany('Content','owner_id')->where(['owner_type'=>'grand']);
    }
    public function getLink()
    {
        return $this->hasOne('LinkTarget','owner_id')->where('owner_type','grand')->with('getLink');
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
