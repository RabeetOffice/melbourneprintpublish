# Blog Admin System — Build Guide for Replicating Across Brands

**Purpose:** Hand this whole file to an AI coding assistant (Claude Code or similar)
working inside one of your brand sites. It contains everything needed to rebuild the
custom admin dashboard + SEO-safe blog publishing system, re-skinned to that brand.

**Reference implementation:** `ukpublishinghouse.co.uk` (the first build). All the
patterns below are proven there — copy them, don't reinvent them.

---

## 0. HOW TO USE THIS (read first)

> **Prompt to give the AI, verbatim:**
>
> "I want to add a custom admin dashboard + blog publishing system to this brand site,
> identical in features to the one described in `blog-system-create.md` but re-skinned
> to THIS brand's theme. Follow that guide's phases in order: discover the site first,
> extract this brand's design tokens, make the generated blog post BYTE-IDENTICAL to
> this brand's existing post template, then build the admin, then write and run the
> verification tests, then write the deploy README. Do not skip the security section or
> the 'known gotchas' — apply those fixes proactively. Work on the main checkout, lint
> every PHP file, and run a local PHP server to test end-to-end before telling me it's
> done."

**The big idea:** These brands are sister sites on a similar PHP template. The admin
*engine* (auth, stores, editor JS, publish pipeline, security) is the same everywhere.
**Only two things change per brand:**

1. **The admin's look** — `admin/assets/admin.css` design tokens, re-derived from the
   brand's stylesheet.
2. **The generated post template** — the PHP string in the post generator must match
   THAT brand's blog post HTML exactly (proven by byte-identical regeneration).

Everything else (PHP function names with `admin_*` / `ukph_*` prefixes, file layout,
JS) can be copied as-is. Keeping the code identical across brands makes maintenance easy.

### TWO RULES THAT OVERRIDE EVERYTHING ELSE

1. **Build ONLY what the brand site actually has — never add a section for a feature the
   site doesn't have.** Blog is the only guaranteed-present module. Portfolio, Testimonials
   (written/video), Authors, and even the Submissions inbox are **conditional**: include a
   manager *only if* you found that feature on the site during discovery (a data file it
   reads, a section it renders, a `leads` table it writes). If a brand has no portfolio,
   there is NO Portfolio admin page and NO Portfolio nav item and NO Portfolio dashboard
   stat. Do not invent features. When unsure whether a feature exists, ask the owner rather
   than assume. The nav, dashboard stats, and file list must reflect exactly what exists.

2. **The admin's design MUST match the brand's website theme.** This is not optional polish
   — it's a requirement. Re-derive every colour, font, radius, shadow and signature motif
   from the brand's own stylesheet (Phase 2) so the admin looks like it was always part of
   that brand. Never ship the reference brand's navy/crimson palette to a different brand.

---

## 1. WHAT YOU'RE BUILDING

A file-based CMS (no database required for the CMS itself) living at `/admin/`. **Every
module below except the blog is conditional — build it only if the brand site has that
feature (see Rule 1 above).**

A file-based CMS (no database required for the CMS itself) living at `/admin/`:

- **Blog post manager + SEO editor** — the centrepiece. A WYSIWYG editor that produces
  blog posts structurally identical to the brand's hand-built posts, so SEO is never
  degraded: title tag, meta description, canonical, JSON-LD schema, table of contents,
  internal links, FAQ section — all preserved. Generates `blogs/<slug>.php`, updates the
  central post registry, and updates the sitemap.
- **Dashboard** — stats overview; show ONLY the stats for modules this brand has.
- **Form submissions inbox** *(only if the site has a `leads` table / form handler)* — reads
  the brand's existing `leads` MySQL table (read-only), with search, filter, detail,
  read/star, CSV export. Graceful fallback when the DB is unreachable (e.g. localhost).
- **Authors manager** *(only if posts show an author/bio box)* — name + bio, drives the
  "About the Author" box.
- **Portfolio manager** *(only if the site has a portfolio)* — CRUD over the brand's
  portfolio data file. **Fully dynamic: supports uploading a custom cover image per item
  (auto-converted to WebP), with any auto-derived cover — e.g. from an Amazon ASIN — as the
  fallback when no image is uploaded.** Reorder, edit, delete.
