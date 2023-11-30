<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use Mail;
use App\Mail\AffiliateCreated;
use Illuminate\Support\Facades\Hash;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService,
        protected ApiService $apiService
    ) {
    }

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        $merchant = Merchant::where('domain', $data['merchant_domain'])->first();
        $user = User::where('email', $data['email'])->first();
        $affiliate = null;
        if (!empty($user)) {
            $affiliate = Affiliate::where(['user_id' => $user->id, 'merchant_id' => $merchant->id])->first();
        }
        if (!$affiliate) {
            $this->createNewAffiliateRecord($data, $merchant);
        }
        if (isset($data['discount_code'])) {
            $affiliate = Affiliate::where(['discount_code' => $data['discount_code'], 'merchant_id' => $merchant->id])->first();
        }

        // $order = Order::where(['affiliate_id' => isset($affiliate->id) ? $affiliate->id : null, 'merchant_id' => $merchant->id, 'discount_code' => $data['discount_code']])->get();
        // if (count($order) == 0) {
        $order_data = [
            'subtotal' => $data['subtotal_price'],
            'discount_code' => $data['discount_code'],
            'merchant_id' => $merchant->id,
            'payout_status' => Order::STATUS_UNPAID,
            'affiliate_id' => isset($affiliate->id) ? $affiliate->id : null,
            'commission_owed' => $data['subtotal_price'] * isset($affiliate->commission_rate) ? $affiliate->commission_rate : 0,
        ];
        return Order::create($order_data);
        // } else {
        //     $order_data = [
        //         'subtotal' => $data['subtotal_price'],
        //         'discount_code' => $data['discount_code'],
        //         'merchant_id' => $merchant->id,
        //         'payout_status' => Order::STATUS_PAID,
        //         'affiliate_id' => isset($affiliate->id) ? $affiliate->id : null,
        //         'commission_owed' => 0,
        //     ];
        //     return Order::create($order_data);
        // }
    }

    public function createNewAffiliateRecord($data, $merchant)
    {

        $user_data = [ //
            'name' => $data['name'], //
            'email' => $data['email'],
            'password' => Hash::make(Str::uuid()),
            'type' => User::TYPE_AFFILIATE
        ];

        $user = User::create($user_data);
        $merchant = Merchant::find($merchant->id);

        if ($merchant) {
            $merchant->increment('turn_customers_into_affiliates');
        }
        $user_record = User::find($user->id);
        $apiService = $this->apiService->createDiscountCode($merchant);

        $affiliate_data = [
            'user_id' => $user_record->id,
            'merchant_id' => $merchant->id,
            'commission_rate' => $merchant->default_commission_rate,
            'discount_code' => $apiService['code']
        ];

        $affiliate = Affiliate::create($affiliate_data);
        Mail::to($data['email'])->send(new AffiliateCreated($affiliate));

        return $affiliate;
    }
}
