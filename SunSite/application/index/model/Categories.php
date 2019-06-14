<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午4:40
 */

namespace app\index\model;

use think\Model;

class Categories extends Model
{
    public function detail()
    {
        return $this->hasMany('CategoryDetails','category_id')->order('language','asc');
    }

    public function son()
    {
        return $this->hasMany('Categories','parent_id')->where('deleted',0)->with('detail,post')->order('seq','asc');
    }

    public function post()
    {
        return $this->hasMany('PostCategories','category_id')->with('detail')->order('id','desc');
    }


    public function backendSon()
    {
        return $this->hasMany('Categories','parent_id')->where('deleted',0)->with('getContentDetail')->order('seq','asc');//->field('id,parent_id,slug,seq')
    }


    public function getContentDetail(){
        return $this->hasMany('Content','owner_id')->where('owner_type','categories');

    }

}
