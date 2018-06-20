<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class Bootstrap extends \Yaf\Bootstrap_Abstract
{

    public function _initLoader()
    {
        set_error_handler([$this, "onError"]);
        register_shutdown_function(array($this, 'cleanup'));

        Yaf\Loader::import(APP_PATH . "/vendor/autoload.php");
        Yaf\Loader::import(APP_PATH . "/application/function.php");

        // 注册本地类名前缀, 这部分类名将会在本地类库查找
        Yaf\Loader::getInstance()->registerLocalNameSpace(array('Log', 'Cache', 'Upload', 'Http', 'Util'));
    }

    public function onError($severity, $message, $file, $line)
    {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    }

    public function _initConfig()
    {
        $config = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $config);
    }

    public function _initDefaultName(Yaf\Dispatcher $dispatcher)
    {
        $dispatcher->setDefaultModule('Index')->setDefaultController('Index')->setDefaultAction('index');
    }

    public function _initDatabaseEloquent()
    {
        $capsule = new Capsule;

        // 创建默认链接
        $capsule->addConnection(Yaf\Application::app()->getConfig()->db_wallet->toArray());

        // another业务链接
        // $capsule->addConnection(Yaf_Application::app()->getConfig()->another->toArray(), 'another');
        // $capsule::connection('another')->enableQueryLog();

        // 设置全局静态可访问
        $capsule->setAsGlobal();

        // 启动Eloquent
        $capsule->bootEloquent();
    }

    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        $router = $dispatcher->getRouter();

        $router->addRoute('login', new Yaf\Route\Rewrite(
            '/login$',
            array(
                'module'     => 'Index', // 默认的模块可以省略
                'controller' => 'Public',
                'action'     => 'login',
            )
        ));
    }

    public function _initSession()
    {
        try {
            $redis = redisConnect();
            $redis->ping();
            $session = new Util_Session();
            session_set_save_handler($session, true);
        } catch (Exception $e) {
            Log_Log::info('[Bootstrap] session init error:' . $e->getMessage(), true, true);
        }
    }

    public function cleanup()
    {

        restore_error_handler();

        // 捕获fatal error
        $e = error_get_last();
        if ($e['type'] == E_ERROR) {
            $str = <<<TYPEOTHER
[message] {$e['message']}
[file] {$e['file']}
[line] {$e['line']}
TYPEOTHER;
            // todo 发送邮件、短信、写日志报警……
        }

        // 定义了开关，便关闭log
        if (!defined('SHUTDOWN')) {
            Log_Log::info('receive:' . var_export($_REQUEST, true), true, true);

            // DEFAULT
            $this->log(Capsule::getQueryLog(), 'DEFAULT');

            // 业务库相关SQL
            if (defined('ANOTHER')) {
                $this->log(Capsule::connection(ANOTHER)->getQueryLog(), 'ANOTHER');
            }

        }

    }

    /**
     * @param $info
     * @param $link
     */
    public function log($info, $link)
    {
        foreach ($info as $val) {
            Log_Log::info('[' . $link . ' query] ' . $val['query'] . ' [bindings] ' . implode(' ', $val['bindings']) . ' [time] ' . $val['time'], 1, 1);
        }
    }
}
