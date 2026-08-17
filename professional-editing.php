<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="robots" content="follow, index">
    <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('professional-editing'); ?>    <?php include("includes/style.php"); ?>
</head>

<body class="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?> service-page">

    <!-- Google Tag Manager (noscript) -->
    <?php echo mpp_seo_gtm_noscript(); ?>
    <!-- End Google Tag Manager (noscript) -->

    <?php include("includes/disclaimer.php"); ?>

    <header id="masthead" class="site-header">
        <?php include("includes/header.php"); ?>
    </header>

    <!-- Hero Section - Book Editing Focus -->
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="home-banner-cont">
                        <div class="head45 mb20">
                            <h1>Professional Book Editing Services in Melbourne</h1>
                        </div>
                        <div class="para16 mb20">
                            <p>Transform your manuscript into a polished, publication-ready masterpiece with our expert
                                book editing services. We've helped authors in Melbourne refine their work through
                                comprehensive editing that addresses everything from story structure to grammar. Our
                                experienced editors work across all genres, delivering professional results that lift
                                your writing and engage readers. Whether you need developmental editing, copy editing,
                                or proofreading, we provide the expertise your manuscript deserves.</p>
                        </div>
                        <div class="bttn-grp">
                            <a class="bttn" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                                data-target="#exampleModalCenter">Get Your Manuscript Edited</a>
                            <a href="javascript:void(0);" class="bttn1 openChatBtn">Chat With an Editor</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 right-col">
                    <div class="banner-img">
                        <img src="assets/images/ProfessionalEditing.webp" alt="Professional book editor in Melbourne" width="2533" height="1688" loading="lazy" decoding="async">
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

    <!-- Complete Book Editing Services for Every Manuscript Need -->
    <section class="book-store-sec serv-sec2">
        <div class="container">
            <div class="row">
                <div class="col-md-6 right-col">
                    <div class="store-img">
                        <img src="assets/images/story.webp" alt="Melbourne book mockup showcasing custom book design" width="2145" height="1552" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>Professional <span>Book Editing Services</span> for Every Story</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>
                            Every story has its own voice and direction, so our editing support is tailored to refine
                            clarity, enhance structure, and improve overall flow, helping your writing move smoothly
                            from first draft to a polished, publication-ready piece.

                        </p>
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

    <!-- Complete Book Editing Services - Detailed -->
    <section class="complete-sec serv-sec4">
        <div class="container">
            <div class="row row1">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Complete <span>Book Editing Services</span> for Every Manuscript Need</h2>
                    </div>
                    <div class="head30 mb20">
                        <h3>From <span>Developmental Editing</span> to <span>Final Proofreading</span></h3>
                    </div>
                    <div class="para16 mb20">
                        <p>Every manuscript is different, and editing isn't one-size-fits-all. We offer comprehensive
                            editing services tailored to where your manuscript is in the writing process and what it
                            needs to reach publication quality.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="comp-grid">
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-pen-fancy"
                                    viewBox="0 0 512 512">
                                    <path
                                        d="M79.18 282.94a32.005 32.005 0 0 0-20.24 20.24L0 480l4.69 4.69 92.89-92.89c-.66-2.56-1.18-5.18-1.18-7.94 0-17.67 14.33-32 32-32s32 14.33 32 32-14.33 32-32 32c-2.76 0-5.38-.52-7.94-1.18L64 508.31 512 512 512 0 79.18 282.94zM464 64l-48 48-48-48 48-48 48 48z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Developmental Editing</h3>
                            </div>
                            <div class="para16">
                                <p>This is big-picture editing for manuscripts that need structural work. Our
                                    developmental editors analyse your story's foundation, plot structure, character
                                    development, pacing, narrative arc, and thematic consistency. If your manuscript
                                    feels like something's missing or not quite working, developmental editing
                                    identifies exactly what needs fixing. We provide detailed feedback on story logic,
                                    character motivations, scene structure, and overall narrative flow. Ideal for first
                                    drafts or manuscripts that have received feedback indicating structural issues.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-highlighter"
                                    viewBox="0 0 544 512">
                                    <path
                                        d="M0 480l148.6-148.6c-4.5-8.3-7.2-17.6-7.2-27.4 0-24.2 19.8-44 44-44s44 19.8 44 44c0 9.8-2.7 19.1-7.2 27.4L448 480l-448 32zM496 0L48 448l128 128L544 96 496 0z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Copy Editing</h3>
                            </div>
                            <div class="para16">
                                <p>Copy editing focuses on the sentence level, grammar, punctuation, spelling, syntax,
                                    and style consistency. Our copy editors refine your writing while preserving your
                                    unique voice, correcting errors, improving clarity, and ensuring consistency
                                    throughout your manuscript. This service is perfect for manuscripts that are
                                    structurally sound but need professional polish before publication.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-align-left"
                                    viewBox="0 0 448 512">
                                    <path
                                        d="M288 44v40c0 8.84-7.16 16-16 16H16c-8.84 0-16-7.16-16-16V44c0-8.84 7.16-16 16-16h256c8.84 0 16 7.16 16 16zM0 172v40c0 8.84 7.16 16 16 16h416c8.84 0 16-7.16 16-16v-40c0-8.84-7.16-16-16-16H16c-8.84 0-16 7.16-16 16zm416 212H16c-8.84 0-16 7.16-16 16v40c0 8.84 7.16 16 16 16h416c8.84 0 16-7.16 16-16v-40c0-8.84-7.16-16-16-16z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Line Editing</h3>
                            </div>
                            <div class="para16">
                                <p>Line editing sits between developmental and copy editing, focusing on how your story
                                    is told at the sentence and paragraph level. Our line editors improve flow, rhythm,
                                    word choice, and emotional impact. We tighten prose, eliminate redundancy, enhance
                                    descriptions, and ensure every sentence serves your story. This editing level
                                    transforms good writing into great writing.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-check-double"
                                    viewBox="0 0 448 512">
                                    <path
                                        d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.7-98.1l184-184c6.2-6.2 6.2-16.4 0-22.6l-22.6-22.6c-6.2-6.2-16.4-6.2-22.6 0L184 302.7l-70.1-70.1c-6.2-6.2-16.4-6.2-22.6 0l-22.6 22.6c-6.2 6.2-6.2 16.4 0 22.6l104 104c6.2 6.3 16.4 6.3 22.6 0z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Proofreading</h3>
                            </div>
                            <div class="para16">
                                <p>Proofreading is the final quality check before publication. Our proofreaders catch
                                    typos, formatting inconsistencies, grammatical errors, and layout issues that
                                    slipped through previous editing rounds. This is essential for manuscripts that have
                                    already been edited but need one last professional review to ensure they're
                                    flawless.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-clipboard-list"
                                    viewBox="0 0 384 512">
                                    <path
                                        d="M336 64h-80c0-35.3-28.7-64-64-64s-64 28.7-64 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM96 424c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm96-192c13.3 0 24 10.7 24 24s-10.7 24-24 24-24-10.7-24-24 10.7-24 24-24z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Manuscript Critique & Assessment</h3>
                            </div>
                            <div class="para16">
                                <p>Not sure what level of editing your manuscript needs? Our manuscript assessment
                                    service provides professional feedback on your work's strengths, weaknesses, and
                                    recommended next steps. You'll receive a detailed editorial report outlining what's
                                    working, what needs improvement, and which editing services would benefit your
                                    manuscript most.</p>
                            </div>
                        </div>
                        <div class="comp-box">
                            <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-book"
                                    viewBox="0 0 448 512">
                                    <path
                                        d="M448 360V24c0-13.3-10.7-24-24-24H96C43 0 0 43 0 96v320c0 53 43 96 96 96h328c13.3 0 24-10.7 24-24v-16c0-7.5-3.5-14.2-8.9-18.7L448 360zM128 96c0-17.7 14.3-32 32-32h224c17.7 0 32 14.3 32 32v224c0 17.7-14.3 32-32 32H160c-17.7 0-32-14.3-32-32V96z">
                                    </path>
                                </svg></div>
                            <div class="head22">
                                <h3>Production & Publishing</h3>
                            </div>
                            <div class="para16">
                                <p>Coloring books are creative activity books featuring outlined illustrations designed
                                    for people to fill in with colors. They cater to all age groups, with themes ranging
                                    from simple shapes for children to intricate patterns and designs for adults.


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
                        <h2>Our<span> Portfolio</span></h2>
                    </div>
                    <?php include("shortcode/portfolio_slider.php"); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Professional Book Editing Services for All Genres -->
    <section class="genre-sec serv-sec6">
        <div class="container">
            <div class="row row1">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Professional <span>Book Editing Services</span> for All Genres</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>As one of Melbourne's leading book editing services, we edit manuscripts across every
                            literary genre and category. Whether you're writing literary fiction or a business guide,
                            our experienced editors understand your genre's conventions, reader expectations, and
                            publishing standards. We don't just fix errors, we help your book meet the specific
                            requirements that make it successful in your category.</p>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-4">
                    <div class="genre-card">
                        <div class="head26">
                            <h2>Fiction Editing</h2>
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
                            <h2>Non-Fiction Editing</h2>
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
                Genre Edited Professionally</a>
        </div>
    </section>

    <!-- How Much Does Book Editing Cost in Melbourne? -->
    <section class="cost-sec serv-sec7">
        <div class="container">
            <div class="row">
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>How Much Does <span>Book Editing Cost</span> in Melbourne?</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>Book editing costs in Melbourne vary significantly based on several factors, your
                            manuscript's length, current condition, the editing level you need, and your genre. A
                            50,000-word romance novel needing copy editing costs differently than a 120,000-word fantasy
                            manuscript requiring developmental editing. Manuscripts in good shape cost less than those
                            needing substantial work.</p>
                        <p>Here's what matters: cheap editing usually means inexperienced editors or rushed work that
                            misses issues. Expensive doesn't always mean better either. What you need is fair pricing
                            that reflects the actual work your manuscript requires.</p>
                        <p>We provide honest, transparent quotes based on reviewing your actual manuscript, not generic
                            per-word rates that might not reflect reality. During your free consultation, we'll assess
                            your manuscript's specific needs and provide a detailed quote with no hidden fees. You'll
                            know exactly what you're paying for and why, with no surprises.</p>
                    </div>
                    <a class="bttn1" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                        data-target="#exampleModalCenter">Get Your Free Editing Quote</a>
                </div>
                <div class="col-md-6 right-col">
                    <div class="t-b-img">
                        <img src="assets/images/cost.webp" alt="Custom Melbourne book mockup showcasing professional design" width="1047" height="1140" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Professional Book Editing Process -->
    <section class="process-sec serv-sec8">
        <div class="container">
            <div class="row row1 mb20">
                <div class="col-md-12">
                    <div class="head45 mb20">
                        <h2>Our Professional <span>Book Editing Process</span></h2>
                    </div>
                    <div class="head30 mb20">
                        <h3>Expert Manuscript Editing in <span>4 Clear Steps</span></h3>
                    </div>
                    <div class="para16">
                        <!-- <p>
                            Professional editing requires a systematic approach. Our proven editing process ensures your
                            manuscript receives thorough, consistent attention from start to finish, with clear
                            communication and professional results you can trust.
                        </p> -->
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-4 left-col">
                    <div class="our-proce-wrap">
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Free Manuscript Assessment & Quote</h2>
                            </div>
                            <div class="para16">
                                <p>Submit your manuscript (or a sample chapter) and tell us about your editing needs.
                                    We'll review your work and provide an honest assessment of what editing level would
                                    benefit your manuscript most. You'll receive transparent pricing, realistic
                                    timelines, and clear explanations of what each editing service includes. No
                                    obligation, no pressure, just straightforward guidance.</p>
                            </div>
                        </div>
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Editor Assignment & Project Setup</h2>
                            </div>
                            <div class="para16">
                                <p>Once you proceed, we assign an editor who specialises in your genre. You'll receive
                                    their credentials, editing approach, and have the opportunity to discuss your
                                    manuscript's specific needs. We establish clear communication channels, confirm
                                    deadlines, and answer any questions before editing begins.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mid-col">
                    <div class="proceimg">
                        <img src="assets/images/Melbourne-Book-log.png" alt="Melbourne Book logo with modern typography" width="1093" height="1093">
                    </div>
                </div>
                <div class="col-md-4 right-col">
                    <div class="our-proce-wrap">
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Professional Editing</h2>
                            </div>
                            <div class="para16">
                                <p>Your editor works through your manuscript thoroughly, providing the level of editing
                                    you've selected. For developmental editing, you'll receive detailed structural
                                    feedback and revision guidance. For copy editing and line editing, your manuscript
                                    is refined sentence by sentence. For proofreading, every error is caught and
                                    corrected. All changes are tracked so you can review every edit.</p>
                            </div>
                        </div>
                        <div class="our-proce-inner mb20">
                            <div class="head22 mb20">
                                <h2>Editorial Feedback & Revision Support</h2>
                            </div>
                            <div class="para16">
                                <p>You receive your edited manuscript with tracked changes and an editorial letter
                                    explaining key recommendations. For developmental editing, this includes substantial
                                    guidance on strengthening your story. For all editing levels, we're available to
                                    discuss feedback, answer questions, and clarify suggestions. Many authors choose to
                                    implement revisions and submit for a second editing pass, we offer discounted rates
                                    for revision editing.</p>
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
                        <div class="img-box mb20"><img src="assets/images/icons/Romance.png" alt="Romance genre book icon for Melbourne Print & Publish" width="73" height="73" loading="lazy" decoding="async"></div>
                        <div class="head18">
                            <h3>Romance</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Thriller.png" alt="Thriller genre book icon for Melbourne Print & Publish" width="73" height="73" loading="lazy" decoding="async"></div>
                        <div class="head18">
                            <h3>Memoirs and Autobiography</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/fantasy.png" alt="Fantasy genre book icon for Melbourne Print & Publish" width="73" height="73" loading="lazy" decoding="async"></div>
                        <div class="head18">
                            <h3>Fantasy</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Since-Fiction.png" alt="Science fiction genre book icon for Melbourne Print & Publish" width="73" height="73" loading="lazy" decoding="async"></div>
                        <div class="head18">
                            <h3>Science fiction</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Since.png" alt="General publishing icon for Melbourne Print & Publish" width="73" height="73" loading="lazy" decoding="async"></div>
                        <div class="head18">
                            <h3>Leadership</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="cate-img">
                        <div class="img-box mb20"><img src="assets/images/icons/Adventure.png" alt="Adventure genre book icon for Melbourne Print & Publish" width="145" height="145" loading="lazy" decoding="async"></div>
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

    <!-- Why Professional Book Editing Matters -->
    <section class="book-store-sec serv-sec11">
        <div class="container">
            <div class="row">
                <div class="col-md-6 right-col">
                    <div class="store-img">
                        <img src="assets/images/matters.webp" alt="Why editing matters" width="1610" height="1195" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="col-md-6 left-col">
                    <div class="head45 mb20">
                        <h2>Why <span>Professional Book Editing</span> Matters</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>Professional editing is the difference between a manuscript and a publishable book. Even
                            experienced writers need editors, not because they can't write, but because every author is
                            too close to their own work to spot every issue. Your brain automatically fills in gaps,
                            overlooks errors, and assumes readers understand things that aren't quite clear on the page.
                        </p>
                        <p>Readers notice poor editing immediately. Grammatical errors, plot inconsistencies, pacing
                            problems, and unclear writing pull readers out of your story and damage your credibility as
                            an author. In competitive markets like Amazon where readers choose from millions of books,
                            professional editing ensures yours stands out for the right reasons.</p>
                        <p>Publishing an unedited manuscript wastes your marketing efforts and damages your author
                            reputation. Negative reviews mentioning editing issues are difficult to overcome, even if
                            you later revise the book. Professional editing is an investment in your book's success,
                            better reviews, stronger reader engagement, and a polished product you're genuinely proud
                            of.</p>
                    </div>
                    <a class="bttn1" href="javascript:void(0);" data-toggle="modal" aria-haspopup="dialog"
                        data-target="#exampleModalCenter">Get Professional Editing for Your Book</a>
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
                        <h2>Our<span> Other Services</span></h2>
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
                        <h2>All-Inclusive Book Editing Services</h2>
                    </div>
                    <div class="para16 mb20">
                        <p>Our comprehensive book editing services have helped countless authors refine their
                            manuscripts and prepare them for publication. From developmental editing to line editing and
                            proofreading, we ensure your book is polished and ready to captivate your audience. Trust us
                            to enhance your work and make your manuscript stand out in the competitive world of
                            publishing.</p>
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

    <!-- FAQs Section - Book Editing Focused -->
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
                            <!-- Q1 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse1">
                                        <span><strong>Q1:</strong> How to find reliable book editing companies in
                                            Melbourne?</span>
                                    </div>
                                </div>
                                <div id="collapse1" class="collapse show">
                                    <div class="card-body text">
                                        <p>Finding reliable book editors starts with checking their experience,
                                            qualifications, and client reviews. Look for editing companies that clearly
                                            explain their editing process, provide sample edits, and offer transparent
                                            pricing upfront. We've been editing manuscripts for Melbourne authors and
                                            writers worldwide for years. You can review our portfolio, read client
                                            testimonials, and request a free sample edit of your first chapter to assess
                                            our editing quality before committing. Reliable editors will always provide
                                            credentials, genre expertise, and realistic timelines, avoid anyone
                                            promising unrealistic turnarounds or guaranteeing bestseller status.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Q2 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse2">
                                        <span><strong>Q2:</strong> Where to get fiction book editing in
                                            Melbourne?</span>
                                    </div>
                                </div>
                                <div id="collapse2" class="collapse">
                                    <div class="card-body text">
                                        <p>We specialise in fiction editing across all genres including literary
                                            fiction, romance, thriller, mystery, science fiction, fantasy, historical
                                            fiction, and young adult novels. Our fiction editors understand story
                                            structure, character development, pacing, dialogue, and the specific
                                            conventions of your genre. Submit your manuscript through our online portal,
                                            and we’ll assign an editor who specialises in your specific fiction
                                            category. We offer developmental editing for manuscripts needing structural
                                            work, line editing to refine your prose, and copy editing to polish grammar
                                            and consistency. You’ll receive tracked changes, detailed feedback, and an
                                            editorial letter explaining our recommendations. Fiction editing typically
                                            takes 2-4 weeks depending on manuscript length and editing level required.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Q3 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse3">
                                        <span><strong>Q3:</strong> How long do book editing services take in
                                            Melbourne?</span>
                                    </div>
                                </div>
                                <div id="collapse3" class="collapse">
                                    <div class="card-body text">
                                        <p>Editing timelines depend on your manuscript’s length, the editing level
                                            required, and our current workload. Typically, proofreading takes 3-7 days,
                                            copy editing requires 1-2 weeks, line editing needs 2-3 weeks, and
                                            developmental editing takes 3-4 weeks. For a standard 80,000-word novel,
                                            expect around 2-3 weeks for comprehensive copy editing. We provide exact
                                            timelines during your free consultation based on your specific manuscript.
                                            Rush editing is available for urgent deadlines, though we never compromise
                                            quality for speed. After receiving your edited manuscript, you’ll have time
                                            to review our feedback and implement revisions before any final editing
                                            pass.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Q4 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse4">
                                        <span><strong>Q4:</strong> What to expect from a book editing service in
                                            Melbourne?</span>
                                    </div>
                                </div>
                                <div id="collapse4" class="collapse">
                                    <div class="card-body text">
                                        <p>Professional book editing involves much more than fixing typos. You’ll
                                            receive your manuscript with tracked changes showing every edit we’ve made,
                                            marginal comments explaining significant changes or suggestions, a detailed
                                            editorial letter addressing overall strengths and areas for improvement, and
                                            a style sheet documenting terminology, character names, and consistency
                                            decisions. For developmental editing, expect substantial feedback on plot
                                            structure, character arcs, pacing, and narrative effectiveness. Copy editing
                                            focuses on grammar, punctuation, consistency, and clarity. All editing
                                            levels include a follow-up consultation where we discuss our feedback and
                                            answer your questions. You maintain complete creative control, our
                                            suggestions are recommendations, not requirements.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Q5 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse5">
                                        <span><strong>Q5:</strong> How to book an appointment with a Melbourne book
                                            editor?</span>
                                    </div>
                                </div>
                                <div id="collapse5" class="collapse">
                                    <div class="card-body text">
                                        <p>Booking an editing appointment is straightforward. Visit our website and
                                            complete the manuscript submission form with your book’s details, title,
                                            genre, word count, and a brief description. Upload your complete manuscript
                                            or a sample chapter (Word or PDF format). Include your contact information
                                            and preferred editing service. Within 48 hours, we’ll review your submission
                                            and contact you to schedule a free consultation. During this consultation,
                                            we’ll discuss your manuscript’s needs, recommend appropriate editing
                                            services, provide transparent pricing, and establish realistic timelines.
                                            There’s no obligation to proceed, and the consultation is completely free.
                                            You can also contact us directly via phone or email to arrange your
                                            appointment.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Q6 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse6">
                                        <span><strong>Q6:</strong> What are common book editing packages available in
                                            Melbourne?</span>
                                    </div>
                                </div>
                                <div id="collapse6" class="collapse">
                                    <div class="card-body text">
                                        <p>We offer flexible editing packages tailored to your manuscript’s needs. Our
                                            developmental editing package includes structural analysis, plot and
                                            character feedback, pacing assessment, and a detailed editorial letter with
                                            revision guidance, ideal for first drafts or manuscripts needing significant
                                            work. The copy editing package covers grammar, punctuation, spelling,
                                            consistency, style refinement, and fact-checking, perfect for structurally
                                            sound manuscripts needing polish. Our proofreading package provides final
                                            quality checks for typos, formatting, and minor errors before publication.
                                            We also offer combination packages: developmental editing followed by copy
                                            editing at discounted rates, manuscript assessment to determine what editing
                                            you need, and revision editing for manuscripts returning after author
                                            revisions. All packages include tracked changes, editorial feedback, and
                                            follow-up consultations.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Q7 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse7">
                                        <span><strong>Q7:</strong> Can I get personalised editing advice from Melbourne
                                            book editors?</span>
                                    </div>
                                </div>
                                <div id="collapse7" class="collapse">
                                    <div class="card-body text">
                                        <p>Absolutely. Personalised feedback is central to our editing approach. Every
                                            manuscript receives individual attention from an editor who specialises in
                                            your genre. You’ll receive customised editorial advice addressing your
                                            specific manuscript’s strengths, weaknesses, and improvement opportunities.
                                            We don’t use automated editing tools or generic feedback templates, every
                                            comment, suggestion, and editorial letter is written specifically for your
                                            work. During your follow-up consultation, you can discuss our feedback in
                                            detail, ask questions about specific suggestions, and receive guidance on
                                            implementing revisions. We’re also available throughout your revision
                                            process to answer questions and provide additional advice. Many authors
                                            develop ongoing relationships with their editors, returning for subsequent
                                            books and benefiting from continuity and personalised guidance.

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Q8 -->
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <div class="collapsible-link" data-target="#collapse8">
                                        <span><strong>Q8:</strong> Which Melbourne services specialise in editing for
                                            poetry collections?</span>
                                    </div>
                                </div>
                                <div id="collapse8" class="collapse">
                                    <div class="card-body text">
                                        <p>We offer specialised poetry editing services for collections, chapbooks, and
                                            individual poems. Our poetry editors understand poetic forms, meter, rhythm,
                                            imagery, and the specific demands of contemporary and traditional poetry.
                                            Poetry editing focuses on line breaks and enjambment, word choice and sound
                                            devices, imagery and metaphor effectiveness, consistency in voice and theme
                                            across collections, punctuation and formatting conventions, and overall
                                            collection structure and flow. We also provide feedback on poem ordering,
                                            section breaks, and thematic coherence. Poetry editing is highly
                                            personalised, we preserve your unique voice while strengthening clarity,
                                            impact, and technical execution. Submit your poetry collection for a free
                                            assessment, and we’ll assign an editor experienced in your poetic style and
                                            form.

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