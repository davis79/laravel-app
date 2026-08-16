<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Freezer extends Model
{
    protected $fillable = ['number'];

    public function temperatureChecks(): HasMany
    {
        return $this->hasMany(FreezerTemperatureCheck::class);
    }

    public function cleanings(): HasMany
    {
        return $this->hasMany(FreezerCleaning::class);
    }

    public function vaccineAssignments(): HasMany
    {
        return $this->hasMany(FreezerVaccineAssignment::class);
    }

    public function currentAssignment(): HasOne
    {
        return $this->hasOne(FreezerVaccineAssignment::class)
            ->whereNull('ended_at')
            ->latestOfMany('started_at');
    }

    public function latestTemperatureCheck(): HasOne
    {
        return $this->temperatureChecks()->one()->latestOfMany('checked_at');
    }

    public function latestCleaning(): HasOne
    {
        return $this->cleanings()->one()->latestOfMany('cleaned_at');
    }

    protected function temperatureCheckedToday(): Attribute
    {
        return Attribute::get(fn () => $this->latestTemperatureCheck?->checked_at?->isToday() ?? false);
    }

    protected function cleaningIsValid(): Attribute
    {
        return Attribute::get(fn () => $this->latestCleaning?->valid_until?->endOfDay()->isFuture() ?? false);
    }
}