- **Testimonials managers** *(only if the site has testimonials)* — written + video, CRUD
  over the brand's data files.
- **Settings** — safe site settings (contact, social, lead recipients) via a JSON
  override merged into config; user management; password change; 2FA.
- **Auth** — sessions, CSRF, login throttle, reCAPTCHA v3, optional TOTP 2FA.
- **Roles & module permissions (RBAC)** — every section is a "module". The owner creates
  custom roles (e.g. *Publisher* = Portfolio only, *Web Developer* = Author Sites only) and
  assigns users to them. A built-in locked super-admin role has everything. Each user also
  has a self-service Account page (own password + 2FA).
- Fully **responsive** (desktop + mobile).

---

## 2. PHASE 1 — DISCOVER THE TARGET BRAND (do not skip)

Before writing any code, map the site. The structure is similar across brands but details
differ. Read these and take notes:

**Blog anatomy (most important):**
- Find the most recent blog post file (usually `blogs/<slug>.php`). Read it COMPLETELY.
  This is your template. Note: the top PHP preamble (variables set, includes required, in
  what order), the `<head>` (which tags are per-post vs from a shared header include), the
  body structure, breadcrumb, hero, the article wrapper class, sidebar include, FAQ section
  markup, author box, recent-posts, CTA, the TOC `<script>`, footer include.
- Read the central blog index/registry (e.g. `includes/blog-data.php`): the `$blog_posts`
  array shape (`slug, title, excerpt, date, category, image, author, read`), and helper
  functions (`blog_get_post`, `blog_get_recent`, `blog_post_url`, `blog_format_date`).
- Read the schema include (e.g. `includes/blog-schema.php`) and the FAQ schema include.
- Read the blog listing page (`blogs.php`): how cards are rendered, featured slot,
  pagination/infinite-scroll.
- Read 2–3 OTHER older posts to learn the markup vocabulary actually used: tables
  (`<table class="...">`), blockquotes, internal-link class, inline images, legacy CTA
  boxes with inline styles, WP-export quirks (`<span style="font-weight:400">`).

**Theme tokens:**
- Read the main stylesheet(s) (`assets/css/*.css`). Extract the `:root` CSS variables
  (colors, fonts, shadows, radii), the font families and how they're loaded, button/card
  styles, section-label / eyebrow motifs, dividers, hover effects.
- Read the header include for nav structure, fonts, and which CSS/JS libraries load
  site-wide.

**Forms / leads pipeline:**
- Read the form handler (`form-submission.php`): the reCAPTCHA gate, validation, email
  send, and **the exact `CREATE TABLE leads (...)` and INSERT columns** — the admin
  submissions viewer must match THIS brand's columns (they may differ).
- Read `includes/recaptcha.php` (the reCAPTCHA helpers — reuse them for the admin login),
  `includes/smtp-mailer.php`, the config (`$DB`, `$LEAD`, `$RECAPTCHA`, `$SMTP`, `$CONTACT`,
  `$SOCIAL`).

**Content data + infra:**
- Find the portfolio / testimonials / video-testimonials / authors data files (often
  `includes/data/*.php`). Note their array shapes and any derived-field logic (e.g. Amazon
  cover from ASIN, YouTube ID from URL) — your writer must preserve that logic. **Whatever
  you DON'T find, you DON'T build (Rule 1)** — make an explicit list of which optional
  modules this brand has before writing any admin pages.
- Read `.htaccess` (clean-URL rewrite rules, localhost bypass, blocked file patterns,
  caching) and `robots.txt` and the sitemap (static file? what's the per-post `<url>` entry
  format?).

**Environment:**
- PHP version, and whether GD (`imagewebp`), PDO MySQL, and fileinfo extensions are enabled.

> Tip: for a large site, fan this discovery out to parallel sub-agents (one for blog
> anatomy, one for theme, one for forms, one for content/infra) and have each return a
> structured report. Then read the brand's newest post yourself before building the generator.

---

## 3. PHASE 2 — ADAPT THE THEME (required — see Rule 2)

