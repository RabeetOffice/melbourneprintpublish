<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"> -->
    <!-- <meta http-equiv="refresh" content="0; url=https://viraladviceteam.com/test/"> -->
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('index'); ?>            <?php include("includes/style.php"); ?>
</head>
<style>
    
    /* ==============================
   Client Testimonial Section
================================= */

/* ==========================================
   Melbourne Print & Publish Testimonial
   Brand colours:
   Orange:   #F07F40
   Charcoal: #393A39
   White:    #FFFFFF
========================================== */

.client-testimonial-section {
    --mpp-orange: #f07f40;
    --mpp-orange-dark: #d9682e;
    --mpp-orange-light: #fff0e6;
    --mpp-charcoal: #393a39;
    --mpp-dark: #202120;
    --mpp-text: #626462;
    --mpp-border: #ece5e0;
    --mpp-cream: #fff8f3;
    --mpp-white: #ffffff;

    position: relative;
    overflow: hidden;
    padding: 100px 24px;
    background:
        radial-gradient(
            circle at 90% 15%,
            rgba(240, 127, 64, 0.17),
            transparent 28%
        ),
        radial-gradient(
            circle at 5% 90%,
            rgba(57, 58, 57, 0.07),
            transparent 25%
        ),
        linear-gradient(135deg, #ffffff 0%, #fff8f3 100%);
    font-family: Arial, Helvetica, sans-serif;
    margin-top:70px;
}

/* Background decorative circle */

.client-testimonial-section::before {
    content: "";
    position: absolute;
    top: -150px;
    left: -150px;
    width: 350px;
    height: 350px;
    border: 70px solid rgba(240, 127, 64, 0.06);
    border-radius: 50%;
}

.client-testimonial-section::after {
    content: "";
    position: absolute;
    right: -100px;
    bottom: -130px;
    width: 290px;
    height: 290px;
    border-radius: 50%;
    background: rgba(57, 58, 57, 0.035);
}

/* Main layout */

.testimonial-container {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(280px, 430px);
    align-items: center;
    gap: 80px;
    width: 100%;
    max-width: 1180px;
    margin: 0 auto;
}

/* ==========================
   Testimonial Content
========================== */

.testimonial-content {
    max-width: 650px;
}

.testimonial-label {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    color: var(--mpp-orange);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.testimonial-label::before {
    content: "";
    width: 36px;
    height: 3px;
    border-radius: 10px;
    background: var(--mpp-orange);
}

.testimonial-content h2 {
    max-width: 620px;
    margin: 0 0 24px;
    color: var(--mpp-charcoal);
    font-size: clamp(38px, 5vw, 62px);
    font-weight: 700;
    line-height: 1.08;
    letter-spacing: -2px;
}

.testimonial-intro {
    max-width: 600px;
    margin: 0;
    color: var(--mpp-text);
    font-size: 17px;
    line-height: 1.8;
}

/* Quote box */

.testimonial-quote {
    position: relative;
    margin-top: 35px;
    padding: 27px 30px 27px 78px;
    overflow: hidden;
    border: 1px solid rgba(240, 127, 64, 0.16);
    border-left: 5px solid var(--mpp-orange);
    border-radius: 0 16px 16px 0;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 18px 45px rgba(57, 58, 57, 0.09);
    backdrop-filter: blur(10px);
}

.testimonial-quote::after {
    content: "";
    position: absolute;
    top: -45px;
    right: -45px;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(240, 127, 64, 0.07);
}

.quote-icon {
    position: absolute;
    top: 27px;
    left: 27px;
    width: 32px;
    height: 32px;
}

.quote-icon svg {
    display: block;
    width: 100%;
    height: 100%;
    fill: var(--mpp-orange);
}

.testimonial-quote p {
    position: relative;
    z-index: 2;
    margin: 0;
    color: #494b49;
    font-size: 15px;
    font-style: italic;
    line-height: 1.75;
}

/* Client information */

.client-details {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-top: 28px;
}

.client-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    flex-shrink: 0;
    border: 3px solid rgba(240, 127, 64, 0.25);
    border-radius: 50%;
    background: var(--mpp-charcoal);
    color: var(--mpp-white);
    font-size: 18px;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(57, 58, 57, 0.18);
}

.client-details h3 {
    margin: 0 0 4px;
    color: var(--mpp-charcoal);
    font-size: 16px;
    font-weight: 700;
}

.client-details span {
    color: #7b7d7b;
    font-size: 13px;
}

/* ==========================
   YouTube Video
========================== */

.testimonial-video-column {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.video-decoration {
    position: absolute;
    top: -27px;
    right: 10px;
    width: 75%;
    height: 90%;
    border: 2px solid rgba(240, 127, 64, 0.45);
    border-radius: 35px;
    transform: rotate(6deg);
}

.testimonial-video {
    position: relative;
    z-index: 2;
    width: min(100%, 330px);
    aspect-ratio: 9 / 16;
    overflow: hidden;
    padding: 8px;
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 32px;
    background: var(--mpp-charcoal);
    box-shadow:
        0 35px 70px rgba(57, 58, 57, 0.23),
        0 10px 25px rgba(240, 127, 64, 0.12);
}

.testimonial-video::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    border-radius: inherit;
    background: linear-gradient(
        145deg,
        var(--mpp-charcoal),
        var(--mpp-dark)
    );
}

.testimonial-video iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: 0;
    border-radius: 25px;
}

