<?php

namespace app\models;

use Yii;

class Redis {

	public static $redis;

	public static function initialize() {
		$cache = Yii::$app->cache;
		return $cache->redis;
	}

	public static function set($key, $value) {
		return self::initialize ()->set ( $key, $value );
	}

	public static function setex($key, $ttl, $value) {
		return self::initialize ()->setex ( $key, $ttl, $value );
	}

	public static function get($key) {
		return self::initialize ()->get ( $key );
	}

	public static function del($key) {
		return self::initialize ()->del ( $key );
	}

	public static function hset($key, $field, $value) {
		return self::initialize ()->hset ( $key, $field, $value );
	}

	public static function hget($key, $field) {
		return self::initialize ()->hget ( $key, $field );
	}

	public static function hgetall($key) {
		$fields = $values = [ ];
		$fields = self::initialize ()->hkeys ( $key );
		if (! $fields) {
			return [ ];
		}
		$values = self::initialize ()->hvals ( $key );
		return array_combine ( $fields, $values );
	}

	public static function hmset($key, array $data) {
		if (empty ( $data )) {
			return false;
		}
		$kv [] = $key;
		foreach ( $data as $field => $value ) {
			$kv [] = $field;
			$kv [] = $value;
		}
		return self::initialize ()->executeCommand ( 'HMSET', $kv );
	}

	public static function hmget() {
	}

	public static function expire($key, $ttl = 0) {
		return self::initialize ()->expire ( $key, $ttl );
	}

	public static function expireat($key, $timestamp = 0) {
		return self::initialize ()->expireat ( $key, $timestamp );
	}

	public static function sadd($key, $value) {
		return self::initialize ()->sadd ( $key, $value );
	}

	public static function smembers($key) {
		return self::initialize ()->smembers ( $key );
	}

	public static function lpush($key, $value) {
		return self::initialize ()->lpush ( $key, $value );
	}

	public static function rpush($key, $value) {
		return self::initialize ()->rpush ( $key, $value );
	}

	public static function lrange($key, $start, $stop) {
		return self::initialize ()->lrange ( $key, $start, $stop );
	}

	public static function llen($key) {
		return self::initialize ()->llen ( $key );
	}
}