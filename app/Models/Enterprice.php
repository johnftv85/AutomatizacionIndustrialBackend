<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enterprice extends Model
{
    // La migración crea la tabla en singular: "enterprice"
    protected $table = 'enterprice';

    protected $fillable = ['name', 'nit', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function equipmentTypes(): HasMany
    {
        return $this->hasMany(EquipmentType::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function erpConnection(): HasOne
    {
        return $this->hasOne(ErpConnection::class);
    }
}
