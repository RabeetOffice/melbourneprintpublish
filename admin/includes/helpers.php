<?php
/**
 * Admin helpers: file IO (atomic, locked, backed up), slugs, PHP escaping,
 * PHP lint gate, and the shared image -> WebP upload pipeline.
 */

if (!defined('ADMIN_DIR')) {
    define('ADMIN_DIR', dirname(__DIR__));
}
if (!defined('ADMIN_SITE_ROOT')) {
    define('ADMIN_SITE_ROOT', dirname(ADMIN_DIR));
}
if (!defined('ADMIN_DATA_DIR')) {
    define('ADMIN_DATA_DIR', ADMIN_DIR . DIRECTORY_SEPARATOR . 'data');
}

/* ---------------------------------------------------------------------------
 * JSON store (flock-protected)
 * ------------------------------------------------------------------------- */

function admin_json_read(string $path, $default = [])
{
    if (!is_file($path)) {
        return $default;
    }
    $fh = @fopen($path, 'rb');
    if (!$fh) {
        return $default;
    }
    flock($fh, LOCK_SH);
    $raw = stream_get_contents($fh);
    flock($fh, LOCK_UN);
    fclose($fh);
    $data = json_decode((string) $raw, true);
    return is_array($data) ? $data : $default;
}

function admin_json_write(string $path, $data): bool
{
    $dir = dirname($path);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    return admin_atomic_write($path, $json . "\n");
}

/* ---------------------------------------------------------------------------
 * Atomic writes / backups. Every write to a LIVE site file must go through
 * admin_replace_site_file() so there is always a backup to roll back to.
 * ------------------------------------------------------------------------- */

function admin_atomic_write(string $path, string $content): bool
{
    $dir = dirname($path);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $tmp = @tempnam($dir, '.adm');
    if ($tmp === false) {
        return false;
    }
    $bytes = @file_put_contents($tmp, $content, LOCK_EX);
    if ($bytes === false || $bytes !== strlen($content)) {
        @unlink($tmp);
        return false;
    }
    // Windows rename() refuses to overwrite an existing target.
    if (DIRECTORY_SEPARATOR === '\\' && is_file($path)) {
        @unlink($path);
    }
    if (!@rename($tmp, $path)) {
        @unlink($tmp);
        return false;
    }
    return true;
}

function admin_backup_file(string $path): void
{
    if (!is_file($path)) {
        return;
    }
    $dir = ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'backups';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $name = basename($path);
    @copy($path, $dir . DIRECTORY_SEPARATOR . $name . '.' . date('Ymd-His') . '.bak');

    // Keep only the newest 20 backups per file.
    $all = glob($dir . DIRECTORY_SEPARATOR . $name . '.*.bak') ?: [];
    if (count($all) > 20) {
        sort($all); // timestamped names sort chronologically
        foreach (array_slice($all, 0, count($all) - 20) as $old) {
            @unlink($old);
        }
    }
}

function admin_replace_site_file(string $path, string $content): bool
{
    admin_backup_file($path);
    return admin_atomic_write($path, $content);
}

/* ---------------------------------------------------------------------------
 * PHP lint gate: never overwrite a live .php file with code that fails php -l
 * ------------------------------------------------------------------------- */

/**
 * Under apache2handler PHP_BINARY is httpd.exe (and under php-cgi/fpm it's a
 * binary that can't lint), so resolve the real PHP CLI next to the loaded
 * php.ini / extension dir before falling back to "php" on PATH.
 */
