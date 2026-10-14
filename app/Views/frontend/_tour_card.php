<?php
/**
 * -------------------------------------------------------------------------
 *  Reusable tour card. Expects $tour (row from Tour model w/ destination_name).
 * -------------------------------------------------------------------------
 */
?>
<a href="<?= e(base_url('tours/' . ($tour['slug'] ?? ''))) ?>" class="bt-tour-card">
    <div class="bt-tour-media">
        <img src="<?= e(upload_url($tour['image'])) ?>" alt="<?= e($tour['title']) ?>" loading="lazy">
        <?php if (!empty($tour['badge'])): ?>
            <span class="bt-tour-badge"><?= e($tour['badge']) ?></span>
        <?php endif; ?>
    </div>
    <div class="bt-tour-body">
        <div class="bt-tour-meta">
            <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> <?= e(fa_number($tour['duration_days'] ?? 1)) ?> روز</span>
            <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> <?= e($tour['location'] ?? $tour['destination_name'] ?? '') ?></span>
        </div>
        <h5 class="bt-tour-title"><?= e($tour['title']) ?></h5>
        <?php if (!empty($tour['summary'])): ?>
            <p class="bt-tour-summary"><?= e($tour['summary']) ?></p>
        <?php endif; ?>
        <div class="bt-tour-footer">
            <div class="bt-tour-price">
                <?php if (!empty($tour['old_price'])): ?>
                    <span class="bt-tour-old"><?= e(fa_number(format_price($tour['old_price']))) ?></span>
                <?php endif; ?>
                <span class="bt-tour-now"><?= e(fa_number(format_price($tour['price']))) ?> افغانی</span>
            </div>
            <span class="bt-tour-rating"><span class="star">★</span> <?= e($tour['rating']) ?></span>
        </div>
        <span class="bt-tour-cta">قیمت از</span>
    </div>
</a>
