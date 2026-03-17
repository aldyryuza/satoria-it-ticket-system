<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

use Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = [
            'statusCode' => 200,
            'is_valid' => false,
            'username' => $request->username,
            'password' => '******',
            'message' => 'Failed',
            'data' => null,
        ];

        try {
            $user = User::with(['company', 'division', 'roles.permissions'])->where('username', $request->username)->where('is_active', 1)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                $result['message'] = 'Username atau Password salah';
                return response()->json($result, 401);
            }
            // dd($user->toArray());
            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            $result['is_valid'] = true;
            $result['message'] = 'Success';
            $result['data'] = $user;
            $result['token'] = $token;

            return response()->json($result);
        } catch (Exception $e) {
            $result['statusCode'] = 500;
            $result['message'] = $e->getMessage();
            return response()->json($result);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'statusCode' => 200,
                'message' => 'Successfully logged out',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Failed to logout',
            ]);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
