<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('book-design'); ?>    <?php include("includes/style.php"); ?>
</head>

<body class="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?> service-page">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFZRKR97" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include("includes/disclaimer.php"); ?>

    <header id="masthead" class="site-header">
        <?php include("includes/header.php"); ?>
    </header>

    <!-- Hero Section - Book Cover Design Focus -->
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="home-banner-cont">
                        <div class="head45 mb20">
                            <h1>Professional Book Cover Design Services in Melbourne</h1>
                        </div>
                        <div class="para16 mb20">
                            <p>Your book cover is the first thing readers see, make it count. Our professional book
                                designers create stunning, genre-appropriate covers that grab attention and drive sales.
                                We've designed covers for Melbourne authors and writers worldwide across every genre,
                                delivering custom designs that stand out in crowded marketplaces like Amazon. Whether
                                you need an eBook cover, print book design, or complete series branding, our experienced
                                designers combine creative excellence with commercial understanding. You'll receive
                                multiple design concepts, unlimited revisions, and final files optimised for every
                                platform, all tailored specifically to your book and target audience.</p>
                        </div>
                        <div class="bttn-grp">
                            <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                                data-target="#exampleModalCenter">Get Started</a>
                             <a href="javascript:void(0);" class="bttn1 openChatBtn">Live Chat</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 right-col">
                    <div class="banner-img">
                        <img src="assets/images/MP&P_Services_BookDesign_01.webp"
                            alt="Professional book cover designer in Melbourne">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lead Form Section -->
    <section class="banner-foam-sec serv-sec1">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php include("foam/banner_foam.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Customised Book Cover Designs Section -->
    <section class="book-store-sec serv-sec2">
        <div class="container">
            <div class="row">
                <div class="col-md-6 right-col">
                    <div class="store-img">
                        <img src="assets/images/Melbourne-Book-Mockup.webp" alt="Melbourne book mockup showcasing custom book design">
                    </div>
                </div>
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>Customised <span>Book Cover Designs</span></h2>
                    </div>
                    <div class="para16 mb20">
                        <p>Our team of talented and creative designers specialises in various print and digital design
                            genres. Whether you need a children's book cover, a project book front page, or an online
                            cover, we've got you covered.</p>
                        <!--<p>Book covers aren't just decoration, they're marketing tools that determine whether readers-->
                        <!--    click on your book or scroll past it. Professional book cover design combines artistic skill-->
                        <!--    with commercial understanding of what sells in your specific genre. We offer comprehensive-->
                        <!--    design services covering every aspect of book presentation, ensuring your cover works hard-->
                        <!--    to attract your ideal readers.</p>-->
                    </div>
                     <a href="javascript:void(0);" class="bttn1 openChatBtn">Live Chat</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges Section -->
    <section class="logo-sec serv-sec3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php include("shortcode/logo_list.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Complete Book Cover Design Services -->
    <section class="complete-sec serv-sec4">
        <div class="container">
            <div class="row row1">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Complete <span>Book Cover Design Services</span> for Every Publishing Need</h2>
                    </div>
                    <div class="head30 mb20">
                        <h3>From <span>eBook Covers</span> to <span>Complete Series Branding</span></h3>
                    </div>
                    <div class="para16 mb20">
                        <p>Book covers aren't just decoration, they're marketing tools that determine whether readers
                            click on your book or scroll past it. Professional book cover design combines artistic skill
                            with commercial understanding of what sells in your specific genre. We offer comprehensive
                            design services covering every aspect of book presentation, ensuring your cover works hard
                            to attract your ideal readers.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="comp-grid">
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>eBook Cover Design</h3>
                            </div>
                            <div class="para16">
                                <p>Digital book covers need to be instantly eye-catching at thumbnail size since most
                                    readers first see them as tiny images in search results. Our eBook cover designs use
                                    bold typography, clear imagery, and strategic color choices that pop on screens and
                                    remain readable even when displayed small. We design specifically for digital
                                    platforms, understanding what works on Amazon, Apple Books, Kobo, and other
                                    retailers. Your eBook cover includes genre-appropriate design elements,
                                    platform-compliant specifications, and optimisation for both thumbnail and full-size
                                    display.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Print Book Cover Design</h3>
                            </div>
                            <div class="para16">
                                <p>Print covers require more complex design including front cover, spine, and back cover
                                    as a single wrap-around design. Our print book designers create complete covers that
                                    look professional on bookstore shelves and in readers' hands. We handle all
                                    technical requirements including spine width calculations based on page count, bleed
                                    and trim specifications for different printing services, barcode placement, ISBN
                                    integration, and wraparound design that works cohesively. You'll receive print-ready
                                    PDFs that meet specifications for Amazon KDP, IngramSpark, and other print-on-demand
                                    services.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Book Cover Redesign & Refresh</h3>
                            </div>
                            <div class="para16">
                                <p>Sometimes published books need fresh covers to boost sales or better match genre
                                    expectations. Our redesign services give your existing book a professional makeover
                                    that attracts new readers while maintaining any brand recognition you've already
                                    built. We analyze why your current cover isn't working, research current trends in
                                    your genre, and create designs that better position your book in today's market.
                                    Redesigns often result in immediate sales increases as your book finally looks
                                    competitive.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Series Design & Branding</h3>
                            </div>
                            <div class="para16">
                                <p>Book series need visual consistency so readers instantly recognize your brand. Our
                                    series design creates cohesive looks across multiple books using consistent
                                    typography, color schemes, layout structures, and design elements while keeping each
                                    cover unique enough to be distinguished. We design with the full series in mind,
                                    ensuring book one through book ten maintain brand consistency. This includes
                                    creating design templates for future installments and providing brand guidelines for
                                    maintaining consistency.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Children's Book Illustration & Cover Design</h3>
                            </div>
                            <div class="para16">
                                <p>Children's books require special consideration, illustrations need to appeal to kids
                                    while design appeals to parents making purchasing decisions. Our children's book
                                    designers create age-appropriate, engaging covers with vibrant illustrations,
                                    playful typography, and designs that work for picture books, early readers, middle
                                    grade, and young adult categories. We understand different age group expectations
                                    and design accordingly.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Interior Book Design & Layout</h3>
                            </div>
                            <div class="para16">
                                <p>Professional books need professional interiors. Our interior design services create
                                    polished layouts for chapter openings, section breaks, headers and footers, page
                                    numbers, and formatting that’s easy to read and visually appealing. We design
                                    interiors for both print books and eBooks, ensuring your content looks as
                                    professional inside as the cover promises.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>3D Book Mockups</h3>
                            </div>
                            <div class="para16">
                                <p>Professional mockups showcase your book in realistic settings for marketing purposes.
                                    We create 3D renderings showing your book as a physical object, standing upright,
                                    lying flat, held in hands, stacked in series, or displayed on shelves. These mockups
                                    are essential for websites, social media marketing, and advertising where showing
                                    your book as a real product increases perceived value and professionalism.
                                </p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book-open"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Book Marketing Graphics</h3>
                            </div>
                            <div class="para16">
                                <p>Extend your cover design into marketing materials that maintain visual consistency.
                                    We create bookmarks, social media graphics, website banners, email newsletter
                                    headers, promotional posters, and other marketing materials using your cover design
                                    elements. This creates cohesive branding across all your promotional efforts.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="h-port-sec serv-sec5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Our <span>Portfolio</span></h2>
                    </div>
                    <?php include("shortcode/portfolio_slider.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Professional Book Cover Design for All Genres -->
    <section class="genre-sec serv-sec6">
        <div class="container">
            <div class="row row1">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Professional <span>Book Cover Design</span> for All Genres</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>As one of Melbourne's premier book cover design agencies, we create covers across every
                            literary genre and category. Whether you're publishing a thriller, business book, or
                            children's story, our experienced designers understand genre-specific visual conventions,
                            reader expectations, and what makes covers successful in each category. We don't use generic
                            templates, every cover is custom-designed to position your book competitively in your
                            specific market.</p>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-4">
                    <div class="genre-card">
                        <div class="head26">
                            <h2>Fiction Cover Design</h2>
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
                                Children's Books & Middle Grade
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="genre-card">
                        <div class="head26">
                            <h2>Non-Fiction Cover Design</h2>
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
                                </svg>
                                Memoirs & Autobiographies
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
            <a class="bttn1" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog" data-target="#exampleModalCenter">Get Your
                Genre Designed Professionally</a>
        </div>
    </section>

    <!-- How Much Does Book Cover Design Cost in Melbourne? -->
    <section class="cost-sec serv-sec7">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>How Much Does <span>Book Cover Design Cost</span> in Melbourne?</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>Book cover design costs vary based on complexity, design type, and what's included. Simple
                            eBook covers with typography and stock imagery cost less than fully illustrated children's
                            book covers or complete print book wraparound designs. Series branding requires more
                            investment than single covers. Custom illustration adds to costs compared to stock
                            photo-based designs.</p>
                        <p>What you're really paying for is expertise, designers who understand your genre, create
                            covers that actually sell books, and deliver professional files that work across all
                            platforms. Cheap pre-made templates or amateur designers might save money upfront but cost
                            you thousands in lost sales because readers won't click on unprofessional covers. Overpriced
                            doesn't guarantee quality either, some designers charge premium rates without delivering
                            premium results.</p>
                        <p>We provide honest design quotes based on what your specific book needs. During your free
                            consultation, we'll discuss your genre, design complexity, and budget, then recommend
                            appropriate services with transparent pricing. You'll know exactly what's included, how many
                            concepts and revisions you'll receive, and what final files you'll get. No hidden fees, no
                            surprises, just professional design at fair prices that reflect the actual work involved.
                        </p>
                    </div>
                    <a class="bttn1" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                        data-target="#exampleModalCenter">Get Your Free Design Quote</a>
                </div>
                <div class="col-md-6 right-col">
                    <div class="t-b-img">
                        <img src="assets/images/MP&P_Services_BookDesign_03.webp" alt="Custom Melbourne book mockup showcasing professional design">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Professional Book Cover Design Process -->
    <section class="process-sec serv-sec8">
        <div class="container">
            <div class="row row1 mb20">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Professional <span>Book Cover Design Process</span></h2>
                    </div>
                    <div class="para16">
                        <p>Getting a professional book cover designed should be exciting, not stressful. Our proven
                            design process ensures you're involved, informed, and thrilled with the final result through
                            a collaborative approach that values your input while providing expert guidance. We've
                            designed thousands of covers for Melbourne authors and internationally, refining our
                            workflow to consistently deliver covers that authors love and readers can't resist clicking.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-4 left-col">
                    <div class="our-proce-wrap">
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Brief & Concept Discussion</h2>
                            </div>
                            <div class="para16">
                                <p>We start by understanding your book, target audience, genre conventions, and design
                                    preferences. You'll complete a detailed design brief covering your book's content,
                                    mood and tone, comparable titles you admire, design elements you like or dislike,
                                    and any specific ideas you have. We research your genre's current cover trends,
                                    analyze competitor covers, and identify opportunities to make your book stand out
                                    while still fitting genre expectations. This groundwork ensures designs align with
                                    your vision and market realities.</p>
                            </div>
                        </div>
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Design Concepts Creation</h2>
                            </div>
                            <div class="para16">
                                <p>Your assigned designer creates multiple cover concepts (typically 3-4 distinct
                                    designs) exploring different visual approaches to your book. These aren't minor
                                    variations, they're genuinely different design directions giving you real choices.
                                    Each concept includes professional typography, compelling imagery or illustrations,
                                    genre-appropriate styling, and complete front cover design. You'll see how different
                                    approaches could position your book, helping you choose the direction that best
                                    represents your work.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mid-col">
                    <div class="proceimg">
                        <img src="assets/images/Melbourne-Book-log.png" alt="Melbourne Book logo with modern typography">
                    </div>
                </div>
                <div class="col-md-4 right-col">
                    <div class="our-proce-wrap">
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Revisions & Refinement</h2>
                            </div>
                            <div class="para16">
                                <p>You choose your preferred concept, then we refine it based on your feedback. This is
                                    where your cover transforms from good to perfect through unlimited revision rounds.
                                    Want different typography? Different colors? Different imagery? Adjusted layout? We
                                    make every requested change until you're completely satisfied. Most covers require
                                    2-3 revision rounds, but we don't limit you, we keep refining until you genuinely
                                    love your cover.</p>
                            </div>
                        </div>
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Finalization & File Delivery</h2>
                            </div>
                            <div class="para16">
                                <p>Once you approve the final design, we prepare all necessary file formats for your
                                    publishing needs. This includes high-resolution files for print, optimised files for
                                    eBook platforms, thumbnail-optimised versions, and files in multiple formats (JPG,
                                    PNG, PDF). We ensure every file meets exact specifications for Amazon KDP,
                                    IngramSpark, and other publishing platforms. You receive everything needed to upload
                                    your cover immediately.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Published Categories -->
    <section class="category-sec serv-sec9">
        <div class="container">
            <div class="row row1">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Our Top <span>Published Categories</span></h2>
                    </div>
                </div>
            </div>
            <div class="row row2 mb20">
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Romance.png" alt="Romance genre book icon for Melbourne Print & Publish"></div>
                        <div class="head18">
                            <h3>Romance</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Thriller.png" alt="Thriller genre book icon for Melbourne Print & Publish"></div>
                        <div class="head18">
                            <h3>Memoirs and Autobiography</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/fantasy.png" alt="Fantasy genre book icon for Melbourne Print & Publish"></div>
                        <div class="head18">
                            <h3>Fantasy</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Since-Fiction.png" alt="Science fiction genre book icon for Melbourne Print & Publish"></div>
                        <div class="head18">
                            <h3>Science fiction</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Since.png" alt="General publishing icon for Melbourne Print & Publish"></div>
                        <div class="head18">
                            <h3>Leadership</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Adventure.png" alt="Adventure genre book icon for Melbourne Print & Publish"></div>
                        <div class="head18">
                            <h3>Self help</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Counter Section -->
    <section class="counter-sec serv-sec10">
        <div class="container">
            <?php include("shortcode/counter.php"); ?>
        </div>
    </section>

    <!-- Why Professional Book Cover Design Matters -->
    <section class="book-store-sec serv-sec11">
        <div class="container">
            <div class="row">
                <div class="col-md-6 right-col">
                    <div class="store-img">
                        <img src="assets/images/MP&P_Services_BookDesign_02.webp" alt="Why cover design matters">
                    </div>
                </div>
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>Why <span>Professional Book Cover Design</span> Matters</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>Readers judge books by their covers, literally. Research shows readers decide whether to
                            click on your book in less than three seconds based entirely on the cover. Amateur or DIY
                            covers immediately signal "unprofessional" to readers, making them scroll past no matter how
                            good your actual writing is. Professional cover design isn't vanity, it's essential
                            marketing that directly impacts your book's success.</p>
                        <p>Your cover competes with thousands of other books in Amazon search results, category pages,
                            and promotional emails. Generic template covers or amateur designs get ignored because they
                            don't stand out or they look untrustworthy. Professional designers understand genre visual
                            language, the specific fonts, colors, imagery, and layouts that signal "this is a quality
                            thriller" or "this is a serious business book." Getting these signals wrong means readers
                            interested in your genre won't even consider your book.</p>
                        <p>The investment in professional cover design pays for itself through increased sales. A book
                            with a professional cover sells significantly more copies than the same book with an amateur
                            cover, often 3-5 times more in the first month alone. Your cover is working 24/7 to convince
                            readers to click, read your description, and buy. It's the single most important marketing
                            asset you have. Making it professional isn't optional if you want your book to succeed.</p>
                    </div>
                    <!-- <a class="bttn1" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog" data-target="#exampleModalCenter">Start Publishing Your Book</a> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Our Other Services -->
    <section class="service-sec serv-sec12">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head45">
                        <h2>Our <span>Other Services</span></h2>
                    </div>
                </div>
            </div>
            <?php include("shortcode/our_service_list.php"); ?>
        </div>
    </section>

    <!-- Logo Marquee Section -->
    <section class="logo-marquee-sec serv-sec13">
        <div class="container-fluids">
            <div class="row">
                <div class="col-md-12">
                    <?php include("shortcode/logo_marquee.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-sec serv-sec14">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="head38 mb20">
                        <h2>All-Inclusive Custom Book Design Services</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>
                            We help authors turn their manuscripts into professionally designed books that look
                            polished, engaging, and ready for readers. From layout and typography to cover design and
                            formatting, our focus is on creating visually compelling books that stand out in print and
                            digital formats.
                        </p>
                    </div>
                    <div class="bttn-grp">
                        <a class="bttn2" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                            data-target="#exampleModalCenter">Schedule A Consultation</a>
                         <a href="javascript:void(0);" class="bttn3 openChatBtn">Live Chat</a>
                         
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="faqs-sec serv-sec15">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2><span>FAQs</span></h2>
                    </div>
                    <div class="head38 mb20">
                        <h3><span style="font-weight: 800;">We're here to answer</span> all your questions.</h3>
                    </div>
                    <div class="para16 mb20">
                        <p>Ahead are answers to commonly asked questions. If you have any additional inquiries, feel
                            free to reach out via email, live chat, or phone.</p>
                    </div>
                    <div class="bttn-grp">
                        <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                            data-target="#exampleModalCenter">Get Started</a>
                         <a href="javascript:void(0);" class="bttn1 openChatBtn">Live Chat</a>
                    </div>
                </div>
                <div class="col-md-6 right-col">
                    <div class="faqs">
                        <div id="accordions" class="accordion">
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse1">
                                        <span><strong>Q1:</strong> What is the average cost of book design?</span>
                                    </div>
                                </div>
                                <div id="collapse1" class="collapse show">
                                    <div class="card-body text">
                                        <p>Book design costs depend on what you need, eBook covers, print wraparound
                                            designs, or illustrated children's books all have different pricing. Simple
                                            eBook covers are more affordable, while print books requiring spine and back
                                            cover design cost more due to additional complexity. Children's books with
                                            custom illustrations represent the highest investment because of extensive
                                            artistic work. We don't use one-size-fits-all pricing because every book has
                                            unique requirements. During your free consultation, we'll discuss your
                                            specific project and provide a detailed, transparent quote showing exactly
                                            what's included, design concepts, revision rounds, final files, and any
                                            extras. No hidden fees, just honest pricing reflecting the actual work your
                                            book needs.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse2">
                                        <span><strong>Q2:</strong> How much does an illustrator charge for a
                                            book?</span>
                                    </div>
                                </div>
                                <div id="collapse2" class="collapse">
                                    <div class="card-body text">
                                        <p>Illustration costs vary based on style complexity, number of illustrations
                                            needed, detail level, and whether you need full-page artwork or spot
                                            illustrations. A 32-page picture book requiring 15-20 full-color
                                            illustrations costs significantly more than a chapter book needing just a
                                            few simple spot illustrations. Custom illustration is skilled artistic work
                                            that takes time, rushing or underpaying usually means disappointing results.
                                            We provide customised quotes after understanding your book’s specific needs,
                                            preferred illustration style, and timeline. During consultation, we’ll show
                                            style examples, discuss your vision, and provide transparent pricing. You’ll
                                            know exactly what you’re paying for and what illustration quality to expect
                                            before committing to anything.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse3">
                                        <span><strong>Q3:</strong> How do I get someone to illustrate a book?</span>
                                    </div>
                                </div>
                                <div id="collapse3" class="collapse">
                                    <div class="card-body text">
                                        <p>Getting your book illustrated through us is simple. Submit your manuscript or
                                            concept via our website with details about your illustration needs, full
                                            picture book illustrations, chapter book spots, or cover artwork. Include
                                            style preferences, character descriptions, and budget. We’ll review within
                                            48 hours and match you with an illustrator whose style suits your book.
                                            You’ll see their portfolio before starting. The process includes concept
                                            sketches for approval, detailed illustration with ongoing feedback,
                                            revisions until satisfied, and final file delivery. We coordinate
                                            everything, you just provide input and approve work. No complicated agent
                                            negotiations or unclear processes, just straightforward professional
                                            illustration services.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse4">
                                        <span><strong>Q4:</strong> How much does an illustration agent cost?</span>
                                    </div>
                                </div>
                                <div id="collapse4" class="collapse">
                                    <div class="card-body text">
                                        <p>We’re not an illustration agent, we provide illustration services directly
                                            without agent fees or commission percentages. You pay for the actual
                                            illustration work, not representation fees. Our project management is
                                            included in illustration costs, covering coordination between you and
                                            illustrators, timeline management, quality control, and final file delivery.
                                            This approach gives you professional illustration with expert coordination
                                            at lower costs than traditional agent representation. During consultation,
                                            we’ll explain exactly what you’re paying for, illustration work itself,
                                            included revisions, project coordination, and file delivery. Everything is
                                            transparent with no percentage-based fees or hidden agent commissions. Just
                                            straightforward pricing for professional illustration services.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse5">
                                        <span><strong>Q5:</strong> How long does it take to illustrate a 32-page
                                            children's book?</span>
                                    </div>
                                </div>
                                <div id="collapse5" class="collapse">
                                    <div class="card-body text">
                                        <p>Illustrating a 32-page picture book typically takes 6-10 weeks depending on
                                            style complexity and revisions. The timeline includes initial concept
                                            sketches (1-2 weeks), your feedback and approval, detailed illustration
                                            creation for all pages (3-5 weeks), revision rounds (1-2 weeks), and final
                                            file preparation (1 week). Complex, detailed styles take longer than simpler
                                            illustrations. Extensive revisions extend timelines accordingly. We provide
                                            realistic schedules during consultation based on your chosen style and book
                                            requirements. Rush services are available for tighter deadlines without
                                            compromising quality. Throughout the process, you’ll receive regular updates
                                            and preview batches of illustrations, ensuring the final book matches your
                                            vision perfectly.

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include("includes/footer.php"); ?>
    <?php include("includes/script.php"); ?>
</body>

</html>