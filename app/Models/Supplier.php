<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Supplier extends Model
{
    protected $fillable = [
        'enterprice_id', 'erp_code', 'name', 'contact', 'active', 'synced_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'synced_at' => 'datetime',
    ];

    public function enterprice(): BelongsTo
    {
        return $this->belongsTo(Enterprice::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_suppliers')
            ->using(ItemSupplier::class)
            ->withPivot(['supplier_code', 'price', 'currency', 'lead_time_days', 'preferred', 'synced_at'])
            ->withTimestamps();
    }
}
