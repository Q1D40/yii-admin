<?php

namespace app\controllers;

use Yii;
use app\models\Util;
use yii\helpers\Url;
use app\models\Cookie;

class AdminController extends BaseController {

	public $layout = 'admin.php';

	public $mustLogin = 0;

	public function actionIndex() {
		return $this->render ( 'index' );
	}

	public function actionLogin() {
		if ($this->isAjax ()) {
			$username = $this->post ( 'username' );
			$passwd = $this->post ( 'password' );
			if (! $username) {
				Util::ajax ( '100001', '帐号为空' );
			}
			if (! $passwd) {
				Util::ajax ( '100001', '密码为空' );
			}
			$data ['userName'] = $username;
			$data ['password'] = md5 ( $passwd );
			$json = json_encode ( $data ) . '220cf73aace658bb';
			$url = $this->getDomain () . "pic_news/user/adminLogin?md5=" . md5 ( $json );
			$result = $this->curlPost ( json_encode ( $data ), $url );
			if ($result ['code'] != 0) {
				Util::ajax ( '100001', '帐号或密码错误' );
			}
			// 成功则写session
			$userSession ['_islogin'] = 1;
			$userSession ['user'] = $result ['result'];
			$_SESSION ['_islogin'] = $userSession ['_islogin']; // 登录标识
			$_SESSION ['user'] = $userSession ['user'];
			// 记住我
			Cookie::set ( 'last_login_acc', json_encode ( $userSession ), 604800, '/' );
			// 跳转地址
			$returnUrl = Url::toRoute ( "/index.php/admin" );
			Util::ajax ( '100000', '登录成功，即将跳转...', $returnUrl );
		}
	}

	public function actionLogout() {
		$session = Yii::$app->session;
		// 销毁session中所有已注册的数据
		$session->destroy ();
		$url = Url::toRoute ( "/" );
		return Yii::$app->getResponse ()->redirect ( $url )->send ();
	}
}