<?php
$this->layout('frontend', ['pageTitle' => 'مقاصد']);
?>
<section class="bt-page-head" style="background:var(--bt-dark)">
    <div class="container text-center">
        <h1>مقاصد سفر</h1>
        <p>شهرها و کشورهایی که با ما می‌توانید کشف کنید</p>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <?php if (empty($destinations)): ?>
            <div class="bt-empty">هنوز مقصدی ثبت نشده است.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($destinations as $dest): ?>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?= e(base_url('destinations/' . $dest['slug'])) ?>" class="bt-dest-card">
                            <div class="bt-dest-media">
                                <img src="<?= e(upload_url($dest['image'])) ?>" alt="<?= e($dest['title']) ?>" loading="lazy">
                                <?php if (!empty($dest['is_featured'])): ?><span class="bt-dest-tag">محبوب</span><?php endif; ?>
                            </div>
                            <div class="bt-dest-body">
                                <div class="bt-dest-rating"><span class="star">★</span> <?= e($dest['rating']) ?></div>
                                <h5 class="bt-dest-name"><?= e($dest['title']) ?></h5>
                                <p class="bt-dest-loc"><?= e($dest['country']) ?></p>
                                <div class="bt-dest-price">شروع از <strong><?= e(fa_number(format_price($dest['price_from']))) ?></strong> افغانی</div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
