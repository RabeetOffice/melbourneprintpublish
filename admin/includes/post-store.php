<?php
/**
 * Blog post store + publish pipeline.
 *
 * Source of truth for admin-managed posts: admin/data/posts/<slug>.json
 * Publishing writes, in order (each via admin_replace_site_file):
 *   1. blogs/<slug>.php            (generated from the brand template, linted)
 *   2. data/blogs_post.php         ($blogs registry, regenerated, linted)
 *   3. sitemap_index.xml           (house-format <url> entry upsert)
 * Unpublishing reverses 2-3 and moves the post file to /trash (never deletes).
 *
 * The generator template is derived byte-for-byte from the brand's newest
 * hand-built post (blogs/how-to-write-a-short-story.php). Regenerating that
 * post from an import MUST be byte-identical - proven by the test suite.
 */

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/content-store.php';

const MPP_BLOG_DOMAIN = 'https://melbourneprintpublish.com.au';

function mpp_posts_dir(): string
{
    return ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'posts';
}

function mpp_post_json_path(string $slug): string
{
    return mpp_posts_dir() . DIRECTORY_SEPARATOR . $slug . '.json';
}

function mpp_post_php_path(string $slug): string
{
    return ADMIN_SITE_ROOT . DIRECTORY_SEPARATOR . 'blogs' . DIRECTORY_SEPARATOR . $slug . '.php';
}

function mpp_valid_slug(string $slug): bool
{
    return (bool) preg_match('~^[a-z0-9]+(?:-[a-z0-9]+)*$~', $slug) && strlen($slug) <= 120;
}

/* ---------------------------------------------------------------------------
 * Post JSON source
 * ------------------------------------------------------------------------- */

function mpp_post_load(string $slug): ?array
{
    if (!mpp_valid_slug($slug)) {
        return null;
    }
    $post = admin_json_read(mpp_post_json_path($slug), null);
    return is_array($post) ? $post : null;
}

function mpp_post_save(array $post): bool
{
    $slug = (string) ($post['slug'] ?? '');
    if (!mpp_valid_slug($slug)) {
        return false;
    }
    $post['updated'] = date('c');
    return admin_json_write(mpp_post_json_path($slug), $post);
}

/** All posts: admin sources merged with registry-only legacy posts. */
function mpp_post_index(): array
{
    $out = [];
    foreach (glob(mpp_posts_dir() . DIRECTORY_SEPARATOR . '*.json') ?: [] as $file) {
        $post = admin_json_read($file, null);
        if (is_array($post) && !empty($post['slug'])) {
            $out[$post['slug']] = [
                'slug'    => $post['slug'],
                'status'  => (string) ($post['status'] ?? 'draft'),
                'title'   => (string) ($post['registry']['name'] ?? $post['seo']['page_title'] ?? $post['slug']),
                'image'   => (string) ($post['registry']['image'] ?? ''),
                'date'    => (string) ($post['registry']['date'] ?? ''),
                'updated' => (string) ($post['updated'] ?? ''),
                'source'  => (string) ($post['source'] ?? 'admin'),
                'managed' => true,
            ];
        }
    }
    // Legacy posts that exist on the site but have no admin source yet.
    foreach (mpp_registry_read() as $entry) {
        $slug = mpp_slug_from_link((string) ($entry['link'] ?? ''));
        if ($slug === '' || isset($out[$slug])) {
            continue;
        }
        $out[$slug] = [
            'slug'    => $slug,
            'status'  => is_file(mpp_post_php_path($slug)) ? 'published' : 'missing-file',
            'title'   => (string) ($entry['name'] ?? $slug),
            'image'   => (string) ($entry['image'] ?? ''),
            'date'    => (string) ($entry['date'] ?? ''),
            'updated' => '',
            'source'  => 'site',
            'managed' => false,
        ];
    }
    return array_values($out);
}

function mpp_slug_from_link(string $link): string
{
    if (!preg_match('~/blogs/([^/]+?)(?:\.php)?/?$~', $link, $m)) {
        return '';
    }
    return mpp_valid_slug($m[1]) ? $m[1] : '';
}

/* ---------------------------------------------------------------------------
 * Body sanitizer. The article body is the only raw-HTML sink in the whole
 * pipeline, so it gets the hard treatment:
 *   - DOM whitelist of tags + per-tag attributes
 *   - all non-element/non-text nodes dropped (comments, CDATA and - critical -
 *     PHP processing instructions, which would otherwise execute after being
 *     written into the generated .php file)
 *   - scheme checks on href/src, conservative inline-style filter
 *   - h1 demoted to h2, h5/h6 demoted to h4, unknown tags unwrapped
 * ukph_strip_php() runs on the serialized result as a final guard.
 * ------------------------------------------------------------------------- */

