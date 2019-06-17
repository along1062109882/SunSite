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
use app\index\model\CategoryDetails;
use app\index\model\Content;
use app\index\model\Links;
use app\index\model\PostCategories;
use app\index\model\PostDetails;
use app\index\model\Posts;
use Aws\Aws;

class Category extends Common
{
    //分类
    public function classify(){

        return $this->fetch();
    }

    public function get_classify_data(){
        $data = Categories::where(['deleted'=>0])->order('seq','asc')->with('getContentDetail')->select()->toArray();
        if($data){
            foreach($data as $k=>$v){
                $data[$k]['name_hk'] = '';
                $data[$k]['name_cn'] = '';
                $data[$k]['name_en'] = '';
                if($data[$k]['get_content_detail']){
                    foreach ($data[$k]['get_content_detail'] as $content_k=>$content_v){
                        if($data[$k]['get_content_detail'][$content_k]['owner_field'] == 'Title'){
                            if($data[$k]['get_content_detail'][$content_k]['language'] == 0){
                                $data[$k]['name_hk'] = $data[$k]['get_content_detail'][$content_k]['data'];
                            }elseif($data[$k]['get_content_detail'][$content_k]['language'] == 1){
                                $data[$k]['name_cn'] = $data[$k]['get_content_detail'][$content_k]['data'];
                            }elseif($data[$k]['get_content_detail'][$content_k]['language'] == 2){
                                $data[$k]['name_en'] = $data[$k]['get_content_detail'][$content_k]['data'];
                            }
                        }
                    }
                }
                unset($data[$k]['get_content_detail']);
            }
        }
        echo json_encode([
            'code'=>0,
            'count'=>count($data),
            'data'=>$data,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);    }

    public function classifyEdit(){
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {
                //编辑
                $posts = Categories::find(['id'=>$data['id']]);
                $posts->slug = $data['slug'];
                $posts->seq = intval($data['seq']);
                if($posts->save()){
                    CategoryDetails::update(['name'=>$data['name_hk']],['category_id'=>$id,'language'=>0]);
                    CategoryDetails::update(['name'=>$data['name_cn']],['category_id'=>$id,'language'=>1]);
                    CategoryDetails::update(['name'=>$data['name_en']],['category_id'=>$id,'language'=>2]);
//                    return Result::success('成功','',$a);

                    Content::update(['data'=>$data['name_hk']],['owner_id'=>$id,'language'=>0,'owner_field'=>'Title']);
                    Content::update(['data'=>$data['name_cn']],['owner_id'=>$id,'language'=>1,'owner_field'=>'Title']);
                    Content::update(['data'=>$data['name_en']],['owner_id'=>$id,'language'=>2,'owner_field'=>'Title']);

                    Content::update(['data'=>$data['title_hk']],['owner_id'=>$id,'language'=>0,'owner_field'=>'BannerTitle']);
                    Content::update(['data'=>$data['title_cn']],['owner_id'=>$id,'language'=>1,'owner_field'=>'BannerTitle']);
                    Content::update(['data'=>$data['title_en']],['owner_id'=>$id,'language'=>2,'owner_field'=>'BannerTitle']);

                    Content::update(['data'=>$data['content_hk']],['owner_id'=>$id,'language'=>0,'owner_field'=>'BannerContent']);
                    Content::update(['data'=>$data['content_cn']],['owner_id'=>$id,'language'=>1,'owner_field'=>'BannerContent']);
                    Content::update(['data'=>$data['content_en']],['owner_id'=>$id,'language'=>2,'owner_field'=>'BannerContent']);

                    return Result::success('成功');
                }else{
                    return Result::error('失敗');
                }
            } else {

                //添加
                $posts = new Categories();
                $posts->parent_id = intval($data['parent_id']);
                $posts->slug = $data['slug'];
                $posts->seq = intval($data['seq']);
                if($posts->save()){
                    $detail = [
                        [
                            'language'=>0,
                            'name'=>$data['name_hk'],
                            'title'=>$data['title_hk'],
                            'content'=>$data['content_hk'],
                        ],
                        [
                            'language'=>1,
                            'name'=>$data['name_cn'],
                            'title'=>$data['title_cn'],
                            'content'=>$data['content_cn'],
                        ],
                        [
                            'language'=>2,
                            'name'=>$data['name_en'],
                            'title'=>$data['title_en'],
                            'content'=>$data['content_en'],
                        ],
                    ];
                    foreach ($detail as $dk=>$dv){
                        $p_cate = new CategoryDetails();
                        $p_cate->category_id = $posts->id;
                        $p_cate->language = $dv['language'];
                        $p_cate->name = $dv['name'];
                        $p_cate->save();
                        $p_detail = new Content();
                        $p_detail->data = $dv['name'];
                        $p_detail->language = $dv['language'];
                        $p_detail->owner_id = $posts->id;
                        $p_detail->owner_type = 'categories';
                        $p_detail->owner_field = 'Title';
                        $p_detail->save();
                        $p_detail = new Content();
                        $p_detail->data = $dv['title'];
                        $p_detail->language = $dv['language'];
                        $p_detail->owner_id = $posts->id;
                        $p_detail->owner_type = 'categories';
                        $p_detail->owner_field = 'BannerTitle';
                        $p_detail->save();
                        $p_detail = new Content();
                        $p_detail->data = $dv['content'];
                        $p_detail->language = $dv['language'];
                        $p_detail->owner_id = $posts->id;
                        $p_detail->owner_type = 'categories';
                        $p_detail->owner_field = 'BannerContent';
                        $p_detail->save();
                    }

                    return Result::success('成功');
                }else{
                    return Result::error('失敗');
                }
            }
        }

        $id = $this->request->get('id');
        $llist = Categories::where(['id'=>$id])->order('seq','asc')->with('getContentDetail,backendSon')->select()->toArray();

        $list=[];
        if($llist){
            $data = $llist[0];
            $list['id'] = $data['id'];
            $list['parent_id'] = $data['parent_id'];
            $list['slug'] = $data['slug'];
            $list['seq'] = $data['seq'];
            $list['name_hk'] = '';
            $list['name_cn'] = '';
            $list['name_en'] = '';
            $list['title_hk'] = '';
            $list['title_cn'] = '';
            $list['title_en'] = '';
            $list['content_hk'] = '';
            $list['content_cn'] = '';
            $list['content_en'] = '';
            if($data['get_content_detail']){
                foreach ($data['get_content_detail'] as $content_k=>$content_v){
                    if($data['get_content_detail'][$content_k]['owner_field'] == 'Title'){
                        if($data['get_content_detail'][$content_k]['language'] == 0){
                            $list['name_hk'] = $data['get_content_detail'][$content_k]['data'];
                        }elseif($data['get_content_detail'][$content_k]['language'] == 1){
                            $list['name_cn'] = $data['get_content_detail'][$content_k]['data'];
                        }elseif($data['get_content_detail'][$content_k]['language'] == 2){
                            $list['name_en'] = $data['get_content_detail'][$content_k]['data'];
                        }
                    }elseif($data['get_content_detail'][$content_k]['owner_field'] == 'BannerTitle'){
                        if($data['get_content_detail'][$content_k]['language'] == 0){
                            $list['title_hk'] = $data['get_content_detail'][$content_k]['data'];
                        }elseif($data['get_content_detail'][$content_k]['language'] == 1){
                            $list['title_cn'] = $data['get_content_detail'][$content_k]['data'];
                        }elseif($data['get_content_detail'][$content_k]['language'] == 2){
                            $list['title_en'] = $data['get_content_detail'][$content_k]['data'];
                        }
                    }elseif($data['get_content_detail'][$content_k]['owner_field'] == 'BannerContent'){
                        if($data['get_content_detail'][$content_k]['language'] == 0){
                            $list['content_hk'] = $data['get_content_detail'][$content_k]['data'];
                        }elseif($data['get_content_detail'][$content_k]['language'] == 1){
                            $list['content_cn'] = $data['get_content_detail'][$content_k]['data'];
                        }elseif($data['get_content_detail'][$content_k]['language'] == 2){
                            $list['content_en'] = $data['get_content_detail'][$content_k]['data'];
                        }
                    }
                }
            }
        }

        $datas = Categories::where(['parent_id'=>0,'deleted'=>0])->field('*')->order('seq','asc')->with('detail')->select()->toArray();
        $grouplist=[];

        if($datas){
            foreach ($datas as $k=>$v){
                $group['id'] = $datas[$k]['id'];
                $group['title'] = $datas[$k]['detail']?$datas[$k]['detail'][0]['name']:'';
                $grouplist[] = $group;
            }
        }
        $this->assign('grouplist', $grouplist);

        $this->assign('list', $list);
        return $this->fetch();
    }
    public function classifyDelete(){
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Categories::update(['deleted'=>1],['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }

    /**
     * 集團動態列表
     */
    public function news()
    {
        $datas = Categories::where(['slug'=>'news','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $groups=[];
        if($datas){
            $data = $datas[0]['son'];
            foreach ($data as $k=>$v){
                if($data[$k]['detail']){
                    $group['id'] = $data[$k]['id'];
                    $group['title'] = $data[$k]['detail']?$data[$k]['detail'][0]['name']:'';
                    $groups[] = $group;
                }
            }
        }

        $this->assign('grouplist', $groups);

        return $this->fetch();
    }

    public function get_news_data(){
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $start_time = $this->request->post('start_time', '');
        $end_time = $this->request->post('end_time', '');
        $type = $this->request->post('type', 0,'intval');
        $title = $this->request->post('title', '');

        $grouplist=[];
        $datas = Posts::where(['post_type'=>'news','deleted'=>0])->order('publish_time','desc')->with('getCategory,detail')->select()->toArray();
        if($datas){
            foreach ($datas as $k=>$v){
                if($datas[$k]['get_category'] && $datas[$k]['get_category']['category']['deleted']==0){
                    if($datas[$k]['detail']){
                        $join['id'] = $datas[$k]['detail'][0]['post_id'];
                        $join['parent_slug'] = isset($datas[$k]['get_category']['category']['detail'])&&!empty($datas[$k]['get_category']['category']['detail'])?$datas[$k]['get_category']['category']['detail'][0]['name']:'';
                        $join['category_id'] = $datas[$k]['get_category']['category_id'];
                        $join['slug'] = $datas[$k]['slug'];
                        $join['status'] = $datas[$k]['status'];
                        $join['created'] = $datas[$k]['publish_time'];
                        $join['name'] = $datas[$k]['detail'][0]['title'];
                        $grouplist[] = $join;
                    }
                }
            }
        }

        $search=[];
        if($grouplist){
            foreach ($grouplist as $gk=>$gv){
                if($start_time && $end_time && $type && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && $end_time && $type && $title){
                    if($grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && $start_time && $type && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($type) && $end_time && $start_time && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && $end_time && $type && $start_time){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($end_time) && $type && $title){
                    if($grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($type) && $end_time && $title){
                    if($grouplist[$gk]['created'] <= $end_time && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($title) && $type && $end_time){
                    if( $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($type) && $start_time && $title){
                    if( $start_time <= $grouplist[$gk]['created'] && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($title) && $start_time && $type){
                    if( $start_time <= $grouplist[$gk]['created'] && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($type) && $start_time && $end_time){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($title) && empty($type) && $start_time){
                    if( $start_time <= $grouplist[$gk]['created']){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($start_time) && empty($type) && $end_time){
                    if( $grouplist[$gk]['created'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($start_time) && empty($end_time) && $type){
                    if( $grouplist[$gk]['category_id']==$type ){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($start_time) && empty($type) && $title){
                    if( stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }
            }
        }
        if($start_time || $end_time || $type || $title){
            $grouplist = $search;
        }
        $grouplist = $grouplist?$this->order($grouplist,'created'):[];
        $grouplist2 = array_slice($grouplist,$limit*($page-1),$limit);
        echo json_encode([
            'code'=>0,
            'count'=>count($grouplist),
            'data'=>$grouplist2,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
//        return show($grouplist2, 0, '', ['count' => count($grouplist)]);
    }
    /**
     * 添加、编辑集團動態
     */
    public function newsEdit()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {
                $check = Posts::find(['slug'=>$data['slug']]);
                if($check && $check['id'] != $data['id']){
                    return Result::error('當前slug已存在');
                }
                //编辑
                $posts = Posts::find(['id'=>$data['id']]);
                $posts->post_type = 'news';
                $posts->slug = $data['slug'];
                $posts->status = intval($data['status']);
                $posts->publish_time = $data['time'];
                if($posts->save()){

                    $category = PostCategories::where(['post_id'=>$id])->find();
                    if($category && $category['category_id'] == intval($data['type'])){

                    }else{
                        $category->category_id = intval($data['type']);
                        $category->save();
                    }
//                    return $category;
                    $hk_detail = PostDetails::where(['post_id'=>$id,'language'=>0])->find();
                    $hk_detail->keywords = $data['keyword_hk'];
                    $hk_detail->description = $data['desc_hk'];
                    $hk_detail->title = $data['title_hk'];
                    $hk_detail->excerpt = $data['show_hk'];
                    $hk_detail->content = $data['content_hk'];
                    $hk_detail->save();

                    $cn_detail = PostDetails::where(['post_id'=>$id,'language'=>1])->find();
                    $cn_detail->keywords = $data['keyword_cn'];
                    $cn_detail->description = $data['desc_cn'];
                    $cn_detail->title = $data['title_cn'];
                    $cn_detail->excerpt = $data['show_cn'];
                    $cn_detail->content = $data['content_cn'];
                    $cn_detail->save();

                    $en_detail = PostDetails::where(['post_id'=>$id,'language'=>2])->find();
                    $en_detail->keywords = $data['keyword_en'];
                    $en_detail->description = $data['desc_en'];
                    $en_detail->title = $data['title_en'];
                    $en_detail->excerpt = $data['show_en'];
                    $en_detail->content = $data['content_en'];
                    $en_detail->save();
                    return Result::success('成功');
                }else{
                    return Result::error('失敗');
                }
            } else {
                $check = Posts::find(['slug'=>$data['slug']]);
                if($check){
                    return Result::error('當前slug已存在');
                }
                //添加
                $posts = new Posts();
                $posts->post_type = 'news';
                $posts->cover_link_id = 0;
                $posts->square_link_id = 0;
                $posts->slug = $data['slug'];
                $posts->status = intval($data['status']);
                $posts->publish_time = $data['time'];
                if($posts->save()){
                    $detail = [
                        [
                            'language'=>0,
                            'title'=>$data['title_hk'],
                            'content'=>$data['content_hk'],
                            'excerpt'=>$data['show_hk'],
                            'keywords'=>$data['keyword_hk'],
                            'description'=>$data['desc_hk'],
                        ],
                        [
                            'language'=>1,
                            'title'=>$data['title_cn'],
                            'content'=>$data['content_cn'],
                            'excerpt'=>$data['show_cn'],
                            'keywords'=>$data['keyword_cn'],
                            'description'=>$data['desc_cn'],
                        ],
                        [
                            'language'=>2,
                            'title'=>$data['title_en'],
                            'content'=>$data['content_en'],
                            'excerpt'=>$data['show_en'],
                            'keywords'=>$data['keyword_en'],
                            'description'=>$data['desc_en'],
                        ],
                    ];
                    $p_cate = new PostCategories();
                    $p_cate->post_id = $posts->id;
                    $p_cate->category_id = intval($data['type']);
                    if($p_cate->save()){
                        foreach ($detail as $dk=>$dv){
                            $p_detail = new PostDetails();
                            $p_detail->post_id = $posts->id;
                            $p_detail->author_id = 0;
                            $p_detail->language = $dv['language'];
                            $p_detail->keywords = $dv['keywords'];
                            $p_detail->description = $dv['description'];
                            $p_detail->title = $dv['title'];
                            $p_detail->excerpt = $dv['excerpt'];
                            $p_detail->content = $dv['content'];
                            $a = $p_detail->save();
                        }
                    }

                    return Result::success('删除成功');
                }else{
                    return Result::error('失敗');
                }
            }
        }

        $id = $this->request->get('id');
        $llist = Posts::where(['id'=>$id])->with('detail,getCategory')->select()->toArray();
        $list=[];
        if($llist){
            $list['id'] = $llist[0]['id'];
            $list['type'] = $llist[0]['get_category']['category_id'];
            $list['slug'] = $llist[0]['slug'];
            $list['status'] = $llist[0]['status'];
            $list['time'] = $llist[0]['publish_time'];
            $list['title_hk'] = '';
            $list['content_hk'] = '';
            $list['show_hk'] = '';
            $list['keyword_hk'] = '';
            $list['desc_hk'] = '';

            $list['title_cn'] = '';
            $list['content_cn'] = '';
            $list['show_cn'] = '';
            $list['keyword_cn'] = '';
            $list['desc_cn'] = '';

            $list['title_en'] = '';
            $list['content_en'] = '';
            $list['show_en'] = '';
            $list['keyword_en'] = '';
            $list['desc_en'] = '';
            if($llist[0]['detail']){
                $list['title_hk'] = $llist[0]['detail'][0]['title'];
                $list['content_hk'] = $llist[0]['detail'][0]['content'];
                $list['show_hk'] = $llist[0]['detail'][0]['excerpt'];
                $list['keyword_hk'] = $llist[0]['detail'][0]['keywords'];
                $list['desc_hk'] = $llist[0]['detail'][0]['description'];

                $list['title_cn'] = $llist[0]['detail'][1]['title'];
                $list['content_cn'] = $llist[0]['detail'][1]['content'];
                $list['show_cn'] = $llist[0]['detail'][1]['excerpt'];
                $list['keyword_cn'] = $llist[0]['detail'][1]['keywords'];
                $list['desc_cn'] = $llist[0]['detail'][1]['description'];

                $list['title_en'] = $llist[0]['detail'][2]['title'];
                $list['content_en'] = $llist[0]['detail'][2]['content'];
                $list['show_en'] = $llist[0]['detail'][2]['excerpt'];
                $list['keyword_en'] = $llist[0]['detail'][2]['keywords'];
                $list['desc_en'] = $llist[0]['detail'][2]['description'];
            }

        }

        $datas = Categories::where(['slug'=>'news','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $grouplist=[];

        if($datas){
            $data = $datas[0]['son'];
            foreach ($data as $k=>$v){
                if($data[$k]['detail']){
                    $group['id'] = $data[$k]['id'];
                    $group['title'] = $data[$k]['detail']?$data[$k]['detail'][0]['name']:'';
                    $grouplist[] = $group;
                }
            }
        }
        $this->assign('grouplist', $grouplist);
        $this->assign('list', $list);


        return $this->fetch();
    }





    /**
     * 删除集團動態
     */
    public function newsDelete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Posts::update(['deleted'=>1],['id'=>$id]);
            if($up){
                PostDetails::update(['deleted'=>1],['post_id'=>$id]);
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }


    /**
     * 集團业务列表
     */
    public function business()
    {
        $datas = Categories::where(['slug'=>'group-business','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $groups=[];
        if($datas){
            $data = $datas[0]['son'];
            foreach ($data as $k=>$v){
                if($data[$k]['detail']){
                    $group['id'] = $data[$k]['id'];
                    $group['title'] = $data[$k]['detail']?$data[$k]['detail'][0]['name']:'';
                    $groups[] = $group;
                }
            }
        }

        $this->assign('grouplist', $groups);

        return $this->fetch();
    }

    public function get_business_data(){
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $start_time = $this->request->post('start_time', '');
        $end_time = $this->request->post('end_time', '');
        $type = $this->request->post('type', 0,'intval');
        $title = $this->request->post('title', '');

        $search=[];
        $grouplist=[];
        $datas = Posts::where(['post_type'=>'group-business','deleted'=>0])->order('publish_time','desc')->with('getCategory,detail')->select()->toArray();
        if($datas){
            foreach ($datas as $k=>$v){
                if($datas[$k]['get_category'] && $datas[$k]['get_category']['category']['deleted']==0){
                    if($datas[$k]['detail']){
                        $join['id'] = $datas[$k]['detail'][0]['post_id'];
                        $join['parent_slug'] = isset($datas[$k]['get_category']['category']['detail'])&&!empty($datas[$k]['get_category']['category']['detail'])?$datas[$k]['get_category']['category']['detail'][0]['name']:'';
                        $join['category_id'] = $datas[$k]['get_category']['category_id'];
                        $join['slug'] = $datas[$k]['slug'];
                        $join['status'] = $datas[$k]['status'];
                        $join['created'] = $datas[$k]['publish_time'];
                        $join['name'] = $datas[$k]['detail'][0]['title'];
                        $grouplist[] = $join;
                    }
                }
            }
        }


        if($grouplist){
            foreach ($grouplist as $gk=>$gv){
                if($start_time && $end_time && $type && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && $end_time && $type && $title){
                    if($grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && $start_time && $type && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($type) && $end_time && $start_time && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && $end_time && $type && $start_time){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($end_time) && $type && $title){
                    if($grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($type) && $end_time && $title){
                    if($grouplist[$gk]['created'] <= $end_time && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($title) && $type && $end_time){
                    if( $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($type) && $start_time && $title){
                    if( $start_time <= $grouplist[$gk]['created'] && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($title) && $start_time && $type){
                    if( $start_time <= $grouplist[$gk]['created'] && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($type) && $start_time && $end_time){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($title) && empty($type) && $start_time){
                    if( $start_time <= $grouplist[$gk]['created']){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($start_time) && empty($type) && $end_time){
                    if( $grouplist[$gk]['created'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($start_time) && empty($end_time) && $type){
                    if( $grouplist[$gk]['category_id']==$type ){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($start_time) && empty($type) && $title){
                    if( stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }
            }
        }
        if($start_time || $end_time || $type || $title){
            $grouplist = $search;
        }
        $grouplist = $grouplist?$this->order($grouplist,'created'):[];
        $grouplist2 = array_slice($grouplist,$limit*($page-1),$limit);
        echo json_encode([
            'code'=>0,
            'count'=>count($grouplist),
            'data'=>$grouplist2,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
//        return show($grouplist2, 0, '', ['count' => count($grouplist)]);
    }

    /**
     * 添加、编辑集團业务
     */
    public function businessEdit()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {
                //编辑
                $posts = Posts::find(['id'=>$data['id']]);
                $posts->post_type = 'group-business';
                $posts->slug = $data['slug'];
                $posts->status = intval($data['status']);
                $posts->publish_time = $data['time'];
                if($posts->save()){

                    $category = PostCategories::where(['post_id'=>$id])->find();
                    if($category && $category['category_id'] == intval($data['type'])){

                    }else{
                        $category->category_id = intval($data['type']);
                        $category->save();
                    }
//                    return $category;
                    $hk_detail = PostDetails::where(['post_id'=>$id,'language'=>0])->find();
                    $hk_detail->keywords = $data['keyword_hk'];
                    $hk_detail->description = $data['desc_hk'];
                    $hk_detail->title = $data['title_hk'];
                    $hk_detail->excerpt = $data['show_hk'];
                    $hk_detail->content = $data['content_hk'];
                    $hk_detail->save();

                    $cn_detail = PostDetails::where(['post_id'=>$id,'language'=>1])->find();
                    $cn_detail->keywords = $data['keyword_cn'];
                    $cn_detail->description = $data['desc_cn'];
                    $cn_detail->title = $data['title_cn'];
                    $cn_detail->excerpt = $data['show_cn'];
                    $cn_detail->content = $data['content_cn'];
                    $cn_detail->save();

                    $en_detail = PostDetails::where(['post_id'=>$id,'language'=>2])->find();
                    $en_detail->keywords = $data['keyword_en'];
                    $en_detail->description = $data['desc_en'];
                    $en_detail->title = $data['title_en'];
                    $en_detail->excerpt = $data['show_en'];
                    $en_detail->content = $data['content_en'];
                    $en_detail->save();
                    return Result::success('成功');
                }else{
                    return Result::error('失敗');
                }
            } else {

                //添加
                $posts = new Posts();
                $posts->post_type = 'group-business';
                $posts->cover_link_id = 0;
                $posts->square_link_id = 0;
                $posts->slug = $data['slug'];
                $posts->status = intval($data['status']);
                $posts->publish_time = $data['time'];
                if($posts->save()){
                    $detail = [
                        [
                            'language'=>0,
                            'title'=>$data['title_hk'],
                            'content'=>$data['content_hk'],
                            'excerpt'=>$data['show_hk'],
                            'keywords'=>$data['keyword_hk'],
                            'description'=>$data['desc_hk'],
                        ],
                        [
                            'language'=>1,
                            'title'=>$data['title_cn'],
                            'content'=>$data['content_cn'],
                            'excerpt'=>$data['show_cn'],
                            'keywords'=>$data['keyword_cn'],
                            'description'=>$data['desc_cn'],
                        ],
                        [
                            'language'=>2,
                            'title'=>$data['title_en'],
                            'content'=>$data['content_en'],
                            'excerpt'=>$data['show_en'],
                            'keywords'=>$data['keyword_en'],
                            'description'=>$data['desc_en'],
                        ],
                    ];
                    $p_cate = new PostCategories();
                    $p_cate->post_id = $posts->id;
                    $p_cate->category_id = intval($data['type']);
                    if($p_cate->save()){
                        foreach ($detail as $dk=>$dv){
                            $p_detail = new PostDetails();
                            $p_detail->post_id = $posts->id;
                            $p_detail->author_id = 0;
                            $p_detail->language = $dv['language'];
                            $p_detail->keywords = $dv['keywords'];
                            $p_detail->description = $dv['description'];
                            $p_detail->title = $dv['title'];
                            $p_detail->excerpt = $dv['excerpt'];
                            $p_detail->content = $dv['content'];
                            $a = $p_detail->save();
                        }
                    }

                    return Result::success('删除成功');
                }else{
                    return Result::error('失敗');
                }
            }
        }

        $id = $this->request->get('id');
        $llist = Posts::where(['id'=>$id])->with('detail,getCategory')->select()->toArray();
        $list=[];
        if($llist){
            $list['id'] = $llist[0]['id'];
            $list['type'] = $llist[0]['get_category']['category_id'];
            $list['slug'] = $llist[0]['slug'];
            $list['status'] = $llist[0]['status'];
            $list['time'] = $llist[0]['publish_time'];
            $list['title_hk'] = '';
            $list['content_hk'] = '';
            $list['show_hk'] = '';
            $list['keyword_hk'] = '';
            $list['desc_hk'] = '';

            $list['title_cn'] = '';
            $list['content_cn'] = '';
            $list['show_cn'] = '';
            $list['keyword_cn'] = '';
            $list['desc_cn'] = '';

            $list['title_en'] = '';
            $list['content_en'] = '';
            $list['show_en'] = '';
            $list['keyword_en'] = '';
            $list['desc_en'] = '';
            if($llist[0]['detail']){
                $list['title_hk'] = $llist[0]['detail'][0]['title'];
                $list['content_hk'] = $llist[0]['detail'][0]['content'];
                $list['show_hk'] = $llist[0]['detail'][0]['excerpt'];
                $list['keyword_hk'] = $llist[0]['detail'][0]['keywords'];
                $list['desc_hk'] = $llist[0]['detail'][0]['description'];

                $list['title_cn'] = $llist[0]['detail'][1]['title'];
                $list['content_cn'] = $llist[0]['detail'][1]['content'];
                $list['show_cn'] = $llist[0]['detail'][1]['excerpt'];
                $list['keyword_cn'] = $llist[0]['detail'][1]['keywords'];
                $list['desc_cn'] = $llist[0]['detail'][1]['description'];

                $list['title_en'] = $llist[0]['detail'][2]['title'];
                $list['content_en'] = $llist[0]['detail'][2]['content'];
                $list['show_en'] = $llist[0]['detail'][2]['excerpt'];
                $list['keyword_en'] = $llist[0]['detail'][2]['keywords'];
                $list['desc_en'] = $llist[0]['detail'][2]['description'];
            }

        }

        $datas = Categories::where(['slug'=>'group-business','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $grouplist=[];

        if($datas){
            $data = $datas[0]['son'];
            foreach ($data as $k=>$v){
                if($data[$k]['detail']){
                    $group['id'] = $data[$k]['id'];
                    $group['title'] = $data[$k]['detail']?$data[$k]['detail'][0]['name']:'';
                    $grouplist[] = $group;
                }
            }
        }
        $this->assign('grouplist', $grouplist);
        $this->assign('list', $list);


        return $this->fetch();
    }


    /**
     * 删除集團业务
     */
    public function businessDelete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Posts::update(['deleted'=>1],['id'=>$id]);
            if($up){
                PostDetails::update(['deleted'=>1],['post_id'=>$id]);
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }


    /**
     * 就业机会列表
     */
    public function jobs()
    {
        $datas = Categories::where(['slug'=>'jobs','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $grouplist=[];
        $groups=[];
        if($datas){
            $data = $datas[0]['son'];
            foreach ($data as $k=>$v){
                if($data[$k]['detail']){
                    $group['id'] = $data[$k]['id'];
                    $group['title'] = $data[$k]['detail']?$data[$k]['detail'][0]['name']:'';
                    $groups[] = $group;
                }
            }
        }

        $this->assign('grouplist', $groups);

        return $this->fetch();
    }

    public function get_jobs_data(){
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $start_time = $this->request->post('start_time', '');
        $end_time = $this->request->post('end_time', '');
        $type = $this->request->post('type', 0,'intval');
        $title = $this->request->post('title', '');

        $search=[];
        $grouplist=[];
        $datas = Posts::where(['post_type'=>'jobs','deleted'=>0])->order('publish_time','desc')->with('getCategory,detail')->select()->toArray();
        if($datas){
            foreach ($datas as $k=>$v){
                if($datas[$k]['get_category'] && $datas[$k]['get_category']['category']['deleted']==0){
                    if($datas[$k]['detail']){
                        $join['id'] = $datas[$k]['detail'][0]['post_id'];
                        $join['parent_slug'] = isset($datas[$k]['get_category']['category']['detail'])&&!empty($datas[$k]['get_category']['category']['detail'])?$datas[$k]['get_category']['category']['detail'][0]['name']:'';
                        $join['category_id'] = $datas[$k]['get_category']['category_id'];
                        $join['slug'] = $datas[$k]['slug'];
                        $join['status'] = $datas[$k]['status'];
                        $join['created'] = $datas[$k]['publish_time'];
                        $join['name'] = $datas[$k]['detail'][0]['title'];
                        $grouplist[] = $join;
                    }
                }
            }
        }


        if($grouplist){
            foreach ($grouplist as $gk=>$gv){
                if($start_time && $end_time && $type && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && $end_time && $type && $title){
                    if($grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && $start_time && $type && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($type) && $end_time && $start_time && $title){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && $end_time && $type && $start_time){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($end_time) && $type && $title){
                    if($grouplist[$gk]['category_id']==$type && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($type) && $end_time && $title){
                    if($grouplist[$gk]['created'] <= $end_time && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($title) && $type && $end_time){
                    if( $grouplist[$gk]['created'] <= $end_time && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($type) && $start_time && $title){
                    if( $start_time <= $grouplist[$gk]['created'] && stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($title) && $start_time && $type){
                    if( $start_time <= $grouplist[$gk]['created'] && $grouplist[$gk]['category_id']==$type){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($type) && $start_time && $end_time){
                    if( $start_time <= $grouplist[$gk]['created']&& $grouplist[$gk]['created'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($title) && empty($type) && $start_time){
                    if( $start_time <= $grouplist[$gk]['created']){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($start_time) && empty($type) && $end_time){
                    if( $grouplist[$gk]['created'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && empty($start_time) && empty($end_time) && $type){
                    if( $grouplist[$gk]['category_id']==$type ){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($start_time) && empty($type) && $title){
                    if( stristr($grouplist[$gk]['name'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }
            }
        }

        if($start_time || $end_time || $type || $title){
            $grouplist = $search;
        }
        $grouplist = $grouplist?$this->order($grouplist,'created'):[];
        $grouplist2 = array_slice($grouplist,$limit*($page-1),$limit);
        echo json_encode([
            'code'=>0,
            'count'=>count($grouplist),
            'data'=>$grouplist2,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
//        return show($grouplist2, 0, '', ['count' => count($grouplist)]);
    }

    /**
     * 添加、编辑就业机会
     */
    public function jobsEdit()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {
                //编辑
                $posts = Posts::find(['id'=>$data['id']]);
                $posts->post_type = 'jobs';
                $posts->slug = $data['slug'];
                $posts->status = intval($data['status']);
                $posts->publish_time = $data['time'];
                if($posts->save()){

                    $category = PostCategories::where(['post_id'=>$id])->find();
                    if($category && $category['category_id'] == intval($data['type'])){

                    }else{
                        $category->category_id = intval($data['type']);
                        $category->save();
                    }
//                    return $category;
                    $hk_detail = PostDetails::where(['post_id'=>$id,'language'=>0])->find();
                    $hk_detail->keywords = $data['keyword_hk'];
                    $hk_detail->description = $data['desc_hk'];
                    $hk_detail->title = $data['title_hk'];
                    $hk_detail->excerpt = $data['show_hk'];
                    $hk_detail->content = $data['content_hk'];
                    $hk_detail->save();

                    $cn_detail = PostDetails::where(['post_id'=>$id,'language'=>1])->find();
                    $cn_detail->keywords = $data['keyword_cn'];
                    $cn_detail->description = $data['desc_cn'];
                    $cn_detail->title = $data['title_cn'];
                    $cn_detail->excerpt = $data['show_cn'];
                    $cn_detail->content = $data['content_cn'];
                    $cn_detail->save();

                    $en_detail = PostDetails::where(['post_id'=>$id,'language'=>2])->find();
                    $en_detail->keywords = $data['keyword_en'];
                    $en_detail->description = $data['desc_en'];
                    $en_detail->title = $data['title_en'];
                    $en_detail->excerpt = $data['show_en'];
                    $en_detail->content = $data['content_en'];
                    $en_detail->save();
                    return Result::success('成功');
                }else{
                    return Result::error('失敗');
                }
            } else {

                //添加
                $posts = new Posts();
                $posts->post_type = 'jobs';
                $posts->cover_link_id = 0;
                $posts->square_link_id = 0;
                $posts->slug = $data['slug'];
                $posts->status = intval($data['status']);
                $posts->publish_time = $data['time'];
                if($posts->save()){
                    $detail = [
                        [
                            'language'=>0,
                            'title'=>$data['title_hk'],
                            'content'=>$data['content_hk'],
                            'excerpt'=>$data['show_hk'],
                            'keywords'=>$data['keyword_hk'],
                            'description'=>$data['desc_hk'],
                        ],
                        [
                            'language'=>1,
                            'title'=>$data['title_cn'],
                            'content'=>$data['content_cn'],
                            'excerpt'=>$data['show_cn'],
                            'keywords'=>$data['keyword_cn'],
                            'description'=>$data['desc_cn'],
                        ],
                        [
                            'language'=>2,
                            'title'=>$data['title_en'],
                            'content'=>$data['content_en'],
                            'excerpt'=>$data['show_en'],
                            'keywords'=>$data['keyword_en'],
                            'description'=>$data['desc_en'],
                        ],
                    ];
                    $p_cate = new PostCategories();
                    $p_cate->post_id = $posts->id;
                    $p_cate->category_id = intval($data['type']);
                    if($p_cate->save()){
                        foreach ($detail as $dk=>$dv){
                            $p_detail = new PostDetails();
                            $p_detail->post_id = $posts->id;
                            $p_detail->author_id = 0;
                            $p_detail->language = $dv['language'];
                            $p_detail->keywords = $dv['keywords'];
                            $p_detail->description = $dv['description'];
                            $p_detail->title = $dv['title'];
                            $p_detail->excerpt = $dv['excerpt'];
                            $p_detail->content = $dv['content'];
                            $a = $p_detail->save();
                        }
                    }

                    return Result::success('删除成功');
                }else{
                    return Result::error('失敗');
                }
            }
        }

        $id = $this->request->get('id');
        $llist = Posts::where(['id'=>$id])->with('detail,getCategory')->select()->toArray();
        $list=[];
        if($llist){
            $list['id'] = $llist[0]['id'];
            $list['type'] = $llist[0]['get_category']['category_id'];
            $list['slug'] = $llist[0]['slug'];
            $list['status'] = $llist[0]['status'];
            $list['time'] = $llist[0]['publish_time'];
            $list['title_hk'] = '';
            $list['content_hk'] = '';
            $list['show_hk'] = '';
            $list['keyword_hk'] = '';
            $list['desc_hk'] = '';

            $list['title_cn'] = '';
            $list['content_cn'] = '';
            $list['show_cn'] = '';
            $list['keyword_cn'] = '';
            $list['desc_cn'] = '';

            $list['title_en'] = '';
            $list['content_en'] = '';
            $list['show_en'] = '';
            $list['keyword_en'] = '';
            $list['desc_en'] = '';
            if($llist[0]['detail']){
                $list['title_hk'] = $llist[0]['detail'][0]['title'];
                $list['content_hk'] = $llist[0]['detail'][0]['content'];
                $list['show_hk'] = $llist[0]['detail'][0]['excerpt'];
                $list['keyword_hk'] = $llist[0]['detail'][0]['keywords'];
                $list['desc_hk'] = $llist[0]['detail'][0]['description'];

                $list['title_cn'] = $llist[0]['detail'][1]['title'];
                $list['content_cn'] = $llist[0]['detail'][1]['content'];
                $list['show_cn'] = $llist[0]['detail'][1]['excerpt'];
                $list['keyword_cn'] = $llist[0]['detail'][1]['keywords'];
                $list['desc_cn'] = $llist[0]['detail'][1]['description'];

                $list['title_en'] = $llist[0]['detail'][2]['title'];
                $list['content_en'] = $llist[0]['detail'][2]['content'];
                $list['show_en'] = $llist[0]['detail'][2]['excerpt'];
                $list['keyword_en'] = $llist[0]['detail'][2]['keywords'];
                $list['desc_en'] = $llist[0]['detail'][2]['description'];
            }

        }

        $datas = Categories::where(['slug'=>'jobs','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $grouplist=[];

        if($datas){
            $data = $datas[0]['son'];
            foreach ($data as $k=>$v){
                if($data[$k]['detail']){
                    $group['id'] = $data[$k]['id'];
                    $group['title'] = $data[$k]['detail']?$data[$k]['detail'][0]['name']:'';
                    $grouplist[] = $group;
                }
            }
        }
        $this->assign('grouplist', $grouplist);
        $this->assign('list', $list);


        return $this->fetch();
    }


    /**
     * 删除就业机会
     */
    public function jobsDelete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Posts::update(['deleted'=>1],['id'=>$id]);
            if($up){
                PostDetails::update(['deleted'=>1],['post_id'=>$id]);
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }




    /**
     * 新闻发布列表
     */
    public function publish()
    {
        return $this->fetch();
    }

    public function get_publish_data(){
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $start_time = $this->request->post('start_time', '');
        $end_time = $this->request->post('end_time', '');
        $title = $this->request->post('title', '');


        $aws = new Aws();
        $datas = Categories::where(['slug'=>'publish','deleted'=>0])->field('*')->order('seq','asc')->with('post')->find()->toArray();
        $grouplist=[];
        if($datas['post']){
            $data = $datas['post'];
            foreach ($data as $k=>$v){
                if($data[$k]['detail']){
                    $group['id'] = $data[$k]['detail']['id'];
                    $group['status'] = $data[$k]['detail']['status'];
                    $group['publish_time'] = $data[$k]['detail']['publish_time'];
                    $group['title'] = $data[$k]['detail']['detail']?$data[$k]['detail']['detail'][0]['title']:'';
                    $group['meta'] = $data[$k]['detail']['detail']?$data[$k]['detail']['detail'][0]['keywords']:'';
                    $group['text'] = $data[$k]['detail']['detail']?$data[$k]['detail']['detail'][0]['description']:'';
                    $group['url'] = $data[$k]['detail']['cover_link']['url']?$aws->getUrl($data[$k]['detail']['cover_link']['url']):'';
                    $group['file'] = $data[$k]['detail']['cover_link']['name']?$data[$k]['detail']['cover_link']['name']:'PDF';
                    $grouplist[] = $group;
                }
            }
        }

        $search=[];
        if($grouplist){
            foreach ($grouplist as $gk=>$gv){
                if($start_time && $end_time && $title){
                    if( $start_time <= $grouplist[$gk]['publish_time']&& $grouplist[$gk]['publish_time'] <= $end_time && stristr($grouplist[$gk]['title'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && $end_time && $title){
                    if($grouplist[$gk]['publish_time'] <= $end_time && stristr($grouplist[$gk]['title'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && $start_time && $title){
                    if( $start_time <= $grouplist[$gk]['publish_time']&& stristr($grouplist[$gk]['title'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($title) && $end_time && $start_time){
                    if( $start_time <= $grouplist[$gk]['publish_time']&& $grouplist[$gk]['publish_time'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($end_time) && $title){
                    if(stristr($grouplist[$gk]['title'],$title)){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($start_time) && empty($title) && $end_time){
                    if( $grouplist[$gk]['publish_time'] <= $end_time){
                        $search[] = $grouplist[$gk];
                    }
                }elseif (empty($end_time) && empty($title) && $start_time){
                    if( $start_time <= $grouplist[$gk]['publish_time']){
                        $search[] = $grouplist[$gk];
                    }
                }
            }
        }
        if($start_time || $end_time || $title){
            $grouplist = $search;
        }

        $grouplist = $grouplist?$this->order($grouplist,'publish_time'):[];
        $grouplist2 = array_slice($grouplist,$limit*($page-1),$limit);
        echo json_encode([
            'code'=>0,
            'count'=>count($grouplist),
            'data'=>$grouplist2,
            'extra'=>'',
            'message'=>'获取数据成功'
        ]);
//        return show($grouplist2, 0, '', ['count' => count($grouplist)]);
    }
    /**
     * 添加、编辑新闻发布
     */
    public function publishEdit()
    {
        $id = $this->request->get('id');
        if($id){
            $llist = Posts::where(['id'=>$id])->with('detail,getCategory')->select()->toArray();
            $list=[];
            if($llist){
                $list['id'] = $llist[0]['id'];
                $list['type'] = $llist[0]['get_category']['category_id'];
                $list['slug'] = $llist[0]['slug'];
                $list['status'] = $llist[0]['status'];
                $list['time'] = $llist[0]['publish_time'];
                $list['title_hk'] = '';
                $list['meta_hk'] = '';
                $list['text_hk'] = '';


                $list['title_cn'] = '';
                $list['meta_cn'] = '';
                $list['text_cn'] = '';

                $list['title_en'] = '';
                $list['meta_en'] = '';
                $list['text_en'] = '';
                if($llist[0]['detail']){
                    $list['title_hk'] = $llist[0]['detail'][0]['title'];
                    $list['meta_hk'] = $llist[0]['detail'][0]['keywords'];
                    $list['text_hk'] = $llist[0]['detail'][0]['description'];

                    $list['title_cn'] = $llist[0]['detail'][1]['title'];
                    $list['meta_cn'] = $llist[0]['detail'][1]['keywords'];
                    $list['text_cn'] = $llist[0]['detail'][1]['description'];

                    $list['title_en'] = $llist[0]['detail'][2]['title'];
                    $list['meta_en'] = $llist[0]['detail'][2]['keywords'];
                    $list['text_en'] = $llist[0]['detail'][2]['description'];
                }
            }

            $this->assign('list', $list);
        }

        return $this->fetch();
    }
    public function publishCommit(){
        $aws = new Aws();
        $data = $this->request->post();
        $id = $data['id'];
        if (isset($id) && !empty($id)) {
            $posts = Posts::find(['id'=>$data['id']]);

            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $file_name = $_FILES['file']['name'];
                $ext = pathinfo($file_name)['extension'];
                $type = 2;

                $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$type);

                $list = new Links();
                $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                $list->description = isset($data['description'])?$data['description']:'';
                $list->url = $url;
                $list->author_id = 0;
                $list->type = $type;
                if($list->save()){
                    $posts->cover_link_id = $list->id;
                }
            }
            $posts->status = intval($data['status']);
            $posts->publish_time = $data['test1'];
            if($posts->save()){
                PostDetails::update(['keywords'=>$data['meta_hk'],'description'=>$data['text_hk'],'title'=>$data['title_hk']],['post_id'=>$id,'language'=>0]);
                PostDetails::update(['keywords'=>$data['meta_cn'],'description'=>$data['text_cn'],'title'=>$data['title_cn']],['post_id'=>$id,'language'=>1]);
                PostDetails::update(['keywords'=>$data['meta_en'],'description'=>$data['text_en'],'title'=>$data['title_en']],['post_id'=>$id,'language'=>2]);

                return Result::success('成功');
            }else{
                return Result::error('失敗');
            }
        } else {

            //添加
            $posts = new Posts();
            $posts->post_type = 'publish';
            $posts->cover_link_id = 0;
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $file_name = $_FILES['file']['name'];
                $ext = pathinfo($file_name)['extension'];
                $type = 2;

                $url = $aws->Upload($_FILES['file']['tmp_name'],$ext,$type);

                $list = new Links();
                $list->name = isset($data['name']) && !empty($data['name'])?$data['name']:$file_name;
                $list->description = isset($data['description'])?$data['description']:'';
                $list->url = $url;
                $list->author_id = 0;
                $list->type = $type;
                if($list->save()){
                    $posts->cover_link_id = $list->id;
                }
            }
            $posts->square_link_id = 0;
            $posts->slug = 'publish'.time();
            $posts->status = intval($data['status']);
            $posts->publish_time = $data['test1'];
            if($posts->save()){
                $detail = [
                    [
                        'language'=>0,
                        'title'=>$data['title_hk'],
                        'meta'=>$data['meta_hk'],
                        'text'=>$data['text_hk'],
                    ],
                    [
                        'language'=>1,
                        'title'=>$data['title_cn'],
                        'meta'=>$data['meta_cn'],
                        'text'=>$data['text_cn'],
                    ],
                    [
                        'language'=>2,
                        'title'=>$data['title_en'],
                        'meta'=>$data['meta_en'],
                        'text'=>$data['text_en'],
                    ],
                ];
                $category = Categories::where(['slug'=>'publish','deleted'=>0])->find();
                $p_cate = new PostCategories();
                $p_cate->post_id = $posts->id;
                $p_cate->category_id = isset($category['id'])?intval($category['id']):0;
                if($p_cate->save()){
                    foreach ($detail as $dk=>$dv){
                        $p_detail = new PostDetails();
                        $p_detail->post_id = $posts->id;
                        $p_detail->author_id = 0;
                        $p_detail->language = $dv['language'];
                        $p_detail->keywords = $dv['meta'];
                        $p_detail->description = $dv['text'];
                        $p_detail->title = $dv['title'];
                        $p_detail->excerpt = '';
                        $p_detail->content = '';
                        $p_detail->save();
                    }
                }

                return Result::success('删除成功');
            }else{
                return Result::error('失敗');
            }
        }
    }

    /**
     * 删除新闻发布
     */
    public function publishDelete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Posts::update(['deleted'=>1],['id'=>$id]);
            if($up){
                PostDetails::update(['deleted'=>1],['post_id'=>$id]);
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }


}