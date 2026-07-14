<?php
/**
 * Site settings layer — the single source for everything the admin makes
 * "dynamic": contact details, socials, analytics IDs, custom scripts, SMTP,
 * reCAPTCHA, chat widget, disclaimer and the Organization schema toggle.
 *
 * Values live in admin/data/site-settings.json (web-blocked). Every default
 * below is the site's CURRENT hard-coded value, so with no overrides present
 * the public site renders byte-for-byte identically — nothing changes until
 * the owner edits a field in the admin.
 *
 * This file has NO dependency on config.php constants, so config.php can
 * require it early and define the SMTP and RECAPTCHA constants from these values.
 */

if (!function_exists('mpp_settings_defaults')) {

    function mpp_settings_file(): string
    {
        return __DIR__ . '/../admin/data/site-settings.json';
    }

    /** CURRENT live values — the safe baseline. */
    function mpp_settings_defaults(): array
    {
        return [
            'contact' => [
                'phone'           => '(03) 4138 8706',
                'phone_href'      => '(03) 4138 8706',
                'email'           => 'info@melbourneprintpublish.com.au',
                'abn_company'     => 'Keystone Publishing Group Pty Ltd',
                'abn'             => '21 697 806 447',
                'address'         => '470 St Kilda Rd, Melbourne VIC 3004, Australia',
                'address_map_url' => 'https://share.google/CUt8YHg5DZuZngGWA',
                'tagline'         => 'Melbourne Print & Publish is a trusted provider of high-quality printing and publishing services across Australia.',
            ],
            'social' => [
                'facebook'  => 'https://www.facebook.com/melbourneprintandpublish/',
                'instagram' => 'https://www.instagram.com/melbourne_print_and_publish/',
                'linkedin'  => 'https://www.linkedin.com/company/melbourneprintandpublish',
                'pinterest' => 'https://www.pinterest.com/melbournePandP/',
                'twitter'   => 'https://x.com/melbournePandP',
                'youtube'   => 'https://www.youtube.com/@melbourneprintandpublish',
            ],
            'analytics' => [
                'gtm_id'                   => 'GTM-PFZRKR97',
                'ga_id'                    => 'G-V7PVJBXYL9',
                'google_site_verification' => 'fmX4SQHeIHlfWDv3FiuLtWRDGStxBIfdRWPTQGQ8Vxs',
            ],
            // Raw custom code the owner can inject. Empty = nothing rendered.
            'scripts' => [
                'head'      => '',
                'body_open' => '',
                'footer'    => '',
            ],
            'smtp' => [
                // Empty string means "use the config.php default" — keeps the
                // password in one place until the owner overrides it.
                'host'       => '',
                'port'       => '',
                'encryption' => '',
                'user'       => '',
                'pass'       => '',
                'from_email' => '',
                'from_name'  => '',
            ],
            'recaptcha' => [
                'site_key'            => '',
                'secret_key'          => '',
                'enable_public_forms' => false,
                'min_score'           => 0.5,
            ],
            'db' => [
                // Empty string means "use the config.php default" — keeps the
                // password in config.secret.php until the owner overrides it.
                'host' => '',
                'name' => '',
                'user' => '',
                'pass' => '',
            ],
            'chat' => [
                'tawk_src' => 'https://embed.tawk.to/698e24f485e35c1c3911db06/1jh9k0nl3',
            ],
            'disclaimer' => 'Melbourne Print Publish is owned and operated by Keystone Publishing Group Pty Ltd (ABN: 21 697 806 447). In Australia, .com.au domains are the only ABN-backed extension. Always verify a .com.au domain before engaging. No other company or website holds affiliation with this brand.',
            'schema' => [
                // OFF by default so the migration changes zero rendered bytes.
                // The owner can switch on a site-wide Organization block later.
                'organization_enabled' => false,
                'organization_json'    => '',
            ],
        ];
    }

    /** Deep-merge overrides over defaults (associative arrays only). */
    function mpp_settings_merge(array $base, array $over): array
    {
        foreach ($over as $k => $v) {
            if (is_array($v) && isset($base[$k]) && is_array($base[$k])
                && array_keys($v) !== range(0, count($v) - 1)) {
                $base[$k] = mpp_settings_merge($base[$k], $v);
            } else {
                $base[$k] = $v;
            }
        }
        return $base;
    }

    function mpp_settings(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $defaults = mpp_settings_defaults();
        $file = mpp_settings_file();
        if (is_file($file)) {
            $raw = json_decode((string) @file_get_contents($file), true);
            if (is_array($raw)) {
                $defaults = mpp_settings_merge($defaults, $raw);
            }
        }
        return $cache = $defaults;
    }

    /** Fetch one setting by dot path, e.g. mpp_setting('contact.phone'). */
    function mpp_setting(string $path, $default = '')
    {
        $node = mpp_settings();
        foreach (explode('.', $path) as $seg) {
            if (is_array($node) && array_key_exists($seg, $node)) {
                $node = $node[$seg];
            } else {
                return $default;
            }
        }
        return $node;
    }

    /** Escaped convenience for templates. */
    function mpp_e(string $path, $default = ''): string
    {
        return htmlspecialchars((string) mpp_setting($path, $default), ENT_QUOTES, 'UTF-8');
    }

    /** Raw overrides only (what's actually in the JSON file), not merged. */
    function mpp_settings_overrides(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $cache = [];
        if (is_file(mpp_settings_file())) {
            $raw = json_decode((string) @file_get_contents(mpp_settings_file()), true);
            if (is_array($raw)) {
                $cache = $raw;
            }
        }
        return $cache;
    }

    /** True only when the owner has set this field to a non-empty STRING. */
    function mpp_is_overridden(string $path): bool
    {
        $node = mpp_settings_overrides();
        foreach (explode('.', $path) as $seg) {
            if (is_array($node) && array_key_exists($seg, $node)) {
                $node = $node[$seg];
            } else {
                return false;
            }
        }
        // Only a non-empty string counts as an override for mpp_val (a stray
        // bool/0/[] from a hand-edited JSON must not replace the seeded bytes).
        return is_string($node) && $node !== '';
    }

    /**
     * Migration-safe field echo. Until the owner overrides the field in the
     * admin, this returns the page's EXACT original bytes ($original), so the
     * rendered site is byte-for-byte unchanged. After an override it returns
     * the escaped admin value. Use in templates:
     *   <?= mpp_val('contact.phone', '(03) 4138 8706') ?>
     */
    function mpp_val(string $path, string $original): string
    {
        if (mpp_is_overridden($path)) {
            return htmlspecialchars((string) mpp_setting($path), ENT_QUOTES, 'UTF-8');
        }
        return $original;
    }

    /**
     * Like mpp_val but for a URL that lands in an href/src. Defends against a
     * stored javascript:/data:/vbscript: URI (which htmlspecialchars does NOT
     * neutralise): a dangerous or unrecognised scheme falls back to the safe
     * original bytes. Byte-identical until overridden with a safe URL.
     */
    function mpp_val_url(string $path, string $original): string
    {
        if (!mpp_is_overridden($path)) {
            return $original;
        }
        $v = trim((string) mpp_setting($path));
        if (preg_match('~^\s*(?:javascript|data|vbscript)\s*:~i', $v)) {
            return $original; // dangerous scheme — keep the safe original
        }
        // Allow http(s), protocol-relative, mailto/tel, root/relative, anchors.
        if (!preg_match('~^(?:https?:)?//~i', $v)
            && !preg_match('~^(?:mailto:|tel:|/|\#)~i', $v)
            && preg_match('~^[a-z][a-z0-9+.\-]*:~i', $v)) {
            return $original; // some other scheme (chrome:, file:, ...) — reject
        }
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Owner-injected custom code slots: 'head', 'body_open', 'footer'.
     * Empty by default (no output). Rendered raw; only super-admins can set them.
     */
    function mpp_custom_script(string $slot): void
    {
        $code = (string) mpp_setting('scripts.' . $slot, '');
        if (trim($code) !== '') {
            echo "\n" . $code . "\n";
        }
    }

    /** reCAPTCHA v3 on public forms: on only when enabled AND a site key is set. */
    function mpp_recaptcha_public_enabled(): bool
    {
        return (bool) mpp_setting('recaptcha.enable_public_forms', false)
            && defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== '';
    }

    /** Emits the token field + loader for a public form. Nothing when disabled. */
    function mpp_recaptcha_form_field(): void
    {
        if (!mpp_recaptcha_public_enabled()) {
            return;
        }
        $k = htmlspecialchars(RECAPTCHA_SITE_KEY, ENT_QUOTES, 'UTF-8');
        echo '<input type="hidden" name="recaptcha_token" class="mpp-recaptcha-token" value="">';
        echo '<script src="https://www.google.com/recaptcha/api.js?render=' . $k . '"></script>';
        echo '<script>document.addEventListener("submit",function(ev){var f=ev.target;if(!f.querySelector)return;var t=f.querySelector(".mpp-recaptcha-token");if(!t||t.value)return;ev.preventDefault();grecaptcha.ready(function(){grecaptcha.execute("' . $k . '",{action:"submit"}).then(function(tok){t.value=tok;f.submit();});});},true);</script>';
    }
}
