<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/post-store.php';
require_once __DIR__ . '/includes/authors-store.php';

$user = admin_require_module('posts');
$authorsList = mpp_authors_read();

$slug = (string) ($_GET['slug'] ?? '');
$post = null;
$importNotice = false;

if ($slug !== '' && mpp_valid_slug($slug)) {
    $post = mpp_post_load($slug);
    if (!$post && is_file(mpp_post_php_path($slug))) {
        // Legacy post: import on first edit and keep the source from now on.
        $post = mpp_import_post($slug);
        if ($post) {
            mpp_post_save($post);
            $importNotice = true;
        }
    }
}

$isNew = $post === null;
$status = (string) ($post['status'] ?? 'draft');

// registry date (d-m-Y) -> value for <input type="date">
$dateValue = date('Y-m-d');
if (!$isNew) {
    $ts = false;
    $regDate = (string) ($post['registry']['date'] ?? '');
    if (preg_match('~^(\d{1,2})-(\d{1,2})-(\d{4})$~', $regDate, $m)) {
        $ts = mktime(12, 0, 0, (int) $m[2], (int) $m[1], (int) $m[3]);
    }
    if ($ts) {
        $dateValue = date('Y-m-d', $ts);
    }
}

// Internal link picker: main site pages + every blog post.
$linkTargets = [];
$skipPages = ['404', 'form-submission', 'thank-you', 'index', 'parking-page', 'blog'];
foreach (glob(ADMIN_SITE_ROOT . '/*.php') ?: [] as $pageFile) {
    $name = basename($pageFile, '.php');
    if (in_array($name, $skipPages, true)) {
        continue;
    }
    $linkTargets[] = [
        'label' => ucwords(str_replace('-', ' ', $name)),
        'href'  => MPP_BLOG_DOMAIN . '/' . $name . '/',
        'group' => 'Page',
    ];
}
$linkTargets[] = ['label' => 'Home', 'href' => MPP_BLOG_DOMAIN . '/', 'group' => 'Page'];
$linkTargets[] = ['label' => 'Blog', 'href' => MPP_BLOG_DOMAIN . '/blog/', 'group' => 'Page'];
foreach (mpp_post_index() as $entry) {
    if ($entry['status'] === 'published') {
        $linkTargets[] = [
            'label' => $entry['title'],
            'href'  => MPP_BLOG_DOMAIN . '/blogs/' . $entry['slug'] . '/',
            'group' => 'Blog post',
        ];
    }
}

$initial = [
    'isNew'         => $isNew,
    'status'        => $status,
    'slug'          => $isNew ? '' : (string) $post['slug'],
    'originalSlug'  => $isNew ? '' : (string) $post['slug'],
    'h1'            => (string) ($post['display']['h1'] ?? ''),
    'pageTitle'     => (string) ($post['seo']['page_title'] ?? ''),
    'pageDesc'      => (string) ($post['seo']['page_description'] ?? ''),
    'excerpt'       => (string) ($post['registry']['text'] ?? ''),
    'date'          => $dateValue,
    'image'         => (string) ($post['registry']['image'] ?? ''),
    'imageUrl'      => ($post['registry']['image'] ?? '') !== '' ? brand_asset((string) $post['registry']['image']) : '',
    'imageAlt'      => (string) ($post['registry']['alt'] ?? ''),
    'body'          => (string) ($post['body'] ?? ''),
    'faqs'          => array_values((array) ($post['faqs'] ?? [])),
    'authorName'    => (string) ($post['author']['name'] ?? ''),
    'authorBio'     => (string) ($post['author']['bio'] ?? ''),
    'links'         => $linkTargets,
    'authors'       => array_map(fn($a) => ['name' => (string) ($a['name'] ?? ''), 'bio' => (string) ($a['bio'] ?? '')], $authorsList),
];

admin_layout_start($user, 'posts', $isNew ? 'New Post' : 'Edit Post');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Blog editor</div>
        <h1><?= $isNew ? 'New <span>Post</span>' : 'Edit <span>Post</span>'; ?></h1>
    </div>
    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="posts.php">&larr; All posts</a>
</div>

<?php if ($importNotice): ?>
<div class="adm-alert adm-alert-info">This legacy post was imported into the editor. Its live file is untouched until you publish an update.</div>
<?php endif; ?>

