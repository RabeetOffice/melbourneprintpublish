<?php
/**
 * Authors store — a simple JSON list (admin/data/authors.json) that feeds the
 * post editor's author picker. Each post still embeds its chosen author's
 * name + bio in its own JSON source and generated HTML, so the public author
 * box is unchanged; this store just makes the choices reusable and editable.
 */

require_once __DIR__ . '/helpers.php';

function mpp_authors_file(): string
{
    return ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'authors.json';
}

function mpp_authors_read(): array
{
    $data = admin_json_read(mpp_authors_file(), ['authors' => []]);
    return is_array($data['authors'] ?? null) ? array_values($data['authors']) : [];
}

function mpp_authors_write(array $authors): bool
{
    return admin_json_write(mpp_authors_file(), ['authors' => array_values($authors)]);
}

function mpp_author_find(string $id): ?array
{
    foreach (mpp_authors_read() as $a) {
        if ((string) ($a['id'] ?? '') === $id) {
            return $a;
        }
    }
    return null;
}

function mpp_authors_next_id(array $authors): string
{
    $max = 0;
    foreach ($authors as $a) {
        $max = max($max, (int) ($a['id'] ?? 0));
    }
    return (string) ($max + 1);
}
