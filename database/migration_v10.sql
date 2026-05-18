CREATE TABLE IF NOT EXISTS repair_messages (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repair_id  INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    message    TEXT NOT NULL,
    is_read    TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repair_id) REFERENCES repairs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    INDEX idx_repair (repair_id),
    INDEX idx_unread (is_read, repair_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
