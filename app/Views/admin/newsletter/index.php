<?php
$this->layout('admin', ['title' => $title]);
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">اعضای خبرنامه (<?= e(fa_number(count($emails))) ?>)</h3>
    </div>
    <?php if (empty($emails)): ?>
        <div class="bt-admin-empty">هنوز عضوی در خبرنامه ثبت نشده است.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>#</th><th>ایمیل</th><th>تاریخ عضویت</th><th>عملیات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($emails as $e0): ?>
                    <tr>
                        <td data-label="#"><?= e($e0['id']) ?></td>
                        <td data-label="ایمیل"><?= e($e0['email']) ?></td>
                        <td data-label="تاریخ عضویت"><?= e(fa_number(date('Y/m/d H:i', strtotime($e0['created_at'])))) ?></td>
                        <td data-label="عملیات">
                            <button class="btn-bt-danger btn-bt-del" data-id="<?= e($e0['id']) ?>" data-delete-url="<?= e(base_url('admin/newsletter/delete')) ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>