function ukph_allowed_tags(): array
{
    return [
        'p'          => ['class'],
        'h2'         => ['id', 'class'],
        'h3'         => ['id', 'class'],
        'h4'         => ['id', 'class'],
        'ul'         => ['class'],
        'ol'         => ['class'],
        'li'         => ['class'],
        'table'      => ['class'],
        'thead'      => [],
        'tbody'      => [],
        'tr'         => [],
        'td'         => ['colspan', 'rowspan', 'class'],
        'th'         => ['colspan', 'rowspan', 'class', 'scope'],
        'blockquote' => ['class'],
        'figure'     => ['class'],
        'figcaption' => ['class'],
        'hr'         => [],
        'a'          => ['href', 'title', 'class', 'target', 'rel'],
        'img'        => ['src', 'alt', 'width', 'height', 'class', 'loading'],
        'b'          => [],
        'strong'     => [],
        'i'          => [],
        'em'         => [],
        'u'          => [],
        'br'         => [],
        'span'       => ['class', 'style'],
        'sub'        => [],
        'sup'        => [],
        // legacy inline-styled call-out boxes used by this brand's posts
        'section'    => ['class', 'style'],
        'div'        => ['class', 'style', 'id'],
    ];
}

function ukph_safe_url(string $url): bool
{
    $url = trim($url);
    if ($url === '') {
        return false;
    }
    if (preg_match('~^(?:https?:)?//~i', $url)) {
        return true;
    }
    if ($url[0] === '/' || $url[0] === '#') {
        return true;
    }
    if (preg_match('~^(?:mailto|tel):~i', $url)) {
        return true;
    }
    // relative path with no scheme
    return !preg_match('~^[a-z][a-z0-9+.\-]*:~i', $url);
}

function ukph_safe_style(string $style): bool
{
    $style = strtolower($style);
    foreach (['url(', 'expression', 'behavior', '@import', 'javascript', '\\', '<'] as $bad) {
        if (strpos($style, $bad) !== false) {
            return false;
        }
    }
    return strlen($style) <= 600;
}

function ukph_sanitize_body(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML(
        '<?xml encoding="utf-8"?><body>' . $html . '</body>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET
    );
    libxml_clear_errors();

    $body = $doc->getElementsByTagName('body')->item(0);
    if (!$body) {
        return '';
    }

    ukph_walk_sanitize($body, $doc);

    $out = '';
    foreach (iterator_to_array($body->childNodes) as $child) {
        $out .= $doc->saveHTML($child);
    }
    return ukph_strip_php($out);
}

function ukph_walk_sanitize(DOMNode $node, DOMDocument $doc): void
{
    $allowed = ukph_allowed_tags();
    $demote = ['h1' => 'h2', 'h5' => 'h4', 'h6' => 'h4'];
    $drop = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'link', 'meta', 'base', 'noscript'];

    foreach (iterator_to_array($node->childNodes) as $child) {
        /* CRITICAL: keep only element + text nodes. Comments, CDATA and PHP
           processing instructions (a nested PHP open/close tag becomes a PI
           node in DOMDocument) are removed; a PI left in place would be
           re-emitted into the generated .php file and execute. */
        if ($child->nodeType === XML_TEXT_NODE) {
            continue;
        }
        if ($child->nodeType !== XML_ELEMENT_NODE) {
            $node->removeChild($child);
            continue;
        }

        $tag = strtolower($child->nodeName);

        if (in_array($tag, $drop, true)) {
            $node->removeChild($child);
            continue;
        }

        if (isset($demote[$tag])) {
            $replacement = $doc->createElement($demote[$tag]);
            while ($child->firstChild) {
                $replacement->appendChild($child->firstChild);
            }
            foreach (iterator_to_array($child->attributes ?? []) as $attr) {
                $replacement->setAttribute($attr->name, $attr->value);
            }
            $node->replaceChild($replacement, $child);
            $child = $replacement;
            $tag = strtolower($child->nodeName);
        }

        if (!isset($allowed[$tag])) {
            // Unknown tag: unwrap (keep children in place), then continue the
            // walk over the children that now belong to $node.
            while ($child->firstChild) {
                $node->insertBefore($child->firstChild, $child);
            }
            $node->removeChild($child);
            ukph_walk_sanitize($node, $doc);
            return;
        }

        // Attribute whitelist for this tag.
        $keep = $allowed[$tag];
        foreach (iterator_to_array($child->attributes) as $attr) {
            $name = strtolower($attr->name);
            $value = (string) $attr->value;
            $ok = in_array($name, $keep, true);
            if ($ok && ($name === 'href' || $name === 'src')) {
                $ok = ukph_safe_url($value) && stripos($value, 'javascript:') === false
                    && stripos($value, 'data:') !== 0 && stripos($value, 'vbscript:') === false;
            }
            if ($ok && $name === 'style') {
                $ok = ukph_safe_style($value);
            }
            if ($ok && $name === 'target') {
                $ok = $value === '_blank';
            }
            if (!$ok) {
                $child->removeAttribute($attr->name);
            }
        }
        if ($tag === 'a' && $child->getAttribute('target') === '_blank' && $child->getAttribute('rel') === '') {
            $child->setAttribute('rel', 'noopener');
        }

        ukph_walk_sanitize($child, $doc);
    }
}

