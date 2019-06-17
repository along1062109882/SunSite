<?php
/**
 * Created by originThink
 * Author: 原点 467490186@qq.com
 * Date: 2018/1/18
 * Time: 15:05
 */

namespace app\admin\controller;

use app\admin\traits\Result;
use app\index\model\Categories;
use app\index\model\LinkTarget;
use app\index\model\PostCategories;
use app\index\model\PostDetails;
use app\index\model\PostLinks;
use app\index\model\Posts;
use app\index\model\Links;
use aws\Aws;
use Aws\S3\S3Client;

class Link extends Common
{
    /**
     * 集團動態列表
     */
    public function photoList()
    {
        return $this->fetch();
    }

    public function get_photo_data(){

        $types = $this->request->post('types', '');
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $keyword = $this->request->post('keyword', '');
        if($types == 'reset'){
            $limit = 10;
            $page = 1;
            $keyword = '';
        }
        $offset = ($page - 1) * $limit;

        $map[]=['deleted','=',0];
        $map[]=['type','=',0];
        if($keyword){
            $map[] = ['name','like','%'.$keyword.'%'];
        }

        $datas = Links::where($map)->field('*')->order('created','desc')->limit($offset,$limit)->select()->toArray();
        if($datas){
            $aws = new Aws();
            foreach ($datas as $k=>$v){
                $datas[$k]['url'] = $datas[$k]['url']?$aws->getUrl($datas[$k]['url']):'';
            }
        }
        $count = Links::where($map)->count();

        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }
    /**
     * 添加、编辑集團動態
     */
    public function photoEdit()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();

