<?php

namespace App\Http\Controllers;

use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Order;
use App\Models\User;
use App\Services\AffiliateService;
use App\Services\ApiService;
use App\Services\MerchantService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected ApiService $apiService,
    ) {
    }

    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data_array = $request->all();
        $this->orderService->processOrder($data_array);
        return Response::json([]);
    }
    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function payout()
    {
        $this->apiService->sendPayout();
        return redirect()->back();
    }
}
