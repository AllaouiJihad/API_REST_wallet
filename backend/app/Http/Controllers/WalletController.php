<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getId($id)
    {
        $query = DB::table('wallets')
            ->where('id', $id)
            ->select(DB::raw('CAST(id AS CHAR) AS id'))
            ->first();
        return $query->id;
    }
    /**
     * @OA\Info(
     * version="1.0.0",
     * title="Your API",
     * description="Description of your API",
     * @OA\Contact(
     * email="ahmed@example.com"
     * )
     * )
     *
     * @OA\Post(
     *     path="/api/AddWallet",
     *     tags={"Wallets"},
     *     summary="Add a new wallet",
     *     description="Creates a new wallet for the authenticated user.",
     *     operationId="addWallet",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Wallet data",
     *         @OA\JsonContent(
     *             required={"type", "solde"},
     *             @OA\Property(property="type", type="string", example="Savings"),
     *             @OA\Property(property="solde", type="string", example="100.00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wallet created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Wallet created successfully"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="wallet solde", type="string"),
     *             @OA\Property(property="uuid", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized or validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="erreur", type="object"),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="status", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="status", type="boolean", example=false)
     *         )
     *     )
     * )
     */

    public function AddWallet(Request $request)
    {
        try {
            $registerUserData = Validator::make($request->all(), [
                'type' => 'required|string',
                'solde' => 'required|string',
            ]);


            if ($registerUserData->fails()) {
                return response()->json([
                    'erreur' => $registerUserData->errors(),
                    'message' => 'validation failed',
                    'status' => false
                ], 401);
            }
            $wallet = Wallet::where('type', $request->type)->where('user_id', Auth::id())->first();
            if ($wallet != NULL) {
                return response()->json([
                    'message' => 'ce type de compte est deja existe',
                    'status' => false
                ], 401);
            }
            $wallet = Wallet::create([
                'type' => $request->type,
                'id' => Str::uuid(),
                'user_id' =>  Auth::id(),
                'solde' => $request->solde
            ]);

            return response()->json([
                'message' => 'wallet cree avec success',
                'status' => true,
                'wallet solde' => $wallet->solde,
                'uuid' => $this->getId($wallet->id)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function getWallets()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        return response()->json([
            'wallets' => $wallets,
            'status' => true
        ], 200);
    }

    public function updateSolde(Request $request)
    {
        try {
            $registerUserData = Validator::make($request->all(), [
                'solde' => 'required|numeric',
                'type' => 'required|string',
            ]);

            if ($registerUserData->fails()) {
                return response()->json([
                    'erreur' => $registerUserData->errors(),
                    'message' => 'validation failed',
                    'status' => false
                ], 401);
            }

            $wallet = Wallet::where('type', $request->type)->where('user_id', Auth::id())->first();
            if ($wallet == NULL) {
                return response()->json([
                    'erreur' => 'ce type de compte non existe',
                    'status' => false
                ], 401);
            }

            $wallet->solde = $wallet->solde + $request->solde;
            $wallet->save();

            return response()->json([
                'message' => 'solde ajouter avec success',
                'status' => true,
                'wallet solde' => $wallet->solde,
                'type' => $wallet->type,
                'uuid' => $this->getId($wallet->id)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }



    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
