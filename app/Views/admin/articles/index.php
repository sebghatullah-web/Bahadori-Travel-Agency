<?php
$this->layout('admin', ['title' => $title]);
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">فهرست مقالات</h3>
        <button class="btn-bt" data-open-modal="#artModal" data-title="افزودن مقاله"><i class="bi bi-plus-lg"></i> افزودن مقاله</button>
    </div>
    <?php if (empty($articles)): ?>
        <div class="bt-admin-empty">هنوز مقاله‌ای ثبت نشده است.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>تصویر</th><th>عنوان</th><th>دسته</th><th>نویسنده</th><th>بازدید</th><th>وضعیت</th><th>عملیات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $a): ?>
                    <tr>
                        <td data-label="تصویر"><img class="bt-thumb" src="<?= e(upload_url($a['image'])) ?>" alt=""></td>
                        <td data-label="عنوان"><strong><?= e($a['title']) ?></strong></td>
                        <td data-label="دسته"><?= e($a['category'] ?? '—') ?></td>
                        <td data-label="نویسنده"><?= e($a['author'] ?? '—') ?></td>
                        <td data-label="بازدید"><?= e(fa_number($a['views'])) ?></td>
                        <td data-label="وضعیت"><span class="bt-status <?= $a['is_published'] ? 'bt-status-confirmed' : 'bt-status-read' ?>"><?= $a['is_published'] ? 'منتشر شده' : 'پیش‌نویس' ?></span></td>
                        <td data-label="عملیات">
                            <button class="btn-bt btn-bt-sm btn-bt-edit"
                                data-json="<?= e(json_encode($a, JSON_UNESCAPED_UNICODE)) ?>">ویرایش</button>
                            <button class="btn-bt-danger btn-bt-del" data-id="<?= e($a['id']) ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<div class="bt-modal-backdrop" id="artModal">
    <div class="bt-modal">
        <div class="bt-modal-head">
            <h4 class="bt-modal-title">افزودن مقاله</h4>
            <button class="btn-close" data-close-modal></button>
        </div>
        <form enctype="multipart/form-data" data-crud-form data-entity="articles">
            <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
            <input type="hidden" name="id" value="">
            <div class="bt-modal-body">
                <div class="bt-field">
                    <label class="bt-form-label">عنوان مقاله *</label>
                    <input class="bt-input" name="title" required>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">دسته‌بندی</label>
                        <input class="bt-input" name="category" placeholder="مثلاً راهنمای سفر">
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">نویسنده</label>
                        <input class="bt-input" name="author">
                    </div>
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">خلاصه</label>
                    <textarea class="bt-textarea" name="excerpt" rows="2"></textarea>
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">تصویر شاخص</label>
                    <input class="bt-input" name="image" type="file" accept="image/*">
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">متن کامل</label>
                    <textarea class="bt-textarea" name="body" rows="6"></textarea>
                </div>
                <label class="bt-switch"><input type="checkbox" name="is_published" value="1" checked> انتشار</label>
            </div>
            <div class="bt-modal-foot">
                <button type="submit" class="btn-bt">ذخیره</button>
                <button type="button" class="btn-bt-ghost" data-close-modal>انصراف</button>
            </div>
        </form>
    </div>
</div>
