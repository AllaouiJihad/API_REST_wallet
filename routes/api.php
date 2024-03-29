<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register',[UserAuthController::class,'register']);
Route::post('/login',[UserAuthController::class,'login']);
Route::post('/logout',[UserAuthController::class,'logout'])->middleware('auth:sanctum');

Route::post('addSolde',[WalletController::class,'updateSolde'])->middleware('auth:sanctum');

Route::post('/AddWallet',[WalletController::class,'AddWallet'])->middleware('auth:sanctum');
Route::get('/wallets',[WalletController::class,'getWallets'])->middleware('auth:sanctum');

Route::post('/sendmoney',[TransactionController::class,'sendMoney'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
