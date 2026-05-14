<?php
class ClientController {

    public function dashboard(): void {
        requireLogin();
        global $pdo;
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare('
            SELECT r.*, rs.label as status_label, rs.color as status_color,
                   dt.name as device_type, db.name as device_brand
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id = rs.id
            JOIN device_types dt ON r.device_type_id = dt.id
            LEFT JOIN device_brands db ON r.device_brand_id = db.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
            LIMIT 5
        ');
        $stmt->execute([$user_id]);
        $repairs = $stmt->fetchAll();

        $stmt2 = $pdo->prepare('SELECT COUNT(*) as total FROM repairs WHERE user_id = ?');
        $stmt2->execute([$user_id]);
        $total = $stmt2->fetch()['total'];

        $pageTitle = 'Panel klienta';
        ob_start();
        include VIEW_PATH . '/client/dashboard.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }

    public function repairs(): void {
        requireLogin();
        global $pdo;
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare('
            SELECT r.*, rs.label as status_label, rs.color as status_color,
                   dt.name as device_type, db.name as device_brand
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id = rs.id
            JOIN device_types dt ON r.device_type_id = dt.id
            LEFT JOIN device_brands db ON r.device_brand_id = db.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ');
        $stmt->execute([$user_id]);
        $repairs = $stmt->fetchAll();

        $pageTitle = 'Moje zgłoszenia';
        ob_start();
        include VIEW_PATH . '/client/repairs.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }

    public function newRepairForm(): void {
        requireLogin();
        global $pdo;

        $device_types  = $pdo->query('SELECT * FROM device_types WHERE is_active = 1')->fetchAll();
        $device_brands = $pdo->query('SELECT * FROM device_brands WHERE is_active = 1')->fetchAll();
        $addresses     = $pdo->prepare('SELECT * FROM addresses WHERE user_id = ?');
        $addresses->execute([$_SESSION['user_id']]);
        $addresses = $addresses->fetchAll();

        $error = $_GET['error'] ?? '';
        $pageTitle = 'Nowe zgłoszenie';
        ob_start();
        include VIEW_PATH . '/client/new-repair.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }

    public function newRepairSubmit(): void {
        requireLogin();
        global $pdo;
        $user_id = $_SESSION['user_id'];

        $device_type_id  = (int)($_POST['device_type_id'] ?? 0);
        $device_brand_id = (int)($_POST['device_brand_id'] ?? 0) ?: null;
        $device_model    = trim($_POST['device_model'] ?? '');
        $problem         = trim($_POST['problem_description'] ?? '');

        if (!$device_type_id || !$problem) {
            redirect('/panel/nowe-zgloszenie?error=Wypełnij wymagane pola');
        }

        $status = $pdo->query("SELECT id FROM repair_statuses WHERE code = 'new'")->fetch();
        $rma    = generateRMA();

        $stmt = $pdo->prepare('
            INSERT INTO repairs (rma_number, user_id, device_type_id, device_brand_id, device_model, problem_description, status_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$rma, $user_id, $device_type_id, $device_brand_id, $device_model, $problem, $status['id']]);
        $repair_id = $pdo->lastInsertId();

        // Zdjęcia
        if (!empty($_FILES['photos']['name'][0])) {
            $config = require ROOT_PATH . '/config/config.php';
            $upload_path = ROOT_PATH . '/public/uploads/';
            foreach ($_FILES['photos']['tmp_name'] as $i => $tmp) {
                if ($_FILES['photos']['error'][$i] !== 0) continue;
                $ext = strtolower(pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg','jpeg','png','webp'])) continue;
                $filename = 'repair_' . $repair_id . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($tmp, $upload_path . $filename)) {
                    $pdo->prepare('INSERT INTO repair_photos (repair_id, filename) VALUES (?, ?)')->execute([$repair_id, $filename]);
                }
            }
        }

        // Historia statusu
        $pdo->prepare('INSERT INTO repair_status_history (repair_id, status_id, changed_by, note) VALUES (?, ?, ?, ?)')
            ->execute([$repair_id, $status['id'], $user_id, 'Zgłoszenie utworzone przez klienta']);

        redirect('/panel/naprawa/' . $repair_id);
    }

    public function repairDetail(string $id): void {
        requireLogin();
        global $pdo;

        $stmt = $pdo->prepare('
            SELECT r.*, rs.label as status_label, rs.color as status_color,
                   dt.name as device_type, db.name as device_brand
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id = rs.id
            JOIN device_types dt ON r.device_type_id = dt.id
            LEFT JOIN device_brands db ON r.device_brand_id = db.id
            WHERE r.id = ? AND r.user_id = ?
        ');
        $stmt->execute([(int)$id, $_SESSION['user_id']]);
        $repair = $stmt->fetch();
        if (!$repair) redirect('/panel');

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

        $pageTitle = 'Zgłoszenie ' . $repair['rma_number'];
        ob_start();
        include VIEW_PATH . '/client/repair-detail.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }
}
