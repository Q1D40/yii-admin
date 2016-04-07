<?php
$params = require (__DIR__ . '/params.php');

$config = [ 
	'id' => 'basic',
	'basePath' => dirname ( __DIR__ ),
	'bootstrap' => [ 
		'log' 
	],
	'modules' => [ 
		'v1' => [ 
			'class' => 'app\modules\v1\v1' 
		],
		'v2' => [ 
			'class' => 'app\modules\v2\v2' 
		] 
	],
	'aliases' => [ 
		'@mongodb' => '@vendor/yiisoft/yii2-mongodb',
		'@redis' => '@vendor/yiisoft/yii2-redis' 
	],
	'timeZone' => 'Asia/Chongqing',
	'components' => [ 
		'mongodb' => [
			'class' => '\yii\mongodb\Connection',
			'dsn' => 'mongodb://ianshen:4sQY61k1dmJb@10.10.79.125:27017/liuliuliu' 
		],
		'redis' => [ 
			'class' => 'yii\redis\Connection',
			'hostname' => '10.10.16.32',
			'port' => 6379,
			'database' => 0 
		],
		'urlManager' => [ 
			'enablePrettyUrl' => true,
			'enableStrictParsing' => false,
			'showScriptName' => false,
			'rules' => [ 
				[ 
					'class' => 'yii\rest\UrlRule',
					'controller' => [ 
						'v1/test' 
					] 
				],
				[ 
					'class' => 'yii\rest\UrlRule',
					'controller' => [ 
						'v1/spider' 
					] 
				],
				[ 
					'class' => 'yii\rest\UrlRule',
					'controller' => [ 
						'v1/feed' 
					] 
				],
				[ 
					'class' => 'yii\rest\UrlRule',
					'controller' => [ 
						'v1/relation' 
					] 
				],
				[ 
					'class' => 'yii\rest\UrlRule',
					'controller' => [ 
						'v1/artist' 
					] 
				] 
			] 
		],
		'request' => [ 
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'enableCsrfValidation' => false,
			'enableCookieValidation' => false,
			'cookieValidationKey' => '' 
		],
		'cache' => [
			'class' => 'yii\redis\Cache' 
		],
		'user' => [ 
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true 
		],
		'errorHandler' => [ 
			'errorAction' => 'site/error' 
		],
		'mailer' => [ 
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			// 'useFileTransport' => true,
			'transport' => [ 
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.exmail.qq.com',
				'username' => 'account@bandfuntech.com',
				'password' => '64^4ZQS;y6v[',
				'port' => '465',
				'encryption' => 'ssl' 
			] 
		],
		'log' => [ 
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [ 
				[ 
					'class' => 'yii\log\FileTarget',
					'levels' => [ 
						'error',
						'warning' 
					] 
				] 
			] 
		],
		'db' => require (__DIR__ . '/db.php') 
	],
	'params' => $params 
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config ['bootstrap'] [] = 'debug';
	$config ['modules'] ['debug'] = 'yii\debug\Module';
	
	$config ['bootstrap'] [] = 'gii';
	$config ['modules'] ['gii'] = 'yii\gii\Module';
}

return $config;
