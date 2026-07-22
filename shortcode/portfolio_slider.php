<?php include 'data/portfolio_post.php'; ?>
<div class="portfolio-sliders">
    <?php
        $initial = array_slice($portfolio, 0, 33);
        foreach($initial as $item):
    ?>
    <?php
        $link = $item['link'];

        // Agar real URL hai to new tab
        $target = ($link !== 'javascript:void(0);') ? '_blank' : '';
    ?>
    <div>
        <!-- Slide 1 -->
        <a href="<?= $link; ?>" <?= $target ? 'target="'.$target.'"' : ''; ?> rel="nofollow">
            <div class="portfolio_box">
                <div class="portfolio_box-img">
                    <img data-lazy="<?= $item['image']; ?>" alt="<?= $item['alt'] ?? ''; ?>">
                </div>

                <div class="portfolio_box-name">
                    <h4><?= $item['name']; ?></h4>
                </div>
            </div>
        </a>
        <!-- Slide 1 -->
    </div>

    <?php endforeach; ?>

</div>