<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken($enrollment, $course, $user)
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'ENROLL-' . $enrollment->id . '-' . time(),
                'gross_amount' => $course->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [[
                'id' => $course->id,
                'price' => $course->price,
                'quantity' => 1,
                'name' => $course->title,
            ]],
        ];

        return Snap::getSnapToken($params);
    }
}