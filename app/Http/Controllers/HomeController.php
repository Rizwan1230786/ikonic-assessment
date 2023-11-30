<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('create-merchant');
    }
    public function orders()
    {
        $merchants = Merchant::all();
        return view('orders', compact('merchants'));
    }
    public function createAffilate()
    {
        $users = User::with('merchant')->where(['type' => User::TYPE_MERCHANT])->get();
        return view('pages.create-affilate', compact('users'));
    }
    public function editMerchant($id)
    {
        $user = User::with('merchant')->where('id', $id)->first();
        return view('pages.edit-merchant', compact('user'));
    }
    public function signin()
    {
        return view('pages.signin');
    }
    public function dashboard()
    {
        $user = User::with('merchant')->where(['type' => User::TYPE_MERCHANT, 'id' => auth()->user()->id])->first();
        return view('pages.dashboard', compact('user'));
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        if (!empty($user) && $user->type == User::TYPE_MERCHANT && Auth::attempt($credentials)) {
            $json['status'] = true;
            $json['message'] = 'User logged in successfully.';
            return response($json);
        } elseif (!empty($user) && $user->type == User::TYPE_MERCHANT) {
            $json['status'] = true;
            $json['message'] = 'Please login as a merchant.';
            return response($json);
        }
        $json['status'] = false;
        $json['message'] = 'Email or password is incorrect.';
        return response($json);
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function addAffilates($user_id)
    {
        $user = User::with('merchant')->find($user_id);
        return view('pages.add-affilates', compact('user'));
    }
}
