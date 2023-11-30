<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Support\Facades\Validator;

class AffilatesController extends Controller
{
    public function __construct(
        protected AffiliateService $AffiliateService
    ) {
    }

    public function affilateUsers()
    {
        $merchant_id = auth()->user()->merchant->id;
        $affiliates = Affiliate::with(['user'])->where(['merchant_id' => $merchant_id])->get();
        return view('pages.affilate-users', compact('affiliates'));
    }
    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function addAffilateUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        /* send validation messages if any */
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['msg'] = 'There is some validation errors';
            $response['errors'] = array_map(function ($val) {
                return $val[0];
            }, $validator->getMessageBag()->toArray());
            return Response::json($response);
        }
        $data_array = $request->all();
        $merchant = Merchant::with('User')->where('id', $data_array['merchant_id'])->first();
        $merchantResult = $this->AffiliateService->register($merchant,  $data_array['email'], $data_array['name'], floatval($merchant->default_commission_rate));
        return Response::json([$merchantResult]);
    }
    public function commissionEarned()
    {
        $merchant_id = auth()->user()->merchant->id;
        $affiliates = Affiliate::with(['orders', 'user'])->where(['merchant_id' => $merchant_id])->get();
        return view('pages.addilate-users-commission-earned', compact('affiliates'));
    }
}
