<?php

namespace app\controllers;

use Yii;
use app\models\Curl;
use app\models\Util;
use yii\helpers\Url;

/**
 * 评论管理
 */
class CommentController extends BaseController {

	public $layout = 'admin.php';

	public $pageSize = 30;

	/**
	 * 隐藏运营人员评论
	 */
	public $hideUids = [ 
		"1",
		"2",
		"3",
		"336",
		"407",
		"416",
		"428",
		"472",
		"605",
		"1313",
		"1437" 
	];

	public function actionIndex() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		$key = $this->get ( 'key' );
		$flag = $this->get ( 'flag', 'Y' );
        $type = $this->get('type', 'all');
		if ($key) {
			$result = $this->serachComments ( $key, $curPage, $this->pageSize, $flag );
		} else {
			// 取数据
			$result = $this->getComments ( $curPage, $this->pageSize, $flag, $this->hideUids, $type);
		}
		// 组织分页html代码
		$count = $result ['totalPages'] * $this->pageSize;
		$url = Url::toRoute ( "/index.php/comment/index?key={$key}&flag={$flag}&type={$type}&p" );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['list'] = $result ['result'];
		$this->viewData ['currentPage'] = $result ['currentPage'];
		$this->viewData ['totalPages'] = $result ['totalPages'];
		$this->viewData ['count'] = $count;
		$this->viewData ['pageHtml'] = $pageHtml;
		$this->viewData ['flag'] = $flag;
		$this->viewData ['type'] = $type;
		$this->viewData ['dataCount'] = $result ['dataCount'];
		$this->viewData ['realCount'] = $result ['robotCount'];
		return $this->render ( 'index', $this->viewData );
	}

	public function actionRemove() {
		$ids = $this->post ( 'ids' );
		$ids = explode ( ',', $ids );
		array_pop ( $ids );
		// print_r ( $ids );
		$res = $this->removeComments ( $ids );
		if ($res ['codeInfo'] != 'Success') {
			Util::ajax ( '100001', '操作失败' );
		}
		Util::ajax ( 100000, '操作成功' );
	}

	private function getComments($page, $pageSize, $flag = "Y", $uids = [], $barrageTypt) {
		$curl = new Curl ();
		// $url = $this->getDomain () . "pic_news/barrageManage/getPageData/{$page}/{$pageSize}";
		$url = $this->getDomain () . "pic_news/barrageManage/getPageDataV2/{$page}/{$pageSize}/{$flag}/{$barrageTypt}";
		$data ['uids'] = $uids;
		$result = $this->curlPost ( json_encode ( $data ), $url );
		return $result ['result'];
	}

	private function serachComments($key, $page, $pageSize, $flag) {
		$curl = new Curl ();
		$url = $this->getDomain () . "pic_news/barrageManage/getBarrageByLike/{$flag}";
		$query ['queryStr'] = $key;
		$result = $this->curlPost ( json_encode ( $query ), $url );
		return $result;
	}

	private function removeComments($bids) {
		$curl = new Curl ();
		$url = $this->getDomain () . "pic_news/barrageManage/removeBarrages";
		$fields ['bids'] = $bids;
		$curl->setOption ( CURLOPT_POSTFIELDS, json_encode ( $fields ) );
		$fetch = $curl->post ( $url );
		$result = json_decode ( $fetch, true );
		return $result;
	}

	public function actionUser() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		$uid = $this->get ( 'uid' );
		$url = $this->getDomain () . "pic_news/barrageManage/findBarrageByUid/{$uid}/{$curPage}/{$this->pageSize}";
		$result = $this->curlGet ( $url );
		// 组织分页html代码
		$count = $result ['result'] ['count'];
		$url = Url::toRoute ( "/index.php/comment/user?uid={$uid}&p" );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['list'] = $result ['result'] ['list'];
		$this->viewData ['pageHtml'] = $pageHtml;
		return $this->render ( 'user', $this->viewData );
	}
}
