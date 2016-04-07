<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Getui extends Model {

	public static $_getuiDir = '';

	public static $_config = [ ];

	public static function initQn() {
		header ( "Content-Type: text/html; charset=utf-8" );
		self::$_getuiDir = Yii::$app->basePath . '/vendor/getui/';
		require_once (self::$_getuiDir . 'IGt.Push.php');
		// require_once (self::$_getuiDir . 'demo.php');
		self::$_config = Yii::$app->params ['getui'];
		return self::$_config;
	}

	public static function pushMessageToSingle($clientID,$content,$title,$text,$logo) {
		self::initQn ();
		$igt = new \IGeTui ( self::$_config ['HOST'], self::$_config ['APPKEY'], self::$_config ['MASTERSECRET'] );
		$igt->debug = false;
		// 消息模版：
		// 1.TransmissionTemplate:透传功能模板
		// 2.LinkTemplate:通知打开链接功能模板
		// 3.NotificationTemplate：通知透传功能模板
		// 4.NotyPopLoadTemplate：通知弹框下载功能模板
		
		// $template = self::IGtTransmissionTemplateDemo();
		// $template = self::IGtLinkTemplateDemo();
		$template = self::IGtNotificationTemplateDemo($content,$title,$text,$logo);
		// $template = self::IGtNotyPopLoadTemplateDemo ();
		
		// 个推信息体
		$message = new \IGtSingleMessage ();
		
		$message->set_isOffline ( true ); // 是否离线
		$message->set_offlineExpireTime ( 3600 * 12 * 1000 ); // 离线时间
		$message->set_data ( $template ); // 设置推送消息类型
		$message->set_PushNetWorkType ( 0 ); // 设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
		                                     // 接收方
		$target = new \IGtTarget ();
		$target->set_appId ( self::$_config ['APPID'] );
		$target->set_clientId ( $clientID );
		
		$rep = $igt->pushMessageToSingle ( $message, $target );
		Util::log ( $rep, '|', '/data/wwwlogs/getui' );
		//var_dump ( $rep );
		//echo ("<br><br>");
	}

	public static function IGtNotyPopLoadTemplateDemo() {
		$template = new \IGtNotyPopLoadTemplate ();
		
		$template->set_appId ( self::$_config ['APPID'] ); // 应用appid
		$template->set_appkey ( self::$_config ['APPKEY'] ); // 应用appkey
		                                  // 通知栏
		$template->set_notyTitle ( "个推" ); // 通知栏标题
		$template->set_notyContent ( "个推最新版点击下载" ); // 通知栏内容
		$template->set_notyIcon ( "" ); // 通知栏logo
		$template->set_isBelled ( true ); // 是否响铃
		$template->set_isVibrationed ( true ); // 是否震动
		$template->set_isCleared ( true ); // 通知栏是否可清除
		                                   // 弹框
		$template->set_popTitle ( "弹框标题" ); // 弹框标题
		$template->set_popContent ( "弹框内容" ); // 弹框内容
		$template->set_popImage ( "" ); // 弹框图片
		$template->set_popButton1 ( "下载" ); // 左键
		$template->set_popButton2 ( "取消" ); // 右键
		                                    // 下载
		$template->set_loadIcon ( "" ); // 弹框图片
		$template->set_loadTitle ( "地震速报下载" );
		$template->set_loadUrl ( "http://dizhensubao.igexin.com/dl/com.ceic.apk" );
		$template->set_isAutoInstall ( false );
		$template->set_isActived ( true );
		
		return $template;
	}
	
	public static function IGtLinkTemplateDemo($title,$text,$logo,$url){
		$template =  new \IGtLinkTemplate();
		$template ->set_appId(self::$_config ['APPID']);//应用appid
		$template ->set_appkey(self::$_config ['APPKEY']);//应用appkey
		$template ->set_title($title);//通知栏标题
		$template ->set_text($text);//通知栏内容
		$template ->set_logo($logo);//通知栏logo
		$template ->set_isRing(true);//是否响铃
		$template ->set_isVibrate(true);//是否震动
		$template ->set_isClearable(true);//通知栏是否可清除
		$template ->set_url($url);//打开连接地址
		// iOS推送需要设置的pushInfo字段
		//$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
		//$template ->set_pushInfo("",2,"","","","","","");
		return $template;
	}

	public static function IGtNotificationTemplateDemo($content,$title,$text,$logo){
		$template =  new \IGtNotificationTemplate();
		$template->set_appId(self::$_config ['APPID']);//应用appid
		$template->set_appkey(self::$_config ['APPKEY']);//应用appkey
		$template->set_transmissionType(1);//透传消息类型
		$template->set_transmissionContent($content);//透传内容
		$template->set_title($title);//通知栏标题
		$template->set_text($text);//通知栏内容
		$template->set_logo($logo);//通知栏logo
		$template->set_isRing(true);//是否响铃
		$template->set_isVibrate(true);//是否震动
		$template->set_isClearable(true);//通知栏是否可清除
		// iOS推送需要设置的pushInfo字段
		//$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
		//$template ->set_pushInfo("test",1,"message","","","","","");
		return $template;
	}
	
	public static function IGtTransmissionTemplateDemo($content){
		$template =  new \IGtTransmissionTemplate();
		$template->set_appId(self::$_config ['APPID']);//应用appid
		$template->set_appkey(self::$_config ['APPKEY']);//应用appkey
		$template->set_transmissionType(1);//透传消息类型
		$template->set_transmissionContent($content);//透传内容
		//iOS推送需要设置的pushInfo字段
		//$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
		//$template ->set_pushInfo("", 0, "", "", "", "", "", "");
		return $template;
	}
}