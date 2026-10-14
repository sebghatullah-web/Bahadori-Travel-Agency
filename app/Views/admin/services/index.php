<?php
$this->layout('admin', ['title' => $title]);
$icons = ['compass','shield','headset','tag','star'];
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">خدمات و مزیت‌ها (کارت‌های صفحه اصلی)</h3>
        <button class="btn-bt" data-open-modal="#svcModal" data-title="افزودن خدمت"><i class="bi bi-plus-lg"></i> افزودن خدمت</button>
    </div>
    <?php if (empty($services)): ?>
        <div class="bt-admin-empty">هنوز خدمتی ثبت نشده است.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>آیکون</th><th>عنوان</th><th>توضیحات</th><th>ترتیب</th><th>وضعیت</th><th>عملیات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                    <tr>
                        <td data-label="آیکون"><i class="bi bi-<?= e($s['icon']) ?>"></i> <?= e($s['icon']) ?></td>
                        <td data-label="عنوان"><strong><?= e($s['title']) ?></strong></td>
                        <td data-label="توضیحات"><?= e(mb_substr($s['description'] ?? '', 0, 50)) ?>…</td>
                        <td data-label="ترتیب"><?= e($s['sort_order']) ?></td>
                        <td data-label="وضعیت"><span class="bt-status <?= $s['is_active'] ? 'bt-status-confirmed' : 'bt-status-read' ?>"><?= $s['is_active'] ? 'فعال' : 'غیرفعال' ?></span></td>
                        <td data-label="عملیات">
                            <button class="btn-bt btn-bt-sm btn-bt-edit"
                                data-json="<?= e(json_encode($s, JSON_UNESCAPED_UNICODE)) ?>">ویرایش</button>
                            <button class="btn-bt-danger btn-bt-del" data-id="<?= e($s['id']) ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<div class="bt-modal-backdrop" id="svcModal">
    <div class="bt-modal">
        <div class="bt-modal-head">
            <h4 class="bt-modal-title">افزودن خدمت</h4>
            <button class="btn-close" data-close-modal></button>
        </div>
        <form data-crud-form data-entity="services">
            <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
            <input type="hidden" name="id" value="">
            <div class="bt-modal-body">
                <div class="bt-field">
                    <label class="bt-form-label">عنوان *</label>
                    <input class="bt-input" name="title" required>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">آیکون</label>
                        <select class="bt-select" name="icon">
                            <?php foreach ($icons as $ic): ?>
                                <option value="<?= e($ic) ?>"><?= e($ic) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">ترتیب نمایش</label>
                        <input class="bt-input" name="sort_order" type="number" value="0">
                    </div>
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">توضیحات</label>
                    <textarea class="bt-textarea" name="description" rows="3"></textarea>
                </div>
                <label class="bt-switch"><input type="checkbox" name="is_active" value="1" checked> فعال</label>
            </div>
            <div class="bt-modal-foot">
                <button type="submit" class="btn-bt">ذخیره</button>
                <button type="button" class="btn-bt-ghost" data-close-modal>انصراف</button>
            </div>
        </form>
    </div>
</div>
