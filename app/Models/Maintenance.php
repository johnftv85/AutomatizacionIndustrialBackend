<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    public const ORIGIN_MANUAL = 'manual';
    public const ORIGIN_TIME = 'time';
    public const ORIGIN_READING = 'reading';

    public const STATUS_PROPOSED = 'proposed';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'enterprice_id', 'equipment_id', 'component_id', 'item_id', 'supplier_id',
        'title', 'description', 'origin', 'status',
        'tentative_date', 'completed_date', 'erp_reference',
    ];

    protected $casts = [
        'tentative_date' => 'date',
        'completed_date' => 'date',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
