<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreezerVaccineAssignment extends Model
{
    protected $fillable = ['freezer_id', 'vaccine_type_id', 'recorded_by', 'started_at', 'ended_at'];

    protected function casts(): array
    {
        return ['started_at' => 'date', 'ended_at' => 'date'];
    }

    public function freezer(): BelongsTo
    {
        return $this->belongsTo(Freezer::class);
    }

    public function vaccineType(): BelongsTo
    {
        return $this->belongsTo(VaccineType::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
