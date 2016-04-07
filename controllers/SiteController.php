<?php

namespace app\controllers;

use Yii;
use app\models\Cookie;
use yii\helpers\Url;

class SiteController extends BaseController {

	public $mustLogin = 0;

	public function actionIndex() {
		$lastLoginAcc = Cookie::get ( 'last_login_acc' );
		if ($lastLoginAcc) {
			$userSession = json_decode ( $lastLoginAcc, true );
			if ($userSession) {
				$_SESSION ['_islogin'] = $userSession ['_islogin']; // 登录标识
				$_SESSION ['user'] = $userSession ['user'];
				$url = Url::toRoute ( '/index.php/admin' );
				header ( "Location: {$url}" );
				exit ();
			}
		}
		return $this->render ( 'index', $this->viewData );
	}
}