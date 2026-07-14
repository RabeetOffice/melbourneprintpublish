<?php
/**
 * Dynamic sitemap builder. Static pages come from admin/data/sitemap-static.json
 * (seeded from the original sitemap_index.xml); blog posts are pulled live from
 * the registry, so publishing/unpublishing a post updates the sitemap with no
 * manual step. Absolute URLs always use the live domain.
 */

require_once __DIR__ . '/config.php';

const MPP_SITEMAP_DOMAIN = 'https://melbourneprintpublish.com.au';

function mpp_sitemap_static(): array
{
    $file = __DIR__ . '/../admin/data/sitemap-static.json';
    if (is_file($file)) {
        $data = json_decode((string) @file_get_contents($file), true);
        if (is_array($data)) {
            return $data;
        }
    }
    return [];
}

/** Published blog posts from the registry, as sitemap <url> rows. */
function mpp_sitemap_posts(): array
{
    $blogs = [];
    $regFile = __DIR__ . '/../data/blogs_post.php';
    if (is_file($regFile)) {
        $reader = static function () use ($regFile) {
            include $regFile;
            return isset($blogs) && is_array($blogs) ? $blogs : [];
        };
        $blogs = $reader();
    }
    $rows = [];
    foreach ($blogs as $b) {
        if (!preg_match('~/blogs/([^/]+?)(?:\.php)?/?$~', (string) ($b['link'] ?? ''), $m)) {
            continue;
        }
        $slug = $m[1];
        $lastmod = '';
        if (preg_match('~^(\d{1,2})-(\d{1,2})-(\d{4})$~', (string) ($b['date'] ?? ''), $d)) {
            $lastmod = sprintf('%04d-%02d-%02d', $d[3], $d[2], $d[1]);
        }
        $rows[] = [
            'loc'      => MPP_SITEMAP_DOMAIN . '/blogs/' . $slug . '/',
            'lastmod'  => $lastmod ?: date('Y-m-d'),
            'priority' => '1.0',
        ];
    }
    return $rows;
}

function mpp_sitemap_xml(): string
{
    $rows = array_merge(mpp_sitemap_static(), mpp_sitemap_posts());
    // De-dupe by loc (static list already excludes live posts, but be safe).
    $seen = [];
    $out = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($rows as $r) {
        $loc = (string) ($r['loc'] ?? '');
        if ($loc === '' || isset($seen[$loc])) {
            continue;
        }
        $seen[$loc] = true;
        $lastmod = (string) ($r['lastmod'] ?? date('Y-m-d'));
        $priority = (string) ($r['priority'] ?? '1.0');
        $out .= '<url>' . "\n";
        $out .= '<loc>' . htmlspecialchars($loc, ENT_QUOTES, 'UTF-8') . '</loc>' . "\n";
        $out .= '<lastmod>' . htmlspecialchars($lastmod, ENT_QUOTES, 'UTF-8') . '</lastmod>' . "\n";
        $out .= '<priority>' . htmlspecialchars($priority, ENT_QUOTES, 'UTF-8') . '</priority>' . "\n";
        $out .= '</url>' . "\n";
    }
    $out .= '</urlset>';
    return $out;
}
