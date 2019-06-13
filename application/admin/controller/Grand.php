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
use app\index\model\Content;
use app\index\model\GrandPrix;
use app\index\model\LinkTarget;
use app\index\model\PostCategories;
use app\index\model\PostDetails;
use app\index\model\Posts;
use app\index\model\Links;
use Aws\Aws;

class Grand extends Common
{
    /**
     * 集團動態列表
     */
    public function grandList()
    {
        return $this->fetch();
    }

    public function get_grand_data(){
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $offset = ($page - 1) * $limit;

        $datas = GrandPrix::where(['deleted_at'=> null])->field('id,publish_time,state')->with('getContent')->order('publish_time','desc')->limit($offset,$limit)->select()->toArray();
        $count = GrandPrix::where(['deleted_at'=> null])->count();
        foreach ($datas as $k=>$v){
            $datas[$k]['state'] = $datas[$k]['state']?'已發布':'草稿';
            $datas[$k]['name'] = '';
            $datas[$k]['content'] = '';
            if($datas[$k]['get_content']){
                foreach ($datas[$k]['get_content'] as $ck=>$cv){
                    if($datas[$k]['get_content'][$ck]['language']==0 && $datas[$k]['get_content'][$ck]['owner_field']=='Name'){
                        $datas[$k]['name'] = $datas[$k]['get_content'][$ck]['data'];
                    }
                    if($datas[$k]['get_content'][$ck]['language']==0 && $datas[$k]['get_content'][$ck]['owner_field']=='Content'){
                        $datas[$k]['content'] = $datas[$k]['get_content'][$ck]['data'];
                    }
                }
            }
            unset($datas[$k]['get_content']);
        }
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
    public function grandEdit(){
        $id = $this->request->get('id');
        if($id){
            $llist = GrandPrix::where(['id'=>$id])->with('getContent')->find()->toArray();
            $list=[];
            if($llist){
                $list['id'] = $llist['id'];
                $list['slug'] = $llist['slug'];
                $list['url'] = $llist['url'];
                $list['state'] = $llist['state'];
                $list['time'] = $llist['publish_time'];
                $list['title_hk'] = '';
                $list['content_hk'] = '';
                $list['title_cn'] = '';
                $list['content_cn'] = '';
                $list['title_en'] = '';
                $list['content_en'] = '';

                if($llist['get_content']){
                    foreach ($llist['get_content'] as $ck=>$cv){
                        if($llist['get_content'][$ck]['language']==0){
                            if($llist['get_content'][$ck]['owner_field']=='Name'){
                                $list['title_hk'] = $llist['get_content'][$ck]['data'];
                            }
                            if($llist['get_content'][$ck]['owner_field']=='Content'){
                                $list['content_hk'] = $llist['get_content'][$ck]['data'];
                            }
                        }elseif($llist['get_content'][$ck]['language']==1){
                            if($llist['get_content'][$ck]['owner_field']=='Name'){
                                $list['title_cn'] = $llist['get_content'][$ck]['data'];
                            }
                            if($llist['get_content'][$ck]['owner_field']=='Content'){
                                $list['content_cn'] = $llist['get_content'][$ck]['data'];
                            }
                        }elseif ($llist['get_content'][$ck]['language']==2){
                            if($llist['get_content'][$ck]['owner_field']=='Name'){
                                $list['title_en'] = $llist['get_content'][$ck]['data'];
                            }
                            if($llist['get_content'][$ck]['owner_field']=='Content'){
                                $list['content_en'] = $llist['get_content'][$ck]['data'];
                            }
                        }
                    }
                }

            }
            $this->assign('list', $list);
        }
        return $this->fetch();
    }

    public function grand_commit(){
        $data = $this->request->post();
        $id = $data['id'];
        if (isset($id) && !empty($id)) {
            //编辑
            $posts = GrandPrix::find(['id'=>$data['id']]);
            $posts->slug = $data['slug'];
            $posts->url = $data['url'];
            $posts->state = intval($data['status']);
            $posts->publish_time = $data['time'];
            $posts->updated_at = date('Y-m-d H:i:s');
            if($posts->save()){
                Content::update(['data'=>$data['title_hk']],['owner_id'=>$data['id'],'language'=>0,'owner_type'=>'grand','owner_field'=>'Name']);
                Content::update(['data'=>$data['title_cn']],['owner_id'=>$data['id'],'language'=>1,'owner_type'=>'grand','owner_field'=>'Name']);
                Content::update(['data'=>$data['title_en']],['owner_id'=>$data['id'],'language'=>2,'owner_type'=>'grand','owner_field'=>'Name']);
                Content::update(['data'=>$data['content_hk']],['owner_id'=>$data['id'],'language'=>0,'owner_type'=>'grand','owner_field'=>'Content']);
                Content::update(['data'=>$data['content_cn']],['owner_id'=>$data['id'],'language'=>1,'owner_type'=>'grand','owner_field'=>'Content']);
                Content::update(['data'=>$data['content_en']],['owner_id'=>$data['id'],'language'=>2,'owner_type'=>'grand','owner_field'=>'Content']);
                if($_FILES['file']['name']){
                    $file_name = $_FILES['file']['name'];
                    $ext = pathinfo($file_name)['extension'];

                    $aws = new Aws();
                    $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,0);

                    $list = new Links();
                    $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                    $list->description = isset($data['description'])?$data['description']:'';
                    $list->url = $url;
                    $list->author_id = 0;
                    $list->type = 0;
                    if($list->save()){
                        $check = LinkTarget::where(['owner_type'=>'grand','owner_id'=>$id,'link_id'=>$list->id])->find();
                        if(!$check){
                            $new = new LinkTarget();
                            $new->owner_id = $id;
                            $new->owner_type = 'grand';
                            $new->link_id = $list->id;
                            $new->owner_field = 'all';
                            $new->type = 0;
                            $new->save();
                        }else{
                            LinkTarget::update(['link_id'=>$list->id],['owner_type'=>'grand','owner_id'=>$id]);
                        }
                        return json(Result::success('成功'));
                    }
                    return json(Result::error('失敗'));
                }

                return Result::success('成功');
            }else{
                return Result::error('失敗');
            }

        } else {

            //添加
            $posts = new GrandPrix();
            $posts->slug = $data['slug'];
            $posts->url = $data['url'];
            $posts->state = intval($data['status']);
            $posts->publish_time = $data['time'];
            $posts->created_at = date('Y-m-d H:i:s');
            $posts->updated_at = date('Y-m-d H:i:s');
            if($posts->save()){
                $detail = [
                    [
                        'language'=>0,
                        'title'=>$data['title_hk'],
                        'content'=>$data['content_hk'],
                    ],
                    [
                        'language'=>1,
                        'title'=>$data['title_cn'],
                        'content'=>$data['content_cn'],
                    ],
                    [
                        'language'=>2,
                        'title'=>$data['title_en'],
                        'content'=>$data['content_en'],
                    ],
                ];
                foreach ($detail as $k=>$v){
                    $content = new Content();
                    $content->data = $v['title'];
                    $content->language = $v['language'];
                    $content->owner_id = $posts->id;
                    $content->owner_type = 'grand';
                    $content->owner_field = 'Name';
                    $content->save();
                    $content = new Content();
                    $content->data = $v['content'];
                    $content->language = $v['language'];
                    $content->owner_id = $posts->id;
                    $content->owner_type = 'grand';
                    $content->owner_field = 'Content';
                    $content->save();
                }
                if($_FILES['file']['name']){
                    $file_name = $_FILES['file']['name'];
                    $ext = pathinfo($file_name)['extension'];

                    $aws = new Aws();
                    $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,0);

                    $list = new Links();
                    $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                    $list->description = isset($data['description'])?$data['description']:'';
                    $list->url = $url;
                    $list->author_id = 0;
                    $list->type = 0;
                    if($list->save()){
                        $check = LinkTarget::where(['owner_type'=>'grand','owner_id'=>$posts->id])->find();
                        if(!$check){
                            $new = new LinkTarget();
                            $new->owner_id = $posts->id;
                            $new->owner_type = 'grand';
                            $new->link_id = $list->id;
                            $new->owner_field = 'all';
                            $new->type = 0;
                            $new->save();
                        }else{
                            LinkTarget::update(['link_id'=>$list->id],['owner_type'=>'grand','owner_id'=>$posts->id]);
                        }
                        return json(Result::success('成功'));
                    }
                    return json(Result::error('失敗'));
                }

                return Result::success('成功');
            }else{
                return Result::error('失敗');
            }
        }

    }




    public function grand_basic(){
        return $this->fetch();

    }
    public function grand_cover(){
        return $this->fetch();

    }

    public function grand_video(){
        return $this->fetch();

    }

    public function grand_ticket(){
        return $this->fetch();

    }

    public function grand_tricks(){
        return $this->fetch();
    }





    /**
     * 删除集團動態
     */
    public function grandDelete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = GrandPrix::update(['deleted_at'=>date('Y-m-d H:i:s')],['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }



}