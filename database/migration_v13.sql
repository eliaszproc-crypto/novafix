ALTER TABLE diag_nodes
    ADD COLUMN question_en TEXT NULL AFTER question,
    ADD COLUMN answer_en   VARCHAR(255) NULL AFTER answer,
    ADD COLUMN result_en   TEXT NULL AFTER result;
