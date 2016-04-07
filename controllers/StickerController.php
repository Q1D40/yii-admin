<?php

namespace app\controllers;

use Yii;
use app\models\Base;
use yii\helpers\Url;
use app\models\Util;
use app\models\Upload;
use app\models\Qiniu;

/**
 * 贴纸管理
 */
class StickerController extends BaseController {

	public $layout = 'admin.php';

	public function actionIndex() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		$group = $this->get ( 'group' );
		$condition = [ ];
		if ($group) {
			$condition ['group'] = ( int ) $group;
		}
		$count = Base::count ( $condition, Base::$cnSticker );
		$list = Base::findPage ( $curPage, $this->pageSize, $condition, [ ], 'order_num asc', Base::$cnSticker );
		$url = Url::toRoute ( "/index.php/sticker/index?group={$group}&p" );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$groups = Base::findAll ( [ ], [ ], 'created_time asc', 0, Base::$cnStickerGroup );
		
		$this->viewData ['groups'] = $groups;
		$this->viewData ['list'] = $list;
		$this->viewData ['pageHtml'] = $pageHtml;
		$this->viewData ['curGroup'] = $group;
		return $this->render ( 'index', $this->viewData );
	}

	/**
	 * 添加贴纸
	 */
	public function actionAdd() {
		if ($this->isAjax ()) {
			$list = trim ( $this->post ( 'data' ) );
			$list = Util::checkEmpty ( $list, '数据不能空' );
			$list = explode ( "\n", $list );
			$group = $this->post ( 'group' );
			$group = Util::checkEmpty ( $group, '选择分组' );
			$rows = [ ];
			foreach ( $list as $item ) {
				if ($item) {
					$tmp = explode ( ',', $item );
					$data = [ ];
					$data ['id'] = Base::genId ( Base::$cnSticker );
					$data ['name'] = $tmp [0];
					$data ['url'] = $tmp [1];
					$data ['group'] = ( int ) $group;
					$data ['order_num'] = 0;
					$data ['created_time'] = time ();
					$data ['status'] = 1;
					$rows [] = $data;
				}
			}
			Base::batchAdd ( $rows, Base::$cnSticker );
			Util::ajax ( '100000', '操作成功', Url::toRoute ( '/sticker' ) );
		}
		$groups = Base::findAll ( [ ], [ ], '', 0, Base::$cnStickerGroup );
		$this->viewData ['groups'] = $groups;
		return $this->render ( 'add', $this->viewData );
	}

	/**
	 * 批量编辑
	 */
	public function actionEdits() {
		if ($this->isAjax ()) {
			$values = $this->post ( 'submits' );
			if (empty ( $values )) {
				Util::ajax ( '100001', '参数错误' );
			}
			foreach ( $values as $v ) {
				$data = [ ];
				$data ['order_num'] = ( int ) $v ['orderNum'];
				$data ['name'] = $v ['name'];
				Base::modify ( [ 
					'_id' => $v ['id'] 
				], $data, Base::$cnSticker );
			}
			Util::ajax ( '100000', '操作成功' );
		}
		$curGroup = $this->get ( 'group' );
		$condition = [ ];
		if ($curGroup) {
			$condition ['group'] = ( int ) $curGroup;
		}
		$stickers = Base::findAll ( $condition, [ ], 'order_num asc', 0, Base::$cnSticker );
		$this->viewData ['list'] = $stickers;
		return $this->render ( 'edits', $this->viewData );
	}

	/**
	 * 系统贴纸管理
	 */
	public function actionStickermanage() {
		if ($this->isAjax ()) {
			$opt = $this->post ( 'opt' );
			$mid = $this->post ( 'mid' );
			switch ($opt) {
				case "on" :
					$data ['status'] = 1;
					break;
				case "off" :
					$data ['status'] = 0;
					break;
			}
			Base::modify ( [ 
				'_id' => $mid 
			], $data, Base::$cnSticker );
			Util::ajax ( '100000', '操作成功', Url::toRoute ( '/sticker' ) );
		}
	}

	/**
	 * 分组列表
	 */
	public function actionGroup() {
        $type = $this->get('type');
		$condition = ($type)?['type' => $type]:[];
		$count = Base::count ( $condition, Base::$cnSticker );
		$list = Base::findAll ( $condition, [ ], 'order_num asc', 0, Base::$cnStickerGroup );
		$this->viewData ['list'] = $list;
		$this->viewData ['type'] = $type;
		return $this->render ( 'group', $this->viewData );
	}

	/**
	 * 添加贴纸分组
	 */
	public function actionGroupadd() {
		if ($this->isAjax ()) {
			$name = $this->post ( 'name' );
			$type = $this->post ( 'type' );
			$id = Base::genId ( Base::$cnStickerGroup );
			if (! $id) {
				Util::ajax ( '100001', '编号产生失败' );
			}
			$data ['id'] = $id;
			$data ['name'] = $name;
			$data ['type'] = $type;
			$data ['created_time'] = time ();
			$data ['status'] = 0;
			Base::add ( $data, Base::$cnStickerGroup );
			Util::ajax ( '100000', '操作成功', Url::toRoute ( '/sticker/group' ) );
		}
		return $this->render ( 'groupadd', $this->viewData );
	}

	/**
	 * 批量编辑
	 */
	public function actionGroupedits() {
		if ($this->isAjax ()) {
			$values = $this->post ( 'submits' );
			if (empty ( $values )) {
				Util::ajax ( '100001', '参数错误' );
			}
			foreach ( $values as $v ) {
				$data = [ ];
				$data ['order_num'] = ( int ) $v ['orderNum'];
				$data ['name'] = $v ['name'];
				Base::modify ( [
					'_id' => $v['id']
				], $data, Base::$cnStickerGroup);
			}
			Util::ajax ( '100000', '操作成功' );
		}
		$type = $this->get('type');
		$condition = ($type)?['type' => $type]:[];
		$stickers = Base::findAll ( $condition, [ ], 'order_num asc', 0, Base::$cnStickerGroup);
		$this->viewData ['list'] = $stickers;
		return $this->render ( 'groupedits', $this->viewData );
	}

	public function actionDel() {
		if ($this->isAjax ()) {
			$mid = $this->post ( 'mid' );
			Base::del ( [ 
				'_id' => $mid 
			], Base::$cnSticker );
			Util::ajax ( '100000', '操作成功' );
		}
		return $this->render ( 'add', $this->viewData );
	}

	/**
	 * 贴纸分组管理
	 */
	public function actionGroupmanage() {
		if ($this->isAjax ()) {
			$opt = $this->post ( 'opt' );
			$mid = $this->post ( 'mid' );
			switch ($opt) {
				case "on" :
					$data ['status'] = 1;
					break;
				case "off" :
					$data ['status'] = 0;
					break;
			}
			Base::modify ( [ 
				'_id' => $mid 
			], $data, Base::$cnStickerGroup );
			Util::ajax ( '100000', '操作成功', Url::toRoute ( '/sticker/group' ) );
		}
	}

	/**
	 * 后台活动分类新增贴纸图片
	 */
	public function actionAddfeed() {
		if ($this->isAjax ()) {
			$activityId = $this->post ( 'activity_id' );
			if (! $activityId) {
				Util::ajax ( '100001', 'activity_id 為空' );
			}
			$upload = new Upload ( array (
				'savePath' => $this->basePath . '/web/assets/tmp',
				'maxSize' => 2097152,
				'override' => true,
				'allowedExts' => "*" 
			) );
			$qiniuKey = '';
			$name = uniqid () . time ();
			if (! $upload->saveOne ( $name )) {
				$errno = $upload->errno ();
				Util::ajax ( '100001', $errno, $errno );
			} else {
				$info = $upload->getUploadFileInfo ();
				if (file_exists ( $info ['savepath'] . '/' . $info ['savename'] )) {
					$res = Qiniu::putFile ( $name, $info ['savepath'] . '/' . $info ['savename'], 'stickerQiniu' );
					if ($res ['key']) {
						$qiniuKey = $res ['key'];
					}
				}
			}
			$data ['activity_id'] = ( int ) $activityId;
			$data ['feed_id'] = "";
			$user = $this->getRandUid ();
			if ($user ['code'] != 0) {
				Util::ajax ( '100001', $user ['codeInfo'] );
			}
			$data ['uid'] = $user ['result'] ['uid'];
			$data ['content'] = "";
			$data ['qiniu_key'] = $qiniuKey;
			$data ['likes'] = 0;
			$data ['created_time'] = time ();
			$data ['cdate'] = date ( 'Y-m-d', time () );
			$data ['status'] = 1;
			Base::add ( $data, Base::$cnActivityFeeds );
			Util::ajax ( '100000', '操作成功', Url::toRoute ( [ 
				"/index.php/sticker/addfeed",
				'activity_id' => $activityId 
			] ) );
		}
		$activityId = $this->get ( 'activity_id' );
		if (! $activityId) {
			exit ();
		}
		$this->viewData ['activityId'] = $activityId;
		return $this->render ( 'addfeed', $this->viewData );
	}

	private function getRandUid() {
		$data ['time'] = time ();
		$json = json_encode ( $data );
		$url = $this->getDomain () . "pic_news/users/randomRobot?md5=" . md5 ( $json . '220cf73aace658bb' );
		$result = $this->curlPost ( $json, $url );
		return $result;
	}
}
