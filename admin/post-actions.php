<?php
/**
 * AJAX endpoint for the post editor.
 * Actions: save | publish | unpublish | delete | preview | upload_image
 * Every request: posts-module RBAC + CSRF. Responses are JSON.
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/post-store.php';

$user = admin_require_module('posts');
header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'POST only.']);
    exit;
}
admin_require_csrf();

$action = (string) ($_POST['action'] ?? '');

function respond(array $data): void
{
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/** Plain-text field: no tags, no PHP, quotes made attribute-safe. */
function mpp_clean_text(string $value, bool $attr = false): string
{
    $value = trim(strip_tags(ukph_strip_php($value)));
    $value = str_replace(["\r", "\n"], ' ', $value);
    if ($attr) {
        $value = str_replace('"', '&quot;', $value);
    }
    return $value;
}

/** Indent editor HTML to the template's 24-space body indentation. */
function mpp_body_for_template(string $sanitizedHtml): string
{
    $html = trim($sanitizedHtml);
    if ($html === '') {
        return "\n                    ";
    }
    // newline between top-level blocks so the generated source stays readable
    $html = (string) preg_replace(
        '~(</(?:p|h2|h3|h4|ul|ol|table|blockquote|div|figure|section)>)\s*(<(?:p|h2|h3|h4|ul|ol|table|blockquote|div|figure|section|hr)\b)~',
        "$1\n$2",
        $html
    );
    $lines = explode("\n", str_replace("\r\n", "\n", $html));
    $indented = array_map(
        fn($line) => $line === '' ? '' : ('                        ' . $line),
        $lines
    );
    return "\n" . implode("\n", $indented) . "\n                    ";
}

/** Build/refresh a post source from the editor payload. */
function mpp_post_from_request(array $existing = null): array
{
    $slug = (string) ($_POST['slug'] ?? '');
    if (!mpp_valid_slug($slug)) {
        respond(['ok' => false, 'error' => 'Invalid slug. Use lowercase letters, digits and hyphens.']);
    }

    $h1 = mpp_clean_text((string) ($_POST['h1'] ?? ''));
    if ($h1 === '') {
        respond(['ok' => false, 'error' => 'The post title is required.']);
    }

    $pageTitle = mpp_clean_text((string) ($_POST['page_title'] ?? ''));
    if ($pageTitle === '') {
        $pageTitle = $h1;
    }
    $pageDesc = mpp_clean_text((string) ($_POST['page_description'] ?? ''), true);
    $excerpt = mpp_clean_text((string) ($_POST['excerpt'] ?? ''));

    $dateInput = (string) ($_POST['date'] ?? '');
    $ts = strtotime($dateInput !== '' ? $dateInput : 'now') ?: time();
    $dateRegistry = date('d-m-Y', $ts);

    $image = trim((string) ($_POST['image'] ?? ''));
    $image = str_replace(['"', "'"], '', ukph_strip_php($image));
    if ($image !== '' && !preg_match('~^(?:https?://|/)~i', $image)) {
        $image = '/' . ltrim($image, '/');
    }
    $imageAlt = mpp_clean_text((string) ($_POST['image_alt'] ?? ''), true);

    $bodyRawIn = (string) ($_POST['body'] ?? '');
    $body = mpp_body_for_template(ukph_sanitize_body($bodyRawIn));

    $faqs = [];
    $faqsIn = json_decode((string) ($_POST['faqs'] ?? '[]'), true);
    if (is_array($faqsIn)) {
        foreach ($faqsIn as $faq) {
            $q = mpp_clean_text((string) ($faq['q'] ?? ''));
            $a = ukph_sanitize_faq_answer((string) ($faq['a'] ?? ''));
            if ($q !== '' && $a !== '') {
                if (strpos($a, '<') === false) {
                    $a = '<p>' . $a . '</p>';
                }
                $faqs[] = ['q' => $q, 'a' => $a];
            }
        }
    }

    $authorName = mpp_clean_text((string) ($_POST['author_name'] ?? ''));
    $authorBio = mpp_clean_text((string) ($_POST['author_bio'] ?? ''));

    $post = [
        'slug'   => $slug,
        'status' => $existing['status'] ?? 'draft',
        'registry' => [
            'id'     => (string) ($existing['registry']['id'] ?? ''),
            'hidden' => (string) ($existing['registry']['hidden'] ?? ''),
            'image'  => $image,
            'alt'    => $imageAlt,
            'name'   => $pageTitle,
            'text'   => $excerpt,
            'date'   => $dateRegistry,
            // The public link is a pure function of the slug. Deriving it here
            // (rather than carrying over $existing's link) keeps the registry
            // card, the generated file and the sitemap in sync after a rename -
            // preserving a stale link is what made a renamed post's card 404.
            'link'   => '/blogs/' . $slug . '/',
        ],
        'seo' => [
            'page_title'       => $pageTitle,
            'page_description' => $pageDesc,
        ],
        'display' => [
            'h1'           => $h1,
            'date_display' => $dateRegistry,
        ],
        'head_extra' => (string) ($existing['head_extra'] ?? ''),
        'body'       => $body,
        'faqs'       => $faqs,
        'author'     => $authorName !== '' ? ['name' => $authorName, 'bio' => $authorBio] : null,
        'source'     => (string) ($existing['source'] ?? 'admin'),
        'created'    => (string) ($existing['created'] ?? date('c')),
    ];
    return $post;
}

