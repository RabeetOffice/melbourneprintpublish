<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('testimonial'); ?>    <?php include("includes/style.php"); ?>
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
                        <h1>Testimonials</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'data/testimonials_post.php'; ?>

    <section class="blogs-sec testimonial-sec">
        <div class="container">
            <div class="testimonial-list">
                <div class="row">
                    <?php foreach($testimonials as $item): ?>

                    <?php
                        $link = $item['link'];

                        // Agar real URL hai to new tab
                        $target = ($link !== 'javascript:void(0);') ? '_blank' : '';
                    ?>

                    <div class="col-md-4 post-item <?= $item['hidden'] ?? ''; ?>">
                        <div class="testimonial_box">
                            <div class="testimonial_box_inner">
                                <a href="<?= $link; ?>" <?= $target ? 'target="'.$target.'"' : ''; ?>>
                                    <div class="testimonial_box_top">
                                        <div class="testimonial_box_icon">
                                            <svg class="mpp-icon mpp-icon-quote-right" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M464 32H336c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h80v64c0 35.3-28.7 64-64 64h-8c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h8c88.4 0 160-71.6 160-160V80c0-26.5-21.5-48-48-48zm-288 0H48C21.5 32 0 53.5 0 80v128c0 26.5 21.5 48 48 48h80v64c0 35.3-28.7 64-64 64h-8c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h8c88.4 0 160-71.6 160-160V80c0-26.5-21.5-48-48-48z"/></svg>
                                        </div>
                                        <div class="testimonial_box_name">
                                            <h4><?= $item['name']; ?></h4>
                                        </div>
                                        <div class="testimonial_box_text">
                                            <p><?= $item['text']; ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>

                </div>

                <!-- Load More Button -->
                <div class="load-bttn">
                    <a class="load-more bttn" href="javascript:void(0);">Load More</a>
                </div>

            </div>
        </div>
    </section>


    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>