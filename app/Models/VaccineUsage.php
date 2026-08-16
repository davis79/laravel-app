<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccineUsage extends Model
{
    protected $fillable = ['vaccine_lot_id', 'recorded_by', 'production_number', 'quantity_kg', 'used_at'];

    protected function casts(): array
    {
        return ['quantity_kg' => 'decimal:3', 'used_at' => 'date'];
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(VaccineLot::class, 'vaccine_lot_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
