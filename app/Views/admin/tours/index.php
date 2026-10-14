<?php
$this->layout('admin', ['title' => $title]);
$allDests = (new \App\Models\Destination())->all('sort_order', 'ASC');
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">فهرست تورها و پکیج‌ها</h3>
        <button class="btn-bt" data-open-modal="#tourModal" data-title="افزودن تور"><i class="bi bi-plus-lg"></i> افزودن تور</button>
    </div>
    <?php if (empty($tours)): ?>
        <div class="bt-admin-empty">هنوز توری ثبت نشده است.</div>
    <?php else: ?>
        <div style="overflow-x:auto">
        <table class="bt-table">
            <thead>
                <tr><th>تصویر</th><th>عنوان</th><th>مقصد</th><th>قیمت</th><th>مدت</th><th>امتیاز</th><th>ویژه</th><th>عملیات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($tours as $t): ?>
                    <tr>
                        <td data-label="تصویر"><img class="bt-thumb" src="<?= e(upload_url($t['image'])) ?>" alt=""></td>
                        <td data-label="عنوان"><strong><?= e($t['title']) ?></strong></td>
                        <td data-label="مقصد"><?= e($t['destination_name'] ?? $t['location'] ?? '—') ?></td>
                        <td data-label="قیمت"><?= e(fa_number(format_price($t['price']))) ?></td>
                        <td data-label="مدت"><?= e(fa_number($t['duration_days'])) ?> روز</td>
                        <td data-label="امتیاز"><?= e($t['rating']) ?></td>
                        <td data-label="ویژه"><?= $t['is_featured'] ? '★' : '—' ?></td>
                        <td data-label="عملیات">
                            <button class="btn-bt btn-bt-sm btn-bt-edit"
                                data-json="<?= e(json_encode($t, JSON_UNESCAPED_UNICODE)) ?>">ویرایش</button>
                            <button class="btn-bt-danger btn-bt-del" data-id="<?= e($t['id']) ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<div class="bt-modal-backdrop" id="tourModal">
    <div class="bt-modal">
        <div class="bt-modal-head">
            <h4 class="bt-modal-title">افزودن تور</h4>
            <button class="btn-close" data-close-modal></button>
        </div>
        <form enctype="multipart/form-data" data-crud-form data-entity="tours">
            <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
            <input type="hidden" name="id" value="">
            <div class="bt-modal-body">
                <div class="bt-field">
                    <label class="bt-form-label">عنوان تور *</label>
                    <input class="bt-input" name="title" required>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">مقصد</label>
                        <select class="bt-select" name="destination_id">
                            <option value="">— انتخاب مقصد —</option>
                            <?php foreach ($allDests as $d): ?>
                                <option value="<?= e($d['id']) ?>"><?= e($d['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">موقعیت (متن آزاد)</label>
                        <input class="bt-input" name="location">
                    </div>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">برچسب</label>
                        <input class="bt-input" name="badge" placeholder="تور / پکیج / پیش‌فروش">
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">خلاصه</label>
                        <input class="bt-input" name="summary">
                    </div>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">قیمت (افغانی)</label>
                        <input class="bt-input" name="price" inputmode="numeric">
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">قیمت قبلی</label>
                        <input class="bt-input" name="old_price" inputmode="numeric">
                    </div>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">تعداد روز</label>
                        <input class="bt-input" name="duration_days" type="number" min="1" value="1">
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">تعداد شب</label>
                        <input class="bt-input" name="duration_nights" type="number" min="0" value="0">
                    </div>
                </div>
                <div class="bt-form-grid">
                    <div class="bt-field">
                        <label class="bt-form-label">امتیاز</label>
                        <input class="bt-input" name="rating" value="4.5" step="0.1" min="0" max="5">
                    </div>
                    <div class="bt-field">
                        <label class="bt-form-label">ترتیب نمایش</label>
                        <input class="bt-input" name="sort_order" type="number" value="0">
                    </div>
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">تصویر</label>
                    <input class="bt-input" name="image" type="file" accept="image/*">
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">توضیحات کامل</label>
                    <textarea class="bt-textarea" name="description" rows="4"></textarea>
                </div>
                <label class="bt-switch"><input type="checkbox" name="is_featured" value="1"> نمایش در بخش ویژه</label>
            </div>
            <div class="bt-modal-foot">
                <button type="submit" class="btn-bt">ذخیره</button>
                <button type="button" class="btn-bt-ghost" data-close-modal>انصراف</button>
            </div>
        </form>
    </div>
</div>
