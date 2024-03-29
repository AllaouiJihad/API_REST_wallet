<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\User;
use App\Models\Wallet;
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
            
            $solde = Wallet::select('solde')
                        ->join('users', 'users.id', '=', 'wallets.user_id')
                        ->where('users.name', $request['expediteur'])
                        ->where('wallets.type', $request['type'])
                        ->first(); 

            if($solde < $request['montant']){
                return response()->json([
                   'status' => false,
                   'message' =>'solde insuffisant'
                ], 401);
            }
            

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
               'montant' => $request['montant'],
               'expiration' => $expediteur->id
            ]);

            $newsolde = $solde - $request['montant'];
            Wallet::where('user_id', Auth::id())
                ->where('type', $request['type'])
                ->update(['solde' => $newsolde]);
        
               

            return response()->json([
               'message' => 'transaction envoyée avec success',
               'status' => true,
            ], 201);

            


            
        } catch (\Throwable $th) {
              return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
     }

     public function getTransaction(){
        $transaction = Transaction::where('user_id', Auth::id())->get();

        return response()->json([
            'transactions' => $transaction,
           'status' => true
        ], 201);
     }
    
}
