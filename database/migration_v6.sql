ALTER TABLE repairs
    ADD COLUMN payment_method ENUM('transfer','blik','cash','other') NULL AFTER return_tracking,
    ADD COLUMN payment_amount DECIMAL(10,2) NULL AFTER payment_method;

-- Numer konta bankowego w configu - nie w bazie
