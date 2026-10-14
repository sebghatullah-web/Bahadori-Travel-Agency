<?php
/**
 * -------------------------------------------------------------------------
 *  Booking request form (AJAX). Expects optional $destination, $tourId.
 * -------------------------------------------------------------------------
 */
$destination = $destination ?? '';
$tourId = $tourId ?? 0;
?>
<form class="bt-booking-form" data-ajax-form data-success-msg="درخواست شما ثبت شد. به‌زودی با شما تماس می‌گیریم." action="<?= e(base_url('book')) ?>" method="post">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <?php if ($tourId): ?><input type="hidden" name="tour_id" value="<?= e($tourId) ?>"><?php endif; ?>
    <h4 class="bt-booking-title">درخواست رزرو</h4>
    <div class="bt-booking-row">
        <label>نام و تخلص *</label>
        <input type="text" name="name" required placeholder="نام شما">
    </div>
    <div class="bt-booking-row">
        <label>شماره تماس *</label>
        <input type="tel" name="phone" required placeholder="۰۷…">
    </div>
    <div class="bt-booking-row">
        <label>ایمیل</label>
        <input type="email" name="email" placeholder="email@example.com">
    </div>
    <?php if ($destination): ?>
        <div class="bt-booking-row">
            <label>مقصد</label>
            <input type="text" name="destination" value="<?= e($destination) ?>" readonly>
        </div>
    <?php endif; ?>
    <div class="bt-booking-row bt-booking-2">
        <div>
            <label>تاریخ سفر</label>
            <input type="date" name="travel_date">
        </div>
        <div>
            <label>تاریخ برگشت</label>
            <input type="date" name="return_date">
        </div>
    </div>
    <div class="bt-booking-row">
        <label>تعداد مسافران</label>
        <input type="number" name="travelers" min="1" value="1">
    </div>
    <div class="bt-booking-row">
        <label>توضیحات</label>
        <textarea name="notes" rows="3" placeholder="درخواست یا سوال خاصی دارید؟"></textarea>
    </div>
    <button type="submit" class="btn bt-btn-primary w-100">ارسال درخواست</button>
    <div class="bt-form-msg" role="alert"></div>
</form>
