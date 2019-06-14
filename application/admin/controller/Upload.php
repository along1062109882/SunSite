<?php
/**
 *
 * User: Edwin
 * Date: 2019/5/31
 * Email: <467490186@qq.com>
 */

namespace app\admin\controller;


class Upload extends Common
{
    public function index()
    {
        $user = session('user_auth');
        $file = request()->file('file');
        $info = $file->validate(['size'=>1024*1024*2,'ext'=>'jpg,png,gif,mp4'])->move( '../public/uploads');
        if($info){
            $src = '/uploads/'.$info->getSaveName();
            $msg=['code'=>0,'msg'=>'上传成功','data'=>['src'=>$src]];
            \app\admin\model\User::update(['head'=>$src],['uid'=>$user['uid']]);
        }else{
            $msg=['code'=>1,'msg'=>$file->getError()];
        }
        return $msg;
        
    }

}