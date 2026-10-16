<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HourlyReading extends Model
{
    protected $fillable = [
        'enterprice_id', 'equipment_id', 'hour', 'samples',
        'temperature_min', 'temperature_max', 'temperature_avg',
        'ads_voltage_avg', 'dac_voltage_avg', 'rssi_avg', 'uptime_max',
    ];

    protected $casts = ['hour' => 'datetime'];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
