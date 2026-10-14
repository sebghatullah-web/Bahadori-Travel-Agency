<?php
$this->layout('frontend', ['pageTitle' => 'تماس با ما']);
?>
<section class="bt-page-head" style="background:var(--bt-dark)">
    <div class="container text-center">
        <h1>تماس با ما</h1>
        <p>ما همیشه آمادهٔ پاسخگویی و مشاورهٔ رایگان هستیم</p>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="bt-contact-info">
                    <h4>اطلاعات تماس</h4>
                    <div class="bt-contact-item">
                        <span class="bt-contact-ic">📞</span>
                        <div><strong>تلفن</strong><span><?= e($settings['contact_phone'] ?? '') ?></span></div>
                    </div>
                    <div class="bt-contact-item">
                        <span class="bt-contact-ic">✉️</span>
                        <div><strong>ایمیل</strong><span><?= e($settings['contact_email'] ?? '') ?></span></div>
                    </div>
                    <div class="bt-contact-item">
                        <span class="bt-contact-ic">📍</span>
                        <div><strong>آدرس</strong><span><?= e($settings['contact_address'] ?? '') ?></span></div>
                    </div>
                    <div class="bt-contact-item">
                        <span class="bt-contact-ic">🕒</span>
                        <div><strong>ساعات کاری</strong><span><?= e($settings['contact_hours'] ?? '') ?></span></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <form class="bt-contact-form" data-ajax-form data-success-msg="پیام شما با موفقیت ارسال شد." action="<?= e(base_url('contact')) ?>" method="post">
                    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
                    <h4>ارسال پیام</h4>
                    <div class="bt-booking-row bt-booking-2">
                        <div><label>نام و تخلص *</label><input type="text" name="name" required></div>
                        <div><label>ایمیل *</label><input type="email" name="email" required></div>
                    </div>
                    <div class="bt-booking-row bt-booking-2">
                        <div><label>شماره تماس</label><input type="tel" name="phone"></div>
                        <div><label>موضوع</label><input type="text" name="subject"></div>
                    </div>
                    <div class="bt-booking-row">
                        <label>متن پیام *</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn bt-btn-primary">ارسال پیام</button>
                    <div class="bt-form-msg" role="alert"></div>
                </form>
            </div>
        </div>
    </div>
</section>
