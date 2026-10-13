<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commandes extends Model
{
    protected $table = 'commandes';

    protected $fillable = [
        'client_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'total',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produits::class, 'produit_id');
    }
}
