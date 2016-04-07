<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Base;
use app\models\Curl;
use app\models\Util;

class InsController extends Controller {

	public $page_size = 20;

	public $access_token = '2030837575.c7fa050.06f6af596f344743b0c9fc8870cff539';

	public $api = 'https://api.instagram.com/v1/';

	public function actionIndex() {
		// 取最新一个资源id
		$userid = 378123881;
		$url = "{$this->api}users/{$userid}/media/recent/?access_token=2030837575.c7fa050.06f6af596f344743b0c9fc8870cff539&count=2&max_id=969972510197852245_378123881";
		$curl = new Curl ();
		$res = $curl->get ( $url );
		print_r ( json_decode ( $res, true ) );
		exit ();
		$fetch = file_get_contents ( $url );
		$content = json_decode ( $fetch, true );
		foreach ( $content ['data'] as $item ) {
			echo date ( "Y-m-d H:i:s", $item ['created_time'] ), "\n";
		}
		print_r ( $content );
	}

	public function actionJc($action = "ins/group") {
		$this->Jc ();
	}

	public function Jc($action) {
		if (! $action) {
			echo 'empty action';
			exit ();
		}
		$output = [ ];
		$return_val = [ ];
		$command = "ps aux |grep {$action}";
		$res = exec ( $command, $output, $return_val );
		$cmdstr = "{$command}===>{$return_val}|";
		if (count ( $output ) > 3) {
			$str = $output [0];
			$pid = substr ( $str, 10, 5 );
			$command = "kill -9 {$pid}";
			$res = exec ( $command, $output, $return_val );
			$cmdstr .= "{$command}===>{$return_val}";
		}
		return $cmdstr;
	}

	/**
	 * 手动执行一次
	 * 团体初始化
	 * /usr/local/php/bin/php yii ins/group
	 */
	public function actionGroup() {
		$content = file_get_contents ( 'groups.txt' );
		$list = explode ( "\n", $content );
		foreach ( $list as $item ) {
			$tmp = explode ( '===', trim ( $item ) );
			$data = [ ];
			$data ['name'] = trim ( $tmp [1] );
			$data ['order'] = $tmp [0];
			$data ['followers'] = 0; // 粉丝数
			$data ['ctime'] = time ();
			$data ['status'] = 1;
			$rows [] = $data;
		}
		$res = Base::batchAdd ( $rows, Base::$cngroups );
		var_dump ( count ( $res ) );
	}

	/**
	 * 手动执行一次
	 * 艺人入库
	 * /usr/local/php/bin/php yii ins/artist
	 */
	public function actionArtist() {
		$groups = Base::findAll ( [ ], [ ], '', 0, Base::$cngroups );
		foreach ( $groups as $group ) {
			$tmpgroups [$group ['name']] = $group;
		}
		$groups = $tmpgroups;
		$content = file_get_contents ( 'artists.txt' );
		$list = explode ( "\n", $content );
		$curl = new Curl ();
		$i = 0;
		foreach ( $list as $item ) {
			$tmp = explode ( '===', trim ( $item ) );
			$tmp [] = explode ( '/', trim ( $tmp [1], '/' ) );
			$data ['cnname'] = $tmp [0];
			$data ['ins_url'] = trim ( $tmp [1], '/' );
			$data ['group_id'] = ( string ) $groups [$tmp [2]] ['_id'];
			$data ['group_name'] = $tmp [2];
			$username = $tmp [3] [3];
			$url = "{$this->api}users/search?q={$username}&access_token={$this->access_token}&count=1";
			// $one = file_get_contents ( $url );
			$one = $curl->get ( $url );
			$one = json_decode ( $one, true );
			$userid = $one ['data'] [0] ['id'];
			$exist = Base::findOne ( [ 
				'user_id' => $userid 
			], [ ], '', 1, Base::$cnartists );
			if ($exist)
				continue;
			$url = "{$this->api}users/{$userid}?access_token={$this->access_token}";
			$detail = $curl->get ( $url );
			$detail = json_decode ( $detail, true );
			$avatar_key = '';
			if (isset ( $detail ['data'] ) && $detail ['data'] ['profile_picture']) {
				// 头像处理
				$keys = explode ( '/', $detail ['data'] ['profile_picture'] );
				$fields = [ 
					'qiniu_key' => 'avatar/' . end ( $keys ),
					'image_url' => $detail ['data'] ['profile_picture'] 
				];
				$curlRes = $this->to7niu ( $fields );
				if ($curlRes ['key']) {
					$avatar_key = $curlRes ['key'];
				}
			}
			$data ['username'] = $username;
			$data ['user_id'] = $userid;
			$data ['user_bio'] = $detail ['data'] ['bio'];
			$data ['user_website'] = $detail ['data'] ['website'];
			$data ['user_full_name'] = $detail ['data'] ['full_name'];
			$data ['instagram_detail'] = $detail ['data'];
			$data ['avatar_key'] = $avatar_key;
			$data ['followers'] = 0; // 粉丝数
			$data ['min_id'] = 0;
			$data ['min_time'] = 0;
			$data ['max_id'] = 0;
			$data ['max_time'] = 0;
			$data ['curr_id'] = 0;
			$data ['curr_time'] = 0;
			$data ['curr_max_id'] = 0;
			$data ['curr_max_time'] = 0;
			$data ['ctime'] = time ();
			$data ['status'] = 0; // 0初始化===>1旧数据已抓完
			$rows [] = $data;
			echo $i ++, "\n";
			// print_r ( $data );
		}
		$res = Base::batchAdd ( $rows, Base::$cnartists );
		var_dump ( count ( $res ) );
	}

