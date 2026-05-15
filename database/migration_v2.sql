-- Migracja v2 - renegocjacje, komentarze odrzucenia, adres zwrotny

-- Dodaj kolumny do repairs
ALTER TABLE repairs
    -- Komentarz klienta przy odrzuceniu wstępnej wyceny
    ADD COLUMN initial_quote_rejection_note TEXT NULL AFTER initial_quote_decided_at,
    -- Komentarz klienta przy odrzuceniu kosztu naprawy
    ADD COLUMN final_quote_rejection_note TEXT NULL AFTER final_quote_decided_at,
    -- Adres zwrotny (tekst - imię, ulica, kod, miasto)
    ADD COLUMN return_address TEXT NULL AFTER tracking_number,
    -- Licznik renegocjacji
    ADD COLUMN negotiation_round INT UNSIGNED NOT NULL DEFAULT 0 AFTER return_address;

-- Aktualizacja statusów - nowe nazwy i kody
UPDATE repair_statuses SET label = 'Koszt naprawy wysłany'       WHERE code = 'final_quote_sent';
UPDATE repair_statuses SET label = 'Koszt naprawy zaakceptowany' WHERE code = 'final_quote_accepted';
UPDATE repair_statuses SET label = 'Koszt naprawy odrzucony'     WHERE code = 'final_quote_rejected';

-- Nowe statusy
INSERT IGNORE INTO repair_statuses (code, label, sort_order, color) VALUES
('initial_quote_renegotiation', 'Renegocjacja wstępnej wyceny', 3,  '#8B5CF6'),
('final_quote_renegotiation',   'Renegocjacja kosztu naprawy',  10, '#8B5CF6'),
('return_in_progress',          'Zwrot sprzętu w toku',         13, '#EF4444');

-- Przebudowa tabeli payments - tylko opłacone
ALTER TABLE payments
    MODIFY COLUMN status ENUM('paid','refunded') NOT NULL DEFAULT 'paid';
