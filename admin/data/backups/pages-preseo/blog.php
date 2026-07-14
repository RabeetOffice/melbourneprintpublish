<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <title>Melbourne Book Publishing Blog – Tips & Insights</title>
    <meta name="description"
        content="Stay updated with our Melbourne blog featuring expert tips, trends, and insights on book publishing, editing, marketing, and author success stories." />
    <meta name="DC.title" content="Melbourne Print and Publish" />
    <meta name="geo.region" content="AU-VIC" />
    <meta name="geo.placename" content="Melbourne" />
    <meta name="geo.position" content="-37.841303;144.97652" />
    <meta name="ICBM" content="-37.841303, 144.97652" />
    <link rel="canonical" href="<?php echo e(brand_canonical()); ?>" />
        
    <!-- End Google Tag Manager -->
    <meta name="google-site-verification" content="fmX4SQHeIHlfWDv3FiuLtWRDGStxBIfdRWPTQGQ8Vxs" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-V7PVJBXYL9"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-V7PVJBXYL9');
    </script>

    <!-- Google Tag Manager -->
    <script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PFZRKR97');
    </script>
    <!-- End Google Tag Manager -->

    <?php include("includes/style.php"); ?>
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
                        <h1>Our Blogs</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blogs-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php include("shortcode/blogs_list.php"); ?>
                </div>
            </div>
        </div>
    </section>





    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>