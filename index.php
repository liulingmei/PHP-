<?php
require_once("./common/conf.php");
require_once("DB.class.php");
$db = Db::getInstance("Db_USER_R");
$arr = array('username'=>'zhaobin','password'=>'12345','typename'=>'carrera','newage'=>24,'ip'=>'127.0.0.1');
$db->insert($arr,'test');