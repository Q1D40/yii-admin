<?php

// comment out the following two lines when deployed to production
defined ( 'YII_DEBUG' ) or define ( 'YII_DEBUG', true );
defined ( 'YII_ENV' ) or define ( 'YII_ENV', 'dev' );

// 定义线上环境,只有线上环境才可开启下行的注释
// defined ( 'ENV_PRO' ) or define ( 'ENV_PRO', true );

error_reporting ( E_ERROR | E_WARNING | E_PARSE );
// error_reporting ( E_ALL );
ini_set ( 'display_errors', 'on' ); // 线上设置为off

require (__DIR__ . '/../vendor/autoload.php');
require (__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// $config = require(__DIR__ . '/../config/web.php');
if (defined ( 'ENV_PRO' )) {
	$config = require (__DIR__ . '/../config/rest_production.php');
} else {
	$config = require (__DIR__ . '/../config/rest.php');
}

(new yii\web\Application ( $config ))->run ();
