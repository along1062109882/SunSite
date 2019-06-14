<?php
namespace app\index\controller;

use app\index\model\Annual;
use app\index\model\Concert;
use app\index\model\Entertainment;
use app\index\model\Food;
use app\index\model\GrandPrix;
use app\index\model\LinkTarget;
use app\index\model\PostCategories;
use app\index\model\Posts;
use Aws\Api;
use Aws\Aws;
use think\Controller;
use mustache\Mustache;
use think\Db;
use think\Exception;
use think\facade\Cookie;
use app\index\model\Categories;
use think\Session;

class Index extends Controller
{
    private $lang = '';
    private  $all_lang = [
        'zh-hant'=>0,
        'zh-hans'=>1,
        'en'=>2
    ];
    //排序二维数组
    public function order($arrUsers,$field,$order='SORT_DESC'){
        $sort = array(
            'direction' => $order, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => $field,       //排序字段
        );
        $arrSort = array();
        foreach($arrUsers as $uniqid => $row){
            foreach($row as $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);
        }
        return $arrUsers;
    }
    public function getLang(){
        $uri = array_filter(explode('/',$_SERVER['REQUEST_URI']));
//        $uri = array_filter(explode('/',$_SERVER['PATH_INFO']));
        $this->lang = $uri?$uri[1]:'zh-hant';
    }

    public function getMenu($language){
        $in_lang = $this->all_lang[$language];
        $data = Categories::where(['parent_id'=>0,'deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $result = [];
        if($data){
            foreach ($data as $k=>$v){
                if ($data[$k]['slug'] != 'home'){
                    $res['slug'] = $data[$k]['slug'];
                    $res['seq'] = $data[$k]['seq'];
                    $res['name'] = $data[$k]['detail']?$data[$k]['detail'][$in_lang]['name']:'';
                    $res['son_list'] = [];
                    if($data[$k]['son']){
                        foreach ($data[$k]['son'] as $son_k=>$son_v){
                            $res_son['slug'] = $data[$k]['son'][$son_k]['slug'];
                            $res_son['name'] = $data[$k]['son'][$son_k]['detail']?$data[$k]['son'][$son_k]['detail'][$in_lang]['name']:'';
                            $res['son_list'][] = $res_son;
                        }
                    }
                    $result[] = $res;
                }
            }
        }
        return $result;
    }

    public function lastedNews($in_lang){
        $aws = new Aws();

        $datas = Posts::where(['post_type'=>'news','deleted'=>0,'status'=>1])->where('publish_time','<=',date('Y-m-d H:i:s'))->with('detail,coverLink,squareLink')->order('publish_time','desc')->limit(3)->select()->toArray();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['title'] = '';
                $datas[$k]['excerpt'] = '';
                $datas[$k]['content'] = '';
                $datas[$k]['keywords'] = '';
                $datas[$k]['description'] = '';
                $datas[$k]['date'] = date("F j, Y",strtotime($datas[$k]['publish_time']));
                if(isset($datas[$k]['detail'][$in_lang])){
                    $datas[$k]['title'] = $datas[$k]['detail'][$in_lang]['title'];
                    $datas[$k]['excerpt'] = $datas[$k]['detail'][$in_lang]['excerpt'];
                    $datas[$k]['content'] = $datas[$k]['detail'][$in_lang]['content'];
                    $datas[$k]['keywords'] = $datas[$k]['detail'][$in_lang]['keywords'];
                    $datas[$k]['description'] = $datas[$k]['detail'][$in_lang]['description'];
                }
                if($datas[$k]['square_link']){
                    $datas[$k]['square_link']['url'] = $datas[$k]['square_link']['url']?$aws->getUrl($datas[$k]['square_link']['url']):'';
                }
                unset($datas[$k]['detail']);
            }
        }
        return $datas;
    }

