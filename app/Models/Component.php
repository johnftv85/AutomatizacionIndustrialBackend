<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    protected $fillable = [
        'enterprice_id', 'equipment_id', 'parent_id', 'item_id', 'name',
        'installed_at', 'lifespan_days', 'lifespan_hours',
        'max_temperature', 'active',
    ];

    protected $casts = [
        'installed_at' => 'date',
        'active' => 'boolean',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }
}
