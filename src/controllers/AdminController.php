<?php
class AdminController {

    public function dashboard(): void {
        requireAdmin();
        global $pdo;

        $stats = [
            'total'    => $pdo->query('SELECT COUNT(*) FROM repairs')->fetchColumn(),
            'new'      => $pdo->query("SELECT COUNT(*) FROM repairs r JOIN repair_statuses rs ON r.status_id = rs.id WHERE rs.code = 'new'")->fetchColumn(),
            'active'   => $pdo->query("SELECT COUNT(*) FROM repairs r JOIN repair_statuses rs ON r.status_id = rs.id WHERE rs.code NOT IN ('completed','initial_quote_rejected','final_quote_rejected')")->fetchColumn(),
            'clients'  => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'")->fetchColumn(),
        ];

        $recent = $pdo->query('
            SELECT r.*, rs.label as status_label, rs.color as status_color,
                   dt.name as device_type, u.first_name, u.last_name
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id = rs.id
            JOIN device_types dt ON r.device_type_id = dt.id
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC LIMIT 8
        ')->fetchAll();

        $pageTitle = 'Panel admina';
        ob_start();
        include VIEW_PATH . '/admin/dashboard.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/admin/layout.php';
    }

    public function repairs(): void {
        requireAdmin();
        global $pdo;

        $status_filter = $_GET['status'] ?? '';
        $search = trim($_GET['q'] ?? '');

        $where = '1=1';
        $params = [];
        if ($status_filter) {
            $where .= ' AND rs.code = ?';
            $params[] = $status_filter;
        }
        if ($search) {
            $where .= ' AND (r.rma_number LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)';
            $s = "%$search%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }

        $stmt = $pdo->prepare("
            SELECT r.*, rs.label as status_label, rs.color as status_color,
                   dt.name as device_type, db.name as device_brand,
                   u.first_name, u.last_name, u.email
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id = rs.id
            JOIN device_types dt ON r.device_type_id = dt.id
            LEFT JOIN device_brands db ON r.device_brand_id = db.id
            JOIN users u ON r.user_id = u.id
            WHERE $where
            ORDER BY r.created_at DESC
        ");
        $stmt->execute($params);
        $repairs = $stmt->fetchAll();
        $statuses = $pdo->query('SELECT * FROM repair_statuses ORDER BY sort_order')->fetchAll();

        $pageTitle = 'Zgłoszenia';
        ob_start();
        include VIEW_PATH . '/admin/repairs.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/admin/layout.php';
    }

    public function repairDetail(string $id): void {
        requireAdmin();
        global $pdo;

        $stmt = $pdo->prepare('
            SELECT r.*, rs.label as status_label, rs.color as status_color,
                   dt.name as device_type, db.name as device_brand,
                   u.first_name, u.last_name, u.email, u.phone
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id = rs.id
            JOIN device_types dt ON r.device_type_id = dt.id
            LEFT JOIN device_brands db ON r.device_brand_id = db.id
            JOIN users u ON r.user_id = u.id
            WHERE r.id = ?
        ');
        $stmt->execute([(int)$id]);
        $repair = $stmt->fetch();
        if (!$repair) redirect('/admin/zgloszenia');

        $photos = $pdo->prepare('SELECT * FROM repair_photos WHERE repair_id = ?');
        $photos->execute([(int)$id]);
        $photos = $photos->fetchAll();

        $history = $pdo->prepare('
            SELECT rsh.*, rs.label, rs.color, u.first_name, u.last_name
            FROM repair_status_history rsh
            JOIN repair_statuses rs ON rsh.status_id = rs.id
            JOIN users u ON rsh.changed_by = u.id
            WHERE rsh.repair_id = ?
            ORDER BY rsh.changed_at ASC
        ');
        $history->execute([(int)$id]);
        $history = $history->fetchAll();

        $statuses = $pdo->query('SELECT * FROM repair_statuses ORDER BY sort_order')->fetchAll();

        $pageTitle = 'Zgłoszenie ' . $repair['rma_number'];
        ob_start();
        include VIEW_PATH . '/admin/repair-detail.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/admin/layout.php';
    }

    public function updateStatus(string $id): void {
        requireAdmin();
        global $pdo;

        $status_id = (int)($_POST['status_id'] ?? 0);
        $note      = trim($_POST['note'] ?? '');

        if (!$status_id) redirect('/admin/naprawa/' . $id);

        $pdo->prepare('UPDATE repairs SET status_id = ?, updated_at = NOW() WHERE id = ?')
            ->execute([$status_id, (int)$id]);

        $pdo->prepare('INSERT INTO repair_status_history (repair_id, status_id, changed_by, note) VALUES (?, ?, ?, ?)')
            ->execute([(int)$id, $status_id, $_SESSION['user_id'], $note]);

        redirect('/admin/naprawa/' . $id);
    }

    public function updateQuote(string $id): void {
        requireAdmin();
        global $pdo;

        $type   = $_POST['quote_type'] ?? '';
        $amount = (float)($_POST['amount'] ?? 0);
        $note   = trim($_POST['note'] ?? '');

        if ($type === 'initial') {
            $pdo->prepare('UPDATE repairs SET initial_quote_amount = ?, initial_quote_note = ?, initial_quote_sent_at = NOW() WHERE id = ?')
                ->execute([$amount, $note, (int)$id]);
            $status = $pdo->query("SELECT id FROM repair_statuses WHERE code = 'initial_quote_sent'")->fetch();
        } else {
            $pdo->prepare('UPDATE repairs SET final_quote_amount = ?, final_quote_note = ?, final_quote_sent_at = NOW() WHERE id = ?')
                ->execute([$amount, $note, (int)$id]);
            $status = $pdo->query("SELECT id FROM repair_statuses WHERE code = 'final_quote_sent'")->fetch();
        }

        $pdo->prepare('UPDATE repairs SET status_id = ? WHERE id = ?')->execute([$status['id'], (int)$id]);
        $pdo->prepare('INSERT INTO repair_status_history (repair_id, status_id, changed_by, note) VALUES (?, ?, ?, ?)')
            ->execute([(int)$id, $status['id'], $_SESSION['user_id'], 'Wycena: ' . number_format($amount, 2) . ' zł. ' . $note]);

        redirect('/admin/naprawa/' . $id);
    }

    public function calendar(): void {
        requireAdmin();
        global $pdo;

        $repairs = $pdo->query("
            SELECT r.*, rs.label as status_label, rs.color as status_color,
                   u.first_name, u.last_name
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id = rs.id
            JOIN users u ON r.user_id = u.id
            WHERE rs.code NOT IN ('completed','initial_quote_rejected','final_quote_rejected')
            ORDER BY r.updated_at DESC
        ")->fetchAll();

        $pageTitle = 'Kalendarz';
        ob_start();
        include VIEW_PATH . '/admin/calendar.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/admin/layout.php';
    }

    public function payments(): void {
        requireAdmin();
        global $pdo;

        $payments = $pdo->query("
            SELECT p.*, r.rma_number, u.first_name, u.last_name
            FROM payments p
            JOIN repairs r ON p.repair_id = r.id
            JOIN users u ON r.user_id = u.id
            ORDER BY p.created_at DESC
        ")->fetchAll();

        $total_paid = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'paid'")->fetchColumn() ?: 0;

        $pageTitle = 'Płatności';
        ob_start();
        include VIEW_PATH . '/admin/payments.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/admin/layout.php';
    }
}
