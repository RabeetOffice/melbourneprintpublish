<?php
require_once __DIR__ . '/../includes/config.php';
include __DIR__ . '/../data/blogs_post.php';
?>
<div class="recent-blogs blogs-slider">

    <?php
        $initial = array_slice($blogs, 0, 4);
        foreach($initial as $item):
    ?>
    <div>
        <div class="blogs-wrapper">
            <div class="blog-img">
                <img src="<?= e(brand_asset($item['image'])); ?>" alt="<?= e($item['alt'] ?? ''); ?>" loading="lazy">
            </div>
            <div class="blog-info">
                <div class="blog-title">
                    <h4>
                        <?= $item['name']; ?>
                    </h4>
                </div>
                <div class="blog_excerpt">
                    <p>
                        <?= $item['text']; ?>
                    </p>
                </div>
                <div class="blog-footer">
                    <div class="get_data">
                        <?= $item['date']; ?>
                    </div>
                    <div class="blog-readmore">
                        <a href="<?= e(brand_asset($item['link'])); ?>">
                            <span class="blog-readmore-text">Read more</span>
                            <span class="blog-readmore-arrow">
                                <svg class="mpp-icon mpp-icon-long-arrow-alt-right" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z"/></svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>