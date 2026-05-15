-- Migracja v4 - status paczki w drodze + tabela drzewa diagnostycznego

INSERT IGNORE INTO repair_statuses (code, label, sort_order, color) VALUES
('parcel_sent', 'Paczka w drodze', 6, '#06B6D4');

UPDATE repair_statuses SET sort_order = 7  WHERE code = 'parcel_received';
UPDATE repair_statuses SET sort_order = 8  WHERE code = 'diagnosis';
UPDATE repair_statuses SET sort_order = 9  WHERE code = 'final_quote_sent';
UPDATE repair_statuses SET sort_order = 10 WHERE code = 'final_quote_accepted';
UPDATE repair_statuses SET sort_order = 11 WHERE code = 'final_quote_rejected';
UPDATE repair_statuses SET sort_order = 12 WHERE code = 'final_quote_renegotiation';
UPDATE repair_statuses SET sort_order = 13 WHERE code = 'in_repair';
UPDATE repair_statuses SET sort_order = 14 WHERE code = 'awaiting_payment';
UPDATE repair_statuses SET sort_order = 15 WHERE code = 'paid';
UPDATE repair_statuses SET sort_order = 16 WHERE code = 'shipped_to_client';
UPDATE repair_statuses SET sort_order = 17 WHERE code = 'completed';
UPDATE repair_statuses SET sort_order = 18 WHERE code = 'return_in_progress';

-- Tabela węzłów drzewa diagnostycznego
CREATE TABLE IF NOT EXISTS diag_nodes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id   INT UNSIGNED NULL,
    question    TEXT NOT NULL,
    answer      VARCHAR(255) NULL,
    result      TEXT NULL,
    result_type ENUM('continue','repair','no_repair','contact') DEFAULT 'continue',
    sort_order  INT UNSIGNED DEFAULT 0,
    FOREIGN KEY (parent_id) REFERENCES diag_nodes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Startowe drzewo diagnostyczne
INSERT INTO diag_nodes (id, parent_id, question, answer, result, result_type, sort_order) VALUES
-- KORZEŃ
(1, NULL, 'Jaki typ urządzenia chcesz sprawdzić?', NULL, NULL, 'continue', 0),

-- Gałąź: Lampa LED
(2, 1, 'Co dokładnie się dzieje z lampą?', 'Lampa LED', NULL, 'continue', 1),
(3, 2, 'Czy lampa w ogóle się włącza?', 'Lampa nie świeci wcale', NULL, 'continue', 1),
(4, 2, 'Czy lampa działa ale ma problemy?', 'Lampa działa nieprawidłowo', NULL, 'continue', 2),
(5, 3, 'Czy diody LED są sprawne (świecą się po podłączeniu zasilania bezpośrednio)?', 'Nie ma żadnej reakcji', NULL, 'continue', 1),
(6, 3, 'Czy słychać buczenie lub czuć zapach spalenizny?', 'Widać uszkodzenia mechaniczne', 'Możliwe uszkodzenie zasilacza lub drivera LED. Prawdopodobna przyczyna: przepalone kondensatory lub tranzystory w układzie zasilania. Warto zgłosić do naprawy.', 'repair', 2),
(7, 5, 'Sprawdź czy świeci się dioda zasilania lub kontrolna na sterowniku.', 'Brak jakiejkolwiek reakcji po włączeniu', 'Prawdopodobnie uszkodzony zasilacz lub bezpiecznik. Koszt naprawy od 80 zł. Warto zgłosić.', 'repair', 1),
(8, 5, 'Lampa nie reaguje na aplikację/pilot', 'Zasilanie jest ale lampa nie reaguje', 'Możliwe uszkodzenie modułu sterującego (WiFi/BT) lub głównej płyty. Koszt naprawy od 100 zł.', 'repair', 2),
(9, 4, 'Które diody nie świecą?', 'Miga lub świeci nieregularnie', 'Prawdopodobna usterka drivera LED lub luźne połączenie. Możliwa naprawa od 80 zł.', 'repair', 1),
(10, 4, 'Jaki jest problem ze spektrum?', 'Jeden kanał kolorów nie działa', 'Uszkodzony driver konkretnego kanału kolorowego. Naprawa od 80 zł.', 'repair', 2),
(11, 4, 'Czy lampa nie łączy się z aplikacją?', 'Problemy z połączeniem WiFi/BT', 'Uszkodzony moduł komunikacyjny. Naprawa od 80 zł.', 'repair', 3),

