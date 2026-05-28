-- Tabela połączeń między węzłami (wiele-do-wielu)
CREATE TABLE IF NOT EXISTS diag_edges (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NOT NULL,
    child_id  INT UNSIGNED NOT NULL,
    UNIQUE KEY unique_edge (parent_id, child_id),
    FOREIGN KEY (parent_id) REFERENCES diag_nodes(id) ON DELETE CASCADE,
    FOREIGN KEY (child_id)  REFERENCES diag_nodes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Przepisz istniejące połączenia z parent_id do nowej tabeli
INSERT IGNORE INTO diag_edges (parent_id, child_id)
SELECT parent_id, id FROM diag_nodes WHERE parent_id IS NOT NULL;
