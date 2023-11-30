<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Order;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{
    public function __construct(
        protected MerchantService $merchantService
    ) {
    }

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        $to = $request->to;
        $from = $request->from;
        $order = Order::whereBetween('created_at', [$from, $to])->get();

        $data = [
            'count' => $order->count(),
            'commissions_owed' => $order->sum('commission_owed'),
            'revenue' => $order->sum('subtotal'),
        ];

        return Response::json($data);
    }
    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'domain' => 'required',
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
        $merchantResult = $this->merchantService->register($data_array);
        return Response::json([$merchantResult]);
    }
    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function updateMerchant(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required',
            'domain' => 'required',
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
        $user = User::find($data_array['id']);
        $merchantResult = $this->merchantService->updateMerchant($user, $data_array);
        return Response::json([$merchantResult]);
    }

    public function searchMerchant(Request $request)
    {
        $email = $request->email;
        $merchant = $this->merchantService->findMerchantByEmail($email);
        return view('pages.dashboard-filter', compact('merchant'));
    }
}
