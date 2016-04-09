<?php
require_once("./common/conf.php");
require_once('UserDao.class.php');
require_once('UserService.class.php');
require_once('DB.class.php');
require_once("DbWrapper.class.php");
$db = DbWrapper::getInstance("Db_USER_R");
$arr = array('username'=>'zhaobin','password'=>md5(12345),'sex'=>'carrera','age'=>24,);
$res = $db->queryUpdateAll(array('username'=>'carrera'), 'user',array('id'=>array('>=',2)));
var_dump($res);
