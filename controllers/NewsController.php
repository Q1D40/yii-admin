<?php

namespace app\controllers;

use Yii;
use app\models\Curl;
use app\models\Util;
use yii\helpers\Url;
use app\models\Upload;
use app\models\Qiniu;

/**
 * 新闻管理
 */
class NewsController extends BaseController {

	public $layout = 'admin.php';

	public $pageSize = 30;

	private $authStr = "220cf73aace658bb";

	/**
	 * 新闻列表页
	 */
	public function actionIndex() {
		$curPage = intval ( $this->get ( 'p', 1 ) );
		$curPage = $curPage < 1 ? 1 : $curPage;
		$type = $this->get ( 'type', 'all' );
		$key = $this->get ( 'key', '' );
		// 取数据
		$result = $this->getNews ( $type, $curPage, $this->pageSize, $key );
		// 组织分页html代码
		$count = $result ['count'];
		$url = Url::toRoute ( "/index.php/news/index?type={$type}&p" );
		$pageHtml = Util::pageHtml ( $curPage, $this->pageSize, $count, $url );
		$this->viewData ['list'] = $result ['list'];
		$this->viewData ['count'] = $count;
		$this->viewData ['pageHtml'] = $pageHtml;
		$this->viewData ['type'] = $type;
		return $this->render ( 'index', $this->viewData );
	}

	/**
	 * 新增新闻
	 */
	public function actionAdd() {
		if ($this->isAjax ()) {
			$title = $this->post ( 'title' );
			Util::checkEmpty ( $title, '请填写标题' );
			// $content = trim ( $this->post ( 'content' ) );
			$sourceName = $this->post ( 'sourceName' );
			$source = $this->post ( 'source' );
			$level = $this->post ( 'level' );
			$publishTimeRadio = $this->post ( 'publish-time-radio' );
			$contentLevel = $this->post ( 'content_level' );
			$data = [ ];
			if ($publishTimeRadio == "timer") {
				$timer = $this->post ( 'publish-time' );
				$data ['timer'] = $timer;
			} else {
				$data ['timer'] = date("Y-m-d H:i", time());
			}
			Util::checkEmpty ( $data ['timer'], '请选择发布时间' );
			// $path = '/data/resource/news';
			// 上传封面截图到七牛
			$x = ( int ) $this->post ( 'x' );
			$y = ( int ) $this->post ( 'y' );
			$w = ( int ) $this->post ( 'w' );
			$h = ( int ) $this->post ( 'h' );
			$coverImg = $this->post ( 'cover_img' );
			Util::checkEmpty ( $coverImg, '请设置封面图片' );
			$path = $this->basePath . '/web/assets/news';
			if ($x || $y || $w || $h) {
				$coverImg = $this->crop ( $coverImg, $x, $y, $w, $h );
			}
			$coverKey = '';
			if (file_exists ( $path . '/' . $coverImg )) {
				$res = Qiniu::putFile ( 'assets/news/cover/' . $coverImg, $path . '/' . $coverImg, 'news_qiniu' );
				if (! isset ( $res ['key'] ) || ! $res ['key']) {
					Util::ajax ( '100001', '封面上传七牛失败' );
				}
				$coverKey = $res ['key'];
			} else {
				Util::ajax ( '100001', '封面上传失败' );
			}
			
			$imagesStr = $this->post ( 'images' );
			Util::checkEmpty ( $imagesStr, "请上传图集" );
			$imagesStr = explode ( ';', $imagesStr );
            foreach ( $imagesStr as $row ) {
                $r = explode("|", $row);
                $imgSort[$r[0]] = $r[1];
            }
            ksort($imgSort);
			foreach ( $imgSort as $key ) {
				if ($key) {
					$img = [ ];
					$img ['content'] = "";
					$img ['imageJson'] ['imageUrl'] = $key;
					$images [] = $img;
				}
			}
			$curUser = $this->getCurUser ();
			$data ['author'] = $curUser ['userName'];
			$data ['cover'] ['imageUrl'] = $coverKey;
			$data ['title'] = $title;
			$data ['source'] = $source;
			$data ['grade'] = ( int ) $contentLevel;
			$data ['sourceName'] = $sourceName;
			$data ['images'] = $images;
			$data ['content'] = "";
			$json = json_encode ( $data );
			$url = $this->getDomain () . "pic_news/topic/save/v2?md5=" . md5 ( $json . '220cf73aace658bb' );
			$result = $this->curlPost ( $json, $url );
			if ($result ['code'] != 0) {
				Util::ajax ( '100001', $result ['codeInfo'] );
			}
			//$url = Url::toRoute ( [ 
			//	'/index.php/news/addimgs',
			//	'topicid' => $result ['result'] 
			//] );
            $url = Url::toRoute ( [ 
            	'/index.php/news',
            	'type' => 'all'
            ] );
			Util::ajax ( '100000', $result ['codeInfo'], $url );
		}
		return $this->render ( 'add', $this->viewData );
	}

