<?php
require_once __DIR__ . '/config.php';
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
<footer id="colophon" class="site-footer">
    <div class="footer">
        <div class="container">
            <div class="row row1">
                <div class="col-lg-4 logo-col">
                    <div class="footer1">

                        <a href="<?php echo $assetBaseEsc; ?>/">
                            <img src="<?php echo $assetBaseEsc; ?>/assets/images/footer-logo.png" alt="Melbourne Print & Publish footer logo" />
                        </a>

                        <div class="para16">
                            <p><?= mpp_val('contact.tagline', 'Melbourne Print & Publish is a trusted provider of high-quality printing and publishing
                                services across Australia.'); ?></p>
                        </div>

                        <div class="social-icon">
                            <ul>
                                <li>
                                    <a href="<?= mpp_val_url('social.facebook', 'https://www.facebook.com/melbourneprintandpublish/'); ?>" rel="nofollow" target="_blank">
                                        <svg width="9" height="18" viewBox="0 0 9 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.99121 0.691964V3.34375H7.4142C6.83831 3.34375 6.44992 3.46428 6.24902 3.70536C6.04813 3.94643 5.94768 4.30804 5.94768 4.79018V6.68862H8.89077L8.49902 9.66183H5.94768V17.2857H2.87402V9.66183H0.31264V6.68862H2.87402V4.49888C2.87402 3.25335 3.22224 2.28906 3.91867 1.60603C4.6151 0.916294 5.54255 0.571427 6.70103 0.571427C7.68541 0.571427 8.4488 0.611606 8.99121 0.691964Z"
                                                fill="#393939" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= mpp_val_url('social.instagram', 'https://www.instagram.com/melbourne_print_and_publish/'); ?>" rel="nofollow" target="_blank">
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.81557 10.3895C10.3178 9.88728 10.5689 9.28125 10.5689 8.57143C10.5689 7.86161 10.3178 7.25558 9.81557 6.75335C9.31334 6.25112 8.70731 6 7.99749 6C7.28767 6 6.68164 6.25112 6.17941 6.75335C5.67718 7.25558 5.42606 7.86161 5.42606 8.57143C5.42606 9.28125 5.67718 9.88728 6.17941 10.3895C6.68164 10.8917 7.28767 11.1429 7.99749 11.1429C8.70731 11.1429 9.31334 10.8917 9.81557 10.3895ZM10.7999 5.76897C11.57 6.53906 11.9551 7.47321 11.9551 8.57143C11.9551 9.66964 11.57 10.6038 10.7999 11.3739C10.0299 12.144 9.0957 12.529 7.99749 12.529C6.89928 12.529 5.96512 12.144 5.19503 11.3739C4.42494 10.6038 4.0399 9.66964 4.0399 8.57143C4.0399 7.47321 4.42494 6.53906 5.19503 5.76897C5.96512 4.99888 6.89928 4.61384 7.99749 4.61384C9.0957 4.61384 10.0299 4.99888 10.7999 5.76897ZM12.7687 3.80022C12.9495 3.98103 13.0399 4.19866 13.0399 4.45312C13.0399 4.70759 12.9495 4.92522 12.7687 5.10603C12.5879 5.28683 12.3703 5.37723 12.1158 5.37723C11.8613 5.37723 11.6437 5.28683 11.4629 5.10603C11.2821 4.92522 11.1917 4.70759 11.1917 4.45312C11.1917 4.19866 11.2821 3.98103 11.4629 3.80022C11.6437 3.61942 11.8613 3.52902 12.1158 3.52902C12.3703 3.52902 12.5879 3.61942 12.7687 3.80022ZM8.76088 2.2433C8.29883 2.2433 8.04436 2.2433 7.99749 2.2433C7.95061 2.2433 7.6928 2.2433 7.22405 2.2433C6.762 2.23661 6.41044 2.23661 6.16936 2.2433C5.92829 2.2433 5.60352 2.25335 5.19503 2.27344C4.79325 2.28683 4.44838 2.32031 4.16044 2.37388C3.87919 2.42076 3.64146 2.48103 3.44727 2.55469C3.11244 2.68862 2.8178 2.88281 2.56334 3.13728C2.30887 3.39174 2.11468 3.68638 1.98075 4.0212C1.90709 4.2154 1.84347 4.45647 1.7899 4.74442C1.74302 5.02567 1.70954 5.37054 1.68945 5.77902C1.67606 6.1808 1.66602 6.50223 1.65932 6.7433C1.65932 6.98437 1.65932 7.33929 1.65932 7.80804C1.66602 8.27009 1.66936 8.52455 1.66936 8.57143C1.66936 8.6183 1.66602 8.87612 1.65932 9.34487C1.65932 9.80692 1.65932 10.1585 1.65932 10.3996C1.66602 10.6406 1.67606 10.9654 1.68945 11.3739C1.70954 11.7757 1.74302 12.1205 1.7899 12.4085C1.84347 12.6897 1.90709 12.9275 1.98075 13.1217C2.11468 13.4565 2.30887 13.7511 2.56334 14.0056C2.8178 14.26 3.11244 14.4542 3.44727 14.5882C3.64146 14.6618 3.87919 14.7254 4.16044 14.779C4.44838 14.8259 4.79325 14.8594 5.19503 14.8795C5.60352 14.8929 5.92829 14.9029 6.16936 14.9096C6.41044 14.9096 6.762 14.9096 7.22405 14.9096C7.6928 14.9029 7.95061 14.8996 7.99749 14.8996C8.04436 14.8996 8.29883 14.9029 8.76088 14.9096C9.22963 14.9096 9.58454 14.9096 9.82561 14.9096C10.0667 14.9029 10.3881 14.8929 10.7899 14.8795C11.1984 14.8594 11.5432 14.8259 11.8245 14.779C12.1124 14.7254 12.3535 14.6618 12.5477 14.5882C12.8825 14.4542 13.1772 14.26 13.4316 14.0056C13.6861 13.7511 13.8803 13.4565 14.0142 13.1217C14.0879 12.9275 14.1482 12.6897 14.195 12.4085C14.2486 12.1205 14.2821 11.7757 14.2955 11.3739C14.3156 10.9654 14.3256 10.6406 14.3256 10.3996C14.3323 10.1585 14.3323 9.80692 14.3256 9.34487C14.3256 8.87612 14.3256 8.6183 14.3256 8.57143C14.3256 8.52455 14.3256 8.27009 14.3256 7.80804C14.3323 7.33929 14.3323 6.98437 14.3256 6.7433C14.3256 6.50223 14.3156 6.1808 14.2955 5.77902C14.2821 5.37054 14.2486 5.02567 14.195 4.74442C14.1482 4.45647 14.0879 4.2154 14.0142 4.0212C13.8803 3.68638 13.6861 3.39174 13.4316 3.13728C13.1772 2.88281 12.8825 2.68862 12.5477 2.55469C12.3535 2.48103 12.1124 2.42076 11.8245 2.37388C11.5432 2.32031 11.1984 2.28683 10.7899 2.27344C10.3881 2.25335 10.0667 2.2433 9.82561 2.2433C9.58454 2.23661 9.22963 2.23661 8.76088 2.2433ZM15.6616 5.38728C15.695 5.97656 15.7118 7.03795 15.7118 8.57143C15.7118 10.1049 15.695 11.1663 15.6616 11.7556C15.5946 13.1484 15.1794 14.2266 14.416 14.99C13.6526 15.7533 12.5745 16.1685 11.1816 16.2355C10.5924 16.269 9.53097 16.2857 7.99749 16.2857C6.46401 16.2857 5.40262 16.269 4.81334 16.2355C3.42048 16.1685 2.34235 15.7533 1.57896 14.99C0.815569 14.2266 0.400391 13.1484 0.333426 11.7556C0.299944 11.1663 0.283203 10.1049 0.283203 8.57143C0.283203 7.03795 0.299944 5.97656 0.333426 5.38728C0.400391 3.99442 0.815569 2.91629 1.57896 2.1529C2.34235 1.38951 3.42048 0.97433 4.81334 0.907366C5.40262 0.873883 6.46401 0.857142 7.99749 0.857142C9.53097 0.857142 10.5924 0.873883 11.1816 0.907366C12.5745 0.97433 13.6526 1.38951 14.416 2.1529C15.1794 2.91629 15.5946 3.99442 15.6616 5.38728Z"
                                                fill="#393939" />
                                        </svg>
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= mpp_val_url('social.linkedin', 'https://www.linkedin.com/company/melbourneprintandpublish'); ?>" rel="nofollow" target="_blank">
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.78878 4.84933V14.8036H0.474051V4.84933H3.78878ZM3.99972 1.77567C4.00642 2.26451 3.83566 2.67299 3.48744 3.00112C3.14593 3.32924 2.69392 3.4933 2.13142 3.4933H2.11133C1.56222 3.4933 1.12026 3.32924 0.785435 3.00112C0.450614 2.67299 0.283203 2.26451 0.283203 1.77567C0.283203 1.28013 0.453962 0.871652 0.79548 0.550223C1.14369 0.222098 1.5957 0.0580353 2.15151 0.0580353C2.70731 0.0580353 3.15262 0.222098 3.48744 0.550223C3.82227 0.871652 3.99302 1.28013 3.99972 1.77567ZM15.7118 9.09821V14.8036H12.4071V9.47991C12.4071 8.77679 12.2698 8.22768 11.9953 7.83259C11.7274 7.4308 11.3055 7.22991 10.7296 7.22991C10.3078 7.22991 9.95285 7.3471 9.6649 7.58147C9.38365 7.80915 9.17271 8.09375 9.03209 8.43527C8.95843 8.63616 8.9216 8.90737 8.9216 9.24888V14.8036H5.61691C5.6303 12.1317 5.637 9.9654 5.637 8.30469C5.637 6.64397 5.63365 5.6529 5.62695 5.33147L5.61691 4.84933H8.9216V6.29576H8.90151C9.03544 6.08147 9.17271 5.89397 9.31334 5.73326C9.45396 5.57254 9.64146 5.39844 9.87584 5.21094C10.1169 5.02344 10.4082 4.87946 10.7497 4.77902C11.0979 4.67187 11.483 4.6183 11.9049 4.6183C13.0499 4.6183 13.9707 5 14.6671 5.76339C15.3636 6.52009 15.7118 7.6317 15.7118 9.09821Z"
                                                fill="#393939" />
                                        </svg>
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= mpp_val_url('social.pinterest', 'https://www.pinterest.com/melbournePandP/'); ?>" rel="nofollow" target="_blank">
                                        <svg fill="#393939" width="16" height="15" viewBox="0 0 32 32"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M16.75 0.406c-6.413 0-12.75 4.275-12.75 11.194 0 4.4 2.475 6.9 3.975 6.9 0.619 0 0.975-1.725 0.975-2.212 0-0.581-1.481-1.819-1.481-4.238 0-5.025 3.825-8.588 8.775-8.588 4.256 0 7.406 2.419 7.406 6.863 0 3.319-1.331 9.544-5.644 9.544-1.556 0-2.888-1.125-2.888-2.737 0-2.363 1.65-4.65 1.65-7.088 0-4.137-5.869-3.387-5.869 1.613 0 1.050 0.131 2.212 0.6 3.169-0.863 3.713-2.625 9.244-2.625 13.069 0 1.181 0.169 2.344 0.281 3.525 0.212 0.238 0.106 0.213 0.431 0.094 3.15-4.313 3.038-5.156 4.463-10.8 0.769 1.463 2.756 2.25 4.331 2.25 6.637 0 9.619-6.469 9.619-12.3 0-6.206-5.363-10.256-11.25-10.256z">
                                                </path>
                                            </g>
                                        </svg>
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= mpp_val_url('social.twitter', 'https://x.com/melbournePandP'); ?>" rel="nofollow" target="_blank">
                                        <svg width="16" height="15" viewBox="0 -2 20 20" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" fill="#393939">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <title>twitter [#154]</title>
                                                <desc>Created with Sketch.</desc>
                                                <defs> </defs>
                                                <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <g id="Dribbble-Light-Preview"
                                                        transform="translate(-60.000000, -7521.000000)" fill="#393939">
                                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                                            <path
                                                                d="M10.29,7377 C17.837,7377 21.965,7370.84365 21.965,7365.50546 C21.965,7365.33021 21.965,7365.15595 21.953,7364.98267 C22.756,7364.41163 23.449,7363.70276 24,7362.8915 C23.252,7363.21837 22.457,7363.433 21.644,7363.52751 C22.5,7363.02244 23.141,7362.2289 23.448,7361.2926 C22.642,7361.76321 21.761,7362.095 20.842,7362.27321 C19.288,7360.64674 16.689,7360.56798 15.036,7362.09796 C13.971,7363.08447 13.518,7364.55538 13.849,7365.95835 C10.55,7365.79492 7.476,7364.261 5.392,7361.73762 C4.303,7363.58363 4.86,7365.94457 6.663,7367.12996 C6.01,7367.11125 5.371,7366.93797 4.8,7366.62489 L4.8,7366.67608 C4.801,7368.5989 6.178,7370.2549 8.092,7370.63591 C7.488,7370.79836 6.854,7370.82199 6.24,7370.70483 C6.777,7372.35099 8.318,7373.47829 10.073,7373.51078 C8.62,7374.63513 6.825,7375.24554 4.977,7375.24358 C4.651,7375.24259 4.325,7375.22388 4,7375.18549 C5.877,7376.37088 8.06,7377 10.29,7376.99705"
                                                                id="twitter-[#154]"> </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= mpp_val_url('social.youtube', 'https://www.youtube.com/@melbourneprintandpublish'); ?>" rel="nofollow" target="_blank">
                                        <svg width="18" height="18" viewBox="0 0 24 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M20.5245 6.00694C20.3025 5.81544 20.0333 5.70603 19.836 5.63863C19.6156 5.56337 19.3637 5.50148 19.0989 5.44892C18.5677 5.34348 17.9037 5.26005 17.1675 5.19491C15.6904 5.06419 13.8392 5 12 5C10.1608 5 8.30956 5.06419 6.83246 5.1949C6.09632 5.26005 5.43231 5.34348 4.9011 5.44891C4.63628 5.50147 4.38443 5.56337 4.16403 5.63863C3.96667 5.70603 3.69746 5.81544 3.47552 6.00694C3.26514 6.18846 3.14612 6.41237 3.07941 6.55976C3.00507 6.724 2.94831 6.90201 2.90314 7.07448C2.81255 7.42043 2.74448 7.83867 2.69272 8.28448C2.58852 9.18195 2.53846 10.299 2.53846 11.409C2.53846 12.5198 2.58859 13.6529 2.69218 14.5835C2.74378 15.047 2.81086 15.4809 2.89786 15.8453C2.97306 16.1603 3.09841 16.5895 3.35221 16.9023C3.58757 17.1925 3.92217 17.324 4.08755 17.3836C4.30223 17.461 4.55045 17.5218 4.80667 17.572C5.32337 17.6733 5.98609 17.7527 6.72664 17.8146C8.2145 17.9389 10.1134 18 12 18C13.8865 18 15.7855 17.9389 17.2733 17.8146C18.0139 17.7527 18.6766 17.6733 19.1933 17.572C19.4495 17.5218 19.6978 17.461 19.9124 17.3836C20.0778 17.324 20.4124 17.1925 20.6478 16.9023C20.9016 16.5895 21.0269 16.1603 21.1021 15.8453C21.1891 15.4809 21.2562 15.047 21.3078 14.5835C21.4114 13.6529 21.4615 12.5198 21.4615 11.409C21.4615 10.299 21.4115 9.18195 21.3073 8.28448C21.2555 7.83868 21.1874 7.42043 21.0969 7.07448C21.0517 6.90201 20.9949 6.72401 20.9206 6.55976C20.8539 6.41236 20.7349 6.18846 20.5245 6.00694Z"
                                                    stroke="#393939" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M14.5385 11.5L10.0962 14.3578L10.0962 8.64207L14.5385 11.5Z"
                                                    stroke="#2A2C70" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row row2">
                        <div class="col-lg-3">
                            <div class="footer2">
                                <h2 class="widget-title">Quick Links</h2>
                                <ul id="footer-menu" class="menu">
                                    <li>
                                        <a href="<?php echo $assetBaseEsc; ?>/">Home</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $assetBaseEsc; ?>/about-us/">About Us</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $assetBaseEsc; ?>/blog/">Blog</a>
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
                        <div class="col-lg-5">
                            <div class="footer3">
                                <h2 class="widget-title">Services</h2>
                                <ul id="menu-services" class="menu">
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
                                        <a href="<?php echo $assetBaseEsc; ?>/fiction-ghostwriting-services-in-melbourne/">Fiction Ghostwriting</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $assetBaseEsc; ?>/book-formatting-services-in-melbourne/">Book Formatting</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $assetBaseEsc; ?>/book-proofreading-services-in-melbourne/">Book Proofreading</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $assetBaseEsc; ?>/e-book-writing-services-in-melbourne/">eBook Writing</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $assetBaseEsc; ?>/non-fiction-ghostwriting-service-in-melbourne/">Non-Fiction
                                            Ghostwriting</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="footer4">

                                <h2 class="widget-title">Contact Us</h2>
                                <div class="cont-info">
                                    <ul>
                                        <li>
                                            <a href="tel:<?= mpp_val('contact.phone_href', '(03) 4138 8706'); ?>">
                                                <i class="fa-solid fa-phone"></i>
                                                <?= mpp_val('contact.phone', '(03) 4138 8706'); ?> </a>
                                        </li>

                                        <li>
                                            <a href="mailto:<?= mpp_val('contact.email', 'info@melbourneprintpublish.com.au'); ?>">
                                                <i class="fa-solid fa-envelope"></i>
                                                <?= mpp_val('contact.email', 'info@melbourneprintpublish.com.au'); ?> </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;"><b><?= mpp_val('contact.abn_company', 'Keystone Publishing Group Pty Ltd'); ?> ABN:</b> <?= mpp_val('contact.abn', '21 697 806 447'); ?> </a>
                                        </li>
                                    </ul>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyrights">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="off-add">
                        <h3>Office Address</h3>
                        <a href="<?= mpp_val_url('contact.address_map_url', 'https://share.google/CUt8YHg5DZuZngGWA'); ?>" rel="nofollow" target="_blank">
                            <i class="fa-solid fa-location-dot"></i>
                            <?php echo mpp_val('contact.address', '470 St Kilda Rd, Melbourne VIC 3004, Australia') . "\r\n"; ?>                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="copyright">&copy; Copyright
                        <?php echo date('Y'); ?> <a href="<?php echo $assetBaseEsc; ?>/">
                            Melbourne Print & Publish
                        </a>, All Rights Reserved.
                    </p>
                </div>
                <div class="col-md-4">
                    <ul id="menu-privacy" class="menu">
                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/privacy-policy/">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="<?php echo $assetBaseEsc; ?>/terms-conditions/">Terms &amp; Conditions</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;
                    <!-- <span aria-hidden="true"> CLOSE</span> -->
                </button>
            </div>
            <div class="modal-body">
                <div class="head38 mb20" style="text-align: center;">
                    <h2><span>Get a Quote</span></h2>
                </div>
                <?php include(__DIR__ . "/../foam/foam.php"); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API = Tawk_API || {},
    Tawk_LoadStart = new Date();

(function() {
    var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];

    s1.async = true;
    s1.src = '<?= mpp_val('chat.tawk_src', 'https://embed.tawk.to/698e24f485e35c1c3911db06/1jh9k0nl3'); ?>';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
})();

/* Open Chat on Button Click */
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".openChatBtn").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();

            if (typeof Tawk_API !== "undefined" && Tawk_API.maximize) {
                Tawk_API.maximize();
            }
        });
    });
});
</script>
<!--End of Tawk.to Script-->
<?php if (function_exists('mpp_custom_script')) { mpp_custom_script('footer'); } ?>
