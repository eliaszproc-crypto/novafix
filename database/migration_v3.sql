-- Migracja v3 - uproszczenie statusów i rozbicie adresu

-- Usuń shipping_instructions (zbędny - zastąpiony przez initial_quote_accepted)
-- Najpierw zaktualizuj istniejące rekordy
UPDATE repairs SET status_id = (SELECT id FROM repair_statuses WHERE code='initial_quote_accepted')
    WHERE status_id = (SELECT id FROM repair_statuses WHERE code='shipping_instructions');
UPDATE repair_status_history SET status_id = (SELECT id FROM repair_statuses WHERE code='initial_quote_accepted')
    WHERE status_id = (SELECT id FROM repair_statuses WHERE code='shipping_instructions');
DELETE FROM repair_statuses WHERE code='shipping_instructions';

-- Dodaj nowe kolumny adresu zwrotnego (rozbite na pola)
ALTER TABLE repairs
    ADD COLUMN return_first_name VARCHAR(100) NULL AFTER return_address,
    ADD COLUMN return_last_name  VARCHAR(100) NULL AFTER return_first_name,
    ADD COLUMN return_phone      VARCHAR(20)  NULL AFTER return_last_name,
    ADD COLUMN return_street     VARCHAR(200) NULL AFTER return_phone,
    ADD COLUMN return_postal     VARCHAR(10)  NULL AFTER return_street,
    ADD COLUMN return_city       VARCHAR(100) NULL AFTER return_postal;

-- Dodaj adres wysyłki DO serwisu (stały adres - przechowujemy w config)
-- Dodaj kolumnę na numer śledzenia odesłania
ALTER TABLE repairs
    ADD COLUMN return_tracking VARCHAR(100) NULL AFTER tracking_number;

-- Upewnij się że renegocjacje istnieją
INSERT IGNORE INTO repair_statuses (code, label, sort_order, color) VALUES
('initial_quote_renegotiation', 'Renegocjacja wstępnej wyceny', 3,  '#8B5CF6'),
('final_quote_renegotiation',   'Renegocjacja kosztu naprawy',  10, '#8B5CF6'),
('return_in_progress',          'Zwrot sprzętu w toku',         16, '#EF4444');

-- Zaktualizuj nazwy final_quote na koszt naprawy
UPDATE repair_statuses SET label='Koszt naprawy wysłany'       WHERE code='final_quote_sent';
UPDATE repair_statuses SET label='Koszt naprawy zaakceptowany' WHERE code='final_quote_accepted';
UPDATE repair_statuses SET label='Koszt naprawy odrzucony'     WHERE code='final_quote_rejected';