-- Gałąź: Sterownik
(12, 1, 'Co się dzieje ze sterownikiem?', 'Sterownik akwarystyczny', NULL, 'continue', 2),
(13, 12, 'Czy sterownik się uruchamia?', 'Sterownik nie włącza się', NULL, 'continue', 1),
(14, 12, 'Czy sterownik działa ale błędnie?', 'Sterownik działa nieprawidłowo', NULL, 'continue', 2),
(15, 13, 'Czy jest napięcie na zasilaczu (możesz zmierzyć)?', 'Brak reakcji po włączeniu', 'Prawdopodobnie uszkodzony zasilacz lub bezpiecznik. Naprawa od 80 zł.', 'repair', 1),
(16, 13, 'Czy ekran lub diody reagują?', 'Słychać klik przekaźnika ale brak obrazu', 'Możliwe uszkodzenie wyświetlacza lub układu wideo. Naprawa od 80 zł.', 'repair', 2),
(17, 14, 'Czy problem dotyczy konkretnego wyjścia/modułu?', 'Błędy lub restarty', 'Możliwe uszkodzenie pamięci lub niestabilność zasilania. Naprawa od 100 zł.', 'repair', 1),
(18, 14, 'Czy moduły dodatkowe działają?', 'Jedno wyjście nie działa', 'Uszkodzony przekaźnik lub tranzystor sterujący wyjściem. Naprawa od 80 zł.', 'repair', 2),

-- Gałąź: Dozownik
(19, 1, 'Co się dzieje z dozownikiem?', 'Dozownik / ATO / Rollermat', NULL, 'continue', 3),
(20, 19, 'Jaki objaw ma dozownik?', 'Dozownik nie dozuje', NULL, 'continue', 1),
(21, 19, 'Czy dozownik dozuje ale błędnie?', 'Dozuje nieprawidłowe ilości', NULL, 'continue', 2),
(22, 20, 'Czy silniczki/pompy perystaltyczne działają?', 'Brak reakcji po włączeniu', 'Prawdopodobnie uszkodzony zasilacz lub płyta główna. Naprawa od 80 zł.', 'repair', 1),
(23, 20, 'Czy ekran/diody świecą?', 'Zasilanie jest ale nie dozuje', 'Możliwe uszkodzenie sterownika pompy lub błąd kalibracji. Naprawa od 80 zł.', 'repair', 2),
(24, 21, 'Czy przeprowadzałeś kalibrację?', 'Dozuje ale za mało lub za dużo', 'Sprawdź kalibrację — jeśli po kalibracji nadal błędnie, możliwe uszkodzenie czujnika przepływu. Naprawa od 80 zł.', 'repair', 1),

-- Gałąź: Sprzęt zalany
(25, 1, 'Opisz co się stało ze sprzętem', 'Sprzęt zalany / wpadł do akwarium', NULL, 'continue', 4),
(26, 25, 'Jak dawno doszło do zalania?', 'Sprzęt wpadł do akwarium lub został zalany', NULL, 'continue', 1),
(27, 26, 'Ważne: NIE włączaj mokrego sprzętu! Czy próbowałeś go włączyć po zalaniu?', 'Mniej niż 24 godziny temu', 'Szanse na naprawę są wysokie. Natychmiast wyślij sprzęt — czas ma znaczenie! Pamiętaj: na sprzęt po zalaniu nie udzielamy gwarancji, ale podejmujemy próbę naprawy od 100 zł.', 'repair', 1),
(28, 26, 'Czy próbowałeś suszyć sprzęt?', 'Kilka dni lub tygodni temu', 'Szanse na naprawę są mniejsze z powodu korozji. Diagnostyka od 50 zł powie czy naprawa ma sens. Bez gwarancji końcowej.', 'repair', 2),

-- Gałąź: Nie wiem
(29, 1, 'Opisz objawy', 'Inne / nie wiem co to jest', 'Napisz do nas na eliasz.proc@gmail.com lub złóż zgłoszenie opisując dokładnie co się dzieje — postaram się pomóc określić problem.', 'contact', 5);
