<?php
require_once("./common/conf.php");
require_once('DB.class.php');
require_once("DbWrapper.class.php");
require_once("TableService.class.php");
require_once('UserDao.class.php');
require_once('UserService.class.php');
$UserService = UserService::getInstance();
$condition = ['id'=>1];
$res = $UserService->selectUser($condition,1,1);
var_dump($res);