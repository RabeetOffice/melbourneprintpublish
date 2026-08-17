<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('contact-us'); ?>    <?php include("includes/style.php"); ?>
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
                        <h1>Contact Us</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="reach-now-sec">
        <div class="container">
            <div class="row">

                <div class="col-md-6 left-col">
                    <div class="head38 mb20">
                        <h2><span>Have a Question?</span> Get in Touch</h2>
                    </div>
                    <div class="para16">
                        <p>
                            At Melbourne Print & Publish, we’re here to support you at every stage of your
                            publishing
                            journey. Whether you’re exploring your options, looking for clarity on our services, or
                            ready to take the next step, our team is always happy to help.
                        </p>
                        <p>
                            Simply complete the form below and one of our publishing consultants will be in touch
                            shortly. From first-time authors to experienced writers, we provide clear guidance
                            across editing, design, printing, and distribution, so you always know exactly what to
                            expect.
                        </p>
                        <p>
                            We believe publishing should feel straightforward, collaborative, and transparent. No
                            confusing jargon, no unanswered questions, just honest advice and reliable support.
                        </p>
                        <p>
                            Your story deserves to be shared. Let’s work together to bring your book to life.
                        </p>
                    </div>

                </div>

                <div class="col-md-6 right-col">
                    <?php include("foam/foam.php"); ?>
                </div>

            </div>
        </div>
    </section>





    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>