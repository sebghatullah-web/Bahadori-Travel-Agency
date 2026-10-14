<?php
$this->layout('admin', ['title' => $title]);
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">درخواست‌های رزرو</h3>
    </div>
    <?php if (empty($bookings)): ?>
        <div class="bt-admin-empty">هنوز درخواستی ثبت نشده است.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>#</th><th>نام</th><th>تماس</th><th>تور / مقصد</th><th>تاریخ سفر</th><th>مسافران</th><th>وضعیت</th><th>تاریخ ثبت</th><th>عملیات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td data-label="#"><?= e($b['id']) ?></td>
                        <td data-label="نام"><strong><?= e($b['name']) ?></strong></td>
                        <td data-label="تماس"><?= e($b['phone']) ?><?= $b['email'] ? '<br><small class="text-muted">'.e($b['email']).'</small>' : '' ?></td>
                        <td data-label="تور / مقصد"><?= e($b['tour_title'] ?? $b['destination'] ?? '—') ?></td>
                        <td data-label="تاریخ سفر"><?= e($b['travel_date'] ? fa_number($b['travel_date']) : '—') ?></td>
                        <td data-label="مسافران"><?= e(fa_number($b['travelers'])) ?></td>
                        <td data-label="وضعیت">
                            <select class="bt-select bt-status-select" data-id="<?= e($b['id']) ?>" style="min-width:120px">
                                <?php foreach (['pending'=>'در انتظار','confirmed'=>'تأیید شده','cancelled'=>'لغو'] as $k=>$v): ?>
                                    <option value="<?= $k ?>" <?= $b['status']===$k?'selected':'' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td data-label="تاریخ ثبت"><?= e(fa_number(date('Y/m/d H:i', strtotime($b['created_at'])))) ?></td>
                        <td data-label="عملیات">
                            <button class="btn-bt-danger btn-bt-del" data-id="<?= e($b['id']) ?>" data-delete-url="<?= e(base_url('admin/bookings/delete')) ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>
