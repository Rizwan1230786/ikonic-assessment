<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\ApiService;
use Illuminate\Support\Facades\Hash;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {
    }

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        $merchantUserEmail = $merchant->user->email;

        // Check if the provided email is already used as a merchant or an affiliate
        $userAsMerchant = User::where('email', $email)->where('type', User::TYPE_MERCHANT)->first();
        $userAsAffiliate = User::where('email', $email)->where('type', User::TYPE_AFFILIATE)->first();

        if ($userAsMerchant || $merchantUserEmail === $email) {
            throw new AffiliateCreateException('Email is already in use as a merchant.');
        } elseif ($userAsAffiliate) {
            throw new AffiliateCreateException('Email is already in use as an affiliate.');
        }

        if ($merchant->user->email == $email) { //check if email use as merchant 
            $user_id = $merchant->user->id;
            $merchant_id = $merchant->id;
        } else {
            $user = $this->createNewUser($name, $email, $merchant->id); //create new user 
            $user_id = $user->id;
            $merchant_id = $merchant->id;
        }


        $apiService = $this->apiService->createDiscountCode($merchant);


        $affiliate = Affiliate::where('user_id', $user_id)
            ->where('merchant_id', $merchant_id)
            ->where('commission_rate', $commissionRate)
            ->where('discount_code', $apiService['code'])
            ->first();

        if (!$affiliate) {
            $affiliate_data = [
                'user_id' => $user_id,
                'merchant_id' => $merchant_id,
                'commission_rate' => $commissionRate,
                'discount_code' => $apiService['code']
            ];
            $affiliate = Affiliate::create($affiliate_data);

            if ($affiliate) {
                Mail::to($email)->send(new AffiliateCreated($affiliate));
            }
        }
        return $affiliate;
    }

    public function createNewUser($name, $email, $merchant_id)
    {

        $user_check = User::where('email', $email)->first();

        if (!$user_check) {
            $user_data = [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::uuid()),
                'type' => User::TYPE_AFFILIATE
            ];

            $user = User::create($user_data);
            $merchantUser = Merchant::find($merchant_id);

            if ($merchantUser) {
                $merchantUser->increment('turn_customers_into_affiliates');
            }
            return User::find($user->id);
        }

        return $user_check;
    }
}
