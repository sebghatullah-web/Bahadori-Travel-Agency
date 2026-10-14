<?php
$this->layout('frontend', ['pageTitle' => 'درباره ما']);
?>
<section class="bt-page-head" style="background:var(--bt-dark)">
    <div class="container text-center">
        <h1>درباره ما</h1>
        <p>آژانس مسافرتی بهادری — همسافر شما در هر مسیر</p>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="bt-section-title text-start"><?= e($settings['about_title'] ?? '') ?></h2>
                <p class="bt-lead"><?= e($settings['about_intro'] ?? '') ?></p>
                <p><?= e($settings['about_story'] ?? '') ?></p>
            </div>
            <div class="col-lg-6">
                <div class="bt-about-stats row g-3">
                    <div class="col-6"><div class="bt-whyus-stat"><div class="bt-whyus-num">۱۲+</div><div class="bt-whyus-lbl">سال تجربه</div></div></div>
                    <div class="col-6"><div class="bt-whyus-stat"><div class="bt-whyus-num">۵۰۰۰+</div><div class="bt-whyus-lbl">مسافر راضی</div></div></div>
                    <div class="col-6"><div class="bt-whyus-stat"><div class="bt-whyus-num">۴۰+</div><div class="bt-whyus-lbl">مقصد</div></div></div>
                    <div class="col-6"><div class="bt-whyus-stat"><div class="bt-whyus-num">۲۴/۷</div><div class="bt-whyus-lbl">پشتیبانی</div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bt-section bt-bg-light">
    <div class="container">
        <div class="bt-section-head"><div><h2 class="bt-section-title">مزیت‌های سفر با ما</h2></div></div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6"><div class="bt-benefit-card"><div class="bt-benefit-icon">🎯</div><h5>برنامهٔ اختصاصی</h5><p>هر سفر بر اساس سلیقه و بودجهٔ شما طراحی می‌شود.</p></div></div>
            <div class="col-lg-3 col-md-6"><div class="bt-benefit-card"><div class="bt-benefit-icon">💳</div><h5>پرداخت امن</h5><p>امکان پرداخت اقساطی و شفاف برای تورهای طولانی.</p></div></div>
            <div class="col-lg-3 col-md-6"><div class="bt-benefit-card"><div class="bt-benefit-icon">🛡</div><h5>بیمهٔ سفر</h5><p>همهٔ تورها دارای بیمهٔ مسافرتی کامل هستند.</p></div></div>
            <div class="col-lg-3 col-md-6"><div class="bt-benefit-card"><div class="bt-benefit-icon">🤝</div><h5>پشتیبانی واقعی</h5><p>کارشناس اختصاصی از اولین تماس تا بازگشت همراه شماست.</p></div></div>
        </div>
    </div>
</section>
