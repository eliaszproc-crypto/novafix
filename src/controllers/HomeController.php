<?php
class HomeController {

    private function render(string $view, string $title): void {
        $pageTitle = $title;
        ob_start();
        include VIEW_PATH . '/home/' . $view . '.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }


    public function setLang(string $code): void {
        if (in_array($code, ['pl', 'en'])) {
            $_SESSION['lang'] = $code;
        }
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($ref);
    }

    public function index(): void {
        $this->render('index', 'Strona główna');
    }

    public function services(): void {
        $this->render('services', 'Usługi');
    }

    public function pricing(): void {
        $this->render('pricing', 'Cennik');
    }

    public function contact(): void {
        $this->render('contact', 'Kontakt');
    }

    public function statusPage(): void {
        $rma    = trim($_GET['rma'] ?? '');
        $repair = null;
        $error  = '';

        if ($rma) {
            global $pdo;
            $stmt = $pdo->prepare('
                SELECT r.*, rs.label as status_label, rs.color as status_color,
                       rs.sort_order, dt.name as device_type
                FROM repairs r
                JOIN repair_statuses rs ON r.status_id = rs.id
                JOIN device_types dt ON r.device_type_id = dt.id
                WHERE r.rma_number = ?
            ');
            $stmt->execute([$rma]);
            $repair = $stmt->fetch();
            if (!$repair) $error = 'Nie znaleziono zgłoszenia o numerze ' . htmlspecialchars($rma);
        }

        $pageTitle = 'Status naprawy';
        ob_start();
        include VIEW_PATH . '/home/status.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }

    public function checkStatus(string $rma): void {
        header('Location: /status?rma=' . urlencode($rma));
        exit;
    }

    public function setLanguage(string $code): void {
        setLang($code);
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($ref);
    }
}
