<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreezerTemperatureCheck extends Model
{
    protected $fillable = ['freezer_id', 'recorded_by', 'temperature_c', 'checked_at'];

    protected function casts(): array
    {
        return ['temperature_c' => 'decimal:2', 'checked_at' => 'datetime'];
    }

    public function freezer(): BelongsTo
    {
        return $this->belongsTo(Freezer::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
