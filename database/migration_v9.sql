CREATE TABLE IF NOT EXISTS page_visits (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    path       VARCHAR(255) NOT NULL,
    ip_hash    VARCHAR(64) NOT NULL,
    user_agent VARCHAR(255) NULL,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_path (path),
    INDEX idx_date (visited_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