            if (isset($data['id']) && !empty($data['id'])) {
                $id = $data['id'];
                $list = Links::find(['id'=>$id]);
                $list->name = $data['name'];
                $list->description = $data['description'];
                if($list->save()){
                    return Result::success('成功');
                }
                return Result::error('失敗');
            } else {
                return Result::error('失敗');
            }
        }

        $id = $this->request->get('id');
        $list = Links::find(['id'=>$id]);

        $this->assign('list', $list);
        return $this->fetch();
    }


    /**
     * 删除集團動態
     */
    public function photoDelete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Links::update(['deleted'=>1],['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }

    public function uploadPhoto(){

//        if($this->request->isPost())
//        {
//            $data = $this->request->post();
////            var_dump($_FILES);
////            var_dump($data);
////            die();
////            return $_FILES;
//            if($_FILES){
//                $file_name = $_FILES['file']['name'];
//                $ext = pathinfo($file_name)['extension'];
//                $type = isset($data['type'])?$data['type']:0;
//
//                $aws = new Aws();
//                $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$type);
//
//                $list = new Links();
//                $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
//                $list->description = isset($data['description'])?$data['description']:'';
//                $list->url = $url;
//                $list->author_id = 0;
//                $list->type = 0;
//                if($list->save()){
////                    return Result::success('成功');
//                }
//            }
////            return Result::error('失敗');
//
//        }
        return $this->fetch();
    }

    public function multiUploadPhoto(){
        return $this->fetch();
    }

    public function uploadCommit(){

        if($this->request->isPost())
        {
            $data = $this->request->post();
            if($_FILES['file']['name']){
                if(is_array($_FILES['file']['name'])){
                    foreach ($_FILES['file']['name'] as $k=>$v){
                        $file_name = $_FILES['file']['name'][$k];
                        $ext = pathinfo($file_name)['extension'];
                        $type = isset($data['type'])?$data['type']:0;

                        $aws = new Aws();
                        $url = $aws->Upload($_FILES['file']['tmp_name'][$k],$ext,$type);

                        $list = new Links();
                        $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                        $list->description = isset($data['description'])?$data['description']:'';
                        $list->url = $url;
                        $list->author_id = 0;
                        $list->type = 0;
                        if($list->save()){
                        }
                    }
                    return json(Result::success('成功'));
                }else{
                    $file_name = $_FILES['file']['name'];
                    $ext = pathinfo($file_name)['extension'];
                    $type = isset($data['type'])?$data['type']:0;

                    $aws = new Aws();
                    $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$type);

                    $list = new Links();
                    $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                    $list->description = isset($data['description'])?$data['description']:'';
                    $list->url = $url;
                    $list->author_id = 0;
                    $list->type = 0;
                    if($list->save()){
                        return json(Result::success('成功'));
                    }
                }
            }
        }
        return json(Result::error('失敗'));
    }



    public function videoList()
    {
        return $this->fetch();
    }

    public function get_video_data(){
        $types = $this->request->post('types', '');
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $keyword = $this->request->post('keyword', '');
        if($types == 'reset'){
            $limit = 10;
            $page = 1;
            $keyword = '';
        }
        $offset = ($page - 1) * $limit;

        $map[]=['deleted','=',0];
        $map[]=['type','=',1];
        if($keyword){
            $map[] = ['name','like','%'.$keyword.'%'];
        }

        $datas = Links::where($map)->field('*')->order('created','desc')->limit($offset,$limit)->select()->toArray();
        $count = Links::where($map)->count();

        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }


    public function uploadVideo(){
        return $this->fetch();
    }



    public function album()
    {
        $data = $this->request->get();
        $this->assign('id', $data['id']);
        $this->assign('type', $data['type']); //0相簿，1封面，2方形封面

        return $this->fetch();
    }


    public function get_choice_data(){

        $type = $this->request->post('type', 0);
        $post_id = $this->request->post('id', 0);
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');


        $offset = ($page - 1) * $limit;

        $map[]=['deleted','=',0];
        $map[]=['type','=',0];

        $post = Posts::find(['id'=>$post_id]);
        if($post){
            $po_link = [];
            if($type == 1){
                $link_id = $post['cover_link_id'];
                $map[]=['id','=',$link_id];
                $datas = Links::where($map)->field('*')->order('created','desc')->limit($offset,$limit)->select()->toArray();
                if($datas){
                    $aws = new Aws();
                    foreach ($datas as $k=>$v){
                        $datas[$k]['url'] = $datas[$k]['url']?$aws->getUrl($datas[$k]['url']):'';
                    }
                }
                $count = Links::where($map)->count();
            }elseif ($type == 2){
                $link_id = $post['square_link_id'];
                $map[]=['id','=',$link_id];
                $datas = Links::where($map)->field('*')->order('created','desc')->limit($offset,$limit)->select()->toArray();
                if($datas){
                    $aws = new Aws();
                    foreach ($datas as $k=>$v){
                        $datas[$k]['url'] = $datas[$k]['url']?$aws->getUrl($datas[$k]['url']):'';
                    }
                }
                $count = Links::where($map)->count();
            }else{
                $all_links = PostLinks::where(['post_id'=>$post_id])->field('*')->limit($offset,$limit)->select()->toArray();
                if($all_links){
                    $all_link_ids = array_column($all_links,'link_id');
                    $map[]=['id','in',$all_link_ids];
                    $datas = Links::where($map)->field('*')->order('created','desc')->limit($offset,$limit)->select()->toArray();
                    if($datas){
                        $aws = new Aws();
                        foreach ($datas as $k=>$v){
                            $datas[$k]['url'] = $datas[$k]['url']?$aws->getUrl($datas[$k]['url']):'';
                        }
                    }
                    $count = Links::where($map)->count();
                }
            }

        }

        echo json_encode([
            'code'=>0,
            'count'=>isset($count)?$count:0,
            'data'=>isset($datas)?$datas:[],
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }

    //还没实现
    public function get_unchoice_data(){

        $types = $this->request->post('types', '');
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $keyword = $this->request->post('keyword', '');
        if($types == 'reset'){
            $limit = 10;
            $page = 1;
            $keyword = '';
        }
        $offset = ($page - 1) * $limit;

        $map[]=['deleted','=',0];
        $map[]=['type','=',0];
        if($keyword){
            $map[] = ['name','like','%'.$keyword.'%'];
        }

        $datas = Links::where($map)->field('*')->order('created','desc')->limit($offset,$limit)->select()->toArray();
        if($datas){
            $aws = new Aws();
            foreach ($datas as $k=>$v){
                $datas[$k]['url'] = $datas[$k]['url']?$aws->getUrl($datas[$k]['url']):'';
            }
        }
        $count = Links::where($map)->count();

        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }

    public function choiceCommit(){
        $id = $this->request->post('id');
        $type = $this->request->post('type');
        $post_id = $this->request->post('post_id');

        if($type==1){
            Posts::update(['cover_link_id'=>$id],['id'=>$post_id]);
        }elseif ($type==2){
            Posts::update(['square_link_id'=>$id],['id'=>$post_id]);
        }else{
            $check = PostLinks::find(['post_id'=>$post_id,'link_id'=>$id]);
            if(!$check){
                $new = new PostLinks();
                $new->post_id = $post_id;
                $new->link_id = $id;
                $new->save();
            }
        }
        return Result::success('成功');
    }

    public function news_album_delete(){
        $id = $this->request->post('id');
        $type = $this->request->post('type');
        $post_id = $this->request->post('post_id');

        if($type==1){
            Posts::update(['cover_link_id'=>0],['id'=>$post_id]);
        }elseif ($type==2){
            Posts::update(['square_link_id'=>0],['id'=>$post_id]);
        }else{
          PostLinks::destroy(['post_id'=>$post_id,'link_id'=>$id]);
        }
        return Result::success('成功');
    }




    public function event_album()
    {
        $data = $this->request->get();
        $this->assign('id', $data['id']);
        $this->assign('type', $data['type']); //1演唱会，2十周年，3大赛车

        return $this->fetch();
    }

    public function get_event_choice_data(){

        $type = $this->request->post('type', 1);
        $post_id = $this->request->post('id', 0);
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');

        if($type==1){
            $owner_type = 'concert';
        }elseif($type==2){
            $owner_type = 'annual';
        }elseif($type==3){
            $owner_type = 'grand';
        }

        $offset = ($page - 1) * $limit;
        $link = LinkTarget::where(['owner_type'=>$owner_type,'owner_id'=>$post_id])->with('getLink')->find();

        $datas = [];
        if($link){
            $link = $link->toArray();
            if($link['get_link']){
                $aws = new Aws();
                $link['get_link']['url'] = $link['get_link']['url']?$aws->getUrl($link['get_link']['url']):'';
            }
            $datas[] = $link['get_link'];
        }

        echo json_encode([
            'code'=>0,
            'count'=>1,
            'data'=>isset($datas)?$datas:[],
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }
    public function event_choiceCommit(){
        $id = $this->request->post('id');
        $type = $this->request->post('type');
        $post_id = $this->request->post('post_id');
        if($type==1){
            $owner_type = 'concert';
        }elseif($type==2){
            $owner_type = 'annual';
        }elseif($type==3){
            $owner_type = 'grand';
        }
        $check = LinkTarget::where(['owner_type'=>$owner_type,'owner_id'=>$post_id])->find();

        if(!$check){
            $new = new LinkTarget();
            $new->owner_id = $post_id;
            $new->owner_type = $owner_type;
            $new->link_id = $id;
            $new->owner_field = 'all';
            $new->type = 0;
            $new->save();
        }else{
            LinkTarget::update(['link_id'=>$id],['owner_type'=>$owner_type,'owner_id'=>$post_id]);
        }
        return Result::success('成功');
    }


    public function event_choice_delete(){
        $id = $this->request->post('id');
        $type = $this->request->post('type');
        $post_id = $this->request->post('post_id');
        if($type==1){
            $owner_type = 'concert';
        }elseif($type==2){
            $owner_type = 'annual';
        }elseif($type==3){
            $owner_type = 'grand';
        }

        LinkTarget::destroy(['owner_type'=>$owner_type,'owner_id'=>$post_id,'link_id'=>$id]);

        return Result::success('成功');
    }





}