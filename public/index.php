<?php
date_default_timezone_set ( "Asia/Shanghai" );
define("APP_PATH", realpath(dirname(__FILE__) . '/../'));
$app = new \Yaf\Application(APP_PATH . "/conf/application.ini");
$app->bootstrap()->run();
