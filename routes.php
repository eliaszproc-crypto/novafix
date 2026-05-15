<?php
// ---- Strona publiczna ----
$router->get('',                                    ['HomeController',   'index']);
$router->get('uslugi',                              ['HomeController',   'services']);
$router->get('cennik',                              ['HomeController',   'pricing']);
$router->get('kontakt',                             ['HomeController',   'contact']);
$router->get('status',                              ['HomeController',   'statusPage']);
$router->get('status/{rma}',                        ['HomeController',   'checkStatus']);

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
$router->post('panel/naprawa/{id}/akceptuj-wycene', ['ClientController', 'acceptInitialQuote']);
$router->post('panel/naprawa/{id}/odrzuc-wycene',   ['ClientController', 'rejectInitialQuote']);
$router->post('panel/naprawa/{id}/akceptuj-koszt',  ['ClientController', 'acceptFinalQuote']);
$router->post('panel/naprawa/{id}/odrzuc-koszt',    ['ClientController', 'rejectFinalQuote']);
$router->post('panel/naprawa/{id}/usun-zdjecie/{photo_id}',   ['ClientController', 'deletePhoto']);
$router->post('panel/naprawa/{id}/adres-zwrotny',   ['ClientController', 'updateReturnAddress']);

// ---- Panel admina ----
$router->get('admin',                               ['AdminController',  'dashboard']);
$router->get('admin/zgloszenia',                    ['AdminController',  'repairs']);
$router->get('admin/naprawa/{id}',                  ['AdminController',  'repairDetail']);
$router->post('admin/naprawa/{id}/status',          ['AdminController',  'updateStatus']);
$router->post('admin/naprawa/{id}/wycena',          ['AdminController',  'sendQuote']);
$router->post('admin/naprawa/{id}/oplacone',        ['AdminController',  'markPaid']);
$router->post('admin/naprawa/{id}/zwrot',           ['AdminController',  'markReturning']);
$router->post('admin/naprawa/{id}/usun',            ['AdminController',  'deleteRepair']);
$router->get('admin/kalendarz',                     ['AdminController',  'calendar']);
$router->get('admin/platnosci',                     ['AdminController',  'payments']);
$router->get('admin/uzytkownicy',                   ['AdminController',  'users']);
$router->post('admin/uzytkownik/{id}/usun',         ['AdminController',  'deleteUser']);
$router->post('admin/platnosc/{id}/usun',           ['AdminController',  'deletePayment']);
