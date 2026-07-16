<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('video-trailers-portfolio'); ?>    <?php include("includes/style.php"); ?>
</head>

<body class="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFZRKR97" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include("includes/disclaimer.php"); ?>

    <header id="masthead" class="site-header">
        <?php include("includes/header.php"); ?>
    </header>

    <section class="inner-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head45">
                        <h1>Video Trailers Portfolio</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'data/video_trailers_post.php'; ?>
    <section class="blogs-sec portf-sec">
        <div class="container">
            <div class="portfolio-list">
                <div class="row">

                    <?php $visibleCount = count(array_filter($videoTrailers, fn($v) => ($v['hidden'] ?? '') === '')); ?>
                    <?php if ($visibleCount === 0): ?>
                    <div class="col-md-12">
                        <p style="text-align:center;padding:40px 0">Our video trailers portfolio is being updated &mdash; check back soon.</p>
                    </div>
                    <?php endif; ?>

                    <?php foreach($videoTrailers as $item): ?>
                    <?php
                        $embed = mpp_youtube_embed((string) ($item['link'] ?? ''));
                        if ($embed === '') { continue; } // skip any entry with an unparseable link
                    ?>

                    <div class="col-md-4 <?= $item['hidden'] ?? ''; ?>">
                        <div id="videoportf<?= $item['id']; ?>" class="portfolio_box video-portf">
                            <div class="portfolio_box-img">
                                <iframe src="<?= e($embed); ?>"
                                    title="<?= e((string) ($item['name'] ?? 'Video trailer')); ?>"
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen></iframe>
                            </div>

                            <div class="portfolio_box-name">
                                <h4><?= e((string) $item['name']); ?></h4>
                                <?php if (!empty($item['description'])): ?>
                                <p class="portf-desc"><?= e($item['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </section>




    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>