	/**
	 * 先手动执行
	 * /usr/local/php/bin/php yii ins/initdata
	 * 新增艺人图片数据初始化
	 */
	public function actionInitdata() {
		$users = Base::findAll ( [ 
			'status' => 0 
		], [ ], 'ctime ASC', 0, Base::$cnartists );
		Util::log ( [ 
			'opt' => 'Initdata:艺人图片数据初始化',
			'data' => count ( $users ) 
		], '|', '/data/wwwlogs/cronlog' );
		if ($users) {
			$curl = new Curl ();
			foreach ( $users as $k => $user ) {
				if (! $user ['max_id']) {
					$url = "{$this->api}users/{$user['user_id']}/media/recent/?access_token={$this->access_token}&count={$this->page_size}";
				} else {
					$url = "{$this->api}users/{$user['user_id']}/media/recent/?access_token={$this->access_token}&count={$this->page_size}&max_id={$user['max_id']}";
				}
				$fetch = $curl->get ( $url );
				$content = json_decode ( $fetch, true );
				while ( $content ['data'] ) {
					foreach ( $content ['data'] as $item ) {
						echo date ( "Y-m-d H:i:s", $item ['created_time'] ), "\n";
						$curr_id = $item ['id'];
						$curr_time = date ( "Y-m-d H:i:s", $item ['created_time'] );
					}
					Base::modify ( [ 
						'user_id' => $user ['user_id'] 
					], [ 
						'min_id' => $curr_id, // 最早的数据id
						'min_time' => $curr_time,
						'max_id' => $curr_id, // 线上最新的数据id
						'max_time' => $curr_time,
						'curr_id' => $curr_id, // 当前正在抓取的id
						'curr_time' => $curr_time,
						'curr_max_id' => $curr_id, // 本地最新的数据id
						'curr_max_time' => $curr_time 
					], Base::$cnartists );
					if (isset ( $content ['pagination'] ) && empty ( $content ['pagination'] )) {
						// 初始化完成后更新状态
						Base::modify ( [ 
							'user_id' => $user ['user_id'] 
						], [ 
							'status' => 1 
						], Base::$cnartists );
						break;
					}
					// 否则继续抓取下一页
					$url = $content ['pagination'] ['next_url'];
					$fetch = $curl->get ( $url );
					$content = json_decode ( $fetch, true );
				}
				echo "++++++++++{$k}{$user['cnname']}++++++++++", "\n";
			}
		}
	}

	/**
	 * * * * * * /usr/local/php/bin/php /data/www/6666/yii ins/check_new
	 * /usr/local/php/bin/php yii ins/check_new
	 * 检测是否产生新图片数据
	 */
	public function actionCheck_new() {
		$jcres = $this->Jc ( "ins/check_new" );
		$users = Base::findAll ( [ 
			'status' => 1 
		], [ ], 'ctime ASC', 0, Base::$cnartists );
		Util::log ( [ 
			'jcres' => $jcres,
			'opt' => 'Check_new:检测是否产生新数据',
			'data' => count ( $users ) 
		], '|', '/data/wwwlogs/cronlog' );
		$havenew = [ ];
		if ($users) {
			$curl = new Curl ();
			foreach ( $users as $user ) {
				$url = "{$this->api}users/{$user['user_id']}/media/recent/?access_token={$this->access_token}&count=1";
				$fetch = $curl->get ( $url );
				$content = json_decode ( $fetch, true );
				if ($content ['data']) {
					$latestId = $content ['data'] [0] ['id'] ?  : 0;
					echo $latestId, "\n";
					if ($latestId != $user ['max_id']) {
						// 产生了新数据,更新max_id和status，准备好抓新数据状态2
						Base::modify ( [ 
							'user_id' => $user ['user_id'] 
						], [ 
							'max_id' => $latestId,
							'max_time' => date ( "Y-m-d H:i:s", $content ['data'] [0] ['created_time'] ),
							'status' => 2 
						], Base::$cnartists );
						$havenew [] = $user ['user_id'];
					}
				}
			}
		}
		Util::log ( [ 
			'opt' => ',产生新数据的人数:' . count ( $havenew ) 
		], '|', '/data/wwwlogs/cronlog' );
	}

