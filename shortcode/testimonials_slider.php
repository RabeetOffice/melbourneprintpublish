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
                                <svg class="mpp-icon mpp-icon-quote-right" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M464 32H336c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h80v64c0 35.3-28.7 64-64 64h-8c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h8c88.4 0 160-71.6 160-160V80c0-26.5-21.5-48-48-48zm-288 0H48C21.5 32 0 53.5 0 80v128c0 26.5 21.5 48 48 48h80v64c0 35.3-28.7 64-64 64h-8c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h8c88.4 0 160-71.6 160-160V80c0-26.5-21.5-48-48-48z"/></svg>
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