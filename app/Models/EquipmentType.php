<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentType extends Model
{
    protected $fillable = ['enterprice_id', 'name', 'description'];

    public function enterprice(): BelongsTo
    {
        return $this->belongsTo(Enterprice::class);
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function componentTemplates(): HasMany
    {
        return $this->hasMany(ComponentTemplate::class);
    }
}
