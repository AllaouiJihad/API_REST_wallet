<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Transaction extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'montant',
        'expediteur',
    ];

    public function user(){
        return $this->hasOne(Wallet::class);
    }

    public function expediteur(){
        return $this->hasOne(Wallet::class, 'expediteur');
    }
   
}
