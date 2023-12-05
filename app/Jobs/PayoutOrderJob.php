<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PayoutOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order The order instance to be processed
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Use the API service to send a payout of the correct amount.
     * Note: The order status must be paid if the payout is successful, or remain unpaid in the event of an exception.
     *
     * @return void
     */
    public function handle(ApiService $apiService)
    {
        $this->order->update([
            'payout_status' => Order::STATUS_PAID
        ]);

        // Check if the order payout status is updated before sending the payout
        if ($this->order->wasChanged('payout_status') && $this->order->payout_status === Order::STATUS_PAID) {
            $apiService->sendPayout($this->order->affiliate->user->email, $this->order->commission_owed);
        }
        return $this->order;
    }
}
