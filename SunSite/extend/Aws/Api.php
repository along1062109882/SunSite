<?php
/**
 * Created by PhpStorm.
 * User: gapday
 * Date: 2017/4/18
 * Time: 下午2:06
 */

namespace Aws;


class Api {
    const API = 'http://uat-hrisapi.suncity-group.com/';

    /**
     * @param $func_id
     * @param $info
     * @return array|mixed
     */
    public static function curl($route, $token, $info){
//        $session = session('token');
////        if(!$session){
//            $json = [
//                "identity"=>"90000002",
//                "password"=>"123456"
//            ];
//            $api = self::curl('json_web_token',$json);
//
//            if(isset($api['data']) && !empty($api['data'])){
//                $token = $api['data']['token'];
////                session('token', $token);
//            }else{
//                return false;
//            }
////            $session = session('token');
////        }

        $response = self::toCurl(self::API.$route,$token, $info);

        $result = array();

        if($response){
            $result = json_decode($response, true);
        }
        return $result;
    }


    /**
     * 使用curl调用 API
     * @param  String $url    请求的地址
     * @param  Array  $param  请求的参数
     * @return JSON
     */
    public static function postCurl($url, $token,$param=array()){

        $ch = curl_init();
        $headers = array("Content-Type:application/json","token:$token");

        if(substr($url,0,5)=='https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        }

        curl_setopt($ch, CURLOPT_URL, self::API.$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if($error=curl_error($ch)){
            return false;
        }
        curl_close($ch);
        $result = array();

        if($response){
            $result = json_decode($response, true);
        }
        return $result;
    }



    public static function getCurl($url,$token){
        $url = self::API.$url;
        $headers = array("token:$token");
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);

        if($error=curl_error($curl)){
            return false;
        }
        curl_close($curl);

        //显示获得的数据
        $result = array();

        if($response){
            $result = json_decode($response, true);
        }
        return $result;
    }
}
