<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午4:40
 */

namespace app\index\model;

use think\Model;

class Posts extends Model
{
    public function detail()
    {
        return $this->hasMany('PostDetails','post_id')->where('deleted',0)->order('language','asc');
    }
    public function getCategory()
    {
        return $this->hasOne('PostCategories','post_id')->with('category');
    }


    public function coverLink()
    {
        return $this->hasOne('Links','id','cover_link_id');
    }

    public function squareLink()
    {
        return $this->hasOne('Links','id','square_link_id');
    }

    public function allLink()
    {
        return $this->hasMany('PostLinks','post_id','id')->with('linkDetail');
    }
}