switch ($action) {
    case 'save': {
        $slug = (string) ($_POST['slug'] ?? '');
        $originalSlug = (string) ($_POST['original_slug'] ?? '');
        $existing = $originalSlug !== '' ? mpp_post_load($originalSlug) : null;

        // Slug is locked once published: changing it breaks inbound links/SEO.
        if ($existing && ($existing['status'] ?? '') === 'published' && $slug !== $originalSlug) {
            respond(['ok' => false, 'error' => 'The slug is locked because this post is published.']);
        }
        if ((!$existing || $slug !== $originalSlug)) {
            if (mpp_post_load($slug) || is_file(mpp_post_php_path($slug))) {
                respond(['ok' => false, 'error' => 'A post with this slug already exists.']);
            }
        }

        $post = mpp_post_from_request($existing);
        if (!mpp_post_save($post)) {
            respond(['ok' => false, 'error' => 'Could not save the draft.']);
        }
        if ($existing && $slug !== $originalSlug && mpp_valid_slug($originalSlug)) {
            @unlink(mpp_post_json_path($originalSlug));
        }
        respond(['ok' => true, 'msg' => 'Draft saved.', 'slug' => $post['slug'], 'status' => $post['status']]);
    }

    case 'publish': {
        $originalSlug = (string) ($_POST['original_slug'] ?? $_POST['slug'] ?? '');
        $existing = mpp_post_load($originalSlug);
        $slug = (string) ($_POST['slug'] ?? '');
        if ($existing && ($existing['status'] ?? '') === 'published' && $slug !== $originalSlug) {
            respond(['ok' => false, 'error' => 'The slug is locked because this post is published.']);
        }
        $post = mpp_post_from_request($existing);
        if (!mpp_post_save($post)) {
            respond(['ok' => false, 'error' => 'Could not save the post source.']);
        }
        [$ok, $msg] = mpp_publish($post);
        // Draft was published under a new slug: retire the old slug's leftovers
        // (autosaved draft JSON, and any stray file/registry/sitemap entry) so
        // it can't reappear as a duplicate post or a 404ing card link.
        if ($ok && mpp_valid_slug($originalSlug) && $originalSlug !== $slug) {
            mpp_retire_slug($originalSlug);
        }
        respond(['ok' => $ok, $ok ? 'msg' : 'error' => $msg, 'status' => $ok ? 'published' : ($post['status'] ?? 'draft')]);
    }

    case 'unpublish': {
        $slug = (string) ($_POST['slug'] ?? '');
        $post = mpp_post_load($slug);
        if (!$post) {
            // Legacy post without a source yet: import it first so unpublish is reversible.
            $post = mpp_import_post($slug);
            if ($post) {
                mpp_post_save($post);
            }
        }
        if (!$post) {
            respond(['ok' => false, 'error' => 'Post not found.']);
        }
        [$ok, $msg] = mpp_unpublish($post);
        respond(['ok' => $ok, $ok ? 'msg' : 'error' => $msg, 'status' => 'draft']);
    }

    case 'delete': {
        $slug = (string) ($_POST['slug'] ?? '');
        $post = mpp_post_load($slug);
        if (!$post) {
            respond(['ok' => false, 'error' => 'Post not found.']);
        }
        if (($post['status'] ?? '') === 'published') {
            [$ok, $msg] = mpp_unpublish($post);
            if (!$ok) {
                respond(['ok' => false, 'error' => $msg]);
            }
        }
        @unlink(mpp_post_json_path($slug));
        respond(['ok' => true, 'msg' => 'Post deleted. Any published file was moved to /trash.']);
    }

    case 'preview': {
        $originalSlug = (string) ($_POST['original_slug'] ?? '');
        $existing = $originalSlug !== '' ? mpp_post_load($originalSlug) : null;
        $post = mpp_post_from_request($existing);
        [$ok, $result] = mpp_write_preview($post);
        if (!$ok) {
            respond(['ok' => false, 'error' => $result]);
        }
        respond(['ok' => true, 'url' => brand_asset('/blogs/admin-preview.php')]);
    }

    case 'upload_image': {
        if (empty($_FILES['file'])) {
            respond(['ok' => false, 'error' => 'No file received.']);
        }
        $hint = mpp_clean_text((string) ($_POST['hint'] ?? ''));
        [$ok, $result] = admin_upload_image_webp($_FILES['file'], 'assets/images', $hint);
        if (!$ok) {
            respond(['ok' => false, 'error' => $result]);
        }
        respond(['ok' => true, 'path' => '/' . $result, 'url' => brand_asset('/' . $result)]);
    }
}

respond(['ok' => false, 'error' => 'Unknown action.']);
