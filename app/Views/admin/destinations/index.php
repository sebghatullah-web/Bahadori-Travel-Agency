<?php
$this->layout('admin', ['title' => $title]);
$statusMap = ['1' => 'فعال', '0' => 'غیرفعال'];
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">فهرست مقاصد</h3>
        <button class="btn-bt" data-open-modal="#destModal" data-mode="create"><i class="bi bi-plus-lg"></i> افزودن مقصد</button>
    </div>
    <?php if (empty($destinations)): ?>
        <div class="bt-admin-empty">هنوز مقصدی ثبت نشده است. اولین مقصد را اضافه کنید.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>تصویر</th><th>عنوان</th><th>کشور</th><th>قیمت از</th><th>امتیاز</th><th>ویژه</th><th>عملیات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($destinations as $d): ?>
                    <tr data-id="<?= e($d['id']) ?>">
                        <td data-label="تصویر"><img class="bt-thumb" src="<?= e(upload_url($d['image'])) ?>" alt=""></td>
                        <td data-label="عنوان"><strong><?= e($d['title']) ?></strong></td>
                        <td data-label="کشور"><?= e($d['country']) ?></td>
                        <td data-label="قیمت از"><?= e(fa_number(format_price($d['price_from']))) ?></td>
                        <td data-label="امتیاز"><?= e($d['rating']) ?></td>
                        <td data-label="ویژه"><?= $d['is_featured'] ? '★' : '—' ?></td>
                        <td data-label="عملیات">
                            <button class="btn-bt btn-bt-sm btn-bt-edit"
                                data-json="<?= e(json_encode($d, JSON_UNESCAPED_UNICODE)) ?>">ویرایش</button>
                            <button class="btn-bt-danger btn-bt-del" data-id="<?= e($d['id']) ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<!-- ====================  Modal form  ==================== -->
<div class="bt-modal-backdrop" id="destModal">
    <div class="bt-modal">
        <div class="bt-modal-head">
            <h4 class="bt-modal-title" id="destModalTitle">افزودن مقصد</h4>
            <button class="btn-close" data-close-modal></button>
        </div>
        <form enctype="multipart/form-data" data-crud-form data-entity="destinations">
            <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
            <input type="hidden" name="id" value="">
            <div class="bt-modal-body">
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">عنوان *</label>
                        <input class="bt-input" name="title" required>
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">کشور / موقعیت</label>
                        <input class="bt-input" name="country">
                    </div>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">قیمت از (افغانی)</label>
                        <input class="bt-input" name="price_from" inputmode="numeric">
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">امتیاز (۰ تا ۵)</label>
                        <input class="bt-input" name="rating" value="4.5" step="0.1" min="0" max="5">
                    </div>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">ترتیب نمایش</label>
                        <input class="bt-input" name="sort_order" type="number" value="0">
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">تصویر</label>
                        <input class="bt-input" name="image" type="file" accept="image/*">
                    </div>
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">توضیحات</label>
                    <textarea class="bt-textarea" name="description" rows="3"></textarea>
                </div>
                <label class="bt-switch"><input type="checkbox" name="is_featured" value="1" checked> نمایش در بخش ویژه</label>
            </div>
            <div class="bt-modal-foot">
                <button type="submit" class="btn-bt">ذخیره</button>
                <button type="button" class="btn-bt-ghost" data-close-modal>انصراف</button>
            </div>
        </form>
    </div>
</div>
