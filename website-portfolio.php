<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('website-portfolio'); ?>    <?php include("includes/style.php"); ?>
</head>

<body class="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>">

    <!-- Google Tag Manager (noscript) -->
    <?php echo mpp_seo_gtm_noscript(); ?>
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
                        <h1>Website Portfolio</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'data/website_portfolio_post.php'; ?>
    <section class="blogs-sec portf-sec">
        <div class="container">
            <div class="portfolio-list">
                <div class="row">

                    <?php $visibleCount = count(array_filter($websitePortfolio, fn($w) => ($w['hidden'] ?? '') === '')); ?>
                    <?php if ($visibleCount === 0): ?>
                    <div class="col-md-12">
                        <p style="text-align:center;padding:40px 0">Our author website portfolio is being updated &mdash; check back soon.</p>
                    </div>
                    <?php endif; ?>

                    <?php foreach($websitePortfolio as $item): ?>
                    <?php
                        $link = $item['link'];

                        // Agar real URL hai to new tab
                        $target = ($link !== 'javascript:void(0);' && $link !== '') ? '_blank' : '';
                    ?>

                    <div class="col-md-4 <?= $item['hidden'] ?? ''; ?>">
                        <div id="webportf<?= $item['id']; ?>" class="portfolio_box website-portf">
                            <div class="portfolio_box-img">
                                <img src="<?= e(mpp_website_thumb($item)); ?>" alt="<?= $item['alt'] ?? ''; ?>" loading="lazy" decoding="async">
                            </div>

                            <div class="portfolio_box-name">
                                <h4><?= $item['name']; ?></h4>
                                <?php if (!empty($item['description'])): ?>
                                <p class="portf-desc"><?= e($item['description']); ?></p>
                                <?php endif; ?>
                            </div>
                            <a class="bttn" href="<?= $link !== '' ? $link : 'javascript:void(0);'; ?>" <?= $target ? 'target="'.$target.'"' : ''; ?>
                                rel="nofollow">
                                Visit Website
                                <svg class="mpp-icon mpp-icon-globe" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M336.5 160C322 70.7 287.8 8 248 8s-74 62.7-88.5 152h177zM152 256c0 22.2 1.2 43.5 3.3 64h185.3c2.1-20.5 3.3-41.8 3.3-64s-1.2-43.5-3.3-64H155.3c-2.1 20.5-3.3 41.8-3.3 64zm324.7-96c-28.6-67.9-86.5-120.4-158-141.6 24.4 33.8 41.2 84.7 50 141.6h108zM177.2 18.4C105.8 39.6 47.8 92.1 19.3 160h108c8.7-56.9 25.5-107.8 49.9-141.6zM487.4 192H372.7c2.1 21 3.3 42.5 3.3 64s-1.2 43-3.3 64h114.6c5.5-20.5 8.6-41.8 8.6-64s-3.1-43.5-8.5-64zM120 256c0-21.5 1.2-43 3.3-64H8.6C3.2 212.5 0 233.8 0 256s3.2 43.5 8.6 64h114.6c-2-21-3.2-42.5-3.2-64zm39.5 96c14.5 89.3 48.7 152 88.5 152s74-62.7 88.5-152h-177zm159.3 141.6c71.4-21.2 129.4-73.7 158-141.6h-108c-8.8 56.9-25.6 107.8-50 141.6zM19.3 352c28.6 67.9 86.5 120.4 158 141.6-24.4-33.8-41.2-84.7-50-141.6h-108z"/></svg>
                            </a>
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