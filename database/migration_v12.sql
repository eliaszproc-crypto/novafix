-- Pozycje węzłów na canvasie
ALTER TABLE diag_nodes 
    ADD COLUMN pos_x INT NOT NULL DEFAULT 0,
    ADD COLUMN pos_y INT NOT NULL DEFAULT 0;
