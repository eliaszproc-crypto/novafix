<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="background:#111;color:#fff;padding:20px;font-family:monospace">
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';
    echo "FILES:\n";
    print_r($_FILES);
    echo '</pre>';
}
?>
<h2>Test upload z JS</h2>
<form method="POST" enctype="multipart/form-data" id="testForm">
    <div class="upload-area">
        <input type="file" name="photos[]" multiple accept="image/*" style="display:none">
        <div class="upload-preview"></div>
        <div class="upload-label" style="border:2px dashed #444;padding:30px;cursor:pointer;text-align:center">
            Kliknij lub przeciągnij
        </div>
        <p class="upload-counter"></p>
    </div>
    <br>
    <button type="submit">Wyślij</button>
</form>
<script src="/js/upload.js"></script>
</body>
</html>
