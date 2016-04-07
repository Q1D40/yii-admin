<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\models\Util;
use app\models\Base;

/**
 * 抓取日志
 */
class InslogController extends BaseController {

	public $layout = 'admin.php';

	public $pageSize = 30;

	public function actionIndex() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		$userid = $this->get ( 'uid' );
		$action = $this->get ( "action" );
		$errType = $this->get ( "err_type" );
		$condition = [ ];
		if ($userid) {
			$condition ['user_id'] = $userid;
		}
		if ($action) {
			$condition ['action'] = $action;
		}
		if ($errType) {
			$condition ['err_type'] = $errType;
		}
		$count = Base::count ( $condition, Base::$cnInsLog );
		$list = Base::findPage ( $curPage, $this->pageSize, $condition, [ ], 'start_time desc', Base::$cnInsLog );
		$url = Url::toRoute ( "/index.php/inslog/index?uid={$userid}&action={$action}&err_type={$errType}&p" );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['list'] = $list;
		$this->viewData ['pageHtml'] = $pageHtml;
		return $this->render ( 'index', $this->viewData );
	}
}