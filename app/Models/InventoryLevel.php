<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLevel extends Model
{
    protected $fillable = ['item_id', 'warehouse', 'quantity', 'synced_at'];

    protected $casts = [
        'quantity' => 'decimal:3',
        'synced_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
