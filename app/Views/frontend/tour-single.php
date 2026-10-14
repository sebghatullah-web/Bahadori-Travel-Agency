<?php
$this->layout('frontend', ['pageTitle' => $tour['title']]);
?>
<section class="bt-page-head" style="background-image:url('<?= e(upload_url($tour['image'])) ?>');background-size:cover;background-position:center">
    <div class="bt-page-head-overlay"></div>
    <div class="container position-relative">
        <?php if (!empty($tour['badge'])): ?><span class="bt-hero-badge mb-2"><?= e($tour['badge']) ?></span><?php endif; ?>
        <h1><?= e($tour['title']) ?></h1>
        <p class="d-flex align-items-center gap-3 justify-content-center flex-wrap">
            <span><span class="star">★</span> <?= e($tour['rating']) ?></span>
            <span>• <?= e(fa_number($tour['duration_days'])) ?> روز / <?= e(fa_number($tour['duration_nights'])) ?> شب</span>
            <?php if (!empty($tour['location'])): ?><span>• <?= e($tour['location']) ?></span><?php endif; ?>
        </p>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="bt-tour-hero-img mb-4">
                    <img src="<?= e(upload_url($tour['image'])) ?>" alt="<?= e($tour['title']) ?>" class="img-fluid rounded">
                </div>
                <h3>معرفی تور</h3>
                <p class="bt-lead"><?= e($tour['summary']) ?></p>
                <div class="bt-prose"><?= nl2br(e($tour['description'])) ?></div>

                <div class="bt-tour-includes row g-3 mt-4">
                    <div class="col-sm-4"><div class="bt-include"><span class="bt-include-ic">✈</span> بلیط رفت و برگشت</div></div>
                    <div class="col-sm-4"><div class="bt-include"><span class="bt-include-ic">🏨</span> اقامت هتل</div></div>
                    <div class="col-sm-4"><div class="bt-include"><span class="bt-include-ic">🧭</span> راهنمای محلی</div></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bt-booking-aside">
                    <div class="bt-aside-price">
                        <?php if (!empty($tour['old_price'])): ?>
                            <span class="bt-tour-old"><?= e(fa_number(format_price($tour['old_price']))) ?></span>
                        <?php endif; ?>
                        <span class="bt-aside-now"><?= e(fa_number(format_price($tour['price']))) ?> افغانی</span>
                        <small>قیمت به ازای هر نفر</small>
                    </div>
                    <?= $this->partial('frontend/_booking_form', [
                        'tourId'      => $tour['id'],
                        'destination' => $tour['location'] ?? $tour['destination_name'] ?? '',
                    ]) ?>
                </div>
            </div>
        </div>

        <?php if (!empty($related)): ?>
            <div class="mt-5">
                <h3 class="mb-4">تورهای پیشنهادی</h3>
                <div class="row g-4">
                    <?php foreach ($related as $r): ?>
                        <?php if ($r['id'] == $tour['id']) continue; ?>
                        <div class="col-lg-4 col-md-6">
                            <?= $this->partial('frontend/_tour_card', ['tour' => $r]) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
