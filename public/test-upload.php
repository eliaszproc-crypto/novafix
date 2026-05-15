<?php
// Tymczasowy test uploadu - usuń po testach
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';
    echo "FILES:\n";
    print_r($_FILES);
    echo "\nPHP upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
    echo "PHP post_max_size: " . ini_get('post_max_size') . "\n";
    echo "PHP file_uploads: " . ini_get('file_uploads') . "\n";
    echo '</pre>';
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="background:#111;color:#fff;padding:20px;font-family:monospace">
<h2>Test uploadu</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="photos[]" multiple accept="image/*">
    <button type="submit">Wyślij</button>
</form>
</body>
</html>
