<?php include 'data/testimonials_post.php'; ?>
<div class="testimonial-slider">
    <?php 
        $initial = array_slice($testimonials, 0, 4);
        foreach($initial as $item): 
        
            $link = $item['link'];

            // Agar real URL hai to new tab
            $target = ($link !== 'javascript:void(0);') ? '_blank' : '';
    ?>
    <div>
        <div class="testimonial-slide">
            <div class="testimonial_box">
                <div class="testimonial_box_inner">
                    <a href="<?= $link; ?>" <?= $target ? 'target="'.$target.'"' : ''; ?>>
                        <div class="testimonial_box_top">
                            <div class="testimonial_box_icon">
                                <i class="fas fa-quote-right"></i>
                            </div>
                            <div class="testimonial_box_name">
                                <h4><?= $item['name']; ?></h4>
                            </div>
                            <div class="testimonial_box_text">
                                <p><?= $item['text']; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>