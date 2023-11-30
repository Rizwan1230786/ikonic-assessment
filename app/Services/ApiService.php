<?php

namespace App\Services;

use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Merchant;
use Illuminate\Support\Str;
use RuntimeException;
use Mail;
use App\Mail\SendPayout;
use App\Models\Order;

/**
 * You don't need to do anything here. This is just to help
 */
class ApiService
{
    /**
     * Create a new discount code for an affiliate
     *
     * @param Merchant $merchant
     *
     * @return array{id: int, code: string}
     */
    public function createDiscountCode(Merchant $merchant): array
    {
        return [
            'id' => rand(0, 100000),
            'code' => Str::uuid()
        ];
    }
    /**
     * Send payouts for unpaid orders
     *
     * @return void
     */
    public function sendPayout()
    {
        $orders = Order::where('payout_status', Order::STATUS_UNPAID)->get();

        foreach ($orders as $order) {
            // Dispatch the job with the Order instance
            PayoutOrderJob::dispatch($order);
        }
        return;
    }
}
