<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemSupplier extends Pivot
{
    public $incrementing = true;

    protected $casts = [
        'price' => 'decimal:2',
        'preferred' => 'boolean',
        'synced_at' => 'datetime',
    ];
}
