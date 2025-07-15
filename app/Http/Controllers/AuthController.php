<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Claims\Custom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Step 1: Get OTP - always return success (no actual OTP returned)
     */
    public function getOtp(Request $request)
    {
        $request->validate(['phone' => 'required']);

        // Simulate OTP generation - in real case, send SMS
        return response()->json([
            'success' => true
        ]);
    }
public function validateOtp(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'otp' => 'required'
    ]);

    if ($request->otp !== '123456') {
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP'
        ], 401);
    }

    $user = User::where('phone', $request->phone)->first();

    if ($user) {
        // ✅ Existing user — issue normal token
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'success' => true,
            'is_new_user' => false,
            'token' => $token,
            'phone' => $user->phone
        ]);
    }

    // ✅ New user — simulate a guest user for token (same structure)
    // Create a fake user instance (not saved to DB)
    $fakeUser = new User([
        'phone' => $request->phone,
        'first_name' => 'guest',
        'last_name' => 'user',
        'email' => null
    ]);
    $fakeUser->id = 0; // <- ID 0 = placeholder for non-registered user

    $token = JWTAuth::fromUser($fakeUser);

    return response()->json([
        'success' => true,
        'is_new_user' => true,
        'token' => $token,
        'phone' => $request->phone
    ]);
}

    /**
     * Step 3: Register new user
     */
  public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string',
        'last_name'  => 'required|string',
        'email'      => 'nullable|email|unique:users',
        'phone'      => 'required|unique:users'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Create user
    $user = User::create($request->only('first_name', 'last_name', 'email', 'phone'));

    return response()->json([
        'success' => true,
        'message' => 'User registered successfully',
        'phone' => $user->phone
    ]);
}

}