/* Video caption */

.video-caption {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 11px;
    margin-top: 25px;
    color: var(--mpp-charcoal);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.3px;
}

.play-indicator {
    position: relative;
    display: inline-block;
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 50%;
    background: var(--mpp-orange);
    box-shadow: 0 7px 18px rgba(240, 127, 64, 0.4);
    transition:
        transform 0.25s ease,
        background-color 0.25s ease;
}

.video-caption:hover .play-indicator {
    background: var(--mpp-orange-dark);
    transform: scale(1.08);
}

.play-indicator::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 53%;
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
    border-left: 8px solid var(--mpp-white);
    transform: translate(-50%, -50%);
}

/* ==========================
   Tablet
========================== */

@media (max-width: 900px) {
    .client-testimonial-section {
        padding: 75px 22px;
    }

    .testimonial-container {
        grid-template-columns: 1fr;
        gap: 58px;
    }

    .testimonial-content {
        max-width: 720px;
        margin: 0 auto;
        text-align: center;
    }

    .testimonial-content h2,
    .testimonial-intro {
        margin-left: auto;
        margin-right: auto;
    }

    .testimonial-label {
        justify-content: center;
    }

    .testimonial-quote {
        text-align: left;
    }

    .client-details {
        justify-content: center;
        text-align: left;
    }

    .video-decoration {
        right: calc(50% - 185px);
        width: 290px;
    }
}

/* ==========================
   Mobile
========================== */

@media (max-width: 520px) {
    .client-testimonial-section {
        padding: 60px 16px;
    }

    .testimonial-content h2 {
        font-size: 36px;
        letter-spacing: -1.3px;
    }

    .testimonial-intro {
        font-size: 15px;
        line-height: 1.7;
    }

    .testimonial-quote {
        padding: 66px 22px 24px;
        border-left-width: 4px;
    }

    .quote-icon {
        top: 22px;
        left: 22px;
    }

    .client-details {
        align-items: flex-start;
    }

    .testimonial-video {
        width: min(100%, 300px);
        border-radius: 27px;
    }

    .testimonial-video iframe {
        border-radius: 20px;
    }

    .video-decoration {
        right: calc(50% - 165px);
        width: 270px;
    }
}
    
</style>
<?php
$page = basename($_SERVER['PHP_SELF'], '.php');

if ($page == 'index') {
    $page = 'home';
}
?>

<body class="<?php echo $page; ?> site-page">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFZRKR97" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include("includes/disclaimer.php"); ?>

    <header id="masthead" class="site-header">
        <?php include("includes/header.php"); ?>
    </header>



        <style>
        .new-home-banner {
    position: relative;
    overflow: hidden;
    min-height: 720px;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #fffaf5 0%, #ffffff 45%, #fff1e8 100%);
    padding : 100px 0px 20px 0px;
}

.new-home-banner .banner-bg-shape {
    position: absolute;
    right: -180px;
    top: -180px;
    width: 760px;
    height: 760px;
    background: radial-gradient(circle, rgba(255, 121, 61, 0.26), rgba(255, 121, 61, 0.04) 65%);
    border-radius: 50%;
    z-index: 0;
    filter: blur(51px);
}

.new-home-banner:before {
    content: "";
    position: absolute;
    left: -160px;
    bottom: -220px;
    width: 520px;
    height: 520px;
    background: rgba(255, 121, 61, 0.08);
    border-radius: 50%;
}

.new-home-banner .container {
    position: relative;
    z-index: 2;
}

.home-banner-cont {
    max-width: 620px;
}

.banner-kicker {
    display: inline-flex;
    align-items: center;
    padding: 10px 22px;
    border-radius: 50px;
    background: rgba(255, 121, 61, 0.12);
    color: #ff793d;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 22px;
}

