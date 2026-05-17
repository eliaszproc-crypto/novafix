-- Nowe typy urządzeń (bezpieczne - nie usuwa starych)
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE device_types;
SET FOREIGN_KEY_CHECKS=1;

INSERT INTO device_types (name, is_active) VALUES
('Lampa LED', 1),
('Lampa T5', 1),
('Falownik', 1),
('Cyrkulator', 1),
('Dozownik', 1),
('Urządzenia pomiarowe', 1),
('Komputer / sterownik akwarystyczny', 1),
('Chiller', 1),
('Inne', 1);
