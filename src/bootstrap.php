<?php
session_start();
define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH',  ROOT_PATH . '/src');
define('VIEW_PATH', ROOT_PATH . '/views');

require_once SRC_PATH . '/helpers/functions.php';
require_once SRC_PATH . '/helpers/ImageHelper.php';
require_once SRC_PATH . '/config/database.php';
require_once SRC_PATH . '/Router.php';

$router = new Router();
require_once ROOT_PATH . '/routes.php';

// Zliczaj odwiedziny publicznych stron
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$skip = ['/admin', '/panel', '/api', '/logout', '/login', '/rejestracja'];
$track = true;
foreach ($skip as $s) {
    if (str_starts_with($uri, $s)) { $track = false; break; }
}
if ($track && isset($pdo)) {
    try {
        $ip_hash = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '');
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
        $pdo->prepare('INSERT INTO page_visits (path, ip_hash, user_agent) VALUES (?,?,?)')
            ->execute([$uri, $ip_hash, $ua]);
    } catch (Exception $e) {}
}

$router->dispatch();