	/**
	 * 新增新聞第二步
	 */
	public function actionAddimgs() {
		if ($this->isAjax ()) {
			$topicId = $this->post ( 'topicid' );
			$submits = $this->post ( 'submits' );
			$images = [ ];
			if ($submits) {
				foreach ( $submits as $item ) {
					$img = [ ];
					$img ['content'] = $item ['content'];
					$img ['imageJson'] ['imageUrl'] = str_replace ( 'http://7xki3m.com1.z0.glb.clouddn.com/', '', $item ['img'] );
					$images [] = $img;
				}
			}
			$data ['images'] = $images;
			$json = json_encode ( $data );
			$url = $this->getDomain () . "pic_news/topic/web/update/{$topicId}?md5=" . md5 ( $json . $this->authStr );
			$result = $this->curlPost ( $json, $url );
			if ($result ['code'] != 0) {
				Util::ajax ( '100001', $result ['codeInfo'] );
			}
			$url = Url::toRoute ( '/index.php/news' );
			Util::ajax ( '100000', $result ['codeInfo'], $url );
		}
		$topicId = $this->get ( 'topicid' );
		$topic = $this->getTopic ( $topicId );
		$this->viewData ['topic'] = $topic;
		return $this->render ( 'addimgs', $this->viewData );
	}

	/**
	 * 编辑新闻
	 */
	public function actionEdit() {
		if ($this->isAjax ()) {
			$topicId = $this->post ( 'topicid' );
			$title = $this->post ( 'title' );
			$sourceName = $this->post ( 'sourceName' );
			$source = $this->post ( 'source' );
			$contentLevel = $this->post ( 'content_level', 0 );
			$data ['title'] = $title;
			$data ['source'] = $source;
			$data ['sourceName'] = $sourceName;
			$data ['grade'] = ( int ) $contentLevel;
			$json = json_encode ( $data );
			$url = $this->getDomain () . "pic_news/topic/web/update/{$topicId}?md5=" . md5 ( $json . '220cf73aace658bb' );
			$result = $this->curlPost ( $json, $url );
			if ($result ['code'] != 0) {
				Util::ajax ( '100001', $result ['codeInfo'] );
			}
			Util::ajax ( '100000', $result ['codeInfo'] );
		}
		$topicId = $this->get ( 'topicid' );
		$topic = $this->getTopic ( $topicId );
		$topic ['id'] = $topicId;
		$this->viewData ['topic'] = $topic;
		return $this->render ( 'edit', $this->viewData );
	}

	/**
	 * 推送新闻
	 */
	public function actionPush() {
		$topicId = $this->post ( 'topicid' );
		$content = $this->post ( 'content' );
		$data ['content'] = $content;
		$data ['isMute'] = "N";
		print_r ( $data );
		exit ();
		$url = $this->getDomain () . "pic_news/device/sendPush/{$topicId}";
		$result = $this->curlPost ( json_encode ( $data ), $url );
		if ($result ['code'] != 0) {
			Util::ajax ( '100001', $result ['codeInfo'] );
		}
		Util::ajax ( '100000', $result ['codeInfo'] );
	}

	/**
	 * 单个新闻詳情
	 *
	 * @param unknown $topicId        	
	 * @return boolean mixed
	 */
	private function getTopic($topicId) {
		$url = $this->getDomain () . "pic_news/php/getDataById/{$topicId}";
		$result = $this->curlGet ( $url );
		if ($result ['code'] != 0) {
			return false;
		}
		$topic = $result ['result'];
		return $topic;
	}

	/**
	 * 新闻上下线、删除
	 */
	public function actionManage() {
		if ($this->isAjax ()) {
			$opt = $this->post ( "opt" );
			$topicId = $this->post ( 'topicid' );
			switch ($opt) {
				case 'rm' :
					$url = $this->getDomain () . "pic_news/topic/online/{$topicId}/D";
					break;
				case 'on' :
					$url = $this->getDomain () . "pic_news/topic/online/{$topicId}/Y";
					break;
				case 'off' :
					$url = $this->getDomain () . "pic_news/topic/online/{$topicId}/N";
					break;
			}
			$result = $this->curlGet ( $url );
			if ($result ['code'] != 0) {
				Util::ajax ( '100001', $result ['codeInfo'] );
			}
			Util::ajax ( '100000', $result ['codeInfo'] );
		}
	}

