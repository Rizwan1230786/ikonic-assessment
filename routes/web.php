<?php

use App\Http\Controllers\AffilatesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Gest Routes 
Route::get('/', [HomeController::class, 'orders'])->name('orders');
Route::get('/signin', [HomeController::class, 'signin'])->name('login');
Route::post('/login-user', [HomeController::class, 'authenticate'])->name('authenticate');
Route::get('/create-merchant', HomeController::class)->name('createMerchant');
Route::post('/save-merchant-user', [MerchantController::class, 'register'])->name('createMerchant');
Route::post('/save-affilate-user', [AffilatesController::class, 'addAffilateUser'])->name('addAffilateUser');
Route::post('/create-order', WebhookController::class)->name('webhook');
Route::get('/create-affilate', [HomeController::class, 'createAffilate'])->name('createAffilate');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    //Merchant
    Route::get('/merchant/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/edit-merchant/{id}', [HomeController::class, 'editMerchant'])->name('editMerchant');
    Route::get('/add-affilates/{user_id}', [HomeController::class, 'addAffilates'])->name('addAffilates');
    Route::post('/edit-merchant-user', [MerchantController::class, 'updateMerchant'])->name('updateMerchant');
    Route::get('/search-merchant', [MerchantController::class, 'searchMerchant'])->name('searchMerchant');
    Route::get('/affilate-users', [AffilatesController::class, 'affilateUsers'])->name('affilateUsers');
    Route::get('/affilate-users-commission', [AffilatesController::class, 'commissionEarned'])->name('commissionEarned');
    Route::get('/payout', [WebhookController::class, 'payout'])->name('payout');
    Route::get('/merchant/order-stats', [MerchantController::class, 'orderStats'])->name('merchant.order-stats');
});
