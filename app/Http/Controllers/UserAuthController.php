<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $registerUserData = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|min:8'
            ]);


            if ($registerUserData->fails()) {
                return response()->json([
                    'erreur' => $registerUserData->errors(),
                    'message' => 'validation failed',
                    'status' => false
                ], 401);
            }

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
            $wallet = Wallet::create([
                'type' => 'standard',
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'solde' => 0
            ]);

            

            return response()->json([   
                'token' => $user->createToken("API Token")->plainTextToken,
                'message' => 'User Created',
                'status' => true,
                'wallet solde' => $wallet->solde,
                'uuid' => Uuid::fromBytes($wallet->id)->toString()
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }

    
    public function login(Request $request)
    {
        try {
            $registerUserData = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|min:8'
            ]);
            if ($registerUserData->fails()) {
                return response()->json([
                    'erreur' => $registerUserData->errors(),
                    'message' => 'validation failed',
                    'status' => false
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'wallets' => $user->wallets
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }
}
