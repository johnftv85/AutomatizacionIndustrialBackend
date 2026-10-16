<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Equipment extends Model
{
    // Cada equipo se autentica con su propio token de Sanctum
    use HasApiTokens;

    protected $fillable = [
        'enterprice_id', 'equipment_type_id', 'code', 'name',
        'location', 'active', 'last_reading_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'last_reading_at' => 'datetime',
    ];

    public function enterprice(): BelongsTo
    {
        return $this->belongsTo(Enterprice::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function readings(): HasMany
    {
        return $this->hasMany(Reading::class);
    }

    public function hourlyReadings(): HasMany
    {
        return $this->hasMany(HourlyReading::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /** Copia los componentes de la plantilla de su tipo (conserva la jerarquía). */
    public function applyTemplate(): void
    {
        if (! $this->type) {
            return;
        }

        $map = []; // id de plantilla => id del componente nuevo

        foreach ($this->type->componentTemplates()->orderBy('id')->get() as $template) {
            $component = $this->components()->create([
                'enterprice_id' => $this->enterprice_id,
                'parent_id' => $template->parent_id ? ($map[$template->parent_id] ?? null) : null,
                'item_id' => $template->item_id,
                'name' => $template->name,
                'installed_at' => now()->toDateString(),
                'lifespan_days' => $template->lifespan_days,
                'lifespan_hours' => $template->lifespan_hours,
                'max_temperature' => $template->max_temperature,
            ]);
            $map[$template->id] = $component->id;
        }
    }
}
