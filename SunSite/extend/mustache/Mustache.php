<?php
/**
 * Created by PhpStorm.
 * User: cityfruit-lf
 * Date: 2019/6/3
 * Time: 下午1:56
 */

namespace mustache;

use Mustache_Engine;

class Mustache
{
    public static function mustache($lang){
        $mustache = new Mustache_Engine(array(
            'template_class_prefix' => '__MyTemplates_',
            'cache' => __DIR__.'/../../runtime/cache/mustache',
            'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
            'cache_lambda_templates' => true,
            'loader' => new \Mustache_Loader_FilesystemLoader(__DIR__.'/../../public/templates/'.$lang),
//            'partials_loader' => new \Mustache_Loader_FilesystemLoader(\Env::get('app_path').'index/templates/partials'),
            'helpers' => array('i18n' => function($text) {
                // do something translatey here...
            }),
            'escape' => function($value) {
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            },
            'charset' => 'ISO-8859-1',
            'logger' => new \Mustache_Logger_StreamLogger('php://stderr'),
            'strict_callables' => true,
            'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
        ));
        return $mustache;
    }
}