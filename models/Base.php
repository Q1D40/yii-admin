<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\mongodb\Query;

class Base extends Model {

	public static $collection = null;

	public static $query = null;

	/**
	 * 用户表
	 *
	 * @var unknown
	 */
	public static $cnuser = 'user';

	/**
	 * feeds表
	 *
	 * @var unknown
	 */
	public static $cnfeeds = 'feeds';

	public static $cnresource = 'resource';

	public static $cnartists = 'artists';

	public static $cngroups = 'groups';

	public static $cnrelations = 'relations';

	public static $cntags = 'tags';

	public static $cnInsLog = 'ins_log';
	
	public static $cnSticker = 'sticker';
	
	public static $cnStickerGroup = 'sticker_group';

	public static $cnActivityFeeds = 'activity_feeds';
	
	/**
	 * 自增ID表
	 *
	 * @var unknown
	 */
	public static $cngenId = 'ids';

	public static function collectionName() {
		return '';
	}

	public static function query() {
		self::$query = new Query ();
		return self::$query;
	}

	public static function collection($collectionName = null) {
		$collectionName = $collectionName ? $collectionName : self::collectionName ();
		self::$collection = Yii::$app->mongodb->getCollection ( $collectionName );
		return self::$collection;
	}

	/**
	 * mongodb产生自增ID
	 * http://www.sharejs.com/codes/php/7145
	 *
	 * @param unknown $collectionName
	 *        	表示为哪个集合产生自增ID
	 * @return boolean
	 */
	public static function genId($collectionName) {
		$newId = false;
		$update ['$inc'] ['id'] = 1;
		$query ['name'] = $collectionName;
		$options ['new'] = true;
		$options ['upsert'] = true;
		$data = self::collection ( self::$cngenId )->findandmodify ( $query, $update, [ ], $options );
		$newId = $data ['id'];
		return $newId;
	}

	public static function env() {
		if (defined ( 'ENV_PRO' )) {
			return 'pro';
		} else {
			return 'dev';
		}
	}

	public static function findAll($condition = [], $select = [], $orderBy = [], $limit = 0, $collectionName = null) {
		$collectionName = $collectionName ? $collectionName : self::collectionName ();
		$query = self::query ()->select ( $select )->from ( $collectionName )->where ( $condition );
		if ($orderBy) {
			$query = $query->orderBy ( $orderBy );
		}
		if ($limit) {
			$query = $query->limit ( $limit );
		}
		$all = $query->all ();
		return $all;
	}

	public static function findPage($curPage, $pageSize, $condition, $select, $orderBy, $collectionName) {
		$query = self::query ()->select ( $select )->from ( $collectionName )->where ( $condition );
		$query->offset = ($curPage - 1) * $pageSize;
		$query = $query->orderBy ( $orderBy );
		$query = $query->limit ( $pageSize );
		$all = $query->all ();
		return $all;
	}

	public static function findOne($condition = [], $select = [], $orderBy = [], $limit = 0, $collectionName = null) {
		$one = self::findAll ( $condition, $select, $orderBy, $limit, $collectionName );
		if (! $one || ! is_array ( $one )) {
			return false;
		}
		return $one [0];
	}

	public static function add($data, $collectionName = null) {
		return self::collection ( $collectionName )->insert ( $data );
	}

	public static function batchAdd($rows, $collectionName = null) {
		return self::collection ( $collectionName )->batchInsert ( $rows );
	}

	public static function modify($condition = [], $params = [], $collectionName = null) {
		return self::collection ( $collectionName )->update ( $condition, $params );
	}

	public static function del($condition = [], $collectionName = null) {
		return self::collection ( $collectionName )->remove ( $condition );
	}

	public static function count($condition = [], $collectionName = null) {
		$collectionName = $collectionName ? $collectionName : self::collectionName ();
		$count = self::query ()->from ( $collectionName )->where ( $condition )->count ();
		return $count;
	}

	public static function save($data, $collectionName = null) {
		return self::collection ( $collectionName )->save ( $data );
	}
}