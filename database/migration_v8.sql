-- Usuń stare typy urządzeń i dodaj nowe
TRUNCATE TABLE device_types;

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
