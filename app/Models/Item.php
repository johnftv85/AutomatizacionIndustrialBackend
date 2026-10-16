<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'enterprice_id', 'erp_code', 'description', 'unit', 'active', 'synced_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'synced_at' => 'datetime',
    ];

    public function enterprice(): BelongsTo
    {
        return $this->belongsTo(Enterprice::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'item_suppliers')
            ->using(ItemSupplier::class)
            ->withPivot(['supplier_code', 'price', 'currency', 'lead_time_days', 'preferred', 'synced_at'])
            ->withTimestamps();
    }

    public function inventoryLevels(): HasMany
    {
        return $this->hasMany(InventoryLevel::class);
    }

    /** Existencias totales sumando todas las bodegas. */
    public function totalStock(): float
    {
        return (float) $this->inventoryLevels()->sum('quantity');
    }
}
