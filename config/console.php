<?php
Yii::setAlias ( '@tests', dirname ( __DIR__ ) . '/tests' );

$params = require (__DIR__ . '/params.php');
$db = require (__DIR__ . '/db.php');

return [ 
	'id' => 'basic-console',
	'basePath' => dirname ( __DIR__ ),
	'bootstrap' => [ 
		'log',
		'gii' 
	],
	'controllerNamespace' => 'app\commands',
	'modules' => [ 
		'gii' => 'yii\gii\Module' 
	],
	'components' => [ 
		'mongodb' => [ 
			'class' => '\yii\mongodb\Connection',
			'dsn' => 'mongodb://ianshen:4sQY61k1dmJb@10.10.21.200:27017/liuliuliu' 
		],
		'cache' => [ 
			'class' => 'yii\caching\FileCache' 
		],
		'log' => [ 
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
		'db' => $db 
	],
	'params' => $params 
];
