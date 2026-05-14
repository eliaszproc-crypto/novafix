-- ============================================================
-- NovaFix - Schemat bazy danych MySQL
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- ------------------------------------------------------------
-- Użytkownicy (klienci + admin)
-- ------------------------------------------------------------
CREATE TABLE users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    first_name      VARCHAR(100) NOT NULL,
    last_name       VARCHAR(100) NOT NULL,
    phone           VARCHAR(20),
    role            ENUM('client', 'admin') NOT NULL DEFAULT 'client',
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Adresy wysyłkowe klientów
-- ------------------------------------------------------------
CREATE TABLE addresses (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    full_name       VARCHAR(200) NOT NULL,
    street          VARCHAR(255) NOT NULL,
    city            VARCHAR(100) NOT NULL,
    postal_code     VARCHAR(10) NOT NULL,
    country         VARCHAR(100) NOT NULL DEFAULT 'Polska',
    is_default      TINYINT(1) NOT NULL DEFAULT 0,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Typy urządzeń (lampa, falownik, sterownik itd.)
-- ------------------------------------------------------------
CREATE TABLE device_types (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL UNIQUE,
    is_active       TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Marki urządzeń
-- ------------------------------------------------------------
CREATE TABLE device_brands (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL UNIQUE,
    is_active       TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Statusy napraw (słownik)
-- ------------------------------------------------------------
CREATE TABLE repair_statuses (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code            VARCHAR(50) NOT NULL UNIQUE,
    label           VARCHAR(150) NOT NULL,
    sort_order      INT UNSIGNED NOT NULL DEFAULT 0,
    color           VARCHAR(7) NOT NULL DEFAULT '#888888'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Zgłoszenia napraw
-- ------------------------------------------------------------
CREATE TABLE repairs (
    id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rma_number                  VARCHAR(20) NOT NULL UNIQUE,
    user_id                     INT UNSIGNED NOT NULL,
    device_type_id              INT UNSIGNED NOT NULL,
    device_brand_id             INT UNSIGNED,
    device_model                VARCHAR(150),
    problem_description         TEXT NOT NULL,
    status_id                   INT UNSIGNED NOT NULL,
    shipping_address_id         INT UNSIGNED,
    initial_quote_amount        DECIMAL(10,2),
    initial_quote_note          TEXT,
    initial_quote_sent_at       DATETIME,
    initial_quote_decided_at    DATETIME,
    final_quote_amount          DECIMAL(10,2),
    final_quote_note            TEXT,
    final_quote_sent_at         DATETIME,
    final_quote_decided_at      DATETIME,
    diagnosis_note              TEXT,
    repair_report               TEXT,
    tracking_number             VARCHAR(100),
    created_at                  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)               REFERENCES users(id),
    FOREIGN KEY (device_type_id)        REFERENCES device_types(id),
    FOREIGN KEY (device_brand_id)       REFERENCES device_brands(id),
    FOREIGN KEY (status_id)             REFERENCES repair_statuses(id),
    FOREIGN KEY (shipping_address_id)   REFERENCES addresses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Zdjęcia do zgłoszeń
-- ------------------------------------------------------------
CREATE TABLE repair_photos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repair_id   INT UNSIGNED NOT NULL,
    filename    VARCHAR(255) NOT NULL,
    uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repair_id) REFERENCES repairs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Historia statusów naprawy
-- ------------------------------------------------------------
CREATE TABLE repair_status_history (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repair_id   INT UNSIGNED NOT NULL,
    status_id   INT UNSIGNED NOT NULL,
    changed_by  INT UNSIGNED NOT NULL,
    note        TEXT,
    changed_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repair_id)  REFERENCES repairs(id) ON DELETE CASCADE,
    FOREIGN KEY (status_id)  REFERENCES repair_statuses(id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Płatności
-- ------------------------------------------------------------
CREATE TABLE payments (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repair_id       INT UNSIGNED NOT NULL,
    amount          DECIMAL(10,2) NOT NULL,
    method          ENUM('transfer', 'card', 'cash', 'other') NOT NULL DEFAULT 'transfer',
    status          ENUM('pending', 'paid', 'refunded') NOT NULL DEFAULT 'pending',
    transaction_id  VARCHAR(255),
    paid_at         DATETIME,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repair_id) REFERENCES repairs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Powiadomienia
-- ------------------------------------------------------------
CREATE TABLE notifications (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    repair_id   INT UNSIGNED,
    type        VARCHAR(50) NOT NULL,
    message     TEXT NOT NULL,
    is_read     TINYINT(1) NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (repair_id) REFERENCES repairs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Dane podstawowe
-- ============================================================

INSERT INTO repair_statuses (code, label, sort_order, color) VALUES
('new',                     'Nowe zgłoszenie',                  1,  '#3B82F6'),
('initial_quote_sent',      'Wstępna wycena wysłana',           2,  '#8B5CF6'),
('initial_quote_accepted',  'Wstępna wycena zaakceptowana',     3,  '#10B981'),
('initial_quote_rejected',  'Wstępna wycena odrzucona',         4,  '#EF4444'),
('shipping_instructions',   'Instrukcja wysyłki wysłana',       5,  '#F59E0B'),
('parcel_received',         'Paczka odebrana',                  6,  '#06B6D4'),
('diagnosis',               'Diagnostyka',                      7,  '#6366F1'),
('final_quote_sent',        'Finalna wycena wysłana',           8,  '#8B5CF6'),
('final_quote_accepted',    'Finalna wycena zaakceptowana',     9,  '#10B981'),
('final_quote_rejected',    'Finalna wycena odrzucona',         10, '#EF4444'),
('in_repair',               'W naprawie',                       11, '#F97316'),
('awaiting_payment',        'Oczekuje na płatność',             12, '#EAB308'),
('paid',                    'Opłacone',                         13, '#22C55E'),
('shipped_to_client',       'Wysłano do klienta',               14, '#14B8A6'),
('completed',               'Zakończone',                       15, '#6B7280');

INSERT INTO device_types (name) VALUES
('Lampa LED'),
('Lampa T5/T8'),
('Falownik'),
('Sterownik'),
('Pompa'),
('Skimmer'),
('Grzałka'),
('Chiller'),
('Dozownik'),
('Inne');

INSERT INTO device_brands (name) VALUES
('AI (Aqua Illumination)'),
('Ecotech Marine'),
('Hydra'),
('Kessil'),
('Maxspect'),
('Neptune Systems'),
('GHL'),
('Apex'),
('Tunze'),
('Jebao'),
('Inne');

