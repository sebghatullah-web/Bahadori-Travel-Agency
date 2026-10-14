<?php
$this->layout('admin', ['title' => $title]);
$v = fn($k) => $settings[$k] ?? '';
?>
<form data-settings-form enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">

    <div class="bt-tabs">
        <div class="bt-tab active" data-tab="general">عمومی</div>
        <div class="bt-tab" data-tab="contact">تماس</div>
        <div class="bt-tab" data-tab="hero">اسلایدر اصلی</div>
        <div class="bt-tab" data-tab="about">درباره ما</div>
        <div class="bt-tab" data-tab="cta">فراخوان (CTA)</div>
        <div class="bt-tab" data-tab="social">شبکه‌های اجتماعی</div>
        <div class="bt-tab" data-tab="appearance">ظاهر و رنگ</div>
    </div>

    <!-- General -->
    <div class="bt-panel bt-tabpane active" id="tab-general">
        <div class="bt-panel-head"><h3 class="bt-panel-title">تنظیمات عمومی</h3></div>
        <div class="bt-form-grid">
            <div class="bt-field"><label class="bt-form-label">نام سایت</label><input class="bt-input" name="site_name" value="<?= e($v('site_name')) ?>"></div>
            <div class="bt-field"><label class="bt-form-label">شعار / زیرعنوان</label><input class="bt-input" name="site_tagline" value="<?= e($v('site_tagline')) ?>"></div>
        </div>
        <div class="bt-field"><label class="bt-form-label">متن کپی‌رایت فوتر</label><input class="bt-input" name="footer_copyright" value="<?= e($v('footer_copyright')) ?>"></div>
        <div class="bt-field">
            <label class="bt-form-label">لوگوی سایت (در صورت خالی بودن، آیکون پیش‌فرض نمایش داده می‌شود)</label>
            <input class="bt-input" name="site_logo" type="file" accept="image/*">
            <?php if ($v('site_logo')): ?><div class="mt-2"><img src="<?= e(upload_url($v('site_logo'))) ?>" style="height:40px" alt=""></div><?php endif; ?>
        </div>
    </div>

    <!-- Contact -->
    <div class="bt-panel bt-tabpane" id="tab-contact">
        <div class="bt-panel-head"><h3 class="bt-panel-title">اطلاعات تماس</h3></div>
        <div class="bt-form-grid">
            <div class="bt-field"><label class="bt-form-label">تلفن</label><input class="bt-input" name="contact_phone" value="<?= e($v('contact_phone')) ?>"></div>
            <div class="bt-field"><label class="bt-form-label">ایمیل</label><input class="bt-input" name="contact_email" value="<?= e($v('contact_email')) ?>"></div>
        </div>
        <div class="bt-field"><label class="bt-form-label">آدرس</label><input class="bt-input" name="contact_address" value="<?= e($v('contact_address')) ?>"></div>
        <div class="bt-field"><label class="bt-form-label">ساعات کاری</label><input class="bt-input" name="contact_hours" value="<?= e($v('contact_hours')) ?>"></div>
    </div>


    <!-- Hero -->
    <div class="bt-panel bt-tabpane" id="tab-hero">
        <div class="bt-panel-head"><h3 class="bt-panel-title">بخش اصلی صفحه (Hero)</h3></div>
        <div class="bt-field"><label class="bt-form-label">برچسب کوچک</label><input class="bt-input" name="hero_badge" value="<?= e($v('hero_badge')) ?>"></div>
        <div class="bt-field"><label class="bt-form-label">تیتر اصلی</label><input class="bt-input" name="hero_title" value="<?= e($v('hero_title')) ?>"></div>
        <div class="bt-field"><label class="bt-form-label">زیرتیتر</label><textarea class="bt-textarea" name="hero_subtitle" rows="2"><?= e($v('hero_subtitle')) ?></textarea></div>
        <div class="bt-form-grid">
            <div class="bt-field"><label class="bt-form-label">متن دکمهٔ اول</label><input class="bt-input" name="hero_cta1" value="<?= e($v('hero_cta1')) ?>"></div>
            <div class="bt-field"><label class="bt-form-label">متن دکمهٔ دوم</label><input class="bt-input" name="hero_cta2" value="<?= e($v('hero_cta2')) ?>"></div>
        </div>
        <div class="bt-field">
            <label class="bt-form-label">تصویر پس‌زمینهٔ Hero</label>
            <input class="bt-input" name="hero_image" type="file" accept="image/*">
            <?php if ($v('hero_image')): ?><div class="mt-2"><img src="<?= e(upload_url($v('hero_image'))) ?>" style="height:60px;border-radius:8px" alt=""></div><?php endif; ?>
        </div>
    </div>

    <!-- About -->
    <div class="bt-panel bt-tabpane" id="tab-about">
        <div class="bt-panel-head"><h3 class="bt-panel-title">بخش درباره ما</h3></div>
        <div class="bt-field"><label class="bt-form-label">تیتر</label><input class="bt-input" name="about_title" value="<?= e($v('about_title')) ?>"></div>
        <div class="bt-field"><label class="bt-form-label">مقدمه</label><textarea class="bt-textarea" name="about_intro" rows="2"><?= e($v('about_intro')) ?></textarea></div>
        <div class="bt-field"><label class="bt-form-label">داستان / توضیحات کامل</label><textarea class="bt-textarea" name="about_story" rows="4"><?= e($v('about_story')) ?></textarea></div>
    </div>

    <!-- CTA -->
    <div class="bt-panel bt-tabpane" id="tab-cta">
        <div class="bt-panel-head"><h3 class="bt-panel-title">بخش فراخوان عمل (CTA)</h3></div>
        <div class="bt-field"><label class="bt-form-label">تیتر</label><input class="bt-input" name="cta_title" value="<?= e($v('cta_title')) ?>"></div>
        <div class="bt-field"><label class="bt-form-label">زیرتیتر</label><textarea class="bt-textarea" name="cta_subtitle" rows="2"><?= e($v('cta_subtitle')) ?></textarea></div>
        <div class="bt-field"><label class="bt-form-label">متن دکمه</label><input class="bt-input" name="cta_button" value="<?= e($v('cta_button')) ?>"></div>
    </div>

    <!-- Social -->
    <div class="bt-panel bt-tabpane" id="tab-social">
        <div class="bt-panel-head"><h3 class="bt-panel-title">شبکه‌های اجتماعی</h3></div>
        <div class="bt-form-grid">
            <div class="bt-field"><label class="bt-form-label">یوتیوب</label><input class="bt-input" name="social_youtube" value="<?= e($v('social_youtube')) ?>"></div>
            <div class="bt-field"><label class="bt-form-label">توییتر</label><input class="bt-input" name="social_twitter" value="<?= e($v('social_twitter')) ?>"></div>
            <div class="bt-field"><label class="bt-form-label">اینستاگرام</label><input class="bt-input" name="social_instagram" value="<?= e($v('social_instagram')) ?>"></div>
            <div class="bt-field"><label class="bt-form-label">تلگرام</label><input class="bt-input" name="social_telegram" value="<?= e($v('social_telegram')) ?>"></div>
        </div>
    </div>

    <!-- Appearance -->
    <div class="bt-panel bt-tabpane" id="tab-appearance">
        <div class="bt-panel-head"><h3 class="bt-panel-title">رنگ‌های سایت</h3></div>
        <div class="bt-form-grid">
            <div class="bt-field"><label class="bt-form-label">رنگ اصلی (طلایی)</label><input class="bt-input" type="color" name="color_primary" value="<?= e($v('color_primary') ?: '#c9a24b') ?>"></div>
            <div class="bt-field"><label class="bt-form-label">رنگ تیره (فوتر/هدر)</label><input class="bt-input" type="color" name="color_dark" value="<?= e($v('color_dark') ?: '#101828') ?>"></div>
            <div class="bt-field"><label class="bt-form-label">رنگ روشن (پس‌زمینه)</label><input class="bt-input" type="color" name="color_light" value="<?= e($v('color_light') ?: '#f6f1e7') ?>"></div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn-bt btn-lg"><i class="bi bi-check2"></i> ذخیرهٔ همهٔ تنظیمات</button>
    </div>
</form>
