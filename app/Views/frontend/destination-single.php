<?php
$this->layout('frontend', ['pageTitle' => $destination['title']]);
?>
<section class="bt-page-head" style="background-image:url('<?= e(upload_url($destination['image'])) ?>');background-size:cover;background-position:center">
    <div class="bt-page-head-overlay"></div>
    <div class="container text-center position-relative">
        <h1><?= e($destination['title']) ?></h1>
        <p><?= e($destination['country']) ?> — <span class="star">★</span> <?= e($destination['rating']) ?></p>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <h3 class="mb-3">دربارهٔ <?= e($destination['title']) ?></h3>
                <p class="bt-lead"><?= e($destination['description']) ?></p>
                <div class="bt-info-cards row g-3 mt-3">
                    <div class="col-sm-6">
                        <div class="bt-info-card">
                            <span class="bt-info-k">قیمت از</span>
                            <span class="bt-info-v"><?= e(fa_number(format_price($destination['price_from']))) ?> افغانی</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bt-info-card">
                            <span class="bt-info-k">امتیاز مسافران</span>
                            <span class="bt-info-v"><span class="star">★</span> <?= e($destination['rating']) ?> / ۵</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <?= $this->partial('frontend/_booking_form', [
                    'destination' => $destination['title'],
                ]) ?>
            </div>
        </div>

        <?php if (!empty($tours)): ?>
            <div class="mt-5">
                <h3 class="mb-4">تورهای این مقصد</h3>
                <div class="row g-4">
                    <?php foreach ($tours as $tour): ?>
                        <div class="col-lg-4 col-md-6">
                            <?= $this->partial('frontend/_tour_card', ['tour' => $tour]) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($other)): ?>
            <div class="mt-5">
                <h3 class="mb-4">مقاصد دیگر</h3>
                <div class="row g-3">
                    <?php foreach (array_slice($other, 0, 4) as $o): ?>
                        <div class="col-6 col-lg-3">
                            <a href="<?= e(base_url('destinations/' . $o['slug'])) ?>" class="bt-mini-dest">
                                <img src="<?= e(upload_url($o['image'])) ?>" alt="<?= e($o['title']) ?>" loading="lazy">
                                <span><?= e($o['title']) ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
