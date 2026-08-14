<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FruitContainerUsage extends Model
{
    protected $fillable = ['fruit_container_id', 'recorded_by', 'production_name', 'production_number', 'quantity_kg', 'used_at', 'notes'];

    protected function casts(): array
    {
        return ['quantity_kg' => 'decimal:3', 'used_at' => 'datetime'];
    }

    public function container(): BelongsTo { return $this->belongsTo(FruitContainer::class, 'fruit_container_id'); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
}