Build `admin/assets/admin.css` from the brand's tokens. The reference admin uses a navy/
crimson/cream editorial look; **re-derive every value from the target brand instead — do
NOT reuse the reference palette.** The finished admin must visibly belong to this brand: a
stranger glancing at it should not be able to tell it's a bolt-on. Pull the exact hex
values, fonts, button shapes and motifs from the brand's live stylesheet.

Map the brand's palette into these admin CSS variables (keep the variable NAMES, change the
VALUES):

```css
:root{
  --adm-paper; --adm-cream; --adm-lilac; --adm-rule;        /* surfaces + borders */
  --adm-ink; --adm-ink-deep; --adm-ink-soft; --adm-text; --adm-muted;  /* text */
  --adm-accent; --adm-accent-deep;                          /* brand accent (was crimson) */
  --adm-gold; --adm-gold-lt; --adm-rose;                    /* secondary accents */
  --adm-navy-1; --adm-navy-2;                               /* dark sidebar gradient */
  --adm-green/-bg; --adm-amber/-bg;                         /* status chips (keep) */
  --adm-serif; --adm-sans; --adm-mono;                      /* the brand's fonts */
  --adm-sh-sm/-md/-lg;                                      /* shadows, tinted to brand ink */
  --adm-ease;                                               /* the brand's easing curve */
}
```

Reuse the brand's signature motifs in the admin chrome: the same button shape (e.g. pill
with arrow chip), the same eyebrow/section-label style, the same card hover lift, the same
heading style (e.g. serif with an italic accent word). The sidebar uses the brand's dark
surface + a thin accent→gold gradient hairline. Load the brand's actual fonts from Google
Fonts (or wherever the site loads them).

Goal: an admin that looks like it was always part of that brand — "in my theme and unique
sleek design."

---

## 4. PHASE 3 — BUILD THE ADMIN (file-by-file)

Create this structure. Function-name prefixes (`admin_`, `ukph_`) can stay identical across
brands.

```
admin/
  index.php              # redirect: logged-in → dashboard/posts, else → login
  login.php              # password + reCAPTCHA, then optional 2FA step
  logout.php
  dashboard.php          # admin-only: stats + latest posts + fresh leads
  posts.php              # list posts (registry + drafts), search/filter
  post-edit.php          # the editor page (admin + editor roles)
  post-actions.php       # AJAX endpoint: save/publish/unpublish/delete/preview/upload
  authors.php            # admin-only
  submissions.php        # module: submissions — leads inbox + CSV export
  portfolio.php          # module: portfolio
  testimonials.php       # module: testimonials (written + video)
  settings.php           # module: settings — contact/social/leads ONLY
  users.php              # super-admin only: roles + users management (RBAC)
  account.php            # any logged-in user: own password + own 2FA
  README.md              # deploy + security guide (write this last)
  assets/
    admin.css            # the re-skinned design system
    admin.js             # shared UI + the whole editor engine
  includes/
    auth.php             # session, RBAC (modules/roles/admin_can/require_module), CSRF, throttle, 2FA flow
    helpers.php          # atomic/locked file IO, backups, slugify, read-time, php-lint, webp upload
    layout.php           # sidebar shell; nav built from the user's accessible modules
    forbidden.php        # 403 page (links back to the user's own home)
    post-store.php       # registry, post sources, legacy import, sanitizer, generator, publish, sitemap, uploads
    content-store.php    # portfolio/testimonials/videos/authors read+regenerate
    leads-db.php         # read-only PDO reader for the leads table (+ admin read/star state)
    totp.php             # RFC 6238 TOTP (pure PHP) for 2FA
  data/                  # NOT web-accessible (see .htaccess below)
    .htaccess            # "Require all denied"
    roles.json           # roles: [{name,label,modules[],locked?}]; modules ['*'] = super-admin
    users.json           # accounts (bcrypt password_hash, role NAME, optional totp_*)
    posts/<slug>.json    # editable source of each post (admin's source of truth)
    settings-overrides.json
    leads-state.json     # admin read/star flags keyed by lead id
    login-attempts.json  # throttle
    backups/             # timestamped copies of every overwritten site file
```