	/**
	 * 弹幕管理
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionDanmu() {
		$topicId = $this->get ( 'topicid' );
		$topic = $this->getTopic ( $topicId );
		if (! $topic) {
			echo json_encode ( $topic );
			exit ();
		}
		$cover ['imageId'] = $topic ['coverId'];
		$cover ['imageUrl'] = $topic ['coverImage'];
		$cover ['barrage'] = $topic ['coverBarrage'];
		array_unshift ( $topic ['images'], $cover );
		foreach ( $topic ['images'] as $k => $img ) {
			$images [$k] = $img ['imageId'];
		}
		$imageId = $this->get ( 'imgid' );
		$index = array_search ( $imageId, $images );
		$pre = $index - 1;
		$pre = ($pre > 0) ? $pre : 0;
		$next = $index + 1;
		$next = ($next >= count ( $images )) ? 0 : $next;
		$data ['time'] = time ();
        $data["request_os"] = 1;
		$json = json_encode ( $data );
		$url = $this->getDomain () . "pic_news/comment/findComments/{$topicId}/{$imageId}?md5=" . md5 ( $json . '220cf73aace658bb' );
		$result = $this->curlPost ( $json, $url );
		if ($result ['code'] != 0) {
			echo $result ['codeInfo'];
			exit ();
		}
		$image = $topic ['images'] [$index];
		$this->viewData ['topic'] = $topic;
		$this->viewData ['pre'] = $images [$pre];
		$this->viewData ['next'] = $images [$next];
		$this->viewData ['image'] = $image;
		$this->viewData ['index'] = $index;
		return $this->render ( 'danmu', $this->viewData );
	}

	/**
	 * 添加弹幕
	 */
	public function actionAdddanmu() {
		if ($this->isAjax ()) {
			$content = $this->post ( 'content' );
			$topicId = $this->post ( 'topicid' );
			$imageId = $this->post ( 'imgid' );
			$xAxis = $this->post ( 'xAxis' );
			$yLine = $this->post ( 'yLine' );
			$data ['content'] = $content;
			$data ['topicId'] = $topicId;
			$data ['imageId'] = $imageId;
			$data ['x_axis'] = $xAxis?$xAxis:0;
			$data ['y_line'] = $yLine?$yLine:0;
			$json = json_encode ( $data );
			$url = $this->getDomain () . "pic_news/comment/robotCreate?md5=" . md5 ( $json . '220cf73aace658bb' );
			$result = $this->curlPost ( $json, $url );
			if ($result ['code'] != 0) {
				Util::ajax ( '100001', $result ['codeInfo'] );
			}
			Util::ajax ( '100000', $result ['codeInfo'], $result ['result'] ['commentId'] );
		}
	}

    /**
     * 更新图片描述
     */
    public function actionImgContentUpdate() {
        if ($this->isAjax ()) {
            $content = $this->post ( 'content' );
            $iid = $this->post ( 'iid' );
            $data ['content'] = $content;
            $json = json_encode ( $data );
            $url = $this->getDomain () . "pic_news/image/update/{$iid}?md5=" . md5 ( $json . '220cf73aace658bb' );
            $result = $this->curlPost ( $json, $url );
            if ($result ['code'] != 0) {
                Util::ajax ( '100001', $result ['codeInfo'] );
            }
            Util::ajax ( '100000', $result ['codeInfo']);
        }
    }

	/**
	 * 删除弹幕
	 */
	public function actionRmdanmu() {
		if ($this->isAjax ()) {
			$commentId = $this->post ( 'commentid' );
			$data ['time'] = time ();
			$json = json_encode ( $data );
			$url = $this->getDomain () . "pic_news/comment/removeCommentByAdmin/{$commentId}?md5=" . md5 ( $json . '220cf73aace658bb' );
			$json = json_encode ( $data );
			$result = $this->curlPost ( $json, $url );
			if ($result ['code'] != 0) {
				Util::ajax ( '100001', $result ['codeInfo'] );
			}
			Util::ajax ( '100000', $result ['codeInfo'], $result ['result'] ['commentId'] );
		}
	}

	/**
	 * 获取新闻列表
	 */
	private function getNews($type, $page, $pageSize, $keyword = "") {
		$curl = new Curl ();
		if ($type == "search") {
			$url = $this->getDomain () . "pic_news/topic/getTopicByLike/{$page}/{$pageSize}";
			$data ['title'] = $keyword;
			$result = $this->curlPost ( json_encode ( $data ), $url );
			return $result ['result'];
		}
		switch ($type) {
			case 'all' :
				$url = $this->getDomain () . "pic_news/topic/getTopicByPage/{$page}/{$pageSize}";
				break;
			case 'on' :
				$url = $this->getDomain () . "pic_news/topic/getTopicOnlineByPage/{$page}/{$pageSize}";
				break;
			case 'off' :
				$url = $this->getDomain () . "pic_news/topic/getTopicOfflineByPage/{$page}/{$pageSize}";
				break;
			case 'timer' :
				$url = $this->getDomain () . "pic_news/topic/getQueue/{$page}/{$pageSize}";
				break;
		}
		$result = $curl->get ( $url );
		$result = json_decode ( $result, true );
		return $result ['result'];
	}

