<?php
class ImageHelper {

    /**
     * Skaluje obraz do max 800x600 i zapisuje jako JPEG
     * Zwraca nazwę pliku lub false przy błędzie
     */
    public static function resizeAndSave(string $tmp_path, string $dest_path, int $max_w = 800, int $max_h = 600): bool {
        $info = @getimagesize($tmp_path);
        if (!$info) return false;

        [$orig_w, $orig_h, $type] = $info;

        // Wczytaj obraz
        $src = match($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($tmp_path),
            IMAGETYPE_PNG  => imagecreatefrompng($tmp_path),
            IMAGETYPE_WEBP => imagecreatefromwebp($tmp_path),
            default        => false,
        };
        if (!$src) return false;

        // Oblicz nowe wymiary zachowując proporcje
        $ratio = min($max_w / $orig_w, $max_h / $orig_h, 1.0); // max 1.0 - nie powiększaj
        $new_w = (int)round($orig_w * $ratio);
        $new_h = (int)round($orig_h * $ratio);

        // Stwórz nowy obraz
        $dst = imagecreatetruecolor($new_w, $new_h);

        // Zachowaj przezroczystość dla PNG
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $new_w, $new_h, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);

        // Zapisz jako JPEG (jakość 85)
        $result = imagejpeg($dst, $dest_path, 85);

        imagedestroy($src);
        imagedestroy($dst);

        return $result;
    }
}
