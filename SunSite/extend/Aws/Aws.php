<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/10
 * Time: 下午3:10
 */
namespace Aws;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

use Aws\Spyc;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Aws
{

    public $S3;
    public $bucket = 'source';
    public function __construct(){
        $config = Spyc::YAMLLoad(__DIR__ . '/../../config.yml');
        $this->S3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'endpoint' => $config['minio']['endpoint'],
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => $config['minio']['key'],
                'secret' => $config['minio']['secret'],
            ],
        ]);
    }

    public function getUrl($key){
        $cmd = $this->S3->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        $request = $this->S3->createPresignedRequest($cmd, '+7200 minutes');
        $presignedUrl = (string)$request->getUri();
        return $presignedUrl;
    }

    public function Upload($tmp,$ext,$type){

//        try {
            $uuid1 = Uuid::uuid1()->toString();
            if($type == 0){
                $key = 'img/'.$uuid1.'.'.$ext;
                $this->S3->putObject([
                    'Bucket' => 'source',
                    'Key'    => $key,
                    'Body'   => fopen($tmp, 'r'),
                    'ACL'    => 'public-read'
                ]);
            }elseif($type == 1){
                $key = 'video/'.$uuid1.'.'.$ext;
                $this->S3->putObject([
                    'Bucket' => 'source',
                    'Key'    => $key,
                    'Body'   => fopen($tmp, 'r'),
                    'ACL'    => 'public-read'
                ]);
            }elseif($type == 2){
                $key = 'pdf/'.$uuid1.'.'.$ext;
                $this->S3->putObject([
                    'Bucket' => 'source',
                    'Key'    => $key,
                    'Body'   => fopen($tmp, 'r'),
                    'ACL'    => 'public-read',
                    'ContentType'=>'application/pdf',
                    'ContentDisposition'=>'inline',
                ]);
            }
            return $key;
//        } catch (S3Exception $e) {
//            return $e->getMessage() . PHP_EOL;
//        }
    }


    //作用：产生随机字符串，不长于32位
    private static function createNoncestr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}

