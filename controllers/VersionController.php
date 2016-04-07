<?php

namespace app\controllers;

use Yii;
use app\models\Util;
use app\models\Redis;

/**
 * 版本管理
 */
class VersionController extends BaseController {

	public $layout = 'admin.php';

	public $versionFile = '/data/www/last_version';

	public $versionKey = 'last_version';

	public function actionIndex() {
		/* $version = "2.0.0";
		if (! file_exists ( $this->versionFile )) {
			file_put_contents ( $this->versionFile, $version );
		}
		$version = file_get_contents ( $this->versionFile );
		$this->viewData ['version'] = $version;
		return $this->render ( 'index', $this->viewData ); */
		
		$version = Redis::get ( $this->versionKey );
		$this->viewData ['version'] = $version;
		return $this->render ( 'index', $this->viewData );
	}

	public function actionAdd() {
		if ($this->isAjax ()) {
			$version = $this->post ( 'version' );
			// file_put_contents ( $this->versionFile, $version );
			Redis::set ( $this->versionKey, $version );
			Util::ajax ();
		}
	}
}