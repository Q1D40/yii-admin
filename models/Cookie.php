<?php

namespace app\models;

use Yii;

class Cookie {
	// 判断Cookie是否存在
	static function is_set($name) {
		// $prefix = Cola::getConfig ( "_cookie.COOKIE_PREFIX" );
		$prefix = Yii::$app->params ['_cookie'] ['COOKIE_PREFIX'];
		return isset ( $_COOKIE [$prefix . $name] );
	}
	
	// 获取某个Cookie值
	static function get($name) {
		// $prefix = Cola::getConfig ( "_cookie.COOKIE_PREFIX" );
		$prefix = Yii::$app->params ['_cookie'] ['COOKIE_PREFIX'];
		$value = $_COOKIE [$prefix . $name];
		$value = unserialize ( base64_decode ( $value ) );
		return $value;
	}
	
	// 设置某个Cookie值
	static function set($name, $value, $expire = '', $path = '', $domain = '') {
		if ($expire == '') {
			// $expire = Cola::getConfig ( "_cookie.COOKIE_EXPIRE" );
			$expire = Yii::$app->params ['_cookie'] ['COOKIE_EXPIRE'];
		}
		if (empty ( $path )) {
			// $path = Cola::getConfig ( "_cookie.COOKIE_PATH" );
			$path = Yii::$app->params ['_cookie'] ['COOKIE_PATH'];
		}
		if (empty ( $domain )) {
			// $domain = Cola::getConfig ( "_cookie.COOKIE_DOMAIN" );
			$domain = Yii::$app->params ['_cookie'] ['COOKIE_DOMAIN'];
		}
		$expire = ! empty ( $expire ) ? time () + $expire : 0;
		$value = base64_encode ( serialize ( $value ) );
		// $prefix = Cola::getConfig ( "_cookie.COOKIE_PREFIX" );
		$prefix = Yii::$app->params ['_cookie'] ['COOKIE_PREFIX'];
		setcookie ( $prefix . $name, $value, $expire, $path, $domain );
		$_COOKIE [$prefix . $name] = $value;
	}
	
	// 删除某个Cookie值
	static function delete($name) {
		self::set ( $name, '', - 3600 );
		// $prefix = Cola::getConfig ( "_cookie.COOKIE_PREFIX" );
		$prefix = Yii::$app->params ['_cookie'] ['COOKIE_PREFIX'];
		unset ( $_COOKIE [$prefix . $name] );
	}
	
	// 清空Cookie值
	static function clear() {
		$_COOKIE = array ();
		unset ( $_COOKIE );
	}
}