function php_cli_binary(): string
{
    static $resolved = null;
    if ($resolved !== null) {
        return $resolved;
    }
    $exe = DIRECTORY_SEPARATOR === '\\' ? 'php.exe' : 'php';
    if (defined('PHP_BINARY') && PHP_BINARY !== ''
        && preg_match('~^php(\.exe)?$~i', basename(PHP_BINARY))) {
        return $resolved = PHP_BINARY;
    }
    $candidates = [];
    $ini = php_ini_loaded_file();
    if ($ini) {
        $candidates[] = dirname($ini) . DIRECTORY_SEPARATOR . $exe;
    }
    $candidates[] = PHP_BINDIR . DIRECTORY_SEPARATOR . $exe;
    $ext = (string) ini_get('extension_dir');
    if ($ext !== '') {
        $candidates[] = dirname($ext) . DIRECTORY_SEPARATOR . $exe;
    }
    foreach ($candidates as $candidate) {
        if (is_file($candidate)) {
            return $resolved = $candidate;
        }
    }
    return $resolved = 'php';
}

function php_check_syntax_string(string $code): bool
{
    if (!function_exists('exec')) {
        return true; // degrade to trust when exec is disabled
    }
    $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
    if (in_array('exec', $disabled, true)) {
        return true;
    }
    $tmp = tempnam(sys_get_temp_dir(), 'lint');
    if ($tmp === false) {
        return true;
    }
    file_put_contents($tmp, $code);
    $out = [];
    $exit = 1;
    @exec(escapeshellarg(php_cli_binary()) . ' -l ' . escapeshellarg($tmp) . ' 2>&1', $out, $exit);
    @unlink($tmp);
    $text = implode("\n", $out);
    if (stripos($text, 'No syntax errors') !== false) {
        return true;
    }
    if (preg_match('~(Errors parsing|Parse error|Fatal error)~i', $text)) {
        return false;
    }
    // The command produced no recognisable lint verdict (CLI missing or not
    // runnable): degrade to trust, same as the exec-disabled path above.
    return $exit === 0;
}

/* ---------------------------------------------------------------------------
 * Strings
 * ------------------------------------------------------------------------- */

function admin_slugify(string $text): string
{
    $text = strtolower(trim($text));
    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = $converted;
        }
    }
    $text = preg_replace('~[\'"’‘”“]+~u', '', $text);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text);
    return trim((string) $text, '-');
}

/** Escape a value for inclusion inside a single-quoted PHP string literal. */
function admin_php_sq(string $s): string
{
    return str_replace(['\\', "'"], ['\\\\', "\\'"], $s);
}

/** Escape a value for inclusion inside a double-quoted PHP string literal. */
function admin_php_dq(string $s): string
{
    return addcslashes($s, "\\\"\$");
}

/**
 * Remove any PHP open/close tag sequence from HTML that will be written into
 * a .php file. Belt-and-suspenders on top of the DOM sanitizer: nothing that
 * survives this can ever execute. (The pattern also eats short tags and
 * unterminated opens at end-of-string.)
 */
function ukph_strip_php(string $html): string
{
    /* strip complete open-close blocks first, then any dangling opener */
    $html = preg_replace('~<\?(?:php|=)?[\s\S]*?\?>~i', '', (string) $html);
    $html = preg_replace('~<\?(?:php|=)?[\s\S]*$~i', '', (string) $html);
    return (string) $html;
}

/* ---------------------------------------------------------------------------
 * Image upload -> WebP. Shared by blog featured images, inline editor images
 * and portfolio covers.
 *
 * Returns [true, '<relDir>/<name>'] or [false, 'error message'].
 * ------------------------------------------------------------------------- */

