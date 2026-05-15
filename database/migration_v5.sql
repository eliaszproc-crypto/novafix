-- Migracja v5 - tabela cennika

CREATE TABLE IF NOT EXISTS pricing_items (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category    VARCHAR(100) NOT NULL,
    name        VARCHAR(200) NOT NULL,
    price_from  DECIMAL(10,2) NOT NULL,
    price_to    DECIMAL(10,2) NULL,
    unit        VARCHAR(50) NULL DEFAULT NULL,
    note        VARCHAR(255) NULL,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    sort_order  INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO pricing_items (category, name, price_from, price_to, unit, note, sort_order) VALUES
-- Diagnostyka
('Diagnostyka', 'Diagnostyka urządzenia', 50, NULL, NULL, 'Wstępna ocena usterki i opłacalności naprawy', 1),
('Diagnostyka', 'Diagnostyka sprzętu po zalaniu', 50, NULL, NULL, 'Ocena stopnia uszkodzenia — bez gwarancji skuteczności', 2),

-- Lampy LED
('Lampy LED', 'Naprawa sterownika / płyty głównej', 100, NULL, NULL, 'AI, Kessil, Hydra, Maxspect, Radion i inne', 10),
('Lampy LED', 'Naprawa / wymiana drivera LED', 80, NULL, NULL, 'Naprawa układu zasilającego diody', 11),
('Lampy LED', 'Wymiana diody LED', 20, NULL, 'szt.', 'Zależy od dostępności i typu diod', 12),
('Lampy LED', 'Regeneracja uszkodzonego laminatu PCB', 100, NULL, NULL, 'Odbudowa ścieżek, usunięcie korozji', 13),
('Lampy LED', 'Naprawa modułu WiFi / BT', 80, NULL, NULL, 'Przywrócenie łączności z aplikacją', 14),

-- Sterowniki
('Sterowniki i elektronika', 'Naprawa sterownika akwarystycznego', 100, NULL, NULL, 'Neptune Apex, GHL, CoralBox i inne', 20),
('Sterowniki i elektronika', 'Naprawa elektroniki cyrkulatora / falownika', 100, NULL, NULL, 'Tylko elektronika — nie mechanika', 21),
('Sterowniki i elektronika', 'Naprawa elektroniki odpieniacza', 80, NULL, NULL, 'Sterownik, zasilacz, układ regulacji', 22),
('Sterowniki i elektronika', 'Naprawa wyświetlacza / interfejsu', 80, NULL, NULL, 'Wymiana ekranu, przycisków, złącz', 23),

-- Dozowniki
('Dozowniki i automatyka', 'Naprawa elektroniki dozownika', 80, NULL, NULL, 'Dozowniki Balling, wielokomponentowe', 30),
('Dozowniki i automatyka', 'Naprawa sterownika dolewki ATO', 80, NULL, NULL, 'Elektronika sterująca, czujniki poziomu', 31),
('Dozowniki i automatyka', 'Naprawa elektroniki rollermat', 80, NULL, NULL, 'Sterownik, czujniki, zasilanie', 32),
('Dozowniki i automatyka', 'Naprawa elektroniki chillera', 100, NULL, NULL, 'Sterownik temperatury, układ sterowania', 33),

-- Zalanie
('Sprzęt po kontakcie z wodą', 'Czyszczenie i regeneracja po zalaniu', 100, NULL, NULL, 'Bez gwarancji. Płacisz tylko jeśli naprawa się uda.', 40),
('Sprzęt po kontakcie z wodą', 'Regeneracja laminatu po korozji', 100, NULL, NULL, 'Odbudowa ścieżek PCB, usunięcie korozji galwanicznej', 41);
