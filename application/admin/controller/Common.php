<?php
/**
 * Created by originThink
 * Author: 原点 467490186@qq.com
 * Date: 2017/5/9
 * Time: 13:54
 */

namespace app\admin\controller;

use app\admin\model\Config;
use think\Controller;
use think\exception\HttpResponseException;

class Common extends Controller
{
    public $uid;             //用户id
    public $group_id;       //用户组

    /**
     * 后台控制器初始化
     */
    protected function initialize()
    {
        $this->uid = $this->request->LoginUid;
        $this->group_id = $this->request->LoginGroupId;
        $this->config();
        $site_config = $this->siteConfig();
        $this->assign('site_config', $site_config);
    }

    /**
     * 动态配置
     * @author 原点 <467490186@qq.com>
     */
    private function config()
    {
        if (cache('config')) {
            $list = cache('config');
        } else {
            $list = Config::where('name', '=', 'system_config')->field('value,status')->find();
            cache('config', $list);
        }
        if ($list['status'] == 1) {
            config('app_debug', $list['value']['debug']);
            config('app_trace', $list['value']['trace']);
            config('trace.type', $list['value']['trace_type'] == 0 ? 'Html' : 'Console');
        }
    }

    /**
     * 站点配置信息
     * @return array|mixed|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function siteConfig()
    {
        $site_config = cache('site_config');
        if ($site_config) {
            return $site_config;
        }
        $list = Config::where('name', '=', 'site_config')->field('value')->find();
        cache('site_config', $list);
        return $list;
    }

    //排序二维数组
    public static function order($arrUsers,$field,$order='SORT_DESC'){
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

}