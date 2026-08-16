<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreezerCleaning extends Model
{
    protected $fillable = ['freezer_id', 'recorded_by', 'cleaned_at', 'valid_until'];

    protected function casts(): array
    {
        return ['cleaned_at' => 'datetime', 'valid_until' => 'date'];
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
