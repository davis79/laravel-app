<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FruitFlavor extends Model
{
    protected $fillable = ['fruit_product_id', 'name', 'color'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(FruitProduct::class, 'fruit_product_id');
    }

    public function containers(): HasMany
    {
        return $this->hasMany(FruitContainer::class);
    }
}