    public function relatedNews($in_lang,$ids){
        $aws = new Aws();
        $datas = Posts::where(['post_type'=>'news','deleted'=>0,'status'=>1])->where('id','in',$ids)->where('publish_time','<=',date('Y-m-d H:i:s'))->with('detail,coverLink,squareLink')->order('publish_time','desc')->limit(3)->select()->toArray();
        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['title'] = '';
                $datas[$k]['excerpt'] = '';
                $datas[$k]['content'] = '';
                $datas[$k]['keywords'] = '';
                $datas[$k]['description'] = '';
                $datas[$k]['date'] = date("F j, Y",strtotime($datas[$k]['publish_time']));
                if(isset($datas[$k]['detail'][$in_lang])){
                    $datas[$k]['title'] = $datas[$k]['detail'][$in_lang]['title'];
                    $datas[$k]['excerpt'] = $datas[$k]['detail'][$in_lang]['excerpt'];
                    $datas[$k]['content'] = $datas[$k]['detail'][$in_lang]['content'];
                    $datas[$k]['keywords'] = $datas[$k]['detail'][$in_lang]['keywords'];
                    $datas[$k]['description'] = $datas[$k]['detail'][$in_lang]['description'];
                }
                if($datas[$k]['square_link']){
                    $datas[$k]['square_link']['url'] = $datas[$k]['square_link']['url']?$aws->getUrl($datas[$k]['square_link']['url']):'';
                }
                unset($datas[$k]['detail']);
            }
        }
        return $datas;
    }

    public function index()
    {
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);
        $mustache = Mustache::mustache($language);
        $tpl = $mustache->loadTemplate('index');
        return $tpl->render(array('LanguageDisplay' => $language,'LatestNews'=>$news));
    }
    public function unknown()
    {
        $this->getLang();
        $language = $this->lang;
        $mustache = Mustache::mustache($language);
        $tpl = $mustache->loadTemplate('404');
        return $tpl->render(array('LanguageDisplay' => $language));
    }

    public function lang() {
        $this->lang = $_GET['lang'];
        var_dump($_GET);die();
        switch ($_GET['lang']) {
            case 'cn':
                cookie('think_var', 'zh-cn');
                break;
            case 'en':
                cookie('think_var', 'en-us');
                break;
            case 'tw':
                cookie('think_var', 'zh-tw');
                break;
            default:
                cookie('think_var', 'zh-cn');
        }
    }
    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    //集團介紹
    public function about_us()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('aboutus');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }

    //貴賓服務
    public function sun_vip()
    {
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sun-vip');
        return $tpl->render(array('LanguageDisplay' => $language,'News'=>$news));
    }
    //新聞動態
    public function news()
    {
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $get_year = isset($_REQUEST['year'])?$_REQUEST['year']:0;
        $get_page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $keyword = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        $limit = $this->request->post('limit', 9,'intval');
        $offset = ($get_page - 1) * $limit;

        $where[] = ['post_type','=','news'];
        $where[] = ['deleted','=',0];
        $where[] = ['status','=',1];
        $all_datas = $this->getNews($where,$in_lang);
        if($get_year){
            $where[] = ['publish_time','like','%'.$get_year.'%'];
        }
//        if($keyword){
//            $where[] = ['publish_time','like','%'.$keyword.'%'];
//        }
        $datas = $this->getNews($where,$in_lang);
        if($datas){
            $all_year = $all_datas?array_values(array_unique(array_column($all_datas,'year'))):[];

            $cate = Categories::where(['slug'=>'news','parent_id'=>0])->field('id,slug')->with('detail')->find();
            $cate = $cate?$cate->toArray():[];
            if(isset($cate['detail']) && $cate['detail']){
                $cate['language'] = '';
                $cate['name'] = '';
                foreach ($cate['detail'] as $ck=>$cv){
                    if($cate['detail'][$ck]['language']==$in_lang){
                        $cate['language'] = $in_lang;
                        $cate['name'] = $cate['detail'][$ck]['name'];
                    }
                }
                unset($cate['detail']);
            }

            $year = $datas[0]['publish_time']?date("Y",strtotime($datas[0]['publish_time'])):'';

            foreach ($datas as $dk=>$dv){
                if($keyword){
                    if(stristr($datas[$dk]['title'],$keyword) || stristr($datas[$dk]['publish_time'],$keyword)){

                    }else{
                        unset($datas[$dk]);
                    }
                }
            }
            if($keyword && $datas){
                $this->order($datas,'date');
            }
            $count = count($datas);
            $all_page = ceil($count/9);
            $all_page = $all_page?$all_page:1;
            for ($i=1;$i<=$all_page;$i++){
                $p['No'] = $i;
                $Paging['Pages'][] = $p;
            }
            $Paging['PageCount'] = $all_page;
            $Paging['CurrentPage'] = $get_page;
            $Paging['FirstPage'] = 1;
            $Paging['LastPage'] = $all_page;
            $Paging['PreviousPage'] = ($get_page-1 <= 0)? 1 : $get_page-1;
            $Paging['NextPage'] = ($get_page+1>$all_page) ?$all_page : $get_page+1;


            $result['LanguageDisplay'] = $language;
            $result['CurrentRootCategory'] = $cate;
            $result['CurrentCategorySlug'] = $cate?$cate['slug']:'';
            $result['PostPreviews'] = array_slice($datas,$offset,$limit);
            $result['Year'] = $year;
            $result['Years'] = $all_year;
            $result['Paging'] = $Paging;

            $mustache = Mustache::mustache($this->lang);
            $tpl = $mustache->loadTemplate('news');
            return $tpl->render($result);
        }

        $mustache = Mustache::mustache($this->lang);
        if($get_year||$keyword){
            $tpl = $mustache->loadTemplate('news');
        }else{
            $tpl = $mustache->loadTemplate('404');
        }
        return $tpl->render(array('LanguageDisplay' => $language));
    }

    public function post_news()
    {
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $get_year = isset($_REQUEST['year'])?$_REQUEST['year']:0;
        $get_page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $keyword = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        $limit = $this->request->post('limit', 9,'intval');
        $offset = ($get_page - 1) * $limit;

        $where[] = ['post_type','=','news'];
        $where[] = ['deleted','=',0];
        $where[] = ['status','=',1];
        $all_datas = $this->getNews($where,$in_lang);
        if($get_year){
            $where[] = ['publish_time','like','%'.$get_year.'%'];
        }
        if($keyword){
            $where[] = ['publish_time','like','%'.$keyword.'%'];
        }
        $datas = $this->getNews($where,$in_lang);
        $result = [];
        if($datas){
            $all_year = $all_datas?array_values(array_unique(array_column($all_datas,'year'))):[];

            $cate = Categories::where(['slug'=>'news','parent_id'=>0])->field('id,slug')->with('detail')->find();
            $cate = $cate?$cate->toArray():[];
            if(isset($cate['detail']) && $cate['detail']){
                $cate['language'] = '';
                $cate['name'] = '';
                foreach ($cate['detail'] as $ck=>$cv){
                    if($cate['detail'][$ck]['language']==$in_lang){
                        $cate['language'] = $in_lang;
                        $cate['name'] = $cate['detail'][$ck]['name'];
                    }
                }
                unset($cate['detail']);
            }

            $year = $datas[0]['publish_time']?date("Y",strtotime($datas[0]['publish_time'])):'';

            foreach ($datas as $dk=>$dv){
                if($keyword){
                    if(stristr($datas[$dk]['title'],$keyword)){

                    }else{
                        unset($datas[$dk]);
                    }
                }
            }
            if($keyword){
                $this->order($datas,'date');
            }
            $count = count($datas);
            $all_page = ceil($count/9);
            $all_page = $all_page?$all_page:1;
            for ($i=1;$i<=$all_page;$i++){
                $p['No'] = $i;
                $Paging['Pages'][] = $p;
            }
            $Paging['PageCount'] = $all_page;
            $Paging['CurrentPage'] = $get_page;
            $Paging['FirstPage'] = 1;
            $Paging['LastPage'] = $all_page;
            $Paging['PreviousPage'] = ($get_page-1 <= 0)? 1 : $get_page-1;
            $Paging['NextPage'] = ($get_page+1>$all_page) ?$all_page : $get_page+1;


            $result['LanguageDisplay'] = $language;
            $result['CurrentRootCategory'] = $cate;
            $result['CurrentCategorySlug'] = $cate?$cate['slug']:'';
            $result['PostPreviews'] = array_slice($datas,$offset,$limit);
            $result['Year'] = $year;
            $result['Years'] = $all_year;
            $result['Paging'] = $Paging;


        }

        return $result;

    }

    public function getNews($where,$in_lang){
        $aws = new Aws();
        $datas = Posts::where($where)->where('publish_time','<=',date('Y-m-d H:i:s'))->with('detail,coverLink,squareLink')->order('publish_time','desc')->select()->toArray();
        foreach ($datas as $k=>$v){
            $datas[$k]['title'] = '';
            $datas[$k]['excerpt'] = '';
            $datas[$k]['content'] = '';
            $datas[$k]['keywords'] = '';
            $datas[$k]['description'] = '';
            $datas[$k]['date'] = date("F j, Y",strtotime($datas[$k]['publish_time']));
            $datas[$k]['year'] = date("Y",strtotime($datas[$k]['publish_time']));
            if(isset($datas[$k]['detail'][$in_lang])){
                $datas[$k]['title'] = $datas[$k]['detail'][$in_lang]['title'];
                $datas[$k]['excerpt'] = $datas[$k]['detail'][$in_lang]['excerpt'];
                $datas[$k]['content'] = $datas[$k]['detail'][$in_lang]['content'];
                $datas[$k]['keywords'] = $datas[$k]['detail'][$in_lang]['keywords'];
                $datas[$k]['description'] = $datas[$k]['detail'][$in_lang]['description'];
            }
            unset($datas[$k]['detail']);

            if(isset($datas[$k]['cover_link']) && $datas[$k]['cover_link']){
                $datas[$k]['cover_link']['url'] = $datas[$k]['cover_link']['url']?$aws->getUrl($datas[$k]['cover_link']['url']):'';
            }
        }
        return $datas;
    }


    //查看新闻详情
    public function news_post()
    {
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];

        $uri = array_filter(explode('/',$_SERVER['REQUEST_URI']));
        $slug = end($uri);

        $datas = Posts::where(['slug'=>$slug])->with('detail,coverLink,squareLink,allLink,getCategory')->find();

        if($datas){

            $category_id = $datas['get_category']['category_id'];
            $post_id = $datas['id'];

            $datas = $this->postDetail($datas,$in_lang);
            $year = $datas['publish_time']?date("Y",strtotime($datas['publish_time'])):'';

            $cate = Categories::where(['slug'=>'news','parent_id'=>0])->field('id,slug')->with('detail')->find();
            $cate = $cate?$cate->toArray():[];
            if(isset($cate['detail']) && $cate['detail']){
                $cate['language'] = '';
                $cate['name'] = '';
                foreach ($cate['detail'] as $ck=>$cv){
                    if($cate['detail'][$ck]['language']==$in_lang){
                        $cate['language'] = $in_lang;
                        $cate['name'] = $cate['detail'][$ck]['name'];
                    }
                }
                unset($cate['detail']);
            }

            $all_post = Posts::where(['post_type'=>'news','deleted'=>0,'status'=>1])->where('publish_time','<=',date('Y-m-d H:i:s'))->order('publish_time','desc')->select()->toArray();
            $all_ids = array_column($all_post,'id');

            //獲取當前ID的key
            $key = array_search($datas['id'],$all_ids);

            if(isset($all_ids[$key-1])){
                $prev = Posts::where(['id'=>$all_ids[$key-1]])->with('detail,coverLink,squareLink,allLink')->find();
                if($prev){
                    $prev = $this->postDetail($prev,$in_lang);
                    $result['PreviousPost'] = $prev;

                }
            }
            if(isset($all_ids[$key+1])){
                $next = Posts::where(['id'=>$all_ids[$key+1]])->with('detail,coverLink,squareLink,allLink')->find();
                if($next){
                    $next = $this->postDetail($next,$in_lang);
                    $result['NextPost'] = $next;

                }
            }
            $news = $this->lastedNews($in_lang);

            $related = PostCategories::where(['category_id'=>$category_id])->select()->toArray();
            $all_relate_post = array_column($related,'post_id');
            $key = array_search($post_id, $all_relate_post);
            if ($key !== false)
                array_splice($all_relate_post, $key, 1);
            $all_relate_post = array_filter($all_relate_post);

            $related_news = $this->relatedNews($in_lang,$all_relate_post);

            $result['LanguageDisplay'] = $language;
            $result['Post'] = $datas;
            $result['Year'] = $year;
            $result['CurrentRootCategory'] = $cate;
            $result['LatestPostPreviews'] = $news;
            $result['RelatedPostPreviews'] = $related_news;

            $mustache = Mustache::mustache($this->lang);
            $tpl = $mustache->loadTemplate('news-post');
            return $tpl->render($result);
        }

        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('404');
        return $tpl->render(array('LanguageDisplay' => $language));
}
    public function postDetail($data,$in_lang){
        $aws = new Aws();
        $datas = $data->toArray();
        $datas['title'] = '';
        $datas['excerpt'] = '';
        $datas['content'] = '';
        $datas['keywords'] = '';
        $datas['description'] = '';
        $datas['date'] = date("F j, Y",strtotime($datas['publish_time']));
        if(isset($datas['detail'][$in_lang])){
            $datas['title'] = $datas['detail'][$in_lang]['title'];
            $datas['excerpt'] = $datas['detail'][$in_lang]['excerpt'];
            $datas['content'] = $datas['detail'][$in_lang]['content'];
            $datas['keywords'] = $datas['detail'][$in_lang]['keywords'];
            $datas['description'] = $datas['detail'][$in_lang]['description'];
        }
        $datas['Links']=[];
        if($datas['all_link']){
            foreach ($datas['all_link'] as $lk=>$lv){
                if($datas['all_link'][$lk]['link_detail']){
                    $datas['all_link'][$lk]['link_detail']['url'] = $datas['all_link'][$lk]['link_detail']['url']?$aws->getUrl($datas['all_link'][$lk]['link_detail']['url']):'';
                    $datas['Links'][] = $datas['all_link'][$lk]['link_detail'];
                }
            }
        }
        unset($datas['detail']);
        unset($datas['all_link']);
        return $datas;
    }


    //新聞發布
    public function release()
    {
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $get_year = isset($_REQUEST['year'])?$_REQUEST['year']:0;
        $get_page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $keyword = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        $limit = $this->request->post('limit', 9,'intval');
        $offset = ($get_page - 1) * $limit;

        $where[] = ['post_type','=','publish'];
        $where[] = ['deleted','=',0];
        $where[] = ['status','=',1];
        $all_datas = $this->getNews($where,$in_lang);
        if($get_year){
            $where[] = ['publish_time','like','%'.$get_year.'%'];
        }
        if($keyword){
            $where[] = ['publish_time','like','%'.$keyword.'%'];
        }
        $datas = $this->getNews($where,$in_lang);
        if($datas){
            $all_year = $all_datas?array_values(array_unique(array_column($all_datas,'year'))):[];

            $cate = Categories::where(['slug'=>'publish','parent_id'=>0])->field('id,slug')->with('detail')->find();
            $cate = $cate?$cate->toArray():[];
            if(isset($cate['detail']) && $cate['detail']){
                $cate['language'] = '';
                $cate['name'] = '';
                foreach ($cate['detail'] as $ck=>$cv){
                    if($cate['detail'][$ck]['language']==$in_lang){
                        $cate['language'] = $in_lang;
                        $cate['name'] = $cate['detail'][$ck]['name'];
                    }
                }
                unset($cate['detail']);
            }
            $year = $datas[0]['publish_time']?date("Y",strtotime($datas[0]['publish_time'])):'';
            foreach ($datas as $dk=>$dv){
                if($keyword){
                    if(stristr($datas[$dk]['title'],$keyword)){
                    }else{
                        unset($datas[$dk]);
                    }
                }
            }
            if($keyword){
                $this->order($datas,'date');
            }

            $count = count($datas);
            $all_page = ceil($count/9);
            $all_page = $all_page?$all_page:1;
            for ($i=1;$i<=$all_page;$i++){
                $p['No'] = $i;
                $Paging['Pages'][] = $p;
            }
            $Paging['PageCount'] = $all_page;
            $Paging['CurrentPage'] = $get_page;
            $Paging['FirstPage'] = 1;
            $Paging['LastPage'] = $all_page;
            $Paging['PreviousPage'] = ($get_page-1 <= 0)? 1 : $get_page-1;
            $Paging['NextPage'] = ($get_page+1>$all_page) ?$all_page : $get_page+1;


            $result['LanguageDisplay'] = $language;
            $result['CurrentRootCategory'] = $cate;
            $result['CurrentCategorySlug'] = $cate?$cate['slug']:'';
            $result['PostPreviews'] = array_slice($datas,$offset,$limit);
            $result['Year'] = $year;
            $result['Years'] = $all_year;
            $result['Paging'] = $Paging;

            $mustache = Mustache::mustache($this->lang);
            $tpl = $mustache->loadTemplate('release');
            return $tpl->render($result);
        }

        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('404');
        return $tpl->render(array('LanguageDisplay' => $language));
    }


    public function getUrl(){
        $uri = explode('?',$_SERVER['REQUEST_URI']);
        $uri = explode('=',$uri[1]);

        $aws = new Aws();
        $url = $aws->getUrl($uri[1]);
//        Header("HTTP/1.1 302 Moved Temporarily");
//        Header("Location: $url");
//        exit;

        $headers = get_headers($url);
                var_dump($headers);die();

//        if ($headers && $headers['Location']){
//            echo $headers['Location'];
//        }
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $uri[1] . '"');
        echo file_get_contents($url);
    }

    //矚目盛事
    public function grand()
    {
        $aws = new Aws();
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];

        $concert = Concert::where(['deleted_at'=> null,'state'=>1])->where('publish_time','<=',date('Y-m-d H:i:s'))->field('id,publish_time,url')->with('getContent,getLink')->order('publish_time','desc')->select()->toArray();
        if($concert){
            foreach ($concert as $k=>$v){
                $concert[$k]['index'] = $k%2;
                $concert[$k]['name'] = '';
                $concert[$k]['content'] = '';
                if($concert[$k]['get_content']){
                    foreach ($concert[$k]['get_content'] as $ck=>$cv){
                        if($concert[$k]['get_content'][$ck]['language']==$in_lang && $concert[$k]['get_content'][$ck]['owner_field']=='Name'){
                            $concert[$k]['name'] = $concert[$k]['get_content'][$ck]['data'];
                        }
                        if($concert[$k]['get_content'][$ck]['language']==$in_lang && $concert[$k]['get_content'][$ck]['owner_field']=='Content'){
                            $concert[$k]['content'] = $concert[$k]['get_content'][$ck]['data'];
                        }
                    }
                }
                $concert[$k]['img_url'] = isset($concert[$k]['get_link']['get_link'])&&$concert[$k]['get_link']['get_link']?$aws->getUrl($concert[$k]['get_link']['get_link']['url']):'';
                $concert[$k]['alt'] = isset($concert[$k]['get_link']['get_link'])&&$concert[$k]['get_link']['get_link']?$concert[$k]['get_link']['get_link']['description']:'';
                unset($concert[$k]['get_content']);
                unset($concert[$k]['get_link']);
            }
        }


        $grand = GrandPrix::where(['deleted_at'=> null,'state'=>1])->where('publish_time','<=',date('Y-m-d H:i:s'))->field('id,publish_time,url')->with('getContent,getLink')->order('publish_time','desc')->select()->toArray();
        if($grand){
            foreach ($grand as $gk=>$gv){
                $grand[$gk]['index'] = $gk%2;
                $grand[$gk]['name'] = '';
                $grand[$gk]['content'] = '';
                if($grand[$gk]['get_content']){
                    foreach ($grand[$gk]['get_content'] as $gck=>$gcv){
                        if($grand[$gk]['get_content'][$gck]['language']==$in_lang && $grand[$gk]['get_content'][$gck]['owner_field']=='Name'){
                            $grand[$gk]['name'] = $grand[$gk]['get_content'][$gck]['data'];
                        }
                        if($grand[$gk]['get_content'][$gck]['language']==$in_lang && $grand[$gk]['get_content'][$gck]['owner_field']=='Content'){
                            $grand[$gk]['content'] = $grand[$gk]['get_content'][$gck]['data'];
                        }
                    }
                }
                $grand[$gk]['img_url'] = isset($grand[$gk]['get_link']['get_link'])&&$grand[$gk]['get_link']['get_link']?$aws->getUrl($grand[$gk]['get_link']['get_link']['url']):'';
                $grand[$gk]['alt'] = isset($grand[$gk]['get_link']['get_link'])&&$grand[$gk]['get_link']['get_link']?$grand[$gk]['get_link']['get_link']['description']:'';

                unset($grand[$gk]['get_content']);
                unset($grand[$gk]['get_link']);
            }
        }


        $annual = Annual::where(['deleted_at'=> null,'state'=>1])->where('publish_time','<=',date('Y-m-d H:i:s'))->field('id,publish_time,url')->with('getContent,getLink')->order('publish_time','desc')->select()->toArray();

        foreach ($annual as $ak=>$av){
            $annual[$ak]['index'] = $ak%2;
            $annual[$ak]['name'] = '';
            $annual[$ak]['content'] = '';
            if($annual[$ak]['get_content']){
                foreach ($annual[$ak]['get_content'] as $ack=>$acv){
                    if($annual[$ak]['get_content'][$ack]['language']==$in_lang && $annual[$ak]['get_content'][$ack]['owner_field']=='Name'){
                        $annual[$ak]['name'] = $annual[$ak]['get_content'][$ack]['data'];
                    }
                    if($annual[$ak]['get_content'][$ack]['language']==$in_lang && $annual[$ak]['get_content'][$ack]['owner_field']=='Content'){
                        $annual[$ak]['content'] = $annual[$ak]['get_content'][$ack]['data'];
                    }
                }
            }
            $annual[$ak]['img_url'] = isset($annual[$ak]['get_link']['get_link'])&&$annual[$ak]['get_link']['get_link']?$aws->getUrl($annual[$ak]['get_link']['get_link']['url']):'';
            $annual[$ak]['alt'] = isset($annual[$ak]['get_link']['get_link'])&&$annual[$ak]['get_link']['get_link']?$annual[$ak]['get_link']['get_link']['description']:'';
            unset($annual[$ak]['get_content']);
            unset($annual[$ak]['get_link']);
        }

        $result['LanguageDisplay'] = $language;
        $result['Concert'] = $concert;
        $result['Grand'] = $grand;
        $result['Annual'] = $annual;

        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('grand');
        return $tpl->render($result);
    }
