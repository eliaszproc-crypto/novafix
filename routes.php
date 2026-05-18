<?php
// ---- Strona publiczna ----
$router->get('',                                    ['HomeController',   'index']);
$router->get('uslugi',                              ['HomeController',   'services']);
$router->get('cennik',                              ['HomeController',   'pricing']);
$router->get('kontakt',                             ['HomeController',   'contact']);
$router->get('status',                              ['HomeController',   'statusPage']);
$router->get('status/{rma}',                        ['HomeController',   'checkStatus']);

// ---- API ----
$router->get('api/stats',  ['ApiController', 'stats']);

// ---- Autoryzacja ----
$router->get('login',                               ['AuthController',   'loginForm']);
$router->post('login',                              ['AuthController',   'login']);
$router->get('rejestracja',                         ['AuthController',   'registerForm']);
$router->post('rejestracja',                        ['AuthController',   'register']);
$router->get('logout',                              ['AuthController',   'logout']);

// ---- Panel klienta ----
$router->get('panel',                               ['ClientController', 'dashboard']);
$router->get('panel/zgloszenia',                    ['ClientController', 'repairs']);
$router->get('panel/nowe-zgloszenie',               ['ClientController', 'newRepairForm']);
$router->post('panel/nowe-zgloszenie',              ['ClientController', 'newRepairSubmit']);
$router->get('panel/naprawa/{id}',                  ['ClientController', 'repairDetail']);
$router->post('panel/naprawa/{id}/usun',            ['ClientController', 'deleteRepair']);
$router->post('panel/naprawa/{id}/akceptuj-wycene', ['ClientController', 'acceptInitialQuote']);
$router->post('panel/naprawa/{id}/odrzuc-wycene',   ['ClientController', 'rejectInitialQuote']);
$router->post('panel/naprawa/{id}/akceptuj-koszt',  ['ClientController', 'acceptFinalQuote']);
$router->post('panel/naprawa/{id}/odrzuc-koszt',    ['ClientController', 'rejectFinalQuote']);
$router->post('panel/naprawa/{id}/usun-zdjecie/{photo_id}',   ['ClientController', 'deletePhoto']);
$router->post('panel/naprawa/{id}/nadanie-paczki',  ['ClientController', 'submitTracking']);
$router->post('panel/naprawa/{id}/adres-zwrotny',   ['ClientController', 'updateReturnAddress']);


// ---- Diagnostyka klienta ----
$router->get('panel/diagnostyka',                   ['ClientController',  'diagnostics']);

$router->post('panel/naprawa/{id}/wiadomosc',      ['ClientController',  'sendMessage']);
$router->get('panel/naprawa/{id}/wiadomosci',       ['ClientController',  'getMessages']);
$router->post('admin/naprawa/{id}/wiadomosc',       ['AdminController',   'sendMessage']);
$router->get('admin/naprawa/{id}/wiadomosci',       ['AdminController',   'getMessages']);
$router->post('admin/naprawa/{id}/przeczytane',     ['AdminController',   'markRead']);
$router->post('panel/naprawa/{id}/opinia',          ['ClientController',  'submitReview']);
$router->get('admin/opinie',                        ['AdminController',   'reviews']);
$router->post('admin/opinia/{id}/widocznosc',       ['AdminController',   'reviewToggle']);
$router->post('admin/opinia/{id}/usun',             ['AdminController',   'reviewDelete']);

// ---- Panel admina ----
$router->get('admin',                               ['AdminController',  'dashboard']);
$router->get('admin/zgloszenia',                    ['AdminController',  'repairs']);
$router->get('admin/naprawa/{id}',                  ['AdminController',  'repairDetail']);
$router->post('admin/naprawa/{id}/status',          ['AdminController',  'updateStatus']);
$router->post('admin/naprawa/{id}/wycena',          ['AdminController',  'sendQuote']);
$router->post('admin/naprawa/{id}/ustaw-platnosc', ['AdminController',  'setPaymentMethod']);
$router->post('admin/naprawa/{id}/oplacone',        ['AdminController',  'markPaid']);
$router->post('admin/naprawa/{id}/zwrot',           ['AdminController',  'markReturning']);
$router->post('admin/naprawa/{id}/usun',            ['AdminController',  'deleteRepair']);
$router->get('admin/statystyki',                   ['AdminController',   'statistics']);
$router->get('admin/kalendarz',                     ['AdminController',  'calendar']);
$router->get('admin/diagnostyka',                   ['AdminController',   'diagnostics']);
$router->post('admin/diagnostyka/dodaj',            ['AdminController',   'diagAdd']);
$router->post('admin/diagnostyka/edytuj/{id}',      ['AdminController',   'diagEdit']);
$router->post('admin/diagnostyka/usun/{id}',        ['AdminController',   'diagDelete']);
$router->get('admin/cennik',                        ['AdminController',   'pricing']);
$router->post('admin/cennik/dodaj',                 ['AdminController',   'pricingAdd']);
$router->post('admin/cennik/edytuj/{id}',           ['AdminController',   'pricingEdit']);
$router->post('admin/cennik/usun/{id}',             ['AdminController',   'pricingDelete']);
$router->get('admin/platnosci',                     ['AdminController',  'payments']);
$router->get('admin/uzytkownicy',                   ['AdminController',  'users']);
$router->post('admin/uzytkownik/{id}/usun',         ['AdminController',  'deleteUser']);
$router->post('admin/platnosc/{id}/usun',           ['AdminController',  'deletePayment']);