**Data model — `admin/data/posts/<slug>.json`:**
```json
{
  "slug": "...", "status": "draft|published",
  "registry": { "title","excerpt","date","category","image","author","read" },
  "seo": { "page_title","page_description","page_keywords" },
  "body": "<sanitized article HTML>",
  "faqs": [ { "q":"...", "a":"..." } ],
  "read_auto": true,
  "source": "admin|imported", "created":"...", "updated":"..."
}
```

**Layout / shell:** sidebar with brand logo, nav items filtered by role, a user chip with
logout, a "view website" link. Admin pages MUST send `X-Robots-Tag: noindex`,
`X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`.

**File IO (helpers.php) — use these patterns:**
- `admin_json_read/write` with `flock`.
- `admin_atomic_write($path,$content)`: write to a temp file in the same dir, then
  `rename`. **On Windows, `rename` won't overwrite — `unlink` the target first.**
- `admin_backup_file($path)`: copy to `data/backups/<name>.<timestamp>.bak`, keep last 20.
- `admin_replace_site_file($path,$content)`: backup then atomic-write. Use for EVERY write
  to a live site file (post files, registry, data files, sitemap).
- `php_check_syntax_string($code)`: write to temp, run `php -l` via `exec`, return false on
  lint failure. **Lint generated PHP BEFORE replacing the live file** so a bad write can
  never take the site down. Degrade to "trust" if `exec` is disabled.

---

## 5. THE BLOG PUBLISH PIPELINE (the heart — get this exactly right)

**5.1 The generator must be byte-identical to the brand's template.**
Take the brand's newest hand-built post, and turn it into a PHP template string where only
the per-post bits are placeholders (`%%SLUG%%`, `%%PAGE_TITLE%%`, `%%PAGE_DESC%%`,
`%%PAGE_KEYWORDS%%`, `%%FAQS%%`, `%%BODY%%`, plus a guard block for preview mode). Keep the
TOC `<script>` and all include lines verbatim.

**Prove it:** write a test that imports a real existing post (parse its preamble vars +
`$faqs` + the inner HTML of the article element), regenerates it via your generator, and
asserts the output is **byte-identical** to the original file (normalise CRLF/LF). If it's
not identical, fix the template until it is. This is the single most important quality gate
— it guarantees you didn't degrade SEO.

**5.2 Per-post values → escaped into PHP string literals.**
`title`, `seo.*`, `faqs`, registry entries: escape with `admin_php_sq()` (escapes `\` and
`'` for single-quoted PHP literals). For double-quoted literals (e.g. testimonial review
text) use `addcslashes($s, "\\\"\$")`. This makes any `'; system(...)` breakout inert text.

**5.3 The article BODY is the only raw-HTML sink → it MUST be sanitized hard.**
Run editor HTML through a DOMDocument whitelist (`ukph_sanitize_body`):
- Whitelist tags: `p h2 h3 h4 ul ol li table thead tbody tr td th blockquote figure
  figcaption hr a img b strong i em u br span sub sup` + (if the brand uses them) `section
  div` for legacy inline-styled CTA boxes.
- Whitelist attributes per tag; strip `on*` handlers and `javascript:`/`data:` URLs;
  demote `h1→h2`, `h5/h6→h4`; unwrap unknown tags.
- **CRITICAL — remove processing-instruction nodes.** In the DOM walk, keep only element +
  text nodes; drop comments, CDATA, and **PHP processing instructions (`<?php ?>`)**. Left
  in place, a nested `<blockquote><?php ... ?></blockquote>` would be written verbatim into
  the `.php` file and execute (remote code execution). See gotcha #1.
- As a final belt-and-suspenders pass, run the serialized body through `ukph_strip_php()`
  which removes any `<? ... ?>` sequence. (See gotcha #2 for a PHP comment trap inside this
  function.)
- FAQ answers get their own DOM attribute-stripping pass (allow only validated `href` on
  `<a>`) PLUS `ukph_strip_php()` — because the template echoes FAQ answers raw.