<div class="adm-savebar adm-card" style="padding:14px 18px">
    <button class="adm-btn adm-btn-sm" id="btnSave" type="button">Save draft</button>
    <button class="adm-btn adm-btn-dark adm-btn-sm" id="btnPublish" type="button"><?= $status === 'published' ? 'Update' : 'Publish'; ?></button>
    <button class="adm-btn adm-btn-ghost adm-btn-sm" id="btnPreview" type="button">Preview</button>
    <?php if (!$isNew && $status === 'published'): ?>
    <button class="adm-btn adm-btn-ghost adm-btn-sm" id="btnUnpublish" type="button">Unpublish</button>
    <?php endif; ?>
    <?php if (!$isNew): ?>
    <button class="adm-btn adm-btn-danger adm-btn-sm" id="btnDelete" type="button">Delete</button>
    <?php endif; ?>
    <span class="status" id="saveStatus"><?= $isNew ? 'Not saved yet' : ('Status: ' . e($status)); ?></span>
</div>

<div class="adm-editor-grid">
    <div>
        <div class="adm-card" style="padding:14px 18px">
            <label class="adm-label" for="fH1" style="margin-top:0">Post title (H1) <span class="adm-counter" id="cntH1"></span></label>
            <input class="adm-input" type="text" id="fH1" placeholder="How to &hellip;">
        </div>

        <div class="adm-editorwrap" id="editorWrap">
            <div class="adm-toolbar" id="toolbar">
                <button class="adm-tool" type="button" data-cmd="h2" title="Heading 2">H2</button>
                <button class="adm-tool" type="button" data-cmd="h3" title="Heading 3">H3</button>
                <button class="adm-tool" type="button" data-cmd="h4" title="Heading 4">H4</button>
                <button class="adm-tool" type="button" data-cmd="p" title="Paragraph">&para;</button>
                <span class="sep"></span>
                <button class="adm-tool" type="button" data-cmd="bold" title="Bold (Ctrl+B)"><b>B</b></button>
                <button class="adm-tool" type="button" data-cmd="italic" title="Italic (Ctrl+I)"><i>I</i></button>
                <span class="sep"></span>
                <button class="adm-tool" type="button" data-cmd="ul" title="Bullet list">&bull; List</button>
                <button class="adm-tool" type="button" data-cmd="ol" title="Numbered list">1. List</button>
                <button class="adm-tool" type="button" data-cmd="quote" title="Tip / quote box">&ldquo;Tip&rdquo;</button>
                <span class="sep"></span>
                <button class="adm-tool" type="button" data-cmd="table" title="Insert table">Table</button>
                <button class="adm-tool" type="button" data-cmd="rowadd" title="Add table row">+Row</button>
                <button class="adm-tool" type="button" data-cmd="rowdel" title="Delete table row">-Row</button>
                <span class="sep"></span>
                <button class="adm-tool" type="button" data-cmd="link" title="Insert link">Link</button>
                <button class="adm-tool" type="button" data-cmd="unlink" title="Remove link">Unlink</button>
                <button class="adm-tool" type="button" data-cmd="image" title="Insert image">Img</button>
                <span class="sep"></span>
                <button class="adm-tool" type="button" data-cmd="undo" title="Undo (Ctrl+Z)">&#8630;</button>
                <button class="adm-tool" type="button" data-cmd="redo" title="Redo (Ctrl+Y)">&#8631;</button>
                <button class="adm-tool" type="button" data-cmd="src" id="btnSrc" title="HTML source">&lt;/&gt;</button>
            </div>
            <div class="adm-editor" id="editor" contenteditable="true" spellcheck="true"></div>
            <textarea class="adm-srcview" id="srcview" spellcheck="false"></textarea>
        </div>

        <div class="adm-card" style="margin-top:20px">
            <h2>FAQ <span>section</span></h2>
            <p class="adm-help" style="margin-bottom:10px">Optional. Rendered as the accordion under the article. Drag to reorder.</p>
            <div id="faqList"></div>
            <button class="adm-btn adm-btn-ghost adm-btn-sm" id="btnFaqAdd" type="button">+ Add FAQ</button>
        </div>

        <div class="adm-card">
            <h2>About the <span>author</span></h2>
            <label class="adm-label" for="fAuthorPick">Choose an author</label>
            <select class="adm-select" id="fAuthorPick">
                <option value="">— None (hide author box) —</option>
                <?php foreach ($authorsList as $a): ?>
                <option value="<?= e((string) ($a['name'] ?? '')); ?>"><?= e((string) ($a['name'] ?? '')); ?></option>
                <?php endforeach; ?>
                <option value="__custom">Custom (type below)</option>
            </select>
            <p class="adm-help">Manage the list in <a href="authors.php" target="_blank" rel="noopener">Authors</a>. Choosing one fills the fields below.</p>
            <label class="adm-label" for="fAuthorName">Author name</label>
            <input class="adm-input" type="text" id="fAuthorName" placeholder="Leave blank to omit the author box">
            <label class="adm-label" for="fAuthorBio">Author bio</label>
            <textarea class="adm-textarea" id="fAuthorBio" rows="3"></textarea>
        </div>
    </div>

    <div class="adm-editor-side">
        <div class="adm-card" style="padding:16px 18px">
            <h2>SEO</h2>
            <div class="adm-score">
                <div class="adm-score-ring">
                    <svg width="66" height="66" viewBox="0 0 66 66">
                        <circle class="bg" cx="33" cy="33" r="28" fill="none" stroke-width="7"></circle>
                        <circle class="fg" id="scoreArc" cx="33" cy="33" r="28" fill="none" stroke-width="7" stroke-linecap="round" stroke-dasharray="175.93" stroke-dashoffset="175.93"></circle>
                    </svg>
                    <div class="val" id="scoreVal">0</div>
                </div>
                <ul class="adm-checklist" id="seoChecklist"></ul>
            </div>

            <label class="adm-label" for="fPageTitle">Title tag <span class="adm-counter" id="cntTitle"></span></label>
            <input class="adm-input" type="text" id="fPageTitle" placeholder="Defaults to the H1">
            <label class="adm-label" for="fPageDesc">Meta description <span class="adm-counter" id="cntDesc"></span></label>
            <textarea class="adm-textarea" id="fPageDesc" rows="3"></textarea>
            <label class="adm-label" for="fSlug">Slug <span class="adm-counter" id="slugLock"></span></label>
            <input class="adm-input" type="text" id="fSlug" <?= $status === 'published' ? 'readonly' : ''; ?>>
            <label class="adm-label" for="fExcerpt">Card excerpt <span class="adm-counter" id="cntExcerpt"></span></label>
            <textarea class="adm-textarea" id="fExcerpt" rows="3" placeholder="Shown on the blog listing cards"></textarea>
            <label class="adm-label" for="fDate">Date</label>
            <input class="adm-input" type="date" id="fDate">
        </div>

        <div class="adm-card" style="padding:16px 18px">
            <h2>Featured <span>image</span></h2>
            <div class="adm-drop" id="imgDrop" tabindex="0">
                <img id="imgPreview" alt="" style="display:none">
                <span id="imgDropText">Drop an image here or click to choose.<br>Converted to WebP automatically.</span>
            </div>
            <input type="file" id="imgFile" accept="image/*" style="display:none">
            <label class="adm-label" for="fImageAlt">Image alt text</label>
            <input class="adm-input" type="text" id="fImageAlt">
        </div>
    </div>
</div>

<!-- link picker modal -->
<div class="adm-modal-backdrop" id="linkModal">
    <div class="adm-modal">
        <h3>Insert link</h3>
        <input class="adm-input" type="text" id="linkSearch" placeholder="Search pages and posts&hellip;">
        <ul class="adm-linklist" id="linkList"></ul>
        <label class="adm-label" for="linkCustom">Or paste a URL</label>
        <div class="adm-row">
            <input class="adm-input" type="text" id="linkCustom" placeholder="https://&hellip;">
            <button class="adm-btn adm-btn-sm" type="button" id="linkInsert" style="max-width:110px">Insert</button>
        </div>
        <p class="adm-auth-alt"><a href="#" id="linkCancel">Cancel</a></p>
    </div>
</div>

<script type="application/json" id="postData"><?= json_encode($initial, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?></script>
<script>window.ADMIN_CSRF = <?= json_encode(admin_csrf_token()); ?>;</script>
<?php admin_layout_end();