/** FAQ answers are echoed raw by the template: attribute-strip + strip PHP. */
function ukph_sanitize_faq_answer(string $html): string
{
    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML(
        '<?xml encoding="utf-8"?><body>' . $html . '</body>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET
    );
    libxml_clear_errors();
    $body = $doc->getElementsByTagName('body')->item(0);
    if (!$body) {
        return '';
    }
    ukph_faq_walk($body, $doc);
    $out = '';
    foreach (iterator_to_array($body->childNodes) as $child) {
        $out .= $doc->saveHTML($child);
    }
    return ukph_strip_php(trim($out));
}

function ukph_faq_walk(DOMNode $node, DOMDocument $doc): void
{
    $allowed = ['p' => [], 'ul' => [], 'ol' => [], 'li' => [], 'b' => [], 'strong' => [], 'i' => [], 'em' => [], 'br' => [], 'a' => ['href']];
    foreach (iterator_to_array($node->childNodes) as $child) {
        if ($child->nodeType === XML_TEXT_NODE) {
            continue;
        }
        if ($child->nodeType !== XML_ELEMENT_NODE) {
            $node->removeChild($child);
            continue;
        }
        $tag = strtolower($child->nodeName);
        if (!isset($allowed[$tag])) {
            while ($child->firstChild) {
                $node->insertBefore($child->firstChild, $child);
            }
            $node->removeChild($child);
            ukph_faq_walk($node, $doc);
            return;
        }
        foreach (iterator_to_array($child->attributes) as $attr) {
            $name = strtolower($attr->name);
            $ok = in_array($name, $allowed[$tag], true);
            if ($ok && $name === 'href') {
                $v = (string) $attr->value;
                $ok = ukph_safe_url($v) && stripos($v, 'javascript:') === false && stripos($v, 'data:') !== 0;
            }
            if (!$ok) {
                $child->removeAttribute($attr->name);
            }
        }
        ukph_faq_walk($child, $doc);
    }
}

/* ---------------------------------------------------------------------------
 * THE GENERATOR - byte-for-byte template of the brand's newest post.
 * Placeholders: %%PAGE_TITLE%% %%PAGE_DESC%% %%HEAD_EXTRA%% %%SLUG%% %%H1%%
 * %%DATE_DISPLAY%% %%IMAGE_SQ%% %%IMAGE_ALT%% (+ body / faqs / author blocks).
 * Assembled with LF, converted to CRLF (the house line ending) at the end.
 * ------------------------------------------------------------------------- */

function mpp_template_head(): string
{
    return <<<'EOT'
<?php require_once __DIR__ . '/../includes/config.php'; ?>%%PREVIEW_GUARD%%
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <title>%%PAGE_TITLE%%</title>
    <meta name="description" content="%%PAGE_DESC%%" />
    <meta name="DC.title" content="Melbourne Print and Publish" />
    <meta name="geo.region" content="AU-VIC" />
    <meta name="geo.placename" content="Melbourne" />
    <meta name="geo.position" content="-37.841303;144.97652" />
    <meta name="ICBM" content="-37.841303, 144.97652" />
    <link rel="canonical" href="<?php echo e(brand_canonical()); ?>" />
    <meta name="google-site-verification" content="fmX4SQHeIHlfWDv3FiuLtWRDGStxBIfdRWPTQGQ8Vxs" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-V7PVJBXYL9"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-V7PVJBXYL9');
    </script>
    <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-PFZRKR97');
    </script>
    <?php include("../includes/style.php"); ?>%%HEAD_EXTRA%%
</head>

<body class="%%SLUG%%">

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFZRKR97" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <?php include("../includes/disclaimer.php"); ?>

    <header id="masthead" class="site-header">
        <?php include("../includes/header.php"); ?>
    </header>

    <section class="s-blog-sec1">
        <div class="container">
            <div class="row">
                <div class="col-md-8 left-col">
                    <div class="c-breadcrumb">Blogs
                        <span>
                            <svg width="22" height="11" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.7308 6.1557L17.3133 10.742C17.1476 10.914 16.9268 11 16.7059 11C16.4574 11 16.2365 10.914 16.0709 10.742C15.7119 10.398 15.7119 9.79609 16.0709 9.45212L18.9699 6.41368H0.883504C0.386533 6.41368 0 6.01238 0 5.49642C0 5.00912 0.386533 4.57915 0.883504 4.57915H18.9699L16.0709 1.56938C15.7119 1.22541 15.7119 0.623453 16.0709 0.279479C16.4022 -0.0931596 16.982 -0.0931596 17.3133 0.279479L21.7308 4.8658C22.0897 5.20977 22.0897 5.81173 21.7308 6.1557Z" fill="#252525" />
                            </svg>
                        </span>
                        Blog Detail
                    </div>

                    <div class="s-blog-title">
                        <h1>%%H1%%</h1>
                    </div>

                    <div class="tag-date">Posted on: %%DATE_DISPLAY%%</div>

                    <div class="s-banner">
                        <img src="<?= e(brand_asset('%%IMAGE_SQ%%')); ?>" alt="%%BANNER_ALT%%">
                    </div>

                    <div class="s-content">%%BODY%%</div>
EOT;
}