	/**
	 * * * * * * /usr/local/php/bin/php /data/www/6666/yii ins/fetch_new
	 * /usr/local/php/bin/php yii ins/fetch_new
	 * 抓取新图片数据
	 */
	public function actionFetch_new() {
		$jcres = $this->Jc ( 'ins/fetch_new' );
		$users = Base::findAll ( [ 
			'status' => 2 
		], [ ], 'ctime ASC', 0, Base::$cnartists );
		Util::log ( [ 
			'jcres' => $jcres,
			'opt' => 'Fetch_new:抓取新数据',
			'data' => count ( $users ) 
		], '|', '/data/wwwlogs/cronlog' );
		if ($users) {
			$curl = new Curl ();
			foreach ( $users as $user ) {
				// 置成正在抓取状态3
				Base::modify ( [ 
					'user_id' => $user ['user_id'] 
				], [ 
					'status' => 3 
				], Base::$cnartists );
				// 开始抓取
				$url = "{$this->api}users/{$user['user_id']}/media/recent/?access_token={$this->access_token}&count={$this->page_size}&min_id={$user ['curr_max_id']}&max_id={$user ['max_id']}";
				$fetch = $curl->get ( $url );
				$content = json_decode ( $fetch, true );
				while ( $content ['data'] ) {
					foreach ( $content ['data'] as $item ) {
						echo date ( "Y-m-d H:i:s", $item ['created_time'] ), "\n";
						$row = [ ];
						$row ['id'] = $item ['id'];
						$row ['user_id'] = $user ['user_id'];
						$row ['type'] = $item ['type'];
						$row ['image_url'] = $item ['images'] ['standard_resolution'] ['url'];
						$row ['caption_text'] = $item ['caption'] ['text'] ?  : "";
						$row ['created_time'] = $item ['created_time'];
						$row ['link'] = $item ['link'];
						$row ['ctime'] = time ();
						$row ['status'] = 0;
						Base::save ( $row, Base::$cnresource );
					}
					// 记录当前页抓到的最后一个id
					Base::modify ( [ 
						'user_id' => $user ['user_id'] 
					], [ 
						'curr_id' => $row ['id'],
						'curr_time' => date ( "Y-m-d H:i:s", $row ['created_time'] ) 
					], Base::$cnartists );
					if (isset ( $content ['pagination'] ) && empty ( $content ['pagination'] )) {
						break;
					}
					// 否则继续抓取下一页
					$url = $content ['pagination'] ['next_url'];
					$fetch = $curl->get ( $url );
					$content = json_decode ( $fetch, true );
				}
				// 已爬到最新数据，置成等待检测新数据状态1
				Base::modify ( [ 
					'user_id' => $user ['user_id'] 
				], [ 
					'curr_max_id' => $user ['max_id'],
					'curr_max_time' => $user ['max_time'],
					'curr_id' => $user ['max_id'],
					'curr_time' => $user ['max_time'],
					'status' => 1 
				], Base::$cnartists );
			}
		}
	}

