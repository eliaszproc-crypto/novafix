-- Zdjęcia od admina do klienta
CREATE TABLE IF NOT EXISTS admin_photos (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repair_id  INT UNSIGNED NOT NULL,
    filename   VARCHAR(255) NOT NULL,
    caption    VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repair_id) REFERENCES repairs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Koszt wysyłki w zleceniu
ALTER TABLE repairs ADD COLUMN shipping_cost DECIMAL(10,2) NOT NULL DEFAULT 25.00 AFTER final_quote_amount;
