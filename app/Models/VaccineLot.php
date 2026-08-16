<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VaccineLot extends Model
{
    protected $fillable = ['type', 'lot_number', 'received_at', 'weight_kg'];

    protected function casts(): array
    {
        return ['received_at' => 'date', 'weight_kg' => 'decimal:3'];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(VaccineUsage::class);
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
