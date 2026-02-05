<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Auth\LoginRequest;
use App\Http\Requests\Api\User\Auth\RegisterRequest;
use App\Http\Requests\Api\User\Auth\UpdateRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        if ($request->has('image')) {
            $data['image'] = FileHelper::upload($request->file('image'), 'users');
        }
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return ApiResponse::success($user);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $user = User::where($field, $data['login'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ApiResponse::error(
                message: __('auth.failed'),
                code: 401
            );
        }

        $user->tokens()->delete();

        $token = $user->createToken(
            $data['device_name'] ?? 'user-api-token'
        )->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function update(UpdateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        unset($data['fashion_styles']);
        if ($request->image) {
            $data['image'] = FileHelper::upload($request->file('image'), 'users', $user->getRawOriginal('image'));
        }
        if ($request->password) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);

        if ($request->fashion_styles)
            $user->fashionStyles()->sync($request->fashion_styles, false);

        return ApiResponse::success($user);
    }

    public function profile(Request $request)
    {
        return ApiResponse::success($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success();
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';


        $user = User::where($field, $request->login)->first();

        if (!$user) {
            return ApiResponse::error(__('auth.not_found'));
        }

//        $otp = rand(1000, 9999);
        $otp = 1111;

        DB::beginTransaction();
        try {
            if ($field == 'phone') {
                // send phone otp
            } else {
                // send mail otp
            }

            $user->update([
                'otp' => $otp,
                'otp_expired_at' => now()->addMinutes(5)
            ]);
            if ($field == 'phone') {
                // send phone otp
            } else {
                // send mail otp
            }

            $user->update([
                'otp' => $otp,
                'otp_expired_at' => now()->addMinutes(5)
            ]);

            DB::commit();
            return ApiResponse::success();
        } catch (\Exception $e) {
            DB::rollback();
            return ApiResponse::error(message: $e->getMessage(), code: $e->getCode());
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'otp' => 'required|digits:4',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';


        $user = User::where($field, $request->login)->first();

        if (!$user) {
            return ApiResponse::error(__('auth.not_found'));
        }


        if ($user->otp != $request->otp) {
            return ApiResponse::error(__('auth.invalid_otp'));
        }

        if ($user->otp_expired_at < now()) {
            return ApiResponse::error(__('auth.expired'));
        }

        $user->update([
            'otp' => null,
            'otp_expired_at' => null,
//            'verified_at' => now()
        ]);

        $user->tokens()->delete();

        $token = $user->createToken(
            $data['device_name'] ?? 'user-api-token'
        )->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'user' => $user
        ]);
    }
}
