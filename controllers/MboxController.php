<?php

namespace app\controllers;

use Yii;
use app\models\Util;
use yii\helpers\Url;
use app\models\Curl;

/**
 * 影视盒子
 */
class MboxController extends BaseController {

	public $layout = 'admin.php';

	public $pageSize = 10;

	public function actionIndex() {
		$list = $this->getData ();
		$this->viewData ['list'] = $list;
		return $this->render ( 'index', $this->viewData );
	}

	private function getData() {
		$curl = new Curl ();
		$url = $this->getDomain () . "pic_news/news/getDataByType";
		$result = $curl->get ( $url );
		if (! $result) {
			exit ( '可能接口错误' );
		}
		$result = json_decode ( $result, true );
		$list = [ ];
		foreach ( $result ['result'] as $item ) {
			$list [$item ['newsType'] ['typeId']] ['id'] = $item ['id'];
			$list [$item ['newsType'] ['typeId']] ['typeId'] = $item ['newsType'] ['typeId'];
			$list [$item ['newsType'] ['typeId']] ['typeName'] = $item ['newsType'] ['name'];
			$list [$item ['newsType'] ['typeId']] ['newsNum'] = $item ['newsType'] ['newsNum'];
			$list [$item ['newsType'] ['typeId']] ['topics'] [] = [ 
				'id' => $item ['topic'] ['id'],
				'title' => $item ['topic'] ['title'] 
			];
		}
		return $list;
	}

	public function actionLoadnews() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		// 取数据
		$result = $this->getNew ( $curPage, $this->pageSize );
		// 组织分页html代码
		$count = $result ['totalPages'] * $this->pageSize;
		$url = Url::toRoute ( '/index.php/mbox/index?p' );
		$pageHtml = Util::pageHtmlAj ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['list'] = $result ['result'];
		$this->viewData ['currentPage'] = $result ['currentPage'];
		$this->viewData ['totalPages'] = $result ['totalPages'];
		$this->viewData ['count'] = $count;
		$this->viewData ['pageHtml'] = $pageHtml;
		$html = $this->renderFile ( '@app/views/mbox/data.php', $this->viewData );
		Util::ajax ( '100000', '操作成功', $html );
	}

	public function actionSave() {
		if ($this->isAjax ()) {
			$types = $this->getData ();
			$typeid = $this->post ( 'typeid' );
			$ids = $this->post ( 'ids' );
			$ids = explode ( ',', $ids );
			array_pop ( $ids );
			if (empty ( $ids )) {
				Util::ajax ( '100001', '未选择任何新闻' );
			}
			if (count ( $ids ) > $types [$typeid] ['newsNum']) {
				// Util::ajax ( 100001, "选择的新闻条数超过限制，最多{$types [$typeid] ['newsNum']}条" );
			}
			$res = $this->update ( $typeid, $ids );
			if ($res ['codeInfo'] != 'Success') {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( 100000, '操作成功' );
		}
	}

	private function getNew($page, $pageSize) {
		$curl = new Curl ();
		$url = $this->getDomain () . "pic_news/topic/getPageData/{$page}/{$pageSize}";
		$result = $curl->get ( $url );
		$result = json_decode ( $result, true );
		return $result ['result'];
	}

	private function update($typeid, $ids) {
		$curl = new Curl ();
		$url = $this->getDomain () . "pic_news/news/hao123/replaceNews/{$typeid}";
		$fields ['ids'] = $ids;
		$curl->setOption ( CURLOPT_POSTFIELDS, json_encode ( $fields ) );
		$fetch = $curl->post ( $url );
		$result = json_decode ( $fetch, true );
		return $result;
	}
}