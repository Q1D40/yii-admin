<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Qiniu extends Model {

	public static $_qiniuDir = '';

	public static $_config = [ ];

	public static function initQn($fieldName = "qiniu") {
		self::$_qiniuDir = Yii::$app->basePath . '/vendor/qiniu/';
		// require_once (Yii::$app->basePath . '/vendor/qiniu/rs.php');
		require_once (self::$_qiniuDir . 'rs.php');
		self::$_config = Yii::$app->params [$fieldName] [Base::env ()];
		return self::$_config;
	}

	/**
	 * 生成上传凭证
	 *
	 * @return boolean string
	 */
	public static function upToken($fieldName = "qiniu") {
		self::initQn ( $fieldName );
		try {
			Qiniu_SetKeys ( self::$_config ['accessKey'], self::$_config ['secretKey'] );
			$putPolicy = new \Qiniu_RS_PutPolicy ( self::$_config ['bucket'] );
			$upToken = $putPolicy->Token ( null );
		} catch ( \Exception $e ) {
			return false;
		}
		return $upToken;
	}

	/**
	 * 查看文件信息
	 *
	 * @param unknown $key        	
	 */
	public static function stat($key) {
		self::initQn ();
		Qiniu_SetKeys ( self::$_config ['accessKey'], self::$_config ['secretKey'] );
		$client = new \Qiniu_MacHttpClient ( null );
		list ( $ret, $err ) = Qiniu_RS_Stat ( $client, self::$_config ['bucket'], $key );
		if ($err !== null) {
			var_dump ( $err );
		} else {
			var_dump ( $ret );
		}
	}

	/**
	 * 删除文件
	 *
	 * @param unknown $key        	
	 */
	public static function delete($key) {
		self::initQn ();
		// $key = "file_name1";
		Qiniu_SetKeys ( self::$_config ['accessKey'], self::$_config ['secretKey'] );
		$client = new \Qiniu_MacHttpClient ( null );
		
		$err = Qiniu_RS_Delete ( $client, self::$_config ['bucket'], $key );
		if ($err !== null) {
			var_dump ( $err );
		} else {
			return "Success!";
		}
	}

	/**
	 * 上传文件
	 *
	 * @param unknown $key        	
	 * @param unknown $localFile        	
	 */
	public static function putFile($key, $localFile, $fieldName = "qiniu") {
		$upToken = self::upToken ( $fieldName );
		require_once (self::$_qiniuDir . 'io.php');
		// $key = "file_name1";
		$putExtra = new \Qiniu_PutExtra ();
		$putExtra->Crc32 = 1;
		list ( $ret, $err ) = Qiniu_PutFile ( $upToken, $key, $localFile, $putExtra );
		// echo "====> Qiniu_PutFile result: \n";
		if ($err !== null) {
			$err = ArrayHelper::toArray ( $err );
			return $err;
		} else {
			return $ret;
		}
	}

	public static function copy($srcKey, $dstKey) {
		self::initQn ();
		// $srcKey = "pic.jpg";
		// $dstKey = "file_name1";
		
		Qiniu_SetKeys ( self::$_config ['accessKey'], self::$_config ['secretKey'] );
		$client = new \Qiniu_MacHttpClient ( null );
		
		$err = Qiniu_RS_Copy ( $client, $client, self::$_config ['bucket'], $srcKey, $client, self::$_config ['bucket'], $dstKey );
		echo "====> Qiniu_RS_Copy result: \n";
		if ($err !== null) {
			var_dump ( $err );
		} else {
			echo "Success!";
		}
	}

	public static function move($srcKey, $dstKey) {
		self::initQn ();
		// $srcKey = "pic.jpg";
		// $dstKey = "file_name1";
		Qiniu_SetKeys ( self::$_config ['accessKey'], self::$_config ['secretKey'] );
		$client = new \Qiniu_MacHttpClient ( null );
		
		$err = Qiniu_RS_Move ( $client, self::$_config ['bucket'], $srcKey, self::$_config ['bucket'], $dstKey );
		echo "====> Qiniu_RS_Move result: \n";
		if ($err !== null) {
			var_dump ( $err );
		} else {
			echo "Success!";
		}
	}

	public static function fetch($fetch, $key) {
		self::initQn ();
		$fetch = Qiniu_Encode ( $fetch );
		$to = Qiniu_Encode ( self::$_config ['bucket'] . ":" . $key );
		$url = 'http://iovip.qbox.me/fetch/' . $fetch . '/to/' . $to;
		$access_token = self::generate_access_token ( $url );
		$header [] = 'Host: iovip.qbox.me';
		$header [] = 'Content-Type: application/x-www-form-urlencoded';
		$header [] = 'Authorization: QBox ' . $access_token;
		
		$con = self::send ( 'iovip.qbox.me/fetch/' . $fetch . '/to/' . $to, $header );
		return $con;
	}

	public static function generate_access_token($url, $params = '') {
		$parsed_url = parse_url ( $url );
		$path = $parsed_url ['path'];
		$access = $path;
		if (isset ( $parsed_url ['query'] )) {
			$access .= "?" . $parsed_url ['query'];
		}
		$access .= "\n";
		if ($params) {
			if (is_array ( $params )) {
				$params = http_build_query ( $params );
			}
			$access .= $params;
		}
		$digest = hash_hmac ( 'sha1', $access, self::$_config ['secretKey'], true );
		return self::$_config ['accessKey'] . ':' . Qiniu_Encode ( $digest );
	}

	public static function send($url, $header = '') {
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		// curl_setopt ( $curl, CURLOPT_HEADER, 1 );//是否返回响应头
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, [ ] );
		
		$con = curl_exec ( $curl );
		$info = curl_getinfo ( $curl );
		if ($con === false) {
			echo 'CURL ERROR: ' . curl_error ( $curl );
		} else {
			return $con;
		}
	}
}