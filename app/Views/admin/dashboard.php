<?php
/**
 * Admin dashboard — stat cards + bookings chart + recent requests.
 */
$this->layout('admin', ['title' => $title]);

// Build chart data (fill missing days with 0).
$chartMap = [];
foreach ($chart as $row) { $chartMap[$row['day']] = (int) $row['total']; }
$days = [];
for ($i = 13; $i >= 0; $i--) { $days[date('Y-m-d', strtotime("-$i day"))] = 0; }
foreach ($chartMap as $d => $v) { if (isset($days[$d])) $days[$d] = $v; }
$max = max(1, max($days));
?>
<div class="bt-stat-grid">
    <div class="bt-stat-card">
        <div>
            <div class="bt-stat-label">پیام‌های جدید</div>
            <div class="bt-stat-value"><?= e(fa_number($stats['messages'])) ?></div>
        </div>
        <div class="bt-stat-icon"><i class="bi bi-chat-dots"></i></div>
    </div>
    <div class="bt-stat-card">
        <div>
            <div class="bt-stat-label">رزرو تأیید شده</div>
            <div class="bt-stat-value"><?= e(fa_number($stats['confirmed'])) ?></div>
        </div>
        <div class="bt-stat-icon"><i class="bi bi-calendar-check"></i></div>
    </div>
    <div class="bt-stat-card">
        <div>
            <div class="bt-stat-label">در انتظار تأیید</div>
            <div class="bt-stat-value"><?= e(fa_number($stats['pending'])) ?></div>
        </div>
        <div class="bt-stat-icon"><i class="bi bi-hourglass-split"></i></div>
    </div>
    <div class="bt-stat-card">
        <div>
            <div class="bt-stat-label">مشتریان خبرنامه</div>
            <div class="bt-stat-value"><?= e(fa_number($stats['subscribers'])) ?></div>
        </div>
        <div class="bt-stat-icon"><i class="bi bi-people"></i></div>
    </div>
    <div class="bt-stat-card">
        <div>
            <div class="bt-stat-label">تورها و پکیج‌ها</div>
            <div class="bt-stat-value"><?= e(fa_number($stats['tours'])) ?></div>
        </div>
        <div class="bt-stat-icon"><i class="bi bi-box-seam"></i></div>
    </div>
    <div class="bt-stat-card">
        <div>
            <div class="bt-stat-label">مقاصد ثبت‌شده</div>
            <div class="bt-stat-value"><?= e(fa_number($stats['destinations'])) ?></div>
        </div>
        <div class="bt-stat-icon"><i class="bi bi-geo-alt"></i></div>
    </div>
</div>

<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">آخرین درخواست‌های رزرو</h3>
        <a href="<?= e(base_url('admin/bookings')) ?>" class="btn-bt btn-bt-sm">مدیریت همه</a>
    </div>
    <?php if (empty($recentBookings)): ?>
        <div class="bt-admin-empty">هنوز درخواستی ثبت نشده است.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>#</th><th>نام</th><th>تور / مقصد</th><th>تماس</th><th>تاریخ سفر</th><th>وضعیت</th></tr>
            </thead>
            <tbody>
                <?php foreach ($recentBookings as $b): ?>
                    <tr>
                        <td data-label="#"><?= e($b['id']) ?></td>
                        <td data-label="نام"><?= e($b['name']) ?></td>
                        <td data-label="تور / مقصد"><?= e($b['tour_title'] ?? $b['destination'] ?? '—') ?></td>
                        <td data-label="تماس"><?= e($b['phone']) ?></td>
                        <td data-label="تاریخ سفر"><?= e($b['travel_date'] ? fa_number($b['travel_date']) : '—') ?></td>
                        <td data-label="وضعیت"><span class="bt-status bt-status-<?= e($b['status']) ?>"><?= e(['pending'=>'در انتظار','confirmed'=>'تأیید','cancelled'=>'لغو'][$b['status']] ?? $b['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">روند درخواست‌ها (۱۴ روز اخیر)</h3>
    </div>
    <div class="bt-chart">
        <?php foreach ($days as $day => $val): ?>
            <?php $h = $max > 0 ? round(($val / $max) * 180) : 0; ?>
            <div class="bt-chart-bar">
                <div class="bt-chart-col" style="height:<?= max(3, (int)$h) ?>px" title="<?= e($val) ?>">
                    <?php if ($val > 0): ?><span><?= e(fa_number($val)) ?></span><?php endif; ?>
                </div>
                <div class="bt-chart-label"><?= e(fa_number(date('m/d', strtotime($day)))) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