	/**
	 * 上传封面
	 */
	public function actionUploadcover() {
		$path = $this->basePath . '/web/assets/news';
		$upload = new Upload ( array (
			'savePath' => $path,
			'maxSize' => 2097152,
			'override' => true,
			'allowedExts' => "*" 
		) );
		$filename = uniqid () . time ();
		if (! $upload->saveOne ( $filename )) {
			$errno = $upload->errno ();
			Util::ajax ( '100001', $errno, $errno );
		} else {
			$info = $upload->getUploadFileInfo ();
			$return ['imgSrc'] = $this->cdnUrl . 'assets/news/' . $info ['savename'];
			$return ['imgKey'] = '';
			$return ['imgName'] = $info ['savename'];
			Util::ajax ( '100000', 'ok', $return );
		}
	}

	/**
	 * 图片上传
	 */
	public function actionUploadimgs() {
		$path = $this->basePath . '/web/assets/news';
		$upload = new Upload ( array (
			'savePath' => $path,
			'maxSize' => 2097152,
			'override' => true,
			'allowedExts' => "*" 
		) );
		$filename = uniqid () . time ();
		$key = "";
		if (! $upload->saveOne ( $filename )) {
			$errno = $upload->errno ();
			Util::ajax ( '100001', $errno, $errno );
		} else {
			$info = $upload->getUploadFileInfo ();
			if (file_exists ( $info ['savepath'] . '/' . $info ['savename'] )) {
				$res = Qiniu::putFile ( 'assets/news/' . $info ['savename'], $info ['savepath'] . '/' . $info ['savename'], 'news_qiniu' );
				if (! isset ( $res ['key'] ) || ! $res ['key']) {
					Util::ajax ( '100001', '上传七牛失败' );
				}
				$key = $res ['key'];
			} else {
				Util::ajax ( '100001', '上传失败' );
			}
		}
		$return ['imgSrc'] = $this->cdnUrl . $key;
		$return ['imgKey'] = $key;
		$return ['imgName'] = $info ['savename'];
		$return ['name'] = $info ['name'];
		Util::ajax ( '100000', 'ok', $return );
	}

	/**
	 * 图片裁剪
	 */
	public function actionCrop() {
		$x = ( int ) $_POST ['x'];
		$y = ( int ) $_POST ['y'];
		$w = ( int ) $_POST ['w'];
		$h = ( int ) $_POST ['h'];
		$pic = $_POST ['src'];
		$path = $this->basePath . '/web/assets/news';
		// 剪切后小图片的名字
		$str = explode ( ".", $pic );
		$type = $str [1];
		$name = uniqid () . time () . '.' . $type; // 重新生成图片的名字
		$uploadBanner = $pic;
		
		// 创建图片
		$src_pic = $this->getImageHander ( $path . '/' . $uploadBanner );
		$dst_pic = imagecreatetruecolor ( $w, $h );
		imagecopyresampled ( $dst_pic, $src_pic, 0, 0, $x, $y, $w, $h, $w, $h );
		imagejpeg ( $dst_pic, $path . '/' . $name );
		imagedestroy ( $src_pic );
		imagedestroy ( $dst_pic );
		// 返回新图片的位置
		Util::ajax ( '100000', '已保存', $name );
	}

	private function crop($src_name, $x, $y, $w, $h) {
		$path = $this->basePath . '/web/assets/news';
		// 剪切后小图片的名字
		$str = explode ( ".", $src_name );
		$type = $str [1];
		$name = uniqid () . time () . '.' . $type; // 重新生成图片的名字
		                                           
		// 创建图片
		$src_pic = $this->getImageHander ( $path . '/' . $src_name );
		$dst_pic = imagecreatetruecolor ( $w, $h );
		imagecopyresampled ( $dst_pic, $src_pic, 0, 0, $x, $y, $w, $h, $w, $h );
		imagejpeg ( $dst_pic, $path . '/' . $name );
		imagedestroy ( $src_pic );
		imagedestroy ( $dst_pic );
		// 返回新图片的位置
		return $name;
	}

	private function getImageHander($url) {
		$size = @getimagesize ( $url );
		switch ($size ['mime']) {
			case 'image/jpeg' :
				$im = imagecreatefromjpeg ( $url );
				break;
			case 'image/gif' :
				$im = imagecreatefromgif ( $url );
				break;
			case 'image/png' :
				$im = imagecreatefrompng ( $url );
				break;
			default :
				$im = false;
				break;
		}
		return $im;
	}
}

