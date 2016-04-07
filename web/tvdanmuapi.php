<?php

$dbconn = mysql_connect('127.0.0.1', 'root', '12345');
mysql_select_db('tv_danmu', $dbconn);
mysql_query("set names 'UTF8'");

$sql = "SELECT `title` FROM `setting` WHERE `id` = '1' LIMIT 1";
$rs = mysql_query($sql);
$row = mysql_fetch_row($rs);
$title = $row[0];

$lastId = $_GET['lastid'] + 0;
$sql = "SELECT * FROM `danmu` WHERE `id` > '$lastId' ORDER BY `id` DESC LIMIT 40";
$rs = mysql_query($sql);
$danmu = array();

while($row = mysql_fetch_assoc($rs)){
	if(count($danmu) == 0)
		$lastId =  $row['id'];
	$danmu[] = $row['words'];
}
$return['lastid'] = $lastId;
$return['title'] = $title;
$return['danmu'] = $danmu;

header("Content-type: text/html; charset=utf-8");
echo json_encode($return);