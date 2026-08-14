<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FruitContainer extends Model
{
    protected $fillable = ['fruit_flavor_id', 'container_number', 'received_at', 'expires_at', 'weight_kg'];

    protected function casts(): array
    {
        return ['received_at' => 'date', 'expires_at' => 'date', 'weight_kg' => 'decimal:3'];
    }

    public function flavor(): BelongsTo
    {
        return $this->belongsTo(FruitFlavor::class, 'fruit_flavor_id');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(FruitContainerUsage::class);
    }

    protected function remainingWeight(): Attribute
    {
        return Attribute::get(fn () => max(0, (float) $this->weight_kg - (float) $this->usages_sum_quantity_kg));
    }

    protected function isActive(): Attribute
    {
        return Attribute::get(fn () => $this->remaining_weight > 0);
    }
}
