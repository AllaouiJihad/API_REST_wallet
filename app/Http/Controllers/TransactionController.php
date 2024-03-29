<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function sendMoney(Request $request){
        try {
            $sendMoneyData = Validator::make($request->all(), [
                'montant' => 'required|numeric',
                'expediteur' => 'required|string',
                'type' => 'required|string',
            ]);

            if ($sendMoneyData->fails()) {
                return response()->json([
                    'erreur' => $sendMoneyData->errors(),
                    'message' => 'validation failed',
                    'status' => false
                ], 401);
            }


            $expediteur = User::where('name', $request['expediteur'])->first();
            if($expediteur == NULL){
                return response()->json([
                   'status' => false,
                   'message' => 'expediteur inconnu'
                ], 401);
            }
            
            $walletSender = 
            $wallet = Wallet::with('expediteur')->where()

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
               'montant' => $request['montant'],
               'expiration' => $request['expiration']
            ]);

            return response()->json([
                'message' => 'montant'
            ])


            
        } catch (\Throwable $th) {
              return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
     }
    
}
