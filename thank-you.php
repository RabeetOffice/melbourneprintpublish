<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('thank-you'); ?>    <?php include("includes/style.php"); ?>
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
                        <h1>Thank You</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="privacy-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="privacy-txt">
                        <p style="font-size: 20px; text-align: center;">We’ve received your request and our team will get back to you shortly!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>