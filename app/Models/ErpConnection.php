<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErpConnection extends Model
{
    protected $fillable = [
        'enterprice_id', 'type', 'base_url', 'credentials',
        'active', 'last_synced_at', 'last_error',
    ];

    // Las credenciales nunca se exponen al serializar
    protected $hidden = ['credentials'];

    protected $casts = [
        'credentials' => 'encrypted:array',
        'active' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    public function enterprice(): BelongsTo
    {
        return $this->belongsTo(Enterprice::class);
    }
}
