<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace yii\rest;

use Yii;

class TestAction extends Action {
	public function run() {
		print_r ( [ 
				'1',
				'2',
				'2',
				'3' 
		] );
	}
}