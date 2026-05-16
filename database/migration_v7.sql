CREATE TABLE IF NOT EXISTS reviews (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repair_id  INT UNSIGNED NULL,
    user_id    INT UNSIGNED NULL,
    author     VARCHAR(100) NOT NULL,
    rating     TINYINT UNSIGNED NOT NULL DEFAULT 5,
    content    TEXT NOT NULL,
    is_fake    TINYINT(1) NOT NULL DEFAULT 0,
    is_visible TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repair_id) REFERENCES repairs(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO reviews (author, rating, content, is_fake, is_visible) VALUES
('Marek',   5, 'Lampa Hydra wpadła do akwarium. Myślałem że przepadła — Eliasz ją przywrócił do życia w tydzień. Szczery kontakt i szybka realizacja.', 1, 1),
('Anna',    5, 'Sterownik GHL przestał reagować. Naprawa ekspresowo, wiedziałem na bieżąco co się dzieje ze sprzętem. Polecam z czystym sumieniem.', 1, 1),
('Tomasz',  5, 'Dozownik Balling przestał dozować. Diagnoza i naprawa błyskawicznie. Cena uczciwa, sprzęt działa jak nowy.', 1, 1),
('Piotr',   5, 'Falownik Ecotech po zalaniu — naprawiony bez problemu. Nikt inny się nie podjął, Eliasz zrobił to profesjonalnie.', 1, 1),
('Kamila',  5, 'Lampa Kessil po zalaniu słoną wodą — odratowana! Bardzo fachowe podejście, szczegółowa diagnoza przed naprawą.', 1, 1),
('Rafał',   5, 'Sterownik Neptune Apex przestał komunikować się z modułami. Naprawa płyty głównej, wszystko wróciło do normy.', 1, 1),
('Monika',  5, 'Rollermat nie ruszał po przepięciu. Myślałem że do wymiany — okazało się że spalony moduł sterujący. Tania i szybka naprawa.', 1, 1),
('Jakub',   5, 'Skimmer przestał działać po roku — uszkodzony sterownik. Naprawa zajęła 3 dni robocze. Bardzo polecam!', 1, 1),
('Zofia',   5, 'Dolewka ATO wariowała przez miesiąc. Uszkodzony czujnik poziomu — wymieniony i skalibrowany. Działa idealnie.', 1, 1),
('Bartosz', 5, 'Maxspect Recurve nie świecił jednym kanałem. Driver LED wymieniony, lampa jak nowa. Szybko, uczciwie, profesjonalnie.', 1, 1);
