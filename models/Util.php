<?php

namespace app\models;

use Yii;

class Util {

	public static function isMobile($mbile) {
		return preg_match ( '/^1\d{10}$/', $mbile ) ? true : false;
	}

	public static function isUrl($url) {
		return preg_match ( '/^((https?|ftp|news):\/\/)[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $url ) ? true : false;
	}

	public static function isEmail($email) {
		// return preg_match ( '/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $email ) ? true : false;
		return preg_match ( '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/', $email ) ? true : false;
	}

	public static function ajax($status = '100000', $info = 'ok', $data = '') {
		$json ['status'] = $status;
		$json ['info'] = $info;
		$json ['data'] = $data;
		header ( 'Content-Type:text/html; charset=utf-8' );
		echo json_encode ( $json );
		exit ();
	}

	public static function checkEmpty($str, $info, $status = 100001, $data = '') {
		return empty ( $str ) ? self::ajax ( $status, $info, $data ) : $str;
	}

	public static function checkMaxLen($str, $max, $info, $status = 100001, $data = '') {
		return (mb_strlen ( $str, 'utf-8' ) > $max) ? self::ajax ( $status, $info, $data ) : $str;
	}

	public static function checkMinLen($str, $min, $info, $status = 100001, $data = '') {
		return (mb_strlen ( $str, 'utf-8' ) < $min) ? self::ajax ( $status, $info, $data ) : $str;
	}

	public static function checkMinMaxLen($str, $min, $max, $info, $status = 100001, $data = '') {
		$len = mb_strlen ( $str, 'utf-8' );
		if ($len < $min || $len > $max) {
			self::ajax ( $status, $info, $data );
		}
		return $str;
	}

	public static function checkEqual($param1, $param2, $info, $status = 100001, $data = '') {
		return $param1 == $param2 ? true : self::ajax ( $status, $info, $data );
	}

	public static function mkDir($dir, $mode = 0777) {
		if (is_dir ( $dir ) || @mkdir ( $dir, $mode ))
			return true;
		if (! self::mkDir ( dirname ( $dir ), $mode ))
			return false;
		return @mkdir ( $dir, $mode );
	}

	static function writeLog($message, $delemeter = '|', $logRoot = '') {
		$now = date ( "Y-m-d H:i:s" );
		if (! $logRoot) {
			$logRoot = \Yii::$app->params ['applog'];
		}
		$logFile = $logRoot . '/' . date ( 'Y_m_d' ) . '.log';
		$dir = dirname ( $logFile );
		if (! is_dir ( $dir )) {
			self::mkDir ( $dir );
		}
		$log = "[{$now}]{$delemeter}" . "{$message}\n";
		error_log ( $log, 3, $logFile );
	}

	static function log($logArr = [], $delemeter = '|', $logRoot = '') {
		$message = "";
		foreach ( $logArr as $k => $v ) {
			is_numeric ( $k ) ? ($message .= "{$v}{$delemeter}") : ($message .= "{$k}:{$v}{$delemeter}");
		}
		self::writeLog ( trim ( $message ), $delemeter, $logRoot );
	}

	/**
	 *
	 * @param unknown_type $curPage
	 *        	当前页
	 * @param unknown_type $pageSize
	 *        	每页数量
	 * @param unknown_type $totalItems
	 *        	总记录数
	 * @param unknown_type $url
	 *        	链接
	 * @return string
	 */
	static function pageHtml($curPage, $pageSize, $totalItems, $url) {
		$pageTool = new Pager ( $curPage, $pageSize, $totalItems, $url );
		$pageTool->config ( 'prevNums', '3' );
		$pageTool->config ( 'nextNums', '3' );
		$pageTool->config ( 'prefix', '<ul class="am-pagination am-pagination-centered">' );
		$pageTool->config ( 'first', '<li><a href="%link%=%page%">%page%...</a></li>' );
		$pageTool->config ( 'last', '<li><a href="%link%=%page%">...%page%</a></li>' );
		$pageTool->config ( 'prev', '<li><a href="%link%=%page%">&laquo;</a></li>' );
		$pageTool->config ( 'next', '<li><a href="%link%=%page%">&raquo;</a></li>' );
		$pageTool->config ( 'current', '<li class="am-active"><a href="javascript:void(0);"><strong>%page%</strong></a></li>' );
		$pageTool->config ( 'page', '<li><a href="%link%=%page%">%page%</a></li>' );
		$pageTool->config ( 'suffix', '</ul>' );
		$pageHtml = $pageTool->html ();
		return $pageHtml;
	}

	static function pageHtmlAj($curPage, $pageSize, $totalItems, $url) {
		$pageTool = new Pager ( $curPage, $pageSize, $totalItems, $url );
		$pageTool->config ( 'prevNums', '3' );
		$pageTool->config ( 'nextNums', '3' );
		$pageTool->config ( 'prefix', '<ul class="am-pagination am-pagination-centered">' );
		$pageTool->config ( 'first', '<li><a class="js-chg-page" href="javascript:void(0);" data-p="%page%">%page%...</a></li>' );
		$pageTool->config ( 'last', '<li><a class="js-chg-page" href="javascript:void(0);" data-p="%page%">...%page%</a></li>' );
		$pageTool->config ( 'prev', '<li><a class="js-chg-page" href="javascript:void(0);" data-p="%page%">&laquo;</a></li>' );
		$pageTool->config ( 'next', '<li><a class="js-chg-page" href="javascript:void(0);" data-p="%page%">&raquo;</a></li>' );
		$pageTool->config ( 'current', '<li class="am-active"><a href="javascript:void(0);" data-p="%page%"><strong>%page%</strong></a></li>' );
		$pageTool->config ( 'page', '<li><a class="js-chg-page" href="javascript:void(0);" data-p="%page%">%page%</a></li>' );
		$pageTool->config ( 'suffix', '</ul>' );
		$pageHtml = $pageTool->html ();
		return $pageHtml;
	}

	static function xhprofStart() {
		// start profiling
		xhprof_enable ();
	}

	static function xhprofStop($source = "xhprof_foo") {
		// stop profiler
		$xhprof_data = xhprof_disable ();
		
		// display raw xhprof data for the profiler run
		// print_r($xhprof_data);
		
		require_once (Yii::$app->basePath . '/vendor/xhprof/xhprof_lib/utils/xhprof_lib.php');
		require_once (Yii::$app->basePath . '/vendor/xhprof/xhprof_lib/utils/xhprof_runs.php');
		
		// save raw data for this profiler run using default
		// implementation of iXHProfRuns.
		$xhprof_runs = new \XHProfRuns_Default ();
		
		// save the run under a namespace "xhprof_foo"
		$run_id = $xhprof_runs->save_run ( $xhprof_data, $source );
		if (defined ( 'ENV_PRO' )) {
			$curlurl = "http://pro.xhprof.shshapp.com/xhprof_html/";
		} else {
			$curlurl = "http://dev.xhprof.shshapp.com/xhprof_html/";
		}
		echo '<div style="display:none;">' . "{$curlurl}index.php?run=$run_id&source={$source}" . '</div>';
	}
}