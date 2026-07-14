<?php
/**
 * Minimal RFC 6238 TOTP (HMAC-SHA1, 30s period, 6 digits) + base32 helpers.
 * Verified against the RFC test vectors in the admin test suite.
 */

function totp_base32_encode(string $bytes): string
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $out = '';
    $bits = 0;
    $value = 0;
    for ($i = 0, $len = strlen($bytes); $i < $len; $i++) {
        $value = ($value << 8) | ord($bytes[$i]);
        $bits += 8;
        while ($bits >= 5) {
            $out .= $alphabet[($value >> ($bits - 5)) & 31];
            $bits -= 5;
        }
    }
    if ($bits > 0) {
        $out .= $alphabet[($value << (5 - $bits)) & 31];
    }
    return $out;
}

function totp_base32_decode(string $b32)
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $b32 = strtoupper(preg_replace('~[^A-Za-z2-7]~', '', $b32));
    if ($b32 === '') {
        return false;
    }
    $out = '';
    $bits = 0;
    $value = 0;
    for ($i = 0, $len = strlen($b32); $i < $len; $i++) {
        $pos = strpos($alphabet, $b32[$i]);
        if ($pos === false) {
            return false;
        }
        $value = ($value << 5) | $pos;
        $bits += 5;
        if ($bits >= 8) {
            $out .= chr(($value >> ($bits - 8)) & 255);
            $bits -= 8;
        }
    }
    return $out;
}

function totp_generate_secret(): string
{
    return totp_base32_encode(random_bytes(20));
}

function totp_code(string $secretB32, ?int $time = null, int $period = 30, int $digits = 6): string
{
    $key = totp_base32_decode($secretB32);
    if ($key === false) {
        return '';
    }
    $counter = (int) floor(($time ?? time()) / $period);
    $binCounter = pack('N2', ($counter >> 32) & 0xFFFFFFFF, $counter & 0xFFFFFFFF);
    $hash = hash_hmac('sha1', $binCounter, $key, true);
    $offset = ord($hash[19]) & 0x0F;
    $slice = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;
    return str_pad((string) ($slice % (10 ** $digits)), $digits, '0', STR_PAD_LEFT);
}

/** Verify with a +/-1 window (90 seconds of tolerance). */
function totp_verify(string $secretB32, string $code, ?int $time = null): bool
{
    $code = preg_replace('~\D~', '', $code);
    if (strlen($code) !== 6) {
        return false;
    }
    $time = $time ?? time();
    foreach ([-1, 0, 1] as $window) {
        if (hash_equals(totp_code($secretB32, $time + $window * 30), $code)) {
            return true;
        }
    }
    return false;
}

function totp_otpauth_uri(string $secretB32, string $account, string $issuer): string
{
    return 'otpauth://totp/' . rawurlencode($issuer . ':' . $account)
        . '?secret=' . rawurlencode($secretB32)
        . '&issuer=' . rawurlencode($issuer)
        . '&algorithm=SHA1&digits=6&period=30';
}

/** 8 single-use backup codes; plaintext returned once, bcrypt hashes stored. */
function totp_generate_backup_codes(): array
{
    $plain = [];
    $hashes = [];
    for ($i = 0; $i < 8; $i++) {
        $code = strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        $code = substr($code, 0, 5) . '-' . substr($code, 5);
        $plain[] = $code;
        $hashes[] = password_hash($code, PASSWORD_BCRYPT);
    }
    return [$plain, $hashes];
}

function totp_consume_backup_code(array &$hashes, string $input): bool
{
    $input = strtoupper(trim($input));
    foreach ($hashes as $i => $hash) {
        if (password_verify($input, $hash)) {
            array_splice($hashes, $i, 1); // single use
            return true;
        }
    }
    return false;
}
