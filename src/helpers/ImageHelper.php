<?php
class ImageHelper {

    public static function resizeAndSave(string $tmp_path, string $dest_path, int $max_w = 800, int $max_h = 600): bool {
        // Jeśli GD niedostępne - zapisz bez skalowania
        if (!extension_loaded('gd')) {
            return copy($tmp_path, $dest_path);
        }

        $info = @getimagesize($tmp_path);
        if (!$info) return false;

        [$orig_w, $orig_h, $type] = $info;

        $src = match($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($tmp_path),
            IMAGETYPE_PNG  => imagecreatefrompng($tmp_path),
            IMAGETYPE_WEBP => imagecreatefromwebp($tmp_path),
            default        => false,
        };
        if (!$src) return copy($tmp_path, $dest_path);

        // Oblicz nowe wymiary zachowując proporcje (nie powiększaj)
        $ratio = min($max_w / $orig_w, $max_h / $orig_h, 1.0);
        $new_w = (int)round($orig_w * $ratio);
        $new_h = (int)round($orig_h * $ratio);

        $dst = imagecreatetruecolor($new_w, $new_h);

        // Białe tło dla PNG z przezroczystością
        if ($type === IMAGETYPE_PNG) {
            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefilledrectangle($dst, 0, 0, $new_w, $new_h, $white);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
        $result = imagejpeg($dst, $dest_path, 85);

        imagedestroy($src);
        imagedestroy($dst);

        return $result;
    }
}
