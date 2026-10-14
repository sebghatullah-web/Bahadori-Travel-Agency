<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Notifier;
use App\Core\Session;
use App\Models\Booking;

/**
 * Public tour booking requests (the "قیمت از" / hero search submit form).
 * Stores a booking row and notifies the admin in real time.
 */
class BookingController extends BaseController
{
    public function store()
    {
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $name        = $this->request->str('name');
        $phone       = $this->request->str('phone');
        $email       = $this->request->str('email');
        $destination = $this->request->str('destination');
        $travelDate  = $this->request->str('travel_date');
        $returnDate  = $this->request->str('return_date');
        $travelers   = max(1, $this->request->int('travelers', 1));
        $tourId      = $this->request->int('tour_id', 0) ?: null;
        $notes       = $this->request->str('notes');

        $errors = [];
        if ($name === '')  $errors['name'] = 'نام و تخلص را وارد کنید.';
        if ($phone === '') $errors['phone'] = 'شماره تماس را وارد کنید.';
        if ($errors) {
            return $this->fail('لطفاً اطلاعات خواسته‌شده را کامل کنید.', 422, $errors);
        }

        $id = (new Booking())->create([
            'name'         => $name,
            'phone'        => $phone,
            'email'        => $email ?: null,
            'tour_id'      => $tourId,
            'destination'  => $destination ?: null,
            'travel_date'  => $this->safeDate($travelDate),
            'return_date'  => $this->safeDate($returnDate),
            'travelers'    => $travelers,
            'notes'        => $notes ?: null,
            'status'       => 'pending',
        ]);

        Notifier::emit([
            'type'        => 'new_booking',
            'id'          => $id,
            'name'        => $name,
            'destination' => $destination,
        ]);

        return $this->ok(null, 'درخواست شما ثبت شد. کارشناسان ما به‌زودی با شما تماس می‌گیرند.');
    }

    /** Return a valid Y-m-d date or null. */
    private function safeDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }
        $ts = strtotime($value);
        return $ts ? date('Y-m-d', $ts) : null;
    }
}
