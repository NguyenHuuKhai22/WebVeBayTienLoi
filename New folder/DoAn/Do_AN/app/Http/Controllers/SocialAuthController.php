<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = NguoiDung::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = NguoiDung::create([
                    'ho_ten' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user',
                    'first_time_discount' => 1,
                    'blocked_until' => null,
                ]);
            }

            Auth::login($user);

            return redirect()->route('vietnam-airlines');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập bằng Google thất bại!');
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            $user = NguoiDung::where('email', $facebookUser->email)->first();

            if (!$user) {
                $user = NguoiDung::create([
                    'ho_ten' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user',
                    'first_time_discount' => 1,
                    'blocked_until' => null,
                ]);
            }

            Auth::login($user);

            return redirect()->route('vietnam-airlines');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập bằng Facebook thất bại!');
        }
    }
} 