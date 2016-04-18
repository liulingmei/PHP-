<?php
/**
 * @author:zhaobin
 * @brife:入口
 */
header('Content-Type: text/html; charset=UTF-8');
require_once("./common/conf.php");
$app = new Application();
$rootActionConfig = array(
		'ActionController',
		ActionControllerConfig::$config
	);

$app->setRootActionConfig($rootActionConfig);
$app->execute(defined('IS_DEBUG') ? IS_DEBUG : false);
