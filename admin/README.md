# Melbourne Print & Publish — Admin Dashboard

A file-based CMS + SEO-safe blog publisher living at `/admin/`. No database is
required for the CMS itself (posts, portfolio, testimonials and settings are
stored as PHP/JSON files); the **Submissions** inbox reads the site's existing
`leads` MySQL table read-only.

---

## 1. What each section does & which files it writes

| Admin page | Purpose | Writes / reads |
|---|---|---|
| `dashboard.php` | Stats overview + latest posts + fresh leads | reads everything (no writes) |
| `posts.php` / `post-edit.php` / `post-actions.php` | Blog manager + WYSIWYG SEO editor + author picker | writes `blogs/<slug>.php`, `data/blogs_post.php` (the `$blogs` registry), `sitemap_index.xml`, `admin/data/posts/<slug>.json` (source of truth) |
| `authors.php` | Authors (bylines) CRUD — feeds the post editor's author picker | writes `admin/data/authors.json` |
| `submissions.php` | Leads inbox (search, filter, detail, read/star, CSV) | reads `leads` MySQL table; read/star flags in `admin/data/leads-state.json` |
| `portfolio.php` | Portfolio CRUD + reorder + cover upload→WebP (empty cover auto-fetches from the Amazon link's ASIN) | writes `data/portfolio_post.php`, uploads to `assets/images/portfolio/` |
| `websites.php` | Website portfolio CRUD + reorder + screenshot upload→WebP — feeds the public `/website-portfolio/` page | writes `data/website_portfolio_post.php`, uploads to `assets/images/website-portfolio/` |
| `testimonials.php` | Written testimonials CRUD + reorder | writes `data/testimonials_post.php` |
| `seo.php` | Per-page title / meta description / keywords / JSON-LD schema | writes `admin/data/seo-pages.json` |
| `settings.php` | **Site control:** contact & footer, socials, analytics IDs, SMTP, reCAPTCHA, lead database, custom scripts, Organization schema, lead recipients — plus a live **Connection status** card (DB / SMTP / reCAPTCHA, super-admin) | writes `admin/data/site-settings.json` (+ lead recipients in `admin/data/settings-overrides.json`) |
| `users.php` | Roles & users (RBAC) — **super-admin only** | writes `admin/data/users.json`, `admin/data/roles.json` |
| `account.php` | Own password + own 2FA — **any user** | writes `admin/data/users.json` |

### Public pages that consume this data
- **Homepage** (`index.php`) → recent blogs (`data/blogs_post.php`), portfolio
  slider (`data/portfolio_post.php`), testimonials slider (`data/testimonials_post.php`).
- **Blog listing** (`blog.php`) → `data/blogs_post.php` via `shortcode/blogs_list.php`.
- **Each post** (`blogs/<slug>.php`) → self-contained; recent-blogs widget reads the registry.
- **Portfolio** (`portfolios.php`), **Testimonials** (`testimonial.php`) → their data files.
- **Sitemap** (`sitemap_index.xml`) → one `<url>` per published post.

> Modules: Blog, Authors, Portfolio, Written Testimonials, Leads inbox, SEO &
> Meta, Site Settings. There is deliberately **no** video-testimonials module
> (the site has none).

---

## 1b. Site-wide dynamic controls (header / footer / SEO / scripts)

Everything the brand repeated across pages is now editable from the admin, and —
critically — **the public site renders byte-for-byte identically until you change
a value.** Each dynamic field falls back to the page's exact original markup until
you override it (`mpp_val()` / raw SEO passthrough), so migrating changed nothing.

- **Contact & footer** (Site Settings): phone, email, ABN, address + map link,
  footer tagline, top disclaimer bar, live-chat (Tawk.to) URL. Rendered by
  `includes/footer.php` + `includes/disclaimer.php` via `mpp_val()`.
- **Social links** (Site Settings): the six footer icons.
- **Analytics IDs** (Site Settings): GTM, GA4, google-site-verification — applied
  site-wide, including pages you haven't opened in the SEO manager.
- **SMTP** (Site Settings, super-admin): overrides the config defaults; blank = use config.
- **reCAPTCHA v3** (Site Settings, super-admin): add keys to protect the admin
  login and, if ticked, the public contact forms (`foam/*`). Off by default.
- **Custom scripts** (Site Settings, super-admin): raw code injected into
  `<head>` (`includes/style.php`), just after `<body>` (`includes/disclaimer.php`),
  and before `</body>` (`includes/footer.php`). Empty by default.
- **One central head, no per-page duplication.** The GTM + GA scripts, the
  google-site-verification tag and the geo/DC meta are **no longer copy-pasted
  into every page** — they render once from `mpp_seo_head()` (in
  `includes/seo-meta.php`), driven by Site Settings. The GTM `<noscript>` body
  fallback tracks the same setting via the analytics rewrite in `config.php`.
  Change an analytics ID once in the admin and it updates on every page,
  homepage included.
- **Per-page SEO** (`seo.php`): title tag, meta description, keywords and JSON-LD
  schema for every page live in `admin/data/seo-pages.json` and render through the
  central head. Each page keeps a `seed` snapshot of its original meta so
  "Reset to original" restores it. Page bodies are unchanged (byte-identical);
  only the repeated head boilerplate was centralised.
- **Organization schema** (Site Settings): an optional *additional* site-wide
  JSON-LD block, **off** by default.

Settings and SEO stores live in `admin/data/` (web-blocked). The public loader is
`includes/site-settings.php` (required early by `includes/config.php`), which also
sources the `SMTP_*` and `RECAPTCHA_*` constants.

### Dynamic sitemap & robots
`/sitemap_index.xml` and `/robots.txt` are served by `sitemap.php` and `robots.php`
(via `.htaccess` rewrites). The sitemap is generated live from the static-page list
(`admin/data/sitemap-static.json`) plus every published post in the registry, so
publishing/unpublishing a post updates the sitemap automatically — no manual edit.

### Security of the dynamic controls
- **Custom scripts, SMTP, reCAPTCHA secret, Organization schema, and the live-chat
  script URL** are editable by **super-admins only** — a lower-trust "settings"
  admin can change contact/social text but cannot inject code. Enforced server-side
  in `settings.php` (the save loop only visits super fields when `admin_is_super`).
- **Link fields** (socials, address map, and any href/src) are rendered through
  `mpp_val_url()`, which rejects `javascript:`/`data:`/`vbscript:` URIs (falls back
  to the safe original), with a second scheme check on save.
- **JSON-LD schema** is escaped (`mpp_jsonld_safe`, every `<` → `<`) so a schema
  value can never break out of its `<script>` tag.
- **Secrets** (SMTP app password, DB password): `includes/config.secret.php`
  (git-ignored, HTTP-denied) overrides them if present. If that file is missing
  after upload, `config.php` falls back to the built-in production values, so
  **the contact forms and leads DB keep working out of the box** — upload the
  whole folder and leads flow to the live `leads` table with no extra step.
  For best security, put your real credentials in `config.secret.php` and rotate
  them there.
- The lead-form redirect is host-locked (no open redirect off-site).

### Clean URLs (`.php` on localhost, clean on live)
The public site already uses clean URLs everywhere (nav, footer, body, sitemap,
canonical). On the live host `.htaccess` strips `.php` and adds trailing slashes;
on localhost the `.php` files still resolve directly for editing. The helper
`brand_link('/about-us')` returns `/about-us/` on live and the `.php` path on
localhost — use it for any new internal links you add.

> Author bylines: every post embeds its chosen author's name + bio in its own
> source and HTML, so the public author box is unchanged. The Authors page just
> makes those choices reusable; the editor's picker fills the fields from the list.

---

## 2. How the blog publisher stays SEO-safe

The generated `blogs/<slug>.php` is **byte-for-byte identical** to the brand's
hand-built post template (proven by an automated regeneration test against the
three current-template posts). Title tag, meta description, canonical, geo meta,
GTM/GA, the table-of-contents script, the FAQ accordion and the author box are all
reproduced exactly, so publishing through the admin never degrades SEO.

- **Legacy posts** (older WP-era template) import on first edit; republishing
  migrates them to the current template while preserving all body text and FAQs
  (verified: body text stable, FAQ counts preserved, idempotent).
- **Slug is locked once published** (changing it would break inbound links).
- **Unpublish** removes the post from the registry + sitemap and moves the file to
  `/trash` (never hard-deletes).

---

## 3. Upload / deploy list

Upload the whole `admin/` folder, plus these edited site files:

- `admin/**` (the entire dashboard)
- `.htaccess` — added an admin passthrough rule (`RewriteRule ^admin(/.*)?$ - [L]`)
- `robots.txt` — added `Disallow: /admin/`, `/blogs/admin-preview.php`, `/trash/`
- `includes/config.php` — added the settings-override merge block at the end

### PHP write permissions required (chmod 755 dirs / 644 files, PHP must be able to write):
- `blogs/` and `blogs/images/` (new posts + inline/featured images)
- `assets/images/` and `assets/images/portfolio/` (uploaded images → WebP)
- `data/` (`blogs_post.php`, `portfolio_post.php`, `testimonials_post.php`)
- `includes/` is only read; not written
- `sitemap_index.xml`
- `admin/data/` (JSON stores, backups) and `trash/` (auto-created on first unpublish)

### Environment
- PHP 8.2 with **GD** (`imagewebp`), **PDO MySQL**, **fileinfo**, **dom**, **mbstring** — all present on this server.
- Image uploads are re-encoded to WebP via GD (also strips any hidden payload). Without GD the original is kept but forced to an image extension (still safe).

---

## 4. Security model

- **Passwords:** bcrypt (`password_hash`/`password_verify`) in `admin/data/users.json`, which `admin/data/.htaccess` (`Require all denied`) blocks from the web.
- **Sessions:** dedicated cookie `mpp_admin_sess`, `HttpOnly`, `SameSite=Lax`, `Secure` on HTTPS, `session_regenerate_id(true)` on login.
- **CSRF:** per-session token, verified with `hash_equals` on every state-changing POST (form field or `X-CSRF-Token` header); returns **419** on mismatch.
- **Login throttle:** 5 failures per (IP + username) → 15-minute lockout; a separate throttle for the 2FA step.
- **reCAPTCHA v3 on login:** wired to the site's `verify_recaptcha()` with action `admin_login`. Currently no keys are configured, so it is inactive; add `RECAPTCHA_SITE_KEY` / `RECAPTCHA_SECRET_KEY` to `includes/config.php` to enable it. The captcha skip is gated on the **real peer IP** (`REMOTE_ADDR` loopback), never the spoofable Host header.
- **2FA (TOTP, RFC 6238):** optional per user; enrolled in *My Account* with a QR code + 8 single-use backup codes (bcrypt-hashed, shown once). Verified against the official RFC 6238 test vectors. Admins can reset another user's 2FA from *Roles & Users*.
- **RBAC:** every section is a module; each page calls `admin_require_module()` / `admin_require_admin()` / `admin_require_login()` at the top — access is enforced server-side (**403**), the nav only hides what you can't reach. The super-admin role is locked; custom roles can never be granted `*`; you can't delete the last super-admin, your own account, or a role that still has users; a user whose role vanished gets **zero** modules (fail-closed).
- **Content can't become code:** the article body passes a DOMDocument whitelist that removes `<script>`, `on*` handlers, `javascript:`/`data:` URLs **and PHP processing-instruction nodes** (the nested-`<?php?>` RCE), backed by a final `ukph_strip_php()` pass; FAQ answers get the same treatment. Every generated PHP file is linted (`php -l`) **before** it replaces the live file. (Proven: injected PHP payloads are written as inert text and never execute.)
- **Uploads:** MIME-sniffed (not by extension), size-capped (~8 MB), re-encoded to WebP, and every upload directory gets a `.htaccess` that disables script execution.
- **Every write to a live site file is backed up** to `admin/data/backups/` (last 20 kept) before an atomic replace.

---

## 5. Default accounts (change on first login)

| Username | Role | Password |
|---|---|---|
| `admin` | Super Admin (everything) | `MPP-0CF0-6e4f063c50a5` |
| `editor` | Blog Editor (Posts only) | `MPP-C3D6-cd788155cb54` |

Log in at `/admin/`. Change both passwords immediately (My Account), then enable 2FA.

---

## 6. Extra hardening (do this on the live server)

1. **Enable 2FA** for every admin account (My Account → Set up 2FA).
2. **cPanel → Directory Privacy** on the `/admin` folder — a second HTTP-auth wall in front of PHP:
   - cPanel → *Directory Privacy* → browse to `public_html/admin` → tick **"Password protect this directory"**, name it, **Save** → add a user + strong password under *Create User*.
3. **(Optional) IP allow-list** on `/admin` — in `admin/.htaccess` add:
   ```apache
   Require ip 203.0.113.0    # your office IP
   ```
4. **Move committed secrets** (SMTP / DB / reCAPTCHA in `includes/config.php`) into a separate `config.secret.php` that is `require`d and git-ignored.

### Post-deploy checks
- `https://yourdomain/admin/data/users.json` must return **403**.
- Submit one real form on the live domain and confirm it appears in **Submissions** and in `error_log` (email delivery depends on the host allowing outbound SMTP; the DB + inbox work regardless). Note: reCAPTCHA is domain-locked, so forms only pass on the live domain, not localhost.

---

## 7. Local development notes

- A local leads database (`melbelgx_leads`) with the production schema has been created under XAMPP MySQL so the Submissions inbox works locally.
- `.claude/launch.json` defines a `php -S 127.0.0.1:8811` server for previewing.
- On localhost the site keeps `.php` URLs (config detects loopback); on live, clean URLs apply and the admin passthrough rule keeps `/admin/*` working.