**5.4 Publishing does four things (atomically, each backed up):**
1. Generate `blogs/<slug>.php`.
2. Upsert the registry entry in `includes/blog-data.php` (regenerate the whole file in the
   brand's house style; lint before replace).
3. Upsert the sitemap `<url>` entry (match the brand's exact format — clean URL with
   trailing slash, `lastmod`, `changefreq`, `priority`).
4. Mark the JSON source `published`.

**Unpublish** reverses 2–4 and moves the post file to `/trash` (never delete outright).
**Slug is locked once published** (changing it breaks SEO/inbound links).

**5.5 Legacy import.** On first edit of a pre-existing post (no JSON source yet), parse the
`.php` file: regex the preamble vars; token-validate and `eval` the `$faqs` literal; extract
the inner HTML of the article element. Set `read_auto=false` so imported posts keep their
hand-set read time. **Use a token-based `$faqs` extractor, not a lazy regex** — see gotcha #7.

**5.6 Read time — automatic.** Compute from body word count (whitespace tokens incl.
numbers, ~210 wpm, min 3) on every save. The editor shows it live and locked; a small "auto"
toggle lets a user override. Make the PHP word count match the JS counter (both
`\S+` tokens) so the live preview equals the saved value.

**5.7 Image uploads → WebP.** Write ONE reusable helper —
`admin_upload_image_webp($file, $relDir, $hint, $maxBytes)` in helpers.php — and call it
from everywhere images are uploaded (blog featured/inline images → `blogs/images/`,
portfolio covers → `assets/images/portfolio/`, etc.). It: finfo MIME-sniffs (not extension),
requires a real parseable image, caps size (~8 MB), slugifies the filename to an image
extension; with GD it downscales to max ~1920px wide and re-encodes to WebP (quality ~82) —
re-encoding also destroys any payload hidden in a valid image; without GD it keeps the
original (still safe — the name is forced to an image extension). Drop a `.htaccess` that
disables script execution in EVERY upload directory. Returns `[true, '<relDir>/<name>']` or
`[false, 'error']`.

**Portfolio covers (if the brand has a portfolio):** make the portfolio fully dynamic — each
item can have an uploaded `cover` (stored path) that the data file prefers over any
auto-derived cover (e.g. Amazon ASIN). Keep the derived cover as the fallback. The public
data file's render-time map becomes `image = cover ?: derived_cover(...)`, so the public
consumers need no change. The admin form is a multipart POST with a drag-drop cover field;
store the raw `cover` separately from the derived `image` so editing shows the right thing.

**Self-rendering data+markup includes (e.g. an "author websites / our work" showcase).** Some
brands keep a gallery as a single PHP file that holds the data array AND the HTML that renders
it AND a helper (e.g. a live-screenshot URL builder). To manage it: (a) READ the array by
`ob_start(); require $file; ob_end_clean();` so the include's HTML output is captured and
discarded while you keep the `$dataArray` it defined; (b) WRITE by **surgically replacing only
the array literal** with a regex like `~\$dataArray\s*=\s*\[.*?\R\];~s` (non-greedy, entries
end in `],` so the first line-start `];` is the array close) — never regenerate the whole file,
so the markup + helper stay byte-identical. Escape the replacement with
`addcslashes($block, '\\$')` because `$`/`\` are special in preg replacements. Lint before
write. Items can carry an optional uploaded image with a live-thumbnail fallback (same WebP
uploader). Prove it with an identity-write test: writing the same data back leaves everything
outside the array byte-identical.

**5.8 Internal-link picker.** Build the editor's link list from the brand's pages (e.g.
footer nav columns) + all blog posts, inserting the brand's internal-link class (e.g.
`decorated-link`). hrefs relative to `/blogs/`.

**5.9 Preview.** Generate a temp `blogs/admin-preview.php` guarded by the admin session
(returns 404 to everyone else), robots-disallowed.

---

## 6. SECURITY REQUIREMENTS (non-negotiable — wire them ALL)

- **Passwords:** `password_hash` (bcrypt), `password_verify`. Stored in `data/users.json`,
  which is web-blocked by `data/.htaccess` (`Require all denied`).
- **Sessions:** dedicated cookie name, `HttpOnly`, `SameSite=Lax`, `Secure` on HTTPS,
  `session_regenerate_id(true)` on login.
- **CSRF:** token in session; verify with `hash_equals` on EVERY state-changing POST (form
  field or `X-CSRF-Token` header). Return 419 on mismatch.
- **Login throttle:** 5 failed attempts per (IP+username) → 15-min lockout. Separate throttle
  key for the 2FA code step.
- **reCAPTCHA v3 on login:** reuse the brand's `recaptcha_field/loader/verify` with action
  `admin_login`. **Gate the captcha-skip on the real peer IP** (`REMOTE_ADDR` loopback
  check), NOT the spoofable `Host` header / `is_live()` — see gotcha #3.
- **2FA (TOTP, RFC 6238):** pure-PHP `totp.php` (base32, HMAC-SHA1, ±1 window). Optional per
  user, enrolled in Settings (QR via a CDN JS lib + manual key). 8 single-use backup codes
  (bcrypt-hashed, shown once). Two-step login (password→code). Admins can reset another
  user's 2FA. Verify your TOTP against the official RFC 6238 test vectors.
- **RBAC enforced server-side:** every section is a *module*; `auth.php` holds the module
  registry + `roles.json` (each role = a set of module keys; `['*']` = super-admin). Each page
  calls `admin_require_module('<key>')` at the very top (before any work); `users.php` calls
  `admin_require_admin()`; `account.php` calls `admin_require_login()`. The nav is built from
  the user's accessible modules, but access is enforced server-side (403), never just hidden.
  Guardrails the role/user editor MUST have: the super-admin role is locked (no edit/delete/
  rename); custom roles can NEVER be granted `*` (build the module list from checkboxes only,
  never accept `*` from input); you can't delete/demote the last super-admin, delete a role
  that still has users, or delete your own account; a user whose role no longer exists gets
  **zero** modules (fail-closed, never fail-open). Validate `modules[]` with
  `array_intersect($input, admin_module_keys())`.
- **Content can't become code:** §5.3 (PI-node removal + `ukph_strip_php`) + linting
  generated files. This is the property that makes a file-writing CMS safe.
- **Uploads:** §5.7 hardening + `blogs/images/.htaccess`.
- **Settings input:** `strip_tags` on stored values; public templates must escape with the
  brand's `e()` helper. Check the brand's contact-page template actually escapes address
  fields (the reference brand had one unescaped sink — fix any you find).
- **`.htaccess`:** add an admin passthrough so clean-URL rewrites don't 301 admin pages —
  immediately after the localhost bypass: `RewriteRule ^admin(/.*)?$ - [L]`.
- **robots.txt:** `Disallow: /admin/` and the preview file.

---

## 7. FEATURE CHECKLIST (tick every box)

- [ ] Login: password + reCAPTCHA + throttle; optional 2FA second step; backup codes.
- [ ] Dashboard: live counts (posts, drafts, leads 7/30-day, portfolio, testimonials).
- [ ] Posts list: thumbnails, status chips, search + category + status filter.
- [ ] Editor — SEO panel: title tag + meta description with live char counters, keywords,
      slug (auto from title, locked after publish), excerpt, category, author, date,
      auto read-time with override toggle, live SEO checklist + score ring.
- [ ] Editor — content: H2/H3/H4, bold/italic, ul/ol, blockquote (the brand's "tip" style),
      tables (the brand's table class) with add/delete row, inline images (→WebP),
      internal-link picker (pages + posts), HTML source view, paste cleanup (incl. Google
      Docs — gotcha #4), undo/redo, Ctrl+S save.
- [ ] Editor — FAQ builder (reorder/add/remove), featured image drag-drop (→WebP).
- [ ] Draft autosave (45s), Preview, Publish, Update, Unpublish, Delete.
- [ ] *(if the site has them)* Authors manager (name+bio, fallback bio, post counts).
- [ ] *(if the site has a leads table)* Submissions inbox (search, form-type filter, detail,
      read/star, CSV export, DB-down fallback notice).
- [ ] *(if the site has a portfolio)* Portfolio manager with reorder + **custom cover image
      upload per item (→WebP), preferred over any auto-derived cover**.
- [ ] *(if the site has testimonials)* Testimonials (written + video) managers with reorder.
- [ ] Settings (module): contact/social/lead-recipients (JSON override merged in config).
      Show only the setting groups relevant to this brand.
- [ ] Roles & Users (super-admin only): create/edit/delete roles with module checkboxes;
      create/edit/delete users + assign role; reset password; reset 2FA; all guardrails above.
- [ ] My Account (any user): own password change + own 2FA (TOTP setup/disable/backup codes).
- [ ] Login: password + reCAPTCHA + throttle; optional 2FA step; after login land on the
      user's first accessible module (`admin_home_url()`), never a 403.
- [ ] Responsive: ≤880px sidebar → sticky top bar + hamburger; tables scroll; inputs 16px
      (no iOS zoom); editor stacks single-column.

---

## 8. PHASE 4 — VERIFY (write and run these tests)

Spin up a local PHP server (`php -S 127.0.0.1:PORT -t <siteroot>`) and test for real. Lint
every PHP file (`php -l`). Write these checks (PowerShell/bash + small PHP harnesses):

1. **Byte-identical regeneration** — import a real post, regenerate, assert identical. Also
   assert the registry data is field-for-field identical to the original after regeneration.
2. **E2E publish flow** — login → CSRF rejection → save draft → sanitizer strips
   `<script>`/`on*`/PHP → preview (admin-only, 404 for public) → publish → generated file
   lints + has BlogPosting/FAQ schema + canonical + TOC + lists on blogs.php → update →
   slug-change-on-published rejected → unpublish (file moved, registry+sitemap cleaned) →
   delete → editor role blocked from admin-only pages.
3. **RCE probe** — feed `<?php ?>`, `<?=`, short-tags, nested-in-blockquote, in-attribute
   payloads through publish, then EXECUTE the generated file and assert no attacker code runs.
4. **Leads DB + admin** — create a local `leads` table mirroring the brand's schema, write
   via the brand's exact INSERT, read via the admin's reader functions; assert columns match,
   newest-first, search + filter + counts work, apostrophes/unicode intact.
5. **TOTP** — assert against the RFC 6238 published test vectors; verify ±1 window, backup
   code consume + reuse-rejection; full two-step login over HTTP.
6. **Read time** — word count incl. numbers; formula; auto recompute vs manual override;
   PHP matches the editor's live JS formula.
7. **Responsive** — at 375px: no horizontal overflow on any page, hamburger opens menu,
   editor single-column, toolbar fits.
8. **Content round-trips** *(for each optional module the brand has)* — portfolio/
   testimonials/authors: write then reload, assert all existing items are byte-for-byte
   intact (apostrophes/unicode), the regenerated data file lints, and the public page still
   renders. For portfolio: assert an uploaded cover lands as WebP, is preferred over the
   auto-derived cover, and an item with no upload still falls back correctly.
9. **Scope check** — confirm the nav, dashboard stats, and admin/ file list contain NO module
   the brand site doesn't have (Rule 1), and the admin CSS uses the brand's palette, not the
   reference brand's (Rule 2).
10. **Adversarial review** — have independent agents hunt for auth bypass, XSS, RCE, path
   traversal, upload bypass, template-fidelity drift, and JS editor bugs; verify each finding
   before fixing. Clean up all test artifacts (preview file, trash/backups/test posts, test DB).

---

## 9. PHASE 5 — DEPLOY & DOCUMENT

Write `admin/README.md` covering: how everything connects (which admin page writes which
file, which public pages consume it — call out the homepage data sources), the exact upload
list, PHP write-permission needs (`blogs/`, `blogs/images/`, `includes/`, `includes/data/`,
`admin/data/`, sitemap, `trash/`), GD note, the security model, default accounts, and the
extra hardening:

- **Turn on 2FA** for every admin.
- **cPanel Directory Privacy** on the `/admin` folder (a second HTTP-auth wall in front of
  PHP) — give step-by-step cPanel instructions.
- Optional **IP allow-list** on `/admin`.
- **Move committed secrets** (SMTP/DB/reCAPTCHA in config) into a gitignored file.

Confirm on live: `/admin/data/users.json` returns 403; submit one real form and check the
inbox + `error_log` (email delivery depends on the host allowing outbound SMTP — the DB +
admin inbox work regardless).

---

## 10. KNOWN GOTCHAS (fix these proactively — each cost real debugging)

1. **Nested PHP tags survive a naive HTML sanitizer → RCE.** DOMDocument turns `<?php ?>`
   into a processing-instruction node. A walk that only handles element/comment nodes leaves
   PIs in place; serializing a kept element re-emits them into the `.php` file. Fix: remove
   all non-element/non-text nodes in the walk, AND run a final `ukph_strip_php()`.
2. **`?>` inside a `//` PHP comment closes the PHP block.** Writing
   `// strip <? ... ?> tags` in your code silently ends PHP and breaks the file (parse error
   surfaces somewhere else). Never put a literal `?>` in a `//` or `#` comment. (Block
   comments `/* */` are safe.)
3. **`Host`-header captcha bypass.** `is_live()` reads `HTTP_HOST` (spoofable). Don't gate
   security on it. Use `REMOTE_ADDR` loopback check (`127.0.0.1`/`::1`/`::ffff:127.0.0.1`)
   to detect local dev; everything else enforces the captcha.
4. **Google Docs paste makes everything bold.** Docs wraps the clipboard in
   `<b style="font-weight:normal" id="docs-internal-guid-…">` and expresses real bold/italic
   as `<span style="font-weight:700">`. In paste cleanup: unwrap a `b`/`strong` whose style
   is `font-weight:normal` or whose id starts `docs-internal-guid`; convert
   `font-weight:600-900` spans → `<strong>` and `font-style:italic` spans → `<em>` BEFORE
   stripping attributes.
5. **Click-select-image then Backspace deletes the image silently and un-undoably.** Clear
   the image "selected" class on any non-Delete keypress / selectionchange; delete via
   `execCommand('delete')` (so it's on the undo stack), not `node.remove()`.
6. **Autosave/Preview while in HTML source view yanks the user out.** Don't toggle the view
   to read the body — read from whichever view is active without flipping it.
7. **`$faqs` extraction truncates on `];` inside an answer.** A lazy regex `\[.*?\]` stops at
   the first `]`. Use a token-based scan that tracks bracket depth (strings are atomic
   tokens) so an answer containing `];` (e.g. "see [1]; …") doesn't break extraction.
8. **Legacy inline-styled CTA boxes get flattened.** If the brand has posts with
   `<section><div style="…">` CTA blocks, whitelist `section`/`div` + a safe inline-style
   filter (block `url()`, `expression`, `behavior`, `@import`) so importing+republishing
   doesn't destroy them.
9. **Testimonials regeneration can drop the platform-strip block.** If you preserve part of a
   data file by regex-scraping a marker comment, FAIL CLOSED if the marker is missing rather
   than writing a file with the variable dropped.
10. **Settings can't blank a field.** If you `array_filter` empty values out of the override,
    a user can never clear a field (it reverts to the config default). Keep empty strings in
    the override so blanking works (e.g. empty WhatsApp hides the badge).
11. **Windows `rename()` won't overwrite.** In `admin_atomic_write`, `unlink` the target
    first on Windows before `rename`.
12. **reCAPTCHA blocks forms on localhost.** The site key is domain-locked, so local form
    submits fail with `?form_status=captcha`. Expected — test forms on the live domain. (This
    is also why the admin login skips captcha for loopback requests.)

---

## 11. DEFAULTS & FIRST RUN

- Seed `data/users.json` with one `admin` and one `editor` account (random strong
  passwords, bcrypt-hashed). Tell the owner the credentials and to change them on first login.
- The `editor` account is for the blog uploader — it sees ONLY the Posts pages.
- Seed authors/portfolio/testimonials managers from the brand's existing data files so
  nothing changes on the public site until the owner edits something.

**Done = ** every checklist box ticked, every test in §8 passing, `php -l` clean on all files,
the public site renders unchanged, and `admin/README.md` written. Then summarize for the
owner: how to log in, what each section does, the deploy steps, and the security posture.
