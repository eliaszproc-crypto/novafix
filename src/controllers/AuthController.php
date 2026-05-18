<?php
class AuthController {

    public function loginForm(): void {
        if (isLoggedIn()) redirect('/panel');
        $pageTitle = 'Logowanie';
        $error = $_GET['error'] ?? '';
        ob_start();
        include VIEW_PATH . '/auth/login.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }

    public function login(): void {
        global $pdo;
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Limit prób logowania - max 10 na 15 minut z jednego IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $key = 'login_attempts_' . md5($ip);
        if (!isset($_SESSION[$key])) $_SESSION[$key] = ['count' => 0, 'time' => time()];
        if (time() - $_SESSION[$key]['time'] > 900) {
            $_SESSION[$key] = ['count' => 0, 'time' => time()];
        }
        if ($_SESSION[$key]['count'] >= 10) {
            redirect('/login?error=Zbyt wiele prób logowania. Spróbuj za 15 minut.');
        }
        $_SESSION[$key]['count']++;

        if (!$email || !$password) {
            redirect('/login?error=Wypełnij wszystkie pola');
        }

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND is_active = 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            redirect('/login?error=Nieprawidłowy email lub hasło');
        }

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['role']      = $user['role'];

        redirect($user['role'] === 'admin' ? '/admin' : '/panel');
    }

    public function registerForm(): void {
        if (isLoggedIn()) redirect('/panel');
        $pageTitle = 'Rejestracja';
        $error = $_GET['error'] ?? '';
        ob_start();
        include VIEW_PATH . '/auth/register.php';
        $content = ob_get_clean();
        include VIEW_PATH . '/layout.php';
    }

    public function register(): void {
        global $pdo;
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $password   = $_POST['password'] ?? '';
        $password2  = $_POST['password2'] ?? '';

        if (!$first_name || !$last_name || !$email || !$password) {
            redirect('/rejestracja?error=Wypełnij wszystkie wymagane pola');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirect('/rejestracja?error=Podaj prawidłowy adres email');
        }
        if (strlen($password) < 8) {
            redirect('/rejestracja?error=Hasło musi mieć minimum 8 znaków');
        }
        if ($password !== $password2) {
            redirect('/rejestracja?error=Hasła nie są zgodne');
        }

        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            redirect('/rejestracja?error=Ten adres email jest już zarejestrowany');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$email, $hash, $first_name, $last_name, $phone]);

        $id = $pdo->lastInsertId();
        $_SESSION['user_id']   = $id;
        $_SESSION['user_name'] = $first_name . ' ' . $last_name;
        $_SESSION['role']      = 'client';

        redirect('/panel');
    }

    public function logout(): void {
        session_destroy();
        redirect('/');
    }
}
