<?php
/* Always answer with a real 404 status. Apache sets this when the page is
   served via ErrorDocument, but setting it here too means a direct hit on
   /404/ is never a soft 404 (a 200 on an error page misleads Google). */
http_response_code(404);
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, noindex">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('404'); ?>    <?php include("includes/style.php"); ?>
</head>

<body class="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>">

    <?php include("includes/disclaimer.php"); ?>

    <header id="masthead" class="site-header">
        <?php include("includes/header.php"); ?>
    </header>

    <section class="error-404 not-found">
        <div class="not_found_inner">
            <div class="logo">
                <a href="/" class="custom-logo-link" rel="home">
                    <img src="<?= e(brand_asset('/assets/images/logo.png')); ?>" alt="Primary logo of Melbourne Print & Publish" width="843" height="240">
                </a>
            </div>
            <header class="page-header">
                <h1 class="page-title">4<span>0</span>4</h1>
                <h3>Oops!</h3>
                <p>The Page you are looking for does not exist</p>
            </header><!-- .page-header -->

            <div class="page-content">
                <a class="return_home" href="/">Return to Home</a>
            </div><!-- .page-content -->
        </div>
    </section>



    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>