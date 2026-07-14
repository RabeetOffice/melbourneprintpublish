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
                                <i class="fa-solid fa-globe"></i>
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