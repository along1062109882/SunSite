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
use app\index\model\Entertainment;
use app\index\model\Food;
use app\index\model\LinkTarget;
use app\index\model\PostCategories;
use app\index\model\PostDetails;
use app\index\model\Posts;
use app\index\model\Links;
use Aws\Aws;

class Manage extends Common
{
    /**
     * 影視娛樂
     */
    public function sun_entertainment()
    {
        //演唱会
        $one = Entertainment::where(['deleted'=>0,'type'=>1])->count();
        //文化
        $two = Entertainment::where(['deleted'=>0,'type'=>2])->count();
        $this->assign('one', $one);
        $this->assign('two', $two);

        return $this->fetch();
    }

    public function sun_entertainment_data(){
        $aws = new Aws();

        $types = $this->request->get('type', 1,'intval');
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');

        $offset = ($page - 1) * $limit;

        $map[]=['deleted','=',0];
        $map[]=['type','=',$types];

        $datas = Entertainment::where($map)->field('id,type,link_id')->with('links,getContent')->limit($offset,$limit)->select()->toArray();
        $count = Entertainment::where($map)->count();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['url'] = isset($datas[$k]['links']['url'])?$aws->getUrl($datas[$k]['links']['url']):'';
                $datas[$k]['name'] = isset($datas[$k]['links']['name'])?$datas[$k]['links']['name']:'';
                if($datas[$k]['get_content']){
                    foreach ($datas[$k]['get_content'] as $ck=>$cv){
                        if($datas[$k]['get_content'][$ck]['language']==0){
                            if($datas[$k]['get_content'][$ck]['owner_field']=='Title'){
                                $datas[$k]['title'] = $datas[$k]['get_content'][$ck]['data'];
                            }
                            if($datas[$k]['get_content'][$ck]['owner_field']=='Meta'){
                                $datas[$k]['meta'] = $datas[$k]['get_content'][$ck]['data'];
                            }
                        }
                    }
                }
                unset($datas[$k]['get_content']);
                unset($datas[$k]['links']);
            }
        }

        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }

    public function sun_entertainment_edit()
    {
        $aws = new Aws();
        $id = $this->request->get('id');
        $type = $this->request->get('type',0,'intval');
        $where= $this->request->get('where','');
        if($id){
            $datas = Entertainment::where(['id'=>$id])->field('id,type,link_id')->with('links,getContent')->find()->toArray();
            $list=[];

            if($datas){
                $list['id'] = $datas['id'];
                $list['url'] = $datas['links']?$datas['links']['url']:'';
                $list['type'] = $datas['type'];
                $list['title_hk'] = '';
                $list['meta_hk'] = '';
                $list['title_cn'] = '';
                $list['meta_cn'] = '';
                $list['title_en'] = '';
                $list['meta_en'] = '';
                if($datas['get_content']){
                    foreach ($datas['get_content'] as $ck=>$cv){
                        if($datas['get_content'][$ck]['language']==0){
                            if($datas['get_content'][$ck]['owner_field']=='Title'){
                                $list['title_hk'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='Meta'){
                                $list['meta_hk'] = $datas['get_content'][$ck]['data'];
                            }
                        }elseif($datas['get_content'][$ck]['language']==1){
                            if($datas['get_content'][$ck]['owner_field']=='Title'){
                                $list['title_cn'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='Meta'){
                                $list['meta_cn'] = $datas['get_content'][$ck]['data'];
                            }
                        }elseif ($datas['get_content'][$ck]['language']==2){
                            if($datas['get_content'][$ck]['owner_field']=='Title'){
                                $list['title_en'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='Meta'){
                                $list['meta_en'] = $datas['get_content'][$ck]['data'];
                            }
                        }
                    }
                }
                $list['img_url'] = isset($datas['links']['url'])?$aws->getUrl($datas['links']['url']):'';
            }
            $this->assign('list', $list);
        }
        $this->assign('where', $where);
        $this->assign('type', $type);

        return $this->fetch();
    }

    public function sun_entertainment_edit_commit()
    {
//        var_dump($_FILES);
        $aws = new Aws();
        $data = $this->request->post();
        $id = $data['id'];

        if (isset($id) && !empty($id)) {
            $enter = Entertainment::find(['id'=>$id]);
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $file_name = $_FILES['file']['name'];
                $ext = pathinfo($file_name)['extension'];
                $file_type = 0;

                $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$file_type);

                $list = new Links();
                $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                $list->description = isset($data['description'])?$data['description']:'';
                $list->url = $url;
                $list->author_id = 0;
                $list->type = $file_type;
                if($list->save()){
                    $enter->link_id = $list->id;
                }
            }
            if($enter){
                $enter->type = $data['type'];

                if($enter->save()){
                    Content::update(['data'=>$data['title_hk']],['owner_id'=>$id,'language'=>0,'owner_type'=>'entertainment','owner_field'=>'Title']);
                    Content::update(['data'=>$data['title_cn']],['owner_id'=>$id,'language'=>1,'owner_type'=>'entertainment','owner_field'=>'Title']);
                    Content::update(['data'=>$data['title_en']],['owner_id'=>$id,'language'=>2,'owner_type'=>'entertainment','owner_field'=>'Title']);
                    Content::update(['data'=>$data['meta_hk']],['owner_id'=>$id,'language'=>0,'owner_type'=>'entertainment','owner_field'=>'Meta']);
                    Content::update(['data'=>$data['meta_cn']],['owner_id'=>$id,'language'=>1,'owner_type'=>'entertainment','owner_field'=>'Meta']);
                    Content::update(['data'=>$data['meta_en']],['owner_id'=>$id,'language'=>2,'owner_type'=>'entertainment','owner_field'=>'Meta']);
                    return Result::success('成功');
                }
            }
            return Result::error('失敗');
        } else {
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $file_name = $_FILES['file']['name'];
                $ext = pathinfo($file_name)['extension'];
                $file_type = 0;

                $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$file_type);

                $list = new Links();
                $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                $list->description = isset($data['description'])?$data['description']:'';
                $list->url = $url;
                $list->author_id = 0;
                $list->type = $file_type;
                if(!$list->save()){
                    return Result::error('请上传图片');
                }else{
                    $enter = new Entertainment();
                    $enter->type = $data['type'];
                    $enter->link_id = $list->id;
                    if($enter->save()){
                        $detail = [
                            [
                                'language'=>0,
                                'title'=>$data['title_hk'],
                                'content'=>$data['meta_hk'],
                            ],
                            [
                                'language'=>1,
                                'title'=>$data['title_cn'],
                                'content'=>$data['meta_cn'],
                            ],
                            [
                                'language'=>2,
                                'title'=>$data['title_en'],
                                'content'=>$data['meta_en'],
                            ],
                        ];
                        foreach ($detail as $k=>$v){
                            $content = new Content();
                            $content->data = $v['title'];
                            $content->language = $v['language'];
                            $content->owner_id = $enter->id;
                            $content->owner_type = 'entertainment';
                            $content->owner_field = 'Title';
                            $content->save();
                            $content = new Content();
                            $content->data = $v['content'];
                            $content->language = $v['language'];
                            $content->owner_id = $enter->id;
                            $content->owner_type = 'entertainment';
                            $content->owner_field = 'Meta';
                            $content->save();
                        }
                        return Result::success('成功');
                    }
                    return Result::error('失敗');
                }
            }
            return Result::error('请上传图片');
        }

    }

    public function sun_entertainment_delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Entertainment::update(['deleted'=>1],['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }



    public function sun_banner_edit()
    {
        $data = $this->request->get();
        $this->assign('id', $data['id']);//banner_id--->link_id
        $this->assign('type', $data['type']); //1影视娱乐，2环球旅游，3餐饮体验，4奢华购物，5度假村

        return $this->fetch();
    }

    public function sun_banner_commit(){
        $aws = new Aws();

        $id = $this->request->post('id');
        $type = $this->request->post('type');

        if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
            $file_name = $_FILES['file']['name'];
            $ext = pathinfo($file_name)['extension'];
            $file_type = 0;

            $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$file_type);

            $list = new Links();
            $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
            $list->description = isset($data['description'])?$data['description']:'';
            $list->url = $url;
            $list->author_id = 0;
            $list->type = $file_type;
            if(!$list->save()){
                return Result::error('失敗');
            }else{
                $link_id = $list->id;
            }
        }
        if($type==1){
            $owner_type = 'entertainment';
        }elseif ($type==2){
            $owner_type = 'travel';
        }elseif ($type==3){
            $owner_type = 'food';
        }elseif ($type==4){
            $owner_type = 'luxe';
        }elseif ($type==5){
            $owner_type = 'resort';
        }

        $owner_field = 'all';
        if (isset($id) && !empty($id)) {
            $enter = LinkTarget::find(['id'=>$id]);
            if($enter){
                $enter->link_id = $link_id;
                if($enter->save()){
                    return Result::success('成功');
                }
            }

        } else {
            $enter = new LinkTarget();
            $enter->owner_id = 0;
            $enter->owner_type = $owner_type;
            $enter->owner_field = $owner_field;
            $enter->link_id = $link_id;
            $enter->type = 0;
            if($enter->save()){
                return Result::success('成功');
            }
        }
        return Result::error('失敗');

    }

    public function sun_banner_data(){
        $aws = new Aws();

        $type = $this->request->post('type');
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $offset = ($page - 1) * $limit;

        if($type==1){
            $owner_type = 'entertainment';
        }elseif ($type==2){
            $owner_type = 'travel';
        }elseif ($type==3){
            $owner_type = 'food';
        }elseif ($type==4){
            $owner_type = 'luxe';
        }elseif ($type==5){
            $owner_type = 'resort';
        }

        $datas = LinkTarget::where(['owner_type'=>$owner_type])->field('id,link_id')->with('getLink')->limit($offset,$limit)->select()->toArray();
        $count = LinkTarget::where(['owner_type'=>$owner_type])->count();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['url'] = isset($datas[$k]['get_link']['url'])?$aws->getUrl($datas[$k]['get_link']['url']):'';
                $datas[$k]['name'] = isset($datas[$k]['get_link']['name'])?$datas[$k]['get_link']['name']:'';
                unset($datas[$k]['get_link']);
            }
        }
        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }


    public function sun_banner_delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = LinkTarget::destroy(['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }


    /**
     * 環球旅遊
     */
    public function sun_travel()
    {
        return $this->fetch();
    }

    public function sun_travel_data(){
        $aws = new Aws();
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $offset = ($page - 1) * $limit;

        $datas = LinkTarget::where(['owner_type'=>'travel'])->field('id,link_id')->with('getLink')->limit($offset,$limit)->select()->toArray();
        $count = LinkTarget::where(['owner_type'=>'travel'])->count();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['url'] = isset($datas[$k]['get_link']['url'])?$aws->getUrl($datas[$k]['get_link']['url']):'';
                $datas[$k]['name'] = isset($datas[$k]['get_link']['name'])?$datas[$k]['get_link']['name']:'';
                unset($datas[$k]['get_link']);
            }
        }
        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }

    public function sun_travel_edit()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {


            } else {
                $link = new Links();
                $link->url = 'http://backend.kunworld.cn//backupload/common/20180802/22ecadb2d2c2efd3bf4fe8e77a7737c2.png';
                $link->name = '';
                $link->description = '';
                $link->author_id = 0;
                $link->type = 0;
                if($link->save()){
                    $enter = new LinkTarget();
                    $enter->owner_id = 0;
                    $enter->owner_type = 'travel';
                    $enter->owner_field = 'all';
                    $enter->link_id = $link->id;
                    $enter->type = 0;
                    if($enter->save()){
                        return Result::success('成功');
                    }
                }
                return Result::error('失敗');
            }
        }

        $id = $this->request->get('id');
        if($id){
            $datas = LinkTarget::where(['id'=>$id])->field('id,link_id')->find()->toArray();
            $this->assign('list', $datas);
        }
        return $this->fetch();
    }

    public function sun_travel_delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = LinkTarget::destroy(['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }



    /**
     * 餐飲體驗
     */
    public function sun_food()
    {
        return $this->fetch();
    }

    public function sun_food_data(){
        $aws = new Aws();

        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');

        $offset = ($page - 1) * $limit;

        $map[]=['deleted','=',0];

        $datas = Food::where($map)->field('id,url,link_id')->with('links,getContent')->limit($offset,$limit)->select()->toArray();
        $count = Food::where($map)->count();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['img_url'] = isset($datas[$k]['links']['url'])?$aws->getUrl($datas[$k]['links']['url']):'';
                $datas[$k]['name'] = isset($datas[$k]['links']['name'])?$datas[$k]['links']['name']:'';
                if($datas[$k]['get_content']){
                    foreach ($datas[$k]['get_content'] as $ck=>$cv){
                        if($datas[$k]['get_content'][$ck]['language']==0){
                            if($datas[$k]['get_content'][$ck]['owner_field']=='Title'){
                                $datas[$k]['title'] = $datas[$k]['get_content'][$ck]['data'];
                            }
                            if($datas[$k]['get_content'][$ck]['owner_field']=='Meta'){
                                $datas[$k]['meta'] = $datas[$k]['get_content'][$ck]['data'];
                            }
                        }
                    }
                }
                unset($datas[$k]['get_content']);
                unset($datas[$k]['links']);
            }
        }

        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }

    public function sun_food_edit()
    {
        $aws = new Aws();
        $id = $this->request->get('id');
        $where= $this->request->get('where','');

        if($id){
            $datas = Food::where(['id'=>$id])->field('id,url,link_id')->with('links,getContent')->find()->toArray();
            $list=[];

            if($datas){
                $list['id'] = $datas['id'];
                $list['url'] = $datas['url'];
                $list['img_url'] = '';
                $list['title_hk'] = '';
                $list['meta_hk'] = '';
                $list['text_hk'] = '';
                $list['title_cn'] = '';
                $list['meta_cn'] = '';
                $list['text_cn'] = '';
                $list['title_en'] = '';
                $list['meta_en'] = '';
                $list['text_en'] = '';
                if($datas['get_content']){
                    foreach ($datas['get_content'] as $ck=>$cv){
                        if($datas['get_content'][$ck]['language']==0){
                            if($datas['get_content'][$ck]['owner_field']=='Title'){
                                $list['title_hk'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='Meta'){
                                $list['meta_hk'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='AltText'){
                                $list['text_hk'] = $datas['get_content'][$ck]['data'];
                            }
                        }elseif($datas['get_content'][$ck]['language']==1){
                            if($datas['get_content'][$ck]['owner_field']=='Title'){
                                $list['title_cn'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='Meta'){
                                $list['meta_cn'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='AltText'){
                                $list['text_cn'] = $datas['get_content'][$ck]['data'];
                            }
                        }elseif ($datas['get_content'][$ck]['language']==2){
                            if($datas['get_content'][$ck]['owner_field']=='Title'){
                                $list['title_en'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='Meta'){
                                $list['meta_en'] = $datas['get_content'][$ck]['data'];
                            }
                            if($datas['get_content'][$ck]['owner_field']=='AltText'){
                                $list['text_en'] = $datas['get_content'][$ck]['data'];
                            }
                        }
                    }
                }
                $list['img_url'] = isset($datas['links']['url'])?$aws->getUrl($datas['links']['url']):'';
            }
            $this->assign('list', $list);
        }
        $this->assign('where', $where);

        return $this->fetch();
    }

    public function sun_food_edit_commit()
    {
        $aws = new Aws();
        $data = $this->request->post();
        $id = $data['id'];

        if (isset($id) && !empty($id)) {
            $enter = Food::find(['id'=>$id]);
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $file_name = $_FILES['file']['name'];
                $ext = pathinfo($file_name)['extension'];
                $file_type = 0;

                $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$file_type);

                $list = new Links();
                $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                $list->description = isset($data['description'])?$data['description']:'';
                $list->url = $url;
                $list->author_id = 0;
                $list->type = $file_type;
                if($list->save()){
                    $enter->link_id = $list->id;
                }
            }
            if($enter){
                $enter->url = $data['url'];
                if($enter->save()){
                    Content::update(['data'=>$data['title_hk']],['owner_id'=>$id,'language'=>0,'owner_type'=>'food','owner_field'=>'Title']);
                    Content::update(['data'=>$data['title_cn']],['owner_id'=>$id,'language'=>1,'owner_type'=>'food','owner_field'=>'Title']);
                    Content::update(['data'=>$data['title_en']],['owner_id'=>$id,'language'=>2,'owner_type'=>'food','owner_field'=>'Title']);
                    Content::update(['data'=>$data['meta_hk']],['owner_id'=>$id,'language'=>0,'owner_type'=>'food','owner_field'=>'Meta']);
                    Content::update(['data'=>$data['meta_cn']],['owner_id'=>$id,'language'=>1,'owner_type'=>'food','owner_field'=>'Meta']);
                    Content::update(['data'=>$data['meta_en']],['owner_id'=>$id,'language'=>2,'owner_type'=>'food','owner_field'=>'Meta']);
                    Content::update(['data'=>$data['text_hk']],['owner_id'=>$id,'language'=>0,'owner_type'=>'food','owner_field'=>'AltText']);
                    Content::update(['data'=>$data['text_cn']],['owner_id'=>$id,'language'=>1,'owner_type'=>'food','owner_field'=>'AltText']);
                    Content::update(['data'=>$data['text_en']],['owner_id'=>$id,'language'=>2,'owner_type'=>'food','owner_field'=>'AltText']);
                    return Result::success('成功');
                }
            }
            return Result::error('失敗');
        } else {
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $file_name = $_FILES['file']['name'];
                $ext = pathinfo($file_name)['extension'];
                $file_type = 0;

                $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$file_type);

                $list = new Links();
                $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                $list->description = isset($data['description'])?$data['description']:'';
                $list->url = $url;
                $list->author_id = 0;
                $list->type = $file_type;
                if(!$list->save()){
                    return Result::error('请上传图片');
                }else{
                    $enter = new Food();
                    $enter->url = $data['url'];
                    $enter->link_id = $list->id;
                    if($enter->save()){
                        $detail = [
                            [
                                'language'=>0,
                                'title'=>$data['title_hk'],
                                'content'=>$data['meta_hk'],
                                'desc'=>$data['text_hk'],
                            ],
                            [
                                'language'=>1,
                                'title'=>$data['title_cn'],
                                'content'=>$data['meta_cn'],
                                'desc'=>$data['text_cn'],
                            ],
                            [
                                'language'=>2,
                                'title'=>$data['title_en'],
                                'content'=>$data['meta_en'],
                                'desc'=>$data['text_en'],
                            ],
                        ];

                        foreach ($detail as $k=>$v){
                            $content = new Content();
                            $content->data = $v['title'];
                            $content->language = $v['language'];
                            $content->owner_id = $enter->id;
                            $content->owner_type = 'food';
                            $content->owner_field = 'Title';
                            $content->save();
                            $content = new Content();
                            $content->data = $v['content'];
                            $content->language = $v['language'];
                            $content->owner_id = $enter->id;
                            $content->owner_type = 'food';
                            $content->owner_field = 'Meta';
                            $content->save();
                            $content = new Content();
                            $content->data = $v['desc'];
                            $content->language = $v['language'];
                            $content->owner_id = $enter->id;
                            $content->owner_type = 'food';
                            $content->owner_field = 'AltText';
                            $content->save();
                        }
                        return Result::success('成功');
                    }
                    return Result::error('失敗');
                }
            }
            return Result::error('请上传图片');
        }

    }

    public function sun_food_delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Food::update(['deleted'=>1],['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }



    /**
     * 奢華購物
     */
    public function sun_luxe()
    {
        return $this->fetch();
    }

    public function sun_luxe_data(){
        $aws = new Aws();

        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $offset = ($page - 1) * $limit;

        $datas = LinkTarget::where(['owner_type'=>'luxe'])->field('id,link_id')->with('getLink')->limit($offset,$limit)->select()->toArray();
        $count = LinkTarget::where(['owner_type'=>'luxe'])->count();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['url'] = isset($datas[$k]['links']['url'])?$aws->getUrl($datas[$k]['links']['url']):'';
                $datas[$k]['name'] = isset($datas[$k]['links']['name'])?$datas[$k]['links']['name']:'';
                unset($datas[$k]['get_link']);
            }
        }
        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }


    public function sun_luxe_edit()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {


            } else {
                $link = new Links();
                $link->url = 'http://backend.kunworld.cn//backupload/common/20180802/22ecadb2d2c2efd3bf4fe8e77a7737c2.png';
                $link->name = '';
                $link->description = '';
                $link->author_id = 0;
                $link->type = 0;
                if($link->save()){
                    $enter = new LinkTarget();
                    $enter->owner_id = 0;
                    $enter->owner_type = 'luxe';
                    $enter->owner_field = 'all';
                    $enter->link_id = $link->id;
                    $enter->type = 0;
                    if($enter->save()){
                        return Result::success('成功');
                    }
                }
                return Result::error('失敗');
            }
        }

        $id = $this->request->get('id');
        if($id){
            $datas = LinkTarget::where(['id'=>$id])->field('id,link_id')->find()->toArray();
            $this->assign('list', $datas);
        }
        return $this->fetch();
    }


    public function sun_luxet_delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = LinkTarget::destroy(['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }


    /**
     * 渡假村
     */
    public function sun_resort()
    {
        return $this->fetch();
    }

    public function sun_resort_data(){
        $aws = new Aws();

        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $offset = ($page - 1) * $limit;

        $datas = LinkTarget::where(['owner_type'=>'resort'])->field('id,link_id')->with('getLink')->limit($offset,$limit)->select()->toArray();
        $count = LinkTarget::where(['owner_type'=>'resort'])->count();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['url'] = isset($datas[$k]['links']['url'])?$aws->getUrl($datas[$k]['links']['url']):'';
                $datas[$k]['name'] = isset($datas[$k]['links']['name'])?$datas[$k]['links']['name']:'';
                unset($datas[$k]['get_link']);
            }
        }
        echo json_encode([
            'code'=>0,
            'count'=>$count,
            'data'=>$datas,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
    }

    public function sun_resort_edit()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {


            } else {
                $link = new Links();
                $link->url = 'http://backend.kunworld.cn//backupload/common/20180802/22ecadb2d2c2efd3bf4fe8e77a7737c2.png';
                $link->name = '';
                $link->description = '';
                $link->author_id = 0;
                $link->type = 0;
                if($link->save()){
                    $enter = new LinkTarget();
                    $enter->owner_id = 0;
                    $enter->owner_type = 'resort';
                    $enter->owner_field = 'all';
                    $enter->link_id = $link->id;
                    $enter->type = 0;
                    if($enter->save()){
                        return Result::success('成功');
                    }
                }
                return Result::error('失敗');
            }
        }

        $id = $this->request->get('id');
        if($id){
            $datas = LinkTarget::where(['id'=>$id])->field('id,link_id')->find()->toArray();
            $this->assign('list', $datas);
        }
        return $this->fetch();
    }

    public function sun_resort_delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = LinkTarget::destroy(['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }

}