function mpp_template_faq_card(): string
{
    return <<<'EOT'
                            <div class="card">
                                <div id="heading%%N%%" class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse%%N%%">
                                        <span><strong>%%Q%%</strong></span>
                                    </div>
                                </div>
                                <div id="collapse%%N%%" class="collapse%%SHOW%%">
                                    <div class="card-body text">
                                        %%A%%
                                    </div>
                                </div>
                            </div>
EOT;
}

function mpp_template_author(): string
{
    return <<<'EOT'
                    <div class="author-box">
                        <div class="author-avatar">
                            <img src="<?= e(brand_asset('/assets/images/avatar.jpg')); ?>" alt="avatar">
                        </div>
                        <div class="author-info">
                            <div class="author-user">
                                <h3>%%AUTHOR_NAME%%</h3>
                            </div>
                            <div class="author-bio">
                                <p>%%AUTHOR_BIO%%</p>
                            </div>
                        </div>
                    </div>
EOT;
}

function mpp_template_tail(): string
{
    return <<<'EOT'
                </div>

                <div class="col-md-4 right-col">
                    <div class="sidebar-sticky">
                        <div class="blog-logo">
                            <img src="<?= e(brand_asset('/assets/images/logo.png')); ?>" alt="Primary logo of Melbourne Print & Publish">
                        </div>
                        <div class="share-box">
                            <span class="share-title">Share this article</span>
                            <div class="social-icon">
                                <ul>
                                    <li><a href="#" class="share-btn fb" data-platform="facebook"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.47955 13.1984L2.46192 7.77466H0.154785V5.4502H2.46192V3.90055C2.46192 1.80916 3.74739 0.80127 5.59915 0.80127C6.48616 0.80127 7.2485 0.867803 7.47066 0.897541V3.08317L6.18637 3.08376C5.17929 3.08376 4.98429 3.56591 4.98429 4.27343V5.4502H7.84524L7.07619 7.77466H4.98428V13.1984H2.47955Z" fill="#252525"></path></svg></a></li>
                                    <li><a href="#" class="share-btn li" data-platform="linkedin"><svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.25281 3.09303C3.05555 3.09303 3.70631 2.44227 3.70631 1.63953C3.70631 0.836787 3.05555 0.186035 2.25281 0.186035C1.45007 0.186035 0.799316 0.836787 0.799316 1.63953C0.799316 2.44227 1.45007 3.09303 2.25281 3.09303Z" fill="#252525"></path><path d="M3.46406 4.06201H1.04157C0.907844 4.06201 0.799316 4.17054 0.799316 4.30426V11.5717C0.799316 11.7055 0.907844 11.814 1.04157 11.814H3.46406C3.59778 11.814 3.70631 11.7055 3.70631 11.5717V4.30426C3.70631 4.17054 3.59778 4.06201 3.46406 4.06201Z" fill="#252525"></path><path d="M10.6816 3.72786C9.64625 3.3732 8.35118 3.68474 7.57453 4.24336C7.54788 4.1392 7.45292 4.06168 7.34003 4.06168H4.91754C4.78382 4.06168 4.67529 4.1702 4.67529 4.30392V11.5714C4.67529 11.7051 4.78382 11.8137 4.91754 11.8137H7.34003C7.47376 11.8137 7.58228 11.7051 7.58228 11.5714V6.34851C7.97376 6.0113 8.47812 5.90374 8.89091 6.07913C9.29111 6.24822 9.52028 6.66101 9.52028 7.21092V11.5714C9.52028 11.7051 9.6288 11.8137 9.76253 11.8137H12.185C12.3187 11.8137 12.4273 11.7051 12.4273 11.5714V6.72303C12.3997 4.73222 11.4631 3.9953 10.6816 3.72786Z" fill="#252525"></path></svg></a></li>
                                    <li><a href="#" class="share-btn tw" data-platform="twitter"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/></svg></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="toc">
                            <div class="toc-heading"><h4>In This Blog</h4></div>
                            <div class="toc-listing"><ul class="toc-list"></ul></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="s-blog-sec2">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head38"><h2>Recent <span>Blogs</span></h2></div>
                    <?php include("../shortcode/recent_blogs.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <?php include("../includes/footer.php"); ?>
    <?php include("../includes/script.php"); ?>
</body>
</html>
EOT;
}

/**
 * Generate the full post .php file content from a post source array.
 * Set $preview=true to inject the admin-session guard (404 for the public).
 */
function mpp_generate_post_php(array $post, bool $preview = false): string
{
    $slug = (string) $post['slug'];
    $seo = (array) ($post['seo'] ?? []);
    $display = (array) ($post['display'] ?? []);
    $registry = (array) ($post['registry'] ?? []);

    $imagePath = (string) ($registry['image'] ?? '');
    // The image path lands inside a single-quoted PHP literal: escape it and
    // refuse anything that could re-open PHP.
    $imageSq = admin_php_sq(ukph_strip_php($imagePath));

    $guard = '';
    if ($preview) {
        $guard = "\n" . '<?php session_name(\'mpp_admin_sess\'); if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); } if (empty($_SESSION[\'admin_user\'])) { http_response_code(404); exit; } header(\'X-Robots-Tag: noindex, nofollow\'); ?>';
    }

    $head = strtr(mpp_template_head(), [
        '%%PREVIEW_GUARD%%' => $guard,
        '%%PAGE_TITLE%%'    => ukph_strip_php((string) ($seo['page_title'] ?? '')),
        '%%PAGE_DESC%%'     => ukph_strip_php((string) ($seo['page_description'] ?? '')),
        '%%HEAD_EXTRA%%'    => ukph_strip_php((string) ($post['head_extra'] ?? '')),
        '%%SLUG%%'          => $slug,
        '%%H1%%'            => ukph_strip_php((string) ($display['h1'] ?? '')),
        '%%DATE_DISPLAY%%'  => ukph_strip_php((string) ($display['date_display'] ?? '')),
        '%%IMAGE_SQ%%'      => $imageSq,
        '%%BANNER_ALT%%'    => ukph_strip_php((string) ($display['banner_alt'] ?? $registry['alt'] ?? '')),
        '%%BODY%%'          => ukph_strip_php((string) ($post['body'] ?? '')),
    ]);

    $parts = [$head];

    $faqs = is_array($post['faqs'] ?? null) ? $post['faqs'] : [];
    if ($faqs) {
        $cards = [];
        $n = 0;
        foreach ($faqs as $faq) {
            $q = trim((string) ($faq['q'] ?? ''));
            $a = trim((string) ($faq['a'] ?? ''));
            if ($q === '' || $a === '') {
                continue;
            }
            $n++;
            $cards[] = strtr(mpp_template_faq_card(), [
                '%%N%%'    => (string) $n,
                '%%SHOW%%' => $n === 1 ? ' show' : '',
                '%%Q%%'    => ukph_strip_php($q),
                '%%A%%'    => ukph_strip_php($a),
            ]);
        }
        if ($cards) {
            $parts[] = '                    <div class="faqs">' . "\n"
                . '                        <div id="accordions" class="accordion">' . "\n"
                . implode("\n", $cards) . "\n"
                . '                        </div>' . "\n"
                . '                    </div>';
        }
    }

    $author = is_array($post['author'] ?? null) ? $post['author'] : null;
    if ($author && trim((string) ($author['name'] ?? '')) !== '') {
        $parts[] = strtr(mpp_template_author(), [
            '%%AUTHOR_NAME%%' => ukph_strip_php(trim((string) $author['name'])),
            '%%AUTHOR_BIO%%'  => ukph_strip_php(trim((string) ($author['bio'] ?? ''))),
        ]);
    }

    $out = implode("\n\n", $parts) . "\n" . mpp_template_tail();

    // House line endings: CRLF throughout, no trailing newline after </html>.
    $out = str_replace("\r\n", "\n", $out);
    return str_replace("\n", "\r\n", $out);
}

/* ---------------------------------------------------------------------------
 * Legacy import: parse an existing blogs/<slug>.php into a post source.
 * Captures are verbatim so regenerating an unedited import stays
 * byte-identical (for posts built on the current template).
 * ------------------------------------------------------------------------- */

function mpp_import_post(string $slug): ?array
{
    $path = mpp_post_php_path($slug);
    if (!mpp_valid_slug($slug) || !is_file($path)) {
        return null;
    }
    $raw = (string) file_get_contents($path);
    $html = str_replace("\r\n", "\n", $raw);

    $title = preg_match('~<title>(.*?)</title>~s', $html, $m) ? $m[1] : '';
    $desc = '';
    if (preg_match('~<meta name="description"\s+content="(.*?)"\s*/>~s', $html, $m)) {
        $desc = $m[1];
    }
    $h1 = preg_match('~<div class="s-blog-title">\s*<h1>(.*?)</h1>~s', $html, $m) ? trim($m[1]) : '';
    $date = preg_match('~<div class="tag-date">Posted on:\s?(.*?)</div>~', $html, $m) ? $m[1] : '';

    $image = '';
    $alt = '';
    if (preg_match('~<div class="s-banner">\s*<img src="(.*?)"\s+alt="(.*?)"~s', $html, $m)) {
        $srcRaw = $m[1];
        $alt = $m[2];
        if (preg_match("~brand_asset\\('([^']*)'\\)~", $srcRaw, $mm)) {
            $image = $mm[1];
        } else {
            $image = $srcRaw;
        }
    }

    // Everything between the style include and </head> (per-post <style> block).
    $headExtra = '';
    $styleAnchor = '<?php include("../includes/style.php"); ?>';
    $sp = strpos($html, $styleAnchor);
    if ($sp !== false) {
        $after = $sp + strlen($styleAnchor);
        $he = strpos($html, "\n</head>", $after);
        if ($he !== false) {
            $headExtra = substr($html, $after, $he - $after);
        }
    }

    // Body: from after <div class="s-content"> to the last </div> before the
    // faqs / author-box / sidebar block (handles nested divs inside the body).
    $open = strpos($html, '<div class="s-content">');
    if ($open === false) {
        return null;
    }
    $bodyStart = $open + strlen('<div class="s-content">');
    $endAnchor = false;
    foreach (['<div class="faqs">', '<div class="author-box">', '<div class="col-md-4 right-col">'] as $anchor) {
        $p = strpos($html, $anchor, $bodyStart);
        if ($p !== false && ($endAnchor === false || $p < $endAnchor)) {
            $endAnchor = $p;
        }
    }
    if ($endAnchor === false) {
        return null;
    }
    $chunk = substr($html, $bodyStart, $endAnchor - $bodyStart);
    // Trim trailing whitespace + HTML comments (older posts put an
    // "<!-- Accordions Start -->" comment between the body and the FAQ block).
    $trimmed = mpp_rtrim_comments($chunk);
    if (substr($trimmed, -6) !== '</div>') {
        return null;
    }
    $body = substr($trimmed, 0, -6);
    // The closing </div> of col-md-8 sits between s-content and the sidebar
    // when there is no faqs/author block; drop it if present.
    if (strpos($html, '<div class="faqs">', $bodyStart) === false
        && strpos($html, '<div class="author-box">', $bodyStart) === false) {
        $again = mpp_rtrim_comments($body);
        if (substr($again, -6) === '</div>') {
            $body = substr($again, 0, -6);
        }
    }
    $body = mpp_resolve_brand_asset_php($body);

    // FAQs. Per-card capture handles both the current markup
    // (<span><strong>Q</strong></span>) and older posts where <strong> only
    // wraps a "Q1:" label (<span><strong>Q1:</strong> real question</span>).
    // The question is the full <span> text with tags stripped; the answer is
    // the inner HTML of the card-body.
    $faqs = [];
    $faqStart = strpos($html, '<div class="faqs">');
    if ($faqStart !== false) {
        $faqEnd = strpos($html, '<div class="author-box">', $faqStart);
        if ($faqEnd === false) {
            $faqEnd = strpos($html, '<div class="col-md-4 right-col">', $faqStart);
        }
        $faqHtml = $faqEnd !== false ? substr($html, $faqStart, $faqEnd - $faqStart) : substr($html, $faqStart);
        if (preg_match_all(
            '~<div class="collapsible-link"[^>]*>\s*<span>(.*?)</span>\s*</div>\s*</div>\s*<div id="collapse\d+"[^>]*>\s*<div class="card-body text">\s*(.*?)\s*</div>~s',
            $faqHtml,
            $cards,
            PREG_SET_ORDER
        )) {
            foreach ($cards as $card) {
                $q = trim(preg_replace('~\s+~', ' ', strip_tags($card[1])));
                $a = mpp_resolve_brand_asset_php(trim($card[2]));
                if ($q !== '' && $a !== '') {
                    $faqs[] = ['q' => $q, 'a' => $a];
                }
            }
        }
    }

    // Author box
    $author = null;
    if (preg_match('~<div class="author-box">.*?<h3>(.*?)</h3>.*?<div class="author-bio">\s*<p>(.*?)</p>~s', $html, $m)) {
        $author = ['name' => trim($m[1]), 'bio' => trim($m[2])];
    }

    $registryEntry = mpp_registry_find($slug);

    return [
        'slug'     => $slug,
        'status'   => 'published',
        'registry' => $registryEntry ?: [
            'id'     => '',
            'hidden' => '',
            'image'  => $image,
            'alt'    => $alt,
            'name'   => $h1,
            'text'   => '',
            'date'   => $date,
            'link'   => '/blogs/' . $slug . '/',
        ],
        'seo' => [
            'page_title'       => $title,
            'page_description' => $desc,
        ],
        'display' => [
            'h1'           => $h1,
            'date_display' => $date,
            'banner_alt'   => $alt,
        ],
        'head_extra' => $headExtra,
        'body'       => $body,
        'faqs'       => $faqs,
        'author'     => $author,
        'source'     => 'imported',
        'created'    => date('c'),
        'updated'    => date('c'),
    ];
}

/** Strip trailing whitespace and trailing HTML comments from a chunk. */
function mpp_rtrim_comments(string $s): string
{
    $prev = null;
    while ($prev !== $s) {
        $prev = $s;
        $s = rtrim($s);
        // Tempered: the comment body may not contain "-->", so this only ever
        // matches a single trailing comment (never spans body-internal ones).
        $s = (string) preg_replace('~<!--(?:(?!-->).)*-->\s*$~s', '', $s);
    }
    return rtrim($s);
}

/** Replace the brand_asset() echo tags with the live literal URL. */
function mpp_resolve_brand_asset_php(string $html): string
{
    return (string) preg_replace_callback(
        "~<\\?(?:php echo|=)\\s*e\\(brand_asset\\('([^']*)'\\)\\);?\\s*\\?>~",
        function ($m) {
            $path = $m[1];
            if (preg_match('~^(?:https?:)?//~i', $path)) {
                return htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
            }
            return MPP_BLOG_DOMAIN . '/' . ltrim($path, '/');
        },
        $html
    );
}

/* ---------------------------------------------------------------------------
 * Registry (data/blogs_post.php) helpers - reads/writes via content-store.
 * ------------------------------------------------------------------------- */

function mpp_registry_read(): array
{
    return mpp_data_file_read(ADMIN_SITE_ROOT . '/data/blogs_post.php', 'blogs');
}

function mpp_registry_find(string $slug): ?array
{
    foreach (mpp_registry_read() as $entry) {
        if (mpp_slug_from_link((string) ($entry['link'] ?? '')) === $slug) {
            return $entry;
        }
    }
    return null;
}

function mpp_registry_upsert(array $entry, string $slug): bool
{
    $items = mpp_registry_read();
    $found = false;
    foreach ($items as $i => $existing) {
        if (mpp_slug_from_link((string) ($existing['link'] ?? '')) === $slug) {
            $entry['id'] = (string) ($existing['id'] ?? $entry['id']);
            $items[$i] = $entry;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $max = 0;
        foreach ($items as $existing) {
            $max = max($max, (int) ($existing['id'] ?? 0));
        }
        $entry['id'] = (string) ($max + 1);
        array_unshift($items, $entry); // newest first, matching the house order
    }
    return mpp_data_file_write(ADMIN_SITE_ROOT . '/data/blogs_post.php', 'blogs', $items, mpp_registry_fields());
}

function mpp_registry_remove(string $slug): bool
{
    $items = mpp_registry_read();
    $kept = [];
    foreach ($items as $existing) {
        if (mpp_slug_from_link((string) ($existing['link'] ?? '')) !== $slug) {
            $kept[] = $existing;
        }
    }
    if (count($kept) === count($items)) {
        return true;
    }
    return mpp_data_file_write(ADMIN_SITE_ROOT . '/data/blogs_post.php', 'blogs', $kept, mpp_registry_fields());
}

function mpp_registry_fields(): array
{
    return ['id', 'hidden', 'image', 'alt', 'name', 'text', 'date', 'link'];
}

/* ---------------------------------------------------------------------------
 * Sitemap upsert (house format: flush-left url blocks, loc/lastmod/priority)
 * ------------------------------------------------------------------------- */

function mpp_sitemap_path(): string
{
    return ADMIN_SITE_ROOT . DIRECTORY_SEPARATOR . 'sitemap_index.xml';
}

function mpp_sitemap_upsert(string $slug, ?string $lastmod = null): bool
{
    $path = mpp_sitemap_path();
    if (!is_file($path)) {
        return false;
    }
    $xml = (string) file_get_contents($path);
    $loc = MPP_BLOG_DOMAIN . '/blogs/' . $slug . '/';
    $lastmod = $lastmod ?: date('Y-m-d');
    $block = "<url>\n<loc>" . $loc . "</loc>\n<lastmod>" . $lastmod . "</lastmod>\n<priority>1.0</priority>\n</url>\n";

    $pattern = '~<url>\s*<loc>' . preg_quote($loc, '~') . '</loc>.*?</url>\s*~s';
    if (preg_match($pattern, $xml)) {
        $xml = (string) preg_replace($pattern, $block, $xml, 1);
    } else {
        $xml = str_replace('</urlset>', $block . '</urlset>', $xml);
    }
    return admin_replace_site_file($path, $xml);
}

function mpp_sitemap_remove(string $slug): bool
{
    $path = mpp_sitemap_path();
    if (!is_file($path)) {
        return false;
    }
    $xml = (string) file_get_contents($path);
    $loc = MPP_BLOG_DOMAIN . '/blogs/' . $slug . '/';
    $pattern = '~<url>\s*<loc>' . preg_quote($loc, '~') . '</loc>.*?</url>\n?~s';
    $xml = (string) preg_replace($pattern, '', $xml);
    return admin_replace_site_file($path, $xml);
}

/* ---------------------------------------------------------------------------
 * Publish / unpublish / preview
 * ------------------------------------------------------------------------- */

function mpp_publish(array $post): array
{
    $slug = (string) ($post['slug'] ?? '');
    if (!mpp_valid_slug($slug)) {
        return [false, 'Invalid slug.'];
    }

    // 1. Generate + lint + write the post file.
    $php = mpp_generate_post_php($post, false);
    if (!php_check_syntax_string($php)) {
        return [false, 'Generated PHP failed lint - post NOT published.'];
    }
    if (!admin_replace_site_file(mpp_post_php_path($slug), $php)) {
        return [false, 'Could not write the post file.'];
    }

    // 2. Registry upsert (content-store lints before replacing). The link is
    // always rebuilt from the slug so the card can never point at an old slug.
    $entry = (array) ($post['registry'] ?? []);
    $entry['link'] = '/blogs/' . $slug . '/';
    if (!mpp_registry_upsert($entry, $slug)) {
        return [false, 'Post file written but the registry update failed.'];
    }

    // 3. Sitemap upsert.
    mpp_sitemap_upsert($slug);

    // 4. Mark the source published.
    $post['status'] = 'published';
    mpp_post_save($post);

    return [true, 'Published.'];
}

/**
 * Retire every artifact tied to a slug a post has been renamed away from: the
 * JSON source, the registry entry, the sitemap entry and (defensively) any live
 * .php file. Called when a draft is published under a new slug, so the old slug
 * cannot linger as a duplicate listing ("clone") or a dead inbound link.
 * registry/sitemap removals are no-ops when nothing matches, so this is safe to
 * call for a draft rename where only the JSON source exists.
 */
function mpp_retire_slug(string $slug): void
{
    if (!mpp_valid_slug($slug)) {
        return;
    }
    @unlink(mpp_post_json_path($slug));
    mpp_registry_remove($slug);
    mpp_sitemap_remove($slug);

    $php = mpp_post_php_path($slug);
    if (is_file($php)) {
        $trashDir = ADMIN_SITE_ROOT . DIRECTORY_SEPARATOR . 'trash';
        if (!is_dir($trashDir)) {
            @mkdir($trashDir, 0755, true);
            @file_put_contents($trashDir . DIRECTORY_SEPARATOR . '.htaccess', "Require all denied\n");
        }
        admin_backup_file($php);
        @rename($php, $trashDir . DIRECTORY_SEPARATOR . $slug . '.php.' . date('Ymd-His'));
    }
}

function mpp_unpublish(array $post): array
{
    $slug = (string) ($post['slug'] ?? '');
    if (!mpp_valid_slug($slug)) {
        return [false, 'Invalid slug.'];
    }
    $live = mpp_post_php_path($slug);
    if (is_file($live)) {
        $trashDir = ADMIN_SITE_ROOT . DIRECTORY_SEPARATOR . 'trash';
        if (!is_dir($trashDir)) {
            @mkdir($trashDir, 0755, true);
            @file_put_contents($trashDir . DIRECTORY_SEPARATOR . '.htaccess', "Require all denied\n");
        }
        admin_backup_file($live);
        $dest = $trashDir . DIRECTORY_SEPARATOR . $slug . '.php.' . date('Ymd-His');
        if (!@rename($live, $dest)) {
            return [false, 'Could not move the post file to trash.'];
        }
    }
    mpp_registry_remove($slug);
    mpp_sitemap_remove($slug);
    $post['status'] = 'draft';
    mpp_post_save($post);
    return [true, 'Unpublished. The file was moved to /trash.'];
}

function mpp_preview_path(): string
{
    return ADMIN_SITE_ROOT . DIRECTORY_SEPARATOR . 'blogs' . DIRECTORY_SEPARATOR . 'admin-preview.php';
}

function mpp_write_preview(array $post): array
{
    $php = mpp_generate_post_php($post, true);
    if (!php_check_syntax_string($php)) {
        return [false, 'Preview failed PHP lint.'];
    }
    if (!admin_atomic_write(mpp_preview_path(), $php)) {
        return [false, 'Could not write the preview file.'];
    }
    return [true, 'blogs/admin-preview.php'];
}
