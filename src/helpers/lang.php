<?php

function lang(): string {
    if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], ['pl','en'])) {
        return $_SESSION['lang'];
    }
    return 'pl';
}

function t(string $key, array $replace = []): string {
    static $translations = null;
    if ($translations === null) {
        $translations = require ROOT_PATH . '/lang/' . lang() . '.php';
    }
    $text = $translations[$key] ?? $key;
    foreach ($replace as $k => $v) {
        $text = str_replace(':' . $k, $v, $text);
    }
    return $text;
}
