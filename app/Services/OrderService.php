<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use Mail;
use App\Mail\AffiliateCreated;
use Dotenv\Exception\ValidationException;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Hash;

class OrderService
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function getAffiliateService(): AffiliateService
    {
        return $this->container->make(AffiliateService::class);
    }

    public function processOrder($data)
    {
        $orderExists = Order::where('external_order_id', $data['order_id'])->exists();
        if ($orderExists) {
            return null; // Order already exists, no action needed
        }

        $affiliate = Affiliate::where('discount_code', $data['discount_code'])->first();

        if (!$affiliate) {
            $merchant = Merchant::where('domain', $data['merchant_domain'])->first();
            $user = User::factory()->create();
            $affiliate = Affiliate::factory()
                ->for($merchant)
                ->for($user)
                ->create([
                    'discount_code' => $data['discount_code']
                ]);
        }

        $merchantId = Merchant::where('domain', $data['merchant_domain'])->value('id');
        $commissionRate = $affiliate ? $affiliate->commission_rate : 0.1;

        $commissionOwed = $data['subtotal_price'] * $commissionRate;
        $affiliateId = $affiliate ? $affiliate->id : null;

        // If there's no affiliate, set the commission owed to zero for the order
        if (!$affiliate) {
            $commissionOwed = 0;
        }

        $order = Order::create([
            'subtotal' => $data['subtotal_price'],
            'discount_code' => $data['discount_code'],
            'merchant_id' => $merchantId,
            'payout_status' => Order::STATUS_UNPAID,
            'affiliate_id' => $affiliateId,
            'commission_owed' => $commissionOwed,
            'external_order_id' => $data['order_id']
        ]);

        return $order;
    }

}
