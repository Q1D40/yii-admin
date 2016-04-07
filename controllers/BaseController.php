<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Util;
use app\models\Curl;
use app\models\Cookie;

class BaseController extends Controller {

	public $layout = false;

	/**
	 * 当前页的JS资源
	 *
	 * @var unknown
	 */
	public $thisJs = '';

	/**
	 * 当前页title
	 *
	 * @var unknown
	 */
	public $pageTitle = '';

	/**
	 * 传给视图的数据
	 *
	 * @var unknown
	 */
	public $viewData = [ ];

	public $basePath = '';

	public $baseUrl = '';

	public $homeUrl = '';

	public $cdnUrl = '';

	public $pageSize = 10;

	/**
	 * 是否须登录
	 *
	 * @var unknown
	 */
	public $mustLogin = 0;

	public function init() {
		if (! Yii::$app->session->isActive) {
			Yii::$app->session->open ();
		}
		$this->basePath = Yii::$app->basePath;
		// $this->baseUrl = Url::base ();
		$this->homeUrl = Url::home ();
		$this->getView ()->params ['basePath'] = $this->basePath;
		// $this->getView ()->params ['baseUrl'] = $this->baseUrl;
		$this->getView ()->params ['homeUrl'] = $this->homeUrl;
		if (! $this->cdnUrl) {
			$this->cdnUrl = $this->homeUrl;
		}
		$this->getView ()->params ['cdnUrl'] = $this->cdnUrl;
		$this->getView ()->params ['__csrf'] = Yii::$app->getRequest ()->getCsrfToken ();
		$this->getView ()->params ['thisJs'] = $this->thisJs;
		// 记住我
		$lastLoginAcc = Cookie::get ( 'last_login_acc' );
		if ($lastLoginAcc) {
			$userSession = json_decode ( $lastLoginAcc, true );
			if ($userSession) {
				$_SESSION ['_islogin'] = $userSession ['_islogin']; // 登录标识
				$_SESSION ['user'] = $userSession ['user'];
			}
		}
		$this->getView ()->params ['islogin'] = $this->isLogin ();
		$this->mustLoginCheck ( '', '/' );
	}

	public function isAjax() {
		return Yii::$app->getRequest ()->isAjax;
	}

	public function get($name, $defaultValue = null) {
		return Yii::$app->getRequest ()->get ( $name, $defaultValue );
	}

	public function post($name, $defaultValue = null) {
		return Yii::$app->getRequest ()->post ( $name, $defaultValue );
	}

	protected function getCurSer() {
		return isset ( $_SESSION ['service'] ) && $_SESSION ['service'] ? $_SESSION ['service'] : [ ];
	}

	protected function isSerLogin() {
		return isset ( $_SESSION ['ser_islogin'] ) && $_SESSION ['ser_islogin'] ? true : false;
	}

	protected function getCurUser() {
		return isset ( $_SESSION ['user'] ) && $_SESSION ['user'] ? $_SESSION ['user'] : [ ];
	}

	protected function isUserLogin() {
		return isset ( $_SESSION ['islogin'] ) && $_SESSION ['islogin'] ? true : false;
	}

	protected function getCurAdmin() {
		return isset ( $_SESSION ['admin'] ) && $_SESSION ['admin'] ? $_SESSION ['admin'] : [ ];
	}

	protected function isAdminLogin() {
		return isset ( $_SESSION ['admin_islogin'] ) && $_SESSION ['admin_islogin'] ? true : false;
	}

	protected function isLogin() {
		return isset ( $_SESSION ['_islogin'] ) && $_SESSION ['_islogin'] ? true : false;
	}

	/**
	 * 登录检查，若未登录跳转至登录页
	 */
	protected function mustLoginCheck($from = '', $action = '/index.php/acc/login') {
		if ($this->mustLogin && ! $this->isLogin ()) {
			if ($this->isAjax ()) {
				$cururl = $this->post ( 'cururl' );
				if (! $cururl) {
					$pathinfo = $_SERVER ['REQUEST_URI'];
					$returnUrl = urlencode ( $pathinfo );
				} else {
					$returnUrl = urlencode ( $cururl );
				}
				$url = Url::toRoute ( [ 
					$action,
					'from' => $from,
					'returnUrl' => $returnUrl 
				] );
				Util::ajax ( '100002', '请先登录，即将跳转至登录页...', $url );
			} else {
				// $pathinfo = trim ( $_SERVER ['PATH_INFO'], '/\\' );
				$pathinfo = $_SERVER ['REQUEST_URI'];
				$returnUrl = urlencode ( $pathinfo );
				$url = Url::toRoute ( [ 
					$action,
					'from' => $from,
					'returnUrl' => $returnUrl 
				] );
				return Yii::$app->getResponse ()->redirect ( $url )->send ();
			}
		}
	}

	protected function createQRC($data, $mongoid, $path, $prefix, $override = false) {
		// 纠错级别：L、M、Q、H
		// $level = 'L';
		// 点的大小：1到10,用于手机端4就可以了
		// $size = 4;
		// $margin=3;
		// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
		// 生成的文件名
		$fileName = $prefix . $mongoid . '.png';
		// 判断文件是否存在，存在返回二维码图片名字
		if (! $override && file_exists ( $path . $fileName )) {
			return $fileName;
		}
		require_once (Yii::$app->basePath . '/vendor/phpqrcode/qrlib.php');
		// 输出图片流
		// \QRcode::png($data, false, 'L', 4);
		// 生成图片
		$QRcode = new \QRcode ();
		$QRCimg = $QRcode->png ( $data, $path . $fileName, 'L', 6, 1 );
		return $fileName;
		// 显示出来
		// echo "<img src='http://erp/Public/Uploads/QrcPic/".$fileName."' />";
	}

	public function getDomain() {
		if (defined ( 'ENV_PRO' )) {
			$domain = "http://6666server.pn-cn.com:8080/";
			$domain = "http://10.10.112.93:8080/";
		} else {
			$domain = "http://192.168.88.60:8080/";
			$domain = "http://192.168.88.11:8081/";
			$domain = "http://123.120.85.202:8082/";
		}
		return $domain;
	}

	public function curlPost($param, $url, $headers = []) {
		$curl = new Curl ();
		$curl->setOption ( CURLOPT_HTTPHEADER, $headers );
		$curl->setOption ( CURLOPT_RETURNTRANSFER, 1 );
		$curl->setOption ( CURLOPT_POSTFIELDS, $param );
		$result = $curl->post ( $url );
		$result = json_decode ( $result, true );
		return $result;
	}

	public function curlGet($url) {
		$curl = new Curl ();
		$result = $curl->get ( $url );
		$result = json_decode ( $result, true );
		return $result;
	}
}
