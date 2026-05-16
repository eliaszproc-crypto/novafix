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

function sendEmailNotification(string $to, string $subject, string $body): bool {
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: NovaFix <noreply@novafix.pl>',
        'X-Mailer: PHP/' . PHP_VERSION,
    ];
    return @mail($to, $subject, $body, implode("\r\n", $headers));
}

function notifyNewRepair(array $repair, string $rma, string $device, string $problem): void {
    $config = require ROOT_PATH . '/config/config.php';
    $to     = $config['notify_email'] ?? 'eliasz.proc@gmail.com';
    $url    = rtrim($config['app']['url'] ?? 'http://novafix.local', '/');

    $subject = "NovaFix — Nowe zgłoszenie $rma";
    $body = "
    <html><body style='font-family:Arial,sans-serif;background:#070d1a;color:#e2e8f4;padding:24px'>
    <div style='max-width:560px;margin:0 auto;background:#0f1929;border-radius:12px;overflow:hidden'>
        <div style='background:linear-gradient(135deg,#003ca0,#001450);padding:24px 28px'>
            <h1 style='margin:0;font-size:20px;color:#fff'>🔔 Nowe zgłoszenie</h1>
            <p style='margin:6px 0 0;color:rgba(255,255,255,0.7);font-size:14px'>$rma</p>
        </div>
        <div style='padding:24px 28px'>
            <table style='width:100%;border-collapse:collapse'>
                <tr><td style='padding:8px 0;color:#7a8aaa;font-size:13px;width:120px'>Urządzenie:</td><td style='padding:8px 0;color:#fff;font-size:13px'>" . htmlspecialchars($device) . "</td></tr>
                <tr><td style='padding:8px 0;color:#7a8aaa;font-size:13px'>Opis problemu:</td><td style='padding:8px 0;color:#fff;font-size:13px'>" . nl2br(htmlspecialchars(mb_substr($problem, 0, 300))) . "</td></tr>
            </table>
            <div style='margin-top:20px;text-align:center'>
                <a href='$url/admin/zgloszenia' style='display:inline-block;background:linear-gradient(135deg,#0050d0,#00e5ff20);color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;border:1px solid rgba(0,229,255,0.3)'>Otwórz panel admina →</a>
            </div>
        </div>
    </div>
    </body></html>";

    sendEmailNotification($to, $subject, $body);
}
