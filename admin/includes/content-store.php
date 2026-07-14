<?php
/**
 * Content store: reads and regenerates this brand's /data/*.php array files
 * ($blogs, $portfolio, $testimonials). Writing always lints the generated
 * PHP before replacing the live file, and every replace is backed up.
 */

require_once __DIR__ . '/helpers.php';

/** Read a data file's array by including it in an isolated scope. */
function mpp_data_file_read(string $path, string $varName): array
{
    if (!is_file($path)) {
        return [];
    }
    $reader = static function () use ($path, $varName) {
        ob_start();
        include $path;
        ob_end_clean();
        return isset($$varName) && is_array($$varName) ? $$varName : [];
    };
    return $reader();
}

/**
 * Regenerate a data file in the house style:
 *   <?php
 *   $var = [
 *
 *       [
 *           "id" => "1",
 *           ...
 *       ],
 *       ...
 *   ];
 *   ?>
 * Values are escaped for double-quoted PHP literals (admin_php_dq), which
 * makes any '"; system(...)' style breakout inert text.
 */
function mpp_data_file_write(string $path, string $varName, array $items, array $fields, bool $allowEmpty = false): bool
{
    if (!$items && !$allowEmpty && mpp_data_file_read($path, $varName)) {
        return false; // fail closed: refuse to blank a populated live data file
    }

    $blocks = [];
    foreach ($items as $item) {
        $lines = [];
        foreach ($fields as $field) {
            $value = (string) ($item[$field] ?? '');
            $lines[] = '        "' . $field . '" => "' . admin_php_dq($value) . '"';
        }
        $blocks[] = "    [\n" . implode(",\n", $lines) . "\n    ]";
    }

    $out = "<?php\n\$" . $varName . " = [\n\n" . implode(",\n\n", $blocks) . "\n\n];\n?>\n";
    if (!$blocks) {
        $out = "<?php\n\$" . $varName . " = [];\n?>\n";
    }

    if (!php_check_syntax_string($out)) {
        return false;
    }
    return admin_replace_site_file($path, $out);
}

/* ---------------------------------------------------------------------------
 * Portfolio ($portfolio in data/portfolio_post.php)
 * ------------------------------------------------------------------------- */

function mpp_portfolio_path(): string
{
    return ADMIN_SITE_ROOT . '/data/portfolio_post.php';
}

function mpp_portfolio_fields(): array
{
    return ['id', 'hidden', 'image', 'alt', 'name', 'link'];
}

function mpp_portfolio_read(): array
{
    return mpp_data_file_read(mpp_portfolio_path(), 'portfolio');
}

function mpp_portfolio_write(array $items, bool $allowEmpty = false): bool
{
    return mpp_data_file_write(mpp_portfolio_path(), 'portfolio', $items, mpp_portfolio_fields(), $allowEmpty);
}

/* ---------------------------------------------------------------------------
 * Website portfolio ($websitePortfolio in data/website_portfolio_post.php)
 * ------------------------------------------------------------------------- */

function mpp_websites_path(): string
{
    return ADMIN_SITE_ROOT . '/data/website_portfolio_post.php';
}

function mpp_websites_fields(): array
{
    return ['id', 'hidden', 'image', 'alt', 'name', 'description', 'link'];
}

function mpp_websites_read(): array
{
    return mpp_data_file_read(mpp_websites_path(), 'websitePortfolio');
}

function mpp_websites_write(array $items, bool $allowEmpty = false): bool
{
    return mpp_data_file_write(mpp_websites_path(), 'websitePortfolio', $items, mpp_websites_fields(), $allowEmpty);
}

/* ---------------------------------------------------------------------------
 * Testimonials ($testimonials in data/testimonials_post.php)
 * ------------------------------------------------------------------------- */

function mpp_testimonials_path(): string
{
    return ADMIN_SITE_ROOT . '/data/testimonials_post.php';
}

function mpp_testimonials_fields(): array
{
    return ['id', 'hidden', 'name', 'text', 'link'];
}

function mpp_testimonials_read(): array
{
    return mpp_data_file_read(mpp_testimonials_path(), 'testimonials');
}

function mpp_testimonials_write(array $items, bool $allowEmpty = false): bool
{
    return mpp_data_file_write(mpp_testimonials_path(), 'testimonials', $items, mpp_testimonials_fields(), $allowEmpty);
}

/**
 * Derive an Amazon cover image URL from the ASIN inside a product link.
 * Returns '' when the link carries no recognisable ASIN. Used by the
 * portfolio editor to auto-fill the cover when none is provided — existing
 * items are never touched, this only runs on save with an empty image field.
 */
function mpp_amazon_cover_from_link(string $link): string
{
    if (preg_match('~/(?:dp|gp/product|gp/aw/d)/([A-Z0-9]{10})(?:[/?]|$)~i', $link, $m)) {
        return 'https://m.media-amazon.com/images/P/' . strtoupper($m[1]) . '.01._SCLZZZZZZZ_SX500_.jpg';
    }
    return '';
}

/** Next numeric id for a data set (ids are stored as strings, house style). */
function mpp_next_id(array $items): string
{
    $max = 0;
    foreach ($items as $item) {
        $max = max($max, (int) ($item['id'] ?? 0));
    }
    return (string) ($max + 1);
}
