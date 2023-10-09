<?php

namespace App\Http\Controllers;

use App\Models\ForgetPasswordToken;
use App\Models\Product;
use App\Models\User;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function showProducts(User $user)
    {
        $products = $user->products;

        return response()->json($products);
    }

    public function profile()
    {
        $user = auth()->user();

        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'phone_number' => 'string|min:11|max:11|unique:users',
            'email' => 'string|email|max:255|unique:users',
        ]);

        $user->first_name = $request->input('first_name') ?? $user->first_name;
        $user->last_name = $request->input('last_name') ?? $user->last_name;
        $user->email = $request->input('email') ?? $user->email;
        $user->phone_number = $request->input('phone_number') ?? $user->phone_number;

        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function getUserProducts(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $userProducts = auth()->user()->products()->paginate($perPage);

        return response()->json($userProducts);
    }

    public function destory(User $user)
    {
        $user->delete();

        return response(['message' => 'User deleted succuflly']);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'phone_number' => 'string|min:11|max:11|unique:users',
            'email' => 'string|email|max:255|unique:users',
        ]);

        $user->first_name = $request->input('first_name') ?? $user->first_name;
        $user->last_name = $request->input('last_name') ?? $user->last_name;
        $user->email = $request->input('email') ?? $user->email;
        $user->phone_number = $request->input('phone_number') ?? $user->phone_number;

        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $token = md5($request->input('email') . time());
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        ForgetPasswordToken::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        $user->notify(new \App\Notifications\MailForgetPasswordToken([
            'url' => asset('api/user/reset_password/' . $token),
        ]));

        return response()->json(['message' => 'Password reset link sent successfully']);
    }

    public function resetPassword(Request $request, string $token)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $userCode = ForgetPasswordToken::where('token', $token)->orderBy('id', 'DESC')->first();

        if (!$userCode) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user = User::find($userCode->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        $userCode->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }

    public function updatePasswordByAdmin(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function createUserByAdmin(Request $request)
    {
        $isEmailRequired = is_null($request->input('phone_number')) ? "required|" : " ";
        $isPhoneRequired = is_null($request->input('email')) ? "required|" : " ";

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => $isPhoneRequired . 'string|min:11|max:11|unique:users',
            'email' => $isEmailRequired . 'string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::withoutEvents(function () use ($request) {
            $user = User::create([
                "first_name" => $request->input('first_name'),
                "last_name" => $request->input('last_name'),
                "email" => $request->input('email'),
                "phone_number" => $request->input('phone_number'),
                "password" => Hash::make($request->input('password'))
            ]);
            $user->save();
        });



        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully'
        ]);
    }
}
