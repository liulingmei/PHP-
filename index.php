<?php
require_once("./common/conf.php");
$UserService = UserService::getInstance();
$condition = ['id'=>1];
$res = $UserService->selectUser($condition,1,1);
var_dump($res);