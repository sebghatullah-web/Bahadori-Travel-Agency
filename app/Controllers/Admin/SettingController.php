<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Setting;

/**
 * Site settings manager. Every editable text / colour / link on the site is
 * stored in the `settings` key/value table and edited here.
 */
class SettingController extends BaseController
{
    /** Whitelisted keys and their group — keeps the form and DB in sync. */
    private const FIELDS = [
        'general' => [
            'site_name', 'site_tagline', 'site_logo', 'footer_copyright',
        ],
        'contact' => [
            'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
        ],
        'hero' => [
            'hero_badge', 'hero_title', 'hero_subtitle', 'hero_cta1', 'hero_cta2', 'hero_image',
        ],
        'about' => [
            'about_title', 'about_intro', 'about_story',
        ],
        'cta' => [
            'cta_title', 'cta_subtitle', 'cta_button', 'cta_image',
        ],
        'social' => [
            'social_youtube', 'social_twitter', 'social_instagram', 'social_telegram',
        ],
        'appearance' => [
            'color_primary', 'color_dark', 'color_light',
        ],
    ];

    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/settings/index', [
            'title'    => 'تنظیمات سایت',
            'settings' => Setting::allMap(),
            'groups'   => self::FIELDS,
        ]);
    }

    public function update()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $all = $this->request->all();
        $saved = 0;

        foreach (self::FIELDS as $group => $keys) {
            foreach ($keys as $key) {
                // Skip file-based fields (handled below).
                if (in_array($key, ['site_logo', 'hero_image', 'cta_image'], true)) {
                    continue;
                }
                if (array_key_exists($key, $all)) {
                    Setting::set($key, (string) $this->request->str($key), $group);
                    $saved++;
                }
            }
        }

        // Handle uploaded image fields.
        foreach (['site_logo', 'hero_image', 'cta_image'] as $field) {
            $group = $field === 'site_logo' ? 'general' : ($field === 'hero_image' ? 'hero' : 'cta');
            if (!empty($_FILES[$field]) && ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $name = Upload::image($_FILES[$field], $this->config);
                $old = Setting::get($field);
                Upload::remove($old, $this->config);
                Setting::set($field, $name, $group);
                $saved++;
            }
        }

        if ($saved === 0) {
            return $this->fail('هیچ تغییری ثبت نشد.', 422);
        }

        return $this->ok(null, "تنظیمات ذخیره شد ({$saved} مورد).");
    }
}
