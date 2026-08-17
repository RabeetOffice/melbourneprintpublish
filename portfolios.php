<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('portfolios'); ?>    <?php include("includes/style.php"); ?>
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
                        <h1>Our Portfolio</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'data/portfolio_post.php'; ?>
    <section class="blogs-sec portf-sec">
        <div class="container">
            <div class="portfolio-list">
                <div class="row">

                    <?php foreach($portfolio as $item): ?>
                    <?php
                        $link = $item['link'];

                        // Agar real URL hai to new tab
                        $target = ($link !== 'javascript:void(0);') ? '_blank' : '';
                    ?>

                    <div class="col-md-3 <?= $item['hidden'] ?? ''; ?>">
                        <div id="portf<?= $item['id']; ?>" class="portfolio_box">
                            <div class="portfolio_box-img">
                                <img src="<?= $item['image']; ?>" alt="<?= $item['alt'] ?? ''; ?>" loading="lazy" decoding="async">
                            </div>

                            <div class="portfolio_box-name">
                                <h4><?= $item['name']; ?></h4>
                            </div>
                            <a class="bttn" href="<?= $link; ?>" <?= $target ? 'target="'.$target.'"' : ''; ?>
                                rel="nofollow">
                                View on Amazon
                                <svg class="mpp-icon mpp-icon-amazon" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M257.2 162.7c-48.7 1.8-169.5 15.5-169.5 117.5 0 109.5 138.3 114 183.5 43.2 6.5 10.2 35.4 37.5 45.3 46.8l56.8-56S341 288.9 341 261.4V114.3C341 89 316.5 32 228.7 32 140.7 32 94 87 94 136.3l73.5 6.8c16.3-49.5 54.2-49.5 54.2-49.5 40.7-.1 35.5 29.8 35.5 69.1zm0 86.8c0 80-84.2 68-84.2 17.2 0-47.2 50.5-56.7 84.2-57.8v40.6zm136 163.5c-7.7 10-70 67-174.5 67S34.2 408.5 9.7 379c-6.8-7.7 1-11.3 5.5-8.3C88.5 415.2 203 488.5 387.7 401c7.5-3.7 13.3 2 5.5 12zm39.8 2.2c-6.5 15.8-16 26.8-21.2 31-5.5 4.5-9.5 2.7-6.5-3.8s19.3-46.5 12.7-55c-6.5-8.3-37-4.3-48-3.2-10.8 1-13 2-14-.3-2.3-5.7 21.7-15.5 37.5-17.5 15.7-1.8 41-.8 46 5.7 3.7 5.1 0 27.1-6.5 43.1z"/></svg>
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