.new-home-banner h1 {
    font-size: 48px;
    line-height: 1.05;
    font-weight: 600;
    color: #111111;
    margin-bottom: 24px;
    letter-spacing: -1.8px;
}

.new-home-banner p {
    font-size: 17px;
    line-height: 1.8;
    color: #6f7f8c;
    margin-bottom: 30px;
    max-width: 560px;
}

.new-home-banner .bttn-grp {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 36px;
}

.new-home-banner .bttn,
.new-home-banner .bttn1 {
    min-width: 145px;
    height: 52px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.35s ease;
}

.new-home-banner .bttn {
    background: linear-gradient(135deg, #ff793d, #ff9b5f);
    color: #ffffff;
    box-shadow: 0 14px 30px rgba(255, 121, 61, 0.35);
}

.new-home-banner .bttn:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 38px rgba(255, 121, 61, 0.45);
}

.new-home-banner .bttn1 {
    background: #222222;
    color: #ffffff;
}

.new-home-banner .bttn1:hover {
    background: #ff793d;
    color: #ffffff;
    transform: translateY(-3px);
}

.banner-points {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
}

.banner-points div {
    background: #ffffff;
    border: 1px solid rgba(255, 121, 61, 0.14);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.06);
    border-radius: 18px;
    padding: 18px 20px;
    min-width: 145px;
}

.banner-points strong {
    display: block;
    font-size: 21px;
    color: #111111;
    font-weight: 600;
    line-height: 1;
    margin-bottom: 8px;
    letter-spacing: -0.8px;
}

.banner-points span {
    display: block;
    font-size: 13px;
    color: #788894;
    font-weight: 500;
}

.banner-visual {
    position: relative;
    min-height: 560px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.circle-glow {
    position: absolute;
    width: 620px;
    height: 620px;
    background: linear-gradient(135deg, #ff7f3e, #ff7f3e03);
    border-radius: 50%;
    box-shadow: inset 0 0 80px rgba(255, 121, 61, 0.12);
    filter: blur(51px);
}

.main-book {
    position: relative;
    z-index: 2;
    max-width: 560px;
    width: 100%;
    transform: rotate(-7deg);
    filter: drop-shadow(0 35px 45px rgba(0, 0, 0, 0.22));
    animation: floatBook 5s ease-in-out infinite;
}

.floating-card {
    position: absolute;
    z-index: 3;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 121, 61, 0.18);
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
    border-radius: 18px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #111111;
    font-weight: 700;
    font-size: 14px;
}

.floating-card i {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #ff793d;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.card-one {
    top: 80px;
    left: 40px;
    animation: floatCard 4s ease-in-out infinite;
}

.card-two {
    right: 20px;
    top: 165px;
    animation: floatCard 4.5s ease-in-out infinite;
}

.card-three {
    left: 90px;
    bottom: 90px;
    animation: floatCard 5s ease-in-out infinite;
}

@keyframes floatBook {
    0%, 100% {
        transform: translateY(0) rotate(-7deg);
    }
    50% {
        transform: translateY(-18px) rotate(-7deg);
    }
}

@keyframes floatCard {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-12px);
    }
}

@media (max-width: 991px) {
    .new-home-banner {
        padding: 120px 0 60px;
        min-height: auto;
        text-align: center;
    }

    .home-banner-cont {
        margin: 0 auto;
    }

    .new-home-banner h1 {
        font-size: 42px;
    }

    .new-home-banner p {
        margin-left: auto;
        margin-right: auto;
    }

    .new-home-banner .bttn-grp,
    .banner-points {
        justify-content: center;
    }

    .banner-visual {
        min-height: 480px;
        margin-top: 40px;
    }

    .main-book {
        max-width: 460px;
    }
}