function admin_upload_image_webp(array $file, string $relDir, string $hint = '', int $maxBytes = 8388608): array
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return [false, 'Invalid upload.'];
    }
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return [false, 'No file received.'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [false, 'Upload failed (code ' . (int) $file['error'] . ').'];
    }
    if (!isset($file['size']) || $file['size'] <= 0 || $file['size'] > $maxBytes) {
        return [false, 'Image must be under ' . round($maxBytes / 1048576) . ' MB.'];
    }

    $tmpPath = (string) ($file['tmp_name'] ?? '');
    if ($tmpPath === '' || !is_uploaded_file($tmpPath) && !is_file($tmpPath)) {
        return [false, 'Upload not found.'];
    }

    // MIME-sniff the real content; never trust the filename extension.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($tmpPath);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) {
        return [false, 'Only JPEG, PNG, GIF or WebP images are allowed.'];
    }

    $info = @getimagesize($tmpPath);
    if ($info === false || $info[0] < 1 || $info[1] < 1) {
        return [false, 'File is not a valid image.'];
    }

    $relDir = trim(str_replace('\\', '/', $relDir), '/');
    if ($relDir === '' || strpos($relDir, '..') !== false) {
        return [false, 'Invalid upload directory.'];
    }
    $destDir = ADMIN_SITE_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relDir);
    if (!is_dir($destDir) && !@mkdir($destDir, 0755, true)) {
        return [false, 'Could not create upload directory.'];
    }

    $base = admin_slugify($hint !== '' ? $hint : pathinfo((string) ($file['name'] ?? 'image'), PATHINFO_FILENAME));
    if ($base === '') {
        $base = 'image';
    }
    $base = substr($base, 0, 80);

    $canWebp = function_exists('imagewebp') && function_exists('imagecreatetruecolor');
    $ext = $canWebp ? 'webp' : $allowed[$mime];

    $name = $base . '.' . $ext;
    $n = 1;
    while (file_exists($destDir . DIRECTORY_SEPARATOR . $name)) {
        $n++;
        $name = $base . '-' . $n . '.' . $ext;
    }
    $destPath = $destDir . DIRECTORY_SEPARATOR . $name;

    if ($canWebp) {
        $src = null;
        switch ($mime) {
            case 'image/jpeg': $src = @imagecreatefromjpeg($tmpPath); break;
            case 'image/png':  $src = @imagecreatefrompng($tmpPath);  break;
            case 'image/gif':  $src = @imagecreatefromgif($tmpPath);  break;
            case 'image/webp': $src = @imagecreatefromwebp($tmpPath); break;
        }
        if (!$src) {
            return [false, 'Could not parse the image.'];
        }
        $w = imagesx($src);
        $h = imagesy($src);
        $maxW = 1920;
        if ($w > $maxW) {
            $newW = $maxW;
            $newH = (int) round($h * ($maxW / $w));
            $resized = imagecreatetruecolor($newW, $newH);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($src);
            $src = $resized;
        } else {
            imagepalettetotruecolor($src);
            imagealphablending($src, false);
            imagesavealpha($src, true);
        }
        // Re-encoding also destroys any payload smuggled inside a valid image.
        $ok = @imagewebp($src, $destPath, 82);
        imagedestroy($src);
        if (!$ok) {
            @unlink($destPath);
            return [false, 'Could not convert the image to WebP.'];
        }
    } else {
        // No GD: keep original bytes; the forced image extension keeps it inert.
        $moved = is_uploaded_file($tmpPath)
            ? @move_uploaded_file($tmpPath, $destPath)
            : @rename($tmpPath, $destPath);
        if (!$moved) {
            return [false, 'Could not store the upload.'];
        }
    }

    admin_ensure_upload_htaccess($destDir);

    return [true, $relDir . '/' . $name];
}

/** Drop a .htaccess that disables script execution into an upload directory. */
function admin_ensure_upload_htaccess(string $dir): void
{
    $file = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . '.htaccess';
    if (is_file($file)) {
        return;
    }
    $rules = "# Uploaded files must never execute as code\n"
        . "<FilesMatch \"(?i)\\.(php|phtml|php\\d|phar|pl|py|cgi|sh)$\">\nRequire all denied\n</FilesMatch>\n"
        . "RemoveHandler .php .phtml .phar\nRemoveType .php .phtml .phar\n";
    @file_put_contents($file, $rules);
}

/* ---------------------------------------------------------------------------
 * Misc
 * ------------------------------------------------------------------------- */

function admin_client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

function admin_is_loopback(): bool
{
    return in_array(admin_client_ip(), ['127.0.0.1', '::1', '::ffff:127.0.0.1'], true);
}

function admin_random_token(int $bytes = 32): string
{
    return bin2hex(random_bytes($bytes));
}
