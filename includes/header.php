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
                <div class="navigation">
                    <div class="hamburger">
                        <div class="line1"></div>
                        <div class="line2"></div>
                        <div class="line3"></div>
                    </div>
                    <ul id="primary-menu" class="nav-links">
                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/about-us/">About Us</a>
                        </li>

                        <li class="menu-item-has-children">
                            <a href="<?php echo $assetBaseEsc; ?>/our-services/">Services</a>
                            <ul class="sub-menu">
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

                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/blog/">Blog</a>
                        </li>

                        <li class="menu-item-has-children">
                            <a href="<?php echo $assetBaseEsc; ?>/portfolios/">Portfolios</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/portfolios/">Book Portfolio</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/website-portfolio/">Website Portfolio</a>
                                </li>

                                <li>
                                    <a href="<?php echo $assetBaseEsc; ?>/video-trailers-portfolio/">Video Trailers Portfolio</a>
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
                </div>
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
