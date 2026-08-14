<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FruitProduct extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function flavors(): HasMany
    {
        return $this->hasMany(FruitFlavor::class);
    }
}
