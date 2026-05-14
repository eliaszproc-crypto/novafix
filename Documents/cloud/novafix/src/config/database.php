<?php
$config = require ROOT_PATH . '/config/config.php';
$db_cfg = $config['db'];

try {
    $pdo = new PDO(
        "mysql:host={$db_cfg['host']};dbname={$db_cfg['name']};charset={$db_cfg['charset']}",
        $db_cfg['user'],
        $db_cfg['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Błąd połączenia z bazą danych.');
}
