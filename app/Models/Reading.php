<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reading extends Model
{
    // La tabla no tiene created_at/updated_at: usa recorded_at
    public $timestamps = false;

    protected $fillable = [
        'enterprice_id', 'equipment_id', 'temperature', 'ads_voltage',
        'gpio18', 'gpio25', 'analog_out', 'dac_voltage', 'rssi',
        'uptime', 'simulated', 'notes', 'recorded_at',
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'ads_voltage' => 'decimal:3',
        'analog_out' => 'decimal:2',
        'dac_voltage' => 'decimal:2',
        'gpio18' => 'boolean',
        'gpio25' => 'boolean',
        'simulated' => 'boolean',
        'recorded_at' => 'datetime',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function enterprice(): BelongsTo
    {
        return $this->belongsTo(Enterprice::class);
    }
}
