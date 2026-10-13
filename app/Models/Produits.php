<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produits extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'stock',
    ];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commandes::class, 'produit_id');
    }
}