@media (max-width: 575px) {
    .new-home-banner h1 {
        font-size: 34px;
    }

    .new-home-banner p {
        font-size: 15px;
    }

    .new-home-banner .bttn-grp {
        flex-direction: column;
    }

    .new-home-banner .bttn,
    .new-home-banner .bttn1 {
        width: 100%;
    }

    .banner-points div {
        width: 100%;
    }

    .banner-visual {
        min-height: 390px;
    }

    .floating-card {
        font-size: 12px;
        padding: 10px 12px;
    }

    .card-one {
        left: 0;
        top: 50px;
    }

    .card-two {
        right: 0;
        top: 120px;
    }

    .card-three {
        left: 20px;
        bottom: 70px;
    }
}
    </style>
    <section class="banner new-home-banner">
    <div class="banner-bg-shape"></div>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 left-col">
                <div class="home-banner-cont">
                    <span class="banner-kicker">Melbourne Print & Publish</span>

                    <h1>Professional Printing and Publishing Company in Melbourne</h1>

                    <p>
                        We’re a full-service publishing company helping Melbourne authors bring their books to life.
                        From professional book design and expert editing to ghostwriting, strategic marketing, and
                        complete publishing with global distribution — we’re with you from first draft to first sale.
                    </p>

                    <div class="bttn-grp">
                        <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                           data-target="#exampleModalCenter">Get Started</a>

                        <a href="javascript:void(0);" class="bttn1 openChatBtn">Live Chat</a>
                    </div>

                    <div class="banner-points d-none d-lg-flex">
                        <div>
                            <strong>70+</strong>
                            <span>Publishing Platforms</span>
                        </div>
                        <div>
                            <strong>100%</strong>
                            <span>Author Support</span>
                        </div>
                        <div>
                            <strong>Premium</strong>
                            <span>Book Design</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 right-col d-none d-lg-block">
                <div class="banner-visual">
                    <div class="circle-glow"></div>

                    <img class="main-book" src="assets/images/new-hero.webp" alt="Book Publishing Melbourne" width="1085" height="1450" loading="eager" fetchpriority="high">

                    <div class="floating-card card-one">
                        <i class="fas fa-book-open"></i>
                        <span>Book Design</span>
                    </div>

                    <div class="floating-card card-two">
                        <i class="fas fa-pen-nib"></i>
                        <span>Editing</span>
                    </div>

                    <div class="floating-card card-three">
                        <i class="fas fa-globe"></i>
                        <span>Global Publishing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- <section class="banner h-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="home-banner-cont">
                        <div class="head45 mb20">
                            <h1>Professional Printing and Publishing Company in Melbourne</h1>
                        </div>
                        <div class="para16 mb20">
                            <p>We’re a full-service publishing company helping Melbourne authors bring their books to
                                life. Our experienced team handles everything, professional book design that catches
                                readers’ eyes, expert editing that polishes your manuscript, ghostwriting services when
                                you need help with the writing itself, strategic marketing that gets your book
                                discovered, and complete publishing with global distribution. From your first draft to
                                your first sale, we’re with you.</p>
                        </div>
                        <div class="bttn-grp">
                            <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                                data-target="#exampleModalCenter">Get Started</a>
                             <a href="javascript:void(0);" class="bttn1 openChatBtn">Live Chat</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 right-col">
                </div>

            </div>

        </div>
    </section> -->

    <section class="logo-sec h-logo-sec">
        <div class="containers">
            <div class="row">
                <div class="col-md-12">
                    <?php include("shortcode/logo_list.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="video-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head18 headbox mb20">
                        <h2>We See You, Dreamer.</h2>
                    </div>
                    <div class="head38 mb20">
                        <h2>Let’s Make Your Idea a Book Worth Publishing.</h2>
                    </div>
                    <div class="m-video">
                        <div>
                            <video class="elementor-video" src="assets/videos/melbourne-video.mp4" poster="assets/videos/video-poster.webp" autoplay="" loop=""
                                muted="muted" playsinline="" controlslist="nodownload"></video>
                            <!-- <iframe width="100%" height="100%" src="https://www.youtube.com/embed/UJaXzUVMaKE"-->
                            <!--    frameborder="0"-->
                            <!--    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"-->
                            <!--    allowfullscreen>-->
                            <!--</iframe> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="client-testimonial-section">
    <div class="testimonial-container">

        <!-- Testimonial Content -->
        <div class="testimonial-content">
            <span class="testimonial-label">Client Testimonial</span>

            <h2>Real Stories From Our Valued Clients</h2>

            <p class="testimonial-intro">
                Our clients’ experiences speak for the quality, care, and dedication
                we bring to every project. Watch this testimonial to discover how we
                helped turn an important idea into a professionally delivered result.
            </p>

            <div class="testimonial-quote">
                <div class="quote-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M7.17 6A5.17 5.17 0 0 0 2 11.17V18h7v-7H5.1A2.1 2.1 0 0 1 7.17 9H9V6H7.17Zm10 0A5.17 5.17 0 0 0 12 11.17V18h7v-7h-3.9A2.1 2.1 0 0 1 17.17 9H19V6h-1.83Z"/>
                    </svg>
                </div>

                <p>
                    “A genuine client experience showcasing the support, communication,
                    and professional service delivered throughout the journey.”
                </p>
            </div>

            <div class="client-details">
                <div class="client-avatar">C</div>

                <div>
                    <h3>Verified Client</h3>
                    <span>Video Testimonial</span>
                </div>
            </div>
        </div>

        <!-- YouTube Shorts Video -->
        <div class="testimonial-video-column">
            <div class="video-decoration"></div>

            <div class="testimonial-video">
                <iframe
                    src="https://www.youtube.com/embed/bophMqfJd8o?rel=0"
                    title="Client video testimonial"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen>
                </iframe>
            </div>

            <div class="video-caption">
                <span class="play-indicator"></span>
                Watch the full client experience
            </div>
        </div>

    </div>
</section>

    <section class="genre-sec">
        <div class="container">
            <div class="row row1">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>We <span>Publish Books</span> Across All Genres</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>
                            Whether you've written fiction, non-fiction, or something specialised, we've got the
                            expertise to
                            publish it professionally. Our team understands the unique requirements of every category,
                            from
                            romance novels to academic textbooks, fantasy series to business guides.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-4">
                    <div class="genre-card">
                        <div class="head26">
                            <h2>Fiction</h2>
                        </div>
                        <ul>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Literary Fiction & Contemporary Novels
                            </li>

                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Romance & Romantic Suspense
                            </li>

                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Mystery, Thriller & Crime Fiction
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Science Fiction & Fantasy
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Historical Fiction
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Horror & Supernatural
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Young Adult (YA) & New Adult
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Children’s Books & Middle Grade
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="genre-card">
                        <div class="head26">
                            <h2>Non-Fiction</h2>
                        </div>
                        <ul>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Business & Entrepreneurship
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Self-Help & Personal Development
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg> Memoirs & Autobiographies
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Health, Wellness & Fitness
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Cookbooks & Food Writing
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Travel & Adventure
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Religion & Spirituality
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                True Crime
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="genre-card">
                        <div class="head26">
                            <h2>Specialised Categories</h2>
                        </div>
                        <ul>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Academic & Educational Textbooks
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Poetry Collections
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Biography & History
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Science & Technology
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Parenting & Family
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Art & Photography Books
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                How-To Guides & Instructional Manuals
                            </li>
                            <li>
                                <svg viewBox="0 0 512 512">
                                    <path
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                                Professional & Career Development
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <a class="bttn1" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog" data-target="#exampleModalCenter">Publish
                Your Genre Today</a>
        </div>
    </section>

    <section class="h-port-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Our<span> Portfolio</h2>
                    </div>
                    <?php include("shortcode/portfolio_slider.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="category-sec">
        <div class="container">
            <div class="row row1">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Our Most <span>Published Categories</span></h2>
                    </div>
                </div>
            </div>
            <div class="row row2 mb20">
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20">
                            <img src="assets/images/icons/Romance.png" alt="Romance genre book icon for Melbourne Print & Publish" loading="lazy">
                        </div>
                        <div class="head18">
                            <h3>Romance</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20">
                            <img src="assets/images/icons/Thriller.png" alt="Thriller genre book icon for Melbourne Print & Publish" loading="lazy">
                        </div>
                        <div class="head18">
                            <h3>Memoirs and Autobiography</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20">
                            <img src="assets/images/icons/fantasy.png" alt="Fantasy genre book icon for Melbourne Print & Publish" loading="lazy">
                        </div>
                        <div class="head18">
                            <h3>Fantasy</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20">
                            <img src="assets/images/icons/Since-Fiction.png" alt="Science fiction genre book icon for Melbourne Print & Publish" loading="lazy">
                        </div>
                        <div class="head18">
                            <h3>Science fiction</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20">
                            <img src="assets/images/icons/Since.png" alt="General publishing icon for Melbourne Print & Publish" loading="lazy">
                        </div>
                        <div class="head18">
                            <h3>Business and Leadership</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20">
                            <img src="assets/images/icons/Adventure.png" alt="Adventure genre book icon for Melbourne Print & Publish" loading="lazy">
                        </div>
                        <div class="head18">
                            <h3>Self-Help</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="cost-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>Book Publishing <span>Cost in Melbourne</span></h2>
                    </div>
                    <div class="para16 mb20">
                        <p>Publishing costs aren’t one-size-fits-all, your book’s unique. A children’s picture book
                            needs different services than a business guide or thriller novel. Some authors need complete
                            packages covering editing, design, and marketing, while others just want specific services.
                            Length matters too, a 50,000-word romance costs differently than a 120,000-word epic
                            fantasy. We don’t believe in generic pricing that doesn’t reflect reality. During your free
                            consultation, we’ll discuss your specific book and create a customised quote covering only
                            what you actually need. No pressure to buy services you don’t want, no hidden fees appearing
                            later. Just honest pricing based on your book’s real requirements and your budget.</p>
                    </div>
                    <a class="bttn1" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                        data-target="#exampleModalCenter">Get Your Free Publishing Quote</a>
                </div>
                <div class="col-md-6 right-col">
                    <div class="t-b-img">
                        <img src="assets/images/Melbourne-Book-Mockup-01-1.webp" alt="Custom Melbourne book mockup showcasing professional design" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="process-sec">
        <div class="container">
            <div class="row row1 mb20   ">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Our Streamlined <span>Book Publishing Process</span></h2>
                    </div>
                    <div class="head30 mb20">
                        <h3>From Manuscript to<span> Published Book in 4 Simple Steps</span></h3>
                    </div>
                </div>
            </div>

            <div class="row row2">
                <div class="col-md-4 left-col">
                    <div class="our-proce-wrap">
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Free Consultation & Manuscript Review</h2>
                            </div>
                            <div class="para16">
                                <p>
                                    Submit your manuscript and chat with our publishing experts about your goals. We’ll
                                    discuss what services your book needs, provide transparent pricing, and create a
                                    customised publishing plan that actually fits your timeline and budget.
                                </p>
                            </div>
                        </div>

                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Professional Editing</h2>
                            </div>
                            <div class="para16">
                                <p>
                                    Our editors review your manuscript thoroughly, fixing grammar, improving flow,
                                    strengthening structure, whatever your book needs. You’ll receive tracked changes
                                    showing every edit and detailed feedback explaining our recommendations.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mid-col">
                    <div class="proceimg">
                        <img src="assets/images/Melbourne-Book-log.png" alt="Melbourne Book logo with modern typography" loading="lazy">
                    </div>
                </div>
                <div class="col-md-4 right-col">
                    <div class="our-proce-wrap">
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Custom Cover Design</h2>
                            </div>
                            <div class="para16">
                                <p>
                                    Our designers create eye-catching covers that make readers stop scrolling and
                                    actually
                                    click. You’ll get multiple design concepts, unlimited revisions, and final files
                                    optimised for every platform where you’ll sell your book.
                                </p>
                            </div>
                        </div>

                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Professional Formatting</h2>
                            </div>
                            <div class="para16">
                                <p>
                                    We format your manuscript for flawless display across all devices and platforms,
                                    Kindle,
                                    Apple Books, print editions, everything. No blank pages, no weird spacing, no
                                    compatibility issues. Just professional formatting that works.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cost-sec about-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2><span>Why Choose Us</span> as Your Publishing Partner</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>
                            We’re not a faceless publishing mill churning out cookie-cutter books. You’ll work directly
                            with experienced professionals who actually care whether your book succeeds. We’ve been
                            doing this for nearly two decades, so we know what works and what wastes your money. You get
                            honest advice, if we think you’re not ready to publish yet, we’ll tell you why and help you
                            get there instead of just taking your money. Our pricing is transparent with no hidden fees.
                            You keep 100% of your rights and royalties. We’re based in Melbourne but serve authors
                            worldwide, combining local expertise with global reach. Most importantly, we treat your book
                            like it matters, because it does.
                        </p>
                    </div>
                    <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                        data-target="#exampleModalCenter">Work With Us</a>
                </div>
                <div class="col-md-6 right-col">
                    <div class="about-img">
                        <img src="assets/images/partner.webp" alt="Melbourne Print & Publish business partner collaboration" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head45">
                        <h2>Our<span> Services</span></h2>
                    </div>
                </div>
            </div>
            <?php include("shortcode/our_service_list.php"); ?>
        </div>
    </section>

    <section class="cost-sec about-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="about-img">
                        <img src="assets/images/company.webp" alt="Melbourne Print & Publish company office" loading="lazy">
                    </div>
                </div>

                <div class="col-md-6 right-col">
                    <div class="head45 mb20">
                        <h2><span>About</span> Our Publishing Company</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>
                            We started helping Melbourne authors publish their books nearly two decades ago because we
                            saw too many great manuscripts sitting unpublished, not because they weren’t good enough,
                            but because traditional publishing is exclusive and self-publishing felt overwhelming. We
                            built a company that makes professional publishing accessible without compromising quality.
                            Our team includes experienced editors who’ve worked on bestsellers, designers who understand
                            what sells in every genre, marketers who know how to get books discovered, and ghostwriters
                            who can bring any story to life. We’re not the biggest publishing company in Melbourne, but
                            we’re probably the one that’ll actually care about your book’s success as much as you do.
                        </p>
                    </div>
                    <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                        data-target="#exampleModalCenter">Learn More About Us</a>
                </div>
            </div>
        </div>
    </section>

    <section class="counter-sec">
        <div class="container">
            <?php include("shortcode/counter.php"); ?>
        </div>
    </section>

    <section class="logo-marquee-sec">
        <div class="container-fluids">
            <div class="row">
                <div class="col-md-12">
                    <?php include("shortcode/logo_marquee.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="book-store-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>Our <span>Commitment </span> to You</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>
                            We guarantee professional quality across all our services. If we don’t deliver what we
                            promised, we’ll make it right. Your success matters to us, we don’t consider the job done
                            until you’re genuinely happy with your published book.
                        </p>
                    </div>
                     <a href="javascript:void(0);" class="bttn1 openChatBtn">Live Chat</a>
                </div>
                <div class="col-md-6 right-col">
                    <div class="store-img">
                       <img src="assets/images/Melbourne-Book-Mockup.webp" alt="Melbourne book mockup showcasing custom book design" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonial-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head18 headbox mb20">
                        <h2>Testimonials</h2>
                    </div>
                    <div class="head45 mb20">
                        <h2>What Our Authors Say</h2>
                    </div>

                    <?php include("shortcode/testimonials_slider.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="faqs-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2><span>FAQs</span></h2>
                    </div>
                    <div class="head38 mb20">
                        <h3><span style="font-weight: 800;">We’re here to answer</span> all your questions.</h3>
                    </div>
                    <div class="para16 mb20">
                        <p>
                            Ahead are answers to commonly asked questions. If you have any additional inquiries, feel
                            free to reach out via email, live chat, or phone.
                        </p>
                    </div>
                    <div class="bttn-grp">
                        <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                            data-target="#exampleModalCenter">Get Started</a>
                         <a href="javascript:void(0);" class="bttn1 openChatBtn">Live Chat</a>
                    </div>
                </div>

                <div class="col-md-6 right-col">
                    <!-- Accordions Start -->
                    <div class="faqs">
                        <div id="accordions" class="accordion">

                            <!-- Item 1 -->
                            <div class="card">
                                <div id="heading1" class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse1" role="button" tabindex="0" aria-expanded="true" aria-controls="collapse1">
                                        <span><strong>Q1:</strong> Which Melbourne print and publish companies offer
                                            design assistance?</span>
                                    </div>
                                </div>

                                <div id="collapse1" class="collapse show">
                                    <div class="card-body text">
                                        <p>We provide complete design assistance as part of our publishing services.
                                            This includes professional cover design with multiple concepts and unlimited
                                            revisions, interior book layout and formatting for both print and eBook
                                            versions, marketing graphics and promotional materials, and series branding
                                            if you’re publishing multiple books. Our designers work across all genres
                                            and understand what sells in each category. You’re not limited to templates
                                            or DIY tools, you get actual custom design created specifically for your
                                            book by experienced professionals. Design assistance is included in our
                                            publishing packages, or you can purchase it as a standalone service if
                                            you’ve already handled other aspects of publishing yourself.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="card">
                                <div id="heading2" class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse2" role="button" tabindex="0" aria-expanded="false" aria-controls="collapse2">
                                        <span><strong>Q2:</strong> How long does it take to print and publish books in
                                            Melbourne?</span>
                                    </div>
                                </div>

                                <div id="collapse2" class="collapse">
                                    <div class="card-body text">
                                        <p>
                                            Publishing timelines depend on which services your book needs. If your
                                            manuscript is already edited with a finished cover, we can have your book
                                            live on Amazon and other platforms within 1-2 weeks. Complete publishing
                                            packages including editing, cover design, and formatting typically take 4-6
                                            weeks total. Print books take slightly longer because print-on-demand
                                            services need a few extra days to approve files and print review copies.
                                            Rush services are available if you’ve got a specific launch deadline, we’ve
                                            published books in as little as 2 weeks when necessary, though we prefer
                                            giving your book the time it deserves for proper quality control and setup.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="card">
                                <div id="heading3" class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse3" role="button" tabindex="0" aria-expanded="false" aria-controls="collapse3">
                                        <span><strong>Q3:</strong> What are the usual turnaround times for print and
                                            publish companies in Melbourne?</span>
                                    </div>
                                </div>

                                <div id="collapse3" class="collapse">
                                    <div class="card-body text">
                                        <p>
                                            Our standard turnaround is 4-6 weeks from manuscript submission to your book
                                            being live and available for purchase globally. This includes all editing,
                                            cover design with revisions, formatting for multiple platforms, ISBN
                                            registration, and distribution setup. Individual services have shorter
                                            timelines, cover design alone takes 2-3 weeks, editing takes 1-3 weeks
                                            depending on manuscript length and service level, formatting takes 3-5 days.
                                            If you need faster turnaround, we offer rush services that can reduce the
                                            total timeline to 2-3 weeks, though this depends on our current workload and
                                            your book’s specific requirements. We provide exact timelines during your
                                            free consultation based on your manuscript’s current state and your
                                            deadline.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 4 -->
                            <div class="card">
                                <div id="heading4" class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse4" role="button" tabindex="0" aria-expanded="false" aria-controls="collapse4">
                                        <span><strong>Q4:</strong> Can I get same-day print and publish services in
                                            Melbourne?</span>
                                    </div>
                                </div>

                                <div id="collapse4" class="collapse">
                                    <div class="card-body text">
                                        <p>
                                            Same-day publishing isn’t realistic if you want professional quality.
                                            Quality
                                            editing, professional cover design, and proper formatting simply take time
                                            to do properly. Rushing these steps results in amateur-looking books with
                                            errors that damage your credibility and sales. That said, if your book is
                                            already professionally edited with a finished cover and you just need
                                            formatting and distribution setup, we can get your eBook live within 2-3
                                            business days. Print books take longer because print-on-demand services need
                                            time to review files and produce proof copies. We’re happy to work with
                                            tight deadlines when possible, but we won’t sacrifice quality for speed
                                            because that ultimately hurts your book’s success.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 5 -->
                            <div class="card">
                                <div id="heading5" class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse5" role="button" tabindex="0" aria-expanded="false" aria-controls="collapse5">
                                        <span><strong>Q5:</strong> Are there print and publish companies in Melbourne
                                            that offer online ordering?</span>
                                    </div>
                                </div>

                                <div id="collapse5" class="collapse">
                                    <div class="card-body text">
                                        <p>
                                            Yes, our entire publishing process can be managed online. You can submit
                                            your
                                            manuscript through our website, complete consultations via video call,
                                            review designs and edits digitally, approve everything online, and receive
                                            all final files electronically. You never need to visit our office in person
                                            unless you want to. This online system works perfectly for Melbourne authors
                                            and also lets us serve authors throughout Australia and internationally.
                                            Payment is handled securely online, all communication happens via email or
                                            video calls, and file sharing is done through secure platforms. The only
                                            thing that isn’t online is the actual printing of physical books, but that’s
                                            handled automatically by print-on-demand services once your files are
                                            uploaded.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 6 -->
                            <div class="card">
                                <div id="heading6" class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse6" role="button" tabindex="0" aria-expanded="false" aria-controls="collapse6">
                                        <span><strong>Q6:</strong> What printing formats and publishing options are
                                            offered by Melbourne companies?</span>
                                    </div>
                                </div>

                                <div id="collapse6" class="collapse">
                                    <div class="card-body text">
                                        <p>
                                            We publish books in all major formats including Kindle eBooks for Amazon,
                                            ePub files for Apple Books, Kobo, Google Play and other retailers,
                                            print-on-demand paperbacks in multiple trim sizes, print-on-demand
                                            hardcovers with dust jackets or case laminate, and large print editions for
                                            accessibility. You can publish in one format or all of them, most authors do
                                            both eBook and paperback as a minimum. We handle all the technical
                                            requirements for each format, creating optimised files that meet every
                                            platform’s specifications. Print books can be published in standard sizes
                                            like 5×8, 5.5×8.5, 6×9, or custom sizes if your book requires it. Color or
                                            black-and-white interior, different paper types, various binding options,
                                            we’ll guide you through choosing what’s best for your specific book and
                                            budget.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Accordions End -->
                </div>
            </div>
        </div>
    </section>

    <section class="starting-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left">
                    <div class="head38 mb20">
                        <h2>Stop Staring at That Blank Page.</h2>
                    </div>
                    <div class="para16">
                        <p>Don’t let your story stay trapped in your head.<br>It deserves more than a ‘someday.’</p>
                    </div>
                    <div class="start-img">
                        <img src="assets/images/contimage.webp" alt="High-quality print and publishing services in Melbourne" loading="lazy">
                    </div>
                </div>
                <div class="col-md-6 right-col">
                    <div class="head30 mb20">
                        <h2>Tell us about your Book!</h2>
                    </div>
                    <?php include("foam/foam.php"); ?>
                </div>
            </div>
        </div>
    </section>



    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>