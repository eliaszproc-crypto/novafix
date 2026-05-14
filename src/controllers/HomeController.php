<?php
class HomeController {
    public function index(): void {
        $pageTitle = 'Strona główna';
        ob_start();
        include VIEW_PATH . '/home/index.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }

    public function services(): void {
        $pageTitle = 'Usługi';
        $content = '<section class="section"><div class="container"><h1 style="color:white;font-family:var(--font-head)">Usługi — wkrótce</h1></div></section>';
        include VIEW_PATH . '/layout.php';
    }

    public function pricing(): void {
        $pageTitle = 'Cennik';
        $content = '<section class="section"><div class="container"><h1 style="color:white;font-family:var(--font-head)">Cennik — wkrótce</h1></div></section>';
        include VIEW_PATH . '/layout.php';
    }

    public function contact(): void {
        $pageTitle = 'Kontakt';
        $content = '<section class="section"><div class="container"><h1 style="color:white;font-family:var(--font-head)">Kontakt — wkrótce</h1></div></section>';
        include VIEW_PATH . '/layout.php';
    }

    public function checkStatus(string $rma = ''): void {
        $pageTitle = 'Status naprawy';
        $content = '<section class="section"><div class="container"><h1 style="color:white;font-family:var(--font-head)">Status: ' . htmlspecialchars($rma) . '</h1></div></section>';
        include VIEW_PATH . '/layout.php';
    }
}
