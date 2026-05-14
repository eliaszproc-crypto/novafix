<?php
session_start();
define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH',  ROOT_PATH . '/src');
define('VIEW_PATH', ROOT_PATH . '/views');

require_once SRC_PATH . '/helpers/functions.php';
require_once SRC_PATH . '/config/database.php';
require_once SRC_PATH . '/Router.php';

$router = new Router();
require_once ROOT_PATH . '/routes.php';
$router->dispatch();
