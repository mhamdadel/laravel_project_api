<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterPhoneRequest;
use App\Http\Requests\RegisterWithEmailRequest;
use App\Models\UserCode;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['only' => ['login', 'registerService', 'active', 'register_with_email', 'register_with_phone']]);
        $this->middleware('auth:api', ['except' => ['login', 'registerService', 'active', 'register_with_email', 'register_with_phone']]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (isset($credentials['email'])) {
            $user = User::where('email', $credentials['email'])->first();
        } else {
            $user = User::where('phone_number', $credentials['email'])->first();
        }

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'wrong credentials',
            ], 401);
        }

        $token = $user->createToken('user', ['app:all'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    public function register_with_email(RegisterWithEmailRequest $request)
    {
        return $this->registerService($request);
    }

    public function register_with_phone(RegisterPhoneRequest $request)
    {
        return $this->registerService($request, $email_verified_at = date('Y:m:d H:i:s'));
    }

    public function registerService(Request $request, $email_verified_at = null)
    {
        $credentials = $request->validated();

        $credentials['password'] = Hash::make($credentials['password']);

        $user = User::create($credentials);

        $user->email_verified_at = $email_verified_at;

        $user->save();

        $token = $user->createToken('user', ['app:all'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function profile()
    {
        return Auth::user();
    }

    public function active(Request $request)
    {
        $email = $request->email;
        $code = $request->code;

        $user = User::where('email', $email)->first();

        if (!is_null($user->email_verified_at))
            return response(['message' => 'the mail aleady vervied'], 400);

        if (!$user)
            return response(['message' => 'email is wrong'], 404);

        $activationCode = UserCode::where('user_id', $user->id)->where('code', $code)->first();

        if (!$activationCode)
            return response(['message' => 'the activation code is wrong'], 404);

        $date = Carbon::parse($activationCode->created_at);

        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        if ($diff >= 7)
            return response(['message' => 'Invaild code you must active it before 7 days'], 401);

        $user->email_verified_at = date('Y:m:d H:i:s');
        $user->save();

        $activationCode->delete();

        return response(['message' => 'activation done']);
    }
}
