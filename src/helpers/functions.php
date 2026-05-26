<?php

function uploadPath(): string {
    // Działa zarówno na XAMPP (public/) jak i na hostingu (public_html/)
    $paths = [
        ROOT_PATH . '/public/uploads/',
        ROOT_PATH . '/public_html/uploads/',
        $_SERVER['DOCUMENT_ROOT'] . '/uploads/',
    ];
    foreach ($paths as $p) {
        if (is_dir($p) && is_writable($p)) return $p;
    }
    // Fallback - zwróć pierwszą istniejącą
    foreach ($paths as $p) {
        if (is_dir($p)) return $p;
    }
    return ROOT_PATH . '/public/uploads/';
}

function uploadUrl(string $filename): string {
    return '/uploads/' . $filename;
}

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
    $config = require ROOT_PATH . '/config/config.php';
    $mc = $config['mail'];

    $mailerPath = '/home/host201211/domains/host201211.xce.pl/vendor/phpmailer/src';
    if (!file_exists($mailerPath . '/PHPMailer.php')) {
        // Fallback do mail()
        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: NovaFix <' . $mc['from'] . '>',
        ]);
        return @mail($to, $subject, $body, $headers, '-f' . $mc['from']);
    }

    require_once $mailerPath . '/Exception.php';
    require_once $mailerPath . '/PHPMailer.php';
    require_once $mailerPath . '/SMTP.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $mc['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $mc['user'];
        $mail->Password   = $mc['password'];
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)$mc['port'];
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom($mc['from'], $mc['from_name'] ?? 'NovaFix');
        $mail->addAddress($to);
        $mail->Subject    = $subject;
        $mail->isHTML(true);
        $mail->Body       = $body;
        $mail->send();
        file_put_contents(ROOT_PATH.'/email_debug.log', date('Y-m-d H:i:s')." SMTP OK to=$to\n", FILE_APPEND);
        return true;
    } catch (\Exception $e) {
        file_put_contents(ROOT_PATH.'/email_debug.log', date('Y-m-d H:i:s')." SMTP FAIL: ".$mail->ErrorInfo."\n", FILE_APPEND);
        return false;
    }
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

function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfVerify(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Nieprawidłowy token CSRF. Odśwież stronę i spróbuj ponownie.');
    }
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

