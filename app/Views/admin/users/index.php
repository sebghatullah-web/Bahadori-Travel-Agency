<?php
$this->layout('admin', ['title' => $title]);
?>
<div class="bt-panel">
    <div class="bt-panel-head">
        <h3 class="bt-panel-title">مدیران سیستم</h3>
        <button class="btn-bt" data-open-modal="#userModal" data-title="افزودن مدیر"><i class="bi bi-plus-lg"></i> افزودن مدیر</button>
    </div>
    <div style="overflow-x:auto">
    <table class="bt-table">
        <thead>
            <tr><th>#</th><th>نام</th><th>ایمیل</th><th>نقش</th><th>تاریخ ایجاد</th><th>عملیات</th></tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td data-label="#"><?= e($u['id']) ?></td>
                    <td data-label="نام"><strong><?= e($u['name']) ?></strong></td>
                    <td data-label="ایمیل"><?= e($u['email']) ?></td>
                    <td data-label="نقش"><?= e($u['role'] === 'admin' ? 'مدیر' : 'ویرایشگر') ?></td>
                    <td data-label="تاریخ ایجاد"><?= e(fa_number(date('Y/m/d', strtotime($u['created_at'])))) ?></td>
                    <td data-label="عملیات">
                        <button class="btn-bt btn-bt-sm btn-bt-edit"
                            data-json="<?= e(json_encode(['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']], JSON_UNESCAPED_UNICODE)) ?>">ویرایش</button>
                        <button class="btn-bt-danger btn-bt-del" data-id="<?= e($u['id']) ?>">حذف</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<div class="bt-modal-backdrop" id="userModal">
    <div class="bt-modal">
        <div class="bt-modal-head">
            <h4 class="bt-modal-title">افزودن مدیر</h4>
            <button class="btn-close" data-close-modal></button>
        </div>
        <form data-crud-form data-entity="users">
            <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
            <input type="hidden" name="id" value="">
            <div class="bt-modal-body">
                <div class="bt-field">
                    <label class="bt-form-label">نام *</label>
                    <input class="bt-input" name="name" required>
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">ایمیل *</label>
                    <input class="bt-input" type="email" name="email" required>
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">گذرواژه <small class="text-muted">(برای ویرایش خالی بگذارید)</small></label>
                    <input class="bt-input" type="password" name="password">
                </div>
                <div class="bt-field">
                    <label class="bt-form-label">نقش</label>
                    <select class="bt-select" name="role">
                        <option value="admin">مدیر</option>
                        <option value="editor">ویرایشگر</option>
                    </select>
                </div>
            </div>
            <div class="bt-modal-foot">
                <button type="submit" class="btn-bt">ذخیره</button>
                <button type="button" class="btn-bt-ghost" data-close-modal>انصراف</button>
            </div>
        </form>
    </div>
</div>