	/**
	 * * * * * * /usr/local/php/bin/php /data/www/6666/yii ins/fetch_unfinished
	 * /usr/local/php/bin/php yii ins/fetch_unfinished
	 * 中途遇到故障重新抓取未完成的图片数据
	 */
	public function actionFetch_unfinished() {
		$jcres = $this->Jc ( 'ins/fetch_unfinished' );
		$users = Base::findAll ( [ 
			'status' => 3 
		], [ ], 'ctime ASC', 0, Base::$cnartists );
		Util::log ( [ 
			'jcres' => $jcres,
			'opt' => 'Fetch_unfinished:重新抓取由于故障导致的意外中断',
			'data' => count ( $users ) 
		], '|', '/data/wwwlogs/cronlog' );
		if ($users) {
			$curl = new Curl ();
			foreach ( $users as $user ) {
				$url = "{$this->api}users/{$user['user_id']}/media/recent/?access_token={$this->access_token}&count={$this->page_size}&min_id={$user ['curr_max_id']}&max_id={$user ['curr_id']}";
				$fetch = $curl->get ( $url );
				$content = json_decode ( $fetch, true );
				while ( $content ['data'] ) {
					foreach ( $content ['data'] as $item ) {
						echo date ( "Y-m-d H:i:s", $item ['created_time'] ), "\n";
						$row = [ ];
						$row ['id'] = $item ['id'];
						$row ['user_id'] = $user ['user_id'];
						$row ['type'] = $item ['type'];
						$row ['image_url'] = $item ['images'] ['standard_resolution'] ['url'];
						$row ['caption_text'] = $item ['caption'] ['text'] ?  : "";
						$row ['created_time'] = $item ['created_time'];
						$row ['link'] = $item ['link'];
						$row ['ctime'] = time ();
						$row ['status'] = 0;
						Base::save ( $row, Base::$cnresource );
					}
					// 记录当前页抓到的最后一个id
					Base::modify ( [ 
						'user_id' => $user ['user_id'] 
					], [ 
						'curr_id' => $row ['id'],
						'curr_time' => date ( "Y-m-d H:i:s", $row ['created_time'] ) 
					], Base::$cnartists );
					if (isset ( $content ['pagination'] ) && empty ( $content ['pagination'] )) {
						break;
					}
					// 否则继续抓取下一页
					$url = $content ['pagination'] ['next_url'];
					$fetch = $curl->get ( $url );
					$content = json_decode ( $fetch, true );
				}
				// 已爬到最新数据，置成等待检测新数据状态1
				Base::modify ( [ 
					'user_id' => $user ['user_id'] 
				], [ 
					'curr_max_id' => $user ['max_id'],
					'curr_max_time' => $user ['max_time'],
					'status' => 1 
				], Base::$cnartists );
			}
		}
	}

	/**
	 * * * * * * /usr/local/php/bin/php /data/www/6666/yii ins/up2qiniu
	 * /usr/local/php/bin/php yii ins/up2qiniu
	 * 上传到七牛
	 */
	public function actionUp2qiniu() {
		$jcres = $this->Jc ( 'ins/up2qiniu' );
		$list = Base::findAll ( [ 
			'status' => 0 
		], [ ], 'created_time ASC', 0, Base::$cnresource );
		Util::log ( [ 
			'jcres' => $jcres,
			'opt' => 'Up2qiniu:图片上传七牛',
			'data' => count ( $list ) 
		], '|', '/data/wwwlogs/cronlog' );
		if ($list) {
			foreach ( $list as $item ) {
				// 上传七牛
				$fields = [ ];
				$tmp = explode ( '/', $item ['image_url'] );
				$fields = [ 
					'qiniu_key' => end ( $tmp ),
					'image_url' => $item ['image_url'] 
				];
				$curlRes = $this->to7niu ( $fields );
				var_dump ( $curlRes );
				// 更新状态
				if ((isset ( $curlRes ['key'] ) && $curlRes ['key'])) {
					// echo $curlRes ['key'], "\n";
					Base::modify ( [ 
						'_id' => ( string ) $item ['_id'] 
					], [ 
						'qiniu_key' => $curlRes ['key'],
						'status' => 1,
						'push_status' => 1 
					], Base::$cnresource );
				}
			}
		}
	}

	private function to7niu($fields) {
		$curlurl = "http://23.91.98.67/v1/spider/fetch";
		$curl = new Curl ();
		$curl->setOption ( CURLOPT_RETURNTRANSFER, 1 );
		$curl->setOption ( CURLOPT_POSTFIELDS, http_build_query ( $fields ) );
		$fetch = $curl->post ( $curlurl );
		// var_dump($curl->responseCode);
		$curlRes = json_decode ( $fetch, true );
		return $curlRes;
	}

	/**
	 * /usr/local/php/bin/php yii ins/push_notify
	 * 向粉丝推送通知
	 */
	public function actionPush_notify() {
		// $jcres = $this->Jc ( 'ins/push_notify' );
		$list = Base::findAll ( [ 
			'push_status' => 1 
		], [ ], 'created_time ASC', 0, Base::$cnresource );
		Util::log ( [ 
			'jcres' => $jcres,
			'opt' => 'push_notify:向粉丝推送通知',
			'data' => count ( $list ) 
		], '|', '/data/wwwlogs/cronlog' );
		if ($list) {
			foreach ( $list as $item ) {
				$user_id = $item ['user_id'];
				$fans = Base::findAll ( [ 
					'touid' => $user_id 
				], [ ], 'ctime asc', 0, Base::$cnrelations );
				foreach ( $fans as $fan ) {
					// 调推送通知接口
				}
			}
		}
	}

	public function actionFans() {
	}
}