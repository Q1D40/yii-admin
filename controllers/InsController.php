<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\models\Util;
use app\models\Base;
use app\models\Curl;
use app\models\Upload;
use app\models\Qiniu;

/**
 * 抓取管理
 */
class InsController extends BaseController {
	
	// public $access_token = '2030837575.c7fa050.06f6af596f344743b0c9fc8870cff539';
	
	/*
	 * public $tokens = [ '2030837575.c7fa050.06f6af596f344743b0c9fc8870cff539' ];
	 */
	public $api = 'https://api.instagram.com/v1/';

	public $layout = 'admin.php';

	public $pageSize = 30;

	public $statuses = [ 
		'-2' => '初始化中',
		'-1' => '抓取中',
		'0' => '暂停抓取',
		'2' => '抓取完毕,上传中',
		'3' => '上传完毕,待上线',
		'1' => "已上线",
		'4' => '已下线' 
	];

	/**
	 * ins获取token时的回调地址
	 */
	public function actionToken() {
	}

	/**
	 * 明星列表
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionIndex() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		$type = $this->get ( 'type', 'all' );
		$key = $this->get ( 'key' );
		$order = $this->get ( 'order', 'ctime asc' );
		$condition = [ ];
		if ($key) {
			$condition = [ 
				'LIKE',
				'cnname',
				$key 
			];
		}
		if ($type == 'untag') {
			$condition ['tags'] = null;
		}
		$count = Base::count ( $condition, Base::$cnartists );
		
		$list = Base::findPage ( $curPage, $this->pageSize, $condition, [ ], $order, Base::$cnartists );
		$tags = Base::findAll ( [ ], [ ], 'order_num asc', 0, Base::$cntags );
		foreach ( $tags as $item ) {
			$tmp [$item ['id']] = $item;
		}
		$tags = $tmp;
		$url = Url::toRoute ( "/index.php/ins/index?order={$order}&type={$type}&p" );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['tags'] = $tags;
		$this->viewData ['list'] = $list;
		$this->viewData ['currentPage'] = $curPage;
		$this->viewData ['count'] = $count;
		$this->viewData ['pageHtml'] = $pageHtml;
		$this->viewData ['order'] = $order;
		$this->viewData ['type'] = $type;
		$this->viewData ['statuses'] = $this->statuses;
		return $this->render ( 'index', $this->viewData );
	}

	/**
	 * 新增抓取
	 */
	public function actionAdd() {
		if ($this->isAjax ()) {
			$cnname = $this->post ( 'cnname' );
			$ins_url = $this->post ( 'ins_url' );
			$baike_url = $this->post ( 'baike_url' );
			$desc = $this->post ( 'desc' );
			$cnname = $this->post ( 'cnname' );
			$tag = trim ( $this->post ( 'tag', "" ) );
			$contentLevel = $this->post ( 'content_level', 0 );
			
			$data = [ ];
			$data ['cnname'] = $cnname;
			$data ['ins_url'] = $ins_url;
			$data ['group_id'] = '';
			$data ['group_name'] = '';
			$data ['baike_url'] = $baike_url;
			$data ['desc'] = $desc;
			$tmp = explode ( '/', trim ( $ins_url, '/' ) );
			$data ['username'] = $tmp [3];
			$exist = Base::findOne ( [ 
				'username' => $data ['username'] 
			], [ ], '', 1, Base::$cnartists );
			if ($exist) {
				Util::ajax ( '100001', '该明星已存在' );
			}
			$data ['content_level'] = intval ( $contentLevel );
			$data ['user_id'] = '';
			$data ['user_bio'] = '';
			$data ['user_website'] = '';
			$data ['profile_picture'] = '';
			$data ['user_full_name'] = '';
			$data ['avatar_key'] = '';
			$data ['followers'] = 0;
			$data ['min_id'] = 0;
			$data ['min_time'] = 0;
			$data ['max_id'] = 0;
			$data ['max_time'] = 0;
			$data ['curr_id'] = 0;
			$data ['curr_time'] = 0;
			$data ['curr_max_id'] = 0;
			$data ['curr_max_time'] = 0;
			$data ['ctime'] = time ();
			$data ['status'] = - 2;
			if ($tag) {
				$data [$tag] = true;
			}
			$res = Base::add ( $data, Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			$returnUrl = Url::toRoute ( '/index.php/ins' );
			Util::ajax ( '100000', '操作成功', $returnUrl );
		}
		$tags = Base::findAll ( [ ], [ ], 'order_num asc', 0, Base::$cntags );
		foreach ( $tags as $item ) {
			$tmp [$item ['id']] = $item;
		}
		$tags = $tmp;
		$this->viewData ['tags'] = $tags;
		return $this->render ( 'add', $this->viewData );
	}

	/**
	 * 批量增加
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionBatchadd() {
		if ($this->isAjax ()) {
			$list = trim ( $this->post ( 'batchinfo' ) );
			$tag = trim ( $this->post ( 'tag', "" ) );
			$list = Util::checkEmpty ( $list, '数据不能空' );
			$list = explode ( "\n", $list );
			$rows = [ ];
			foreach ( $list as $item ) {
				$data = [ ];
				$info = explode ( ",", $item );
				$ins_url = trim ( $info [2], '/' );
				$tmp = explode ( '/', $ins_url );
				$data ['cnname'] = $info [0];
				$data ['ins_url'] = $ins_url;
				$data ['group_id'] = '';
				$data ['group_name'] = '';
				$data ['baike_url'] = $info [3];
				$data ['desc'] = $info [1];
				$data ['username'] = $tmp [3];
				$exist = Base::findOne ( [ 
					'username' => $data ['username'] 
				], [ ], '', 1, Base::$cnartists );
				if ($exist) {
					continue;
				}
				$data ['user_id'] = '';
				$data ['user_bio'] = '';
				$data ['user_website'] = '';
				$data ['profile_picture'] = '';
				$data ['user_full_name'] = '';
				$data ['avatar_key'] = '';
				$data ['followers'] = 0;
				$data ['min_id'] = 0;
				$data ['min_time'] = 0;
				$data ['max_id'] = 0;
				$data ['max_time'] = 0;
				$data ['curr_id'] = 0;
				$data ['curr_time'] = 0;
				$data ['curr_max_id'] = 0;
				$data ['curr_max_time'] = 0;
				$data ['ctime'] = time ();
				$data ['status'] = - 2;
				if ($tag) {
					$data [$tag] = true;
				}
				$rows [] = $data;
			}
			$res = Base::batchAdd ( $rows, Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败', $res );
			}
			Util::ajax ( '100000', '操作成功', $res );
		}
		$tags = Base::findAll ( [ ], [ ], 'order_num asc', 0, Base::$cntags );
		foreach ( $tags as $item ) {
			$tmp [$item ['id']] = $item;
		}
		$tags = $tmp;
		$this->viewData ['tags'] = $tags;
		return $this->render ( 'batchadd', $this->viewData );
	}

	/**
	 * 编辑明星
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionEdit() {
		if ($this->isAjax ()) {
			$mid = $this->post ( 'mid' );
			$cnname = $this->post ( 'cnname' );
			$baike_url = $this->post ( 'baike_url' );
			$desc = $this->post ( 'desc' );
			$contentLevel = $this->post ( 'content_level', 0 );
			$data ['cnname'] = $cnname;
			$data ['baike_url'] = $baike_url;
			$data ['desc'] = $desc;
			$data ['content_level'] = intval ( $contentLevel );
			$res = Base::modify ( [ 
				'_id' => $mid 
			], $data, Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
		$mid = $this->get ( 'mid' );
		$info = Base::findOne ( [ 
			'_id' => $mid 
		], [ ], '', 1, Base::$cnartists );
		$this->viewData ['statuses'] = $this->statuses;
		$this->viewData ['info'] = $info;
		return $this->render ( 'edit', $this->viewData );
	}

	/**
	 * 删除
	 */
	public function actionRemove() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$uid = $this->post ( 'uid' );
			$res = Base::del ( [ 
				'_id' => $id 
			], Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			if ($uid) {
				Base::del ( [ 
					'user_id' => $uid 
				], Base::$cnresource );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 暂停抓取
	 */
	public function actionPause() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$uid = $this->post ( 'uid' );
			$res = Base::modify ( [ 
				'_id' => $id 
			], [ 
				'status' => 0 
			], Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 继续抓取
	 */
	public function actionGoon() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$uid = $this->post ( 'uid' );
			$res = Base::modify ( [ 
				'_id' => $id 
			], [ 
				'status' => - 1 
			], Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 上线
	 */
	public function actionOn() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$uid = $this->post ( 'uid' );
			$tags = $this->post ( 'tags' );
			if (! empty ( $tags )) {
				foreach ( $tags as $tag ) {
					$data [$tag] = true;
				}
			}
			$data ['status'] = 1;
			$res = Base::modify ( [ 
				'_id' => $id 
			], $data, Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			
			$res = Base::modify ( [ 
				'user_id' => $uid,
				'type' => "image",
				'status' => 4 
			], $data, Base::$cnresource );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 獲取單個明星信息
	 */
	public function actionGetuser() {
		if ($this->isAjax ()) {
			$userid = $this->post ( 'uid' );
			$user = Base::findOne ( [ 
				'user_id' => $userid 
			], [ ], '', 1, Base::$cnartists );
			$tags = Base::findAll ( [ ], [ ], '', '', Base::$cntags );
			$userTags = [ ];
			foreach ( $tags as $tag ) {
				if (isset ( $user [$tag ['field_name']] ) && $user [$tag ['field_name']]) {
					$userTags [] = $tag ['field_name'];
				}
			}
			Util::ajax ( '100000', 'ok', $userTags );
		}
	}

	/**
	 * 下线
	 */
	public function actionOff() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$uid = $this->post ( 'uid' );
			$res = Base::modify ( [ 
				'_id' => $id 
			], [ 
				'status' => 4 
			], Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			$res = Base::modify ( [ 
				'user_id' => $uid,
				'type' => "image" 
			], [ 
				'status' => 4 
			], Base::$cnresource );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 单个图片的管理 上下线、删除等
	 */
	public function actionImgmanage() {
		if ($this->isAjax ()) {
			$mid = $this->post ( 'mid' );
			$opt = $this->post ( 'opt' );
			switch ($opt) {
				case 'on' :
					// 图片上线
					$res = Base::modify ( [ 
						'_id' => $mid 
					], [ 
						'status' => 1 
					], Base::$cnresource );
					break;
				case 'off' :
					// 图片下线
					$res = Base::modify ( [ 
						'_id' => $mid 
					], [ 
						'status' => 4 
					], Base::$cnresource );
					break;
				case 'reset' :
					// 重新抓取图片
					$res = Base::modify ( [ 
						'_id' => $mid 
					], [ 
						'status' => 0 
					], Base::$cnresource );
					break;
				case 'rm' :
					// 图片移除
					$res = Base::del ( [ 
						'_id' => $mid 
					], Base::$cnresource );
					break;
				default :
					break;
			}
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 分类列表
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionTags() {
		$condition = [ ];
		$type = $this->get ( 'type', 1 );
		$status = $this->get ( 'status', - 1 );
		$condition ['type'] = ( int ) $type;
		if ($status != - 1) {
			$condition ['status'] = ( int ) $status;
		}
		$tags = Base::findAll ( $condition, [ ], 'order_num asc', 0, Base::$cntags );
		if ($tags) {
			foreach ( $tags as $tag ) {
				$tag ['artists'] = [ ];
				$tmpTags [$tag ['field_name']] = $tag;
			}
			$artists = Base::findAll ( [ ], [ ], '', 0, Base::$cnartists );
			foreach ( $artists as $item ) {
				foreach ( $tmpTags as $field => $tag ) {
					if (isset ( $item [$field] ) && $item [$field]) {
						$tmpTags [$field] ['artists'] [] = $item;
					}
				}
			}
		}
		$tags = $tmpTags;
		$this->viewData ['list'] = $tags;
		$this->viewData ['type'] = $type;
		$this->viewData ['status'] = $status;
		return $this->render ( 'tags', $this->viewData );
	}

	/**
	 * 添加分类
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionAddtag() {
		if ($this->isAjax ()) {
			$data = [ ];
			$type = $this->post ( 'type' );
			$name = $this->post ( 'name' );
			$desc = $this->post ( 'desc' );
			$field = $this->post ( 'field' );
			$level = $this->post ( 'level', 1 );
			$size = $this->post ( 'size', 1 );
			$content_level = $this->post('content_level',0);
			$order_num = $this->post ( 'order_num' );
			$start = $this->post ( 'start_time' );
			$start = $start ?  : date ( "Y-m-d H:i:s" );
			$end = $this->post ( 'end_time' );
			$end = $end ?  : "2099-01-01 00:00:00";
			
			$flagRadio = $this->post ( 'flag_radio', 'member' );
			if ($flagRadio == "url") {
				$activityUrl = $this->post ( 'activity_url' );
				Util::checkEmpty ( $activityUrl, '请填写活动URL地址' );
				$data ['flag'] = $flagRadio;
				$data ['url'] = $activityUrl;
			} else {
				$data ['flag'] = $flagRadio;
				$data ['url'] = '';
			}
			$id = Base::genId ( Base::$cntags );
			if (! $id) {
				Util::ajax ( '100001', '编号产生失败' );
			}
			$upload = new Upload ( array (
				'savePath' => $this->basePath . '/web/assets/banner',
				'maxSize' => 2097152,
				'override' => true,
				'allowedExts' => "*" 
			) );
			$key = '';
			if (! $upload->saveOne ( $id . time () )) {
				$errno = $upload->errno ();
				// Util::ajax ( '100001', $errno, $errno );
			} else {
				$info = $upload->getUploadFileInfo ();
				if (file_exists ( $info ['savepath'] . '/' . $info ['savename'] )) {
					$res = Qiniu::putFile ( 'assets/banner/' . $info ['savename'], $info ['savepath'] . '/' . $info ['savename'] );
					if ($res ['key']) {
						$key = $res ['key'];
					}
				}
			}
			$data ['id'] = $id;
			$data ['name'] = $name;
			$data ['desc'] = $desc;
			$data ['field_name'] = $field ?  : "tag_" . $id;
			$data ['banner_path'] = $info ['savepath'];
			$data ['banner_name'] = $info ['savename'];
			$data ['banner_key'] = $key;
			$data ['order_num'] = ( int ) $order_num;
			$data ['start_time'] = $start;
			$data ['start_timestamp'] = strtotime ( $start );
			$data ['end_time'] = $end;
			$data ['end_timestamp'] = strtotime ( $end );
			$data ['type'] = intval ( $type );
			$data ['level'] = intval ( $level );
			$data ['size'] = intval ( $size );
			$data ['content_level'] = $content_level;
			$data ['status'] = 0;
			$res = Base::add ( $data, Base::$cntags );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			$returnUrl = Url::toRoute ( [ 
				'/index.php/ins/edittag',
				'mid' => ( string ) $res 
			] );
			Util::ajax ( '100000', '操作成功', $returnUrl );
		}
		$type = $this->get ( 'type', 1 );
		$mid = $this->get ( 'mid' );
		$tagusers = [ ];
		if ($mid) {
			$tag = Base::findOne ( [ 
				'_id' => $mid 
			], [ ], '', 1, Base::$cntags );
			$field = $tag ['field_name'];
			$tagusers = Base::findAll ( [ 
				$field => true 
			], [ ], '', 0, Base::$cnartists );
		}
		$artists = Base::findAll ( [ 
			'status' => 1 
		], [ ], 'ctime desc', 0, Base::$cnartists );
		$this->viewData ['artists'] = $artists;
		$this->viewData ['tagusers'] = $tagusers;
		$this->viewData ['mid'] = $mid;
		$this->viewData ['type'] = $type;
		return $this->render ( 'addtag', $this->viewData );
	}

	/**
	 * 编辑分类
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionEdittag() {
		if ($this->isAjax ()) {
			$data = [ ];
			$mid = $this->post ( 'mid' );
			$type = $this->post ( 'type' );
			$name = $this->post ( 'name' );
			$desc = $this->post ( 'desc' );
			$level = $this->post ( 'level', 1 );
			$size = $this->post ( 'size', 1 );
			$content_level = $this->post('content_level',0);
			$order_num = $this->post ( 'order_num' );
			$start = $this->post ( 'start_time' );
			$end = $this->post ( 'end_time' );
			
			$flagRadio = $this->post ( 'flag_radio', 'member' );
			if ($flagRadio == "url") {
				$activityUrl = $this->post ( 'activity_url' );
				Util::checkEmpty ( $activityUrl, '请填写活动URL地址' );
				$data ['flag'] = $flagRadio;
				$data ['url'] = $activityUrl;
			} else {
				$data ['flag'] = $flagRadio;
				$data ['url'] = '';
			}
			$tag = Base::findOne ( [ 
				'_id' => $mid 
			], [ ], '', 1, Base::$cntags );
			if (! $tag) {
				Util::ajax ( '100001', '操作失败', 'info null' );
			}
			$key = $tag ['id'] . time ();
			$upload = new Upload ( array (
				'savePath' => $this->basePath . '/web/assets/banner',
				'maxSize' => 2097152,
				'override' => true,
				'allowedExts' => "*" 
			) );
			if (! $upload->saveOne ( $key )) {
				$errno = $upload->errno ();
				// Util::ajax ( '100001', $errno, $errno );
			} else {
				$info = $upload->getUploadFileInfo ();
				$data ['banner_path'] = $info ['savepath'];
				$data ['banner_name'] = $info ['savename'];
				$localFile = $info ['savepath'] . '/' . $info ['savename'];
				if (file_exists ( $localFile )) {
					// 删除原来的图片
					$res = Qiniu::delete ( $tag ['banner_key'] );
					// 新图片
					$res = Qiniu::putFile ( $key, $localFile );
					if ($res ['key']) {
						$key = $res ['key'];
					}
					$data ['banner_key'] = $key;
				}
			}
			$data ['name'] = $name;
			$data ['desc'] = $desc;
			$data ['level'] = intval ( $level );
			$data ['size'] = intval ( $size );
			$data ['content_level'] = $content_level;
			$data ['order_num'] = ( int ) $order_num;
			$data ['start_time'] = $start;
			$data ['start_timestamp'] = strtotime ( $start );
			$data ['end_time'] = $end;
			$data ['end_timestamp'] = strtotime ( $end );
			$res = Base::modify ( [ 
				'_id' => $mid 
			], $data, Base::$cntags );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			$returnUrl = Url::toRoute ( [ 
				'/index.php/ins/tags',
				'type' => $type 
			] );
			Util::ajax ( '100000', '操作成功', $returnUrl );
		}
		$mid = $this->get ( 'mid' );
		$tagusers = [ ];
		if ($mid) {
			$tag = Base::findOne ( [ 
				'_id' => $mid 
			], [ ], '', 1, Base::$cntags );
			$field = $tag ['field_name'];
			$tagusers = Base::findAll ( [ 
				$field => true 
			], [ ], '', 0, Base::$cnartists );
		}
		$info = Base::findOne ( [ 
			'_id' => $mid 
		], [ ], '', 1, Base::$cntags );
		$artists = Base::findAll ( [ 
			'status' => 1 
		], [ ], 'ctime desc', 0, Base::$cnartists );
		$this->viewData ['mid'] = $mid;
		$this->viewData ['info'] = $info;
		$this->viewData ['artists'] = $artists;
		$this->viewData ['tagusers'] = $tagusers;
		return $this->render ( 'edittag', $this->viewData );
	}

	/**
	 * 批量编辑标签
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionEdittags() {
		if ($this->isAjax ()) {
			$values = $this->post ( 'submits' );
			if (empty ( $values )) {
				Util::ajax ( '100001', '参数错误' );
			}
			foreach ( $values as $v ) {
				$data = [ ];
				$data ['order_num'] = ( int ) $v ['orderNum'];
				if ($v ['size']) {
					$data ['size'] = $v ['size'];
				}
				if ($v ['level']) {
					$data ['level'] = $v ['level'];
				}
				$data ['status'] = intval ( $v ['status'] );
				$data ['content_level'] = intval ( $v ['content_level'] );
				Base::modify ( [ 
					'_id' => $v ['id'] 
				], $data, Base::$cntags );
			}
			Util::ajax ( '100000', '操作成功' );
		}
		$type = $this->get ( 'type' );
		$tags = Base::findAll ( [ 
			'type' => ( int ) $type 
		], [ ], 'order_num asc', 0, Base::$cntags );
		$this->viewData ['list'] = $tags;
		$this->viewData ['type'] = $type;
		return $this->render ( 'edittags', $this->viewData );
	}

	/**
	 * 删除分类
	 */
	public function actionRemovetag() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$res = Base::del ( [ 
				'_id' => $id 
			], Base::$cntags );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 分类上线
	 */
	public function actionTagon() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$res = Base::modify ( [ 
				'_id' => $id 
			], [ 
				'status' => 1 
			], Base::$cntags );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 分类下线
	 */
	public function actionTagoff() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$res = Base::modify ( [ 
				'_id' => $id 
			], [ 
				'status' => 0 
			], Base::$cntags );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 改变顺序
	 */
	public function actionTagorder() {
		if ($this->isAjax ()) {
			$id = $this->post ( 'mid' );
			$order = $this->post ( 'order' );
			$res = Base::modify ( [ 
				'_id' => $id 
			], [ 
				'order_num' => ( int ) $order 
			], Base::$cntags );
			if (! $res) {
				Util::ajax ( '100001', '操作失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	public function actionSuggest() {
		if ($this->isAjax ()) {
			$name = $this->post ( 'name' );
			$list = Base::findAll ( [ 
				'LIKE',
				'cnname',
				$name 
			], [ ], '', 0, Base::$cnartists );
			$data = [ ];
			if ($list) {
				foreach ( $list as $item ) {
					$data [] = $item ['cnname'];
				}
			}
			Util::ajax ( '100000', '操作成功', implode ( ',', $data ) );
		}
	}

	/**
	 * 将某人加到某标签下
	 */
	public function actionTaguser() {
		if ($this->isAjax ()) {
			$tagid = $this->post ( 'tagid' );
			$tagid = Util::checkEmpty ( $tagid, '未选择分类吧' );
			$names = $this->post ( 'names' );
			$names = Util::checkEmpty ( $names, '未选择明星' );
			$names = explode ( ',', $names );
			$tag = Base::findOne ( [ 
				'_id' => $tagid 
			], [ ], '', 1, Base::$cntags );
			if (! $tag) {
				Util::ajax ( '100001', '分类不存在' );
			}
			$field = $tag ['field_name'];
			foreach ( $names as $name ) {
				$info = Base::findOne ( [ 
					'cnname' => $name 
				], [ ], '', 1, Base::$cnartists );
				if (! $info) {
					continue;
				}
				// 更新明星标签
				Base::modify ( [ 
					'user_id' => $info ['user_id'] 
				], [ 
					$field => true 
				], Base::$cnartists );
				// 更新素材标签
				Base::modify ( [ 
					'user_id' => $info ['user_id'] 
				], [ 
					$field => true 
				], Base::$cnresource );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 将某人从某标签下删除
	 */
	public function actionTaguserremove() {
		if ($this->isAjax ()) {
			$tagid = $this->post ( 'tagid' );
			$uid = $this->post ( 'uid' );
			$tag = Base::findOne ( [ 
				'_id' => $tagid 
			], [ ], '', 1, Base::$cntags );
			$field = $tag ['field_name'];
			// 更新明星标签
			$res = Base::modify ( [ 
				'user_id' => $uid 
			], [ 
				$field => null 
			], Base::$cnartists );
			if (! $res) {
				Util::ajax ( '100001', '更新明星标签失败' );
			}
			// 更新素材标签
			$res = Base::modify ( [ 
				'user_id' => $uid 
			], [ 
				$field => null 
			], Base::$cnresource );
			if (! $res) {
				Util::ajax ( '100001', '更新图片标签失败' );
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * ajax明星列表
	 */
	public function actionLoadstars() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		$key = $this->get ( 'key' );
		// 取数据
		$condition = [ 
			'AND' 
		];
		$key = $this->get ( 'key' );
		if ($key) {
			$condition [] = [ 
				'LIKE',
				'cnname',
				$key 
			];
		}
		$condition [] = [ 
			'status' => 1 
		];
		$result = Base::findPage ( $curPage, $this->pageSize, $condition, [ ], 'ctime asc', Base::$cnartists );
		// 组织分页html代码
		$count = Base::count ( $condition, Base::$cnartists );
		$url = Url::toRoute ( '/index.php/mbox/index?p' );
		$pageHtml = Util::pageHtmlAj ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['list'] = $result;
		$this->viewData ['currentPage'] = $curPage;
		$this->viewData ['count'] = $count;
		$this->viewData ['pageHtml'] = $pageHtml;
		$html = $this->renderFile ( '@app/views/ins/data.php', $this->viewData );
		Util::ajax ( '100000', '操作成功', $html );
	}

	/**
	 * 单个用户图片列表页
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionUlist() {
		$userId = $this->get ( 'uid' );
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		// 取数据
		$condition = [ ];
		$condition ['user_id'] = $userId;
		$user = Base::findOne ( $condition, [ ], '', 1, Base::$cnartists );
		$condition ['status'] = [ 
			'$in' => [ 
				1,
				4 
			] 
		];
		$list = Base::findPage ( $curPage, $this->pageSize, $condition, [ ], 'created_time desc', Base::$cnresource );
		if ($list) {
			foreach ( $list as &$item ) {
				$item ['img_url'] = Yii::$app->params ['qiniuDomain'] [Base::env ()] . $item ['qiniu_key'];
			}
		}
		// 组织分页html代码
		$count = Base::count ( $condition, Base::$cnresource );
		$url = Url::toRoute ( '/index.php/ins/ulist?uid=' . $userId . '&p' );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['user'] = $user;
		$this->viewData ['list'] = $list;
		$this->viewData ['currentPage'] = $curPage;
		$this->viewData ['count'] = $count;
		$this->viewData ['pageHtml'] = $pageHtml;
		return $this->render ( 'ulist', $this->viewData );
	}

	/**
	 * 还原明星成初始状态
	 */
	public function actionRestore() {
		$userid = $this->get ( 'uid' );
		if (! $userid) {
			exit ();
		}
		$userid = ( string ) $userid;
		$data = [ ];
		$data ['min_id'] = 0;
		$data ['min_time'] = 0;
		$data ['max_id'] = 0;
		$data ['max_time'] = 0;
		$data ['curr_id'] = 0;
		$data ['curr_time'] = 0;
		$data ['curr_max_id'] = 0;
		$data ['curr_max_time'] = 0;
		$data ['status'] = - 2;
		$res = Base::modify ( [ 
			'user_id' => $userid 
		], $data, Base::$cnartists );
		var_dump ( $res );
		// 删除素材
		$res = Base::del ( [ 
			'user_id' => $userid 
		], Base::$cnresource );
		var_dump ( $res );
		// 删除日志
		$res = Base::del ( [ 
			'user_id' => $userid 
		], Base::$cnInsLog );
		var_dump ( $res );
	}

	/**
	 * 重新抓取明星，之前的一切只是个梦
	 */
	public function actionRefetch() {
		if ($this->isAjax ()) {
			$userid = $this->post ( 'uid' );
			if (! $userid) {
				Util::ajax ( '100001', '未选择明星' );
			}
			$userid = ( string ) $userid;
			$data = [ ];
			$data ['min_id'] = 0;
			$data ['min_time'] = 0;
			$data ['max_id'] = 0;
			$data ['max_time'] = 0;
			$data ['curr_id'] = 0;
			$data ['curr_time'] = 0;
			$data ['curr_max_id'] = 0;
			$data ['curr_max_time'] = 0;
			$data ['media_counts'] = 0;
			$data ['image_counts'] = 0;
			$data ['other_counts'] = 0;
			$data ['image_percent'] = 0;
			$data ['status'] = - 2;
			$res = Base::modify ( [ 
				'user_id' => $userid 
			], $data, Base::$cnartists );
			// var_dump ( $res );
			// 删除素材
			$res = Base::del ( [ 
				'user_id' => $userid 
			], Base::$cnresource );
			// var_dump ( $res );
			// 删除日志
			$res = Base::del ( [ 
				'user_id' => $userid 
			], Base::$cnInsLog );
			// var_dump ( $res );
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * 活动贴纸列表
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionTlist() {
		$tagId = $this->get ( 'tid' );
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		// 取数据
		$condition = [ ];
		$condition ['activity_id'] = ( int ) $tagId;
		$list = Base::findPage ( $curPage, $this->pageSize, $condition, [ ], 'created_time desc', Base::$cnActivityFeeds );
		if ($list) {
			foreach ( $list as &$item ) {
				$item ['img_url'] = Yii::$app->params ['stickerQiniuDomain'] [Base::env ()] . $item ['qiniu_key'];
			}
		}
		// 组织分页html代码
		$count = Base::count ( $condition, Base::$cnActivityFeeds );
		$url = Url::toRoute ( '/index.php/ins/tlist?tid=' . $tagId . '&p' );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['tagId'] = $tagId;
		$this->viewData ['list'] = $list;
		$this->viewData ['pageHtml'] = $pageHtml;
		return $this->render ( 'tlist', $this->viewData );
	}

	/**
	 * 活动贴纸管理
	 */
	public function actionTagimgmanage() {
		if ($this->isAjax ()) {
			$mid = $this->post ( 'mid' );
			$opt = $this->post ( 'opt' );
			switch ($opt) {
				case 'on' :
					// 图片上线
					Base::modify ( [ 
						'_id' => $mid 
					], [ 
						'status' => 1 
					], Base::$cnActivityFeeds );
					break;
				case 'off' :
					// 图片下线
					Base::modify ( [ 
						'_id' => $mid 
					], [ 
						'status' => 4 
					], Base::$cnActivityFeeds );
					break;
				case 'rm' :
					// 图片移除
					Base::del ( [ 
						'_id' => $mid 
					], Base::$cnActivityFeeds );
					break;
				default :
					break;
			}
			Util::ajax ( '100000', '操作成功' );
		}
	}

	/**
	 * P图列表
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionFeedstickers() {
		$feedId = $this->get ( 'fid' );
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		// 取数据
		$condition = [ ];
		$condition ['feed_id'] = $feedId;
		$list = Base::findPage ( $curPage, $this->pageSize, $condition, [ ], 'created_time desc', Base::$cnActivityFeeds );
		if ($list) {
			foreach ( $list as &$item ) {
				$item ['img_url'] = Yii::$app->params ['stickerQiniuDomain'] [Base::env ()] . $item ['qiniu_key'];
			}
		}
		// 组织分页html代码
		$count = Base::count ( $condition, Base::$cnActivityFeeds );
		$url = Url::toRoute ( '/index.php/ins/feedstickers?fid=' . $feedId . '&p' );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['list'] = $list;
		$this->viewData ['pageHtml'] = $pageHtml;
		return $this->render ( 'tlist', $this->viewData );
	}

	private function fetch_url($url) {
		if (! $url) {
			exit ();
		}
		if (defined ( 'ENV_PRO' )) {
			$curlurl = "http://23.91.98.67:82/v1/spider/fetch_url";
		} else {
			$curlurl = "http://23.91.98.67/v1/spider/fetch_url";
		}
		$fields ['url'] = $url;
		$curl = new Curl ();
		$curl->setOption ( CURLOPT_RETURNTRANSFER, 1 );
		$curl->setOption ( CURLOPT_POSTFIELDS, http_build_query ( $fields ) );
		$fetch = $curl->post ( $curlurl );
		// var_dump($curl->responseCode);
		$curlRes = json_decode ( $fetch, true );
		return $curlRes;
	}

	private function to7niu($fields) {
		if (defined ( 'ENV_PRO' )) {
			$curlurl = "http://23.91.98.67:82/v1/spider/fetch";
		} else {
			$curlurl = "http://23.91.98.67/v1/spider/fetch";
		}
		$curl = new Curl ();
		$curl->setOption ( CURLOPT_RETURNTRANSFER, 1 );
		$curl->setOption ( CURLOPT_POSTFIELDS, http_build_query ( $fields ) );
		$fetch = $curl->post ( $curlurl );
		// var_dump($curl->responseCode);
		var_dump ( $curl->response );
		$curlRes = json_decode ( $fetch, true );
		return $curlRes;
	}
}