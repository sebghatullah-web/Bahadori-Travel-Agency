<?php
/**
 * -------------------------------------------------------------------------
 *  Home page. All content comes from the database via the controller.
 * -------------------------------------------------------------------------
 */
$this->layout('frontend', ['pageTitle' => '']);
$heroImg = !empty($settings['hero_image'])
    ? upload_url($settings['hero_image'])
    : 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80';

// Icon map for the benefit cards.
$icons = [
    'compass' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
    'shield'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    'headset' => '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>',
    'tag'     => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
    'star'    => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
];
$iconFor = fn($k) => $icons[$k] ?? $icons['star'];
?>

<!-- =======================  HERO  ======================= -->
<section class="bt-hero" style="background-image:url('<?= e($heroImg) ?>')">
    <div class="bt-hero-overlay"></div>
    <div class="container bt-hero-inner">
        <span class="bt-hero-badge"><?= e($settings['hero_badge'] ?? '') ?></span>
        <h1 class="bt-hero-title"><?= e($settings['hero_title'] ?? '') ?></h1>
        <p class="bt-hero-sub"><?= e($settings['hero_subtitle'] ?? '') ?></p>
        <div class="bt-hero-actions">
            <a href="#destinations" class="btn bt-btn-primary btn-lg"><?= e($settings['hero_cta1'] ?? 'کاوش کنید') ?></a>
            <a href="<?= e(base_url('tours')) ?>" class="btn bt-btn-outline btn-lg"><?= e($settings['hero_cta2'] ?? 'تورها') ?></a>
        </div>
    </div>

    <!-- Hero search box -->
    <div class="container">
        <form class="bt-searchbox" id="heroSearch" autocomplete="off">
            <div class="bt-search-grid">
                <div class="bt-search-field bt-search-dest">
                    <label>مقصد</label>
                    <div class="bt-search-input-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="destination" id="searchQuery" placeholder="بگو کجا… مثلاً دبی" list="destList">
                        <datalist id="destList"></datalist>
                    </div>
                    <div class="bt-search-results" id="searchResults"></div>
                </div>
                <div class="bt-search-field">
                    <label>تاریخ رفت</label>
                    <input type="date" name="travel_date">
                </div>
                <div class="bt-search-field">
                    <label>تاریخ برگشت</label>
                    <input type="date" name="return_date">
                </div>
                <div class="bt-search-field bt-search-narrow">
                    <label>مسافران</label>
                    <input type="number" name="travelers" min="1" value="1">
                </div>
                <div class="bt-search-submit">
                    <button type="button" class="btn bt-btn-primary" id="searchBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        جستجو
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>



<!-- =======================  BENEFITS (services)  ======================= -->
<?php if (!empty($services)): ?>
<section class="bt-section bt-benefits">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($services as $svc): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="bt-benefit-card">
                        <div class="bt-benefit-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $iconFor($svc['icon']) ?></svg>
                        </div>
                        <h5><?= e($svc['title']) ?></h5>
                        <p><?= e($svc['description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- =======================  POPULAR DESTINATIONS  ======================= -->
<?php if (!empty($destinations)): ?>
<section class="bt-section" id="destinations">
    <div class="container">
        <div class="bt-section-head">
            <div>
                <h2 class="bt-section-title">مقاصد پرطرفدار میان مسافران ما</h2>
                <p class="bt-section-sub">از شهرهای مدرن خلیج تا جزیره‌های آرام آسیا — مقصد بعدی خود را انتخاب کنید.</p>
            </div>
            <a href="<?= e(base_url('destinations')) ?>" class="bt-section-link">مشاهده همه مقاصد ←</a>
        </div>
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
    </div>
</section>


<!-- =======================  TOURS & PACKAGES  ======================= -->
<?php if (!empty($tours)): ?>
<section class="bt-section bt-bg-light" id="tours">
    <div class="container">
        <div class="bt-section-head">
            <div>
                <h2 class="bt-section-title">تورها و پکیج‌های سفر</h2>
                <p class="bt-section-sub">برنامه‌های آماده با شامل بلیط، اقامت و برنامهٔ بازدید است.</p>
            </div>
            <a href="<?= e(base_url('tours')) ?>" class="bt-section-link">مشاهده همه تورها ←</a>
        </div>
        <div class="row g-4">
            <?php foreach ($tours as $tour): ?>
                <div class="col-lg-4 col-md-6">
                    <?= $this->partial('frontend/_tour_card', ['tour' => $tour]) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- =======================  WHY US  ======================= -->
<section class="bt-section bt-whyus">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="bt-section-title text-start"><?= e($settings['about_title'] ?? 'چرا بهادری تراول ایجنسی؟') ?></h2>
                <p class="bt-whyus-lead"><?= e($settings['about_intro'] ?? '') ?></p>
                <p class="bt-whyus-text"><?= e($settings['about_story'] ?? '') ?></p>
                <a href="<?= e(base_url('about')) ?>" class="btn bt-btn-primary mt-2">بیشتر بدانید</a>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bt-whyus-stat">
                            <div class="bt-whyus-num"><?= e(fa_number('۱۲')) ?>+</div>
                            <div class="bt-whyus-lbl">سال تجربه</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bt-whyus-stat">
                            <div class="bt-whyus-num"><?= e(fa_number('۵۰۰۰')) ?>+</div>
                            <div class="bt-whyus-lbl">مسافر راضی</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bt-whyus-stat">
                            <div class="bt-whyus-num"><?= e(fa_number('۴۰')) ?>+</div>
                            <div class="bt-whyus-lbl">مقصد جهانی</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bt-whyus-stat">
                            <div class="bt-whyus-num">۲۴/۷</div>
                            <div class="bt-whyus-lbl">پشتیبانی</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =======================  ARTICLES  ======================= -->
<?php if (!empty($articles)): ?>
<section class="bt-section" id="articles">
    <div class="container">
        <div class="bt-section-head">
            <div>
                <h2 class="bt-section-title">مجله سفر</h2>
                <p class="bt-section-sub">راهنماها و تجربه‌های سفر از دفتر تحریریهٔ ما.</p>
            </div>
            <a href="<?= e(base_url('articles')) ?>" class="bt-section-link">همه مقالات ←</a>
        </div>
        <div class="row g-4">
            <?php foreach ($articles as $art): ?>
                <div class="col-lg-4 col-md-6">
                    <a href="<?= e(base_url('articles/' . $art['slug'])) ?>" class="bt-article-card">
                        <div class="bt-article-media">
                            <img src="<?= e(upload_url($art['image'])) ?>" alt="<?= e($art['title']) ?>" loading="lazy">
                        </div>
                        <div class="bt-article-body">
                            <?php if (!empty($art['category'])): ?><span class="bt-article-cat"><?= e($art['category']) ?></span><?php endif; ?>
                            <h5><?= e($art['title']) ?></h5>
                            <p><?= e($art['excerpt']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- =======================  CTA  ======================= -->
<section class="bt-cta" style="background:var(--bt-dark)">
    <div class="container">
        <div class="bt-cta-box">
            <h2><?= e($settings['cta_title'] ?? 'ماجراجویی بعدی شما اینجاست') ?></h2>
            <p><?= e($settings['cta_subtitle'] ?? '') ?></p>
            <a href="<?= e(base_url('contact')) ?>" class="btn bt-btn-primary btn-lg"><?= e($settings['cta_button'] ?? 'تنظیم برنامهٔ سفر') ?></a>
        </div>
    </div>
</section>

<?php endif; ?>
