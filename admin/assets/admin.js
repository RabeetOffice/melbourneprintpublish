/* Melbourne Print & Publish - admin UI + blog editor engine (vanilla JS). */
(function () {
    'use strict';

    /* ------------------------------------------------------ shared shell */
    var burger = document.getElementById('admBurger');
    var sidebar = document.getElementById('admSidebar');
    var backdrop = document.getElementById('admBackdrop');
    function closeNav() {
        if (sidebar) { sidebar.classList.remove('is-open'); }
        if (burger) { burger.classList.remove('is-open'); burger.setAttribute('aria-expanded', 'false'); }
        if (backdrop) { backdrop.classList.remove('is-open'); }
    }
    if (burger && sidebar) {
        burger.addEventListener('click', function () {
            var open = sidebar.classList.toggle('is-open');
            burger.classList.toggle('is-open', open);
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (backdrop) { backdrop.classList.toggle('is-open', open); }
        });
        if (backdrop) { backdrop.addEventListener('click', closeNav); }
    }

    /* ---------------------------------------------------------- editor */
    var dataEl = document.getElementById('postData');
    if (!dataEl) { return; }

    var data = JSON.parse(dataEl.textContent);
    var editor = document.getElementById('editor');
    var srcview = document.getElementById('srcview');
    var wrap = document.getElementById('editorWrap');
    var slugTouched = !data.isNew;
    var dirty = false;
    var currentStatus = data.status;

    var $ = function (id) { return document.getElementById(id); };
    var fields = {
        h1: $('fH1'), pageTitle: $('fPageTitle'), pageDesc: $('fPageDesc'),
        slug: $('fSlug'), excerpt: $('fExcerpt'), date: $('fDate'),
        imageAlt: $('fImageAlt'), authorName: $('fAuthorName'), authorBio: $('fAuthorBio')
    };

    fields.h1.value = data.h1;
    fields.pageTitle.value = data.pageTitle;
    fields.pageDesc.value = data.pageDesc;
    fields.slug.value = data.slug;
    fields.excerpt.value = data.excerpt;
    fields.date.value = data.date;
    fields.imageAlt.value = data.imageAlt;
    fields.authorName.value = data.authorName;
    fields.authorBio.value = data.authorBio;
    editor.innerHTML = data.body;

    // Author picker: fill name+bio from the managed authors list.
    var authorPick = $('fAuthorPick');
    if (authorPick) {
        var authors = data.authors || [];
        // Reflect current value: match an existing author, else "custom"/none.
        if (data.authorName) {
            var match = authors.some(function (a) { return a.name === data.authorName; });
            authorPick.value = match ? data.authorName : '__custom';
        }
        authorPick.addEventListener('change', function () {
            var v = this.value;
            if (v === '' ) { fields.authorName.value = ''; fields.authorBio.value = ''; }
            else if (v === '__custom') { fields.authorName.focus(); }
            else {
                var a = authors.filter(function (x) { return x.name === v; })[0];
                if (a) { fields.authorName.value = a.name; fields.authorBio.value = a.bio || ''; }
            }
            markDirty();
        });
    }

    var featuredImage = data.image;
    if (data.imageUrl) {
        $('imgPreview').src = data.imageUrl;
        $('imgPreview').style.display = 'block';
        $('imgDropText').innerHTML = 'Click or drop to replace.';
    }

    function markDirty() { dirty = true; setStatus('Unsaved changes'); }
    function setStatus(msg) { $('saveStatus').textContent = msg; }

    /* Gotcha: never flip the view to read the body - read whichever is live. */
    function getBodyHtml() {
        return wrap.classList.contains('src-mode') ? srcview.value : editor.innerHTML;
    }
    function setBodyHtml(html) {
        if (wrap.classList.contains('src-mode')) { srcview.value = html; }
        else { editor.innerHTML = html; }
    }

    /* --------------------------------------------------------- slug sync */
    function slugify(text) {
        return text.toLowerCase()
            .replace(/['"‘’“”]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
    fields.h1.addEventListener('input', function () {
        if (!slugTouched) { fields.slug.value = slugify(fields.h1.value); }
        refreshMeta(); markDirty();
    });
    fields.slug.addEventListener('input', function () { slugTouched = true; refreshMeta(); markDirty(); });
    if (currentStatus === 'published') {
        $('slugLock').textContent = 'locked (published)';
    }

    /* ----------------------------------------------------- char counters */
    function counter(el, target, ideal, max) {
        var n = el.value.length;
        target.textContent = n + '/' + max;
        target.classList.toggle('over', n > max);
    }
    function refreshMeta() {
        counter(fields.pageTitle, $('cntTitle'), 50, 60);
        counter(fields.pageDesc, $('cntDesc'), 140, 160);
        counter(fields.excerpt, $('cntExcerpt'), 120, 170);
        counter(fields.h1, $('cntH1'), 50, 70);
        refreshChecklist();
    }
    ['pageTitle', 'pageDesc', 'excerpt', 'imageAlt', 'authorName', 'authorBio', 'date'].forEach(function (key) {
        fields[key].addEventListener('input', function () { refreshMeta(); markDirty(); });
    });

    /* -------------------------------------------------- SEO checklist */
    var CHECKS = [
        { label: 'Title tag 30–60 chars', test: function () { var n = (fields.pageTitle.value || fields.h1.value).length; return n >= 30 && n <= 60; } },
        { label: 'Meta description 70–160 chars', test: function () { var n = fields.pageDesc.value.length; return n >= 70 && n <= 160; } },
        { label: 'H1 present', test: function () { return fields.h1.value.trim().length > 3; } },
        { label: 'Slug set', test: function () { return /^[a-z0-9-]{3,}$/.test(fields.slug.value); } },
        { label: 'Body 300+ words', test: function () { return wordCount() >= 300; } },
        { label: 'At least one H2', test: function () { return /<h2\b/i.test(getBodyHtml()); } },
        { label: 'Internal link in body', test: function () { return /href="(?:https?:\/\/melbourneprintpublish\.com\.au|\/)/i.test(getBodyHtml()); } },
        { label: 'Featured image + alt text', test: function () { return featuredImage !== '' && fields.imageAlt.value.trim() !== ''; } },
        { label: 'Card excerpt written', test: function () { return fields.excerpt.value.trim().length >= 40; } }
    ];
    function wordCount() {
        var tmp = document.createElement('div');
        tmp.innerHTML = getBodyHtml();
        var text = tmp.textContent || '';
        var words = text.trim().split(/\s+/).filter(function (w) { return w.length; });
        return words.length;
    }
    function refreshChecklist() {
        var list = $('seoChecklist');
        list.innerHTML = '';
        var passed = 0;
        CHECKS.forEach(function (check) {
            var ok = false;
            try { ok = check.test(); } catch (e) { ok = false; }
            if (ok) { passed++; }
            var li = document.createElement('li');
            li.textContent = check.label;
            if (ok) { li.className = 'ok'; }
            list.appendChild(li);
        });
        var score = Math.round(passed / CHECKS.length * 100);
        $('scoreVal').textContent = score;
        var arc = $('scoreArc');
        var circ = 175.93;
        arc.style.strokeDashoffset = String(circ - circ * score / 100);
        arc.style.stroke = score >= 80 ? '#16a34a' : (score >= 50 ? '#ff7f3e' : '#dc2626');
    }

    /* ------------------------------------------------------ toolbar cmds */
    function exec(cmd, value) {
        editor.focus();
        document.execCommand(cmd, false, value || null);
        markDirty();
        refreshChecklist();
    }
    function closestInEditor(node, selector) {
        while (node && node !== editor) {
            if (node.nodeType === 1 && node.matches(selector)) { return node; }
            node = node.parentNode;
        }
        return null;
    }
    function selectionNode() {
        var sel = window.getSelection();
        return sel.rangeCount ? sel.getRangeAt(0).startContainer : null;
    }

    document.getElementById('toolbar').addEventListener('click', function (ev) {
        var btn = ev.target.closest('button[data-cmd]');
        if (!btn) { return; }
        ev.preventDefault();
        var cmd = btn.getAttribute('data-cmd');
        if (wrap.classList.contains('src-mode') && cmd !== 'src') { return; }
        switch (cmd) {
            case 'h2': exec('formatBlock', '<h2>'); break;
            case 'h3': exec('formatBlock', '<h3>'); break;
            case 'h4': exec('formatBlock', '<h4>'); break;
            case 'p': exec('formatBlock', '<p>'); break;
            case 'bold': exec('bold'); break;
            case 'italic': exec('italic'); break;
            case 'ul': exec('insertUnorderedList'); break;
            case 'ol': exec('insertOrderedList'); break;
            case 'quote': exec('formatBlock', '<blockquote>'); break;
            case 'table': insertTable(); break;
            case 'rowadd': tableRow(1); break;
            case 'rowdel': tableRow(-1); break;
            case 'link': openLinkModal(); break;
            case 'unlink': exec('unlink'); break;
            case 'image': $('inlineImgFile').click(); break;
            case 'undo': exec('undo'); break;
            case 'redo': exec('redo'); break;
            case 'src': toggleSrc(); break;
        }
    });

    function toggleSrc() {
        var on = wrap.classList.toggle('src-mode');
        $('btnSrc').classList.toggle('is-on', on);
        if (on) { srcview.value = prettyHtml(editor.innerHTML); }
        else { editor.innerHTML = srcview.value; }
        refreshChecklist();
    }
    function prettyHtml(html) {
        return html.replace(/(<\/(?:p|h2|h3|h4|ul|ol|li|table|thead|tbody|tr|blockquote|div|figure)>)(<)/g, '$1\n$2');
    }

    function insertTable() {
        var cols = parseInt(prompt('Columns?', '3'), 10);
        var rows = parseInt(prompt('Rows (excluding header)?', '3'), 10);
        if (!cols || !rows || cols < 1 || rows < 1 || cols > 10 || rows > 50) { return; }
        var html = '<table class="table-responsive"><thead><tr>';
        for (var c = 0; c < cols; c++) { html += '<th>Heading</th>'; }
        html += '</tr></thead><tbody>';
        for (var r = 0; r < rows; r++) {
            html += '<tr>';
            for (var c2 = 0; c2 < cols; c2++) { html += '<td><br></td>'; }
            html += '</tr>';
        }
        html += '</tbody></table><p><br></p>';
        exec('insertHTML', html);
    }
    function tableRow(delta) {
        var cell = closestInEditor(selectionNode(), 'td,th');
        if (!cell) { alert('Click inside a table first.'); return; }
        var row = cell.parentNode;
        if (delta > 0) {
            var clone = row.cloneNode(true);
            Array.prototype.forEach.call(clone.children, function (c) { c.innerHTML = '<br>'; });
            row.parentNode.insertBefore(clone, row.nextSibling);
        } else {
            var body = row.parentNode;
            if (body.rows ? body.rows.length > 1 : body.children.length > 1) { row.remove(); }
        }
        markDirty();
    }

    /* ------------------------------------------------------- link modal */
    var linkModal = $('linkModal');
    var savedRange = null;
    function openLinkModal() {
        var sel = window.getSelection();
        if (!sel.rangeCount || sel.isCollapsed) { alert('Select the text to link first.'); return; }
        savedRange = sel.getRangeAt(0).cloneRange();
        renderLinkList('');
        $('linkSearch').value = '';
        $('linkCustom').value = '';
        linkModal.classList.add('is-open');
        $('linkSearch').focus();
    }
    function closeLinkModal() { linkModal.classList.remove('is-open'); }
    function renderLinkList(query) {
        var list = $('linkList');
        list.innerHTML = '';
        data.links.filter(function (link) {
            return !query || link.label.toLowerCase().indexOf(query) !== -1 || link.href.toLowerCase().indexOf(query) !== -1;
        }).slice(0, 40).forEach(function (link) {
            var li = document.createElement('li');
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.innerHTML = '<span></span><small></small>';
            btn.firstChild.textContent = link.label;
            btn.lastChild.textContent = link.group + ' · ' + link.href;
            btn.addEventListener('click', function () { insertLink(link.href); });
            li.appendChild(btn);
            list.appendChild(li);
        });
    }
    function insertLink(href) {
        closeLinkModal();
        if (!savedRange) { return; }
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(savedRange);
        editor.focus();
        document.execCommand('createLink', false, href);
        markDirty();
        refreshChecklist();
    }
    $('linkSearch').addEventListener('input', function () { renderLinkList(this.value.toLowerCase()); });
    $('linkInsert').addEventListener('click', function () {
        var url = $('linkCustom').value.trim();
        if (url) { insertLink(url); }
    });
    $('linkCancel').addEventListener('click', function (ev) { ev.preventDefault(); closeLinkModal(); });
    linkModal.addEventListener('click', function (ev) { if (ev.target === linkModal) { closeLinkModal(); } });

    /* ------------------------------------------------- image behaviours */
    // Hidden input for inline images.
    var inlineInput = document.createElement('input');
    inlineInput.type = 'file';
    inlineInput.accept = 'image/*';
    inlineInput.id = 'inlineImgFile';
    inlineInput.style.display = 'none';
    document.body.appendChild(inlineInput);
    inlineInput.addEventListener('change', function () {
        if (!this.files.length) { return; }
        uploadImage(this.files[0], fields.h1.value + '-inline', function (res) {
            exec('insertHTML', '<img src="' + res.url + '" alt="">');
        });
        this.value = '';
    });

    // Click-to-select an image; Backspace deletes it via execCommand so the
    // deletion lands on the undo stack (gotcha #5).
    var selectedImg = null;
    function clearImgSelection() {
        if (selectedImg) { selectedImg.classList.remove('is-selected'); selectedImg = null; }
    }
    editor.addEventListener('click', function (ev) {
        clearImgSelection();
        if (ev.target.tagName === 'IMG') {
            selectedImg = ev.target;
            selectedImg.classList.add('is-selected');
            var range = document.createRange();
            range.selectNode(selectedImg);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
    });
    editor.addEventListener('keydown', function (ev) {
        if (selectedImg && (ev.key === 'Backspace' || ev.key === 'Delete')) {
            ev.preventDefault();
            var img = selectedImg;
            clearImgSelection();
            var range = document.createRange();
            range.selectNode(img);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            document.execCommand('delete');
            markDirty();
        } else if (selectedImg) {
            clearImgSelection();
        }
    });
    document.addEventListener('selectionchange', function () {
        if (!selectedImg) { return; }
        var sel = window.getSelection();
        if (!sel.rangeCount) { clearImgSelection(); return; }
        var node = sel.getRangeAt(0).startContainer;
        if (node !== selectedImg && (node.nodeType !== 1 || !node.contains(selectedImg))) {
            clearImgSelection();
        }
    });

    /* ------------------------------------------------------- paste clean */
    editor.addEventListener('paste', function (ev) {
        var html = ev.clipboardData && ev.clipboardData.getData('text/html');
        if (!html) { return; } // plain text: let the browser handle it
        ev.preventDefault();
        document.execCommand('insertHTML', false, cleanPastedHtml(html));
        markDirty();
        refreshChecklist();
    });

    function cleanPastedHtml(html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var root = doc.body;

        // Google Docs: unwrap the fake-bold wrapper, convert styled spans
        // BEFORE attributes are stripped (gotcha #4).
        root.querySelectorAll('b,strong').forEach(function (el) {
            var style = (el.getAttribute('style') || '').toLowerCase();
            var id = el.id || '';
            if (style.indexOf('font-weight:normal') !== -1 || style.indexOf('font-weight: normal') !== -1
                || id.indexOf('docs-internal-guid') === 0) {
                unwrap(el);
            }
        });
        root.querySelectorAll('span').forEach(function (el) {
            var style = (el.getAttribute('style') || '').toLowerCase();
            var weight = /font-weight\s*:\s*(bold|[6-9]00)/.test(style);
            var italic = /font-style\s*:\s*italic/.test(style);
            if (weight || italic) {
                var repl = doc.createElement(weight ? 'strong' : 'em');
                while (el.firstChild) { repl.appendChild(el.firstChild); }
                if (weight && italic) {
                    var em = doc.createElement('em');
                    while (repl.firstChild) { em.appendChild(repl.firstChild); }
                    repl.appendChild(em);
                }
                el.parentNode.replaceChild(repl, el);
            }
        });

        var ALLOWED = { P: 1, H1: 1, H2: 1, H3: 1, H4: 1, H5: 1, H6: 1, UL: 1, OL: 1, LI: 1, TABLE: 1, THEAD: 1, TBODY: 1, TR: 1, TD: 1, TH: 1, BLOCKQUOTE: 1, A: 1, IMG: 1, B: 1, STRONG: 1, I: 1, EM: 1, U: 1, BR: 1, HR: 1, FIGURE: 1, FIGCAPTION: 1, SUB: 1, SUP: 1 };
        var KEEP_ATTR = { A: ['href'], IMG: ['src', 'alt'], TD: ['colspan', 'rowspan'], TH: ['colspan', 'rowspan'] };

        function walk(node) {
            Array.prototype.slice.call(node.childNodes).forEach(function (child) {
                if (child.nodeType === 8) { child.remove(); return; } // comments
                if (child.nodeType !== 1) { return; }
                var tag = child.tagName;
                if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'META' || tag === 'LINK') { child.remove(); return; }
                if (!ALLOWED[tag]) { walk(child); unwrap(child); return; }
                var keep = KEEP_ATTR[tag] || [];
                Array.prototype.slice.call(child.attributes).forEach(function (attr) {
                    if (keep.indexOf(attr.name.toLowerCase()) === -1) { child.removeAttribute(attr.name); }
                });
                walk(child);
            });
        }
        function unwrap(el) {
            var parent = el.parentNode;
            while (el.firstChild) { parent.insertBefore(el.firstChild, el); }
            parent.removeChild(el);
        }
        walk(root);

        // drop empty paragraphs Google Docs loves to add
        root.querySelectorAll('p').forEach(function (p) {
            if (!p.textContent.trim() && !p.querySelector('img')) { p.remove(); }
        });
        return root.innerHTML;
    }

    editor.addEventListener('input', function () { markDirty(); });
    srcview.addEventListener('input', function () { markDirty(); });

    /* ------------------------------------------------- featured image */
    var drop = $('imgDrop');
    var fileInput = $('imgFile');
    drop.addEventListener('click', function () { fileInput.click(); });
    drop.addEventListener('dragover', function (ev) { ev.preventDefault(); drop.classList.add('is-over'); });
    drop.addEventListener('dragleave', function () { drop.classList.remove('is-over'); });
    drop.addEventListener('drop', function (ev) {
        ev.preventDefault();
        drop.classList.remove('is-over');
        if (ev.dataTransfer.files.length) { handleFeatured(ev.dataTransfer.files[0]); }
    });
    fileInput.addEventListener('change', function () {
        if (this.files.length) { handleFeatured(this.files[0]); }
        this.value = '';
    });
    function handleFeatured(file) {
        uploadImage(file, fields.h1.value || 'featured', function (res) {
            featuredImage = res.path;
            $('imgPreview').src = res.url;
            $('imgPreview').style.display = 'block';
            $('imgDropText').innerHTML = 'Click or drop to replace.';
            markDirty();
            refreshChecklist();
        });
    }
    function uploadImage(file, hint, onDone) {
        var fd = new FormData();
        fd.append('action', 'upload_image');
        fd.append('csrf', window.ADMIN_CSRF);
        fd.append('hint', hint || '');
        fd.append('file', file);
        setStatus('Uploading image…');
        fetch('post-actions.php', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.ok) { setStatus('Image uploaded.'); onDone(res); }
                else { setStatus('Upload failed'); alert(res.error || 'Upload failed.'); }
            })
            .catch(function () { setStatus('Upload failed'); alert('Upload failed.'); });
    }

    /* ------------------------------------------------------ FAQ builder */
    var faqList = $('faqList');
    function addFaqItem(q, a, focus) {
        var item = document.createElement('div');
        item.className = 'adm-faq-item';
        item.draggable = false;
        item.innerHTML =
            '<div class="adm-faq-head">' +
            '<span class="drag" title="Drag to reorder">&#8942;&#8942;</span>' +
            '<input class="adm-input faq-q" type="text" placeholder="Question">' +
            '<button class="adm-btn adm-btn-ghost adm-btn-sm faq-up" type="button" title="Move up">&uarr;</button>' +
            '<button class="adm-btn adm-btn-ghost adm-btn-sm faq-down" type="button" title="Move down">&darr;</button>' +
            '<button class="adm-btn adm-btn-danger adm-btn-sm faq-del" type="button" title="Remove">&times;</button>' +
            '</div>' +
            '<textarea class="adm-textarea faq-a" placeholder="Answer (plain text or simple HTML)"></textarea>';
        item.querySelector('.faq-q').value = q || '';
        item.querySelector('.faq-a').value = a || '';
        faqList.appendChild(item);
        if (focus) { item.querySelector('.faq-q').focus(); }
    }
    (data.faqs || []).forEach(function (faq) { addFaqItem(faq.q, faq.a, false); });
    $('btnFaqAdd').addEventListener('click', function () { addFaqItem('', '', true); markDirty(); });
    faqList.addEventListener('click', function (ev) {
        var item = ev.target.closest('.adm-faq-item');
        if (!item) { return; }
        if (ev.target.classList.contains('faq-del')) { item.remove(); markDirty(); }
        if (ev.target.classList.contains('faq-up') && item.previousElementSibling) {
            faqList.insertBefore(item, item.previousElementSibling); markDirty();
        }
        if (ev.target.classList.contains('faq-down') && item.nextElementSibling) {
            faqList.insertBefore(item.nextElementSibling, item); markDirty();
        }
    });
    faqList.addEventListener('input', markDirty);
    function collectFaqs() {
        return Array.prototype.map.call(faqList.querySelectorAll('.adm-faq-item'), function (item) {
            return {
                q: item.querySelector('.faq-q').value.trim(),
                a: item.querySelector('.faq-a').value.trim()
            };
        }).filter(function (faq) { return faq.q && faq.a; });
    }

    /* ------------------------------------------------------ save/publish */
    function collectPayload() {
        return {
            slug: fields.slug.value.trim(),
            original_slug: data.originalSlug,
            h1: fields.h1.value,
            page_title: fields.pageTitle.value,
            page_description: fields.pageDesc.value,
            excerpt: fields.excerpt.value,
            date: fields.date.value,
            image: featuredImage,
            image_alt: fields.imageAlt.value,
            body: getBodyHtml(),
            faqs: JSON.stringify(collectFaqs()),
            author_name: fields.authorName.value,
            author_bio: fields.authorBio.value
        };
    }
    function postAction(action, extra, onDone) {
        var payload = collectPayload();
        payload.action = action;
        Object.keys(extra || {}).forEach(function (k) { payload[k] = extra[k]; });
        var fd = new FormData();
        Object.keys(payload).forEach(function (k) { fd.append(k, payload[k]); });
        fd.append('csrf', window.ADMIN_CSRF);
        setStatus(action === 'save' ? 'Saving…' : 'Working…');
        return fetch('post-actions.php', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.ok) {
                    dirty = false;
                    if (res.slug) { data.originalSlug = res.slug; }
                    if (res.status) { currentStatus = res.status; }
                    setStatus(res.msg || 'Done.');
                } else {
                    setStatus('Error');
                    alert(res.error || 'Something went wrong.');
                }
                if (onDone) { onDone(res); }
                return res;
            })
            .catch(function () { setStatus('Network error'); });
    }

    $('btnSave').addEventListener('click', function () {
        if (!fields.slug.value.trim()) { alert('Set a slug first.'); return; }
        postAction('save');
    });
    $('btnPublish').addEventListener('click', function () {
        if (!fields.slug.value.trim()) { alert('Set a slug first.'); return; }
        if (!confirm(currentStatus === 'published'
            ? 'Update the live post, registry and sitemap?'
            : 'Publish this post? It will go live on the site immediately.')) { return; }
        postAction('publish', {}, function (res) {
            if (res && res.ok) { window.location.reload(); }
        });
    });
    $('btnPreview').addEventListener('click', function () {
        // Open the tab synchronously inside the click so it is NOT popup-blocked;
        // then point it at the generated preview once the AJAX call returns.
        var win = window.open('', 'mpp_preview');
        if (win) {
            try { win.document.write('<!doctype html><meta charset="utf-8"><title>Preview…</title><body style="font:16px/1.5 sans-serif;padding:40px;color:#393939">Generating preview…</body>'); } catch (e) {}
        }
        postAction('preview', {}, function (res) {
            if (res && res.ok && res.url) {
                if (win) { win.location.href = res.url; } else { window.open(res.url, 'mpp_preview'); }
            } else if (win) {
                try { win.document.body.innerHTML = 'Preview failed: ' + ((res && res.error) || 'unknown error'); } catch (e) { win.close(); }
            }
        });
    });
    var btnUnpublish = $('btnUnpublish');
    if (btnUnpublish) {
        btnUnpublish.addEventListener('click', function () {
            if (!confirm('Unpublish this post? It is removed from the site, registry and sitemap (file moved to /trash).')) { return; }
            postAction('unpublish', {}, function (res) {
                if (res && res.ok) { window.location.reload(); }
            });
        });
    }
    var btnDelete = $('btnDelete');
    if (btnDelete) {
        btnDelete.addEventListener('click', function () {
            if (!confirm('Delete this post completely? This cannot be undone (the live file, if any, goes to /trash).')) { return; }
            postAction('delete', {}, function (res) {
                if (res && res.ok) { window.location.href = 'posts.php'; }
            });
        });
    }

    /* Ctrl+S save */
    document.addEventListener('keydown', function (ev) {
        if ((ev.ctrlKey || ev.metaKey) && ev.key.toLowerCase() === 's') {
            ev.preventDefault();
            if (fields.slug.value.trim()) { postAction('save'); }
        }
    });

    /* Autosave every 45s while dirty. Reads the active view - it never
       toggles the user out of the HTML source view (gotcha #6). */
    setInterval(function () {
        if (dirty && fields.slug.value.trim() && fields.h1.value.trim()) {
            postAction('save');
        }
    }, 45000);

    window.addEventListener('beforeunload', function (ev) {
        if (dirty) { ev.preventDefault(); ev.returnValue = ''; }
    });

    refreshMeta();
    refreshChecklist();
}());
