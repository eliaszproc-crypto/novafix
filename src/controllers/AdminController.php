<?php
class AdminController {


    public function statistics(): void {
        requireAdmin(); global $pdo;
        $pageTitle = 'Statystyki';
        ob_start(); include VIEW_PATH.'/admin/stats.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function dashboard(): void {
        requireAdmin(); global $pdo;
        $stats = [
            'total'   => $pdo->query('SELECT COUNT(*) FROM repairs')->fetchColumn(),
            'new'     => $pdo->query("SELECT COUNT(*) FROM repairs r JOIN repair_statuses rs ON r.status_id=rs.id WHERE rs.code='new'")->fetchColumn(),
            'active'  => $pdo->query("SELECT COUNT(*) FROM repairs r JOIN repair_statuses rs ON r.status_id=rs.id WHERE rs.code NOT IN ('completed','initial_quote_rejected','final_quote_rejected','return_in_progress')")->fetchColumn(),
            'clients' => $pdo->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn(),
        ];
        $recent = $pdo->query('
            SELECT r.id, r.rma_number, r.created_at,
                   rs.label as status_label, rs.color as status_color,
                   dt.name as device_type, u.first_name, u.last_name
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id=rs.id
            JOIN device_types dt ON r.device_type_id=dt.id
            JOIN users u ON r.user_id=u.id
            ORDER BY r.created_at DESC LIMIT 8
        ')->fetchAll();
        $pageTitle = 'Dashboard';
        ob_start(); include VIEW_PATH.'/admin/dashboard.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function repairs(): void {
        requireAdmin(); global $pdo;
        $status_filter = trim($_GET['status'] ?? '');
        $search        = trim($_GET['q'] ?? '');
        $sql = '
            SELECT r.id, r.rma_number, r.created_at,
                   rs.label as status_label, rs.color as status_color, rs.code as status_code,
                   dt.name as device_type, COALESCE(db.name,\'\') as device_brand,
                   u.first_name, u.last_name, u.email
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id=rs.id
            JOIN device_types dt ON r.device_type_id=dt.id
            LEFT JOIN device_brands db ON r.device_brand_id=db.id
            JOIN users u ON r.user_id=u.id
            WHERE 1=1
        ';
        $params = [];
        if ($status_filter) { $sql .= ' AND rs.code=?'; $params[] = $status_filter; }
        if ($search) {
            $sql .= ' AND (r.rma_number LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)';
            $s = "%$search%";
            $params = array_merge($params, [$s,$s,$s,$s]);
        }
        $sql .= ' ORDER BY r.created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $repairs  = $stmt->fetchAll();
        $statuses = $pdo->query('SELECT * FROM repair_statuses ORDER BY sort_order')->fetchAll();
        $success  = $_GET['success'] ?? '';
        $pageTitle = 'Zgłoszenia';
        ob_start(); include VIEW_PATH.'/admin/repairs.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function repairDetail(string $id): void {
        requireAdmin(); global $pdo;
        $stmt = $pdo->prepare('
            SELECT r.*, rs.label as status_label, rs.color as status_color, rs.code as status_code,
                   dt.name as device_type, COALESCE(db.name,\'\') as device_brand,
                   u.first_name, u.last_name, u.email, u.phone
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id=rs.id
            JOIN device_types dt ON r.device_type_id=dt.id
            LEFT JOIN device_brands db ON r.device_brand_id=db.id
            JOIN users u ON r.user_id=u.id
            WHERE r.id=?
        ');
        $stmt->execute([(int)$id]);
        $repair = $stmt->fetch();
        if (!$repair) redirect('/admin/zgloszenia');

        $photos = $pdo->prepare('SELECT * FROM repair_photos WHERE repair_id=?');
        $photos->execute([(int)$id]);
        $photos = $photos->fetchAll();

        $history = $pdo->prepare('
            SELECT rsh.*, rs.label, rs.color, u.first_name, u.last_name
            FROM repair_status_history rsh
            JOIN repair_statuses rs ON rsh.status_id=rs.id
            JOIN users u ON rsh.changed_by=u.id
            WHERE rsh.repair_id=? ORDER BY rsh.changed_at ASC
        ');
        $history->execute([(int)$id]);
        $history = $history->fetchAll();

        $admin_photos = $pdo->prepare('SELECT * FROM admin_photos WHERE repair_id=? ORDER BY created_at ASC');
        $admin_photos->execute([(int)$id]);
        $admin_photos = $admin_photos->fetchAll();
        $statuses = $pdo->query('SELECT * FROM repair_statuses ORDER BY sort_order')->fetchAll();
        $success  = $_GET['success'] ?? '';
        $error    = $_GET['error'] ?? '';
        $pageTitle = 'Zgłoszenie '.$repair['rma_number'];
        ob_start(); include VIEW_PATH.'/admin/repair-detail.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function updateStatus(string $id): void {
        requireAdmin(); global $pdo;
        $status_id = (int)($_POST['status_id'] ?? 0);
        $note      = trim($_POST['note'] ?? '');
        if (!$status_id) redirect('/admin/naprawa/'.$id);
        $pdo->prepare('UPDATE repairs SET status_id=?, updated_at=NOW() WHERE id=?')->execute([$status_id,(int)$id]);
        $pdo->prepare('INSERT INTO repair_status_history (repair_id,status_id,changed_by,note) VALUES (?,?,?,?)')->execute([(int)$id,$status_id,$_SESSION['user_id'],$note ?: 'Status zmieniony przez admina']);
        // Wyślij email do klienta
        $sc = $pdo->query("SELECT code,label FROM repair_statuses WHERE id=$status_id")->fetch();
        if ($sc) notifyClientStatusChange((int)$id, $sc['code'], $sc['label'], require ROOT_PATH.'/config/config.php');
        redirect('/admin/naprawa/'.$id.'?success=Status zaktualizowany');
    }

    public function sendQuote(string $id): void {
        requireAdmin(); global $pdo;
        $type   = $_POST['quote_type'] ?? 'initial';
        $amount = (float)str_replace(',','.',$_POST['amount'] ?? 0);
        $note   = trim($_POST['note'] ?? '');
        if ($amount <= 0) redirect('/admin/naprawa/'.$id.'?error=Podaj kwotę większą niż 0');

        if ($type === 'initial') {
            $pdo->prepare('UPDATE repairs SET initial_quote_amount=?, initial_quote_note=?, initial_quote_sent_at=NOW(), initial_quote_decided_at=NULL, initial_quote_rejection_note=NULL, negotiation_round=negotiation_round+1 WHERE id=?')
                ->execute([$amount,$note,(int)$id]);
            $status_code = 'initial_quote_sent';
            $hist_note   = 'Wstępna wycena: '.number_format($amount,2).' zł';
        } else {
            $pdo->prepare('UPDATE repairs SET final_quote_amount=?, final_quote_note=?, final_quote_sent_at=NOW(), final_quote_decided_at=NULL, final_quote_rejection_note=NULL, negotiation_round=negotiation_round+1 WHERE id=?')
                ->execute([$amount,$note,(int)$id]);
            $status_code = 'final_quote_sent';
            $hist_note   = 'Koszt naprawy: '.number_format($amount,2).' zł';
        }

        $status_id = $pdo->prepare('SELECT id FROM repair_statuses WHERE code=?');
        $status_id->execute([$status_code]);
        $status_id = $status_id->fetchColumn();
        $pdo->prepare('UPDATE repairs SET status_id=?, updated_at=NOW() WHERE id=?')->execute([$status_id,(int)$id]);
        $pdo->prepare('INSERT INTO repair_status_history (repair_id,status_id,changed_by,note) VALUES (?,?,?,?)')->execute([(int)$id,$status_id,$_SESSION['user_id'],$hist_note.($note ? '. '.$note : '')]);
        $sc2 = $pdo->query("SELECT code,label FROM repair_statuses WHERE id=$status_id")->fetch();
        if ($sc2) notifyClientStatusChange((int)$id, $sc2['code'], $sc2['label'], require ROOT_PATH.'/config/config.php');
        redirect('/admin/naprawa/'.$id.'?success=Wycena wysłana do klienta');
    }


    public function setPaymentMethod(string $id): void {
        requireAdmin(); global $pdo;
        $method = $_POST['method'] ?? 'transfer';
        $amount = (float)str_replace(',', '.', $_POST['amount'] ?? 0);
        if ($amount <= 0) redirect('/admin/naprawa/'.$id.'?error=Podaj kwotę większą niż 0');

        // Zapisz metodę i kwotę
        $pdo->prepare('UPDATE repairs SET payment_method=?, final_quote_amount=?, updated_at=NOW() WHERE id=?')
            ->execute([$method, $amount, (int)$id]);

        // Ustaw status na awaiting_payment
        $status_id = $pdo->query("SELECT id FROM repair_statuses WHERE code='awaiting_payment'")->fetchColumn();
        $pdo->prepare('UPDATE repairs SET status_id=? WHERE id=?')->execute([$status_id, (int)$id]);

        $method_labels = ['transfer'=>'Przelew bankowy','blik'=>'BLIK','cash'=>'Gotówka przy odbiorze'];
        $label = $method_labels[$method] ?? $method;
        $pdo->prepare('INSERT INTO repair_status_history (repair_id,status_id,changed_by,note) VALUES (?,?,?,?)')
            ->execute([(int)$id, $status_id, $_SESSION['user_id'],
                'Oczekiwanie na płatność. Forma: '.$label.'. Kwota: '.number_format($amount,2,',','').' zł']);

        notifyClientStatusChange((int)$id, 'awaiting_payment', 'Oczekuje na płatność', require ROOT_PATH.'/config/config.php');
        redirect('/admin/naprawa/'.$id.'?success=Forma płatności zapisana — klient widzi instrukcje');
    }

    public function markPaid(string $id): void {
        requireAdmin(); global $pdo;
        $method = $_POST['method'] ?? 'transfer';
        $repair = $pdo->prepare('SELECT final_quote_amount FROM repairs WHERE id=?');
        $repair->execute([(int)$id]);
        $repair = $repair->fetch();
        if (!$repair || !$repair['final_quote_amount']) redirect('/admin/naprawa/'.$id.'?error=Brak kosztu naprawy');
        $pdo->prepare('INSERT INTO payments (repair_id,amount,method,status,paid_at) VALUES (?,?,?,\'paid\',NOW())')->execute([(int)$id,$repair['final_quote_amount'],$method]);
        $status_id = $pdo->query("SELECT id FROM repair_statuses WHERE code='paid'")->fetchColumn();
        $pdo->prepare('UPDATE repairs SET status_id=?, updated_at=NOW() WHERE id=?')->execute([$status_id,(int)$id]);
        $pdo->prepare('INSERT INTO repair_status_history (repair_id,status_id,changed_by,note) VALUES (?,?,?,?)')->execute([(int)$id,$status_id,$_SESSION['user_id'],'Płatność potwierdzona. Metoda: '.$method]);
        redirect('/admin/naprawa/'.$id.'?success=Płatność zarejestrowana');
    }

    public function markReturning(string $id): void {
        requireAdmin(); global $pdo;
        $note = trim($_POST['note'] ?? 'Zwrot sprzętu do klienta');
        $status_id = $pdo->query("SELECT id FROM repair_statuses WHERE code='return_in_progress'")->fetchColumn();
        $pdo->prepare('UPDATE repairs SET status_id=?, updated_at=NOW() WHERE id=?')->execute([$status_id,(int)$id]);
        $pdo->prepare('INSERT INTO repair_status_history (repair_id,status_id,changed_by,note) VALUES (?,?,?,?)')->execute([(int)$id,$status_id,$_SESSION['user_id'],$note]);
        redirect('/admin/naprawa/'.$id.'?success=Oznaczono jako zwrot sprzętu');
    }


    public function sendMessage(string $id): void {
        requireAdmin(); global $pdo;
        $msg = trim($_POST['message'] ?? '');
        if (strlen($msg) < 1) { http_response_code(400); echo json_encode(['error' => 'Pusta']); exit; }
        $pdo->prepare('INSERT INTO repair_messages (repair_id, user_id, message) VALUES (?,?,?)')
            ->execute([(int)$id, $_SESSION['user_id'], $msg]);
        // Oznacz wiadomości klienta jako przeczytane
        $pdo->prepare("UPDATE repair_messages SET is_read=1 WHERE repair_id=? AND user_id != ?")->execute([(int)$id, $_SESSION['user_id']]);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    public function getMessages(string $id): void {
        requireAdmin(); global $pdo;
        $msgs = $pdo->prepare("
            SELECT m.*, u.first_name, u.last_name, u.role
            FROM repair_messages m
            JOIN users u ON m.user_id=u.id
            WHERE m.repair_id=?
            ORDER BY m.created_at ASC
        ");
        $msgs->execute([(int)$id]);
        header('Content-Type: application/json');
        echo json_encode($msgs->fetchAll());
        exit;
    }

    public function markRead(string $id): void {
        requireAdmin(); global $pdo;
        $pdo->prepare("UPDATE repair_messages SET is_read=1 WHERE repair_id=? AND user_id != ?")->execute([(int)$id, $_SESSION['user_id']]);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }


    public function addPhoto(string $id): void {
        requireAdmin(); global $pdo;
        if (empty($_FILES['photo']['tmp_name'])) redirect('/admin/naprawa/'.$id.'?error=Brak zdjęcia');
        $caption = trim($_POST['caption'] ?? '');
        $tmp = $_FILES['photo']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) redirect('/admin/naprawa/'.$id.'?error=Nieprawidłowy format');
        $filename = 'admin_'.$id.'_'.uniqid().'.jpg';
        if (ImageHelper::resizeAndSave($tmp, uploadPath().$filename, 1200, 900)) {
            $pdo->prepare('INSERT INTO admin_photos (repair_id, filename, caption) VALUES (?,?,?)')->execute([(int)$id, $filename, $caption ?: null]);
            redirect('/admin/naprawa/'.$id.'?success=Zdjęcie dodane');
        }
        redirect('/admin/naprawa/'.$id.'?error=Błąd przy zapisie zdjęcia');
    }

    public function deleteAdminPhoto(string $id, string $pid): void {
        requireAdmin(); global $pdo;
        $stmt = $pdo->prepare('SELECT filename FROM admin_photos WHERE id=? AND repair_id=?');
        $stmt->execute([(int)$pid, (int)$id]);
        $photo = $stmt->fetch();
        if ($photo) {
            $path = uploadPath().$photo['filename'];
            if (file_exists($path)) unlink($path);
            $pdo->prepare('DELETE FROM admin_photos WHERE id=?')->execute([(int)$pid]);
        }
        redirect('/admin/naprawa/'.$id.'?success=Zdjęcie usunięte');
    }

    public function deleteRepair(string $id): void {
        requireAdmin(); global $pdo;
        // Usuń zdjęcia z dysku
        $photos = $pdo->prepare('SELECT filename FROM repair_photos WHERE repair_id=?');
        $photos->execute([(int)$id]);
        foreach ($photos->fetchAll() as $p) {
            $path = uploadPath().$p['filename'];
            if (file_exists($path)) unlink($path);
        }
        // Usuń powiązane rekordy ręcznie (foreign key constraints)
        $pdo->prepare('DELETE FROM payments WHERE repair_id=?')->execute([(int)$id]);
        $pdo->prepare('DELETE FROM notifications WHERE repair_id=?')->execute([(int)$id]);
        $pdo->prepare('DELETE FROM repair_status_history WHERE repair_id=?')->execute([(int)$id]);
        $pdo->prepare('DELETE FROM repair_photos WHERE repair_id=?')->execute([(int)$id]);
        $pdo->prepare('DELETE FROM repairs WHERE id=?')->execute([(int)$id]);
        redirect('/admin/zgloszenia?success=Zgłoszenie usunięte');
    }

    public function users(): void {
        requireAdmin(); global $pdo;
        $users = $pdo->query("
            SELECT u.*, COUNT(r.id) as repair_count
            FROM users u
            LEFT JOIN repairs r ON r.user_id=u.id
            WHERE u.role='client'
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ")->fetchAll();
        $success = $_GET['success'] ?? '';
        $pageTitle = 'Użytkownicy';
        ob_start(); include VIEW_PATH.'/admin/users.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function deleteUser(string $id): void {
        requireAdmin(); global $pdo;
        // Nie pozwól usunąć admina
        $user = $pdo->prepare('SELECT role FROM users WHERE id=?');
        $user->execute([(int)$id]);
        $user = $user->fetch();
        if (!$user || $user['role'] === 'admin') redirect('/admin/uzytkownicy?error=Nie można usunąć admina');
        // Usuń zdjęcia powiązanych napraw
        $photos = $pdo->prepare('SELECT rp.filename FROM repair_photos rp JOIN repairs r ON rp.repair_id=r.id WHERE r.user_id=?');
        $photos->execute([(int)$id]);
        foreach ($photos->fetchAll() as $p) {
            $path = uploadPath().$p['filename'];
            if (file_exists($path)) unlink($path);
        }
        $pdo->prepare('DELETE FROM users WHERE id=?')->execute([(int)$id]);
        redirect('/admin/uzytkownicy?success=Użytkownik usunięty');
    }

    public function deletePayment(string $id): void {
        requireAdmin(); global $pdo;
        $pdo->prepare('DELETE FROM payments WHERE id=?')->execute([(int)$id]);
        redirect('/admin/platnosci?success=Płatność usunięta');
    }


    public function diagnostics(): void {
        requireAdmin(); global $pdo;
        $pageTitle = 'Drzewo diagnostyczne';
        ob_start(); include VIEW_PATH.'/admin/diagnostics.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }


    public function diagSavePositions(): void {
        requireAdmin(); global $pdo;
        $positions = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $pdo->prepare('UPDATE diag_nodes SET pos_x=?, pos_y=? WHERE id=?');
        foreach ($positions as $pos) {
            if (isset($pos['id'], $pos['x'], $pos['y'])) {
                $stmt->execute([(int)$pos['x'], (int)$pos['y'], (int)$pos['id']]);
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }


    public function diagEditEn(string $id): void {
        requireAdmin(); global $pdo;
        $question_en = trim($_POST['question_en'] ?? '') ?: null;
        $answer_en   = trim($_POST['answer_en'] ?? '') ?: null;
        $result_en   = trim($_POST['result_en'] ?? '') ?: null;
        $pdo->prepare('UPDATE diag_nodes SET question_en=?, answer_en=?, result_en=? WHERE id=?')
            ->execute([$question_en, $answer_en, $result_en, (int)$id]);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    public function diagAdd(): void {
        requireAdmin(); global $pdo;
        $parent_id   = (int)($_POST['parent_id'] ?? 0) ?: null;
        $question    = trim($_POST['question'] ?? '');
        $answer      = trim($_POST['answer'] ?? '') ?: null;
        $result      = trim($_POST['result'] ?? '') ?: null;
        $result_type = $_POST['result_type'] ?? 'continue';
        $sort_order  = (int)($_POST['sort_order'] ?? 0);
        if (!$question) redirect('/admin/diagnostyka?error=Pytanie jest wymagane');
        $pdo->prepare('INSERT INTO diag_nodes (parent_id,question,answer,result,result_type,sort_order) VALUES (?,?,?,?,?,?)')
            ->execute([$parent_id,$question,$answer,$result,$result_type,$sort_order]);
        redirect('/admin/diagnostyka?success=Węzeł dodany');
    }

    public function diagEdit(string $id): void {
        requireAdmin(); global $pdo;
        $question    = trim($_POST['question'] ?? '');
        $answer      = trim($_POST['answer'] ?? '') ?: null;
        $result      = trim($_POST['result'] ?? '') ?: null;
        $result_type = $_POST['result_type'] ?? 'continue';
        $sort_order  = (int)($_POST['sort_order'] ?? 0);
        $parent_id   = (isset($_POST['parent_id']) && $_POST['parent_id'] !== '' && $_POST['parent_id'] !== '0') ? (int)$_POST['parent_id'] : null;

        if (!$question) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) { http_response_code(400); exit; }
            redirect('/admin/diagnostyka?error=Pytanie jest wymagane');
        }

        $question_en = trim($_POST['question_en'] ?? '') ?: null;
        $answer_en   = trim($_POST['answer_en'] ?? '') ?: null;
        $result_en   = trim($_POST['result_en'] ?? '') ?: null;

        if (isset($_POST['parent_id'])) {
            $pdo->prepare('UPDATE diag_nodes SET question=?,answer=?,result=?,question_en=?,answer_en=?,result_en=?,result_type=?,sort_order=?,parent_id=? WHERE id=?')
                ->execute([$question,$answer,$result,$question_en,$answer_en,$result_en,$result_type,$sort_order,$parent_id,(int)$id]);
        } else {
            $pdo->prepare('UPDATE diag_nodes SET question=?,answer=?,result=?,question_en=?,answer_en=?,result_en=?,result_type=?,sort_order=? WHERE id=?')
                ->execute([$question,$answer,$result,$question_en,$answer_en,$result_en,$result_type,$sort_order,(int)$id]);
        }

        // Jeśli żądanie AJAX - zwróć 200
        if (isset($_SERVER['HTTP_FETCH_DEST']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit;
        }
        redirect('/admin/diagnostyka?success=Węzeł zaktualizowany');
    }

    public function diagDelete(string $id): void {
        requireAdmin(); global $pdo;
        $pdo->prepare('DELETE FROM diag_nodes WHERE id=?')->execute([(int)$id]);
        redirect('/admin/diagnostyka?success=Węzeł usunięty');
    }


    public function pricing(): void {
        requireAdmin(); global $pdo;
        $items = $pdo->query('SELECT * FROM pricing_items ORDER BY sort_order,id')->fetchAll();
        $categories = array_unique(array_column($items, 'category'));
        $by_cat = [];
        foreach ($items as $item) $by_cat[$item['category']][] = $item;
        $success = $_GET['success'] ?? '';
        $error   = $_GET['error'] ?? '';
        $edit_id = (int)($_GET['edit'] ?? 0);
        $edit_item = null;
        if ($edit_id) {
            $s = $pdo->prepare('SELECT * FROM pricing_items WHERE id=?');
            $s->execute([$edit_id]);
            $edit_item = $s->fetch();
        }
        $pageTitle = 'Cennik';
        ob_start(); include VIEW_PATH.'/admin/pricing.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function pricingAdd(): void {
        requireAdmin(); global $pdo;
        $pdo->prepare('INSERT INTO pricing_items (category,name,price_from,price_to,unit,note,is_active,sort_order) VALUES (?,?,?,?,?,?,?,?)')
            ->execute([
                trim($_POST['category'] ?? ''),
                trim($_POST['name'] ?? ''),
                (float)str_replace(',','.',$_POST['price_from'] ?? 0),
                $_POST['price_to'] ? (float)str_replace(',','.',$_POST['price_to']) : null,
                trim($_POST['unit'] ?? '') ?: null,
                trim($_POST['note'] ?? '') ?: null,
                isset($_POST['is_active']) ? 1 : 0,
                (int)($_POST['sort_order'] ?? 0),
            ]);
        redirect('/admin/cennik?success=Pozycja dodana');
    }

    public function pricingEdit(string $id): void {
        requireAdmin(); global $pdo;
        $pdo->prepare('UPDATE pricing_items SET category=?,name=?,price_from=?,price_to=?,unit=?,note=?,is_active=?,sort_order=? WHERE id=?')
            ->execute([
                trim($_POST['category'] ?? ''),
                trim($_POST['name'] ?? ''),
                (float)str_replace(',','.',$_POST['price_from'] ?? 0),
                $_POST['price_to'] ? (float)str_replace(',','.',$_POST['price_to']) : null,
                trim($_POST['unit'] ?? '') ?: null,
                trim($_POST['note'] ?? '') ?: null,
                isset($_POST['is_active']) ? 1 : 0,
                (int)($_POST['sort_order'] ?? 0),
                (int)$id,
            ]);
        redirect('/admin/cennik?success=Pozycja zaktualizowana');
    }

    public function pricingDelete(string $id): void {
        requireAdmin(); global $pdo;
        $pdo->prepare('DELETE FROM pricing_items WHERE id=?')->execute([(int)$id]);
        redirect('/admin/cennik?success=Pozycja usunięta');
    }


    public function reviews(): void {
        requireAdmin(); global $pdo;
        $reviews = $pdo->query("
            SELECT r.*, rep.rma_number
            FROM reviews r
            LEFT JOIN repairs rep ON r.repair_id=rep.id
            ORDER BY r.created_at DESC
        ")->fetchAll();
        $success = $_GET['success'] ?? '';
        $pageTitle = 'Opinie';
        ob_start(); include VIEW_PATH.'/admin/reviews.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function reviewToggle(string $id): void {
        requireAdmin(); global $pdo;
        $pdo->prepare('UPDATE reviews SET is_visible = !is_visible WHERE id=?')->execute([(int)$id]);
        redirect('/admin/opinie?success=Widoczność zmieniona');
    }

    public function reviewDelete(string $id): void {
        requireAdmin(); global $pdo;
        $pdo->prepare('DELETE FROM reviews WHERE id=?')->execute([(int)$id]);
        redirect('/admin/opinie?success=Opinia usunięta');
    }

    public function calendar(): void {
        requireAdmin(); global $pdo;
        $repairs = $pdo->query("
            SELECT r.id, r.rma_number, r.updated_at,
                   rs.label as status_label, rs.color as status_color,
                   u.first_name, u.last_name
            FROM repairs r
            JOIN repair_statuses rs ON r.status_id=rs.id
            JOIN users u ON r.user_id=u.id
            WHERE rs.code NOT IN ('completed','initial_quote_rejected','final_quote_rejected','return_in_progress')
            ORDER BY r.updated_at DESC
        ")->fetchAll();
        $pageTitle = 'Kalendarz';
        ob_start(); include VIEW_PATH.'/admin/calendar.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }

    public function payments(): void {
        requireAdmin(); global $pdo;
        $payments   = $pdo->query("
            SELECT p.*, r.rma_number, u.first_name, u.last_name
            FROM payments p JOIN repairs r ON p.repair_id=r.id JOIN users u ON r.user_id=u.id
            ORDER BY p.created_at DESC
        ")->fetchAll();
        $total_paid = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid'")->fetchColumn();
        $success    = $_GET['success'] ?? '';
        $pageTitle  = 'Płatności';
        ob_start(); include VIEW_PATH.'/admin/payments.php'; $content = ob_get_clean();
        include VIEW_PATH.'/admin/layout.php';
    }
}
