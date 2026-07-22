<?php
$siteRoot = realpath(__DIR__ . '/..');
$docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$assetBase = '';
if ($siteRoot && $docRoot && strpos($siteRoot, $docRoot) === 0) {
    $assetBase = '/' . trim(str_replace('\\', '/', substr($siteRoot, strlen($docRoot))), '/');
    if ($assetBase === '/') {
        $assetBase = '';
    }
}
$assetBaseEsc = htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8');
?>
<section class="header-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 left-col">
                <div class="site-branding logo">
                    <a href="<?php echo $assetBaseEsc; ?>/">
                        <img src="<?php echo $assetBaseEsc; ?>/assets/images/logo.png" alt="Primary logo of Melbourne Print & Publish">
                    </a>
                </div>
            </div>
            <div class="col-md-7 mid-col">
                <nav class="navigation" aria-label="Primary">
                    <button type="button" class="hamburger" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="primary-menu">
                        <div class="line1"></div>
                        <div class="line2"></div>
                        <div class="line3"></div>
                    </button>
                    <ul id="primary-menu" class="nav-links">
                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/about-us/">About Us</a>
                        </li>

                        <li class="menu-item-has-children">
                            <a href="<?php echo $assetBaseEsc; ?>/our-services/" aria-haspopup="true" aria-expanded="false">Services</a>
                            <ul class="sub-menu sub-menu-columns">
                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/publishing/">Publishing</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/professional-editing/">Professional Editing</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/ghost-writing/">Ghost Writing</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/book-design/">Book Design</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/marketing/">Marketing</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/author-website-development-services-in-melbourne/">Author Website Development</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/book-illustration-services-in-melbourne/">Book Illustration</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/book-trailer-video-services-in-melbourne/">Book Trailer Videos</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/book-printing-services-in-melbourne/">Book Printing</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/book-formatting-services-in-melbourne/">Book Formatting</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/e-book-writing-services-in-melbourne/">E-Book Writing</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/fiction-ghostwriting-services-in-melbourne/">Fiction Ghostwriting</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/non-fiction-ghostwriting-service-in-melbourne/">Non-Fiction Ghostwriting</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/book-proofreading-services-in-melbourne/">Book Proofreading</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/academic-proofreading-services-in-melbourne/">Academic Proofreading</a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/blog/">Blog</a>
                        </li>

                        <li class="menu-item-has-children">
                            <a href="<?php echo $assetBaseEsc; ?>/portfolios/" aria-haspopup="true" aria-expanded="false">Portfolios</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/portfolios/">Our Books</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/website-portfolio/">Author Websites</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/video-trailers-portfolio/">Book Trailer</a>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/testimonial/">Testimonial</a>
                        </li>

                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/faqs/">FAQs</a>
                        </li>

                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/contact-us/">Contact Us</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-2 right-col">
                <!--<div class="head-bttn">-->
                <!--    <a class="bttn openChatBtn" href="javascript:void(0);" class="">Live Chat</a>-->
                <!--</div>-->
                
                <div class="head-bttn">
    <a href="javascript:void(0);" class="bttn openChatBtn">Live Chat</a>
</div>
            </div>
        </div>
    </div>
</section>
