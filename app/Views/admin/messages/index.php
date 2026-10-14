<?php
$this->layout('admin', ['title' => $title]);
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">صندوق پیام‌ها</h3>
    </div>
    <?php if (empty($messages)): ?>
        <div class="bt-admin-empty">هیچ پیامی وجود ندارد.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>وضعیت</th><th>نام</th><th>ایمیل</th><th>موضوع</th><th>تاریخ</th><th>عملیات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $m): ?>
                    <tr style="<?= $m['is_read'] ? '' : 'background:#fff8ea' ?>">
                        <td data-label="وضعیت"><span class="bt-status <?= $m['is_read'] ? 'bt-status-read' : 'bt-status-unread' ?>"><?= $m['is_read'] ? 'خوانده شده' : 'جدید' ?></span></td>
                        <td data-label="نام"><strong><?= e($m['name']) ?></strong></td>
                        <td data-label="ایمیل"><?= e($m['email']) ?></td>
                        <td data-label="موضوع"><?= e($m['subject'] ?? '—') ?></td>
                        <td data-label="تاریخ"><?= e(fa_number(date('Y/m/d H:i', strtotime($m['created_at'])))) ?></td>
                        <td data-label="عملیات">
                            <a class="btn-bt btn-bt-sm btn-bt-ghost" href="<?= e(base_url('admin/messages/' . $m['id'])) ?>">مشاهده</a>
                            <button class="btn-bt-danger btn-bt-del" data-id="<?= e($m['id']) ?>" data-delete-url="<?= e(base_url('admin/messages/delete')) ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>
