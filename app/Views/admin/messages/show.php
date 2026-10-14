<?php
$this->layout('admin', ['title' => $title]);
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">پیام از <?= e($message['name']) ?></h3>
        <a href="<?= e(base_url('admin/messages')) ?>" class="btn-bt-ghost btn-bt-sm">← بازگشت</a>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-md-4"><div class="bt-info-card"><span class="bt-info-k">ایمیل</span><span class="bt-info-v"><?= e($message['email']) ?></span></div></div>
        <div class="col-md-4"><div class="bt-info-card"><span class="bt-info-k">تلفن</span><span class="bt-info-v"><?= e($message['phone'] ?: '—') ?></span></div></div>
        <div class="col-md-4"><div class="bt-info-card"><span class="bt-info-k">تاریخ</span><span class="bt-info-v"><?= e(fa_number(date('Y/m/d H:i', strtotime($message['created_at'])))) ?></span></div></div>
    </div>
    <h5>موضوع: <?= e($message['subject'] ?? '—') ?></h5>
    <div class="bt-prose" style="background:var(--bt-admin-bg);padding:16px;border-radius:12px"><?= nl2br(e($message['message'])) ?></div>
    <div class="mt-3">
        <a href="mailto:<?= e($message['email']) ?>" class="btn-bt">پاسخ با ایمیل</a>
        <button class="btn-bt-danger btn-bt-del" data-id="<?= e($message['id']) ?>" data-delete-url="<?= e(base_url('admin/messages/delete')) ?>">حذف پیام</button>
    </div>
</div>