//    public function jobs()
//    {
//        $this->getLang();
//        $mustache = Mustache::mustache($this->lang);
//        $tpl = $mustache->loadTemplate('jobs');
//        return $tpl->render(array('LanguageDisplay' => $this->lang));
//    }
    //就業機會
    public function jobs_vip()
    {
        $cache = cache('data');
        if(!$cache){
            $json = [
                "identity"=>"90000002",
                "password"=>"123456"
            ];
            $api = Api::postCurl('json_web_token','',$json);

            if(isset($api['data']) && !empty($api['data'])){
                $token = $api['data']['token'];
                $data = Api::getCurl('jobs/enabled_without_pending',$token);
                if(isset($data['state']) && $data['state'] == 'success'){
                    cache('data',$data['data'],['expire'=>600]);
                }
            }
            $cache = cache('data');
        }
        $arr=[];
        if($cache){
            foreach ($cache as $cache_k=>$cache_v){
                $arr[$cache[$cache_k]['department']['chinese_name']][] = $cache[$cache_k];
            }

        }


        $this->getLang();
        $uri = array_filter(explode('/',$_SERVER['REQUEST_URI']));
        if(count($uri)<3){
            array_push($uri,'');
        }
        $slug = end($uri);
        $smallSlug = $slug?urldecode($slug):$slug;


        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $datas = Categories::where(['slug'=>'jobs','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $result = [];
        if(isset($datas[0]['son']) && !empty($datas[0]['son'])){
            $data['son'] = $datas[0]['son'];
            $result['LanguageDisplay'] = $this->lang;
            if(isset($data['son'])){
                $result['Jobs'] = [];
                $posts = $data['son'];
                foreach ($posts as $pks=>$pvs){
                    $big_join['Name'] = $posts[$pks]['detail']?$posts[$pks]['detail'][$in_lang]['name']:'';
                    $big_join['Slug'] = $posts[$pks]['slug'];
                    $big_join['FirstSubSlug'] = '';
                    $big_join['Posts'] = [];
                    foreach ($arr as $arr_k=>$arr_v){
                        if($arr_k == $posts[$pks]['detail'][0]['name']){
                            foreach ($arr[$arr_k] as $a_k_k=>$a_k_v){
                                $joins['id'] = 0;
                                $joins['created'] = $arr[$arr_k][$a_k_k]['created_at'];
                                $joins['updated'] = $arr[$arr_k][$a_k_k]['updated_at'];
                                $joins['slug'] = $arr[$arr_k][$a_k_k]['position']['english_name'];
                                $joins['cover_link_id'] = 0;
                                $joins['square_link_id'] = 0;
                                $joins['publish_time'] = '';
                                $joins['status'] = 1;
                                $joins['title'] = $in_lang==0?$arr[$arr_k][$a_k_k]['position']['chinese_name']:($in_lang==1?$arr[$arr_k][$a_k_k]['position']['simple_chinese_name']:$arr[$arr_k][$a_k_k]['position']['english_name']);
                                $joins['excerpt'] = '';
                                $joins['content'] = $in_lang==0?$arr[$arr_k][$a_k_k]['scope_of_work_in_chinese']:($in_lang==1?$arr[$arr_k][$a_k_k]['scope_of_work_in_simple_chinese']:$arr[$arr_k][$a_k_k]['scope_of_work_in_english']);
                                $joins['scope'] = $in_lang==0?$arr[$arr_k][$a_k_k]['job_requirements_in_chinese']:($in_lang==1?$arr[$arr_k][$a_k_k]['job_requirements_in_simple_chinese']:$arr[$arr_k][$a_k_k]['job_requirements_in_english']);
                                $joins['keywords'] = '';
                                $joins['description'] = '';
                                $joins['date'] = '';
                                $joins['cover_link'] = '';
                                $joins['square_link'] = '';
                                $big_join['Posts'][] = $joins;
                            }
                        }
                    }
                    if($big_join['Posts']){
                        $big_join['FirstSubSlug'] = $big_join['Posts'][0]['slug'];
                        $result['Jobs'][] = $big_join;
                    }

                }
                $result['AllPostsBelongToCategory'] = [];

                $smallSlug = $smallSlug?$smallSlug:(isset($result['Jobs'][0]['Posts'][0]['slug'])?$result['Jobs'][0]['Posts'][0]['slug']:'');

                if($result['Jobs']){
                    foreach ($result['Jobs'] as $rk=>$rv){
                        if($result['Jobs'][$rk]['Posts']){
                            $po = $result['Jobs'][$rk]['Posts'];
                            foreach ($po as $pok=>$pov){
                                if($po[$pok]['slug']==$smallSlug || $result['Jobs'][$rk]['Slug']==$smallSlug){
                                    $result['CurrentPost'] = $po[$pok];
                                    $result['CurrentCategory']['id'] = 0;
                                    $result['CurrentCategory']['Slug'] = $result['Jobs'][$rk]['Slug'];
                                    $result['CurrentCategory']['Name'] = $result['Jobs'][$rk]['Name'];
                                    $result['CurrentCategory']['language'] = $in_lang;
                                    $result['CurrentCategory']['is_current'] = false;
                                    $result['AllPostsBelongToCategory'] = $result['Jobs'][$rk]['Posts'];
                                }
                            }
                        }
                    }

                    if($result['CurrentCategory']['Slug']){
                        $banner = Categories::where(['slug'=>$result['CurrentCategory']['Slug']])->with('getContentDetail')->find();
                        if($banner){
                            $banner = $banner->toArray();
                            $result['Banner']['Img1920'] = $banner['banner_img_1920'];
                            $result['Banner']['Img1440'] = $banner['banner_img_1440'];
                            $result['Banner']['Img1024'] = $banner['banner_img_1024'];
                            $result['Banner']['Img768'] = $banner['banner_img_768'];
                            $result['Banner']['Img640'] = $banner['banner_img_640'];
                            $result['Banner']['Img375'] = $banner['banner_img_375'];
                            $result['Banner']['Title'] = '';
                            $result['Banner']['Content'] = '';
                            if($banner['get_content_detail']){
                                foreach ($banner['get_content_detail'] as $gk=>$gv){
                                    if($gv['language']==$in_lang){
                                        if($gv['owner_field'] == 'BannerTitle'){
                                            $result['Banner']['Title'] = $gv['data'];
                                        }
                                        if($gv['owner_field'] == 'BannerContent'){
                                            $result['Banner']['Content'] = $gv['data'];
                                        }
                                    }
                                }
                            }
                        }
                    }

                }else{
                    $result['CurrentPost']=[];
                    $result['CurrentCategory']=[];
                    $result['Banner']=[];
//                    $mustache = Mustache::mustache($this->lang);
//                    $tpl = $mustache->loadTemplate('404');
//                    return $tpl->render(array('LanguageDisplay' => $language));
                }

                $result['PostUnpublished'] = false;
                $mustache = Mustache::mustache($this->lang);
                $tpl = $mustache->loadTemplate('jobs-vip');
                return $tpl->render($result);
            }
        }
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('404');
        return $tpl->render(array('LanguageDisplay' => $language));
    }
    public function jobs_vip2()
    {
        $this->getLang();
        $uri = array_filter(explode('/',$_SERVER['REQUEST_URI']));
        if(count($uri)<3){
            array_push($uri,'');
        }
        $slug = end($uri);
//        return $uri;
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $datas = Categories::where(['slug'=>'jobs','deleted'=>0])->field('*')->order('seq','asc')->with('detail,son')->select()->toArray();
        $result = [];
//        return $datas;
        if(isset($datas[0]['son']) && !empty($datas[0]['son'])){
            $data['son'] = $datas[0]['son'];
            $result['LanguageDisplay'] = $this->lang;
            if(isset($data['son'])){
                $result['Jobs'] = [];
                $posts = $data['son'];
                foreach ($posts as $pks=>$pvs){
                    $big_join['Name'] = $posts[$pks]['detail']?$posts[$pks]['detail'][$in_lang]['name']:'';
                    $big_join['Slug'] = $posts[$pks]['slug'];
                    $big_join['FirstSubSlug'] = isset($posts[$pks]['post'][0]['detail']['slug'])?$posts[$pks]['post'][0]['detail']['slug']:'';
                    $big_join['Posts'] = [];
                    if($posts[$pks]['post']){
                        foreach ($posts[$pks]['post'] as $post_key=>$post_val){
                            $joins['id'] = $posts[$pks]['post'][$post_key]['id'];
                            $joins['created'] = $posts[$pks]['post'][$post_key]['created'];
                            $joins['updated'] = $posts[$pks]['post'][$post_key]['updated'];
                            $joins['slug'] = $posts[$pks]['post'][$post_key]['detail']?$posts[$pks]['post'][$post_key]['detail']['slug']:'';
                            $joins['cover_link_id'] = $posts[$pks]['post'][$post_key]['detail']?$posts[$pks]['post'][$post_key]['detail']['cover_link_id']:0;
                            $joins['square_link_id'] = $posts[$pks]['post'][$post_key]['detail']?$posts[$pks]['post'][$post_key]['detail']['square_link_id']:0;
                            $joins['publish_time'] = $posts[$pks]['post'][$post_key]['detail']?$posts[$pks]['post'][$post_key]['detail']['publish_time']:'';
                            $joins['status'] = $posts[$pks]['post'][$post_key]['detail']?$posts[$pks]['post'][$post_key]['detail']['status']:0;
                            $joins['title'] = '';
                            $joins['excerpt'] = '';
                            $joins['content'] = '';
                            $joins['keywords'] = '';
                            $joins['description'] = '';
                            $joins['date'] = '';
                            $joins['cover_link'] = '';
                            $joins['square_link'] = '';
                            if(isset($posts[$pks]['post'][$post_key]['detail']['detail'][$in_lang]) && $posts[$pks]['post'][$post_key]['detail']['detail'][$in_lang]){
                                $joins['title'] = $posts[$pks]['post'][$post_key]['detail']['detail'][$in_lang]['title'];
                                $joins['excerpt'] = $posts[$pks]['post'][$post_key]['detail']['detail'][$in_lang]['excerpt'];
                                $joins['content'] = $posts[$pks]['post'][$post_key]['detail']['detail'][$in_lang]['content'];
                                $joins['keywords'] = $posts[$pks]['post'][$post_key]['detail']['detail'][$in_lang]['keywords'];
                                $joins['description'] = $posts[$pks]['post'][$post_key]['detail']['detail'][$in_lang]['description'];
                                $big_join['Posts'][] = $joins;
                            }
                        }
                    }
                    $result['Jobs'][] = $big_join;
                }
                $slug = $slug?$slug:(isset($result['Jobs'][0]['Posts'][0]['slug'])?$result['Jobs'][0]['Posts'][0]['slug']:'');
                $current = Posts::where(['slug'=>$slug])->with('detail,getCategory')->find();

                if($current){
                    $current = $current->toArray();
                    $result['CurrentPost']['id'] = $current['id'];
                    $result['CurrentPost']['created'] = $current['created'];
                    $result['CurrentPost']['updated'] = $current['updated'];
                    $result['CurrentPost']['slug'] = $current['slug'];
                    $result['CurrentPost']['cover_link_id'] = $current['cover_link_id'];
                    $result['CurrentPost']['square_link_id'] = $current['square_link_id'];
                    $result['CurrentPost']['publish_time'] = $current['publish_time'];
                    $result['CurrentPost']['status'] = $current['status'];
                    $result['CurrentPost']['title'] = '';
                    $result['CurrentPost']['excerpt'] = '';
                    $result['CurrentPost']['content'] = '';
                    $result['CurrentPost']['keywords'] = '';
                    $result['CurrentPost']['description'] = '';
                    $result['CurrentPost']['date'] = date("F j, Y",strtotime($current['publish_time']));
                    $result['CurrentPost']['cover_link'] = '';
                    $result['CurrentPost']['square_link'] = '';
                    if($current['detail']){
                        foreach ($current['detail'] as $ck=>$cv){
                            if($current['detail'][$ck]['language'] == $in_lang){
                                $result['CurrentPost']['title'] = $cv['title'];
                                $result['CurrentPost']['excerpt'] = $cv['excerpt'];
                                $result['CurrentPost']['content'] = $cv['content'];
                                $result['CurrentPost']['keywords'] = $cv['keywords'];
                                $result['CurrentPost']['description'] = $cv['description'];
                            }
                        }
                    }

                    $result['Banner']['Img1920'] = '';
                    $result['Banner']['Img1440'] = '';
                    $result['Banner']['Img1024'] = '';
                    $result['Banner']['Img768'] = '';
                    $result['Banner']['Img640'] = '';
                    $result['Banner']['Img375'] = '';
                    $result['Banner']['Title'] = '';
                    $result['Banner']['Content'] = '';
                    if(isset($current['get_category']['category']) && $current['get_category']['category']){
                        $result['CurrentCategory']['id'] = $current['get_category']['category']['id'];
                        $result['CurrentCategory']['Slug'] = $current['get_category']['category']['slug'];
                        $result['CurrentCategory']['Name'] = $current['get_category']['category']['detail']?$current['get_category']['category']['detail'][$in_lang]['name']:'';
                        $result['CurrentCategory']['language'] = $in_lang;
                        $result['CurrentCategory']['is_current'] = false;

                        $result['Banner']['Img1920'] = $current['get_category']['category']['banner_img_1920'];
                        $result['Banner']['Img1440'] = $current['get_category']['category']['banner_img_1440'];
                        $result['Banner']['Img1024'] = $current['get_category']['category']['banner_img_1024'];
                        $result['Banner']['Img768'] = $current['get_category']['category']['banner_img_768'];
                        $result['Banner']['Img640'] = $current['get_category']['category']['banner_img_640'];
                        $result['Banner']['Img375'] = $current['get_category']['category']['banner_img_375'];

                    }
                    if(isset($current['get_category']['category']['get_content_detail']) && $current['get_category']['category']['get_content_detail']){
                        foreach ($current['get_category']['category']['get_content_detail'] as $gk=>$gv){
                            if($gv['language']==$in_lang){
                                if($gv['owner_field'] == 'BannerTitle'){
                                    $result['Banner']['Title'] = $gv['data'];
                                }
                                if($gv['owner_field'] == 'BannerContent'){
                                    $result['Banner']['Content'] = $gv['data'];
                                }
                            }
                        }

                    }
                }else{
                    $mustache = Mustache::mustache($this->lang);
                    $tpl = $mustache->loadTemplate('404');
                    return $tpl->render(array('LanguageDisplay' => $language));
                }

                $result['PostUnpublished'] = $result['CurrentPost']['status']?false:true;
                $result['AllPostsBelongToCategory'] = [];
                if(isset($data['son'][0]['post']) && !empty($data['son'][0]['post'])){
                    $post = $data['son'][0]['post'];

                    foreach ($post as $pk=>$pv){
                        $join['id'] = $post[$pk]['id'];
                        $join['created'] = $post[$pk]['created'];
                        $join['updated'] = $post[$pk]['updated'];
                        $join['slug'] = $post[$pk]['detail']?$post[$pk]['detail']['slug']:'';
                        $join['cover_link_id'] = $post[$pk]['detail']?$post[$pk]['detail']['cover_link_id']:0;
                        $join['square_link_id'] = $post[$pk]['detail']?$post[$pk]['detail']['square_link_id']:0;
                        $join['publish_time'] = $post[$pk]['detail']?$post[$pk]['detail']['publish_time']:'';
                        $join['status'] = $post[$pk]['detail']?$post[$pk]['detail']['status']:0;
                        $join['title'] = '';
                        $join['excerpt'] = '';
                        $join['content'] = '';
                        $join['keywords'] = '';
                        $join['description'] = '';
                        $join['date'] = '';
                        $join['cover_link'] = '';
                        $join['square_link'] = '';
                        if(isset($post[$pk]['detail']['detail'][$in_lang]) && $post[$pk]['detail']['detail'][$in_lang]){
                            $join['title'] = $post[$pk]['detail']['detail'][$in_lang]['title'];
                            $join['excerpt'] = $post[$pk]['detail']['detail'][$in_lang]['excerpt'];
                            $join['content'] = $post[$pk]['detail']['detail'][$in_lang]['content'];
                            $join['keywords'] = $post[$pk]['detail']['detail'][$in_lang]['keywords'];
                            $join['description'] = $post[$pk]['detail']['detail'][$in_lang]['description'];
                            $result['AllPostsBelongToCategory'][] = $join;
                        }
                    }
                }
return $result;
                $mustache = Mustache::mustache($this->lang);
                $tpl = $mustache->loadTemplate('jobs-vip');
                return $tpl->render($result);
            }
        }
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('404');
        return $tpl->render(array('LanguageDisplay' => $language));
    }
    //  社會責任
    public function duty()
    {
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('duty');
        return $tpl->render(array('LanguageDisplay' => $language,'News'=>$news));
    }
    //  免責聲明
    public function disclaimer()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('disclaimer');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }


    //  影視娛樂
    public function sun_entertainment_concert()
    {
        $aws = new Aws();
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);

        $banner = LinkTarget::where(['owner_type'=>'entertainment'])->field('id,link_id')->with('getLink')->select()->toArray();
        if($banner){
            foreach ($banner as $k=>$v){
                $banner[$k]['url'] = $banner[$k]['get_link']['url']?$aws->getUrl($banner[$k]['get_link']['url']):'';
                $banner[$k]['description'] = $banner[$k]['get_link']?$banner[$k]['get_link']['description']:'';
                unset($banner[$k]['get_link']);
            }
        }

        $concert = Entertainment::where(['deleted'=>0])->field('id,type,link_id')->with('links,getContent')->select()->toArray();
        $data1=[];
        $data2=[];
        if($concert){
            foreach ($concert as $k=>$v){
                $concert[$k]['url'] = $concert[$k]['links']['url']?$aws->getUrl($concert[$k]['links']['url']):'';
                $concert[$k]['title'] = '';
                $concert[$k]['meta'] = '';
                if($concert[$k]['get_content']){
                    foreach ($concert[$k]['get_content'] as $ck=>$cv){
                        if($concert[$k]['get_content'][$ck]['language']==$in_lang){
                            if($concert[$k]['get_content'][$ck]['owner_field']=='Title'){
                                $concert[$k]['title'] = $concert[$k]['get_content'][$ck]['data'];
                            }
                            if($concert[$k]['get_content'][$ck]['owner_field']=='Meta'){
                                $concert[$k]['meta'] = $concert[$k]['get_content'][$ck]['data'];
                            }
                        }
                    }
                }
                unset($concert[$k]['get_content']);
                unset($concert[$k]['links']);
                if($concert[$k]['type']==1){
                    $data1[] = $concert[$k];
                }elseif ($concert[$k]['type']==2){
                    $data2[] = $concert[$k];
                }
            }
        }

        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sun-entertainment-concert');
        return $tpl->render(array('LanguageDisplay' => $language,'Banner'=>$banner,'Concert'=>$data1,'Culture'=>$data2,'News'=>$news));
    }
    //  環球旅遊
    public function sun_travel()
    {
        $aws = new Aws();
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);

        $banner = LinkTarget::where(['owner_type'=>'travel'])->field('id,link_id')->with('getLink')->select()->toArray();
        if($banner){
            foreach ($banner as $k=>$v){
                $banner[$k]['url'] = $banner[$k]['get_link']['url']?$aws->getUrl($banner[$k]['get_link']['url']):'';
                $banner[$k]['description'] = $banner[$k]['get_link']?$banner[$k]['get_link']['description']:'';
                unset($banner[$k]['get_link']);
            }
        }
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sun-travel');
        return $tpl->render(array('LanguageDisplay' => $language,'Banner'=>$banner,'News'=>$news));
    }
    //  餐廳體驗
    public function sun_food()
    {
        $aws = new Aws();

        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);

        $banner = LinkTarget::where(['owner_type'=>'food'])->field('id,link_id')->with('getLink')->select()->toArray();
        if($banner){
            foreach ($banner as $k=>$v){
                $banner[$k]['url'] = $banner[$k]['get_link']['url']?$aws->getUrl($banner[$k]['get_link']['url']):'';
                $banner[$k]['description'] = $banner[$k]['get_link']?$banner[$k]['get_link']['description']:'';
                unset($banner[$k]['get_link']);
            }
        }

        $datas = Food::where(['deleted'=>0])->field('id,url,link_id')->with('links,getContent')->select()->toArray();

        if($datas){
            foreach ($datas as $k=>$v){
                $datas[$k]['img_url'] = $datas[$k]['links']['url']?$aws->getUrl($datas[$k]['links']['url']):'';
                if($datas[$k]['get_content']){
                    foreach ($datas[$k]['get_content'] as $ck=>$cv){
                        if($datas[$k]['get_content'][$ck]['language']==$in_lang){
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

        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sun-food');
        return $tpl->render(array('LanguageDisplay' => $language,'Banner'=>$banner,'Data'=>$datas,'News'=>$news));
    }
    //  奢華購物
    public function sunluxe()
    {
        $aws = new Aws();
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);
        $banner = LinkTarget::where(['owner_type'=>'luxe'])->field('id,link_id')->with('getLink')->select()->toArray();
        if($banner){
            foreach ($banner as $k=>$v){
                $banner[$k]['url'] = $banner[$k]['get_link']['url']?$aws->getUrl($banner[$k]['get_link']['url']):'';
                $banner[$k]['description'] = $banner[$k]['get_link']?$banner[$k]['get_link']['description']:'';
                unset($banner[$k]['get_link']);
            }
        }

        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sunluxe');
        return $tpl->render(array('LanguageDisplay' => $language,'Banner'=>$banner,'News'=>$news));
    }
    //娛樂及度假村
    public function sun_management()
    {
        $aws = new Aws();
        $this->getLang();
        $language = $this->lang;
        $in_lang = $this->all_lang[$language];
        $news = $this->lastedNews($in_lang);
        $banner = LinkTarget::where(['owner_type'=>'resort'])->field('id,link_id')->with('getLink')->select()->toArray();
        if($banner){
            foreach ($banner as $k=>$v){
                $banner[$k]['url'] = $banner[$k]['get_link']['url']?$aws->getUrl($banner[$k]['get_link']['url']):'';
                $banner[$k]['description'] = $banner[$k]['get_link']?$banner[$k]['get_link']['description']:'';
                unset($banner[$k]['get_link']);
            }
        }

        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sun-management');
        return $tpl->render(array('LanguageDisplay' => $language,'Banner'=>$banner,'News'=>$news));
    }

    public function jobs_application()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('jobs-application');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }

    public function sun_entertainment()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sun-entertainment');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }

    public function sun_automobile()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('companies-sun-automobile');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }


    public function event()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('event');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }
    public function event_concert()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('event_concert');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }
    public function event_grandprix()
    {
        $this->getLang();
        $language = $this->lang;
        $result = $this->getMenu($language);
        $mustache = Mustache::mustache($this->lang);
        $tpl = $mustache->loadTemplate('event_grandprix');
        return $tpl->render(array('LanguageDisplay' => $language,'Menu'=>$result));
    }
}
