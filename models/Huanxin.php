<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Huanxin extends Model {

	public static $hx = null;

	public static function initHx() {
		self::$hx = new Easemob ( Yii::$app->params ['huanxin'] );
		return self::$hx;
	}

	public static function register($username, $password) {
		$options ['username'] = md5 ( $username );
		$options ['password'] = md5 ( $password );
		$res = self::initHx ()->accreditRegister ( $options );
		return $res;
	}

	public static function addFriend($owner_username, $friend_username) {
		return self::initHx ()->addFriend ( md5 ( $owner_username ), md5 ( $friend_username ) );
	}

	public static function deleteFriend($owner_username, $friend_username) {
		return self::initHx ()->deleteFriend ( md5 ( $owner_username ), md5 ( $friend_username ) );
	}
}