function notifyClientStatusChange(int $repair_id, string $status_code, string $status_label, array $config): void {
    global $pdo;

    // Pobierz dane zlecenia i klienta
    $stmt = $pdo->prepare("
        SELECT r.rma_number, r.initial_quote_amount, r.final_quote_amount, r.shipping_cost,
               r.payment_method, u.email, u.first_name
        FROM repairs r JOIN users u ON r.user_id=u.id
        WHERE r.id=?
    ");
    $stmt->execute([$repair_id]);
    $repair = $stmt->fetch();
    if (!$repair) return;

    $to        = $repair['email'];
    $name      = $repair['first_name'];
    $rma       = $repair['rma_number'];
    $url       = rtrim($config['app']['url'] ?? 'https://novafix.pl', '/');
    $from      = $config['mail']['from'] ?? 'service@host201211.xce.pl';
    $from_name = 'NovaFix';

    // Treść wiadomości zależna od statusu
    $messages = [
        'initial_quote_sent'     => ['Wstępna wycena do akceptacji', "Przygotowałem dla Ciebie wstępną wycenę naprawy. Zaloguj się do panelu i zaakceptuj lub odrzuć wycenę."],
        'initial_quote_accepted' => ['Wycena zaakceptowana — wyślij sprzęt', "Świetnie! Zaakceptowałeś wstępną wycenę. Zapakuj starannie sprzęt i wyślij na paczkomat SCZ04M Szczecinek (78-400) lub kurierem na ul. Wyszyńskiego 14a/1, 78-400 Szczecinek. Pamiętaj dołączyć numer zlecenia: <strong>$rma</strong>"],
        'parcel_received'        => ['Paczka odebrana — zaczyna się diagnostyka', "Odebrałem Twoją przesyłkę. Zaczynam diagnostykę urządzenia. O wynikach poinformuję mailowo."],
        'final_quote_sent'       => ['Koszt naprawy do akceptacji', "Zakończyłem diagnostykę. Przygotowałem szczegółowy kosztorys naprawy. Zaloguj się do panelu żeby zaakceptować lub odrzucić koszt."],
        'final_quote_accepted'   => ['Koszt zaakceptowany — zaczynam naprawę', "Dziękuję za akceptację! Zaczynam naprawę Twojego urządzenia. Poinformuję Cię gdy będzie gotowe."],
        'awaiting_payment'       => ['Naprawa gotowa — oczekuję na płatność', "Naprawa zakończona pomyślnie! Zaloguj się do panelu żeby zobaczyć dane do płatności i finalizować zlecenie."],
        'paid'                   => ['Płatność otrzymana — wysyłam sprzęt', "Potwierdzam otrzymanie płatności. Pakuję Twój sprzęt i wysyłam go na wskazany adres. Dziękuję za skorzystanie z NovaFix!"],
        'shipped_to_client'      => ['Sprzęt wysłany — w drodze do Ciebie!', "Twój sprzęt został wysłany. Sprawdź numer przesyłki w panelu zlecenia."],
        'completed'              => ['Zlecenie zakończone', "Zlecenie zostało pomyślnie zakończone. Jeśli jesteś zadowolony — będę wdzięczny za opinię w panelu. Dziękuję!"],
        'return_in_progress'     => ['Zwrot sprzętu w toku', "Twój sprzęt jest pakowany do odesłania na wskazany adres zwrotny."],
    ];

    // Debug log
    file_put_contents(ROOT_PATH.'/email_debug.log',
        date('Y-m-d H:i:s').' status='.$status_code.' to='.$to."\n",
        FILE_APPEND);

    if (!isset($messages[$status_code])) {
        file_put_contents(ROOT_PATH.'/email_debug.log',
            date('Y-m-d H:i:s').' SKIP - status not in messages list'."\n",
            FILE_APPEND);
        return;
    }

    [$subject_part, $body_msg] = $messages[$status_code];
    $subject = "NovaFix — $rma — $subject_part";

    $body = "
    <html><body style='font-family:Arial,sans-serif;background:#070d1a;color:#e2e8f4;padding:20px;margin:0'>
    <div style='max-width:560px;margin:0 auto;background:#0f1929;border-radius:14px;overflow:hidden'>
        <div style='background:linear-gradient(135deg,#003ca0,#001450);padding:24px 28px'>
            <h1 style='margin:0;font-size:20px;color:#fff'>NovaFix</h1>
            <p style='margin:6px 0 0;color:rgba(255,255,255,0.6);font-size:14px'>Serwis elektroniki akwarystycznej</p>
        </div>
        <div style='padding:28px'>
            <p style='color:#e2e8f4;font-size:15px;margin-bottom:6px'>Cześć <strong style=\"color:#fff\">$name</strong>,</p>
            <div style='background:rgba(0,229,255,0.06);border:1px solid rgba(0,229,255,0.15);border-radius:10px;padding:16px 20px;margin:20px 0'>
                <p style='margin:0;font-size:13px;color:rgba(255,255,255,0.5)'>Status zlecenia <strong style=\"color:#00e5ff\">$rma</strong>:</p>
                <p style='margin:6px 0 0;font-size:18px;font-weight:700;color:#fff'>$status_label</p>
            </div>
            <p style='color:#b0bec5;font-size:14px;line-height:1.7'>$body_msg</p>
            <div style='text-align:center;margin-top:24px'>
                <a href='$url/panel' style='display:inline-block;background:linear-gradient(135deg,#0050d0,#00e5ff20);color:#fff;padding:13px 32px;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;border:1px solid rgba(0,229,255,0.3)'>Otwórz panel zlecenia →</a>
            </div>
        </div>
        <div style='padding:16px 28px;border-top:1px solid rgba(255,255,255,0.06);font-size:12px;color:rgba(255,255,255,0.3);text-align:center'>
            NovaFix Eliasz Proć · eliasz.proc@gmail.com · 691 113 754
        </div>
    </div>
    </body></html>";

    sendEmailNotification($to, $subject, $body);
}

// ---- SYSTEM TŁUMACZEŃ ----
function getLang(): string {
    return $_SESSION['lang'] ?? 'pl';
}

function t(string $key, array $vars = []): string {
    static $translations = null;
    if ($translations === null) {
        $lang = getLang();
        $file = ROOT_PATH . '/lang/' . $lang . '.php';
        $translations = file_exists($file) ? require $file : require ROOT_PATH . '/lang/pl.php';
    }
    $val = $translations[$key] ?? $key;
    foreach ($vars as $k => $v) {
        $val = str_replace('{' . $k . '}', $v, $val);
    }
    return $val;
}

function setLang(string $lang): void {
    $_SESSION['lang'] = in_array($lang, ['pl', 'en']) ? $lang : 'pl';
}
