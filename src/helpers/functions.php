<?php
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) redirect('/login');
}

function requireAdmin(): void {
    if (!isAdmin()) redirect('/');
}

function generateRMA(): string {
    return 'NF-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
}

function sanitize(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function formatMoney(float $amount): string {
    return number_format($amount, 2, ',', ' ') . ' zł';
}

function timeAgo(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)    return 'przed chwilą';
    if ($diff < 3600)  return floor($diff / 60) . ' min temu';
    if ($diff < 86400) return floor($diff / 3600) . ' godz. temu';
    return date('d.m.Y', strtotime($datetime));
}

function formatDate(string $datetime): string {
    $ts   = strtotime($datetime);
    $now  = time();
    $diff = $now - $ts;

    if ($diff < 60)     return 'przed chwilą';
    if ($diff < 3600)   return floor($diff/60).' min temu';
    if ($diff < 86400)  return floor($diff/3600).' godz. temu';
    if ($diff < 172800) return 'wczoraj, '.date('H:i', $ts);

    $d = (int)date('d', $ts);
    $m = ['','sty','lut','mar','kwi','maj','cze','lip','sie','wrz','paź','lis','gru'][(int)date('m', $ts)];
    $y = date('Y', $ts);

    if ($y == date('Y')) return $d.' '.$m.', '.date('H:i', $ts);
    return $d.' '.$m.' '.$y;
}

function formatDateFull(string $datetime): string {
    $ts = strtotime($datetime);
    $d  = (int)date('d', $ts);
    $months = ['','stycznia','lutego','marca','kwietnia','maja','czerwca',
               'lipca','sierpnia','września','października','listopada','grudnia'];
    $m = $months[(int)date('m', $ts)];
    $y = date('Y', $ts);
    $h = date('H:i', $ts);
    return "$d $m $y, $h";
}
