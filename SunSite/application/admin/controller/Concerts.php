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
use app\index\model\Concert;
use app\index\model\ConcertCenter;
use app\index\model\ConcertDescribe;
use app\index\model\ConcertIntroduction;
use app\index\model\ConcertSlide;
use app\index\model\ConcertTicket;
use app\index\model\ConcertTicketDetail;
use app\index\model\Content;
use app\index\model\CurrentRootCategory;
use app\index\model\LinkTarget;
use app\index\model\PostCategories;
use app\index\model\PostDetails;
use app\index\model\Posts;
use app\index\model\Links;
use Aws\Aws;

class Concerts extends Common
{
    /**
     * 集團動態列表
     */
    public function concertList()
    {
        return $this->fetch();
    }

    public function get_concert_data(){
        $limit = $this->request->post('limit', 10,'intval');
        $page = $this->request->post('page', 0,'intval');
        $offset = ($page - 1) * $limit;

        $datas = Concert::where(['deleted_at'=> null])->field('id,publish_time,state')->with('getContent')->order('publish_time','desc')->limit($offset,$limit)->select()->toArray();
        $count = Concert::where(['deleted_at'=> null])->count();
        if($datas){
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
    public function concertEdit(){
        $id = $this->request->get('id');
        if($id){
            $llist = Concert::where(['id'=>$id])->with('getContent')->find()->toArray();
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


    public function concert_commit(){
        $data = $this->request->post();
        $id = $data['id'];
        if($_FILES['file']['name']){
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
                $check = LinkTarget::where(['owner_type'=>'concert','owner_id'=>$id,'link_id'=>$list->id])->find();
                if(!$check){
                    $new = new LinkTarget();
                    $new->owner_id = $id;
                    $new->owner_type = 'concert';
                    $new->link_id = $list->id;
                    $new->owner_field = 'all';
                    $new->type = 0;
                    $new->save();
                }else{
                    LinkTarget::update(['link_id'=>$list->id],['owner_type'=>'concert','owner_id'=>$id]);
                }
            }
        }
        if (isset($id) && !empty($id)) {
            //编辑
            $posts = Concert::find(['id'=>$data['id']]);
            $posts->slug = $data['slug'];
            $posts->url = $data['url'];
            $posts->state = intval($data['status']);
            $posts->publish_time = $data['time'];
            $posts->updated_at = date('Y-m-d H:i:s');
            if($posts->save()){
                Content::update(['data'=>$data['title_hk']],['owner_id'=>$id,'language'=>0,'owner_type'=>'concert','owner_field'=>'Name']);
                Content::update(['data'=>$data['title_cn']],['owner_id'=>$id,'language'=>1,'owner_type'=>'concert','owner_field'=>'Name']);
                Content::update(['data'=>$data['title_en']],['owner_id'=>$id,'language'=>2,'owner_type'=>'concert','owner_field'=>'Name']);
                Content::update(['data'=>$data['content_hk']],['owner_id'=>$id,'language'=>0,'owner_type'=>'concert','owner_field'=>'Content']);
                Content::update(['data'=>$data['content_cn']],['owner_id'=>$id,'language'=>1,'owner_type'=>'concert','owner_field'=>'Content']);
                Content::update(['data'=>$data['content_en']],['owner_id'=>$id,'language'=>2,'owner_type'=>'concert','owner_field'=>'Content']);
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
                        $check = LinkTarget::where(['owner_type'=>'concert','owner_id'=>$id])->find();
                        if(!$check){
                            $new = new LinkTarget();
                            $new->owner_id = $id;
                            $new->owner_type = 'concert';
                            $new->link_id = $list->id;
                            $new->owner_field = 'all';
                            $new->type = 0;
                            $new->save();
                        }else{
                            LinkTarget::update(['link_id'=>$list->id],['owner_type'=>'concert','owner_id'=>$id]);
                        }
                        return json(Result::success('成功'));
                    }
                    return json(Result::error('失敗'));
                }
                return json(Result::success('成功'));
            }else{
                return json(Result::error('失敗'));
            }
        } else {

            //添加
            $posts = new Concert();
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
                    $content->owner_type = 'concert';
                    $content->owner_field = 'Name';
                    $content->save();
                    $content = new Content();
                    $content->data = $v['content'];
                    $content->language = $v['language'];
                    $content->owner_id = $posts->id;
                    $content->owner_type = 'concert';
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
                        $check = LinkTarget::where(['owner_type'=>'concert','owner_id'=>$posts->id,'link_id'=>$list->id])->find();
                        if(!$check){
                            $new = new LinkTarget();
                            $new->owner_id = $posts->id;
                            $new->owner_type = 'concert';
                            $new->link_id = $list->id;
                            $new->owner_field = 'all';
                            $new->type = 0;
                            $new->save();
                        }else{
                            LinkTarget::update(['link_id'=>$list->id],['owner_type'=>'concert','owner_id'=>$posts->id]);
                        }
                        return json(Result::success('成功'));
                    }
                    return json(Result::error('失敗'));
                }
                return json(Result::success('成功'));
            }else{
                return json(Result::error('失敗'));
            }
        }

    }


    public function concert_basic(){
        return $this->fetch();

    }
    public function concert_desc(){
        return $this->fetch();

    }

    public function concert_ticket(){
        return $this->fetch();

    }

    public function concert_intro(){
        return $this->fetch();

    }

    public function concert_pic(){
        return $this->fetch();
    }
    public function concert_pic22()
    {

        $data['title_hk'] = '';
        $data['title_cn'] = '';
        $data['title_en'] = '';
        $data['slug'] = '';
        $data['state'] = '';
        $data['publish_time'] = '';

        //這是添加新的
        $concert = new Concert();
        $concert->slug = $data['slug'];
        $concert->publish_time = $data['publish_time'];
        $concert->state = $data['state'];
        if ($concert->save()){
            $root_category = new CurrentRootCategory();
            $root_category->owner_id = $concert->id;
            $root_category->owner_type = 'concert';
            $root_category->owner_field = 'CurrentRootCategory';
            $root_category->save();

            $content = new Content();
            $content->data = $data['title_hk'];
            $content->language = 0;
            $content->owner_id = $concert->id;
            $content->owner_type = 'current_root_category';
            $content->owner_field = 'Name';
            $content->save();

            $content = new Content();
            $content->data = $data['title_cn'];
            $content->language = 1;
            $content->owner_id = $concert->id;
            $content->owner_type = 'current_root_category';
            $content->owner_field = 'Name';
            $content->save();

            $content = new Content();
            $content->data = $data['title_en'];
            $content->language = 2;
            $content->owner_id = $concert->id;
            $content->owner_type = 'current_root_category';
            $content->owner_field = 'Name';
            $content->save();
        }

        //修改基礎信息
        $data1['title_hk'] = '';
        $data1['title_cn'] = '';
        $data1['title_en'] = '';
        $data1['slug'] = '';
        $data1['state'] = '';
        $data1['publish_time'] = '';
//        en_current_root_category: "HOME"
//        en_description: "HOME"
//        en_keywords: "HOME"
//        hans_current_root_category: "HOME"
//        hans_description: "HOME"
//        hans_keywords: "HOME"
//        hant_current_root_category: "HOME"
//        hant_description: "HOME"
//        hant_keywords: "HOME"
//        publish_time: "2018-06-29T07:09:11Z"
//        slug: "Jacky_Cheung_A_Classic_Tour-2018_Macao"
//        status: 0
        $concert_id=1;
        Content::update(['data'=>$data['name_hk']],['owner_id'=>$concert_id,'language'=>0,'owner_type'=>'current_root_category']);
        Content::update(['data'=>$data['name_cn']],['owner_id'=>$concert_id,'language'=>1,'owner_type'=>'current_root_category']);
        Content::update(['data'=>$data['name_en']],['owner_id'=>$concert_id,'language'=>2,'owner_type'=>'current_root_category']);
        Concert::update(['slug'=>$data['slug'],'status'=>$data['status'],'publish_time'=>$data['publish_time']],['id'=>$concert_id]);
        $content = new Content();
        $content->data = $data['keywords_hk'];
        $content->language = 0;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert';
        $content->owner_field = 'Keywords';
        $content->save();
        $content = new Content();
        $content->data = $data['keywords_cn'];
        $content->language = 1;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert';
        $content->owner_field = 'Keywords';
        $content->save();
        $content = new Content();
        $content->data = $data['keywords_en'];
        $content->language = 2;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert';
        $content->owner_field = 'Keywords';
        $content->save();

        $content = new Content();
        $content->data = $data['description_hk'];
        $content->language = 0;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert';
        $content->owner_field = 'Description';
        $content->save();
        $content = new Content();
        $content->data = $data['description_cn'];
        $content->language = 1;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert';
        $content->owner_field = 'Description';
        $content->save();
        $content = new Content();
        $content->data = $data['description_en'];
        $content->language = 2;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert';
        $content->owner_field = 'Description';
        $content->save();


        //描述信息
//        en_detail: "HOME"
//        en_sub_title: "HOME"
//        en_title: "HOME"
//        hans_detail: "HOME"
//        hans_sub_title: "HOME"
//        hans_title: "HOME"
//        hant_detail: "HOME"
//        hant_sub_title: "HOME"
//        hant_title: "HOME"

        $des = new ConcertDescribe();
        $des->section_index = 1;
        $des->concert_id = $concert_id;
        $des->save();

        $content = new Content();
        $content->data = $data['title_hk'];
        $content->language = 0;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Title';
        $content->save();

        $content = new Content();
        $content->data = $data['title_cn'];
        $content->language = 1;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Title';
        $content->save();

        $content = new Content();
        $content->data = $data['title_en'];
        $content->language = 2;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Title';
        $content->save();

        $content = new Content();
        $content->data = $data['title_hk'];
        $content->language = 0;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Subtitle';
        $content->save();

        $content = new Content();
        $content->data = $data['title_cn'];
        $content->language = 1;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Subtitle';
        $content->save();

        $content = new Content();
        $content->data = $data['title_en'];
        $content->language = 2;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Subtitle';
        $content->save();

        $content = new Content();
        $content->data = $data['title_hk'];
        $content->language = 0;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Detail';
        $content->save();

        $content = new Content();
        $content->data = $data['title_cn'];
        $content->language = 1;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Detail';
        $content->save();

        $content = new Content();
        $content->data = $data['title_en'];
        $content->language = 2;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_describe';
        $content->owner_field = 'Detail';
        $content->save();




        //p票務信息

//        background_color: "#FFFFFF"
//        en_date_reservation_0: ""
//        en_date_reservation_1: ""
//        en_date_stay_0: ""
//        en_date_stay_1: ""
//        en_detail_content_0: ""
//        en_detail_content_1: ""
//        en_detail_title_0: ""
//        en_detail_title_1: ""
//        en_subscript_0: ""
//        en_subscript_1: ""
//        en_subtitle_0: ""
//        en_subtitle_1: ""
//        en_title: "HOME"
//        hans_date_reservation_0: "由即日起至2018年8月16日 "
//        hans_date_reservation_1: " 由即日起至2018年8月16日"
//        hans_date_stay_0: "2018年8月17 日 / 18日 / 19 日 / 24 日 / 25 日 / 26 日"
//        hans_date_stay_1: "2018年8月17 日 / 18日 / 19 日 / 24 日/ 25 日 / 26 日"
//        hans_detail_content_0: "• 双人入住威尼斯人度假村酒店豪华皇室/贝丽<br>↵• 澳门威尼斯人威丰味餐厅自助早餐 (两人)<br>↵• 豪华专车往返澳门威尼斯人 (往返各一程)<br>↵• 《学友．经典世界巡回演唱会 - 2018澳门站》A区门票两张<br>"
//        hans_detail_content_1: "• 双人入住威尼斯人度假村酒店豪华皇室<br>↵• 威丰味餐厅自助早餐<br>↵• 豪华专车往返<br>↵• 学友．经典世界巡回演唱会<br>"
//        hans_detail_title_0: "《学友．经典世界巡回演唱会 - 2018澳门站》豪华套票包括以下项目:"
//        hans_detail_title_1: "《学友．经典世界巡回演唱会 - 2018澳门站》尊贵套票包括以下项目:"
//        hans_subscript_0: "(已含税)"
//        hans_subscript_1: " (已含税)"
//        hans_subtitle_0: "豪华套票 | 每晚港币 6088 "
//        hans_subtitle_1: "尊贵套票 | 每晚港币  7588"
//        hans_title: "HOME"
//        hant_date_reservation_0: "由即日起至2018年8月16日"
//        hant_date_reservation_1: "由即日起至2018年8月16日"
//        hant_date_stay_0: "2018年8月17 日 / 18日 / 19 日 / 24 日 / 25 日 / 26 日"
//        hant_date_stay_1: "2018年8月17 日 / 18日 / 19 日 / 24 日/ 25 日 / 26 日"
//        hant_detail_content_0: "• 雙人入住威尼斯人度假村酒店豪華皇室/貝麗<br>↵• 澳門威尼斯人威豐味餐廳自助早餐 (兩人)<br>↵• 豪華專車往返澳門威尼斯人 (往返各一程)<br>↵• 《學友．經典世界巡迴演唱會 - 2018澳門站》A區門票兩張<br>"
//        hant_detail_content_1: "• 雙人入住威尼斯人度假村酒店豪華皇室<br>↵• 威豐味餐廳<br>↵• 豪華專車往返<br>↵• 學友．經典世界巡迴演唱會<br>"
//        hant_detail_title_0: "《學友．經典世界巡迴演唱會 - 2018澳門站》豪華套票包括以下項目:"
//        hant_detail_title_1: "《學友．經典世界巡迴演唱會 - 2018澳門站》尊貴套票包括以下項目:"
//        hant_subscript_0: "(已含税)"
//        hant_subscript_1: "(已含稅)"
//        hant_subtitle_0: "豪華套票 | 每晚港幣 6088"
//        hant_subtitle_1: "尊貴套票 | 每晚港幣  7588"
//        hant_title: "HOME"
//        order_url: "TEST"
//        section_index: 2

        $ccenter = new ConcertCenter();
        $ccenter->section_index = 2;
        $ccenter->background_color = $data['background_color'];
        $ccenter->order_url = $data['order_url'];
        $ccenter->concert_id = $concert_id;
        if($ccenter->save()){
            $content = new Content();
            $content->data = $data['title_hk'];
            $content->language = 0;
            $content->owner_id = $concert_id;
            $content->owner_type = 'concert_center';
            $content->owner_field = 'Title';
            $content->save();
            $content = new Content();
            $content->data = $data['title_cn'];
            $content->language = 1;
            $content->owner_id = $concert_id;
            $content->owner_type = 'concert_center';
            $content->owner_field = 'Title';
            $content->save();
            $content = new Content();
            $content->data = $data['title_en'];
            $content->language = 2;
            $content->owner_id = $concert_id;
            $content->owner_type = 'concert_center';
            $content->owner_field = 'Title';
            $content->save();

            //两条票信息 该步骤 *2
            $da=[1,2];
            foreach($da as $k=>$v){
                $cticket = new ConcertTicket();
                $cticket->concert_center_id = $concert_id;
                if($cticket->save()){
                    $content = new Content();
                    $content->data = $data['subtitle_hk'];
                    $content->language = 0;
                    $content->owner_id = $concert_id;
                    $content->owner_type = 'concert_ticket';
                    $content->owner_field = 'Subtitle';
                    $content->save();

                    $content = new Content();
                    $content->data = $data['subscript_hk'];
                    $content->language = 0;
                    $content->owner_id = $concert_id;
                    $content->owner_type = 'concert_ticket';
                    $content->owner_field = 'Subscript';
                    $content->save();

                    $content = new Content();
                    $content->data = $data['subscript_hk'];
                    $content->language = 0;
                    $content->owner_id = $concert_id;
                    $content->owner_type = 'concert_ticket';
                    $content->owner_field = 'DateReservation';
                    $content->save();

                    $content = new Content();
                    $content->data = $data['subscript_hk'];
                    $content->language = 0;
                    $content->owner_id = $concert_id;
                    $content->owner_type = 'concert_ticket';
                    $content->owner_field = 'DateStay';
                    $content->save();

                    $detail = new ConcertTicketDetail();
                    $detail->concert_ticket_id = $cticket->id;
                    if($detail->save()){
                        $content = new Content();
                        $content->data = $data['subscript_hk'];
                        $content->language = 0;
                        $content->owner_id = $detail->id;
                        $content->owner_type = 'concert_ticket_detail';
                        $content->owner_field = 'Title';
                        $content->save();

                        $content = new Content();
                        $content->data = $data['subscript_hk'];
                        $content->language = 0;
                        $content->owner_id = $detail->id;
                        $content->owner_type = 'concert_ticket_detail';
                        $content->owner_field = 'Content';
                        $content->save();
                    }
                }
            }
        }










        //詳細介紹

//        background_color: "#000000"
//        en_content: "HOME"
//        en_title: "HOME"
//        hans_content: "HOME"
//        hans_title: "HOME"
//        hant_content: "HOME"
//        hant_title: "HOME"
//        section_index: 3

        $ccenter = new ConcertIntroduction();
        $ccenter->section_index = 3;
        $ccenter->background_color = $data['background_color'];
        $ccenter->concert_id = $concert_id;
        $ccenter->save();

        $content = new Content();
        $content->data = $data['title_hk'];
        $content->language = 0;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_introduction';
        $content->owner_field = 'Title';
        $content->save();
        $content = new Content();
        $content->data = $data['title_cn'];
        $content->language = 1;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_introduction';
        $content->owner_field = 'Title';
        $content->save();
        $content = new Content();
        $content->data = $data['title_en'];
        $content->language = 2;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_introduction';
        $content->owner_field = 'Title';
        $content->save();

        $content = new Content();
        $content->data = $data['title_hk'];
        $content->language = 0;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_introduction';
        $content->owner_field = 'Content';
        $content->save();
        $content = new Content();
        $content->data = $data['title_cn'];
        $content->language = 1;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_introduction';
        $content->owner_field = 'Content';
        $content->save();
        $content = new Content();
        $content->data = $data['title_en'];
        $content->language = 2;
        $content->owner_id = $concert_id;
        $content->owner_type = 'concert_introduction';
        $content->owner_field = 'Content';
        $content->save();
        //划動圖片

//        background_color: "#FFFFFF"
        $ccenter = new ConcertSlide();
        $ccenter->background_color = $data['background_color'];
        $ccenter->concert_id = $concert_id;
        $ccenter->save();





        if($this->request->isPost())
        {
            $data = $this->request->post();
            $id = $data['id'];

            if (isset($id) && !empty($id)) {
                $list = Links::find(['id'=>$id]);
                $list->name = $data['name'];
                $list->description = $data['description'];
                if($list->save()){
                    return Result::success('成功');
                }
                return Result::success('成功');
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
    public function concertDelete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if ($id) {
            $up = Concert::update(['deleted_at'=>date('Y-m-d H:i:s')],['id'=>$id]);
            if($up){
                return Result::success('删除成功');
            }
            return Result::error('删除失败');
        } else {
            return Result::error('参数错误');
        }
    }



}