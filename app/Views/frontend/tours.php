<?php
$this->layout('frontend', ['pageTitle' => 'تورها و پکیج‌ها']);
?>
<section class="bt-page-head" style="background:var(--bt-dark)">
    <div class="container text-center">
        <h1>تورها و پکیج‌های سفر</h1>
        <p>برنامه‌های سفر آماده با بلیط، اقامت و راهنمای حرفه‌ای</p>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <?php if (empty($tours)): ?>
            <div class="bt-empty">هنوز توری ثبت نشده است.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($tours as $tour): ?>
                    <div class="col-lg-4 col-md-6">
                        <?= $this->partial('frontend/_tour_card', ['tour' => $tour]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
