<?php
/** Dynamic sitemap. Served for /sitemap_index.xml via .htaccess rewrite. */
require_once __DIR__ . '/includes/sitemap-gen.php';
header('Content-Type: application/xml; charset=UTF-8');
echo mpp_sitemap_xml();
