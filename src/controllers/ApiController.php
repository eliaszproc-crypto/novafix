<?php
class ApiController {

    public function stats(): void {
        global $pdo;

        header('Content-Type: application/json');
        header('Cache-Control: no-cache');

        try {
            // Liczba nowych zgłoszeń (dziś)
            $new_today = $pdo->query("
                SELECT COUNT(*) FROM repairs r
                JOIN repair_statuses rs ON r.status_id = rs.id
                WHERE rs.code = 'new'
                AND DATE(r.created_at) = CURDATE()
            ")->fetchColumn();

            // Naprawy w toku
            $in_progress = $pdo->query("
                SELECT COUNT(*) FROM repairs r
                JOIN repair_statuses rs ON r.status_id = rs.id
                WHERE rs.code IN ('parcel_received','diagnosis','in_repair','final_quote_accepted')
            ")->fetchColumn();

            // Ostatnio zaakceptowana wycena
            $last_accepted = $pdo->query("
                SELECT r.rma_number, r.initial_quote_decided_at,
                       dt.name as device_type, COALESCE(db.name,'') as brand
                FROM repairs r
                JOIN repair_statuses rs ON r.status_id = rs.id
                JOIN device_types dt ON r.device_type_id = dt.id
                LEFT JOIN device_brands db ON r.device_brand_id = db.id
                WHERE rs.code IN ('initial_quote_accepted','final_quote_accepted')
                ORDER BY r.updated_at DESC LIMIT 1
            ")->fetch();

            // Aktywna naprawa (najnowsza w statusie in_repair)
            $active_repair = $pdo->query("
                SELECT r.rma_number, r.updated_at,
                       dt.name as device_type, COALESCE(db.name,'') as brand,
                       COALESCE(r.device_model,'') as model
                FROM repairs r
                JOIN repair_statuses rs ON r.status_id = rs.id
                JOIN device_types dt ON r.device_type_id = dt.id
                LEFT JOIN device_brands db ON r.device_brand_id = db.id
                WHERE rs.code = 'in_repair'
                ORDER BY r.updated_at DESC LIMIT 1
            ")->fetch();

            // Łączna liczba zakończonych napraw
            $completed = $pdo->query("
                SELECT COUNT(*) FROM repairs r
                JOIN repair_statuses rs ON r.status_id = rs.id
                WHERE rs.code = 'completed'
            ")->fetchColumn();

            // Ostatnio zakończona naprawa (czas temu)
            $last_completed = $pdo->query("
                SELECT r.updated_at FROM repairs r
                JOIN repair_statuses rs ON r.status_id = rs.id
                WHERE rs.code = 'completed'
                ORDER BY r.updated_at DESC LIMIT 1
            ")->fetchColumn();

            echo json_encode([
                'new_today'      => (int)$new_today,
                'in_progress'    => (int)$in_progress,
                'completed'      => (int)$completed,
                'last_accepted'  => $last_accepted ?: null,
                'active_repair'  => $active_repair ?: null,
                'last_completed' => $last_completed ?: null,
                'timestamp'      => time(),
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
        exit;
    }
}
