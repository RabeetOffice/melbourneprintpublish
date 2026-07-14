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
                <img src="<?= e(brand_asset($item['image'])); ?>" alt="<?= e($item['alt'] ?? ''); ?>">
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
                                <i class="fas fa-long-arrow-alt-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>