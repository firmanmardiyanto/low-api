<?php

namespace App\Http\Controllers\API;

use App\Helpers\Constants;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();
        if (empty($providerUser->email || true)) {
            $params = [
                'error' => base64_encode('Please use account with valid email address.')
            ];
            return redirect(Constants::getLoginRedirectUrl() . '?' . http_build_query($params));
        }

        $user = User::updateOrCreate(
            ['email' => $providerUser->email],
            [
                'name' => $providerUser->name,
                'password' => Hash::make($providerUser->id),
            ]
        );
        
        if($user->photo == null) {
            $user->photo = $providerUser->avatar;
            $user->save();
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return redirect(Constants::getLoginRedirectUrl() . '?token=' . $token);
    }

    public function me()
    {
        return response()->json([
            'status' => true,
            'message' => 'User data',
            'user' => auth()->user()
        ]);
    }
}
